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
  
  // Инициализация карты с темной кибер-темой
  ymaps.ready(() => {
    // Кастомная темная цветовая схема
    const darkTheme = [
      {
        "featureType": "all",
        "elementType": "geometry",
        "stylers": [
          { "color": "#1a1d29" }
        ]
      },
      {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
          { "color": "#8b9dc3" }
        ]
      },
      {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
          { "color": "#1a1d29" }
        ]
      },
      {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
          { "color": "#252830" }
        ]
      },
      {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
          { "color": "#1e2128" }
        ]
      },
      {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
          { "color": "#252830" }
        ]
      },
      {
        "featureType": "poi",
        "elementType": "labels.text.fill",
        "stylers": [
          { "color": "#6b7a99" }
        ]
      },
      {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
          { "color": "#2a2d3a" }
        ]
      },
      {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
          { "color": "#9ca5b8" }
        ]
      },
      {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
          { "color": "#353849" }
        ]
      },
      {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
          { "color": "#0e1117" }
        ]
      }
    ];

    const map = new ymaps.Map('map', {
      center: [lat, lng],
      zoom: 17,
      controls: ['zoomControl', 'fullscreenControl'],
      type: 'yandex#map'
    }, {
      customStyles: darkTheme
    });

    // Кастомный маркер в неоновом стиле
    const placemarkLayout = ymaps.templateLayoutFactory.createClass(
      '<div class="tgg-map-marker">' +
        '<div class="tgg-map-marker__pulse"></div>' +
        '<div class="tgg-map-marker__dot"></div>' +
        '<svg class="tgg-map-marker__icon" width="40" height="40" viewBox="0 0 40 40">' +
          '<circle cx="20" cy="20" r="18" fill="rgba(59, 130, 246, 0.2)" stroke="#3b82f6" stroke-width="2"/>' +
          '<circle cx="20" cy="20" r="12" fill="#3b82f6"/>' +
          '<circle cx="20" cy="20" r="6" fill="#ffffff"/>' +
        '</svg>' +
      '</div>',
      {
        build: function () {
          placemarkLayout.superclass.build.call(this);
          this._element = this.getParentElement().getElementsByClassName('tgg-map-marker')[0];
        }
      }
    );

    const placemark = new ymaps.Placemark([lat, lng], {
      balloonContent: '<div style="background: #1a1d29; color: #ffffff; padding: 12px; border: 1px solid #3b82f6; border-radius: 8px; box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);">' +
                      '<strong style="color: #3b82f6; display: block; margin-bottom: 8px;">Точка Gg</strong>' +
                      'ул. Голованова, 43, Кунгур' +
                      '</div>'
    }, {
      iconLayout: placemarkLayout,
      iconShape: {
        type: 'Circle',
        coordinates: [0, 0],
        radius: 20
      },
      iconOffset: [-20, -20]
    });

    map.geoObjects.add(placemark);

    // Стилизация элементов управления
    map.controls.get('zoomControl').options.set({
      size: 'small',
      position: { right: 10, top: 10 }
    });

    // Скрываем placeholder
    const placeholder = mapContainer.querySelector('.tgg-contacts__map-placeholder');
    if (placeholder) {
      placeholder.style.display = 'none';
    }
  });
}


