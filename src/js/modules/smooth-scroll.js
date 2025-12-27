/**
 * Smooth Scroll Module
 * 
 * Плавная прокрутка по якорным ссылкам
 */

export function initSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  
  // Флаг для отслеживания скролла (предотвращает случайные срабатывания)
  let isScrolling = false;
  let scrollTimeout;
  
  // Отслеживаем скролл страницы
  window.addEventListener('scroll', () => {
    isScrolling = true;
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      isScrolling = false;
    }, 150);
  }, { passive: true });
  
  links.forEach(link => {
    // Проверяем, не добавлен ли уже обработчик (защита от дублирования)
    if (link.dataset.smoothScrollHandlerAdded) return;
    link.dataset.smoothScrollHandlerAdded = 'true';
    
    link.addEventListener('click', (e) => {
      const href = link.getAttribute('href');
      
      // Пропускаем пустые якоря и ссылки только с #
      if (!href || href === '#' || href.length <= 1) return;
      
      // КРИТИЧНО: Если страница скроллится - не обрабатываем клик
      if (isScrolling) {
        e.preventDefault();
        e.stopPropagation();
        return;
      }
      
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
              // КРИТИЧНО: Проверяем, что мы не на странице бара (где нет якорных секций)
              const isBarPage = document.querySelector('.tgg-bar');
              if (!isBarPage) {
                history.pushState(null, '', currentUrl.pathname + href);
              }
            }
          } catch (error) {
            console.warn('Error updating URL:', error);
          }
        } else {
          // Если элемент не найден, не делаем ничего (предотвращаем перезагрузку)
          e.preventDefault();
          e.stopPropagation();
          console.warn('Target element not found for:', href);
        }
      } catch (error) {
        e.preventDefault();
        e.stopPropagation();
        console.error('Error in smooth scroll:', error);
      }
    }, { passive: false });
  });
}



