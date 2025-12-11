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
  
  // Убеждаемся, что фон зафиксирован
  const bgImage = heroBg.querySelector('img');
  const bgVideo = heroBg.querySelector('video');
  
  [bgImage, bgVideo].forEach(element => {
    if (element) {
      // Применяем fixed позиционирование через JavaScript для надежности
      element.style.position = 'fixed';
      element.style.top = '-80px';
      element.style.left = '0';
      element.style.width = '100%';
      // Используем dvh для предотвращения скачков при скрытии адресной строки
      // Проверяем поддержку dvh
      const supportsDvh = CSS.supports('height', '100dvh');
      element.style.height = supportsDvh ? 'calc(100dvh + 80px)' : 'calc(100vh + 80px)';
      element.style.zIndex = '-1';
      // Отключаем transform для предотвращения скачков
      element.style.transform = 'translateZ(0) translateY(0)';
      element.style.webkitTransform = 'translateZ(0) translateY(0)';
    }
  });
  
  // Предотвращаем скачки при скролле
  let ticking = false;
  const handleScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        // На мобильных просто фиксируем фон, без движения
        ticking = false;
      });
      ticking = true;
    }
  };
  
  window.addEventListener('scroll', handleScroll, { passive: true });
}

