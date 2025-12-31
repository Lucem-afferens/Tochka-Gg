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
            function handleMouseMove(e) {
                // Курсор сразу следует за мышью с учетом смещения для точки клика
                cursorElement.style.left = (e.clientX + offsetX) + 'px';
                cursorElement.style.top = (e.clientY + offsetY) + 'px';
                cursorElement.style.display = 'block';
            }
            
            document.addEventListener('mousemove', handleMouseMove, { passive: true });
            
            // Скрываем курсор при выходе за пределы окна
            document.addEventListener('mouseleave', function() {
                cursorElement.style.display = 'none';
            });
            
            // Показываем курсор при входе в окно
            document.addEventListener('mouseenter', function(e) {
                cursorElement.style.left = (e.clientX + offsetX) + 'px';
                cursorElement.style.top = (e.clientY + offsetY) + 'px';
                cursorElement.style.display = 'block';
            });
            
            // ============================================
            // Эффект хвоста курсора (trail effect)
            // ============================================
            const trailEnabled = <?php echo $trail_enabled ? 'true' : 'false'; ?>;
            const trailColor = '<?php echo esc_js($trail_color); ?>';
            
            if (trailEnabled) {
                // Создаем контейнер для хвоста
                const trailContainer = document.createElement('div');
                trailContainer.id = 'tochkagg-cursor-trail';
                trailContainer.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    pointer-events: none;
                    z-index: 999998;
                    overflow: hidden;
                `;
                document.body.appendChild(trailContainer);
                
                // Массив точек хвоста
                const trailPoints = [];
                const maxTrailPoints = 8; // Количество точек в хвосте
                const trailPointSize = 4; // Размер точки хвоста
                let lastMouseX = 0;
                let lastMouseY = 0;
                let rafTrailId = null;
                
                // Создаем точки хвоста
                for (let i = 0; i < maxTrailPoints; i++) {
                    const point = document.createElement('div');
                    point.style.cssText = `
                        position: absolute;
                        width: ${trailPointSize}px;
                        height: ${trailPointSize}px;
                        background: ${trailColor};
                        border-radius: 50%;
                        opacity: ${(maxTrailPoints - i) / maxTrailPoints * 0.6};
                        transform: translate(-50%, -50%);
                        transition: opacity 0.1s ease-out;
                    `;
                    trailContainer.appendChild(point);
                    trailPoints.push({
                        element: point,
                        x: 0,
                        y: 0
                    });
                }
                
                // Функция обновления хвоста
                function updateTrail() {
                    trailPoints.forEach((point, index) => {
                        if (index === 0) {
                            // Первая точка следует за мышью
                            point.x = lastMouseX;
                            point.y = lastMouseY;
                        } else {
                            // Остальные точки следуют за предыдущей с задержкой
                            const prevPoint = trailPoints[index - 1];
                            point.x += (prevPoint.x - point.x) * 0.3;
                            point.y += (prevPoint.y - point.y) * 0.3;
                        }
                        
                        point.element.style.left = point.x + 'px';
                        point.element.style.top = point.y + 'px';
                    });
                    
                    rafTrailId = requestAnimationFrame(updateTrail);
                }
                
                // Обновляем позицию хвоста при движении мыши
                const originalHandleMouseMove = handleMouseMove;
                const newHandleMouseMove = function(e) {
                    originalHandleMouseMove(e);
                    lastMouseX = e.clientX;
                    lastMouseY = e.clientY;
                    
                    if (!rafTrailId) {
                        updateTrail();
                    }
                };
                
                // Переопределяем обработчик
                document.removeEventListener('mousemove', handleMouseMove, { passive: true });
                document.addEventListener('mousemove', newHandleMouseMove, { passive: true });
                
                // Скрываем хвост при выходе за пределы окна
                document.addEventListener('mouseleave', function() {
                    trailPoints.forEach(point => {
                        point.element.style.opacity = '0';
                    });
                    if (rafTrailId) {
                        cancelAnimationFrame(rafTrailId);
                        rafTrailId = null;
                    }
                });
                
                // Показываем хвост при входе в окно
                document.addEventListener('mouseenter', function(e) {
                    lastMouseX = e.clientX;
                    lastMouseY = e.clientY;
                    trailPoints.forEach((point, index) => {
                        point.x = e.clientX;
                        point.y = e.clientY;
                        point.element.style.opacity = ((maxTrailPoints - index) / maxTrailPoints * 0.6).toString();
                    });
                    if (!rafTrailId) {
                        updateTrail();
                    }
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


