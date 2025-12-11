/**
 * Smooth Scroll Module
 * 
 * Плавная прокрутка по якорным ссылкам
 */

export function initSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  
  links.forEach(link => {
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      
      if (href === '#' || !href) return;
      
      const target = document.querySelector(href);
      
      if (target) {
        e.preventDefault();
        
        const header = document.querySelector('.tgg-header');
        const headerHeight = header ? header.offsetHeight : 80;
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
        
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
        
        // Update URL without jumping
        if (history.pushState) {
          history.pushState(null, null, href);
        }
      }
    });
  });
}



