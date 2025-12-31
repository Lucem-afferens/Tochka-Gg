/**
 * News Cards Module
 * 
 * Синхронизация высоты карточек новостей для одинакового размера
 */

// Упрощенная версия синхронизации высоты (без лишних RAF)
export function syncNewsCardsHeight() {
  const newsSection = document.querySelector('.tgg-news-preview__items');
  if (!newsSection) return;
  
  const newsCards = newsSection.querySelectorAll('.tgg-news-preview__item');
  if (!newsCards || newsCards.length === 0) return;
  
  // Сбрасываем высоту для измерения
  newsCards.forEach(card => {
    card.style.height = 'auto';
  });
  
  // Находим максимальную высоту
  let maxHeight = 0;
  newsCards.forEach(card => {
    const height = card.offsetHeight;
    if (height > maxHeight) {
      maxHeight = height;
    }
  });
  
  // Устанавливаем одинаковую высоту
  if (maxHeight > 0) {
    newsCards.forEach(card => {
      card.style.height = maxHeight + 'px';
    });
  }
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
  // Увеличен debounce до 500ms для лучшей производительности
  let resizeTimeout;
  let lastWidth = window.innerWidth;
  window.addEventListener('resize', () => {
    const currentWidth = window.innerWidth;
    // Выполняем синхронизацию только при реальном изменении размера (не при каждом событии)
    if (Math.abs(currentWidth - lastWidth) < 10) {
      return; // Игнорируем незначительные изменения
    }
    lastWidth = currentWidth;
    
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      syncNewsCardsHeight();
    }, 500); // Увеличено с 250ms до 500ms
  });
  
  // Синхронизируем после загрузки всех изображений (оптимизировано)
  const images = document.querySelectorAll('.tgg-news-preview__item img');
  let imagesLoaded = 0;
  const totalImages = images.length;
  
  if (totalImages === 0) {
    syncNewsCardsHeight();
    return;
  }
  
  // Упрощенная обработка загрузки изображений
  const handleImageLoad = () => {
    imagesLoaded++;
    if (imagesLoaded === totalImages) {
      syncNewsCardsHeight();
    }
  };
  
  images.forEach(img => {
    if (img.complete) {
      imagesLoaded++;
      if (imagesLoaded === totalImages) {
        syncNewsCardsHeight();
      }
    } else {
      img.addEventListener('load', handleImageLoad, { once: true });
      img.addEventListener('error', handleImageLoad, { once: true });
    }
  });
}

