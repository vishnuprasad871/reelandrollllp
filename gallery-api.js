/**
 * Gallery API Integration
 * Dynamically loads and renders gallery images from the backend API
 */

const API_BASE = '/api';
let allGalleryItems = [];
let currentCategory = 'all';

/**
 * Load gallery from API on page  load
 */
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('galleryGrid')) {
        loadGalleryFromAPI();
        setupFilterButtons();
    }
});

/**
 * Load gallery data from API
 */
async function loadGalleryFromAPI() {
    const galleryGrid = document.getElementById('galleryGrid');

    if (!galleryGrid) return;

    // Show loading state
    galleryGrid.innerHTML = `
        <div class="loading-container" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
            <div class="loader" style="width: 40px; height: 40px; border: 3px solid #dfe6e9; border-top-color: #C9A961; border-radius: 50%; margin: 0 auto 1rem; animation: spin 0.8s linear infinite;"></div>
            <p style="color: #636e72;">Loading gallery...</p>
        </div>
    `;

    try {
        const url = currentCategory === 'all' ? `${API_BASE}/gallery.php` : `${API_BASE}/gallery.php?category=${currentCategory}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.success && data.data) {
            allGalleryItems = data.data;
            renderGallery(data.data);
        } else {
            throw new Error(data.error || 'Failed to load gallery');
        }
    } catch (error) {
        console.error('Error loading gallery:', error);

        // Fallback to hardcoded images if API fails
        galleryGrid.innerHTML = `
            <div class="error-fallback" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                <p style="color: #d63031; margin-bottom: 1rem;">Unable to load gallery from server. Showing default images.</p>
            </div>
        `;

        // Load default hardcoded images
        loadDefaultGallery();
    }
}

/**
 * Render gallery items
 */
function renderGallery(items) {
    const galleryGrid = document.getElementById('galleryGrid');

    if (!galleryGrid) return;

    if (items.length === 0) {
        galleryGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <p style="color: #636e72; font-size: 1.1rem;">No images found in this category.</p>
            </div>
        `;
        return;
    }

    galleryGrid.innerHTML = items.map(item => `
        <div class="gallery-item reveal active" data-category="${item.category}">
            <img src="${item.image_url}" alt="${item.alt_text || 'Gallery image'}" loading="lazy">
        </div>
    `).join('');

    // Trigger reveal animation
    setTimeout(() => {
        document.querySelectorAll('#galleryGrid .gallery-item').forEach(item => {
            item.classList.add('active');
        });
    }, 100);

    // Initialize Lightbox on first render
    setupLightbox();
}

/**
 * Setup Lightbox functionality
 */
function setupLightbox() {
    const modal = document.getElementById('lightboxModal');
    const modalImg = document.getElementById('lightboxImg');
    const closeBtn = document.getElementById('lightboxClose');

    if (!modal || !modalImg) return;

    // Use event delegation for gallery items
    const galleryGrid = document.getElementById('galleryGrid');
    
    // Clear previous listener if any (to avoid duplicates)
    const newGrid = galleryGrid.cloneNode(true);
    galleryGrid.parentNode.replaceChild(newGrid, galleryGrid);

    newGrid.addEventListener('click', (e) => {
        const item = e.target.closest('.gallery-item');
        if (item) {
            const img = item.querySelector('img');
            if (img) {
                modalImg.src = img.src;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scroll
            }
        }
    });

    // Close modal events
    closeBtn.onclick = () => closeModal();
    modal.onclick = (e) => {
        if (e.target === modal) closeModal();
    };

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

/**
 * Setup filter buttons
 */
function setupFilterButtons() {
    const filterBtns = document.querySelectorAll('.filter-btn');

    if (filterBtns.length === 0) return;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            currentCategory = filter;

            // Reload gallery with filter
            loadGalleryFromAPI();
        });
    });
}

/**
 * Load default hardcoded gallery (fallback)
 */
function loadDefaultGallery() {
    const galleryGrid = document.getElementById('galleryGrid');

    const defaultImages = [
        { src: 'assets/portfolio_wedding_1770402245749.png', alt: 'Wedding Photography', category: 'wedding' },
        { src: 'assets/portfolio_portrait_1770402268053.png', alt: 'Portrait Photography', category: 'portrait' },
        { src: 'assets/about_photographer_1770402230073.png', alt: 'Professional Portrait', category: 'portrait' },
        { src: 'assets/portfolio_landscape_1770402286898.png', alt: 'Landscape Photography', category: 'landscape' },
        { src: 'assets/hero_background_1770402213477.png', alt: 'Sunset Photography', category: 'landscape' },
        { src: 'assets/portfolio_event_1770402304065.png', alt: 'Event Photography', category: 'event' }
    ];

    galleryGrid.innerHTML += defaultImages.map(item => `
        <div class="gallery-item reveal active" data-category="${item.category}">
            <img src="${item.src}" alt="${item.alt}" loading="lazy">
        </div>
    `).join('');
}

// Add loader animation CSS if not already present
if (!document.getElementById('loader-styles')) {
    const style = document.createElement('style');
    style.id = 'loader-styles';
    style.textContent = `
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}
