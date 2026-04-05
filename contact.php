<?php
$pageTitle       = 'Contact Us | Reel and Roll Photography';
$pageDescription = 'Contact Reel and Roll Photography — book your photography session in Dubai today.';
$activeNav       = 'contact';
require_once 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="fade-in-up">Get In <span class="text-gradient">Touch</span></h1>
            <div class="page-breadcrumb fade-in-up">
                <a href="/">Home</a>
                <span>/</span>
                <span>Contact</span>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Let's Work Together</p>
                <h2>Ready to Capture Your <span class="text-gradient">Moments</span>?</h2>
                <p>Fill out the form below and we'll get back to you within 24 hours</p>
            </div>
            <div class="contact-container">
                <form class="contact-form reveal" id="contactForm" action="mailer.php" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            <input type="text" id="name" name="name" required placeholder="John Doe">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required placeholder="john@example.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+971 545465757">
                        </div>
                        <div class="form-group">
                            <label for="service">Service Interested In *</label>
                            <select id="service" name="service" required>
                                <option value="">Select a service</option>
                                <option value="wedding">Wedding Photography</option>
                                <option value="portrait">Portrait Session</option>
                                <option value="event">Event Coverage</option>
                                <option value="landscape">Landscape Photography</option>
                                <option value="commercial">Commercial Photography</option>
                                <option value="editing">Photo Editing</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="timeline">Preferred Timeline</label>
                        <select id="timeline" name="timeline">
                            <option value="">Select a timeline</option>
                            <option value="immediately">Immediately</option>
                            <option value="1_month">Within 1 Month</option>
                            <option value="2_months">Within 2 Months</option>
                            <option value="3_months">Within 3 Months</option>
                            <option value="6_months">Within 6 Months</option>
                            <option value="flexible">Flexible / Not Sure Yet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message *</label>
                        <textarea id="message" name="message" required placeholder="Tell us about your project, event, or photography needs..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-submit">
                        <span>Send Message</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="section section-darker">
        <div class="container">
            <div class="section-header reveal">
                <h2>Other Ways to <span class="text-gradient">Reach Us</span></h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div class="service-card reveal">
                    <div class="service-icon">📧</div>
                    <h3>Email Us</h3>
                    <p style="color: var(--color-yellow); font-size: 1.1rem; margin-top: 1rem;">
                        <a href="mailto:reelrollphotography26@gmail.com">reelrollphotography26@gmail.com</a>
                    </p>
                    <p>We respond to all emails within 24 hours</p>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon">📱</div>
                    <h3>Call Us</h3>
                    <p style="color: var(--color-yellow); font-size: 1.1rem; margin-top: 1rem;">
                        <a href="tel:+971545465757">+971 545465757</a>
                    </p>
                    <p>Monday - Friday: 9AM - 6PM<br>Saturday: 10AM - 4PM</p>
                </div>
                <div class="service-card reveal">
                    <div class="service-icon">📍</div>
                    <h3>Visit Us</h3>
                    <p style="color: var(--color-yellow); font-size: 1.1rem; margin-top: 1rem;">
                        Bakhit Business Centre, Office No: M-88<br>
                        Arzoo Building, Near Al Tawar Centre<br>
                        Al Qusais, Dubai – UAE
                    </p>
                    <p>By appointment only</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="section section-darker">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-subtitle">Common Questions</p>
                <h2>Frequently Asked <span class="text-gradient">Questions</span></h2>
            </div>
            <div style="max-width: 900px; margin: 0 auto;">
                <div class="service-card reveal" style="margin-bottom: 1.5rem;">
                    <h3 style="color: var(--color-yellow);">How far in advance should I book?</h3>
                    <p>We recommend booking 3-6 months in advance for weddings and 2-4 weeks for portrait sessions.</p>
                </div>
                <div class="service-card reveal" style="margin-bottom: 1.5rem;">
                    <h3 style="color: var(--color-yellow);">How do I get started?</h3>
                    <p>Simply reach out via the contact form, WhatsApp, or email. We'll schedule a discovery call to discuss your vision and goals.</p>
                </div>
                <div class="service-card reveal" style="margin-bottom: 1.5rem;">
                    <h3 style="color: var(--color-yellow);">How long until I receive my photos?</h3>
                    <p>Edited photos are typically delivered within 2-3 weeks. Wedding photography may take 4-6 weeks.</p>
                </div>
                <div class="service-card reveal">
                    <h3 style="color: var(--color-yellow);">Do you travel for sessions?</h3>
                    <p>Yes! We travel locally and internationally. Travel fees may apply depending on the location.</p>
                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>

