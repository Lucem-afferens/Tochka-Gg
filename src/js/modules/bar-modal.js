/**
 * Bar Modal Module
 * 
 * Модальное окно для отображения товаров категории бара
 * Открывается по клику на кнопку категории
 * iOS-safe: использует класс на body вместо overflow на html
 */

export function initBarModal() {
  const barSection = document.querySelector('.tgg-bar');
  if (!barSection) return;

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
        
        // Копируем товары в модальное окно
        items.forEach((item) => {
          const itemClone = item.cloneNode(true);
          modalItems.appendChild(itemClone);
        });
      }
      
      // Показываем модальное окно
      showModal(modal);
    });
  });

  // Закрытие модального окна
  if (closeBtn) {
    closeBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      closeModal(modal);
    });
  }

  if (overlay) {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) {
        closeModal(modal);
      }
    });
  }

  // Закрытие по Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('tgg-bar-modal--visible')) {
      closeModal(modal);
    }
  });
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
          <span aria-hidden="true">&times;</span>
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
