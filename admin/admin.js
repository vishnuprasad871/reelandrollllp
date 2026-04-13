/**
 * Admin Dashboard JavaScript
 * Handles authentication, groups, image upload, bulk operations
 */

const API_BASE = '/api';
let currentFilter  = 'all';
let currentGallery = [];
let allGroups      = [];
let selectedIds    = new Set();

// ─── Init ───────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            try { await fetch(`${API_BASE}/logout.php`, { method: 'POST' }); } catch (_) {}
            window.location.href = '/admin';
        });
    }

    checkAuth();
    setupUploadForm();
    setupFilterTabs();
    setupGroupForm();
    setupEditForm();
});

async function checkAuth() {
    if (!window.location.pathname.includes('/admin/dashboard')) return;
    try {
        const res  = await fetch(`${API_BASE}/auth.php`);
        const data = await res.json();
        if (!data.authenticated) { window.location.href = '/admin'; return; }
        document.getElementById('userInfo').textContent = `Welcome, ${data.user.username}`;
        await loadGroups();
        loadGallery();
    } catch (_) {
        window.location.href = '/admin';
    }
}

// ─── Groups ─────────────────────────────────────────────────────────────────

async function loadGroups() {
    try {
        const res  = await fetch(`${API_BASE}/groups.php`);
        const data = await res.json();
        if (data.success) {
            allGroups = data.data;
            renderGroups(allGroups);
            populateGroupDropdowns(allGroups);
        }
    } catch (e) {
        console.error('Failed to load groups', e);
    }
}

function renderGroups(groups) {
    const el = document.getElementById('groupsList');
    if (!el) return;

    if (groups.length === 0) {
        el.innerHTML = '<p style="color:#888;padding:1rem 0;">No groups yet. Create one above.</p>';
        return;
    }

    el.innerHTML = groups.map(g => `
        <div class="group-card">
            <div class="group-cover">
                ${g.cover_url
                    ? `<img src="${g.cover_url}" alt="${escHtml(g.title)}">`
                    : `<div class="group-cover-placeholder">📁</div>`}
            </div>
            <div class="group-info">
                <div class="group-title">${escHtml(g.title)}</div>
                <div class="group-meta">${g.image_count} photo${g.image_count != 1 ? 's' : ''} · #${g.sort_order}</div>
            </div>
            <div class="group-actions">
                <button class="btn-icon" onclick="openGroupModal(${g.id})" title="Edit">✏️</button>
                <button class="btn-icon" onclick="deleteGroup(${g.id})" title="Delete">🗑️</button>
            </div>
        </div>
    `).join('');
}

function populateGroupDropdowns(groups) {
    const options = groups.map(g => `<option value="${g.id}">${escHtml(g.title)}</option>`).join('');

    ['uploadGroup', 'bulkGroupSelect', 'editGroup'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        const placeholder = id === 'bulkGroupSelect'
            ? '<option value="">Unassign from group</option>'
            : '<option value="">No Group</option>';
        el.innerHTML = placeholder + options;
    });
}

function openGroupModal(id = null) {
    const modal = document.getElementById('groupModal');
    document.getElementById('groupId').value         = id || '';
    document.getElementById('groupTitle').value      = '';
    document.getElementById('groupSortOrder').value  = 0;
    document.getElementById('groupCoverImage').value = '';
    document.getElementById('groupModalTitle').textContent = id ? 'Edit Group' : 'New Group';
    document.getElementById('groupMessage').style.display = 'none';

    if (id) {
        const g = allGroups.find(x => x.id == id);
        if (g) {
            document.getElementById('groupTitle').value      = g.title;
            document.getElementById('groupSortOrder').value  = g.sort_order;
            document.getElementById('groupCoverImage').value = g.cover_image_id || '';
        }
    }

    modal.style.display = 'flex';
}

function closeGroupModal() {
    document.getElementById('groupModal').style.display = 'none';
}

function setupGroupForm() {
    const form = document.getElementById('groupForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id         = document.getElementById('groupId').value;
        const msgEl      = document.getElementById('groupMessage');
        const submitBtn  = form.querySelector('button[type="submit"]');

        const payload = {
            title:          document.getElementById('groupTitle').value.trim(),
            sort_order:     parseInt(document.getElementById('groupSortOrder').value) || 0,
            cover_image_id: parseInt(document.getElementById('groupCoverImage').value) || null,
        };

        if (id) payload.id = parseInt(id);

        submitBtn.disabled = true;
        try {
            const res  = await fetch(`${API_BASE}/groups.php`, {
                method:  id ? 'PUT' : 'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                closeGroupModal();
                await loadGroups();
            } else {
                showMessage(msgEl, data.error || 'Failed to save group', 'error');
            }
        } catch (_) {
            showMessage(msgEl, 'Network error', 'error');
        } finally {
            submitBtn.disabled = false;
        }
    });
}

