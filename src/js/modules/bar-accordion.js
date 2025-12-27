/**
 * Bar Accordion Module
 * 
 * Простой и надежный аккордеон для категорий клубного бара
 * Защищен от случайных срабатываний при скролле
 */

export function initBarAccordion() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

  const categories = barSection.querySelectorAll('.tgg-bar__category');
  if (categories.length === 0) return;

  // Определяем мобильное устройство один раз
  const isMobile = window.innerWidth < 768;
  
  // Глобальный флаг для отслеживания скролла
  let isScrolling = false;
  let scrollTimeout;
  
  // Отслеживаем скролл страницы
  window.addEventListener('scroll', () => {
    isScrolling = true;
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      isScrolling = false;
    }, 200); // Увеличено до 200ms для надежности
  }, { passive: true });
  
  // Простая функция переключения категории
  function toggleCategory(button, category, items) {
    // КРИТИЧНО: Не переключаем, если страница скроллится
    if (isScrolling) {
      return;
    }
    
    const isOpen = category.classList.contains('is-open');
    const willBeOpen = !isOpen;
    
    // Обновляем состояние
    button.setAttribute('aria-expanded', willBeOpen);
    category.classList.toggle('is-open', willBeOpen);
    items.classList.toggle('is-open', willBeOpen);
  }
  
  // Инициализация категорий
  categories.forEach((category) => {
    const button = category.querySelector('[data-category-toggle]');
    const items = category.querySelector('.tgg-bar__items');
    
    if (!button || !items) return;
    
    // Начальное состояние: на мобильных закрыто, на десктопе открыто
    if (isMobile) {
      category.classList.remove('is-open');
      items.classList.remove('is-open');
      button.setAttribute('aria-expanded', 'false');
    } else {
      category.classList.add('is-open');
      items.classList.add('is-open');
      button.setAttribute('aria-expanded', 'true');
    }
    
    // Простой обработчик клика - только для мобильных
    if (isMobile) {
      // Флаги для отслеживания touch событий
      let touchStartTime = 0;
      let touchStartY = 0;
      let touchStartX = 0;
      let hasMoved = false;
      let isHandlingTouch = false;
      
      // Touch start
      button.addEventListener('touchstart', (e) => {
        if (isScrolling) return;
        
        const touch = e.touches[0];
        if (touch) {
          touchStartTime = Date.now();
          touchStartY = touch.clientY;
          touchStartX = touch.clientX;
          hasMoved = false;
          isHandlingTouch = true;
        }
      }, { passive: true });
      
      // Touch move - если есть движение, это скролл
      button.addEventListener('touchmove', (e) => {
        if (!isHandlingTouch) return;
        
        const touch = e.touches[0];
        if (touch) {
          const moveY = Math.abs(touch.clientY - touchStartY);
          const moveX = Math.abs(touch.clientX - touchStartX);
          
          // Если движение больше 5px - это скролл
          if (moveY > 5 || moveX > 5) {
            hasMoved = true;
          }
        }
      }, { passive: true });
      
      // Touch end
      button.addEventListener('touchend', (e) => {
        if (!isHandlingTouch) return;
        isHandlingTouch = false;
        
        // КРИТИЧНО: Не обрабатываем, если страница скроллится
        if (isScrolling) {
          hasMoved = false;
          return;
        }
        
        const touch = e.changedTouches[0];
        if (touch) {
          const touchDuration = Date.now() - touchStartTime;
          const moveY = Math.abs(touch.clientY - touchStartY);
          const moveX = Math.abs(touch.clientX - touchStartX);
          
          // Только если это был быстрый тап без движения
          if (touchDuration < 300 && !hasMoved && moveY < 5 && moveX < 5) {
            e.preventDefault();
            e.stopPropagation();
            toggleCategory(button, category, items);
          }
        }
        
        // Сбрасываем флаги
        hasMoved = false;
        touchStartTime = 0;
        touchStartY = 0;
        touchStartX = 0;
      }, { passive: false });
      
      // Mouse события (для десктопов с touch экраном)
      button.addEventListener('click', (e) => {
        // КРИТИЧНО: Не обрабатываем, если страница скроллится
        if (isScrolling) {
          e.preventDefault();
          e.stopPropagation();
          return;
        }
        
        // Проверяем, не было ли недавнего touch события
        const timeSinceTouch = Date.now() - touchStartTime;
        if (timeSinceTouch < 500 && hasMoved) {
          // Это был скролл, игнорируем клик
          e.preventDefault();
          e.stopPropagation();
          return;
        }
        
        e.preventDefault();
        e.stopPropagation();
        toggleCategory(button, category, items);
      }, { passive: false });
    }
  });
  
  // Обработка изменения размера окна
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      const nowIsMobile = window.innerWidth < 768;
      
      categories.forEach((category) => {
        const button = category.querySelector('[data-category-toggle]');
        const items = category.querySelector('.tgg-bar__items');
        
        if (!button || !items) return;
        
        const wasOpen = category.classList.contains('is-open');
        const shouldBeOpen = !nowIsMobile;
        
        if (wasOpen !== shouldBeOpen) {
          button.setAttribute('aria-expanded', shouldBeOpen);
          if (shouldBeOpen) {
            category.classList.add('is-open');
            items.classList.add('is-open');
          } else {
            category.classList.remove('is-open');
            items.classList.remove('is-open');
          }
        }
      });
    }, 200);
  }, { passive: true });
}
