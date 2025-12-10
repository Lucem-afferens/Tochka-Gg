/**
 * VR Modal
 * 
 * Ненавязчивое модальное окно с рекламой VR арены
 * Появляется через 15 секунд после загрузки страницы
 */

(function() {
    'use strict';
    
    const MODAL_ID = 'vr-modal';
    const STORAGE_KEY = 'vr_modal_closed';
    const DEFAULT_DELAY_MS = 30000; // 30 секунд по умолчанию
    
    let modal = null;
    let overlay = null;
    let closeBtn = null;
    let timeoutId = null;
    
    /**
     * Инициализация модального окна
     */
    function init() {
        modal = document.getElementById(MODAL_ID);
        if (!modal) return;
        
        // Не показываем модальное окно на странице VR арены
        const currentPath = window.location.pathname.toLowerCase();
        if (currentPath.includes('/vr') || currentPath.endsWith('/vr/')) {
            return;
        }
        
        overlay = modal.querySelector('.tgg-vr-modal__overlay');
        closeBtn = modal.querySelector('.tgg-vr-modal__close');
        
        // Проверяем, не было ли модальное окно закрыто ранее (в этой сессии)
        if (sessionStorage.getItem(STORAGE_KEY) === 'true') {
            return;
        }
        
        // Получаем задержку из data-атрибута или используем значение по умолчанию
        const delayMs = modal.dataset.delay 
            ? parseInt(modal.dataset.delay, 10) 
            : DEFAULT_DELAY_MS;
        
        // Проверяем, что задержка валидна (минимум 5 секунд, максимум 5 минут)
        const validDelay = isNaN(delayMs) || delayMs < 5000 || delayMs > 300000
            ? DEFAULT_DELAY_MS
            : delayMs;
        
        // Устанавливаем таймер на показ модального окна
        timeoutId = setTimeout(showModal, validDelay);
        
        // Обработчики событий
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        
        if (overlay) {
            overlay.addEventListener('click', closeModal);
        }
        
        // Закрытие по Escape
        document.addEventListener('keydown', handleKeydown);
    }
    
    /**
     * Показать модальное окно
     */
    function showModal() {
        if (!modal) return;
        
        // Показываем модальное окно
        modal.classList.add('tgg-vr-modal--visible');
        modal.setAttribute('aria-hidden', 'false');
        
        // Блокируем скролл на body
        document.body.style.overflow = 'hidden';
        
        // Фокус на кнопке закрытия для доступности
        if (closeBtn) {
            closeBtn.focus();
        }
    }
    
    /**
     * Закрыть модальное окно
     */
    function closeModal() {
        if (!modal) return;
        
        // Скрываем модальное окно
        modal.classList.remove('tgg-vr-modal--visible');
        modal.setAttribute('aria-hidden', 'true');
        
        // Восстанавливаем скролл
        document.body.style.overflow = '';
        
        // Сохраняем в sessionStorage, чтобы не показывать повторно в этой сессии
        sessionStorage.setItem(STORAGE_KEY, 'true');
        
        // Отменяем таймер, если модальное окно было закрыто до показа
        if (timeoutId) {
            clearTimeout(timeoutId);
            timeoutId = null;
        }
    }
    
    /**
     * Обработка нажатия клавиш
     */
    function handleKeydown(e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('tgg-vr-modal--visible')) {
            closeModal();
        }
    }
    
    // Инициализация при загрузке DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

