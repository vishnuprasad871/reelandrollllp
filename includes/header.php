<?php
/**
 * Shared site header — nav + <head>
 *
 * Set these variables BEFORE including this file:
 *   $pageTitle       (string) — browser tab title
 *   $pageDescription (string) — meta description
 *   $activeNav       (string) — 'home' | 'about' | 'photography' |
 *                               'videography' | 'portfolio' | 'gallery' | 'contact'
 *   $navScrolled     (bool)   — false on home (transparent nav), true elsewhere
 */
$pageTitle       = $pageTitle       ?? 'Reel and Roll Photography';
$pageDescription = $pageDescription ?? 'Professional photography & videography in Dubai';
$activeNav       = $activeNav       ?? '';
$navScrolled     = $navScrolled     ?? true;

$navClass = 'navbar' . ($navScrolled ? ' scrolled' : '');

// Helper: returns 'class="active"' when the nav item matches current page
function navActive(string $key, string $active): string {
    return $key === $active ? ' class="active"' : '';
}
function navCTA(string $active): string {
    return 'btn-nav-cta' . ($active === 'contact' ? ' active' : '');
}
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

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/Logo.png">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Navigation -->
<nav class="<?= $navClass ?>" id="navbar">
    <div class="container nav-container">
        <ul class="nav-links-left" id="navLinksLeft">
            <li><a href="/"<?= navActive('home', $activeNav) ?>>Home</a></li>
            <li><a href="/about"<?= navActive('about', $activeNav) ?>>About</a></li>
            <li class="has-dropdown">
                <a href="/services"<?= navActive('photography', $activeNav) ?>>Photography</a>
                <ul class="dropdown">
                    <li><a href="/services#wedding">Wedding</a></li>
                    <li><a href="/services#portrait">Portrait</a></li>
                    <li><a href="/services#event">Event Coverage</a></li>
                    <li><a href="/services#landscape">Landscape &amp; Sports</a></li>
                    <li><a href="/services#corporate">Corporate</a></li>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="/video-gallery"<?= navActive('videography', $activeNav) ?>>Videography</a>
                <ul class="dropdown">
                    <li><a href="/video-gallery#weddings">Wedding Films</a></li>
                    <li><a href="/video-gallery#events">Event Coverage</a></li>
                    <li><a href="/video-gallery#corporate">Corporate Films</a></li>
                    <li><a href="/video-gallery#commercial">Commercial</a></li>
                </ul>
            </li>
        </ul>

        <a href="/" class="logo">
            <img src="assets/Logo.png" alt="Reel &amp; Roll Photography">
        </a>

        <ul class="nav-links-right" id="navLinksRight">
            <li><a href="/portfolio"<?= navActive('portfolio', $activeNav) ?>>Portfolio</a></li>
            <li class="has-dropdown">
                <a href="/gallery"<?= navActive('gallery', $activeNav) ?>>Gallery</a>
                <ul class="dropdown">
                    <li><a href="/gallery?category=wedding">Wedding</a></li>
                    <li><a href="/gallery?category=portrait">Portrait</a></li>
                    <li><a href="/gallery?category=event">Event</a></li>
                    <li><a href="/gallery?category=landscape">Landscape</a></li>
                    <li><a href="/gallery?category=sports">Sports</a></li>
                </ul>
            </li>
            <li><a href="/contact" class="<?= navCTA($activeNav) ?>">Book Now</a></li>
        </ul>

        <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>
