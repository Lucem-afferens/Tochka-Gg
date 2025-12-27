/**
 * Bar Modal Module
 * 
 * Модальное окно для отображения товаров категории бара
 * Открывается по клику на кнопку категории
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
  const modalContent = modal.querySelector('.tgg-bar-modal__content');
  const modalTitle = modal.querySelector('.tgg-bar-modal__title');
  const modalItems = modal.querySelector('.tgg-bar-modal__items');

  // Кнопки категорий
  const categoryButtons = barSection.querySelectorAll('[data-bar-category-btn]');
  
  if (categoryButtons.length === 0) return;

  // Обработчики для кнопок категорий
  categoryButtons.forEach((button) => {
    // Функция открытия модального окна
    const openModal = (e) => {
      if (e) {
        e.preventDefault();
        e.stopPropagation();
      }
      
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
    };
    
    // Обработчик для touch устройств
    let touchStartTime = 0;
    let touchStartY = 0;
    let touchStartX = 0;
    let hasMoved = false;
    
    button.addEventListener('touchstart', (e) => {
      touchStartTime = Date.now();
      const touch = e.touches[0];
      if (touch) {
        touchStartY = touch.clientY;
        touchStartX = touch.clientX;
        hasMoved = false;
      }
    }, { passive: true });
    
    button.addEventListener('touchmove', (e) => {
      const touch = e.touches[0];
      if (touch) {
        const moveY = Math.abs(touch.clientY - touchStartY);
        const moveX = Math.abs(touch.clientX - touchStartX);
        if (moveY > 10 || moveX > 10) {
          hasMoved = true;
        }
      }
    }, { passive: true });
    
    button.addEventListener('touchend', (e) => {
      const touchDuration = Date.now() - touchStartTime;
      const touch = e.changedTouches[0];
      
      if (touch) {
        const moveY = Math.abs(touch.clientY - touchStartY);
        const moveX = Math.abs(touch.clientX - touchStartX);
        
        // Если это был тап без движения - открываем модальное окно
        if (!hasMoved && touchDuration < 400 && moveY < 10 && moveX < 10) {
          e.preventDefault();
          e.stopPropagation();
          openModal(e);
        }
      }
      
      // Сбрасываем флаги
      hasMoved = false;
      touchStartTime = 0;
    }, { passive: false });
    
    // Обработчик для клика (работает на всех устройствах)
    button.addEventListener('click', openModal);
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
 */
function showModal(modal) {
  if (!modal) return;
  
  modal.classList.add('tgg-bar-modal--visible');
  modal.setAttribute('aria-hidden', 'false');
  
  // Блокируем скролл body
  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';
  
  // Фокус на кнопке закрытия
  const closeBtn = modal.querySelector('.tgg-bar-modal__close');
  if (closeBtn) {
    closeBtn.focus();
  }
}

/**
 * Закрывает модальное окно
 */
function closeModal(modal) {
  if (!modal) return;
  
  modal.classList.remove('tgg-bar-modal--visible');
  modal.setAttribute('aria-hidden', 'true');
  
  // Разблокируем скролл body
  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
}

