/**
 * News Cards Module
 * 
 * Синхронизация высоты карточек новостей для одинакового размера
 */

export function syncNewsCardsHeight() {
  const newsSection = document.querySelector('.tgg-news-preview__items');
  if (!newsSection) return;
  
  const newsCards = newsSection.querySelectorAll('.tgg-news-preview__item');
  if (!newsCards || newsCards.length === 0) return;
  
  // Сбрасываем высоту для измерения реальной высоты
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
  
  // Устанавливаем одинаковую высоту для всех карточек
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
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      syncNewsCardsHeight();
    }, 250);
  });
  
  // Синхронизируем после загрузки всех изображений
  const images = document.querySelectorAll('.tgg-news-preview__item img');
  let imagesLoaded = 0;
  const totalImages = images.length;
  
  if (totalImages === 0) {
    syncNewsCardsHeight();
    return;
  }
  
  images.forEach(img => {
    if (img.complete) {
      imagesLoaded++;
      if (imagesLoaded === totalImages) {
        setTimeout(syncNewsCardsHeight, 100);
      }
    } else {
      img.addEventListener('load', () => {
        imagesLoaded++;
        if (imagesLoaded === totalImages) {
          setTimeout(syncNewsCardsHeight, 100);
        }
      });
    }
  });
}

