/**
 * Slider Module
 * 
 * Карусели и слайдеры (использует Swiper)
 */

export function initSliders() {
  // Проверка наличия Swiper
  if (typeof window.Swiper === 'undefined' || !window.SwiperModules) {
    console.warn('Swiper не подключен.');
    return;
  }
  
  const { Navigation, Pagination, Autoplay } = window.SwiperModules;
  const Swiper = window.Swiper;
  
  // Инициализация слайдеров
  const sliderSelectors = [
    '.tgg-slider-hero',
    '.tgg-slider-gallery',
    '.tgg-slider-tournaments'
  ];
  
  sliderSelectors.forEach(selector => {
    const sliderElement = document.querySelector(selector);
    if (!sliderElement) return;
    
    // Настройки для карусели турниров
    if (selector === '.tgg-slider-tournaments') {
      new Swiper(sliderElement, {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1.2,
        spaceBetween: 16,
        loop: false,
        centeredSlides: true, // Центрирование слайдов
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        pagination: {
          el: sliderElement.querySelector('.swiper-pagination'),
          clickable: true,
          dynamicBullets: true,
        },
        navigation: {
          nextEl: sliderElement.querySelector('.swiper-button-next'),
          prevEl: sliderElement.querySelector('.swiper-button-prev'),
        },
        breakpoints: {
          480: {
            slidesPerView: 1.5,
            spaceBetween: 20,
            centeredSlides: true,
          },
          768: {
            slidesPerView: 2.5,
            spaceBetween: 24,
            centeredSlides: true,
          },
          1024: {
            slidesPerView: 3.5,
            spaceBetween: 24,
            centeredSlides: true,
          },
        },
      });
    } else {
      // Настройки для других слайдеров
      new Swiper(sliderElement, {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: sliderElement.querySelector('.swiper-pagination'),
          clickable: true,
        },
        navigation: {
          nextEl: sliderElement.querySelector('.swiper-button-next'),
          prevEl: sliderElement.querySelector('.swiper-button-prev'),
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
          },
        },
      });
    }
  });
}


