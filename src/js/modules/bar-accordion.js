/**
 * Bar Accordion Module
 * 
 * Простой и надежный аккордеон для категорий клубного бара
 * Использует минимальную логику для максимальной производительности
 */

export function initBarAccordion() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

  const categories = barSection.querySelectorAll('.tgg-bar__category');
  if (categories.length === 0) return;

  // Определяем мобильное устройство один раз
  const isMobile = window.innerWidth < 768;
  
  // Простая функция переключения категории
  function toggleCategory(button, category, items) {
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
      // Используем один обработчик с проверкой на реальный клик
      let clickStartTime = 0;
      let clickStartY = 0;
      let isClick = true;
      
      button.addEventListener('mousedown', (e) => {
        clickStartTime = Date.now();
        clickStartY = e.clientY || 0;
        isClick = true;
      }, { passive: true });
      
      button.addEventListener('mouseup', (e) => {
        const clickDuration = Date.now() - clickStartTime;
        const moveDistance = Math.abs((e.clientY || 0) - clickStartY);
        
        // Если клик был быстрым и без движения - это реальный клик
        if (clickDuration < 300 && moveDistance < 10 && isClick) {
          e.preventDefault();
          e.stopPropagation();
          toggleCategory(button, category, items);
        }
      }, { passive: false });
      
      // Для touch устройств
      button.addEventListener('touchstart', (e) => {
        const touch = e.touches[0];
        if (touch) {
          clickStartTime = Date.now();
          clickStartY = touch.clientY;
          isClick = true;
        }
      }, { passive: true });
      
      button.addEventListener('touchmove', () => {
        isClick = false; // Если есть движение - это не клик
      }, { passive: true });
      
      button.addEventListener('touchend', (e) => {
        const touch = e.changedTouches[0];
        if (touch) {
          const clickDuration = Date.now() - clickStartTime;
          const moveDistance = Math.abs(touch.clientY - clickStartY);
          
          // Если touch был быстрым и без движения - это реальный клик
          if (clickDuration < 300 && moveDistance < 10 && isClick) {
            e.preventDefault();
            e.stopPropagation();
            toggleCategory(button, category, items);
          }
        }
        isClick = true; // Сбрасываем флаг
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
