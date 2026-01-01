/**
 * News Cards Module
 * 
 * Синхронизация высоты карточек новостей для одинакового размера
 */

// Оптимизированная версия синхронизации высоты (батчинг для избежания layout thrashing)
export function syncNewsCardsHeight() {
  const newsSection = document.querySelector('.tgg-news-preview__items');
  if (!newsSection) return;
  
  const newsCards = newsSection.querySelectorAll('.tgg-news-preview__item');
  if (!newsCards || newsCards.length === 0) return;
  
  // Батчинг: сначала все чтения, потом все записи
  // Шаг 1: Сбрасываем высоту для измерения (одна операция записи)
  newsCards.forEach(card => {
    card.style.height = 'auto';
  });
  
  // Шаг 2: Принудительный reflow один раз
  void newsSection.offsetHeight;
  
  // Шаг 3: Читаем все высоты (батчинг чтений)
  const heights = Array.from(newsCards).map(card => card.offsetHeight);
  const maxHeight = Math.max(...heights);
  
  // Шаг 4: Устанавливаем одинаковую высоту (батчинг записей)
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

