/**
 * Booking Triangle Module
 * 
 * Интерактивное треугольное расположение карточек бронирования
 * с 3D эффектами при наведении
 */

/**
 * Инициализация треугольного расположения карточек
 */
export function initBookingTriangle() {
  const triangleWrapper = document.getElementById('booking-triangle');
  if (!triangleWrapper) return;

  const options = triangleWrapper.querySelectorAll('.tgg-booking__option');
  if (options.length !== 3) return;

  // Добавляем обработчики для интерактивности
  options.forEach((option, index) => {
    option.addEventListener('mouseenter', () => {
      // При наведении увеличиваем z-index и добавляем эффект
      option.style.zIndex = '10';
      option.classList.add('active');
      
      // Остальные карточки немного затемняем
      options.forEach((otherOption, otherIndex) => {
        if (otherIndex !== index) {
          otherOption.style.opacity = '0.6';
          otherOption.style.filter = 'blur(2px)';
        }
      });
    });

    option.addEventListener('mouseleave', () => {
      // Возвращаем всё обратно
      option.style.zIndex = '';
      option.classList.remove('active');
      
      options.forEach((otherOption) => {
        otherOption.style.opacity = '1';
        otherOption.style.filter = '';
      });
    });
  });
}
