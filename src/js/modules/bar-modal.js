/**
 * Bar Modal Module
 * 
 * Модальное окно для отображения товаров категории бара
 * Открывается по клику на кнопку категории
 * iOS-safe: использует класс на body вместо overflow на html
 */

// Флаг для защиты от дублирования обработчика keydown
let escapeHandlerAdded = false;
let escapeHandler = null;

export function initBarModal() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

  // Проверка: если accordion активен, не инициализируем модальное окно
  // (защита от конфликта, хотя accordion сейчас отключен)
  const accordionButtons = barSection.querySelectorAll('[data-category-toggle]');
  if (accordionButtons.length > 0) {
    return;
    return;
  }

  // Создаем модальное окно, если его еще нет
  let modal = document.getElementById('bar-modal');
  if (!modal) {
    modal = createModal();
    document.body.appendChild(modal);
  }

  const overlay = modal.querySelector('.tgg-bar-modal__overlay');
  const closeBtn = modal.querySelector('.tgg-bar-modal__close');
  const modalTitle = modal.querySelector('.tgg-bar-modal__title');
  const modalItems = modal.querySelector('.tgg-bar-modal__items');

  // Кнопки категорий
  const categoryButtons = barSection.querySelectorAll('[data-bar-category-btn]');
  
  if (categoryButtons.length === 0) return;

  // Обработчики для кнопок категорий - ТОЛЬКО click (браузер сам обрабатывает tap → click)
  categoryButtons.forEach((button) => {
    // Проверка: не добавляем обработчик повторно
    if (button.dataset.barModalHandlerAdded) return;
    button.dataset.barModalHandlerAdded = 'true';
    
    button.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      
      const categoryIndex = button.getAttribute('data-bar-category-btn');
      const category = barSection.querySelector(`[data-category-index="${categoryIndex}"]`);
      
      if (!category) return;
      
      // Получаем данные категории
      const categoryName = button.querySelector('.tgg-bar__category-title')?.textContent || 
                          category.querySelector('.tgg-bar__category-title')?.textContent || '';
      const items = category.querySelectorAll('.tgg-bar__item');
      
      if (items.length === 0) return;
      
      // Заполняем модальное окно
      if (modalTitle) {
        modalTitle.textContent = categoryName;
      }
      
      // Очищаем предыдущие товары
      if (modalItems) {
        modalItems.innerHTML = '';
        
        // Копируем товары в модальное окно с очисткой лишних атрибутов
        items.forEach((item) => {
          const itemClone = cloneItemSafely(item);
          modalItems.appendChild(itemClone);
        });
      }
      
      // Показываем модальное окно
      showModal(modal);
    });
  });

  // Закрытие модального окна
  if (closeBtn) {
    // Проверка: не добавляем обработчик повторно
    if (!closeBtn.dataset.barModalCloseHandlerAdded) {
      closeBtn.dataset.barModalCloseHandlerAdded = 'true';
      closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        closeModal(modal);
      });
    }
  }

  if (overlay) {
    // Проверка: не добавляем обработчик повторно
    if (!overlay.dataset.barModalOverlayHandlerAdded) {
      overlay.dataset.barModalOverlayHandlerAdded = 'true';
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
          closeModal(modal);
        }
      });
    }
  }

  // Закрытие по Escape - защита от дублирования
  if (!escapeHandlerAdded) {
    escapeHandler = (e) => {
      if (e.key === 'Escape' && modal.classList.contains('tgg-bar-modal--visible')) {
        closeModal(modal);
      }
    };
    document.addEventListener('keydown', escapeHandler);
    escapeHandlerAdded = true;
  }
}

/**
 * Безопасное клонирование карточки товара
 * Очищает лишние атрибуты и состояние, которые могут вызвать конфликты
 */
