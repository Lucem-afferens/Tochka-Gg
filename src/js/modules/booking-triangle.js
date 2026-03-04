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

  options.forEach((option, index) => {
    option.addEventListener('mouseenter', () => {
      option.classList.add('tgg-booking__option--active', 'active');
      options.forEach((otherOption, otherIndex) => {
        if (otherIndex !== index) otherOption.classList.add('tgg-booking__option--dimmed');
      });
    });

    option.addEventListener('mouseleave', () => {
      option.classList.remove('tgg-booking__option--active', 'active');
      options.forEach((otherOption) => otherOption.classList.remove('tgg-booking__option--dimmed'));
    });
  });
}
