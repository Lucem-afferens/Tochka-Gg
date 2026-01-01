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
  // Обрабатываем обе кнопки (десктопную и мобильную)
  const langameBtns = [
    document.getElementById('langame-booking-btn'),
    document.getElementById('langame-booking-btn-mobile')
  ].filter(btn => btn !== null);

  if (langameBtns.length === 0) return;

  langameBtns.forEach(langameBtn => {
    const deepLink = langameBtn.dataset.langameDeepLink || 'langame://booking';
    const iosUrl = langameBtn.dataset.langameIos || 'https://apps.apple.com/ru/app/langame';
    const androidUrl = langameBtn.dataset.langameAndroid || 'https://play.google.com/store/apps/details?id=ru.langame.app';

    langameBtn.addEventListener('click', (e) => {
      e.preventDefault();
      
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
  });
}

/**
 * Обработка открытия приложения на iOS
 */
function handleIOSApp(deepLink, fallbackUrl) {
  let appOpened = false;
  const startTime = Date.now();
  const timeout = 2500;
  let timeoutId = null;

  // Функция очистки обработчиков
  const cleanup = () => {
    window.removeEventListener('blur', handleBlur);
    window.removeEventListener('visibilitychange', handleVisibilityChange);
    if (timeoutId) {
      clearTimeout(timeoutId);
      timeoutId = null;
    }
  };

  // Отслеживаем фокус страницы - если страница теряет фокус, значит приложение открылось
  const handleBlur = () => {
    appOpened = true;
    cleanup();
  };

  const handleVisibilityChange = () => {
    if (document.hidden) {
      appOpened = true;
      cleanup();
    }
  };

  window.addEventListener('blur', handleBlur);
  window.addEventListener('visibilitychange', handleVisibilityChange);

  // Пытаемся открыть приложение
  window.location.href = deepLink;

  // Если через timeout приложение не открылось, перенаправляем в App Store
  timeoutId = setTimeout(() => {
    cleanup();

    if (!appOpened && !document.hidden) {
      // Приложение не открылось - перенаправляем в App Store
      window.location.href = fallbackUrl;
    }
  }, timeout);
}

/**
 * Обработка открытия приложения на Android
 */
function handleAndroidApp(deepLink, fallbackUrl) {
  let appOpened = false;
  const timeout = 2000;
  let timeoutId = null;

  // Функция очистки обработчиков
  const cleanup = () => {
    window.removeEventListener('blur', handleBlur);
    window.removeEventListener('visibilitychange', handleVisibilityChange);
    if (timeoutId) {
      clearTimeout(timeoutId);
      timeoutId = null;
    }
  };

  // Отслеживаем фокус и видимость страницы
  const handleBlur = () => {
    appOpened = true;
    cleanup();
  };

  const handleVisibilityChange = () => {
    if (document.hidden) {
      appOpened = true;
      cleanup();
    }
  };

  window.addEventListener('blur', handleBlur);
  window.addEventListener('visibilitychange', handleVisibilityChange);

  // Пытаемся открыть приложение
  window.location.href = deepLink;

  // Если через timeout приложение не открылось, перенаправляем в Google Play
  timeoutId = setTimeout(() => {
    cleanup();

    if (!appOpened && !document.hidden) {
      // Приложение не открылось - перенаправляем в Google Play
      window.location.href = fallbackUrl;
    }
  }, timeout);
}

/**
 * Показать ссылки на магазины для Desktop
 */
function showStoreLinks(iosUrl, androidUrl) {
  // На десктопе просто открываем обе ссылки в новых вкладках
  // Пользователь выберет нужную платформу
  window.open(iosUrl, '_blank');
  window.open(androidUrl, '_blank');
}

