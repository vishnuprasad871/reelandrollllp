/**
 * Admin Dashboard JavaScript
 * Handles authentication, image upload, and gallery management
 */

const API_BASE = '/api';
let currentFilter = 'all';
let currentGallery = [];

/**
 * Check authentication on dashboard load
 */
async function checkAuth() {
    if (window.location.pathname.includes('dashboard.html')) {
        try {
            const response = await fetch(`${API_BASE}/auth.php`);
            const data = await response.json();

            if (!data.authenticated) {
                window.location.href = 'index.html';
                return;
            }

            // Display user info
            document.getElementById('userInfo').textContent = `Welcome, ${data.user.username}`;

            // Load gallery
            loadGallery();
        } catch (error) {
            console.error('Auth check failed:', error);
            window.location.href = 'index.html';
        }
    }
}

/**
 * Handle logout
 */
document.addEventListener('DOMContentLoaded', () => {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            try {
                await fetch(`${API_BASE}/logout.php`, { method: 'POST' });
                window.location.href = 'index.html';
            } catch (error) {
                console.error('Logout failed:', error);
            }
        });
    }

    checkAuth();
    setupUploadForm();
    setupFilterTabs();
});

/**
 * Setup upload form with drag-and-drop
 */
function setupUploadForm() {
    const uploadForm = document.getElementById('uploadForm');
    const uploadArea = document.getElementById('uploadArea');
    const imageFile = document.getElementById('imageFile');
    const imagePreview = document.getElementById('imagePreview');

    if (!uploadForm) return;

    // File selection
    imageFile.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            previewImage(e.target.files[0]);
        }
    });

    // Click to upload
    uploadArea.addEventListener('click', (e) => {
        if (!e.target.closest('.btn-remove-preview')) {
            imageFile.click();
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        if (e.dataTransfer.files.length > 0) {
            imageFile.files = e.dataTransfer.files;
            previewImage(e.dataTransfer.files[0]);
        }
    });

    // Remove preview
    const removeBtn = document.querySelector('.btn-remove-preview');
    if (removeBtn) {
        removeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            imageFile.value = '';
            imagePreview.style.display = 'none';
            uploadArea.querySelector('.upload-placeholder').style.display = 'flex';
        });
    }

    // Form submission
    uploadForm.addEventListener('submit', handleUpload);
}

/**
 * Preview selected image
 */
function previewImage(file) {
    const reader = new FileReader();
    const imagePreview = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');

    reader.onload = (e) => {
        imagePreview.querySelector('img').src = e.target.result;
        imagePreview.style.display = 'block';
        uploadArea.querySelector('.upload-placeholder').style.display = 'none';
    };

    reader.readAsDataURL(file);
}

/**
 * Handle image upload
 */
async function handleUpload(e) {
    e.preventDefault();

    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const loader = submitBtn.querySelector('.loader');
    const btnText = submitBtn.querySelector('span');
    const message = document.getElementById('uploadMessage');

    const formData = new FormData(form);

    // Show loading
    submitBtn.disabled = true;
    btnText.style.display = 'none';
    loader.style.display = 'inline-block';
    message.style.display = 'none';

    try {
        const response = await fetch(`${API_BASE}/gallery.php`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showMessage(message, 'Image uploaded successfully!', 'success');
            form.reset();
            document.getElementById('imagePreview').style.display = 'none';
            document.querySelector('.upload-placeholder').style.display = 'flex';
            loadGallery(); // Refresh gallery
        } else {
            throw new Error(data.error || 'Upload failed');
        }
    } catch (error) {
        showMessage(message, error.message, 'error');
    } finally {
        submitBtn.disabled = false;
        btnText.style.display = 'inline';
        loader.style.display = 'none';
    }
}

/**
 * Load gallery images
 */
async function loadGallery(category = 'all') {
    const galleryGrid = document.getElementById('galleryGrid');

    if (!galleryGrid) return;

    galleryGrid.innerHTML = '<div class="loader-container"><div class="loader"></div><p>Loading gallery...</p></div>';

    try {
        const url = category === 'all' ? `${API_BASE}/gallery.php` : `${API_BASE}/gallery.php?category=${category}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.success) {
            currentGallery = data.data;
            renderGallery(data.data);
        } else {
            throw new Error(data.error || 'Failed to load gallery');
        }
    } catch (error) {
        galleryGrid.innerHTML = `<div class="error-message">Error loading gallery: ${error.message}</div>`;
    }
}

/**
 * Render gallery grid
 */
function renderGallery(items) {
    const galleryGrid = document.getElementById('galleryGrid');

    if (items.length === 0) {
        galleryGrid.innerHTML = '<div class="empty-state">No images found</div>';
        return;
    }

    galleryGrid.innerHTML = items.map(item => `
        <div class="gallery-admin-item" data-id="${item.id}">
            <div class="image-wrapper">
                <img src="${item.image_url}" alt="${item.alt_text || 'Gallery image'}">
                <div class="image-overlay">
                    <button class="btn-icon" onclick="editImage(${item.id})" title="Edit">✏️</button>
                    <button class="btn-icon" onclick="deleteImage(${item.id})" title="Delete">🗑️</button>
                </div>
            </div>
            <div class="image-info">
                <span class="category-badge category-${item.category}">${item.category}</span>
                <p class="image-alt">${item.alt_text || 'No description'}</p>
            </div>
        </div>
    `).join('');
}

/**
 * Setup filter tabs
 */
function setupFilterTabs() {
    const filterTabs = document.querySelectorAll('.filter-tab');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            filterTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            currentFilter = filter;
            loadGallery(filter);
        });
    });
}

/**
 * Edit image
 */
function editImage(id) {
    const item = currentGallery.find(i => i.id == id);
    if (!item) return;

    document.getElementById('editImageId').value = item.id;
    document.getElementById('editCategory').value = item.category;
    document.getElementById('editAltText').value = item.alt_text || '';

    document.getElementById('editModal').style.display = 'flex';

    // Setup form submission
    const editForm = document.getElementById('editForm');
    editForm.onsubmit = async (e) => {
        e.preventDefault();

        const formData = {
            id: parseInt(document.getElementById('editImageId').value),
            category: document.getElementById('editCategory').value,
            alt_text: document.getElementById('editAltText').value
        };

        try {
            const response = await fetch(`${API_BASE}/gallery.php?id=${formData.id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                closeEditModal();
                loadGallery(currentFilter);
            } else {
                throw new Error(data.error || 'Update failed');
            }
        } catch (error) {
            alert('Error updating image: ' + error.message);
        }
    };
}

/**
 * Close edit modal
 */
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

/**
 * Delete image
 */
async function deleteImage(id) {
    if (!confirm('Are you sure you want to delete this image?')) return;

    try {
        const response = await fetch(`${API_BASE}/gallery.php?id=${id}`, {
            method: 'DELETE'
        });

        const data = await response.json();

        if (data.success) {
            loadGallery(currentFilter);
        } else {
            throw new Error(data.error || 'Delete failed');
        }
    } catch (error) {
        alert('Error deleting image: ' + error.message);
    }
}

/**
 * Show message
 */
function showMessage(element, text, type) {
    element.textContent = text;
    element.className = `message ${type}`;
    element.style.display = 'block';

    setTimeout(() => {
        element.style.display = 'none';
    }, 5000);
}
