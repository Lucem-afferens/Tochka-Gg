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
  const activeLine = document.querySelector('.tgg-nav__active-line');
  
  if (!burger || !nav) return;
  
  // ============================================
  // MOBILE MENU TOGGLE
  // ============================================
  
  burger.addEventListener('click', (e) => {
    e.stopPropagation();
    const isExpanded = burger.getAttribute('aria-expanded') === 'true';
    burger.setAttribute('aria-expanded', !isExpanded);
    nav.classList.toggle('active');
    burger.classList.toggle('active');
    document.body.classList.toggle('menu-open');
  });
  
  // Закрытие меню при клике вне его
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 1023 && nav.classList.contains('active')) {
      if (!nav.contains(e.target) && !burger.contains(e.target)) {
        nav.classList.remove('active');
        burger.classList.remove('active');
        burger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-open');
      }
    }
  });
  
  // Закрытие меню при нажатии Escape
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('active')) {
      nav.classList.remove('active');
      burger.classList.remove('active');
      burger.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('menu-open');
    }
  });
  
  // ============================================
  // HEADER SCROLL EFFECT
  // ============================================
  
  let lastScroll = 0;
  let ticking = false;
  
  function updateHeaderOnScroll() {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 50) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
    
    lastScroll = currentScroll;
    ticking = false;
  }
  
  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(updateHeaderOnScroll);
      ticking = true;
    }
  }, { passive: true });
  
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
          nav.classList.remove('active');
          burger.classList.remove('active');
          burger.setAttribute('aria-expanded', 'false');
          document.body.classList.remove('menu-open');
        }, 300);
      }
    });
    
    // Эффект при наведении (десктоп)
    if (window.innerWidth > 1023) {
      link.addEventListener('mouseenter', () => {
        updateActiveLine(link);
      });
    }
  });
  
  // ============================================
  // ACTIVE LINE ANIMATION (DESKTOP)
  // ============================================
  
  function updateActiveLine(activeLink) {
    if (!activeLine || window.innerWidth <= 1023) return;
    
    const linkRect = activeLink.getBoundingClientRect();
    const navRect = navList?.getBoundingClientRect();
    
    if (navRect && linkRect) {
      const left = linkRect.left - navRect.left;
      const width = linkRect.width;
      
      activeLine.style.left = `${left}px`;
      activeLine.style.width = `${width}px`;
      activeLine.classList.add('visible');
      
      // Обновляем цвет активной линии в зависимости от ссылки
      const href = activeLink.getAttribute('href') || '';
      const linkText = activeLink.textContent.trim().toLowerCase();
      
      // Удаляем все классы цветов
      activeLine.className = 'tgg-nav__active-line visible';
      
      // Определяем цвет по URL или тексту
      if (href.includes('оборудование') || href.includes('equipment') || linkText.includes('оборудование')) {
        activeLine.style.background = 'linear-gradient(90deg, #22D3EE 0%, #3B82F6 100%)';
        activeLine.style.boxShadow = '0 0 12px rgba(34, 211, 238, 0.8)';
      } else if (href.includes('цены') || href.includes('pricing') || linkText.includes('цены')) {
        activeLine.style.background = 'linear-gradient(90deg, #C026D3 0%, #9333EA 100%)';
        activeLine.style.boxShadow = '0 0 12px rgba(192, 38, 211, 0.8)';
      } else if (href.includes('контакты') || href.includes('contacts') || linkText.includes('контакты')) {
        activeLine.style.background = 'linear-gradient(90deg, #FF6B35 0%, #F72C25 50%, #FFD93D 100%)';
        activeLine.style.boxShadow = '0 0 12px rgba(255, 107, 53, 0.8), 0 0 20px rgba(247, 44, 37, 0.6)';
      } else if (href.includes('vr') || linkText.includes('vr')) {
        activeLine.style.background = 'linear-gradient(90deg, #10B981 0%, #22D3EE 100%)';
        activeLine.style.boxShadow = '0 0 12px rgba(16, 185, 129, 0.8)';
      } else if (href.includes('бар') || href.includes('bar') || linkText.includes('бар')) {
        activeLine.style.background = 'linear-gradient(90deg, #FFD93D 0%, #FF8C42 100%)';
        activeLine.style.boxShadow = '0 0 12px rgba(255, 217, 61, 0.8), 0 0 20px rgba(255, 140, 66, 0.6)';
      } else {
        // Главная - синий неон (по умолчанию)
        activeLine.style.background = 'linear-gradient(90deg, #3B82F6 0%, #1E90FF 100%)';
        activeLine.style.boxShadow = '0 0 12px rgba(59, 130, 246, 0.6)';
      }
    }
  }
  
  // Обновление активной линии при наведении на меню
  if (navList && activeLine && window.innerWidth > 1023) {
    navList.addEventListener('mouseenter', () => {
      const activeLink = navList.querySelector('.tgg-nav__link.active, a.active');
      if (activeLink) {
        updateActiveLine(activeLink);
      }
    });
    
    navList.addEventListener('mouseleave', () => {
      activeLine.classList.remove('visible');
    });
  }
  
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
        if (window.innerWidth > 1023 && activeLine) {
          updateActiveLine(link);
        }
        return;
      }
      
      // Для других страниц - проверяем совпадение пути
      if (linkPath !== '' && linkPath !== '/' && currentPathNormalized.includes(linkPath)) {
        link.classList.add('active');
        if (window.innerWidth > 1023 && activeLine) {
          updateActiveLine(link);
        }
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
            if (window.innerWidth > 1023 && activeLine) {
              updateActiveLine(link);
            }
          }
        }
      }
    });
  }
  
  // Проверка активного элемента при загрузке и изменении URL
  setActiveMenuItem();
  
  // Обновляем при изменении истории (навигация назад/вперед)
  window.addEventListener('popstate', setActiveMenuItem);
  
  // Обновляем при скролле (для якорных ссылок)
  let scrollTimeout;
  window.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      // Проверяем только якорные ссылки при скролле
      const anchorLinks = Array.from(navLinks).filter(link => {
        const href = link.getAttribute('href');
        return href && href.startsWith('#');
      });
      
      if (anchorLinks.length > 0) {
        setActiveMenuItem();
      }
    }, 100);
  }, { passive: true });
  
  // Обновляем при клике на ссылку
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      setTimeout(setActiveMenuItem, 100);
    });
  });
  
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
        nav.classList.remove('active');
        burger.classList.remove('active');
        burger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-open');
      }
      
      // Обновляем активную линию
      if (window.innerWidth > 1023 && activeLine) {
        const activeLink = navList?.querySelector('.tgg-nav__link.active, a.active');
        if (activeLink) {
          updateActiveLine(activeLink);
        }
      }
    }, 250);
  });
}
