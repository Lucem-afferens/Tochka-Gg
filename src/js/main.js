/**
 * Main JavaScript
 * 
 * Главный файл JavaScript темы
 */

// Импорт стилей
import '../sass/style.scss';

// Импорт модулей
import { initNavigation } from './modules/navigation.js';
import { initSmoothScroll } from './modules/smooth-scroll.js';
import { initScrollAnimations, initParallax } from './modules/animations.js';
import { initMap } from './modules/map.js';
import { initForms } from './modules/forms.js';
import { initSliders } from './modules/slider.js';
import { initEquipmentGalleries } from './modules/equipment-gallery.js';
import { initLangameBooking } from './modules/booking.js';
import { initBookingTriangle } from './modules/booking-triangle.js';

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
    initParallax();
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
  if (document.getElementById('langame-booking-btn') || document.getElementById('langame-booking-btn-mobile')) {
    initLangameBooking();
  }
  
  // Интерактивное треугольное расположение карточек бронирования
  if (document.getElementById('booking-triangle')) {
    initBookingTriangle();
  }
  
  console.log('✅ Tochka Gg theme initialized');
});

