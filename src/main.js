import './sass/style.scss'
import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'
import { initYandexMetrika } from './utils/yandexMetrika';

  // Инициализация Метрики
if (import.meta.env.PROD && location.hostname === 'kungur-tochkagg.ru') {
  initYandexMetrika();
}


// Универсальная функция для проверки открытых оверлеев/модалок
function isAnyOverlayOpen() {
  return document.querySelector(
    '.goods-cards__ps__active, .goods-cards__pc__active, .goods-cards__food__active, .window__registr.active, .window__registr-vr.active'
  );
}

const playLink = 'https://play.google.com/store/apps/details?id=com.f5computers.langame_aggregator';
const appStoreLink = 'https://apps.apple.com/app/id1642484175';
const desktopLink = 'https://langame.ru';

// один обработчик событий для разных элементов с классом langame-launch
const langameLaunch = document.getElementsByClassName('langame-launch');
for (let i = 0; i < langameLaunch.length; i++) {
  (function(index) {
    langameLaunch[index].addEventListener("click", function (e) {
      e.preventDefault();
      const ua = navigator.userAgent;

      if (/android/i.test(ua)) {
        window.location.href = 'intent://#Intent;scheme=langame;package=com.f5computers.langame_aggregator;end';
        setTimeout(() => {
          window.location.href = playLink;
        }, 800);
      } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
        window.location.href = 'langame://';
        setTimeout(() => {
          window.location.href = appStoreLink;
        }, 800);
      } else {
        // ПК или неизвестное устройство
        window.location.href = desktopLink;
      }
    });
  })(i);
}



  //////  добавление класса для выбранного пункта меню
document.addEventListener("DOMContentLoaded", () => {


  const navItems = document.querySelectorAll(".menu ul li");
  const links = document.querySelectorAll(".menu ul li a");
const sections = Array.from(links)
  .map(link => document.querySelector(link.getAttribute("href")))
  .filter(Boolean); // ← Убираем null-значения
  const offset = 80; // высота фиксированного меню

  // Клик по якорю
  links.forEach((link, i) => {
    link.addEventListener("click", () => {
      navItems.forEach(item => item.classList.remove("active"));
      navItems[i].classList.add("active");
    });
  });

  // Скролл
  window.addEventListener("scroll", () => {
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
  });


  ///// включение карточек товаров при клике в "каталоге"

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
          targetCard.classList.remove('hover'); // <-- удаляем hover перед открытием
          targetCard.classList.add(activeClass);
          document.documentElement.classList.add('none-scroll'); // Блокируем прокрутку
        }
      });

      // Наведение — добавляем hover не элементу, а карточке
      item.addEventListener('mouseenter', function () {
        const targetCard = document.querySelector(selectorCard);
        if (targetCard) {
          targetCard.classList.add('hover');
        }
      });

      // Убираем hover при уходе мыши
      item.addEventListener('mouseleave', function () {
        const targetCard = document.querySelector(selectorCard);
        if (targetCard) {
          targetCard.classList.remove('hover');
        }
      });
    });

    // Обработка кнопки "назад"
    document.querySelectorAll(`${selectorCard} .goods-cards__content__back`).forEach(button => {
      button.addEventListener('click', function () {
        const parentCard = this.closest(selectorCard);
        if (parentCard) {
          parentCard.classList.remove(activeClass);
          if (!isAnyOverlayOpen()) {
            document.documentElement.classList.remove('none-scroll'); // Разблокировка прокрутки
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
            document.documentElement.classList.remove('none-scroll'); // Разблокировка прокрутки
          }
        }
      });
    });
  });


  // для открытия окна с записью на игру при клике на кнопку записаться (btn-registr)
   const openButtonRegistr = document.querySelectorAll('.btn-registr');
  const modalWindowRegistr = document.querySelector('.window__registr');
  const closeButton = modalWindowRegistr?.querySelector('.window-back');

  if (openButtonRegistr.length && modalWindowRegistr && closeButton) {
    // Назначаем обработчик на все кнопки .btn-registr
    openButtonRegistr.forEach(button => {
      button.addEventListener('click', function () {
        modalWindowRegistr.classList.add('active');
        document.documentElement.classList.add('none-scroll');
      });
    });

    // Обработчик закрытия окна

          //с проверкой на наличие других открытых модальных окон для корректной работы отключения прокрутки страницы 
    closeButton.addEventListener('click', function () {
      modalWindowRegistr.classList.remove('active');

      if (!isAnyOverlayOpen()) {
        document.documentElement.classList.remove('none-scroll');
      }
    });
  }

          //  const openButtonPrice = document.querySelectorAll('.btn-price');
            // const modalWindowPrice = document.querySelector('.window__price');
            // const closeButtonPrice = modalWindowPrice?.querySelector('.window-back');

            // if (openButtonPrice.length && modalWindowPrice && closeButtonPrice) {
            //   // Назначаем обработчик на все кнопки .btn-price
            //   openButtonPrice.forEach(button => {
            //     button.addEventListener('click', function () {
            //       modalWindowPrice.classList.add('active');
            //       document.documentElement.classList.add('none-scroll');
            //     });
            //   });

            //   // Обработчик закрытия окна

            //         //с проверкой на наличие других открытых модальных окон для корректной работы отключения прокрутки страницы 
            //   closeButtonPrice.addEventListener('click', function () {
            //     modalWindowPrice.classList.remove('active');

            //     if (!isAnyOverlayOpen()) {
            //       document.documentElement.classList.remove('none-scroll');
            //     }
            //   });
            // }