async function deleteGroup(id) {
    if (!confirm('Delete this group? Photos will remain but will be unassigned.')) return;
    try {
        const res  = await fetch(`${API_BASE}/groups.php?id=${id}`, { method: 'DELETE' });
        const data = await res.json();
        if (data.success) await loadGroups();
        else alert('Failed to delete group: ' + (data.error || ''));
    } catch (e) { alert('Network error'); }
}

// ─── Upload ─────────────────────────────────────────────────────────────────

function setupUploadForm() {
    const uploadForm = document.getElementById('uploadForm');
    const uploadArea = document.getElementById('uploadArea');
    const imageFile  = document.getElementById('imageFile');
    const imagePreview = document.getElementById('imagePreview');

    if (!uploadForm) return;

    imageFile.addEventListener('change', e => {
        if (e.target.files.length > 0) previewImage(e.target.files[0]);
    });

    uploadArea.addEventListener('click', e => {
        if (!e.target.closest('.btn-remove-preview')) imageFile.click();
    });

    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', e => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            imageFile.files = e.dataTransfer.files;
            previewImage(e.dataTransfer.files[0]);
        }
    });

    document.querySelector('.btn-remove-preview')?.addEventListener('click', e => {
        e.stopPropagation();
        imageFile.value = '';
        imagePreview.style.display = 'none';
        uploadArea.querySelector('.upload-placeholder').style.display = 'flex';
    });

    uploadForm.addEventListener('submit', handleUpload);
}

function previewImage(file) {
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('imagePreview');
        preview.querySelector('img').src = e.target.result;
        preview.style.display = 'block';
        document.getElementById('uploadArea').querySelector('.upload-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

async function handleUpload(e) {
    e.preventDefault();
    const form      = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const loader    = submitBtn.querySelector('.loader');
    const btnText   = submitBtn.querySelector('span');
    const message   = document.getElementById('uploadMessage');

    submitBtn.disabled  = true;
    btnText.style.display  = 'none';
    loader.style.display   = 'inline-block';
    message.style.display  = 'none';

    try {
        const res  = await fetch(`${API_BASE}/gallery.php`, { method: 'POST', body: new FormData(form) });
        const data = await res.json();
        if (data.success) {
            showMessage(message, 'Image uploaded successfully!', 'success');
            form.reset();
            document.getElementById('imagePreview').style.display = 'none';
            document.querySelector('.upload-placeholder').style.display = 'flex';
            loadGallery();
        } else {
            throw new Error(data.error || 'Upload failed');
        }
    } catch (err) {
        showMessage(message, err.message, 'error');
    } finally {
        submitBtn.disabled = false;
        btnText.style.display  = 'inline';
        loader.style.display   = 'none';
    }
}

// ─── Gallery ─────────────────────────────────────────────────────────────────

async function loadGallery(category = 'all') {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;
    grid.innerHTML = '<div class="loader-container"><div class="loader"></div><p>Loading gallery…</p></div>';

    try {
        const url  = category === 'all' ? `${API_BASE}/gallery.php` : `${API_BASE}/gallery.php?category=${category}`;
        const res  = await fetch(url);
        const data = await res.json();
        if (data.success) {
            currentGallery = data.data;
            clearSelection();
            renderGallery(data.data);
        } else {
            throw new Error(data.error || 'Failed to load');
        }
    } catch (err) {
        grid.innerHTML = `<div class="error-message">Error: ${err.message}</div>`;
    }
}

function renderGallery(items) {
    const grid = document.getElementById('galleryGrid');
    if (items.length === 0) {
        grid.innerHTML = '<div class="empty-state">No images found</div>';
        return;
    }

    grid.innerHTML = items.map(item => {
        const groupName = allGroups.find(g => g.id == item.group_id)?.title || '';
        const isSelected = selectedIds.has(item.id);
        return `
        <div class="gallery-admin-item ${isSelected ? 'selected' : ''}" data-id="${item.id}">
            <div class="image-checkbox-wrap" onclick="toggleSelection(${item.id})">
                <input type="checkbox" class="image-checkbox" ${isSelected ? 'checked' : ''} onclick="event.stopPropagation();toggleSelection(${item.id})">
            </div>
            <div class="image-wrapper">
                <img src="${item.image_url}" alt="${escHtml(item.alt_text || 'Gallery image')}">
                <div class="image-overlay">
                    <button class="btn-icon" onclick="editImage(${item.id})" title="Edit">✏️</button>
                    <button class="btn-icon" onclick="deleteSingle(${item.id})" title="Delete">🗑️</button>
                </div>
            </div>
            <div class="image-info">
                <span class="category-badge category-${item.category}">${item.category}</span>
                ${groupName ? `<span class="group-badge">📁 ${escHtml(groupName)}</span>` : ''}
                <p class="image-alt">${escHtml(item.alt_text || 'No description')}</p>
                <p class="image-sort">Sort: ${item.sort_order ?? 0}</p>
            </div>
        </div>`;
    }).join('');
}

// ─── Filter Tabs ─────────────────────────────────────────────────────────────

function setupFilterTabs() {
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentFilter = tab.dataset.filter;
            loadGallery(currentFilter);
        });
    });
}

