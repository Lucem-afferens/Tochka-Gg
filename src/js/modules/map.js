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
        '<div class="tgg-map-marker__ring tgg-map-marker__ring--outer"></div>' +
        '<div class="tgg-map-marker__ring tgg-map-marker__ring--middle"></div>' +
        '<div class="tgg-map-marker__ring tgg-map-marker__ring--inner"></div>' +
        '<div class="tgg-map-marker__glow"></div>' +
        '<div class="tgg-map-marker__icon-wrapper">' +
          '<svg class="tgg-map-marker__icon" width="56" height="56" viewBox="0 0 56 56">' +
            '<defs>' +
              '<linearGradient id="markerGradient" x1="0%" y1="0%" x2="100%" y2="100%">' +
                '<stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />' +
                '<stop offset="50%" style="stop-color:#60a5fa;stop-opacity:1" />' +
                '<stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />' +
              '</linearGradient>' +
              '<filter id="glow">' +
                '<feGaussianBlur stdDeviation="3" result="coloredBlur"/>' +
                '<feMerge>' +
                  '<feMergeNode in="coloredBlur"/>' +
                  '<feMergeNode in="SourceGraphic"/>' +
                '</feMerge>' +
              '</filter>' +
            '</defs>' +
            '<circle cx="28" cy="28" r="24" fill="rgba(59, 130, 246, 0.15)" stroke="url(#markerGradient)" stroke-width="2"/>' +
            '<circle cx="28" cy="28" r="18" fill="rgba(59, 130, 246, 0.25)" stroke="url(#markerGradient)" stroke-width="1.5"/>' +
            '<circle cx="28" cy="28" r="12" fill="url(#markerGradient)" filter="url(#glow)"/>' +
            '<circle cx="28" cy="28" r="6" fill="#ffffff"/>' +
            '<circle cx="28" cy="28" r="3" fill="#3b82f6"/>' +
          '</svg>' +
        '</div>' +
        '<div class="tgg-map-marker__pulse"></div>' +
      '</div>',
      {
        build: function () {
          placemarkLayout.superclass.build.call(this);
          this._element = this.getParentElement().getElementsByClassName('tgg-map-marker')[0];
        }
      }
    );

    const placemark = new ymaps.Placemark([lat, lng], {
      balloonContent: '<div class="tgg-map-balloon">' +
                      '<div class="tgg-map-balloon__header">' +
                      '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="margin-right: 8px;">' +
                      '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#3b82f6"/>' +
                      '</svg>' +
                      '<strong>Точка Gg</strong>' +
                      '</div>' +
                      '<div class="tgg-map-balloon__content">ул. Голованова, 43, Кунгур</div>' +
                      '</div>'
    }, {
      iconLayout: placemarkLayout,
      iconShape: {
        type: 'Circle',
        coordinates: [0, 0],
        radius: 28
      },
      iconOffset: [-28, -28]
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


