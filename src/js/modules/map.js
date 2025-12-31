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
  const logoUrl = mapContainer.dataset.logo || '';
  
  // Проверка, загружена ли Яндекс.Карта API
  if (typeof ymaps === 'undefined') {
    return;
    
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
    // Кастомная темная цветовая схема (еще более темная)
    const darkTheme = [
      {
        "featureType": "all",
        "elementType": "geometry",
        "stylers": [
          { "color": "#0a0c0f" }
        ]
      },
      {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
          { "color": "#4a5568", "lightness": -40 }
        ]
      },
      {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
          { "color": "#0a0c0f" }
        ]
      },
      {
        "featureType": "administrative",
        "elementType": "geometry",
        "stylers": [
          { "color": "#0f1217" }
        ]
      },
      {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
          { "color": "#080a0d" }
        ]
      },
      {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
          { "color": "#0f1217" }
        ]
      },
      {
        "featureType": "poi",
        "elementType": "labels.text.fill",
        "stylers": [
          { "color": "#3a4558", "lightness": -50 }
        ]
      },
      {
        "featureType": "road",
        "elementType": "geometry",
        "stylers": [
          { "color": "#12151a" }
        ]
      },
      {
        "featureType": "road",
        "elementType": "labels.text.fill",
        "stylers": [
          { "color": "#5a6575", "lightness": -40 }
        ]
      },
      {
        "featureType": "road.highway",
        "elementType": "geometry",
        "stylers": [
          { "color": "#151821" }
        ]
      },
      {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
          { "color": "#030405" }
        ]
      }
    ];

    const map = new ymaps.Map('map', {
      center: [lat, lng],
      zoom: 17,
      controls: ['zoomControl', 'fullscreenControl'],
      type: 'yandex#map',
      // Изначально отключаем все способы масштабирования
      behaviors: ['default']
    }, {
      customStyles: darkTheme
    });

    // Изначально отключаем все способы масштабирования
    map.behaviors.disable('scrollZoom'); // Масштабирование колесом мыши
    map.behaviors.disable('dblClickZoom'); // Масштабирование двойным кликом
    map.behaviors.disable('multiTouch'); // Масштабирование жестами на мобильных

    // Включаем все способы масштабирования только после клика по карте
    let zoomEnabled = false;
    map.events.add('click', () => {
      if (!zoomEnabled) {
        map.behaviors.enable('scrollZoom');
        map.behaviors.enable('dblClickZoom');
        map.behaviors.enable('multiTouch');
        zoomEnabled = true;
        
        // Показываем подсказку пользователю (опционально)
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
          const hint = mapContainer.querySelector('.tgg-map-zoom-hint');
          if (hint) {
            hint.style.display = 'none';
          }
        }
      }
    });

    // Кастомный маркер с логотипом в кибер-стиле (только логотип с указателем)
    const markerHtml = logoUrl 
      ? `<div class="tgg-map-marker tgg-map-marker--with-logo">
          <div class="tgg-map-marker__pulse-ring"></div>
          <div class="tgg-map-marker__icon-wrapper">
            <img src="${logoUrl}" alt="Точка Gg" class="tgg-map-marker__logo">
            <div class="tgg-map-marker__pin-tail"></div>
          </div>
        </div>`
      : `<div class="tgg-map-marker">
          <div class="tgg-map-marker__pulse-ring"></div>
          <div class="tgg-map-marker__icon-wrapper">
            <svg class="tgg-map-marker__icon" width="48" height="64" viewBox="0 0 48 64">
              <defs>
                <linearGradient id="pinGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                  <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:1" />
                  <stop offset="50%" style="stop-color:#2563eb;stop-opacity:1" />
                  <stop offset="100%" style="stop-color:#1e40af;stop-opacity:1" />
                </linearGradient>
                <filter id="pinGlow">
                  <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
                  <feMerge>
                    <feMergeNode in="coloredBlur"/>
                    <feMergeNode in="SourceGraphic"/>
                  </feMerge>
                </filter>
              </defs>
              <ellipse cx="24" cy="58" rx="12" ry="4" fill="rgba(0, 0, 0, 0.3)" opacity="0.5"/>
              <path d="M24 4 C29 4 33 6 36 9 C39 12 44 18 44 26 C44 34 36 50 24 60 C12 50 4 34 4 26 C4 18 9 12 12 9 C15 6 19 4 24 4 Z" 
                    fill="url(#pinGradient)" 
                    stroke="#ffffff" 
                    stroke-width="2" 
                    filter="url(#pinGlow)"/>
              <circle cx="24" cy="20" r="8" fill="rgba(255, 255, 255, 0.9)"/>
              <circle cx="24" cy="20" r="5" fill="#3b82f6"/>
              <circle cx="24" cy="20" r="2.5" fill="#ffffff"/>
            </svg>
          </div>
        </div>`;

    const placemarkLayout = ymaps.templateLayoutFactory.createClass(
      markerHtml,
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
                      '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" style="margin-right: 8px;">' +
                      '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="#3b82f6"/>' +
                      '</svg>' +
                      '<strong>Точка Gg</strong>' +
                      '</div>' +
                      '<div class="tgg-map-balloon__content">ул. Голованова, 43, Кунгур</div>' +
                      '</div>'
    }, {
      iconLayout: placemarkLayout,
      iconShape: {
        type: logoUrl ? 'Circle' : 'Circle',
        coordinates: [0, 0],
        radius: logoUrl ? 30 : 24
      },
      iconOffset: logoUrl ? [-30, -90] : [-24, -64]
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