// ─── Bulk Selection ──────────────────────────────────────────────────────────

function toggleSelection(id) {
    if (selectedIds.has(id)) selectedIds.delete(id);
    else selectedIds.add(id);
    updateBulkBar();

    // Update checkbox + card visual
    const card = document.querySelector(`.gallery-admin-item[data-id="${id}"]`);
    if (card) {
        card.classList.toggle('selected', selectedIds.has(id));
        const cb = card.querySelector('.image-checkbox');
        if (cb) cb.checked = selectedIds.has(id);
    }
}

function updateBulkBar() {
    const bar = document.getElementById('bulkActionBar');
    const cnt = document.getElementById('selectedCount');
    if (!bar) return;
    if (selectedIds.size > 0) {
        bar.style.display = 'flex';
        cnt.textContent = `${selectedIds.size} selected`;
    } else {
        bar.style.display = 'none';
    }
}

function clearSelection() {
    selectedIds.clear();
    updateBulkBar();
    document.querySelectorAll('.gallery-admin-item').forEach(c => {
        c.classList.remove('selected');
        const cb = c.querySelector('.image-checkbox');
        if (cb) cb.checked = false;
    });
}

async function bulkAssignGroup() {
    const group_id = document.getElementById('bulkGroupSelect').value;
    if (!confirm(`Assign ${selectedIds.size} image(s) to selected group?`)) return;
    try {
        const res  = await fetch(`${API_BASE}/gallery.php?action=bulk_assign`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ ids: [...selectedIds], group_id: group_id || null })
        });
        const data = await res.json();
        if (data.success) { clearSelection(); loadGallery(currentFilter); await loadGroups(); }
        else alert('Failed: ' + (data.error || ''));
    } catch (_) { alert('Network error'); }
}

async function bulkDelete() {
    if (!confirm(`Permanently delete ${selectedIds.size} image(s)?`)) return;
    try {
        const res  = await fetch(`${API_BASE}/gallery.php?action=bulk_delete`, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ ids: [...selectedIds] })
        });
        const data = await res.json();
        if (data.success) { clearSelection(); loadGallery(currentFilter); }
        else alert('Failed: ' + (data.error || ''));
    } catch (_) { alert('Network error'); }
}

// ─── Single Edit / Delete ────────────────────────────────────────────────────

function editImage(id) {
    const item = currentGallery.find(i => i.id == id);
    if (!item) return;

    document.getElementById('editImageId').value   = item.id;
    document.getElementById('editCategory').value  = item.category;
    document.getElementById('editAltText').value   = item.alt_text || '';
    document.getElementById('editSortOrder').value = item.sort_order ?? 0;

    const editGroup = document.getElementById('editGroup');
    if (editGroup) editGroup.value = item.group_id || '';

    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function setupEditForm() {
    const form = document.getElementById('editForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const payload = {
            id:           parseInt(document.getElementById('editImageId').value),
            category:     document.getElementById('editCategory').value,
            alt_text:     document.getElementById('editAltText').value,
            sort_order:   parseInt(document.getElementById('editSortOrder').value) || 0,
            group_id:     document.getElementById('editGroup').value || null
        };

        try {
            const res  = await fetch(`${API_BASE}/gallery.php?id=${payload.id}`, {
                method:  'PUT',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) { closeEditModal(); loadGallery(currentFilter); }
            else alert('Update failed: ' + (data.error || ''));
        } catch (_) { alert('Network error'); }
    });
}

async function deleteSingle(id) {
    if (!confirm('Delete this image?')) return;
    try {
        const res  = await fetch(`${API_BASE}/gallery.php?id=${id}`, { method: 'DELETE' });
        const data = await res.json();
        if (data.success) loadGallery(currentFilter);
        else alert('Delete failed: ' + (data.error || ''));
    } catch (_) { alert('Network error'); }
}

// ─── Helpers ─────────────────────────────────────────────────────────────────

function showMessage(el, text, type) {
    el.textContent  = text;
    el.className    = `message ${type}`;
    el.style.display = 'block';
    setTimeout(() => { el.style.display = 'none'; }, 5000);
}

function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
