/**
 * Hero Section Optimization Module
 * 
 * Оптимизация hero секции для мобильных устройств:
 * - Отключение autoplay видео на мобильных
 * - Предотвращение скачков фона
 */

export function optimizeHeroForMobile() {
  const hero = document.querySelector('.tgg-hero');
  if (!hero) return;
  
  // Проверяем, является ли устройство мобильным/планшетным
  const isMobileOrTablet = () => {
    return window.innerWidth < 1024 || 
           /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
           (navigator.maxTouchPoints && navigator.maxTouchPoints > 2);
  };
  
  if (!isMobileOrTablet()) {
    return; // Не оптимизируем на десктопах
  }
  
  const heroBg = hero.querySelector('.tgg-hero__bg');
  if (!heroBg) return;
  
  const video = heroBg.querySelector('video.tgg-hero__bg-video, video');
  if (video) {
    // На мобильных устройствах отключаем autoplay для предотвращения скачков
    video.removeAttribute('autoplay');
    video.pause();
    
    // Используем poster вместо видео для лучшей производительности
    // Видео можно включить по требованию пользователя, но по умолчанию не воспроизводим
  }
  
  // На мобильных используем absolute позиционирование, чтобы фон прокручивался вместе с секцией
  // CSS уже устанавливает правильные стили, но убеждаемся, что они применены
  // Стили для мобильного фона уже заданы в CSS (@include max-width(lg) в _hero.scss).
  // Раньше здесь задавались inline-стили — убраны, чтобы не плодить CSSStyleDeclaration в heap.
}

