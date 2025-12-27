/**
 * Bar Accordion Module
 * 
 * Аккордеон для категорий клубного бара
 * Оптимизирован для производительности и плавных анимаций
 * Защищен от случайных срабатываний при скролле
 */

export function initBarAccordion() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

  const categories = barSection.querySelectorAll('.tgg-bar__category');
  if (categories.length === 0) return;

  // Проверяем настройки пользователя для уменьшенной анимации
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  
  // Определяем, мобильное ли устройство (кешируем для производительности)
  let isMobileCached = window.innerWidth < 768;
  const isMobile = () => isMobileCached;
  
  // Глобальный флаг для отслеживания скролла (предотвращает случайные клики)
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
  
  // Инициализация категорий
  categories.forEach((category, index) => {
    const categoryTitle = category.querySelector('.tgg-bar__category-title');
    const categoryItems = category.querySelector('.tgg-bar__items');
    
    if (!categoryTitle || !categoryItems) return;

    // Флаг для предотвращения множественных кликов
    let isAnimating = false;
    
    // Отслеживание touch событий для различения скролла и клика
    let touchStartX = 0;
    let touchStartY = 0;
    let touchStartTime = 0;
    let hasMoved = false;
    let touchTarget = null;
    const TOUCH_MOVE_THRESHOLD = 15; // пикселей (увеличено для большей точности)
    const TOUCH_TIME_THRESHOLD = 500; // миллисекунд (увеличено)
    const SCROLL_COOLDOWN = 200; // миллисекунд после скролла
    
    // Создаем кнопку для открытия/закрытия
    const toggleButton = document.createElement('button');
    toggleButton.className = 'tgg-bar__category-toggle';
    toggleButton.setAttribute('aria-expanded', isMobile() ? 'false' : 'true');
    toggleButton.setAttribute('aria-controls', `bar-category-${index}`);
    toggleButton.setAttribute('type', 'button');
    
    // Иконка для кнопки (стрелка)
    const icon = document.createElement('span');
    icon.className = 'tgg-bar__category-toggle-icon';
    icon.setAttribute('aria-hidden', 'true');
    toggleButton.appendChild(icon);
    
    // Обертываем заголовок и кнопку
    const titleWrapper = document.createElement('div');
    titleWrapper.className = 'tgg-bar__category-header';
    categoryTitle.parentNode.insertBefore(titleWrapper, categoryTitle);
    titleWrapper.appendChild(categoryTitle);
    titleWrapper.appendChild(toggleButton);
    
    // Функция для безопасного переключения
    const safeToggle = (e) => {
      // Защита от множественных кликов
      if (isAnimating) return false;
      
      // Если страница скроллится - не обрабатываем клик
      if (isScrolling) {
        return false;
      }
      
      // На мобильных проверяем, что это был реальный клик, а не скролл
      if (isMobile()) {
        // Если было движение - это точно скролл
        if (hasMoved) {
          return false;
        }
        
        // Проверяем время с момента начала touch
        const touchTime = Date.now() - touchStartTime;
        if (touchTime > TOUCH_TIME_THRESHOLD) {
          return false; // Слишком долгое нажатие
        }
        
        // Для touch событий проверяем расстояние
        if (e.type === 'touchend' || e.changedTouches) {
          const touch = e.changedTouches?.[0] || e.touches?.[0];
          
          if (touch && touchStartTime > 0) {
            const moveDistance = Math.sqrt(
              Math.pow(touchStartX - touch.clientX, 2) +
              Math.pow(touchStartY - touch.clientY, 2)
            );
            
            // Если было движение - это не клик
            if (moveDistance > TOUCH_MOVE_THRESHOLD) {
              return false;
            }
          }
        }
        
        // Для обычных кликов (не touch) на мобильных - проверяем, не было ли недавнего скролла
        if (e.type === 'click') {
          const timeSinceTouch = Date.now() - touchStartTime;
          // Если touch был недавно и не было движения - это может быть клик после touch
          if (timeSinceTouch < SCROLL_COOLDOWN && !hasMoved) {
            return true;
          }
          // Если touch был давно или не было - проверяем, не скроллится ли страница
          if (isScrolling) {
            return false;
          }
        }
      }
      
      return true;
    };
    
    // Обработка touch событий для различения скролла и клика
    const handleTouchStart = (e) => {
      if (!isMobile()) return;
      
      // Сохраняем цель touch для проверки
      touchTarget = e.target;
      
      const touch = e.touches[0];
      if (!touch) return;
      
      touchStartX = touch.clientX;
      touchStartY = touch.clientY;
      touchStartTime = Date.now();
      hasMoved = false;
    };
    
    const handleTouchMove = (e) => {
      if (!isMobile()) return;
      
      const touch = e.touches[0];
      if (!touch || touchStartTime === 0) return;
      
      const moveX = Math.abs(touch.clientX - touchStartX);
      const moveY = Math.abs(touch.clientY - touchStartY);
      
      // Если движение больше порога - это скролл
      if (moveX > TOUCH_MOVE_THRESHOLD || moveY > TOUCH_MOVE_THRESHOLD) {
        hasMoved = true;
      }
    };
    
    const handleTouchEnd = (e) => {
      if (!isMobile()) return;
      
      // Сбрасываем флаги через задержку
      const resetTimeout = setTimeout(() => {
        hasMoved = false;
        touchStartX = 0;
        touchStartY = 0;
        touchStartTime = 0;
        touchTarget = null;
      }, SCROLL_COOLDOWN);
      
      // Сохраняем timeout для возможной отмены
      if (e.target === toggleButton || toggleButton.contains(e.target)) {
        // Если это кнопка - не сбрасываем сразу, ждем обработки
        clearTimeout(resetTimeout);
        setTimeout(() => {
          hasMoved = false;
          touchStartX = 0;
          touchStartY = 0;
          touchStartTime = 0;
          touchTarget = null;
        }, SCROLL_COOLDOWN * 2);
      }
    };
    
    // Добавляем обработчики touch событий только на элементы управления
    titleWrapper.addEventListener('touchstart', handleTouchStart, { passive: true });
    titleWrapper.addEventListener('touchmove', handleTouchMove, { passive: true });
    titleWrapper.addEventListener('touchend', handleTouchEnd, { passive: true });
    toggleButton.addEventListener('touchstart', handleTouchStart, { passive: true });
    toggleButton.addEventListener('touchmove', handleTouchMove, { passive: true });
    toggleButton.addEventListener('touchend', handleTouchEnd, { passive: true });
    
    // Делаем заголовок кликабельным (только на мобильных)
    titleWrapper.addEventListener('click', (e) => {
      // Если клик не на кнопку, то переключаем (только на мобильных)
      if (isMobile() && e.target !== toggleButton && !toggleButton.contains(e.target)) {
        if (safeToggle(e)) {
          e.preventDefault();
          e.stopPropagation();
          toggleButton.click();
        } else {
          e.preventDefault();
          e.stopPropagation();
        }
      }
    }, { passive: false });
    
    // Устанавливаем ID для контента
    categoryItems.id = `bar-category-${index}`;
    categoryItems.setAttribute('aria-labelledby', `bar-category-title-${index}`);
    
    // Устанавливаем ID для заголовка
    categoryTitle.id = `bar-category-title-${index}`;
    
    // Начальное состояние: на мобильных закрыто, на десктопе открыто
    const shouldBeOpen = !isMobile();
    if (shouldBeOpen) {
      category.classList.add('is-open');
      categoryItems.classList.add('is-open');
    } else {
      category.classList.remove('is-open');
      categoryItems.classList.remove('is-open');
    }
    
    // Флаг для предотвращения двойного срабатывания (click + touchend)
    let wasTouched = false;
    let touchEndTimeout;
    
    // Обработчик клика с защитой от множественных кликов и случайных срабатываний
    const handleToggle = (e) => {
      // КРИТИЧНО: Проверяем, что страница не скроллится
      if (isScrolling) {
        if (e.type === 'click') {
          e.preventDefault();
          e.stopPropagation();
        }
        return;
      }
      
      // На мобильных проверяем, что это не случайное срабатывание при скролле
      if (isMobile() && !safeToggle(e)) {
        if (e.type === 'click') {
          e.preventDefault();
          e.stopPropagation();
        }
        return;
      }
      
      // Предотвращаем двойное срабатывание (touchend + click)
      if (isMobile() && e.type === 'click' && wasTouched) {
        wasTouched = false;
        clearTimeout(touchEndTimeout);
        e.preventDefault();
        e.stopPropagation();
        return;
      }
      
      if (e.type !== 'touchend') {
        e.preventDefault();
        e.stopPropagation();
      }
      
      // Защита от множественных кликов
      if (isAnimating) return;
      
      const isOpen = category.classList.contains('is-open');
      const willBeOpen = !isOpen;
      
      isAnimating = true;
      
      // Обновляем состояние
      toggleButton.setAttribute('aria-expanded', willBeOpen);
      category.classList.toggle('is-open', willBeOpen);
      categoryItems.classList.toggle('is-open', willBeOpen);
      
      // Используем CSS transitions вместо JavaScript анимаций для лучшей производительности
      // CSS уже настроен для плавных анимаций
      
      // Сбрасываем флаг после завершения анимации
      setTimeout(() => {
        isAnimating = false;
      }, prefersReducedMotion ? 0 : 400);
    };
    
    toggleButton.addEventListener('click', handleToggle, { passive: false });
    
    // На мобильных обрабатываем touchend для более точного определения клика
    if (isMobile()) {
      toggleButton.addEventListener('touchend', (e) => {
        // КРИТИЧНО: Проверяем, что страница не скроллится
        if (isScrolling) {
          return;
        }
        
        // Проверяем, что это не скролл
        if (!hasMoved && !isAnimating) {
          const touchTime = Date.now() - touchStartTime;
          if (touchTime < TOUCH_TIME_THRESHOLD && touchTime > 0) {
            wasTouched = true;
          }
          
          // Небольшая задержка для проверки, что это не скролл
          clearTimeout(touchEndTimeout);
          touchEndTimeout = setTimeout(() => {
            if (!hasMoved && !isScrolling && !isAnimating) {
              handleToggle(e);
              // Сбрасываем флаг через время, достаточное для предотвращения click события
              setTimeout(() => {
                wasTouched = false;
              }, 300);
            } else {
              wasTouched = false;
            }
          }, 50);
        } else {
          wasTouched = false;
        }
      }, { passive: false });
    }
  });

  // Обработка изменения размера окна (оптимизировано)
  let resizeTimeout;
  let resizeTicking = false;
  
  const handleResize = () => {
    if (!resizeTicking) {
      window.requestAnimationFrame(() => {
        isMobileCached = window.innerWidth < 768;
        resizeTicking = false;
      });
      resizeTicking = true;
    }
    
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      const nowIsMobile = isMobile();
      
      categories.forEach((category) => {
        const categoryItems = category.querySelector('.tgg-bar__items');
        const toggleButton = category.querySelector('.tgg-bar__category-toggle');
        
        if (!categoryItems || !toggleButton) return;
        
        const wasOpen = category.classList.contains('is-open');
        const shouldBeOpen = !nowIsMobile;
        
        // Если состояние должно измениться
        if (wasOpen !== shouldBeOpen) {
          toggleButton.setAttribute('aria-expanded', shouldBeOpen);
          if (shouldBeOpen) {
            category.classList.add('is-open');
            categoryItems.classList.add('is-open');
          } else {
            category.classList.remove('is-open');
            categoryItems.classList.remove('is-open');
          }
        }
      });
    }, 200);
  };
  
  window.addEventListener('resize', handleResize, { passive: true });
}