<!-- Contact form AJAX handler + auto-fill -->
<script>
(function () {
    // Auto-fill service and message from URL params
    const params = new URLSearchParams(window.location.search);
    const serviceParam = params.get('service');
    const fromParam   = params.get('from');

    const serviceMessages = {
        wedding:    'Hi, I\'m interested in Wedding Photography. I\'d love to discuss my wedding date, venue, and packages available.',
        portrait:   'Hi, I\'m interested in a Portrait Session. Please let me know about your availability and pricing.',
        event:      'Hi, I\'m interested in Event Coverage. I have an upcoming event I\'d like to discuss with you.',
        landscape:  'Hi, I\'m interested in Landscape Photography. I\'d love to learn more about your fine-art prints and commissions.',
        commercial: 'Hi, I\'m interested in Commercial Photography for my brand. I\'d like to discuss a photoshoot project.',
        corporate:  'Hi, I\'m interested in Corporate Photography — headshots, team photos, or office imagery. Please get in touch.',
        sports:     'Hi, I\'m interested in Sports Photography. I\'d like to discuss coverage for an upcoming sporting event or session.',
        editing:    'Hi, I\'m interested in your Photo Editing services. Please share details about your retouching packages.',
        videography:'Hi, I\'m interested in Videography services. I\'d love to discuss my project and your video packages.',
    };

    const key = serviceParam || fromParam;
    if (key) {
        const serviceSelect = document.getElementById('service');
        const messageField  = document.getElementById('message');

        // Pre-select the service dropdown
        if (serviceSelect && serviceMessages[key]) {
            for (const opt of serviceSelect.options) {
                if (opt.value === key) { opt.selected = true; break; }
            }
        }

        // Pre-fill message (editable)
        if (messageField && serviceMessages[key]) {
            messageField.value = serviceMessages[key];
        }
    }

    const form = document.getElementById('contactForm');
    if (!form) return;
    const btn = form.querySelector('.btn-submit');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const btnSpan = btn.querySelector('span');
        btnSpan.textContent = 'Sending...';
        btn.disabled = true;

        fetch('mailer.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json())
            .then(res => {
                showMsg(res.message, res.success ? 'success' : 'error');
                if (res.success) form.reset();
            })
            .catch(() => showMsg('Something went wrong. Please email us directly.', 'error'))
            .finally(() => { btnSpan.textContent = 'Send Message'; btn.disabled = false; });
    });

    function showMsg(text, type) {
        let el = document.getElementById('formMsg');
        if (!el) {
            el = document.createElement('div');
            el.id = 'formMsg';
            el.style.cssText = 'margin-top:1rem;padding:1rem 1.5rem;border-radius:8px;font-weight:500;';
            btn.insertAdjacentElement('afterend', el);
        }
        el.textContent = text;
        el.style.background = type === 'success' ? 'rgba(72,199,142,0.12)' : 'rgba(255,77,0,0.12)';
        el.style.color      = type === 'success' ? '#48c78e' : '#FF4D00';
        el.style.border     = '1px solid ' + (type === 'success' ? 'rgba(72,199,142,0.3)' : 'rgba(255,77,0,0.3)');
        setTimeout(() => { el.style.display = 'none'; }, 7000);
    }
})();
</script>
