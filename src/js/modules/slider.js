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
      // Переменная для хранения текущей высоты и предотвращения бесконечных циклов
      let currentMaxHeight = 0;
      let isSyncing = false;
      let resizeTimeout = null;
      
      // Функция для синхронизации высоты всех слайдов с защитой от бесконечных циклов
      function syncSlideHeights(swiper) {
        // Предотвращаем повторные вызовы во время синхронизации
        if (isSyncing) return;
        
        const slides = swiper.slides;
        if (!slides || slides.length === 0) return;
        
        isSyncing = true;
        
        let maxHeight = 0;
        
        // Находим максимальную высоту
        slides.forEach(slide => {
          const card = slide.querySelector('.tgg-tournaments-preview__card');
          if (card) {
            // Временно сбрасываем высоту для измерения реальной высоты
            const oldHeight = card.style.height;
            card.style.height = 'auto';
            const height = card.offsetHeight;
            if (height > maxHeight) {
              maxHeight = height;
            }
            // Восстанавливаем высоту, если она была установлена
            if (oldHeight) {
              card.style.height = oldHeight;
            }
          }
        });
        
        // Устанавливаем одинаковую высоту для всех карточек только если высота изменилась
        if (maxHeight > 0 && maxHeight !== currentMaxHeight) {
          currentMaxHeight = maxHeight;
          slides.forEach(slide => {
            const card = slide.querySelector('.tgg-tournaments-preview__card');
            if (card) {
              card.style.height = maxHeight + 'px';
            }
          });
        }
        
        isSyncing = false;
      }
      
      // Debounced версия функции для resize
      function debouncedSyncSlideHeights(swiper) {
        if (resizeTimeout) {
          clearTimeout(resizeTimeout);
        }
        resizeTimeout = setTimeout(() => {
          syncSlideHeights(swiper);
        }, 150);
      }
      
      const tournamentsSwiper = new Swiper(sliderElement, {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1.2,
        spaceBetween: 16,
        loop: false,
        centeredSlides: true, // Центрирование слайдов
        autoHeight: false, // Отключаем автоматическую высоту для одинакового размера всех слайдов
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
            autoHeight: false,
          },
          768: {
            slidesPerView: 2.5,
            spaceBetween: 24,
            centeredSlides: true,
            autoHeight: false,
          },
          1024: {
            slidesPerView: 3.5,
            spaceBetween: 24,
            centeredSlides: true,
            autoHeight: false,
          },
        },
        on: {
          init: function() {
            // Синхронизируем высоту всех слайдов после инициализации
            setTimeout(() => {
              syncSlideHeights(this);
            }, 100);
          },
          resize: function() {
            // Используем debounced версию для предотвращения бесконечных циклов
            debouncedSyncSlideHeights(this);
          },
          slideChange: function() {
            // Не синхронизируем при смене слайда, чтобы избежать лишних пересчетов
          },
        },
      });
      
      // Также синхронизируем после загрузки изображений
      const images = sliderElement.querySelectorAll('.tgg-tournaments-preview__card img');
      let imagesLoaded = 0;
      const totalImages = images.length;
      
      if (totalImages > 0) {
        images.forEach(img => {
          if (img.complete) {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
              setTimeout(() => {
                syncSlideHeights(tournamentsSwiper);
              }, 100);
            }
          } else {
            img.addEventListener('load', () => {
              imagesLoaded++;
              if (imagesLoaded === totalImages) {
                setTimeout(() => {
                  syncSlideHeights(tournamentsSwiper);
                }, 100);
              }
            });
            img.addEventListener('error', () => {
              imagesLoaded++;
              if (imagesLoaded === totalImages) {
                setTimeout(() => {
                  syncSlideHeights(tournamentsSwiper);
                }, 100);
              }
            });
          }
        });
      }
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


