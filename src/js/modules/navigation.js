/**
 * Navigation Module
 * 
 * Современная навигация с плавными анимациями
 * Поддержка активных ссылок, мобильное меню, эффекты
 */

export function initNavigation() {
  const burger = document.querySelector('.tgg-header__burger');
  const nav = document.querySelector('.tgg-header__nav');
  const header = document.querySelector('.tgg-header');
  const navList = document.querySelector('.tgg-nav__list');
  
  if (!burger || !nav) {
    return;
  }
  
  // ============================================
  // MOBILE MENU TOGGLE
  // ============================================
  
  // Сохраняем позицию скролла перед блокировкой
  let scrollPosition = 0;
  
  // Функция закрытия меню
  function closeMenu() {
    nav.classList.remove('active');
    burger.classList.remove('active');
    burger.setAttribute('aria-expanded', 'false');
    
    // Разблокируем скролл
    if (window.innerWidth <= 1023) {
      document.body.classList.remove('menu-open');
      document.documentElement.classList.remove('menu-open');
      document.body.style.top = '';
      window.scrollTo(0, scrollPosition);
    }
  }
  
  burger.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    
    const isExpanded = burger.getAttribute('aria-expanded') === 'true';
    const willBeExpanded = !isExpanded;
    
    // Обновляем aria-expanded
    burger.setAttribute('aria-expanded', willBeExpanded);
    
    // Переключаем классы
    nav.classList.toggle('active');
    burger.classList.toggle('active');
    
    // Блокировка скролла и кликов
    if (window.innerWidth <= 1023) {
      if (willBeExpanded) {
        // Сохраняем позицию скролла
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        
        // Блокируем скролл
        document.body.classList.add('menu-open');
        document.documentElement.classList.add('menu-open');
        document.body.style.top = `-${scrollPosition}px`;
      } else {
        // Разблокируем скролл
        document.body.classList.remove('menu-open');
        document.documentElement.classList.remove('menu-open');
        document.body.style.top = '';
        window.scrollTo(0, scrollPosition);
      }
    } else {
      document.body.classList.remove('menu-open');
      document.documentElement.classList.remove('menu-open');
    }
    
    // Обновляем aria-label для доступности
    burger.setAttribute('aria-label', willBeExpanded 
      ? 'Закрыть меню' 
      : 'Открыть меню');
    
  });
  
  // Закрытие меню при клике вне его
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
      if (!nav.contains(e.target) && !burger.contains(e.target)) {
        closeMenu();
      }
    }
  });
  
  // Закрытие меню при нажатии Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('active')) {
      closeMenu();
    }
  });
  
  // Блокировка скролла при открытом меню
  // ВАЖНО: Работает только когда меню открыто, не блокируем скролл на странице бара
  document.addEventListener('wheel', (e) => {
    // Проверяем, не на странице бара
    const isBarPage = document.querySelector('.tgg-bar[data-bar-page="true"]');
    if (isBarPage) {
      // На странице бара не блокируем wheel
      return;
    }
    
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
      e.preventDefault();
      return false;
    }
  }, { passive: false });
  
  // Блокировка touchmove (для мобильных устройств)
  // ВАЖНО: Работает только когда меню открыто, не блокируем скролл на странице бара
  document.addEventListener('touchmove', (e) => {
    // Проверяем, не на странице бара
    const isBarPage = document.querySelector('.tgg-bar[data-bar-page="true"]');
    if (isBarPage) {
      // На странице бара не блокируем touchmove
      return;
    }
    
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
      // Разрешаем скролл только внутри меню
      if (!nav.contains(e.target)) {
        e.preventDefault();
        return false;
      }
    }
  }, { passive: false });
  
  // ============================================
  // NAVIGATION LINKS
  // ============================================
  
  const navLinks = nav.querySelectorAll('.tgg-nav__link, a');
  
  navLinks.forEach((link) => {
    // Эффект при клике
    link.addEventListener('click', (e) => {
      // Плавная анимация клика
      link.style.transform = 'scale(0.95)';
      setTimeout(() => {
        link.style.transform = '';
      }, 150);
      
      // Закрываем мобильное меню после небольшой задержки
      if (window.innerWidth <= 1023) {
        setTimeout(() => {
          closeMenu();
        }, 300);
      }
    });
  });
  
  // ============================================
  // ACTIVE MENU ITEM DETECTION
  // ============================================
  
  // Кэш для getBoundingClientRect (обновляется реже)
  let rectCache = new Map();
  let cacheTimestamp = 0;
  const CACHE_DURATION = 200; // Кэш действителен 200ms
  
  function setActiveMenuItem() {
    try {
      const currentPath = window.location.pathname;
      const currentHref = window.location.href;
      const currentHash = window.location.hash;
      
      // Очищаем все активные классы
      navLinks.forEach((link) => {
        link.classList.remove('active');
      });
      
      // Определяем активную ссылку по текущему URL
      navLinks.forEach((link) => {
        const href = link.getAttribute('href');
        if (!href || href === '#') return;
        
        // Проверка по якорям (для секций на главной странице)
        if (href.startsWith('#')) {
          const targetId = href.substring(1);
          if (!targetId) return;
          
          // Проверяем, есть ли элемент на странице
          const targetElement = document.getElementById(targetId);
          if (targetElement) {
            // Используем кэш для getBoundingClientRect
            const now = Date.now();
            let rect;
            
            if (now - cacheTimestamp > CACHE_DURATION || !rectCache.has(targetId)) {
              rect = targetElement.getBoundingClientRect();
              rectCache.set(targetId, rect);
              cacheTimestamp = now;
            } else {
              rect = rectCache.get(targetId);
            }
            
            // Проверяем, находится ли элемент в видимой области
            if (rect.top <= 200 && rect.bottom >= 100) {
              link.classList.add('active');
            }
          }
          return;
        }
        
        // Для обычных ссылок - нормализуем URL для сравнения
        try {
          const linkUrl = new URL(href, window.location.origin);
          const currentUrl = new URL(currentHref);
          
          // Проверяем совпадение пути
          const linkPath = linkUrl.pathname.replace(/\/$/, ''); // Убираем trailing slash
          const currentPathNormalized = currentPath.replace(/\/$/, '');
          
          // Для главной страницы
          if ((linkPath === '' || linkPath === '/') && (currentPathNormalized === '' || currentPathNormalized === '/')) {
            // Проверяем, нет ли хеша в URL
            if (!currentHash) {
              link.classList.add('active');
            }
            return;
          }
          
          // Для других страниц - проверяем совпадение пути
          if (linkPath !== '' && linkPath !== '/' && currentPathNormalized.includes(linkPath)) {
            link.classList.add('active');
            return;
          }
        } catch (e) {
          // Игнорируем ошибки парсинга URL (например, для mailto:, tel: и т.д.)
        }
      });
    } catch (error) {
      // Ошибка в setActiveMenuItem - игнорируем для стабильности
    }
  }
  
  // Проверка активного элемента при загрузке и изменении URL
  setActiveMenuItem();
  
  // Обновляем при изменении истории (навигация назад/вперед)
  // ВАЖНО: Обрабатываем только реальные изменения истории, не трогаем при скролле
  window.addEventListener('popstate', (e) => {
    // Защита от множественных вызовов
    if (window._isHandlingPopstate) return;
    window._isHandlingPopstate = true;
    
    setTimeout(() => {
      setActiveMenuItem();
      window._isHandlingPopstate = false;
    }, 50);
  });
  
  // ============================================
  // HEADER SCROLL EFFECT + ACTIVE MENU DETECTION (объединены)
  // ============================================
  
  let lastScroll = 0;
  let scrollTicking = false;
  let scrollTimeout;
  
  function handleScroll() {
    // Проверяем, не на странице бара
    const isBarPage = document.querySelector('.tgg-bar[data-bar-page="true"]');
    if (isBarPage) {
      // На странице бара только обновляем header, не трогаем меню
      const currentScroll = window.pageYOffset;
      if (currentScroll > 50) {
        header?.classList.add('scrolled');
      } else {
        header?.classList.remove('scrolled');
      }
      scrollTicking = false;
      return;
    }
    
    const currentScroll = window.pageYOffset;
    
    // Обновление header (scrolled класс)
    if (currentScroll > 50) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
    
    // Обновление активного пункта меню (только для якорных ссылок, с debounce)
    // ВАЖНО: Обновляем только если мы на главной странице (где есть якорные секции)
    // На других страницах (например, страница бара) не обновляем активный пункт при скролле
    const isHomePage = window.location.pathname === '/' || window.location.pathname === '';
    
    // Проверяем наличие якорных ссылок ДО вызова функции (оптимизация)
    if (isHomePage) {
      // Кэшируем список якорных ссылок (обновляется только при изменении DOM)
      if (!window._cachedAnchorLinks) {
        window._cachedAnchorLinks = Array.from(navLinks).filter(link => {
          const href = link.getAttribute('href');
          return href && href.startsWith('#') && href !== '#';
        });
      }
      
      // Обновляем активный пункт меню только если есть якорные ссылки
      // Увеличен debounce до 300ms для лучшей производительности
      if (window._cachedAnchorLinks.length > 0) {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
          setActiveMenuItem();
        }, 300); // Увеличено с 150ms до 300ms
      }
    }
    
    lastScroll = currentScroll;
    scrollTicking = false;
  }
  
  // Единый обработчик скролла с requestAnimationFrame
  window.addEventListener('scroll', () => {
    if (!scrollTicking) {
      window.requestAnimationFrame(handleScroll);
      scrollTicking = true;
    }
  }, { passive: true });
  
  // ============================================
  // SMOOTH SCROLL FOR ANCHOR LINKS (только для навигации)
  // ============================================
  // ПРИМЕЧАНИЕ: smooth-scroll.js обрабатывает все якорные ссылки на странице,
  // поэтому здесь мы обрабатываем только ссылки в навигации для дополнительной логики
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    
    if (href && href.startsWith('#') && href !== '#') {
      // Проверяем, не добавлен ли уже обработчик (защита от дублирования)
      if (link.dataset.navHandlerAdded) return;
      link.dataset.navHandlerAdded = 'true';
      
      link.addEventListener('click', (e) => {
        const targetId = href.substring(1);
        if (!targetId) return;
        
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
          // Не предотвращаем стандартное поведение здесь, так как smooth-scroll.js уже обработает это
          // Просто обновляем активный класс
          navLinks.forEach(l => l.classList.remove('active'));
          link.classList.add('active');
        }
      });
    }
  });
  
  // ============================================
  // RESIZE HANDLER
  // ============================================
  
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      // Закрываем мобильное меню при переходе на десктоп
      if (window.innerWidth > 1023) {
        closeMenu();
      }
    }, 250);
  });
}
