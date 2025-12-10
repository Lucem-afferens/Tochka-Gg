/**
 * News Cards Module
 * 
 * Синхронизация высоты карточек новостей для одинакового размера
 */

// Оптимизированная версия с requestAnimationFrame для лучшей производительности
export function syncNewsCardsHeight() {
  const newsSection = document.querySelector('.tgg-news-preview__items');
  if (!newsSection) return;
  
  const newsCards = newsSection.querySelectorAll('.tgg-news-preview__item');
  if (!newsCards || newsCards.length === 0) return;
  
  // Используем requestAnimationFrame для синхронизации с рендерингом браузера
  requestAnimationFrame(() => {
    // Сбрасываем высоту для измерения реальной высоты
    newsCards.forEach(card => {
      card.style.height = 'auto';
    });
    
    // Принудительный reflow для измерения реальной высоты
    void newsSection.offsetHeight;
    
    // Находим максимальную высоту
    let maxHeight = 0;
    newsCards.forEach(card => {
      const height = card.offsetHeight;
      if (height > maxHeight) {
        maxHeight = height;
      }
    });
    
    // Устанавливаем одинаковую высоту для всех карточек в следующем кадре
    if (maxHeight > 0) {
      requestAnimationFrame(() => {
        newsCards.forEach(card => {
          card.style.height = maxHeight + 'px';
        });
      });
    }
  });
}

// Инициализация при загрузке страницы
export function initNewsCards() {
  // Ждем загрузки изображений
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      setTimeout(syncNewsCardsHeight, 100);
    });
  } else {
    setTimeout(syncNewsCardsHeight, 100);
  }
  
  // Синхронизируем при изменении размера окна
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      syncNewsCardsHeight();
    }, 250);
  });
  
  // Синхронизируем после загрузки всех изображений (оптимизировано)
  const images = document.querySelectorAll('.tgg-news-preview__item img');
  let imagesLoaded = 0;
  const totalImages = images.length;
  
  if (totalImages === 0) {
    syncNewsCardsHeight();
    return;
  }
  
  // Оптимизация: используем { once: true } для автоматического удаления обработчиков
  const handleImageLoad = () => {
    imagesLoaded++;
    if (imagesLoaded === totalImages) {
      // Используем requestAnimationFrame вместо setTimeout для лучшей производительности
      requestAnimationFrame(() => {
        setTimeout(syncNewsCardsHeight, 50);
      });
    }
  };
  
  images.forEach(img => {
    if (img.complete) {
      imagesLoaded++;
      if (imagesLoaded === totalImages) {
        requestAnimationFrame(() => {
          setTimeout(syncNewsCardsHeight, 50);
        });
      }
    } else {
      img.addEventListener('load', handleImageLoad, { once: true });
      img.addEventListener('error', handleImageLoad, { once: true }); // Обрабатываем ошибки загрузки
    }
  });
}

