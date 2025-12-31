/**
 * Main JavaScript
 * 
 * Главный файл JavaScript темы
 */

// Импорт стилей
import '../sass/style.scss';

// Импорт Swiper
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Экспорт Swiper для использования в других модулях
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };

// Импорт модулей
import { initNavigation } from './modules/navigation.js';
import { initSmoothScroll } from './modules/smooth-scroll.js';
import { initScrollAnimations, initParallax } from './modules/animations.js';
import { initMap } from './modules/map.js';
import { initForms } from './modules/forms.js';
import { initSliders } from './modules/slider.js';
import { initEquipmentGalleries } from './modules/equipment-gallery.js';
import { initLangameBooking } from './modules/booking.js';
import { initNewsCards } from './modules/news-cards.js';
import './modules/vr-modal.js';
import { optimizeHeroForMobile } from './modules/hero-optimization.js';
import { initBarModal } from './modules/bar-modal.js';

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', () => {
  // Базовая функциональность
  initNavigation();
  initSmoothScroll();
  initScrollAnimations();
  initForms();
  
  // Дополнительные функции (если есть нужные элементы)
  if (document.querySelector('.tgg-hero')) {
    // Параллакс отключен - фон прокручивается вместе с секцией как обычный элемент
    // initParallax();
    optimizeHeroForMobile(); // Оптимизация hero для мобильных устройств
  }
  
  if (document.getElementById('map')) {
    initMap();
  }
  
  // Слайдеры (если подключен Swiper)
  initSliders();
  
  // Галереи оборудования
  if (document.querySelector('.tgg-equipment-full__gallery-slider')) {
    initEquipmentGalleries();
  }
  
  // Бронирование через Langame
  if (document.getElementById('langame-booking-btn')) {
    initLangameBooking();
  }
  
  // Синхронизация высоты карточек новостей
  if (document.querySelector('.tgg-news-preview__items')) {
    initNewsCards();
  }
  
  // Модальное окно для категорий бара
  if (document.querySelector('.tgg-bar')) {
    initBarModal();
  }
  
  // Theme initialized
});

