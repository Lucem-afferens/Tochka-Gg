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
      let lastSyncTime = 0;
      const MIN_SYNC_INTERVAL = 300; // Минимальный интервал между синхронизациями (мс)
      
      // Функция для синхронизации высоты всех слайдов с защитой от бесконечных циклов
      function syncSlideHeights(swiper) {
        // Предотвращаем повторные вызовы во время синхронизации
        if (isSyncing) {
          return;
        }
        
        // Предотвращаем слишком частые вызовы
        const now = Date.now();
        if (now - lastSyncTime < MIN_SYNC_INTERVAL) {
          return;
        }
        
        const slides = swiper.slides;
        if (!slides || slides.length === 0) return;
        
        isSyncing = true;
        lastSyncTime = now;
        
        // Используем requestAnimationFrame для предотвращения конфликтов с рендерингом
        requestAnimationFrame(() => {
          let maxHeight = 0;
          
          // Находим максимальную высоту
          slides.forEach(slide => {
            const card = slide.querySelector('.tgg-tournaments-preview__card');
            if (card) {
              // Сохраняем текущую высоту
              const oldHeight = card.style.height;
              // Временно сбрасываем высоту для измерения реальной высоты
              card.style.height = '';
              card.style.minHeight = '';
              // Принудительно пересчитываем layout
              void card.offsetHeight; // Force reflow
              const height = card.offsetHeight;
              if (height > maxHeight) {
                maxHeight = height;
              }
              // Восстанавливаем высоту временно
              if (oldHeight) {
                card.style.height = oldHeight;
              }
            }
          });
          
          // Устанавливаем одинаковую высоту для всех карточек только если высота изменилась значительно
          const heightDifference = Math.abs(maxHeight - currentMaxHeight);
          if (maxHeight > 0 && heightDifference > 5) { // Изменение больше 5px
            currentMaxHeight = maxHeight;
            
            // Устанавливаем высоту через requestAnimationFrame для избежания конфликтов
            requestAnimationFrame(() => {
              slides.forEach(slide => {
                const card = slide.querySelector('.tgg-tournaments-preview__card');
                if (card) {
                  card.style.height = maxHeight + 'px';
                  card.style.minHeight = maxHeight + 'px';
                }
              });
              
              // Обновляем высоту Swiper после установки высоты карточек
              setTimeout(() => {
                if (swiper && swiper.update) {
                  swiper.update();
                }
              }, 50);
            });
          }
          
          isSyncing = false;
        });
      }
      
      // Debounced версия функции для resize с более длительной задержкой
      let lastSliderWidth = window.innerWidth;
      function debouncedSyncSlideHeights(swiper) {
        const currentWidth = window.innerWidth;
        // Выполняем синхронизацию только при реальном изменении размера
        if (Math.abs(currentWidth - lastSliderWidth) < 10) {
          return; // Игнорируем незначительные изменения
        }
        lastSliderWidth = currentWidth;
        
        if (resizeTimeout) {
          clearTimeout(resizeTimeout);
        }
        resizeTimeout = setTimeout(() => {
          syncSlideHeights(swiper);
        }, 500); // Увеличена задержка до 500ms для лучшей производительности
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
            // Синхронизируем высоту всех слайдов после инициализации с задержкой
            setTimeout(() => {
              syncSlideHeights(this);
            }, 200);
          },
          resize: function() {
            // Используем debounced версию для предотвращения бесконечных циклов
            debouncedSyncSlideHeights(this);
          },
          slideChange: function() {
            // Не синхронизируем при смене слайда, чтобы избежать лишних пересчетов
          },
          update: function() {
            // Не синхронизируем при обновлении Swiper, чтобы избежать циклов
          },
        },
      });
      
      // Также синхронизируем после загрузки изображений (только один раз)
      const images = sliderElement.querySelectorAll('.tgg-tournaments-preview__card img');
      let imagesLoaded = 0;
      const totalImages = images.length;
      let imagesSyncDone = false;
      
      if (totalImages > 0) {
        const syncAfterImages = () => {
          if (!imagesSyncDone) {
            imagesSyncDone = true;
            setTimeout(() => {
              syncSlideHeights(tournamentsSwiper);
            }, 300);
          }
        };
        
        images.forEach(img => {
          if (img.complete) {
            imagesLoaded++;
            if (imagesLoaded === totalImages) {
              syncAfterImages();
            }
          } else {
            img.addEventListener('load', () => {
              imagesLoaded++;
              if (imagesLoaded === totalImages) {
                syncAfterImages();
              }
            }, { once: true });
            img.addEventListener('error', () => {
              imagesLoaded++;
              if (imagesLoaded === totalImages) {
                syncAfterImages();
              }
            }, { once: true });
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


