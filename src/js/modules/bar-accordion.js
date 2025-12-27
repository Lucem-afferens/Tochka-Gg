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
  
  // Определяем, мобильное ли устройство
  const isMobile = () => window.innerWidth < 768;
  
  // Инициализация категорий
  categories.forEach((category, index) => {
    const categoryTitle = category.querySelector('.tgg-bar__category-title');
    const categoryItems = category.querySelector('.tgg-bar__items');
    
    if (!categoryTitle || !categoryItems) return;

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
        toggleButton.click();
      }
    });
    
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
      // На мобильных скрываем контент сразу
      categoryItems.style.display = 'none';
    }
    
    // Обработчик клика
    toggleButton.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      
      const isOpen = category.classList.contains('is-open');
      const willBeOpen = !isOpen;
      
      // Обновляем состояние
      toggleButton.setAttribute('aria-expanded', willBeOpen);
      category.classList.toggle('is-open', willBeOpen);
      categoryItems.classList.toggle('is-open', willBeOpen);
      
      // Плавная анимация высоты (если не отключена)
      if (!prefersReducedMotion) {
        // Используем requestAnimationFrame для плавности
        requestAnimationFrame(() => {
          if (willBeOpen) {
            // Открываем
            categoryItems.style.display = 'grid';
            const height = categoryItems.scrollHeight;
            categoryItems.style.height = '0px';
            categoryItems.style.overflow = 'hidden';
            categoryItems.style.opacity = '0';
            
            requestAnimationFrame(() => {
              categoryItems.style.transition = 'height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease';
              categoryItems.style.height = `${height}px`;
              categoryItems.style.opacity = '1';
              
              // После завершения анимации
              setTimeout(() => {
                categoryItems.style.height = '';
                categoryItems.style.overflow = '';
                categoryItems.style.opacity = '';
                categoryItems.style.transition = '';
              }, 400);
            });
          } else {
            // Закрываем
            const height = categoryItems.scrollHeight;
            categoryItems.style.height = `${height}px`;
            categoryItems.style.overflow = 'hidden';
            categoryItems.style.opacity = '1';
            
            requestAnimationFrame(() => {
              categoryItems.style.transition = 'height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease';
              categoryItems.style.height = '0px';
              categoryItems.style.opacity = '0';
              
              setTimeout(() => {
                categoryItems.style.display = 'none';
                categoryItems.style.height = '';
                categoryItems.style.overflow = '';
                categoryItems.style.opacity = '';
                categoryItems.style.transition = '';
              }, 400);
            });
          }
        });
      } else {
        // Если анимация отключена, просто показываем/скрываем
        if (willBeOpen) {
          categoryItems.style.display = 'grid';
        } else {
          categoryItems.style.display = 'none';
        }
      }
    });
  });

  // Обработка изменения размера окна
  let resizeTimeout;
  window.addEventListener('resize', () => {
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
            categoryItems.style.display = 'grid';
          } else {
            category.classList.remove('is-open');
            categoryItems.classList.remove('is-open');
            categoryItems.style.display = 'none';
          }
        }
      });
    }, 150);
  }, { passive: true });
}

