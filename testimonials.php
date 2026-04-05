<?php
$pageTitle       = 'Client Testimonials | Reel and Roll Photography';
$pageDescription = 'Read real testimonials from our 500+ happy clients across Dubai and the UAE.';
$activeNav       = '';
require_once 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Client <span class="text-gradient">Stories</span></h1>
            <p class="page-subtitle fade-in-up">Real testimonials from clients who trusted us with their most precious moments</p>
            <div class="page-breadcrumb fade-in-up">
                <a href="index.php">Home</a>
                <span>/</span>
                <span>Testimonials</span>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section">
        <div class="container">
            <!-- Stats -->
            <div class="testimonials-stats reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 5rem; text-align: center;">
                <div>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.5rem;">500+</div>
                    <p style="font-size: 1.1rem; color: var(--color-gray-light);">Happy Clients</p>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.5rem;">4.9/5</div>
                    <p style="font-size: 1.1rem; color: var(--color-gray-light);">Average Rating</p>
                </div>
                <div>
                    <div style="font-size: 3rem; font-weight: 800; color: var(--color-primary); margin-bottom: 0.5rem;">1200+</div>
                    <p style="font-size: 1.1rem; color: var(--color-gray-light);">5-Star Reviews</p>
                </div>
            </div>

            <!-- Testimonials Grid -->
            <div class="testimonials-grid">
                <?php
                $testimonials = [
                    ['name' => 'Sarah Al-Maktoum',    'title' => 'Bride',                    'service' => 'Wedding Photography',         'date' => 'June 2024',     'text' => 'Ahmed and the team captured every magical moment of our wedding day perfectly. The attention to detail, professionalism, and creativity exceeded all our expectations. We couldn\'t have asked for better!'],
                    ['name' => 'Mohammed Al-Dhaheri', 'title' => 'Corporate Client',          'service' => 'Commercial Photography',      'date' => 'April 2024',    'text' => 'We hired Reel and Roll for our corporate event coverage and brand photoshoot. The team\'s professionalism, creativity, and technical expertise made us look absolutely stunning.'],
                    ['name' => 'Fatima Al-Mazrouei',  'title' => 'Fashion Brand Owner',       'service' => 'Fashion Photography',         'date' => 'March 2024',    'text' => 'The creative direction and vision for our fashion collection photoshoot was incredible. The final images perfectly captured our brand essence and elevated our marketing materials significantly.'],
                    ['name' => 'James Patterson',     'title' => 'Event Organizer',            'service' => 'Event Photography',           'date' => 'May 2024',      'text' => 'Reel and Roll\'s event photography coverage was flawless. They captured the energy, emotions, and highlights seamlessly. The fast turnaround and professional editing were impressive. A+!'],
                    ['name' => 'Layla Al-Hashmi',     'title' => 'Bride',                    'service' => 'Wedding Photography',         'date' => 'February 2024', 'text' => 'From our first consultation to the final delivery, everything was perfect. Ahmed understood our vision immediately and captured it beautifully. Our wedding photos are treasured beyond words!'],
                    ['name' => 'Ahmed Al-Mazrouei',   'title' => 'Restaurant Owner',          'service' => 'Food & Interior Photography', 'date' => 'December 2023', 'text' => 'Outstanding food and interior photography for our restaurant! The images make every dish look absolutely appetizing. Our social media engagement increased significantly after using these photos.'],
                    ['name' => 'Noor Al-Mansoori',    'title' => 'Portrait Client',           'service' => 'Portrait Photography',        'date' => 'October 2023',  'text' => 'My executive portraits were done with such professionalism and artistry. The team\'s direction made me feel comfortable and confident. These photos now represent me perfectly on my business profiles!'],
                    ['name' => 'Hassan Al-Qassimi',   'title' => 'Real Estate Developer',     'service' => 'Real Estate Photography',     'date' => 'September 2023','text' => 'The property photography and drone shots for our development project were exceptional. The team delivered stunning visuals that perfectly showcased our properties\' best features.'],
                ];
                foreach ($testimonials as $t): ?>
                <div class="testimonial-card reveal">
                    <div class="testimonial-header">
                        <div class="stars">★★★★★</div>
                        <div class="client-info">
                            <h4><?= htmlspecialchars($t['name']) ?></h4>
                            <p class="client-title"><?= htmlspecialchars($t['title']) ?></p>
                        </div>
                    </div>
                    <p class="testimonial-text">"<?= htmlspecialchars($t['text']) ?>"</p>
                    <div class="testimonial-footer">
                        <span class="testimonial-service"><?= htmlspecialchars($t['service']) ?></span>
                        <span class="testimonial-date"><?= htmlspecialchars($t['date']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Testimonial -->
    <section class="section section-darker">
        <div class="container">
            <div class="featured-testimonial reveal">
                <div class="quote-mark">"</div>
                <p class="featured-quote">"Working with Reel and Roll was transformative for our brand. They didn't just take photos — they captured the essence of our vision and brought it to life in ways we couldn't have imagined. Their professionalism, creativity, and dedication to excellence sets them apart as the premier photography service in Dubai."</p>
                <div class="featured-client">
                    <div style="background: linear-gradient(135deg, #FF4D00, #CC3E00); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; margin-bottom: 1rem;">👤</div>
                    <h4>Dr. Khalid Al-Kitbi</h4>
                    <p class="featured-client-title">CEO, Luxury Hospitality Group</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section section">
        <div class="container">
            <div class="cta-content reveal">
                <h2>Ready to Create Your <span class="text-gradient">Success Story</span>?</h2>
                <p>Join hundreds of satisfied clients who've trusted us with their most important moments</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-primary"><span>Schedule Your Session</span></a>
                    <a href="portfolio.php" class="btn btn-secondary"><span>View Our Portfolio</span></a>
                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
