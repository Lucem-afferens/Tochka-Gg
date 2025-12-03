/**
 * Equipment Gallery Module
 * 
 * Карусель фотографий для страницы оборудования
 */

export function initEquipmentGalleries() {
  const galleries = document.querySelectorAll('[data-gallery]');
  
  if (galleries.length === 0) return;
  
  galleries.forEach(gallery => {
    const galleryId = gallery.getAttribute('data-gallery');
    const track = gallery.querySelector('.tgg-equipment-full__gallery-track');
    const slides = track.querySelectorAll('.tgg-equipment-full__gallery-slide');
    const prevBtn = document.querySelector(`[data-gallery-prev="${galleryId}"]`);
    const nextBtn = document.querySelector(`[data-gallery-next="${galleryId}"]`);
    const dotsContainer = document.querySelector(`[data-gallery-dots="${galleryId}"]`);
    
    if (!track || slides.length === 0) return;
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    // Создаем точки навигации
    if (dotsContainer && totalSlides > 1) {
      for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('button');
        dot.className = 'tgg-equipment-full__gallery-dot';
        if (i === 0) dot.classList.add('active');
        dot.setAttribute('data-gallery-dot', i);
        dot.setAttribute('aria-label', `Перейти к слайду ${i + 1}`);
        dotsContainer.appendChild(dot);
      }
    }
    
    const dots = dotsContainer ? dotsContainer.querySelectorAll('.tgg-equipment-full__gallery-dot') : [];
    
    // Функция обновления слайдера
    function updateSlider() {
      const offset = -currentSlide * 100;
      track.style.transform = `translateX(${offset}%)`;
      
      // Обновляем активную точку
      dots.forEach((dot, index) => {
        if (index === currentSlide) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
      
      // Обновляем состояние кнопок
      if (prevBtn) {
        prevBtn.disabled = currentSlide === 0;
      }
      if (nextBtn) {
        nextBtn.disabled = currentSlide === totalSlides - 1;
      }
    }
    
    // Переход к следующему слайду
    function nextSlide() {
      if (currentSlide < totalSlides - 1) {
        currentSlide++;
        updateSlider();
      }
    }
    
    // Переход к предыдущему слайду
    function prevSlide() {
      if (currentSlide > 0) {
        currentSlide--;
        updateSlider();
      }
    }
    
    // Переход к конкретному слайду
    function goToSlide(index) {
      if (index >= 0 && index < totalSlides) {
        currentSlide = index;
        updateSlider();
      }
    }
    
    // Обработчики событий для кнопок
    if (nextBtn) {
      nextBtn.addEventListener('click', nextSlide);
    }
    
    if (prevBtn) {
      prevBtn.addEventListener('click', prevSlide);
    }
    
    // Обработчики для точек
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => goToSlide(index));
    });
    
    // Поддержка клавиатуры
    gallery.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        prevSlide();
      } else if (e.key === 'ArrowRight') {
        nextSlide();
      }
    });
    
    // Автопрокрутка (опционально)
    let autoplayInterval = null;
    
    function startAutoplay() {
      if (totalSlides <= 1) return;
      autoplayInterval = setInterval(() => {
        if (currentSlide === totalSlides - 1) {
          currentSlide = 0;
        } else {
          currentSlide++;
        }
        updateSlider();
      }, 5000); // 5 секунд
    }
    
    function stopAutoplay() {
      if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
      }
    }
    
    // Останавливаем автопрокрутку при наведении
    gallery.addEventListener('mouseenter', stopAutoplay);
    gallery.addEventListener('mouseleave', startAutoplay);
    
    // Инициализация
    updateSlider();
    startAutoplay();
    
    // Поддержка свайпов на мобильных устройствах
    let touchStartX = 0;
    let touchEndX = 0;
    
    track.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
    });
    
    track.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    });
    
    function handleSwipe() {
      const swipeThreshold = 50;
      const diff = touchStartX - touchEndX;
      
      if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
          nextSlide();
        } else {
          prevSlide();
        }
      }
    }
  });
}

