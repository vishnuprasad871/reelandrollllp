// ===================================
// REEL AND ROLL PHOTOGRAPHY
// JavaScript Functionality - Multi-Page
// ===================================

// ===================================
// MOBILE MENU TOGGLE
// ===================================
const menuToggle = document.getElementById('menuToggle');
const navLinks = document.getElementById('navLinks');

if (menuToggle && navLinks) {
  menuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    menuToggle.classList.toggle('active');
  });

  // Close menu when clicking on a link
  navLinks.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      navLinks.classList.remove('active');
      menuToggle.classList.remove('active');
    });
  });
}

// ===================================
// NAVBAR SCROLL EFFECT
// ===================================
const navbar = document.getElementById('navbar');

window.addEventListener('scroll', () => {
  if (window.scrollY > 100) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
});

// ===================================
// ACTIVE PAGE HIGHLIGHTING
// ===================================
const currentPage = window.location.pathname.split('/').pop() || 'index.html';
document.querySelectorAll('.nav-links a').forEach(link => {
  const href = link.getAttribute('href');
  if (href === currentPage || (currentPage === '' && href === 'index.html')) {
    link.classList.add('active');
  }
});

// ===================================
// SMOOTH SCROLLING
// ===================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      const offsetTop = target.offsetTop - 80;
      window.scrollTo({
        top: offsetTop,
        behavior: 'smooth'
      });
    }
  });
});

// ===================================
// SCROLL REVEAL ANIMATION
// ===================================
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('active');
      observer.unobserve(entry.target);
    }
  });
}, observerOptions);

// Observe all elements with the 'reveal' class
document.querySelectorAll('.reveal').forEach(element => {
  observer.observe(element);
});

// ===================================
// GALLERY FILTERING
// ===================================
const filterBtns = document.querySelectorAll('.filter-btn');
const galleryItems = document.querySelectorAll('.gallery-item');

if (filterBtns.length > 0) {
  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      // Remove active class from all buttons
      filterBtns.forEach(b => b.classList.remove('active'));
      // Add active class to clicked button
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');

      // Filter gallery items
      galleryItems.forEach(item => {
        const category = item.getAttribute('data-category');

        if (filter === 'all' || category === filter) {
          item.style.display = 'block';
          setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'scale(1)';
          }, 10);
        } else {
          item.style.opacity = '0';
          item.style.transform = 'scale(0.8)';
          setTimeout(() => {
            item.style.display = 'none';
          }, 300);
        }
      });
    });
  });
}

// ===================================
// FORM VALIDATION & SUBMISSION
// ===================================
const contactForm = document.getElementById('contactForm');

if (contactForm) {
  contactForm.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form values
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone')?.value || '';
    const service = document.getElementById('service')?.value || '';
    const message = document.getElementById('message').value;

    // Basic validation
    if (!name || !email || !message) {
      showNotification('Please fill in all required fields.', 'error');
      return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showNotification('Please enter a valid email address.', 'error');
      return;
    }

    // Simulate form submission
    const submitButton = contactForm.querySelector('.btn-submit');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<span>Sending...</span>';
    submitButton.disabled = true;

    // Simulate API call
    setTimeout(() => {
      showNotification('Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.', 'success');
      contactForm.reset();
      submitButton.innerHTML = originalText;
      submitButton.disabled = false;
    }, 2000);
  });
}

