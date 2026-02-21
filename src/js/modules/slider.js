/**
 * Slider Module
 *
 * Карусели и слайдеры.
 * Swiper грузится динамически — только когда на странице есть слайдеры.
 */

const SLIDER_SELECTORS = ['.tgg-slider-hero', '.tgg-slider-gallery'];

export async function initSliders() {
  const activeSliders = SLIDER_SELECTORS.filter(s => document.querySelector(s));
  if (activeSliders.length === 0) return;

  // Динамический импорт — Vite создаёт отдельный lazy-chunk
  const [{ default: Swiper }, { Navigation, Pagination, Autoplay }] = await Promise.all([
    import('swiper'),
    import('swiper/modules'),
  ]);

  activeSliders.forEach(selector => {
    const sliderElement = document.querySelector(selector);
    if (!sliderElement) return;

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
        640:  { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      },
    });
  });
}