//для записи на арену VR
// для открытия окна с записью на игру при клике на кнопку записаться (btn-registr)
   const openButtonRegistrVr = document.querySelectorAll('.btn-registr-vr');
  const modalWindowRegistrVr = document.querySelector('.window__registr-vr');
  const closeButtonVr = modalWindowRegistrVr?.querySelector('.window-back');
  const modalVr = document.querySelector('.modal__vr');

  if (openButtonRegistrVr.length && modalWindowRegistrVr && closeButtonVr) {
    // Назначаем обработчик на все кнопки .btn-registr
    openButtonRegistrVr.forEach(button => {
      button.addEventListener('click', function () {
        modalWindowRegistrVr.classList.add('active');
        document.documentElement.classList.add('none-scroll');
          if (modalVr) {
            modalVr.classList.remove('active');
          }
      });
    });

    // Обработчик закрытия окна

          //с проверкой на наличие других открытых модальных окон для корректной работы отключения прокрутки страницы 
    closeButtonVr.addEventListener('click', function () {
      modalWindowRegistrVr.classList.remove('active');

      if (!isAnyOverlayOpen()) {
        document.documentElement.classList.remove('none-scroll');
      }
    });
  }





  // изменять текст при наведении с "компонента" на "записаться"
  const blocks = document.querySelectorAll('.components__center');

blocks.forEach(block => {
  const textSpan = block.querySelector('.components__center__text');
  if (!textSpan) return;
  const originalText = textSpan.textContent;
  const newText = 'Записаться';

  block.addEventListener('mouseenter', () => {
    textSpan.style.opacity = 0;
    setTimeout(() => {
      textSpan.textContent = newText;
      textSpan.style.opacity = 1;
    }, 200);
  });

  block.addEventListener('mouseleave', () => {
    textSpan.style.opacity = 0;
    setTimeout(() => {
      textSpan.textContent = originalText;
      textSpan.style.opacity = 1;
    }, 200);
  });
});


     /* global Swiper */
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


//реклама Других Миров
setTimeout(() => {
  const modal = document.querySelector('.modal__vr');
  if (modal) {
    modal.classList.add('active');
  }
}, 1000 * 30); // 1000 мс = 1 секунда

// По клику на кнопку удаляем класс "active"
const closeModal = document.querySelector('.modal__close');

if (closeModal) {
  closeModal.addEventListener('click', () => {
    const modal = document.querySelector('.modal__vr');
    if (modal) {
      modal.classList.remove('active');
    }
  });
}




//Меню гамбургер
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

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
      } else {
        entry.target.classList.remove('animate'); // опционально
      }
    });
  }, { threshold: 0.1 }); // 10% попадания в зону видимости
  
  document.querySelectorAll('.window').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.main__arrow').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.about__img').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.title').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.title_about').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.advantages__item__devider').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.goods__item__title__click').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.components__center__click').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.swiper-pagination-bullet-active').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.price__table__logo').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.animate__movie').forEach(el => {
    observer.observe(el);
  });
  document.querySelectorAll('.vr__registr').forEach(el => {
    observer.observe(el);
  });


});



