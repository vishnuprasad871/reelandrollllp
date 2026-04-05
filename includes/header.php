<?php
/**
 * Shared header include.
 *
 * Usage in any page:
 *   <?php
 *   $pageTitle       = 'About Us | Reel and Roll Photography';
 *   $pageDescription = 'Learn about our story...';
 *   $activeNav       = 'about';   // matches data-nav values below
 *   require_once __DIR__ . '/../includes/header.php';
 *   ?>
 */

// Defaults if the page file didn't set them
$pageTitle       = $pageTitle       ?? 'Reel and Roll Photography';
$pageDescription = $pageDescription ?? 'Premium photography & videography in Dubai';
$activeNav       = $activeNav       ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar scrolled" id="navbar">
    <div class="container nav-container">
        <ul class="nav-links-left" id="navLinksLeft">
            <li><a href="/" <?= $activeNav === 'home' ? 'class="active"' : '' ?>>Home</a></li>
            <li><a href="/about" <?= $activeNav === 'about' ? 'class="active"' : '' ?>>About</a></li>
            <li class="has-dropdown">
                <a href="/services" <?= $activeNav === 'photography' ? 'class="active"' : '' ?>>Photography</a>
                <ul class="dropdown">
                    <li><a href="/services#wedding">Wedding</a></li>
                    <li><a href="/services#portrait">Portrait</a></li>
                    <li><a href="/services#event">Event Coverage</a></li>
                    <li><a href="/services#landscape">Landscape &amp; Sports</a></li>
                    <li><a href="/services#corporate">Corporate</a></li>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="/video-gallery" <?= $activeNav === 'videography' ? 'class="active"' : '' ?>>Videography</a>
                <ul class="dropdown">
                    <li><a href="/video-gallery#weddings">Wedding Films</a></li>
                    <li><a href="/video-gallery#events">Event Coverage</a></li>
                    <li><a href="/video-gallery#corporate">Corporate Films</a></li>
                    <li><a href="/video-gallery#commercial">Commercial</a></li>
                </ul>
            </li>
        </ul>

        <a href="/" class="logo">
            <img src="/assets/Logo.png" alt="Reel &amp; Roll Photography">
        </a>

        <ul class="nav-links-right" id="navLinksRight">
            <li><a href="/portfolio" <?= $activeNav === 'portfolio' ? 'class="active"' : '' ?>>Portfolio</a></li>
            <li class="has-dropdown">
                <a href="/gallery" <?= $activeNav === 'gallery' ? 'class="active"' : '' ?>>Gallery</a>
                <ul class="dropdown">
                    <li><a href="/gallery?category=wedding">Wedding</a></li>
                    <li><a href="/gallery?category=portrait">Portrait</a></li>
                    <li><a href="/gallery?category=event">Event</a></li>
                    <li><a href="/gallery?category=landscape">Landscape</a></li>
                    <li><a href="/gallery?category=sports">Sports</a></li>
                </ul>
            </li>
            <li><a href="/contact" class="btn-nav-cta <?= $activeNav === 'contact' ? 'active' : '' ?>">Book Now</a></li>
        </ul>

        <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
