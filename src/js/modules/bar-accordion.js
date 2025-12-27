/**
 * Bar Accordion Module
 * 
 * Простой и надежный аккордеон для категорий клубного бара
 * Определяет скролл только по touchmove на кнопке
 */

export function initBarAccordion() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

  const categoryButtons = barSection.querySelectorAll('[data-category-toggle]');
  if (categoryButtons.length === 0) return;

  // Определяем мобильное устройство
  const isMobile = () => window.innerWidth < 768;
  
  // Функция переключения категории
  function toggleCategory(button) {
    const category = button.closest('.tgg-bar__category');
    if (!category) return;
    
    const items = category.querySelector('.tgg-bar__items');
    if (!items) return;
    
    const isOpen = category.classList.contains('is-open');
    const willBeOpen = !isOpen;
    
    // Обновляем состояние
    button.setAttribute('aria-expanded', willBeOpen);
    category.classList.toggle('is-open', willBeOpen);
    items.classList.toggle('is-open', willBeOpen);
  }
  
  // Предотвращаем всплытие событий от карточек товаров (только touchstart и click)
  const itemCards = barSection.querySelectorAll('.tgg-bar__item');
  itemCards.forEach((card) => {
    // Блокируем только touchstart и click от всплытия
    card.addEventListener('touchstart', (e) => {
      e.stopPropagation();
    }, { passive: true });
    
    card.addEventListener('click', (e) => {
      e.stopPropagation();
    });
    
    // Блокируем события на изображениях
    const images = card.querySelectorAll('img');
    images.forEach((img) => {
      img.addEventListener('touchstart', (e) => {
        e.stopPropagation();
      }, { passive: true });
      
      img.addEventListener('click', (e) => {
        e.stopPropagation();
      });
    });
  });
  
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
    
    // Только для мобильных устройств - определяем скролл по touchmove
    if (isMobile()) {
      let moved = false;
      
      button.addEventListener('touchstart', (e) => {
        // Проверяем, что touch начался именно на кнопке, а не на карточке
        const target = e.target;
        if (target.closest('.tgg-bar__item')) {
          return;
        }
        
        moved = false;
      }, { passive: true });
      
      button.addEventListener('touchmove', () => {
        // Если есть движение - это скролл
        moved = true;
      }, { passive: true });
      
      button.addEventListener('touchend', (e) => {
        // Проверяем, что touch закончился на кнопке, а не на карточке
        const target = e.changedTouches[0]?.target || e.target;
        if (target.closest('.tgg-bar__item')) {
          moved = false;
          return;
        }
        
        // Если было движение - это был скролл, не переключаем
        if (moved) {
          moved = false;
          return;
        }
        
        // Это был тап - переключаем категорию
        toggleCategory(button);
        moved = false;
      }, { passive: true });
    }
    
    // Click события (работает на всех устройствах, включая мобильные)
    button.addEventListener('click', (e) => {
      // Проверяем, что клик был именно на кнопке, а не на карточке
      const target = e.target;
      if (target.closest('.tgg-bar__item')) {
        e.stopPropagation();
        return;
      }
      
      e.preventDefault();
      toggleCategory(button);
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
