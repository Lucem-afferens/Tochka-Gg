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
  // ACTIVE MENU ITEM ON SCROLL
  // ============================================
  
  function setActiveMenuItem() {
    const sections = document.querySelectorAll('section[id], [id]');
    const scrollPosition = window.pageYOffset + 150;
    
    let currentActive = null;
    
    sections.forEach((section) => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.offsetHeight;
      const sectionId = section.getAttribute('id');
      
      if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
        currentActive = sectionId;
      }
    });
    
    // Обновляем активные ссылки
    navLinks.forEach((link) => {
      const href = link.getAttribute('href');
      
      if (href && href.startsWith('#')) {
        const targetId = href.substring(1);
        if (targetId === currentActive) {
          link.classList.add('active');
          if (window.innerWidth > 1023 && activeLine) {
            updateActiveLine(link);
          }
        } else {
          link.classList.remove('active');
        }
      } else if (href === window.location.pathname || href === window.location.href) {
        // Для страниц без якорей
        if (!currentActive) {
          link.classList.add('active');
          if (window.innerWidth > 1023 && activeLine) {
            updateActiveLine(link);
          }
        }
      }
    });
  }
  
  // Используем Intersection Observer для более точного определения активной секции
  const sections = document.querySelectorAll('section[id], main[id], [id]');
  const observerOptions = {
    rootMargin: '-20% 0px -70% 0px',
    threshold: [0, 0.25, 0.5, 0.75, 1]
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && entry.intersectionRatio > 0.25) {
        const id = entry.target.getAttribute('id');
        
        navLinks.forEach(link => {
          link.classList.remove('active');
          const href = link.getAttribute('href');
          
          if (href && (href === `#${id}` || href.endsWith(`#${id}`))) {
            link.classList.add('active');
            if (window.innerWidth > 1023 && activeLine) {
              updateActiveLine(link);
            }
          }
        });
      }
    });
  }, observerOptions);
  
  sections.forEach(section => observer.observe(section));
  
  // Дополнительная проверка при скролле
  let scrollTimeout;
  window.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(setActiveMenuItem, 100);
  }, { passive: true });
  
  // Проверка активного элемента при загрузке
  setActiveMenuItem();
  
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