function cloneItemSafely(item) {
  const clone = item.cloneNode(true);
  
  // Очищаем data-атрибуты, которые могут быть связаны с интерактивностью
  // Используем querySelectorAll('*') вместо невалидного '[data-*]'
  const allElements = clone.querySelectorAll('*');
  allElements.forEach((el) => {
    // Сохраняем только data-bar-item (структурный атрибут)
    const attrs = Array.from(el.attributes);
    attrs.forEach((attr) => {
      if (attr.name.startsWith('data-') && attr.name !== 'data-bar-item') {
        el.removeAttribute(attr.name);
      }
    });
  });
  
  // Также обрабатываем сам клонированный элемент
  const cloneAttrs = Array.from(clone.attributes);
  cloneAttrs.forEach((attr) => {
    if (attr.name.startsWith('data-') && attr.name !== 'data-bar-item') {
      clone.removeAttribute(attr.name);
    }
  });
  
  // Очищаем aria-атрибуты, которые могут быть связаны с интерактивностью
  // Используем querySelectorAll('*') вместо невалидного '[aria-*]'
  allElements.forEach((el) => {
    const attrs = Array.from(el.attributes);
    attrs.forEach((attr) => {
      if (attr.name.startsWith('aria-') && 
          attr.name !== 'aria-hidden' && 
          attr.name !== 'aria-label') {
        el.removeAttribute(attr.name);
      }
    });
  });
  
  // Также обрабатываем сам клонированный элемент
  const cloneAriaAttrs = Array.from(clone.attributes);
  cloneAriaAttrs.forEach((attr) => {
    if (attr.name.startsWith('aria-') && 
        attr.name !== 'aria-hidden' && 
        attr.name !== 'aria-label') {
      clone.removeAttribute(attr.name);
    }
  });
  
  // Очищаем inline стили, которые могут быть связаны с состоянием
  const styledElements = clone.querySelectorAll('[style]');
  styledElements.forEach((el) => {
    // Сохраняем только критичные inline стили (если есть)
    // В нашем случае удаляем все, так как стили должны быть в CSS
    el.removeAttribute('style');
  });
  
  // Удаляем event listeners (они не копируются через cloneNode, но на всякий случай)
  // Это не нужно, но оставляем комментарий для ясности
  
  return clone;
}

/**
 * Создает HTML структуру модального окна
 */
function createModal() {
  const modal = document.createElement('div');
  modal.id = 'bar-modal';
  modal.className = 'tgg-bar-modal';
  modal.setAttribute('aria-hidden', 'true');
  modal.setAttribute('role', 'dialog');
  modal.setAttribute('aria-modal', 'true');
  
  modal.innerHTML = `
    <div class="tgg-bar-modal__overlay"></div>
    <div class="tgg-bar-modal__container">
      <div class="tgg-bar-modal__content">
        <button class="tgg-bar-modal__close" aria-label="Закрыть" type="button">
          <span class="tgg-bar-modal__close-line" aria-hidden="true"></span>
          <span class="tgg-bar-modal__close-line" aria-hidden="true"></span>
        </button>
        <h2 class="tgg-bar-modal__title"></h2>
        <div class="tgg-bar-modal__items"></div>
      </div>
    </div>
  `;
  
  return modal;
}

/**
 * Показывает модальное окно
 * iOS-safe: использует класс на body, не трогает html
 */
function showModal(modal) {
  if (!modal) return;
  
  // Сохраняем позицию скролла
  const scrollY = window.scrollY || window.pageYOffset;
  
  modal.classList.add('tgg-bar-modal--visible');
  modal.setAttribute('aria-hidden', 'false');
  
  // iOS-safe блокировка скролла: только body, не трогаем html
  document.body.classList.add('tgg-bar-modal-open');
  document.body.style.top = `-${scrollY}px`;
  
  // Фокус на кнопке закрытия
  const closeBtn = modal.querySelector('.tgg-bar-modal__close');
  if (closeBtn) {
    closeBtn.focus();
  }
}

/**
 * Закрывает модальное окно
 * iOS-safe: восстанавливает позицию скролла
 */
function closeModal(modal) {
  if (!modal) return;
  
  modal.classList.remove('tgg-bar-modal--visible');
  modal.setAttribute('aria-hidden', 'true');
  
  // Восстанавливаем скролл (iOS-safe)
  const scrollY = document.body.style.top;
  document.body.classList.remove('tgg-bar-modal-open');
  document.body.style.top = '';
  
  if (scrollY) {
    window.scrollTo(0, parseInt(scrollY || '0') * -1);
  }
}

/**
 * Очистка обработчиков (для будущего использования, если понадобится)
 */
export function cleanupBarModal() {
  if (escapeHandler && escapeHandlerAdded) {
    document.removeEventListener('keydown', escapeHandler);
    escapeHandlerAdded = false;
    escapeHandler = null;
  }
}
