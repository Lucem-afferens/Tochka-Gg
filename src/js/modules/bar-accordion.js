/**
 * Bar Accordion Module
 * 
 * Аккордеон для категорий клубного бара
 * Оптимизирован для производительности и плавных анимаций
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
  
  // Инициализация категорий
  categories.forEach((category, index) => {
    const categoryTitle = category.querySelector('.tgg-bar__category-title');
    const categoryItems = category.querySelector('.tgg-bar__items');
    
    if (!categoryTitle || !categoryItems) return;

    // Флаг для предотвращения множественных кликов
    let isAnimating = false;
    
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
    
    // Делаем заголовок кликабельным (на мобильных работает всегда, на десктопе только если кнопка видна)
    titleWrapper.addEventListener('click', (e) => {
      // Если клик не на кнопку, то переключаем (только на мобильных)
      if (isMobile() && e.target !== toggleButton && !toggleButton.contains(e.target)) {
        e.preventDefault();
        if (!isAnimating) {
          toggleButton.click();
        }
      }
    }, { passive: true });
    
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
    
    // Обработчик клика с защитой от множественных кликов
    toggleButton.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      
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
    }, { passive: false });
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

