/**
 * Resize Manager
 *
 * Единый менеджер события resize. Все модули подписываются через onResize()
 * вместо создания собственных window.addEventListener('resize', ...).
 * Debounce 300ms + порог 10px — игнорируем незначительные изменения.
 */

const _callbacks = new Set();
let _lastWidth = window.innerWidth;
let _timer = null;

window.addEventListener('resize', () => {
  clearTimeout(_timer);
  _timer = setTimeout(() => {
    const currentWidth = window.innerWidth;
    if (Math.abs(currentWidth - _lastWidth) >= 10) {
      _lastWidth = currentWidth;
      _callbacks.forEach(cb => cb(currentWidth));
    }
  }, 300);
}, { passive: true });

/**
 * @param {function(number): void} callback — вызывается с новой шириной окна
 * @returns {function} unsubscribe — вызовите для отписки
 */
export function onResize(callback) {
  _callbacks.add(callback);
  return () => _callbacks.delete(callback);
}
