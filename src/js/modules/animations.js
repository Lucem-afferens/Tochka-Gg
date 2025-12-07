/**
 * Animations Module
 * 
 * Анимации появления элементов при скролле
 */

export function initScrollAnimations() {
  const animatedElements = document.querySelectorAll(
    '.tgg-advantages__item, .tgg-services__item, .tgg-archive__item, .tgg-card'
  );
  
  if (animatedElements.length === 0) return;
  
  const observerOptions = {
    root: null,
    rootMargin: '0px 0px -100px 0px',
    threshold: 0.1
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.classList.add('tgg-animate-fade-in-up');
          observer.unobserve(entry.target);
        }, index * 100); // Задержка для последовательного появления
      }
    });
  }, observerOptions);
  
  animatedElements.forEach(element => {
    observer.observe(element);
  });
}

/**
 * Parallax эффект для hero секции (оптимизирован с throttle)
 */
export function initParallax() {
  const hero = document.querySelector('.tgg-hero');
  if (!hero) return;
  
  const heroBg = hero.querySelector('.tgg-hero__bg');
  if (!heroBg) return;
  
  let ticking = false;
  
  function updateParallax() {
    const scrolled = window.pageYOffset;
    const rate = scrolled * 0.5;
    
    heroBg.style.transform = `translateY(${rate}px)`;
    ticking = false;
  }
  
  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(updateParallax);
      ticking = true;
    }
  }, { passive: true });
}