// ===================================
// NOTIFICATION SYSTEM
// ===================================
function showNotification(message, type = 'success') {
  // Remove existing notification if any
  const existingNotification = document.querySelector('.notification');
  if (existingNotification) {
    existingNotification.remove();
  }

  // Create notification element
  const notification = document.createElement('div');
  notification.className = `notification notification-${type}`;
  notification.textContent = message;

  // Add styles
  Object.assign(notification.style, {
    position: 'fixed',
    top: '20px',
    right: '20px',
    padding: '1rem 2rem',
    background: type === 'success' ? 'linear-gradient(135deg, #C9A961 0%, #B89850 100%)' : '#ef4444',
    color: type === 'success' ? '#000000' : '#ffffff',
    borderRadius: '8px',
    boxShadow: type === 'success' ? '0 0 30px rgba(201, 169, 97, 0.5)' : '0 4px 16px rgba(0, 0, 0, 0.3)',
    zIndex: '10000',
    animation: 'slideInRight 0.3s ease',
    fontSize: '1rem',
    fontWeight: '600',
    maxWidth: '400px',
    border: type === 'success' ? '2px solid #C9A961' : '2px solid #ef4444'
  });

  // Add to document
  document.body.appendChild(notification);

  // Remove after 5 seconds
  setTimeout(() => {
    notification.style.animation = 'slideOutRight 0.3s ease';
    setTimeout(() => notification.remove(), 300);
  }, 5000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
  @keyframes slideInRight {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes slideOutRight {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);

// ===================================
// STATS COUNTER ANIMATION
// ===================================
function animateCounter(element, target, duration = 2000) {
  const start = 0;
  const increment = target / (duration / 16);
  let current = start;

  const timer = setInterval(() => {
    current += increment;
    if (current >= target) {
      element.textContent = target + '+';
      clearInterval(timer);
    } else {
      element.textContent = Math.floor(current) + '+';
    }
  }, 16);
}

// Observe stats section and trigger counter animation
const statsObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const stats = entry.target.querySelectorAll('.stat-number');
      stats.forEach(stat => {
        const text = stat.textContent.replace('+', '');
        const value = parseInt(text);
        if (!isNaN(value)) {
          animateCounter(stat, value);
        }
      });
      statsObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

const statsSection = document.querySelector('.stats');
if (statsSection) {
  statsObserver.observe(statsSection);
}

// ===================================
// PARALLAX EFFECT ON HERO
// ===================================
const heroBackground = document.querySelector('.hero-background');

if (heroBackground) {
  window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = scrolled * 0.5;
    heroBackground.style.transform = `translateY(${parallax}px)`;
  });
}

// ===================================
// FEATURED ITEMS INTERACTION
// ===================================
const featuredItems = document.querySelectorAll('.featured-item');

featuredItems.forEach(item => {
  item.addEventListener('click', function () {
    const title = this.querySelector('.featured-title')?.textContent || 'Project';
    console.log(`Clicked on: ${title}`);
    // Could implement lightbox or modal here
  });
});

// ===================================
// SERVICE CARDS HOVER ENHANCEMENT
// ===================================
const serviceCards = document.querySelectorAll('.service-card');

serviceCards.forEach(card => {
  card.addEventListener('mouseenter', function () {
    this.style.transition = 'all 0.3s ease';
  });
});

// ===================================
// PAGE LOAD ANIMATION
// ===================================
window.addEventListener('load', () => {
  document.body.style.opacity = '0';
  setTimeout(() => {
    document.body.style.transition = 'opacity 0.5s ease';
    document.body.style.opacity = '1';
  }, 100);
});

// ===================================
// PREVENT FOUC (Flash of Unstyled Content)
// ===================================
document.addEventListener('DOMContentLoaded', () => {
  document.body.style.visibility = 'visible';
});

// ===================================
// BACK TO TOP BUTTON (Optional Enhancement)
// ===================================
const createBackToTop = () => {
  const button = document.createElement('button');
  button.innerHTML = '↑';
  button.className = 'back-to-top';
  Object.assign(button.style, {
    position: 'fixed',
    bottom: '30px',
    right: '30px',
    width: '50px',
    height: '50px',
    background: 'linear-gradient(135deg, #C9A961 0%, #B89850 100%)',
    color: '#000000',
    border: '2px solid #C9A961',
    borderRadius: '50%',
    fontSize: '1.5rem',
    fontWeight: 'bold',
    cursor: 'pointer',
    display: 'none',
    zIndex: '999',
    boxShadow: '0 0 20px rgba(201, 169, 97, 0.5)',
    transition: 'all 0.3s ease'
  });

  button.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  button.addEventListener('mouseenter', () => {
    button.style.transform = 'translateY(-5px) scale(1.1)';
    button.style.boxShadow = '0 0 30px rgba(255, 215, 0, 0.7)';
  });

  button.addEventListener('mouseleave', () => {
    button.style.transform = 'translateY(0) scale(1)';
    button.style.boxShadow = '0 0 20px rgba(255, 215, 0, 0.5)';
  });

  document.body.appendChild(button);

  window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
      button.style.display = 'block';
    } else {
      button.style.display = 'none';
    }
  });
};

// Initialize back to top button
createBackToTop();

// ===================================
// CONSOLE BRANDING (Easter Egg)
// ===================================
console.log('%c📸 Reel & Roll Photography', 'color: #C9A961; font-size: 24px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);');
console.log('%cCapturing Moments, Creating Memories', 'color: #a0a0a0; font-size: 14px;');
console.log('%cWebsite designed with ❤️ and ☕', 'color: #C9A961; font-size: 12px;');
