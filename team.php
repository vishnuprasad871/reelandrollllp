<?php
$pageTitle       = 'Our Team | Reel and Roll Photography';
$pageDescription = 'Meet the award-winning photographers and creative professionals behind Reel and Roll Photography in Dubai.';
$activeNav       = '';
require_once 'includes/header.php';

$team = [
    ['name' => 'Ahmed Al-Mansouri', 'role' => 'Founder & Lead Photographer',          'bio' => '20+ years capturing Dubai\'s most prestigious moments. Award-winning wedding and event photographer with a passion for storytelling.', 'img' => 'assets/portfolio_wedding_1770402245749.png',   'tags' => ['Wedding', 'Portrait', 'Events']],
    ['name' => 'Fatima Al-Naqbi',   'role' => 'Senior Photographer & Creative Director','bio' => 'Specializing in luxury brand photography and commercial shoots. Known for innovative compositions and exceptional attention to detail.', 'img' => 'assets/portfolio_portrait_1770402268053.png',  'tags' => ['Commercial', 'Fashion', 'Lifestyle']],
    ['name' => 'Mohammed Hassan',   'role' => 'Event & Documentary Photographer',      'bio' => 'Captures the energy and emotion of live events with cinematic flair. Expert in multi-camera coverage and real-time storytelling.', 'img' => 'assets/portfolio_event_1770402304065.png',    'tags' => ['Events', 'Documentary', 'Corporate']],
    ['name' => 'Leila Al-Shamsi',   'role' => 'Post-Production & Retouching Specialist','bio' => 'Master of color grading and retouching with 10+ years experience. Transforms raw captures into gallery-ready masterpieces.', 'img' => 'assets/portfolio_landscape_1770402286898.png', 'tags' => ['Retouching', 'Color Grading', 'Editing']],
    ['name' => 'Amira Al-Mazrouei', 'role' => 'Assistant Photographer & Producer',     'bio' => 'Ensures seamless shoots with meticulous planning and on-site coordination. Brings technical expertise and creative problem-solving.', 'img' => 'assets/portfolio_wedding_1770402245749.png',   'tags' => ['Coordination', 'Lighting', 'Production']],
    ['name' => 'Sara Al-Kaabi',     'role' => 'Client Relations & Project Manager',    'bio' => 'Dedicated to understanding client vision and delivering exceptional service. Ensures every project exceeds expectations from start to finish.', 'img' => 'assets/portfolio_portrait_1770402268053.png', 'tags' => ['Client Service', 'Project Mgmt', 'Consultation']],
];
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Our Creative <span class="text-gradient">Team</span></h1>
            <p class="page-subtitle fade-in-up">Award-winning photographers and creative professionals dedicated to excellence</p>
            <div class="page-breadcrumb fade-in-up">
                <a href="index.php">Home</a>
                <span>/</span>
                <span>Team</span>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Our Crew</p>
                <h2>Meet the <span class="text-gradient">Masters</span></h2>
                <p>Over 50+ years of combined photography experience and passion</p>
            </div>

            <div class="team-grid">
                <?php foreach ($team as $member): ?>
                <div class="team-card reveal">
                    <div class="team-image">
                        <img src="<?= htmlspecialchars($member['img']) ?>" alt="<?= htmlspecialchars($member['name']) ?>">
                        <div class="team-overlay">
                            <div class="team-socials">
                                <a href="#" class="social-icon">📸</a>
                                <a href="#" class="social-icon">💼</a>
                            </div>
                        </div>
                    </div>
                    <div class="team-info">
                        <h3><?= htmlspecialchars($member['name']) ?></h3>
                        <p class="team-position"><?= htmlspecialchars($member['role']) ?></p>
                        <p class="team-bio"><?= htmlspecialchars($member['bio']) ?></p>
                        <div class="team-expertise">
                            <?php foreach ($member['tags'] as $tag): ?>
                            <span class="expertise-tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="section section-darker">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Our Philosophy</p>
                <h2>What Drives <span class="text-gradient">Us</span></h2>
            </div>
            <div class="values-grid">
                <div class="value-card reveal"><div class="value-icon">🎯</div><h3>Excellence</h3><p>Every frame is crafted with meticulous attention to detail and uncompromising quality standards.</p></div>
                <div class="value-card reveal"><div class="value-icon">💡</div><h3>Innovation</h3><p>We constantly evolve our techniques, embrace new technologies, and push creative boundaries.</p></div>
                <div class="value-card reveal"><div class="value-icon">🤝</div><h3>Collaboration</h3><p>Your vision is our priority. We work closely with clients to bring their dreams to life.</p></div>
                <div class="value-card reveal"><div class="value-icon">⚡</div><h3>Passion</h3><p>Our love for photography fuels our dedication to creating unforgettable visual experiences.</p></div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section section">
        <div class="container">
            <div class="cta-content reveal">
                <h2>Ready to Create <span class="text-gradient">Magic</span> Together?</h2>
                <p>Get in touch with our team to discuss your project and vision</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="btn btn-primary"><span>Schedule Consultation</span></a>
                    <a href="portfolio.php" class="btn btn-secondary"><span>View Our Work</span></a>
                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
