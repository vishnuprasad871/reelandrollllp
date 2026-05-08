/**
 * Gallery Frontend
 * Groups folder view + image grid view with lightbox
 */

const API_BASE        = '/api';
let currentGroupId    = null;
let currentGroupTitle = '';

// ─── Spin keyframe (reused) ───────────────────────────────────────────────
if (!document.getElementById('rnr-spin')) {
    const s = document.createElement('style');
    s.id = 'rnr-spin';
    s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
    document.head.appendChild(s);
}

// ─── Init ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('galleryGrid')) return;

    const initGroup = (typeof INIT_GROUP_ID !== 'undefined') ? INIT_GROUP_ID : null;

    if (initGroup) {
        openGroupById(initGroup);
    } else {
        loadGroupFolders();
    }
});

// ─── Groups Folder View ───────────────────────────────────────────────────

async function loadGroupFolders() {
    const grid = document.getElementById('groupsFolderGrid');
    if (!grid) return;

    try {
        const res  = await fetch(`${API_BASE}/groups.php`);
        const data = await res.json();

        if (!data.success || data.data.length === 0) {
            grid.innerHTML = `
                <div style="grid-column:1/-1;text-align:center;padding:4rem;color:#888;">
                    <div style="font-size:3rem;margin-bottom:1rem;">📁</div>
                    <p style="font-size:1.1rem;">No photo groups yet.<br>Check back soon!</p>
                </div>`;
            return;
        }

        renderGroupFolders(data.data);
    } catch (_) {
        grid.innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:4rem;color:#888;">
                <div style="font-size:3rem;margin-bottom:1rem;">📁</div>
                <p style="font-size:1.1rem;">No photo groups yet.<br>Check back soon!</p>
            </div>`;
    }
}

function renderGroupFolders(groups) {
    const grid = document.getElementById('groupsFolderGrid');
    if (!grid) return;

    grid.innerHTML = groups.map(g => `
        <div class="group-folder reveal active" onclick="openGroup(${g.id}, '${escJs(g.title)}')">
            <div class="group-folder-thumb">
                ${g.cover_url
                    ? `<img src="${g.cover_url}" alt="${escHtml(g.title)}" loading="lazy">`
                    : `<div class="group-folder-placeholder">📁</div>`}
                <div class="group-folder-overlay">
                    <span>View Photos →</span>
                </div>
            </div>
            <div class="group-folder-info">
                <h3>${escHtml(g.title)}</h3>
                <p>${g.image_count} photo${g.image_count != 1 ? 's' : ''}</p>
            </div>
        </div>
    `).join('');
}

function openGroup(groupId, groupTitle) {
    currentGroupId    = groupId;
    currentGroupTitle = groupTitle;

    const url = new URL(window.location);
    url.searchParams.set('group', groupId);
    history.pushState({}, '', url);

    showImagesView(groupTitle);
    loadImages();
}

async function openGroupById(groupId) {
    try {
        const res  = await fetch(`${API_BASE}/groups.php`);
        const data = await res.json();
        const g    = data.success ? data.data.find(x => x.id == groupId) : null;
        openGroup(groupId, g ? g.title : 'Group');
    } catch (_) {
        openGroup(groupId, 'Group');
    }
}

function showGroupsView() {
    currentGroupId    = null;
    currentGroupTitle = '';

    const url = new URL(window.location);
    url.searchParams.delete('group');
    history.pushState({}, '', url);

    document.getElementById('groupsView').style.display  = '';
    document.getElementById('imagesView').style.display  = 'none';

    // Reload folder grid in case groups changed
    loadGroupFolders();
}

function showImagesView(label) {
    document.getElementById('groupsView').style.display = 'none';
    document.getElementById('imagesView').style.display = '';

    const labelEl = document.getElementById('currentGroupLabel');
    if (labelEl) labelEl.textContent = label || '';
}

// ─── Image Loading ────────────────────────────────────────────────────────

async function loadImages() {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;

    grid.innerHTML = `
        <div style="grid-column:1/-1;text-align:center;padding:3rem;">
            <div style="width:36px;height:36px;border:3px solid #ddd;border-top-color:#FF4D00;border-radius:50%;margin:0 auto 1rem;animation:spin .8s linear infinite;"></div>
            <p style="color:#888;">Loading photos…</p>
        </div>`;

    try {
        let url = `${API_BASE}/gallery.php`;
        if (currentGroupId) url += `?group_id=${currentGroupId}`;

        const res  = await fetch(url);
        const data = await res.json();

        if (data.success) {
            renderImages(data.data);
        } else {
            throw new Error(data.error || 'Failed to load');
        }
    } catch (_) {
        grid.innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:2rem;color:#d63031;">
                Unable to load photos. Please try again later.
            </div>`;
    }
}

