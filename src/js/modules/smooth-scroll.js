/**
 * Smooth Scroll Module
 * 
 * Плавная прокрутка по якорным ссылкам
 */

export function initSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  
  links.forEach(link => {
    // Проверяем, не добавлен ли уже обработчик (защита от дублирования)
    if (link.dataset.smoothScrollHandlerAdded) return;
    link.dataset.smoothScrollHandlerAdded = 'true';
    
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      
      // Пропускаем пустые якоря и ссылки только с #
      if (!href || href === '#' || href.length <= 1) return;
      
      try {
        const target = document.querySelector(href);
        
        if (target) {
          e.preventDefault();
          e.stopPropagation();
          
          const header = document.querySelector('.tgg-header');
          const headerHeight = header ? header.offsetHeight : 80;
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
          
          // Update URL without reloading page
          // ВАЖНО: Обновляем URL только если элемент существует на странице
          // Это предотвращает перезагрузку страницы при клике на несуществующие якоря
          try {
            if (history.pushState && target) {
              const currentUrl = new URL(window.location.href);
              // Обновляем только hash, не трогая pathname
              history.pushState(null, '', currentUrl.pathname + href);
            }
          } catch (error) {
            console.warn('Error updating URL:', error);
          }
        } else {
          // Если элемент не найден, не делаем ничего (предотвращаем перезагрузку)
          console.warn('Target element not found for:', href);
        }
      } catch (error) {
        console.error('Error in smooth scroll:', error);
      }
    });
  });
}



