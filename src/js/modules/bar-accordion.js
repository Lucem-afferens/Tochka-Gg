/**
 * Bar Accordion Module
 * 
 * Надежный аккордеон для категорий клубного бара
 * Полностью защищен от случайных срабатываний при скролле и взаимодействии с карточками
 */

export function initBarAccordion() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

  const categoryButtons = barSection.querySelectorAll('[data-category-toggle]');
  if (categoryButtons.length === 0) return;

  // Определяем мобильное устройство
  const isMobile = () => window.innerWidth < 768;
  
  // Глобальный флаг для отслеживания скролла
  let isScrolling = false;
  let scrollTimeout;
  
  // Отслеживаем скролл страницы
  let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
  window.addEventListener('scroll', () => {
    const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Если есть реальное движение скролла
    if (Math.abs(currentScrollTop - lastScrollTop) > 1) {
      isScrolling = true;
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        isScrolling = false;
      }, 300);
    }
    
    lastScrollTop = currentScrollTop;
  }, { passive: true });
  
  // Предотвращаем всплытие событий от карточек товаров
  const itemCards = barSection.querySelectorAll('.tgg-bar__item');
  itemCards.forEach((card) => {
    // Блокируем все события на карточках от всплытия
    ['touchstart', 'touchmove', 'touchend', 'touchcancel', 'click'].forEach((eventType) => {
      card.addEventListener(eventType, (e) => {
        // Разрешаем события только внутри карточки, но не всплывающие
        e.stopPropagation();
      }, { passive: true });
    });
    
    // Блокируем события на изображениях
    const images = card.querySelectorAll('img');
    images.forEach((img) => {
      ['touchstart', 'touchmove', 'touchend', 'touchcancel', 'click'].forEach((eventType) => {
        img.addEventListener(eventType, (e) => {
          e.stopPropagation();
        }, { passive: true });
      });
    });
  });
  
  // Функция переключения категории
  function toggleCategory(button, event) {
    // КРИТИЧНО: Не переключаем, если страница скроллится
    if (isScrolling) {
      if (event) {
        event.preventDefault();
        event.stopPropagation();
      }
      return false;
    }
    
    // Проверяем, что событие произошло именно на кнопке или её прямых дочерних элементах
    if (event) {
      const target = event.target;
      const isButton = target === button;
      const isButtonChild = button.contains(target) && (
        target.classList.contains('tgg-bar__category-title') ||
        target.classList.contains('tgg-bar__category-toggle-icon')
      );
      
      // Если клик был не на кнопке и не на её прямых дочерних элементах - игнорируем
      if (!isButton && !isButtonChild) {
        event.preventDefault();
        event.stopPropagation();
        return false;
      }
    }
    
    const category = button.closest('.tgg-bar__category');
    if (!category) return false;
    
    const items = category.querySelector('.tgg-bar__items');
    if (!items) return false;
    
    const isOpen = category.classList.contains('is-open');
    const willBeOpen = !isOpen;
    
    // Обновляем состояние
    button.setAttribute('aria-expanded', willBeOpen);
    category.classList.toggle('is-open', willBeOpen);
    items.classList.toggle('is-open', willBeOpen);
    
    return true;
  }
  
  // Инициализация категорий
  categoryButtons.forEach((button) => {
    const category = button.closest('.tgg-bar__category');
    if (!category) return;
    
    const items = category.querySelector('.tgg-bar__items');
    if (!items) return;
    
    // Начальное состояние: на мобильных закрыто, на десктопе открыто
    if (isMobile()) {
      category.classList.remove('is-open');
      items.classList.remove('is-open');
      button.setAttribute('aria-expanded', 'false');
    } else {
      category.classList.add('is-open');
      items.classList.add('is-open');
      button.setAttribute('aria-expanded', 'true');
    }
    
    // Только для мобильных устройств
    if (isMobile()) {
      // Флаги для отслеживания touch событий
      let touchStartTime = 0;
      let touchStartY = 0;
      let touchStartX = 0;
      let hasMoved = false;
      let touchTarget = null;
      
      // Touch start - только на самой кнопке
      button.addEventListener('touchstart', (e) => {
        // Проверяем, что touch начался именно на кнопке
        const target = e.target;
        if (target.closest('.tgg-bar__item')) {
          return; // Игнорируем, если touch начался на карточке
        }
        
        if (isScrolling) {
          e.preventDefault();
          e.stopPropagation();
          return;
        }
        
        const touch = e.touches[0];
        if (touch) {
          touchStartTime = Date.now();
          touchStartY = touch.clientY;
          touchStartX = touch.clientX;
          hasMoved = false;
          touchTarget = target;
        }
      }, { passive: true });
      
      // Touch move
      button.addEventListener('touchmove', (e) => {
        if (!touchTarget) return;
        
        const touch = e.touches[0];
        if (touch) {
          const moveY = Math.abs(touch.clientY - touchStartY);
          const moveX = Math.abs(touch.clientX - touchStartX);
          
          // Если движение больше 10px - это скролл
          if (moveY > 10 || moveX > 10) {
            hasMoved = true;
          }
        }
      }, { passive: true });
      
      // Touch end
      button.addEventListener('touchend', (e) => {
        if (!touchTarget) return;
        
        // Проверяем, что touch закончился на кнопке
        const target = e.changedTouches[0]?.target || e.target;
        if (target.closest('.tgg-bar__item')) {
          touchTarget = null;
          return; // Игнорируем, если touch закончился на карточке
        }
        
        // КРИТИЧНО: Не обрабатываем, если страница скроллится
        if (isScrolling) {
          touchTarget = null;
          e.preventDefault();
          e.stopPropagation();
          return;
        }
        
        const touch = e.changedTouches[0];
        if (touch) {
          const touchDuration = Date.now() - touchStartTime;
          const moveY = Math.abs(touch.clientY - touchStartY);
          const moveX = Math.abs(touch.clientX - touchStartX);
          
          // Только если это был быстрый тап без движения
          if (touchDuration < 400 && !hasMoved && moveY < 10 && moveX < 10) {
            e.preventDefault();
            e.stopPropagation();
            toggleCategory(button, e);
          }
        }
        
        // Сбрасываем флаги
        touchTarget = null;
        hasMoved = false;
        touchStartTime = 0;
        touchStartY = 0;
        touchStartX = 0;
      }, { passive: false });
      
      // Touch cancel
      button.addEventListener('touchcancel', () => {
        touchTarget = null;
        hasMoved = false;
        touchStartTime = 0;
        touchStartY = 0;
        touchStartX = 0;
      }, { passive: true });
    }
    
    // Mouse события (для десктопов с touch экраном и тестирования)
    button.addEventListener('click', (e) => {
      // На мобильных обрабатываем только если не было touch события
      if (isMobile()) {
        // Проверяем, что клик был именно на кнопке
        const target = e.target;
        if (target.closest('.tgg-bar__item')) {
          e.preventDefault();
          e.stopPropagation();
          return;
        }
        
        // КРИТИЧНО: Не обрабатываем, если страница скроллится
        if (isScrolling) {
          e.preventDefault();
          e.stopPropagation();
          return;
        }
      }
      
      e.preventDefault();
      e.stopPropagation();
      toggleCategory(button, e);
    });
  });
  
  // Обработка изменения размера окна
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      const nowIsMobile = isMobile();
      
      categoryButtons.forEach((button) => {
        const category = button.closest('.tgg-bar__category');
        if (!category) return;
        
        const items = category.querySelector('.tgg-bar__items');
        if (!items) return;
        
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
