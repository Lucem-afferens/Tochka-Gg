const CACHE_NAME = 'tochka-gg-v2';
const STATIC_CACHE = 'static-v2';
const DYNAMIC_CACHE = 'dynamic-v2';

const urlsToCache = [
  '/',
  '/index.html',
  '/assets/main.js',
  '/img/tochka-gg.webp',
  '/img/first-girl.webp',
  '/fonts/FeatureMono-Regular.ttf',
  '/fonts/FeatureMono-Medium.ttf',
  '/icons/logo/logo_blue_only.svg'
];

// Критические ресурсы для мгновенной загрузки
const criticalResources = [
  '/',
  '/index.html',
  '/img/tochka-gg.webp',
  '/img/first-girl.webp'
];

// Установка Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // Кешируем файлы по одному с обработкой ошибок
        return Promise.allSettled(
          urlsToCache.map(url => 
            cache.add(url).catch(error => {
              console.warn(`Failed to cache ${url}:`, error);
              return null;
            })
          )
        );
      })
  );
});

// Активация и очистка старых кешей
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Перехват запросов с улучшенной стратегией кеширования
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Стратегия Cache First для статических ресурсов
  if (request.destination === 'image' || 
      request.destination === 'font' || 
      url.pathname.includes('/assets/')) {
    event.respondWith(
      caches.match(request)
        .then(response => {
          if (response) {
            return response;
          }
          return fetch(request).then(fetchResponse => {
            // Кешируем только успешные ответы
            if (fetchResponse.status === 200) {
              const responseClone = fetchResponse.clone();
              caches.open(DYNAMIC_CACHE).then(cache => {
                cache.put(request, responseClone);
              });
            }
            return fetchResponse;
          });
        })
        .catch(() => {
          // Fallback для изображений
          if (request.destination === 'image') {
            return new Response('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="#333"/></svg>', {
              headers: { 'Content-Type': 'image/svg+xml' }
            });
          }
        })
    );
    return;
  }
  
  // Стратегия Network First для HTML
  if (request.destination === 'document') {
    event.respondWith(
      fetch(request)
        .then(response => {
          if (response.status === 200) {
            const responseClone = response.clone();
            caches.open(DYNAMIC_CACHE).then(cache => {
              cache.put(request, responseClone);
            });
          }
          return response;
        })
        .catch(() => {
          return caches.match(request) || caches.match('/');
        })
    );
    return;
  }
  
  // Стандартная стратегия для остальных запросов
  event.respondWith(
    caches.match(request)
      .then(response => response || fetch(request))
  );
});
