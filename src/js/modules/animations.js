/**
 * Animations Module
 * 
 * Анимации появления элементов при скролле
 */

// Оптимизированная версия: проверка prefers-reduced-motion
export function initScrollAnimations() {
  // Проверяем, не на странице бара (где могут быть конфликты)
  const isBarPage = document.querySelector('.tgg-bar[data-bar-page="true"]');
  if (isBarPage) {
    // На странице бара не инициализируем анимации для предотвращения конфликтов
    return;
  }
  
  // Проверяем настройки пользователя для уменьшенной анимации
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReducedMotion) {
    return; // Не инициализируем анимации, если пользователь предпочитает их отключить
  }
  
  const animatedElements = document.querySelectorAll(
    '.tgg-advantages__item, .tgg-services__item, .tgg-archive__item, .tgg-card'
  );
  
  if (animatedElements.length === 0) return;
  
  // Функция для проверки, виден ли элемент в viewport при загрузке
  const isElementInViewport = (element) => {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    
    // Элемент считается видимым, если он находится в видимой области экрана
    // с учетом небольшого отступа сверху
    return (
      rect.top < windowHeight + 100 && // Учитываем rootMargin
      rect.bottom > -100
    );
  };
  
  // Создаем observer с опциями
  const observerOptions = {
    root: null,
    rootMargin: '0px 0px -100px 0px',
    threshold: 0.1
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        // Проверяем, не был ли элемент уже обработан при загрузке
        if (entry.target.dataset.animationProcessed === 'true') {
          // Элемент уже был обработан при загрузке, пропускаем
          observer.unobserve(entry.target);
          return;
        }
        
        // Используем requestAnimationFrame вместо setTimeout для лучшей производительности
        requestAnimationFrame(() => {
          setTimeout(() => {
            entry.target.classList.add('tgg-animate-fade-in-up');
            observer.unobserve(entry.target);
          }, index * 100); // Задержка для последовательного появления
        });
      }
    });
  }, observerOptions);
  
  // Проверяем элементы, которые уже видны при загрузке страницы
  // и сразу устанавливаем для них финальное состояние без анимации
  // Используем requestAnimationFrame для гарантии выполнения после рендеринга
  requestAnimationFrame(() => {
    animatedElements.forEach((element) => {
      if (isElementInViewport(element)) {
        // Элемент уже виден - устанавливаем финальное состояние напрямую
        // чтобы избежать мерцания (не применяем анимацию)
        element.style.opacity = '1';
        element.style.transform = 'translateY(0)';
        element.dataset.animationProcessed = 'true';
      } else {
        // Элемент не виден - добавляем в observer для анимации при появлении
        observer.observe(element);
      }
    });
  });
}

/**
 * Parallax эффект для hero секции (оптимизирован, отключен на мобильных/планшетах)
 */
export function initParallax() {
  const hero = document.querySelector('.tgg-hero');
  if (!hero) return;
  
  const heroBg = hero.querySelector('.tgg-hero__bg');
  if (!heroBg) return;
  
  // Отключаем параллакс на мобильных и планшетных устройствах (меньше 1023px)
  // для лучшей производительности и плавности
  // На мобильных фон прокручивается вместе с секцией как обычный элемент
  const isMobileOrTablet = () => {
    return window.innerWidth < 1023 || 
           /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
           (navigator.maxTouchPoints && navigator.maxTouchPoints > 2);
  };
  
  // Если мобильное/планшетное устройство, не инициализируем параллакс
  if (isMobileOrTablet()) {
    return;
  }
  
  // Проверяем настройки пользователя для уменьшенной анимации
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReducedMotion) {
    return;
  }
  
  let ticking = false;
  let lastScrollY = 0;
  
  function updateParallax() {
    const scrolled = window.pageYOffset || window.scrollY;
    
    // Используем более плавный коэффициент для параллакса
    const rate = scrolled * 0.3;
    
    // Применяем transform только если значение изменилось (оптимизация)
    if (Math.abs(scrolled - lastScrollY) > 0.5) {
      heroBg.style.transform = `translateY(${rate}px)`;
      heroBg.style.willChange = 'transform';
      lastScrollY = scrolled;
    }
    
    ticking = false;
  }
  
  // Обработчик скролла с оптимизацией
  const handleScroll = () => {
    // Проверяем размер экрана при каждом скролле (на случай изменения размера окна)
    if (isMobileOrTablet()) {
      // Отключаем параллакс, если размер экрана изменился
      // На мобильных фон должен прокручиваться вместе с секцией
      // Удаляем inline стили, чтобы CSS правила сработали
      heroBg.style.removeProperty('transform');
      heroBg.style.removeProperty('will-change');
      return;
    }
    
    if (!ticking) {
      window.requestAnimationFrame(updateParallax);
      ticking = true;
    }
  };
  
  window.addEventListener('scroll', handleScroll, { passive: true });
  
  // Также отключаем при изменении размера окна
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      if (isMobileOrTablet()) {
        // На мобильных фон должен прокручиваться вместе с секцией
        // Удаляем inline стили, чтобы CSS правила сработали
        heroBg.style.removeProperty('transform');
        heroBg.style.removeProperty('will-change');
      }
    }, 250);
  }, { passive: true });
}


