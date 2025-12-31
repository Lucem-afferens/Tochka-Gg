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
    
    // Выводим CSS для кастомного курсора
    ?>
    <style id="tochkagg-custom-cursor">
        /* Кастомный курсор */
        * {
            cursor: url('<?php echo $cursor_url; ?>'), auto !important;
        }
        
        /* Для интерактивных элементов используем pointer */
        a, button, [role="button"], input[type="submit"], input[type="button"], 
        .tgg-button, .swiper-button-next, .swiper-button-prev,
        .tgg-header__burger, .tgg-nav__link, .tgg-accordion__header {
            cursor: url('<?php echo $cursor_url; ?>'), pointer !important;
        }
        
        /* Для текстовых полей используем text */
        input[type="text"], input[type="email"], input[type="tel"], 
        input[type="number"], textarea {
            cursor: url('<?php echo $cursor_url; ?>'), text !important;
        }
        
        /* Для элементов, которые нельзя выбрать */
        .no-select, [draggable="false"] {
            cursor: url('<?php echo $cursor_url; ?>'), not-allowed !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'tochkagg_custom_cursor', 100);


