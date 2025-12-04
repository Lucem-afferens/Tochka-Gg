/**
 * Booking Module
 * 
 * Обработка бронирования через приложение Langame
 * Проверка установки приложения и перенаправление
 */

/**
 * Проверка установки приложения Langame
 * @param {string} deepLink - Deep link для открытия приложения
 * @param {string} iosUrl - URL в App Store
 * @param {string} androidUrl - URL в Google Play
 */
export function initLangameBooking() {
  const langameBtn = document.getElementById('langame-booking-btn');
  if (!langameBtn) return;

  const deepLink = langameBtn.dataset.langameDeepLink || 'langame://booking';
  const iosUrl = langameBtn.dataset.langameIos || 'https://apps.apple.com/ru/app/langame';
  const androidUrl = langameBtn.dataset.langameAndroid || 'https://play.google.com/store/apps/details?id=ru.langame.app';

  langameBtn.addEventListener('click', () => {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    const isIOS = /iPad|iPhone|iPod/.test(userAgent) && !window.MSStream;
    const isAndroid = /android/i.test(userAgent);

    if (isIOS) {
      handleIOSApp(deepLink, iosUrl);
    } else if (isAndroid) {
      handleAndroidApp(deepLink, androidUrl);
    } else {
      // Desktop - показываем выбор
      showStoreLinks(iosUrl, androidUrl);
    }
  });
}

/**
 * Обработка открытия приложения на iOS
 */
function handleIOSApp(deepLink, fallbackUrl) {
  // Пытаемся открыть приложение
  const startTime = Date.now();
  const timeout = 2500; // Таймаут для проверки

  // Создаем скрытый iframe для проверки установки
  const iframe = document.createElement('iframe');
  iframe.style.display = 'none';
  iframe.src = deepLink;
  document.body.appendChild(iframe);

  // Если через 2.5 секунды страница все еще видна, приложение не установлено
  setTimeout(() => {
    document.body.removeChild(iframe);
    
    // Проверяем, прошло ли время (пользователь мог уйти в приложение)
    const elapsedTime = Date.now() - startTime;
    
    if (elapsedTime < timeout + 100) {
      // Приложение не открылось - перенаправляем в App Store
      window.location.href = fallbackUrl;
    }
  }, timeout);

  // Дополнительная проверка через window.location
  setTimeout(() => {
    try {
      window.location = deepLink;
    } catch (e) {
      // Если не получилось, открываем App Store
      window.location.href = fallbackUrl;
    }
  }, 100);
}

/**
 * Обработка открытия приложения на Android
 */
function handleAndroidApp(deepLink, fallbackUrl) {
  // Пытаемся открыть приложение
  const startTime = Date.now();
  const timeout = 2000; // Таймаут для Android чуть короче

  // Прямая попытка открыть deep link
  window.location.href = deepLink;

  // Если через 2 секунды страница все еще видна, приложение не установлено
  setTimeout(() => {
    const elapsedTime = Date.now() - startTime;
    
    // Проверяем, не перешли ли мы в приложение
    // Если document.hidden или elapsedTime большой, значит приложение открылось
    if (elapsedTime < timeout + 100 && !document.hidden) {
      // Приложение не открылось - перенаправляем в Google Play
      window.location.href = fallbackUrl;
    }
  }, timeout);
}

/**
 * Показать ссылки на магазины для Desktop
 */
function showStoreLinks(iosUrl, androidUrl) {
  const message = `
    Выберите платформу для установки приложения:
    \niOS: ${iosUrl}
    \nAndroid: ${androidUrl}
  `;
  
  // Можно показать модальное окно или просто открыть ссылки
  const userChoice = confirm(message);
  
  if (userChoice) {
    // Открываем обе ссылки (пользователь выберет нужную)
    window.open(iosUrl, '_blank');
    window.open(androidUrl, '_blank');
  }
}

