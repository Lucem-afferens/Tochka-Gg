import './sass/style.scss'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

import { initYandexMetrika } from './utils/yandexMetrika.js';

// Универсальная функция для проверки открытых оверлеев/модалок
function isAnyOverlayOpen() {
  return document.querySelector(
    '.goods-cards__ps__active, .goods-cards__pc__active, .goods-cards__food__active, .window__registr.active, .window__registr-vr.active'
  );
}

// LanGame интеграция
const playLink = 'https://play.google.com/store/apps/details?id=com.f5computers.langame_aggregator';
const appStoreLink = 'https://apps.apple.com/app/id1642484175';
const desktopLink = 'https://langame.ru';

// Обработчик событий для элементов с классом langame-launch (оптимизированный)
document.querySelectorAll('.langame-launch').forEach(element => {
  element.addEventListener("click", function (e) {
    e.preventDefault();
    const ua = navigator.userAgent;

    if (/android/i.test(ua)) {
      window.location.href = 'intent://#Intent;scheme=langame;package=com.f5computers.langame_aggregator;end';
      setTimeout(() => window.location.href = playLink, 800);
    } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
      window.location.href = 'langame://';
      setTimeout(() => window.location.href = appStoreLink, 800);
    } else {
      // ПК или неизвестное устройство
      window.location.href = desktopLink;
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  // Инициализация аналитики (оптимизированная)
  const banner = document.getElementById('cookie-banner');
  const acceptBtn = document.getElementById('accept-cookies');
  const hasConsent = localStorage.getItem('cookie_consent') === 'true';
  const isTargetDomain = location.hostname === 'kungur-tochkagg.ru';

  function initAnalytics() {
    if (!isTargetDomain) return;
    try {
      initYandexMetrika();
    } catch (error) {
      console.warn('Ошибка инициализации Яндекс.Метрики:', error);
    }
  }

  if (!hasConsent) {
    if (banner) banner.style.display = 'block';
  } else {
    initAnalytics();
  }

  acceptBtn?.addEventListener('click', function () {
    localStorage.setItem('cookie_consent', 'true');
    if (banner) banner.style.display = 'none';
    initAnalytics();
  });

  // Навигация
  const navItems = document.querySelectorAll(".menu ul li");
  const links = document.querySelectorAll(".menu ul li a");
  const sections = Array.from(links)
    .map(link => document.querySelector(link.getAttribute("href")))
    .filter(Boolean);
  const offset = 80; // высота фиксированного меню

  // Клик по якорю
  links.forEach((link, i) => {
    link.addEventListener("click", () => {
      navItems.forEach(item => item.classList.remove("active"));
      navItems[i].classList.add("active");
    });
  });

  // Скролл (оптимизированный с throttling)
  let scrollTimer = null;
  window.addEventListener("scroll", () => {
    if (scrollTimer) return;
    scrollTimer = setTimeout(() => {
      const scrollY = window.scrollY + offset + 1;

      for (let i = 0; i < sections.length; i++) {
        const section = sections[i];
        const top = section.offsetTop;
        const bottom = top + section.offsetHeight;

        if (scrollY >= top && scrollY < bottom) {
          navItems.forEach(item => item.classList.remove("active"));
          navItems[i].classList.add("active");
          break;
        }
      }
      scrollTimer = null;
    }, 16); // ~60fps
  });

  // Каталог товаров
  const types = ['ps', 'pc', 'food'];

  types.forEach(type => {
    const selectorItem = `.goods__item-${type}`;
    const selectorCard = `.goods-cards__${type}`;
    const activeClass = `goods-cards__${type}__active`;

    // Обработка клика по элементу каталога
    document.querySelectorAll(selectorItem).forEach(item => {
      item.addEventListener('click', function () {
        // Удаляем active со всех карточек всех типов
        types.forEach(t => {
          const card = document.querySelector(`.goods-cards__${t}`);
          if (card) {
            card.classList.remove(`goods-cards__${t}__active`);
          }
        });

        // Добавляем active нужной карточке
        const targetCard = document.querySelector(selectorCard);
        if (targetCard) {
          targetCard.classList.remove('hover');
          targetCard.classList.add(activeClass);
          document.documentElement.classList.add('none-scroll');
        }
      });

      // Наведение
      item.addEventListener('mouseenter', function () {
        const targetCard = document.querySelector(selectorCard);
        if (targetCard) {
          targetCard.classList.add('hover');
        }
      });

      item.addEventListener('mouseleave', function () {
        const targetCard = document.querySelector(selectorCard);
        if (targetCard) {
          targetCard.classList.remove('hover');
        }
      });
    });

    // Кнопка "назад"
    document.querySelectorAll(`${selectorCard} .goods-cards__content__back`).forEach(button => {
      button.addEventListener('click', function () {
        const parentCard = this.closest(selectorCard);
        if (parentCard) {
          parentCard.classList.remove(activeClass);
          if (!isAnyOverlayOpen()) {
            document.documentElement.classList.remove('none-scroll');
          }
        }
      });
    });

    document.querySelectorAll(`${selectorCard} .goods-cards__content__item`).forEach(button => {
      button.addEventListener('click', function () {
        const parentCard = this.closest(selectorCard);
        if (parentCard) {
          parentCard.classList.remove(activeClass);
          if (!isAnyOverlayOpen()) {
            document.documentElement.classList.remove('none-scroll');
          }
        }
      });
    });
  });

  // Модальные окна регистрации
  const openButtonRegistr = document.querySelectorAll('.btn-registr');
  const modalWindowRegistr = document.querySelector('.window__registr');
  const closeButton = modalWindowRegistr?.querySelector('.window-back');

  if (openButtonRegistr.length && modalWindowRegistr && closeButton) {
    openButtonRegistr.forEach(button => {
      button.addEventListener('click', function () {
        modalWindowRegistr.classList.add('active');
        document.documentElement.classList.add('none-scroll');
      });
    });

    closeButton.addEventListener('click', function () {
      modalWindowRegistr.classList.remove('active');
      if (!isAnyOverlayOpen()) {
        document.documentElement.classList.remove('none-scroll');
      }
    });
  }

  // Модальные окна VR
  const openButtonRegistrVr = document.querySelectorAll('.btn-registr-vr');
  const modalWindowRegistrVr = document.querySelector('.window__registr-vr');
  const closeButtonVr = modalWindowRegistrVr?.querySelector('.window-back');
  const modalVr = document.querySelector('.modal__vr');

  if (openButtonRegistrVr.length && modalWindowRegistrVr && closeButtonVr) {
    openButtonRegistrVr.forEach(button => {
      button.addEventListener('click', function () {
        modalWindowRegistrVr.classList.add('active');
        document.documentElement.classList.add('none-scroll');
        if (modalVr) {
          modalVr.classList.remove('active');
        }
      });
    });

    closeButtonVr.addEventListener('click', function () {
      modalWindowRegistrVr.classList.remove('active');
      if (!isAnyOverlayOpen()) {
        document.documentElement.classList.remove('none-scroll');
      }
    });
  }

  // Изменение текста при наведении (оптимизированное)
  const blocks = document.querySelectorAll('.components__center');
  const newText = 'Записаться';

  blocks.forEach(block => {
    const textSpan = block.querySelector('.components__center__text');
    if (!textSpan) return;
    const originalText = textSpan.textContent;

    const changeText = (text) => {
      textSpan.style.opacity = 0;
      setTimeout(() => {
        textSpan.textContent = text;
        textSpan.style.opacity = 1;
      }, 200);
    };

    block.addEventListener('mouseenter', () => changeText(newText));
    block.addEventListener('mouseleave', () => changeText(originalText));
  });

  // Swiper
  new Swiper('.components', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  });

  // Реклама VR (оптимизированная с проверкой видимости)
  const showVrModal = () => {
    const modal = document.querySelector('.modal__vr');
    if (modal && !modal.classList.contains('active')) {
      modal.classList.add('active');
    }
  };
  
  // Показываем модалку только если пользователь активен
  let vrModalTimer = setTimeout(showVrModal, 1000 * 30);
  
  // Сбрасываем таймер при активности пользователя
  const resetVrTimer = () => {
    clearTimeout(vrModalTimer);
    vrModalTimer = setTimeout(showVrModal, 1000 * 30);
  };
  
  ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
    document.addEventListener(event, resetVrTimer, { passive: true });
  });

  const closeModal = document.querySelector('.modal__close');
  if (closeModal) {
    closeModal.addEventListener('click', () => {
      const modal = document.querySelector('.modal__vr');
      if (modal) {
        modal.classList.remove('active');
      }
    });
  }

  // Меню гамбургер
  const sign = document.querySelector('.sign');
  const overlay = document.querySelector('.overlay');
  const button = document.getElementById('menuToggle');
  const menuLinks = document.querySelectorAll('.sign nav a');
  const leftChain = document.querySelector('.left-chain');
  const rightChain = document.querySelector('.right-chain');

  function openMenu() {
    if (!sign || !overlay || !button || !leftChain || !rightChain) return;
    sign.classList.add('active');
    overlay.classList.add('active');
    button.classList.add('active');
    document.body.classList.add('none-scroll');
    leftChain.style.animation = 'chain-swing-left 1.2s ease-in-out forwards';
    rightChain.style.animation = 'chain-swing-right 1.2s ease-in-out forwards';
  }

  function closeMenu() {
    if (!sign || !overlay || !button || !leftChain || !rightChain) return;
    sign.classList.remove('active');
    overlay.classList.remove('active');
    button.classList.remove('active');
    document.body.classList.remove('none-scroll');
    leftChain.style.animation = '';
    rightChain.style.animation = '';
  }

  if (button && sign && overlay && leftChain && rightChain) {
    button.addEventListener('click', () => {
      if (sign.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });

    overlay.addEventListener('click', closeMenu);
    sign.addEventListener('click', (e) => e.stopPropagation());

    menuLinks.forEach(link => {
      link.addEventListener('click', closeMenu);
    });
  }

  // Intersection Observer для анимаций
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        // Отключаем наблюдение после анимации для экономии ресурсов
        // Но только для элементов, которые не должны повторно анимироваться
        if (!entry.target.classList.contains('goods__item__title__click') && 
            !entry.target.classList.contains('components__center__click')) {
          observer.unobserve(entry.target);
        }
      } else {
        // Убираем класс только для элементов, которые должны повторно анимироваться
        if (entry.target.classList.contains('goods__item__title__click') || 
            entry.target.classList.contains('components__center__click')) {
          entry.target.classList.remove('animate');
        }
      }
    });
  }, { 
    threshold: 0.1,
    rootMargin: '50px' // Начинаем анимацию немного раньше
  });
  
  const animatedElements = [
    '.window',
    '.main__arrow',
    '.about__img',
    '.title',
    '.title_about',
    '.advantages__item__devider',
    '.goods__item__title__click',
    '.components__center__click',
    '.swiper-pagination-bullet-active',
    '.price__table__logo',
    '.animate__movie',
    '.vr__registr'
  ];

  // Используем requestIdleCallback для отложенной загрузки
  if ('requestIdleCallback' in window) {
    requestIdleCallback(() => {
      animatedElements.forEach(selector => {
        document.querySelectorAll(selector).forEach(el => {
          observer.observe(el);
        });
      });
    });
  } else {
    // Fallback для старых браузеров
    setTimeout(() => {
      animatedElements.forEach(selector => {
        document.querySelectorAll(selector).forEach(el => {
          observer.observe(el);
        });
      });
    }, 100);
  }

  // Регистрация Service Worker для кеширования
  if ('serviceWorker' in navigator && window.location.protocol === 'https:') {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch(() => {
        // Игнорируем ошибки регистрации SW
      });
    });
  }
});



