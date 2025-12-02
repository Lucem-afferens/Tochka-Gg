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
  
  console.log('✅ Tochka Gg theme initialized');
});

