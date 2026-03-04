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
  // Кеш: страница бара (избегаем querySelector при каждом wheel/touchmove)
  let isBarPageCached = false;

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
        // Кешируем флаг страницы бара один раз при открытии меню
        isBarPageCached = !!document.querySelector('.tgg-bar[data-bar-page="true"]');
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
  // isBarPageCached обновляется при открытии меню — избегаем querySelector на каждый wheel
  document.addEventListener('wheel', (e) => {
    if (isBarPageCached) return;
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
      e.preventDefault();
      return false;
    }
  }, { passive: false });

  // Блокировка touchmove (для мобильных устройств)
  document.addEventListener('touchmove', (e) => {
    if (isBarPageCached) return;
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
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
    link.addEventListener('click', () => {
      if (link._tapTimeout) clearTimeout(link._tapTimeout);
      link.classList.add('tgg-nav__link--tap');
      link._tapTimeout = setTimeout(() => {
        link.classList.remove('tgg-nav__link--tap');
        link._tapTimeout = null;
      }, 150);

      if (window.innerWidth <= 1023) {
        setTimeout(() => closeMenu(), 300);
      }
    });
  });
  
  // ============================================
  // ACTIVE MENU ITEM DETECTION
  // ============================================

  // Устанавливает активный класс по URL-пути (без якорей — их обрабатывает IntersectionObserver)
  function setActiveMenuItem() {
    try {
      const currentPath = window.location.pathname;
      const currentHash = window.location.hash;

      navLinks.forEach((link) => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        if (!href || href === '#' || href.startsWith('#')) return;

        try {
          const linkUrl = new URL(href, window.location.origin);
          const linkPath = linkUrl.pathname.replace(/\/$/, '');
          const currentPathNormalized = currentPath.replace(/\/$/, '');

          if ((linkPath === '' || linkPath === '/') && (currentPathNormalized === '' || currentPathNormalized === '/')) {
            if (!currentHash) link.classList.add('active');
            return;
          }
          if (linkPath !== '' && linkPath !== '/' && currentPathNormalized.includes(linkPath)) {
            link.classList.add('active');
          }
        } catch (e) { /* mailto:, tel: и т.д. */ }
      });
    } catch (e) {}
  }

  // IntersectionObserver для якорных ссылок — работает вне main thread
  function initAnchorObserver() {
    const anchorLinks = Array.from(navLinks).filter(link => {
      const href = link.getAttribute('href');
      return href && href.startsWith('#') && href.length > 1;
    });
    if (anchorLinks.length === 0) return;

    const sectionMap = new Map(); // section element → nav link
    anchorLinks.forEach(link => {
      const id = link.getAttribute('href').substring(1);
      const section = document.getElementById(id);
      if (section) sectionMap.set(section, link);
    });
    if (sectionMap.size === 0) return;

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        const link = sectionMap.get(entry.target);
        if (link) link.classList.toggle('active', entry.isIntersecting);
      });
    }, {
      // Секция считается активной, когда занимает верхнюю 40% области просмотра
      rootMargin: '-10% 0px -55% 0px',
      threshold: 0,
    });

    sectionMap.forEach((_, section) => observer.observe(section));
  }

  setActiveMenuItem();
  initAnchorObserver();
  
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
  
  let scrollTicking = false;

  function handleScroll() {
    const currentScroll = window.pageYOffset;
    if (currentScroll > 50) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
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
