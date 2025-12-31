/**
 * Smooth Scroll Module
 * 
 * Плавная прокрутка по якорным ссылкам
 */

export function initSmoothScroll() {
  // Проверяем, не на странице бара (где нет якорных ссылок)
  const isBarPage = document.querySelector('.tgg-bar[data-bar-page="true"]');
  if (isBarPage) {
    // На странице бара не инициализируем smooth scroll
    return;
  }
  
  const links = document.querySelectorAll('a[href^="#"]');
  if (links.length === 0) return;
  
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
          try {
            if (history.pushState && target) {
              const currentUrl = new URL(window.location.href);
              history.pushState(null, '', currentUrl.pathname + href);
            }
          } catch (error) {
            // Ошибка обновления URL - игнорируем
          }
        } else {
          // Если элемент не найден, предотвращаем перезагрузку
          e.preventDefault();
          e.stopPropagation();
        }
      } catch (error) {
        e.preventDefault();
        e.stopPropagation();
        // Ошибка в smooth scroll - игнорируем
      }
    }, { passive: false });
  });
}



