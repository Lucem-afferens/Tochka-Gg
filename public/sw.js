const CACHE_NAME = 'tochka-gg-v1';
const urlsToCache = [
  '/',
  '/index.html',
  '/assets/main.js',
  '/img/tochka-gg.webp',
  '/fonts/FeatureMono-Regular.ttf',
  '/fonts/FeatureMono-Medium.ttf'
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

// Перехват запросов
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Возвращаем кешированный ответ или делаем сетевой запрос
        return response || fetch(event.request);
      })
      .catch(error => {
        console.warn('Fetch failed:', error);
        // Возвращаем fallback для HTML запросов
        if (event.request.destination === 'document') {
          return caches.match('/');
        }
      })
  );
});
