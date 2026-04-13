<?php
$pageTitle       = 'Photo Gallery | Reel and Roll Photography';
$pageDescription = 'Browse our complete photography gallery — weddings, portraits, events, landscapes and sports.';
$activeNav       = 'gallery';
require_once 'includes/header.php';

// Sanitise URL params
$allowedCategories = ['all', 'wedding', 'portrait', 'event', 'landscape', 'sports'];
$activeCategory    = $_GET['category'] ?? 'all';
if (!in_array($activeCategory, $allowedCategories)) $activeCategory = 'all';

$urlGroup = isset($_GET['group']) ? (int)$_GET['group'] : null;

// Determine initial view: category param → flat list, group param or nothing → groups view
$startInImagesView = ($activeCategory !== 'all') || ($urlGroup !== null);
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Photo <span class="text-gradient">Gallery</span></h1>
            <div class="page-breadcrumb fade-in-up">
                <a href="/">Home</a>
                <span>/</span>
                <span>Gallery</span>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-container">
        <div class="container">

            <!-- ── Groups Folder View (default) ─────────────────── -->
            <div id="groupsView" <?= $startInImagesView ? 'style="display:none;"' : '' ?>>
                <div class="section-header reveal" style="margin-bottom:2rem;">
                    <p class="section-subtitle">Our Collection</p>
                    <h2>Browse by <span class="text-gradient">Category</span></h2>
                    <p>Select a group to explore photos</p>
                </div>
                <div id="groupsFolderGrid" class="groups-folder-grid reveal">
                    <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#888;">
                        <div style="width:36px;height:36px;border:3px solid #ddd;border-top-color:#FF4D00;border-radius:50%;margin:0 auto 1rem;animation:spin .8s linear infinite;"></div>
                        Loading groups…
                    </div>
                </div>
            </div>

            <!-- ── Images View (inside group or category filter) ── -->
            <div id="imagesView" <?= $startInImagesView ? '' : 'style="display:none;"' ?>>
                <!-- Nav bar -->
                <div class="gallery-images-nav reveal">
                    <button class="gallery-back-btn" onclick="showGroupsView()">← All Groups</button>
                    <span id="currentGroupLabel" class="gallery-group-label"></span>
                </div>

                <!-- Category filter buttons (visible inside a group too) -->
                <div class="gallery-filters reveal" id="categoryFilters">
                    <?php foreach ($allowedCategories as $cat): ?>
                    <button class="filter-btn <?= $cat === $activeCategory ? 'active' : '' ?>"
                            data-filter="<?= $cat ?>">
                        <?= $cat === 'all' ? 'All' : ucfirst($cat) ?>
                    </button>
                    <?php endforeach; ?>
                </div>

                <!-- Gallery Grid -->
                <div class="gallery-grid" id="galleryGrid"></div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="contact-cta section">
        <div class="container reveal">
            <h2>Love What You <span class="text-gradient">See</span>?</h2>
            <p>Let's create stunning photography for your special moments</p>
            <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Contact Us</span></a>
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox-modal" id="lightboxModal">
        <button class="lightbox-close" id="lightboxClose" aria-label="Close">&times;</button>
        <div class="lightbox-content">
            <img id="lightboxImg" src="" alt="Full size image">
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>

<script src="gallery-api.js"></script>
<script>
// Pass PHP-resolved state to JS
const INIT_CATEGORY = <?= json_encode($activeCategory) ?>;
const INIT_GROUP_ID = <?= json_encode($urlGroup) ?>;
</script>
