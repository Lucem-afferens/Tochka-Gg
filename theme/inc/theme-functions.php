<?php
/**
 * Theme Functions
 * 
 * Кастомные функции темы
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Получить версию темы для кеширования
 */
function tochkagg_get_theme_version() {
    return wp_get_theme()->get('Version');
}

/**
 * Очистка вывода от лишних элементов
 */
function tochkagg_clean_output() {
    // Удаление версии WordPress из head
    remove_action('wp_head', 'wp_generator');
    
    // Удаление ссылки на RSD
    remove_action('wp_head', 'rsd_link');
    
    // Удаление ссылки на wlwmanifest.xml
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Удаление коротких ссылок
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'tochkagg_clean_output');

/**
 * Отключение принудительного HTTPS
 * Разрешает работу сайта по HTTP без редиректа
 */
add_filter('https_ssl_verify', '__return_false');
add_filter('https_local_ssl_verify', '__return_false');

// Отключаем принудительное использование HTTPS в админке
if (!defined('FORCE_SSL_ADMIN')) {
    define('FORCE_SSL_ADMIN', false);
}

// Разрешаем использование HTTP для сайта
add_filter('option_siteurl', function($url) {
    if (is_string($url) && strpos($url, 'https://') === 0) {
        return str_replace('https://', 'http://', $url);
    }
    return $url;
}, 10, 1);

add_filter('option_home', function($url) {
    if (is_string($url) && strpos($url, 'https://') === 0) {
        return str_replace('https://', 'http://', $url);
    }
    return $url;
}, 10, 1);

// Отключаем принудительные редиректы на HTTPS
add_filter('wp_is_using_https', '__return_false');

/**
 * Принудительный сброс кеша для CSS/JS файлов
 * Добавляет параметр версии к URL для обхода кеша браузера
 */
function tochkagg_cache_bust() {
    // Добавляем уникальный параметр к стилям и скриптам
    add_filter('style_loader_src', function($src) {
        if (strpos($src, 'assets/css/style.css') !== false) {
            $src = add_query_arg('v', TOCHKAGG_THEME_VERSION . '.' . time(), $src);
        }
        return $src;
    }, 10, 1);
    
    add_filter('script_loader_src', function($src) {
        if (strpos($src, 'assets/js/main.js') !== false) {
            $src = add_query_arg('v', TOCHKAGG_THEME_VERSION . '.' . time(), $src);
        }
        return $src;
    }, 10, 1);
}
// Принудительный сброс кеша (временно включен для обновления крестика модального окна)
add_action('wp_enqueue_scripts', 'tochkagg_cache_bust', 999);

/**
 * Обновление правил перезаписи (flush rewrite rules)
 * Используется после изменения структуры ссылок
 */
function tochkagg_flush_rewrite_on_init() {
    // Проверяем, нужно ли обновить правила (только один раз)
    $should_flush = get_option('tochkagg_should_flush_rewrite');
    if ($should_flush) {
        flush_rewrite_rules();
        delete_option('tochkagg_should_flush_rewrite');
    }
}
add_action('init', 'tochkagg_flush_rewrite_on_init');

/**
 * Автоматическое обновление правил перезаписи при активации темы
 */
function tochkagg_flush_rewrite_rules_on_activation() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'tochkagg_flush_rewrite_rules_on_activation');

/**
 * Очистка мета-данных Elementor и восстановление шаблонов
 * Используйте эту функцию для восстановления шаблонов после деактивации Elementor
 */
