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
  const bgImage = heroBg.querySelector('img');
  const bgVideo = heroBg.querySelector('video');
  
  [bgImage, bgVideo].forEach(element => {
    if (element) {
      // Используем absolute позиционирование для прокрутки вместе с секцией
      element.style.position = 'absolute';
      element.style.top = '-80px';
      element.style.left = '0';
      element.style.width = '100%';
      // Высота будет установлена через CSS, но убеждаемся, что она достаточна
      const supportsLvh = CSS.supports('height', '100lvh');
      if (supportsLvh) {
        element.style.minHeight = 'calc(100lvh + 80px)';
      } else {
        element.style.minHeight = 'calc(100vh + 80px)';
      }
      element.style.zIndex = '-1';
      // Убеждаемся, что изображение покрывает весь экран
      element.style.objectFit = 'cover';
      element.style.objectPosition = 'center center';
      // Используем translateZ для GPU-ускорения
      element.style.transform = 'translateZ(0)';
      element.style.webkitTransform = 'translateZ(0)';
    }
  });
}

