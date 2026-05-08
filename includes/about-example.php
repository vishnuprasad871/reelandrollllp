<?php
/**
 * EXAMPLE: How to convert about.html → about.php
 *
 * 1. Rename about.html to about.php
 * 2. Replace the <head>...</head> + <nav>...</nav> block with:
 *      $pageTitle  = 'About Us | Reel and Roll Photography';
 *      $activeNav  = 'about';
 *      require_once __DIR__ . '/includes/header.php';
 *
 * 3. Replace the <footer>...</footer> + <script> block with:
 *      require_once __DIR__ . '/includes/footer.php';
 *
 * The page in between stays exactly as-is.
 */

$pageTitle       = 'About Us | Reel and Roll Photography';
$pageDescription = 'Learn about Reel and Roll Photography — our story, team, and passion for capturing moments.';
$activeNav       = 'about';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="fade-in-up">About <span class="text-gradient">Us</span></h1>
        <div class="page-breadcrumb fade-in-up">
            <a href="/">Home</a>
            <span>/</span>
            <span>About</span>
        </div>
    </div>
</section>

<!-- ... rest of page content ... -->

<?php require_once __DIR__ . '/includes/footer.php'; ?>