function tochkagg_clean_elementor_meta() {
    // Маппинг страниц на их шаблоны
    $page_templates = [
        'оборудование' => 'template-equipment.php',
        'equipment' => 'template-equipment.php',
        'vr-арена' => 'template-vr.php',
        'vr' => 'template-vr.php',
        'контакты' => 'template-contacts.php',
        'contacts' => 'template-contacts.php',
        'цены' => 'template-pricing.php',
        'pricing' => 'template-pricing.php',
    ];
    
    // Находим страницы и устанавливаем правильные шаблоны
    foreach ($page_templates as $slug => $template) {
        $page = get_page_by_path($slug);
        if ($page) {
            // Удаляем мета-данные Elementor
            delete_post_meta($page->ID, '_elementor_edit_mode');
            delete_post_meta($page->ID, '_elementor_template_type');
            delete_post_meta($page->ID, '_elementor_version');
            delete_post_meta($page->ID, '_elementor_pro_version');
            delete_post_meta($page->ID, '_elementor_css');
            delete_post_meta($page->ID, '_elementor_data');
            
            // Устанавливаем правильный шаблон
            update_post_meta($page->ID, '_wp_page_template', $template);
        }
    }
}

// Раскомментируйте следующую строку, чтобы запустить очистку один раз:
// add_action('admin_init', 'tochkagg_clean_elementor_meta');

/**
 * Принудительное обновление списка шаблонов страниц
 * Решает проблему, когда шаблоны не появляются в выпадающем списке
 */
function tochkagg_refresh_page_templates() {
    // Очищаем кеш шаблонов
    if (function_exists('wp_cache_delete')) {
        wp_cache_delete('page_templates-' . md5(get_theme_root() . '/' . get_stylesheet()), 'themes');
    }
    
    // Перезагружаем список шаблонов
    get_page_templates();
}
add_action('admin_init', 'tochkagg_refresh_page_templates');

/**
 * Установка шаблона для страницы "Оборудование" принудительно
 * Раскомментируйте и укажите ID страницы, если шаблоны не появляются
 */
function tochkagg_force_set_equipment_template() {
    // Замените 0 на реальный ID страницы "Оборудование"
    // ID можно узнать из URL при редактировании страницы: post.php?post=123&action=edit
    $page_id = 0; // <-- УКАЖИТЕ ID СТРАНИЦЫ ЗДЕСЬ
    
    if ($page_id > 0) {
        $page = get_post($page_id);
        if ($page && $page->post_type === 'page') {
            // Устанавливаем шаблон
            update_post_meta($page_id, '_wp_page_template', 'template-equipment.php');
            
            // Удаляем мета-данные Elementor
            delete_post_meta($page_id, '_elementor_edit_mode');
            delete_post_meta($page_id, '_elementor_template_type');
            delete_post_meta($page_id, '_elementor_data');
        }
    }
}

// Раскомментируйте следующую строку и укажите ID страницы:
// add_action('admin_init', 'tochkagg_force_set_equipment_template');

/**
 * Вывод кастомного курсора в head
 * Получает изображение курсора из SCF Options Page
 */
