<?php
$pageTitle       = 'Photo Gallery | Reel and Roll Photography';
$pageDescription = 'Browse our complete photography gallery — weddings, portraits, events, landscapes and sports.';
$activeNav       = 'gallery';
require_once 'includes/header.php';

// Read ?category= from URL and sanitise
$allowedCategories = ['all', 'wedding', 'portrait', 'event', 'landscape', 'sports'];
$activeCategory    = $_GET['category'] ?? 'all';
if (!in_array($activeCategory, $allowedCategories)) $activeCategory = 'all';
?>

    <!-- Gallery Section -->
    <section class="gallery-container">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Our Collection</p>
                <h2>Complete <span class="text-gradient">Photography Gallery</span></h2>
                <p>Browse through our extensive collection of photography work</p>
            </div>

            <!-- Filter Buttons -->
            <div class="gallery-filters reveal">
                <?php foreach ($allowedCategories as $cat): ?>
                <button class="filter-btn <?= $cat === $activeCategory ? 'active' : '' ?>"
                        data-filter="<?= $cat ?>">
                    <?= ucfirst($cat === 'all' ? 'All' : $cat) ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid" id="galleryGrid">
                <div class="gallery-item reveal" data-category="wedding">
                    <img src="assets/portfolio_wedding_1770402245749.png" alt="Wedding Photography" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="portrait">
                    <img src="assets/portfolio_portrait_1770402268053.png" alt="Portrait Photography" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="portrait">
                    <img src="assets/about_photographer_1770402230073.png" alt="Professional Portrait" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="landscape">
                    <img src="assets/portfolio_landscape_1770402286898.png" alt="Landscape Photography" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="landscape">
                    <img src="assets/hero_background_1770402213477.png" alt="Sunset Photography" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="event">
                    <img src="assets/portfolio_event_1770402304065.png" alt="Event Photography" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="wedding">
                    <img src="assets/portfolio_wedding_1770402245749.png" alt="Wedding Ceremony" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="portrait">
                    <img src="assets/portfolio_portrait_1770402268053.png" alt="Corporate Portrait" loading="lazy">
                </div>
                <div class="gallery-item reveal" data-category="landscape">
                    <img src="assets/portfolio_landscape_1770402286898.png" alt="Nature Photography" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="contact-cta section">
        <div class="container reveal">
            <h2>Love What You <span class="text-gradient">See</span>?</h2>
            <p>Let's create stunning photography for your special moments</p>
            <a href="/contact" class="btn btn-primary" style="margin-top: 2rem;"><span>Contact Us</span></a>
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox-modal" id="lightboxModal">
        <button class="lightbox-close" id="lightboxClose" aria-label="Close lightbox">&times;</button>
        <div class="lightbox-content">
            <img id="lightboxImg" src="" alt="Full size image">
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>

<script src="gallery-api.js"></script>
<script>
// Pre-activate filter from PHP-resolved category
(function () {
    const cat = <?= json_encode($activeCategory) ?>;
    if (cat !== 'all') {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        const target = document.querySelector('[data-filter="' + cat + '"]');
        if (target) target.classList.add('active');
    }
})();
</script>
