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
    console.warn('Navigation elements not found');
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
    
    // Отладочная информация
    if (willBeExpanded) {
      const navItems = nav.querySelectorAll('.tgg-nav__list li');
      console.log('Menu opened, found items:', navItems.length);
    }
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
  document.addEventListener('wheel', (e) => {
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
      e.preventDefault();
      return false;
    }
  }, { passive: false });
  
  // Блокировка touchmove (для мобильных устройств)
  document.addEventListener('touchmove', (e) => {
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
  
  function setActiveMenuItem() {
    const currentPath = window.location.pathname;
    const currentHref = window.location.href;
    
    // Очищаем все активные классы
    navLinks.forEach((link) => {
      link.classList.remove('active');
    });
    
    // Определяем активную ссылку по текущему URL
    navLinks.forEach((link) => {
      const href = link.getAttribute('href');
      if (!href) return;
      
      // Нормализуем URL для сравнения
      const linkUrl = new URL(href, window.location.origin);
      const currentUrl = new URL(currentHref);
      
      // Проверяем совпадение пути
      const linkPath = linkUrl.pathname.replace(/\/$/, ''); // Убираем trailing slash
      const currentPathNormalized = currentPath.replace(/\/$/, '');
      
      // Для главной страницы
      if ((linkPath === '' || linkPath === '/') && (currentPathNormalized === '' || currentPathNormalized === '/')) {
        link.classList.add('active');
        return;
      }
      
      // Для других страниц - проверяем совпадение пути
      if (linkPath !== '' && linkPath !== '/' && currentPathNormalized.includes(linkPath)) {
        link.classList.add('active');
        return;
      }
      
      // Проверка по якорям (для секций на главной странице)
      if (href.startsWith('#')) {
        const targetId = href.substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
          const rect = targetElement.getBoundingClientRect();
          if (rect.top <= 200 && rect.bottom >= 100) {
            link.classList.add('active');
          }
        }
      }
    });
  }
  
  // Проверка активного элемента при загрузке и изменении URL
  setActiveMenuItem();
  
  // Обновляем при изменении истории (навигация назад/вперед)
  window.addEventListener('popstate', setActiveMenuItem);
  
  // ============================================
  // HEADER SCROLL EFFECT + ACTIVE MENU DETECTION (объединены)
  // ============================================
  
  let lastScroll = 0;
  let scrollTicking = false;
  let scrollTimeout;
  
  function handleScroll() {
    const currentScroll = window.pageYOffset;
    
    // Обновление header (scrolled класс)
    if (currentScroll > 50) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
    
    // Обновление активного пункта меню (только для якорных ссылок, с debounce)
    const anchorLinks = Array.from(navLinks).filter(link => {
      const href = link.getAttribute('href');
      return href && href.startsWith('#');
    });
    
    if (anchorLinks.length > 0) {
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        setActiveMenuItem();
      }, 100);
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
  // SMOOTH SCROLL FOR ANCHOR LINKS
  // ============================================
  
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    
    if (href && href.startsWith('#')) {
      link.addEventListener('click', (e) => {
        const targetId = href.substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
          e.preventDefault();
          
          navLinks.forEach(l => l.classList.remove('active'));
          link.classList.add('active');
          
          const headerHeight = header?.offsetHeight || 0;
          const targetPosition = targetElement.offsetTop - headerHeight - 20;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
          
          // Обновляем URL без перезагрузки
          history.pushState(null, '', href);
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
