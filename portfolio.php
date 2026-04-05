<?php
$pageTitle       = 'Portfolio | Reel and Roll Photography';
$pageDescription = 'Browse our portfolio of wedding, portrait, event, and landscape photography in Dubai.';
$activeNav       = 'portfolio';
require_once 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Our <span class="text-gradient">Portfolio</span></h1>
            <div class="page-breadcrumb fade-in-up">
                <a href="index.php">Home</a>
                <span>/</span>
                <span>Portfolio</span>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid -->
    <section class="gallery-container">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Our Work</p>
                <h2>Featured <span class="text-gradient">Projects</span></h2>
                <p>A showcase of our best photography work</p>
            </div>
            <div class="featured-grid" style="margin-bottom: 4rem;">
                <div class="featured-item reveal">
                    <img src="assets/portfolio_wedding_1770402245749.png" alt="Wedding Photography">
                    <div class="featured-overlay">
                        <h3 class="featured-title">Sarah &amp; Michael's Wedding</h3>
                        <p class="featured-category">Wedding Photography</p>
                    </div>
                </div>
                <div class="featured-item reveal">
                    <img src="assets/portfolio_portrait_1770402268053.png" alt="Portrait Photography">
                    <div class="featured-overlay">
                        <h3 class="featured-title">Executive Portraits</h3>
                        <p class="featured-category">Corporate Photography</p>
                    </div>
                </div>
                <div class="featured-item reveal">
                    <img src="assets/portfolio_landscape_1770402286898.png" alt="Landscape Photography">
                    <div class="featured-overlay">
                        <h3 class="featured-title">Mountain Majesty</h3>
                        <p class="featured-category">Landscape Photography</p>
                    </div>
                </div>
                <div class="featured-item reveal">
                    <img src="assets/portfolio_event_1770402304065.png" alt="Event Photography">
                    <div class="featured-overlay">
                        <h3 class="featured-title">Summer Music Festival</h3>
                        <p class="featured-category">Event Coverage</p>
                    </div>
                </div>
                <div class="featured-item reveal">
                    <img src="assets/about_photographer_1770402230073.png" alt="Professional Portrait">
                    <div class="featured-overlay">
                        <h3 class="featured-title">Professional Headshots</h3>
                        <p class="featured-category">Portrait Photography</p>
                    </div>
                </div>
                <div class="featured-item reveal">
                    <img src="assets/hero_background_1770402213477.png" alt="Artistic Photography">
                    <div class="featured-overlay">
                        <h3 class="featured-title">Sunset Silhouette</h3>
                        <p class="featured-category">Artistic Photography</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials section">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Client Feedback</p>
                <h2>What Our <span class="text-gradient">Clients</span> Say</h2>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card reveal">
                    <p class="testimonial-text">"The wedding photos are absolutely stunning! Every shot is perfect and captures the emotion of the day beautifully."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">SM</div>
                        <div><div class="author-name">Sarah Mitchell</div><div class="author-role">Bride</div></div>
                    </div>
                </div>
                <div class="testimonial-card reveal">
                    <p class="testimonial-text">"Outstanding professional work. The portraits exceeded our expectations and the team was wonderful to work with."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">JD</div>
                        <div><div class="author-name">James Davidson</div><div class="author-role">CEO, Tech Solutions</div></div>
                    </div>
                </div>
                <div class="testimonial-card reveal">
                    <p class="testimonial-text">"Captured our event perfectly! The photos are vibrant, dynamic, and tell the complete story of our celebration."</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">EC</div>
                        <div><div class="author-name">Emily Chen</div><div class="author-role">Event Coordinator</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="contact-cta section">
        <div class="container reveal">
            <h2>Want to See More <span class="text-gradient">Work</span>?</h2>
            <p>Visit our full gallery or contact us to discuss your photography needs</p>
            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
                <a href="gallery.php" class="btn btn-primary"><span>View Full Gallery</span></a>
                <a href="contact.php" class="btn btn-secondary"><span>Contact Us</span></a>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