function renderImages(items) {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;

    if (items.length === 0) {
        grid.innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#888;font-size:1.1rem;">
                No photos found in this group.
            </div>`;
        return;
    }

    grid.innerHTML = items.map(item => `
        <div class="gallery-item reveal active" data-category="${item.category}">
            <img src="${item.image_url}" alt="${escHtml(item.alt_text || 'Gallery image')}" loading="lazy">
        </div>
    `).join('');

    setTimeout(() => {
        grid.querySelectorAll('.gallery-item').forEach(el => el.classList.add('active'));
    }, 80);

    setupLightbox();
}

// ─── Lightbox ─────────────────────────────────────────────────────────────

function setupLightbox() {
    const modal   = document.getElementById('lightboxModal');
    const img     = document.getElementById('lightboxImg');
    const closeBtn = document.getElementById('lightboxClose');
    if (!modal || !img) return;

    // Clone grid node to remove stale listeners
    const grid    = document.getElementById('galleryGrid');
    const newGrid = grid.cloneNode(true);
    grid.parentNode.replaceChild(newGrid, grid);

    newGrid.addEventListener('click', e => {
        const item = e.target.closest('.gallery-item');
        if (item) {
            const src = item.querySelector('img')?.src;
            if (src) { img.src = src; modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
        }
    });

    const close = () => { modal.classList.remove('active'); document.body.style.overflow = ''; };
    closeBtn.onclick    = close;
    modal.onclick       = e => { if (e.target === modal) close(); };
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('active')) close(); });
}

// ─── Default Fallback ─────────────────────────────────────────────────────

function loadDefaultGallery() {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;
    const defaultImages = [
        { src: 'assets/portfolio_wedding_1770402245749.png',  alt: 'Wedding Photography',   category: 'wedding'   },
        { src: 'assets/portfolio_portrait_1770402268053.png', alt: 'Portrait Photography',  category: 'portrait'  },
        { src: 'assets/about_photographer_1770402230073.png', alt: 'Professional Portrait', category: 'portrait'  },
        { src: 'assets/portfolio_landscape_1770402286898.png',alt: 'Landscape Photography', category: 'landscape' },
        { src: 'assets/hero_background_1770402213477.png',    alt: 'Sunset Photography',    category: 'landscape' },
        { src: 'assets/portfolio_event_1770402304065.png',    alt: 'Event Photography',     category: 'event'     },
    ];
    grid.innerHTML += defaultImages.map(i => `
        <div class="gallery-item reveal active" data-category="${i.category}">
            <img src="${i.src}" alt="${i.alt}" loading="lazy">
        </div>`).join('');
    setupLightbox();
}

// ─── Image Protection ────────────────────────────────────────────────────

(function () {
    // Block right-click on gallery images and lightbox
    document.addEventListener('contextmenu', e => {
        if (e.target.closest('#galleryGrid, #groupsFolderGrid, #lightboxModal, .group-folder')) {
            e.preventDefault();
            return false;
        }
    });

    // Block drag-to-save on gallery images
    document.addEventListener('dragstart', e => {
        if (e.target.tagName === 'IMG' &&
            e.target.closest('#galleryGrid, #groupsFolderGrid, #lightboxModal, .group-folder')) {
            e.preventDefault();
            return false;
        }
    });
})();

// ─── Helpers ─────────────────────────────────────────────────────────────

function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escJs(str) {
    return String(str ?? '').replace(/'/g,"\\'").replace(/\\/g,'\\\\');
}
