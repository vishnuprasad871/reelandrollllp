<?php
$pageTitle       = 'Photography Services | Reel and Roll Photography';
$pageDescription = 'Professional photography services in Dubai — weddings, portraits, events, landscape, sports & corporate.';
$activeNav       = 'photography';
require_once 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Photography <span class="text-gradient">Services</span></h1>
            <div class="page-breadcrumb fade-in-up">
                <a href="/">Home</a>
                <span>/</span>
                <span>Photography</span>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-content section">
        <div class="container">

            <!-- Sub-Navigation -->
            <div class="service-menu">
                <a href="#wedding"   class="btn btn-secondary"><span>Wedding</span></a>
                <a href="#portrait"  class="btn btn-secondary"><span>Portrait</span></a>
                <a href="#event"     class="btn btn-secondary"><span>Events</span></a>
                <a href="#landscape" class="btn btn-secondary"><span>Landscape</span></a>
                <a href="#sports"    class="btn btn-secondary"><span>Sports</span></a>
                <a href="#corporate" class="btn btn-secondary"><span>Corporate</span></a>
            </div>

            <!-- Wedding -->
            <div id="wedding" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 8rem;">
                <div class="reveal">
                    <img src="assets/portfolio_wedding_1770402245749.png" alt="Wedding Photography"
                        style="width:100%;border-radius:16px;border:3px solid var(--color-primary);box-shadow:var(--shadow-primary);">
                </div>
                <div class="reveal">
                    <div class="service-icon" style="margin-bottom:1rem;">💍</div>
                    <p class="section-subtitle">Premium Experience</p>
                    <h2>Wedding Photography</h2>
                    <p>Your wedding is not just an event; it's the beginning of your legacy. We capture the grand scale and the intimate whispers with equal artistry.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Get A Quote</span></a>
                </div>
            </div>

            <!-- Portrait -->
            <div id="portrait" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 8rem;">
                <div class="reveal">
                    <div class="service-icon" style="margin-bottom:1rem;">👤</div>
                    <p class="section-subtitle">Character &amp; Style</p>
                    <h2>Portrait Photography</h2>
                    <p>Elevate your personal brand or capture your family's essence. We create character-driven portraits that command attention.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Get A Quote</span></a>
                </div>
                <div class="reveal">
                    <img src="assets/portfolio_portrait_1770402268053.png" alt="Portrait Photography"
                        style="width:100%;border-radius:16px;border:3px solid var(--color-primary);box-shadow:var(--shadow-primary);">
                </div>
            </div>

            <!-- Event -->
            <div id="event" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 8rem;">
                <div class="reveal">
                    <img src="assets/portfolio_event_1770402304065.png" alt="Event Photography"
                        style="width:100%;border-radius:16px;border:3px solid var(--color-primary);box-shadow:var(--shadow-primary);">
                </div>
                <div class="reveal">
                    <div class="service-icon" style="margin-bottom:1rem;">🎉</div>
                    <p class="section-subtitle">Dynamic &amp; Candid</p>
                    <h2>Event Coverage</h2>
                    <p>From high-profile corporate galas to exclusive private celebrations, we capture the electricity, scale, and candid moments that define your event.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Get A Quote</span></a>
                </div>
            </div>

            <!-- Landscape -->
            <div id="landscape" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 8rem;">
                <div class="reveal">
                    <div class="service-icon" style="margin-bottom:1rem;">🏞️</div>
                    <p class="section-subtitle">Nature &amp; Fine Art</p>
                    <h2>Landscape Photography</h2>
                    <p>Breathtaking fine-art prints of the world's most spectacular landscapes, from the dunes of the Dubai desert to global natural wonders.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Inquire Now</span></a>
                </div>
                <div class="reveal">
                    <img src="assets/landscape.jpg" alt="Landscape Photography"
                        style="width:100%;border-radius:16px;border:3px solid var(--color-primary);box-shadow:var(--shadow-primary);">
                </div>
            </div>

            <!-- Sports -->
            <div id="sports" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 8rem;">
                <div class="reveal">
                    <img src="assets/sports.jpg" alt="Sports Photography"
                        style="width:100%;border-radius:16px;border:3px solid var(--color-primary);box-shadow:var(--shadow-primary);">
                </div>
                <div class="reveal">
                    <div class="service-icon" style="margin-bottom:1rem;">⚽</div>
                    <p class="section-subtitle">Action &amp; Energy</p>
                    <h2>Sports Photography</h2>
                    <p>Capturing the raw emotion and peak performance of athletes. High-speed, split-second photography for professional events and individual athletes.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Get in Touch</span></a>
                </div>
            </div>

            <!-- Corporate -->
            <div id="corporate" style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; margin-bottom: 8rem;">
                <div class="reveal">
                    <div class="service-icon" style="margin-bottom:1rem;">🏢</div>
                    <p class="section-subtitle">Professional Excellence</p>
                    <h2>Corporate Photography</h2>
                    <p>Elevate your brand with powerful corporate imagery — from executive headshots and team photography to office environments and product shoots.</p>
                    <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Get A Quote</span></a>
                </div>
                <div class="reveal">
                    <img src="assets/corporate.jpg" alt="Corporate Photography"
                        style="width:100%;border-radius:16px;border:3px solid var(--color-primary);box-shadow:var(--shadow-primary);">
                </div>
            </div>

        </div>
    </section>

    <!-- CTA -->
    <section class="contact-cta section">
        <div class="container reveal">
            <h2>Ready to Book Your <span class="text-gradient">Session</span>?</h2>
            <p>Let's discuss your project and create something extraordinary together.</p>
            <a href="/contact" class="btn btn-primary" style="margin-top:2rem;"><span>Start a Conversation</span></a>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