function tochkagg_custom_cursor() {
    // Получаем изображение курсора из Options Page
    $cursor_image = function_exists('get_field') ? get_field('custom_cursor', 'option') : false;
    
    if (!$cursor_image) {
        return; // Если курсор не задан, используем дефолтный
    }
    
    // Получаем URL изображения
    $cursor_url = '';
    if (is_array($cursor_image) && isset($cursor_image['url'])) {
        $cursor_url = esc_url($cursor_image['url']);
    } elseif (is_string($cursor_image)) {
        $cursor_url = esc_url($cursor_image);
    } elseif (is_numeric($cursor_image)) {
        $cursor_url = esc_url(wp_get_attachment_image_url($cursor_image, 'full'));
    }
    
    if (!$cursor_url) {
        return;
    }
    
    // Получаем расположение точки клика курсора
    $cursor_hotspot = function_exists('get_field') ? get_field('custom_cursor_hotspot', 'option') : 'top-left';
    // Значения: 'top-left' (верхний левый угол), 'top-center' (верхний центр), 
    // 'top-right' (верхний правый угол), 'center' (центр)
    $hotspot_position = $cursor_hotspot ? $cursor_hotspot : 'top-left';
    
    // Получаем настройки хвоста курсора
    $cursor_trail_enabled = function_exists('get_field') ? get_field('custom_cursor_trail_enabled', 'option') : false;
    $cursor_trail_color = function_exists('get_field') ? get_field('custom_cursor_trail_color', 'option') : '#3b82f6';
    // Если цвет не задан, используем синий по умолчанию
    $trail_color = $cursor_trail_color ? $cursor_trail_color : '#3b82f6';
    $trail_enabled = ($cursor_trail_enabled === true || $cursor_trail_enabled === '1' || $cursor_trail_enabled === 1);
    
    // Выводим CSS для кастомного курсора
    // Курсор отображается в 1.5 раза меньше (через JavaScript масштабирование)
    ?>
    <style id="tochkagg-custom-cursor">
        /* Скрываем дефолтный курсор - используем кастомный через JavaScript */
        * {
            cursor: none !important;
        }
        
        /* Восстанавливаем курсор для элементов, где нужен дефолтный */
        input[type="text"], input[type="email"], input[type="tel"], 
        input[type="number"], textarea {
            cursor: text !important;
        }
    </style>
    <script>
        // JavaScript решение для кастомного курсора с уменьшением в 1.5 раза
        // Ожидаем загрузки DOM перед выполнением
        (function() {
            'use strict';
            
            function initCustomCursor() {
                // Проверяем, что body существует
                if (!document.body) {
                    return;
                }
                
                // Проверяем, что мы не на мобильном устройстве (там курсор не нужен)
                if (window.matchMedia && window.matchMedia('(max-width: 768px)').matches) {
                    // На мобильных устройствах показываем дефолтный курсор
                    const styleElement = document.querySelector('#tochkagg-custom-cursor');
                    if (styleElement) {
                        styleElement.innerHTML = '* { cursor: auto !important; }';
                    }
                    return;
                }
                
                // Проверяем, не создан ли уже курсор
                if (document.getElementById('tochkagg-custom-cursor-element')) {
                    return;
                }
                
                // Создаем элемент для кастомного курсора
                // Фиксированный размер 48x48px для любого изображения
                const cursorElement = document.createElement('div');
                cursorElement.id = 'tochkagg-custom-cursor-element';
                cursorElement.style.cssText = `
                    position: fixed;
                    width: 48px;
                    height: 48px;
                    pointer-events: none;
                    z-index: 999999;
                    display: none;
                    will-change: transform;
                    transform: translate(0, 0);
                    left: 0;
                    top: 0;
                `;
                
                // Создаем изображение курсора
                // Используем object-fit: contain для вписывания любого изображения в 48x48px
                const cursorImage = document.createElement('img');
                cursorImage.src = '<?php echo $cursor_url; ?>';
                cursorImage.style.cssText = `
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    display: block;
                `;
                cursorImage.alt = 'Custom cursor';
                cursorElement.appendChild(cursorImage);
                document.body.appendChild(cursorElement);
            
            // Расположение точки клика курсора
            const hotspotPosition = '<?php echo esc_js($hotspot_position); ?>';
            const cursorSize = 48; // Размер курсора в пикселях
            
            // Вычисляем смещение в зависимости от расположения точки клика
            // Дефолтный курсор имеет точку клика в верхнем левом углу (0, 0)
            let offsetX = 0;
            let offsetY = 0;
            
            switch(hotspotPosition) {
                case 'top-left':
                    // Верхний левый угол (как дефолтный курсор)
                    offsetX = 0;
                    offsetY = 0;
                    break;
                case 'top-center':
                    // Верхний центр
                    offsetX = -cursorSize / 2;
                    offsetY = 0;
                    break;
                case 'top-right':
                    // Верхний правый угол
                    offsetX = -cursorSize;
                    offsetY = 0;
                    break;
                case 'center':
                    // Центр
                    offsetX = -cursorSize / 2;
                    offsetY = -cursorSize / 2;
                    break;
                default:
                    // По умолчанию верхний левый угол
                    offsetX = 0;
                    offsetY = 0;
            }
            
            // Отслеживаем движение мыши - резкое движение без задержки (как дефолтный курсор)
            // Оптимизировано: используем transform вместо left/top для лучшей производительности
            let lastCursorX = 0;
            let lastCursorY = 0;
            let rafCursorId = null;
            
            function updateCursorPosition(x, y) {
                const newX = x + offsetX;
                const newY = y + offsetY;
                
                // Обновляем только если позиция изменилась (избегаем лишних обновлений)
                if (newX !== lastCursorX || newY !== lastCursorY) {
                    cursorElement.style.transform = `translate(${newX}px, ${newY}px)`;
                    lastCursorX = newX;
                    lastCursorY = newY;
                }
                
                cursorElement.style.display = 'block';
            }
            
            function handleMouseMove(e) {
                // Используем requestAnimationFrame для синхронизации с браузером
                if (!rafCursorId) {
                    rafCursorId = requestAnimationFrame(function() {
                        updateCursorPosition(e.clientX, e.clientY);
                        rafCursorId = null;
                    });
                } else {
                    // Если уже есть запланированное обновление, обновляем координаты
                    const currentX = e.clientX;
                    const currentY = e.clientY;
                    rafCursorId = requestAnimationFrame(function() {
                        updateCursorPosition(currentX, currentY);
                        rafCursorId = null;
                    });
                }
            }
            
            document.addEventListener('mousemove', handleMouseMove, { passive: true });
            
            // Скрываем курсор при выходе за пределы окна
            document.addEventListener('mouseleave', function() {
                cursorElement.style.display = 'none';
            });
            
            // Показываем курсор при входе в окно
            document.addEventListener('mouseenter', function(e) {
                updateCursorPosition(e.clientX, e.clientY);
            });
            
            // ============================================
            // Эффект хвоста курсора (trail effect) - непрерывная полоса (оптимизировано)
            // ============================================
            const trailEnabled = <?php echo $trail_enabled ? 'true' : 'false'; ?>;
            const trailColor = '<?php echo esc_js($trail_color); ?>';
            
            if (trailEnabled) {
                // Используем Canvas для производительности
                const trailCanvas = document.createElement('canvas');
                trailCanvas.id = 'tochkagg-cursor-trail';
                trailCanvas.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    pointer-events: none;
                    z-index: 999998;
                `;
                document.body.appendChild(trailCanvas);
                
                // Оптимизированный контекст canvas
                const ctx = trailCanvas.getContext('2d', { 
                    alpha: true,
                    desynchronized: true // Улучшает производительность
                });
                
                const trailWidth = 3; // Толщина линии хвоста
                const maxTrailLength = 12; // Уменьшено для производительности (было 20)
                const minDistance = 3; // Минимальное расстояние между точками (пиксели)
                const fadeDelay = 80; // Задержка перед затуханием (мс)
                
                // Устанавливаем размер canvas
                function resizeCanvas() {
                    const w = window.innerWidth;
                    const h = window.innerHeight;
                    trailCanvas.width = w;
                    trailCanvas.height = h;
                }
                resizeCanvas();
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(resizeCanvas, 100);
                }, { passive: true });
                
                // Массив точек хвоста (только координаты, без времени)
                const trailPoints = [];
                let rafTrailId = null;
                let lastAddTime = 0;
                let lastX = 0;
                let lastY = 0;
                let isAnimating = false;
                
                // Throttle для добавления точек (16ms = ~60fps)
                const addPointThrottle = 16;
                
                // Функция добавления точки в хвост (оптимизировано)
                function addTrailPoint(x, y) {
                    const now = performance.now();
                    
                    // Throttle: добавляем точку не чаще чем раз в 16ms
                    if (now - lastAddTime < addPointThrottle) {
                        return;
                    }
                    
                    // Проверяем минимальное расстояние (избегаем дубликатов)
                    if (trailPoints.length > 0) {
                        const lastPoint = trailPoints[trailPoints.length - 1];
                        const dx = x - lastPoint.x;
                        const dy = y - lastPoint.y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        if (distance < minDistance) {
                            return; // Пропускаем точку если слишком близко
                        }
                    }
                    
                    trailPoints.push({ x, y });
                    lastX = x;
                    lastY = y;
                    lastAddTime = now;
                    
                    // Ограничиваем длину хвоста
                    if (trailPoints.length > maxTrailLength) {
                        trailPoints.shift();
                    }
                    
                    // Запускаем анимацию если еще не запущена
                    if (!isAnimating && trailPoints.length >= 2) {
                        isAnimating = true;
                        if (!rafTrailId) {
                            rafTrailId = requestAnimationFrame(drawTrail);
                        }
                    }
                }
                
                // Оптимизированная функция отрисовки хвоста
                function drawTrail() {
                    // Если точек меньше 2, останавливаем анимацию
                    if (trailPoints.length < 2) {
                        ctx.clearRect(0, 0, trailCanvas.width, trailCanvas.height);
                        isAnimating = false;
                        rafTrailId = null;
                        return;
                    }
                    
                    // Очищаем canvas (оптимизировано)
                    ctx.clearRect(0, 0, trailCanvas.width, trailCanvas.height);
                    
                    // Настройки линии (выносим из цикла)
                    ctx.strokeStyle = trailColor;
                    ctx.lineWidth = trailWidth;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    
                    // Рисуем упрощенную линию (без квадратичных кривых для производительности)
                    ctx.beginPath();
                    ctx.moveTo(trailPoints[0].x, trailPoints[0].y);
                    
                    // Простая линия через все точки (быстрее чем кривые)
                    for (let i = 1; i < trailPoints.length; i++) {
                        ctx.lineTo(trailPoints[i].x, trailPoints[i].y);
                    }
                    
                    ctx.stroke();
                    
                    // Продолжаем анимацию
                    rafTrailId = requestAnimationFrame(drawTrail);
                }
                
                // Обновляем позицию хвоста при движении мыши (оптимизировано)
                const originalHandleMouseMove = handleMouseMove;
                const newHandleMouseMove = function(e) {
                    originalHandleMouseMove(e);
                    addTrailPoint(e.clientX, e.clientY);
                };
                
                // Переопределяем обработчик
                document.removeEventListener('mousemove', handleMouseMove, { passive: true });
                document.addEventListener('mousemove', newHandleMouseMove, { passive: true });
                
                // Очищаем хвост при остановке движения (оптимизировано)
                let stopTrailTimeout;
                const clearTrail = function() {
                    clearTimeout(stopTrailTimeout);
                    stopTrailTimeout = setTimeout(function() {
                        trailPoints.length = 0;
                        if (rafTrailId) {
                            cancelAnimationFrame(rafTrailId);
                            rafTrailId = null;
                        }
                        isAnimating = false;
                        ctx.clearRect(0, 0, trailCanvas.width, trailCanvas.height);
                    }, fadeDelay);
                };
                
                // Очищаем при остановке движения
                document.addEventListener('mousemove', clearTrail, { passive: true });
                
                // Скрываем хвост при выходе за пределы окна
                document.addEventListener('mouseleave', function() {
                    trailPoints.length = 0;
                    if (rafTrailId) {
                        cancelAnimationFrame(rafTrailId);
                        rafTrailId = null;
                    }
                    isAnimating = false;
                    ctx.clearRect(0, 0, trailCanvas.width, trailCanvas.height);
                });
            }
            }
            
            // Инициализируем курсор после загрузки DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initCustomCursor);
            } else {
                // DOM уже загружен
                initCustomCursor();
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'tochkagg_custom_cursor', 100);


