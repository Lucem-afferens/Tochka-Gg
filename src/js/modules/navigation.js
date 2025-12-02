/**
 * Navigation Module
 * 
 * Мобильное меню, навигация, активные ссылки
 */

export function initNavigation() {
  const burger = document.querySelector('.tgg-header__burger');
  const nav = document.querySelector('.tgg-header__nav');
  const header = document.querySelector('.tgg-header');
  
  if (!burger || !nav) return;
  
  // Toggle mobile menu
  burger.addEventListener('click', () => {
    const isExpanded = burger.getAttribute('aria-expanded') === 'true';
    burger.setAttribute('aria-expanded', !isExpanded);
    nav.classList.toggle('active');
    burger.classList.toggle('active');
    document.body.classList.toggle('menu-open');
  });
  
  // Header scroll effect
  let lastScroll = 0;
  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
    
    lastScroll = currentScroll;
  });
  
  // Close menu on link click
  const navLinks = nav.querySelectorAll('a');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      nav.classList.remove('active');
      burger.classList.remove('active');
      burger.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('menu-open');
    });
  });
  
  // Active menu item on scroll
  const sections = document.querySelectorAll('section[id]');
  const observerOptions = {
    rootMargin: '-20% 0px -70% 0px',
    threshold: 0
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.getAttribute('id');
        navLinks.forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === `#${id}`) {
            link.classList.add('active');
          }
        });
      }
    });
  }, observerOptions);
  
  sections.forEach(section => observer.observe(section));
}


