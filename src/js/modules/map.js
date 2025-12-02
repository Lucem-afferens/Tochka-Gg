/**
 * Map Module
 * 
 * Инициализация Яндекс.Карты
 */

export function initMap() {
  const mapContainer = document.getElementById('map');
  if (!mapContainer) return;
  
  const lat = parseFloat(mapContainer.dataset.lat) || 57.424953;
  const lng = parseFloat(mapContainer.dataset.lng) || 56.963968;
  
  // Проверка, загружена ли Яндекс.Карта API
  if (typeof ymaps === 'undefined') {
    console.warn('Яндекс.Карты API не загружен. Добавьте скрипт в functions.php');
    
    // Показываем ссылку на карту
    mapContainer.innerHTML = `
      <div class="tgg-contacts__map-placeholder">
        <p>Карта загружается...</p>
        <p class="tgg-contacts__map-link">
          <a href="https://yandex.ru/maps/?pt=${lng},${lat}&z=17&l=map" 
             target="_blank" 
             rel="noopener noreferrer">
            Открыть карту в новом окне
          </a>
        </p>
      </div>
    `;
    return;
  }
  
  // Инициализация карты
  ymaps.ready(() => {
    const map = new ymaps.Map('map', {
      center: [lat, lng],
      zoom: 17,
      controls: ['zoomControl', 'fullscreenControl']
    });
    
    const placemark = new ymaps.Placemark([lat, lng], {
      balloonContent: 'Точка Gg<br>ул. Голованова, 43, Кунгур'
    }, {
      preset: 'islands#blueDotIcon'
    });
    
    map.geoObjects.add(placemark);
    
    // Скрываем placeholder
    const placeholder = mapContainer.querySelector('.tgg-contacts__map-placeholder');
    if (placeholder) {
      placeholder.style.display = 'none';
    }
  });
}


