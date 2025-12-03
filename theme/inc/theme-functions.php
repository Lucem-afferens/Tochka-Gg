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
 * Предотвращение кеширования во время разработки
 * Добавляет заголовки для предотвращения кеширования HTML
 */
function tochkagg_prevent_cache() {
    if (!is_admin()) {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
add_action('init', 'tochkagg_prevent_cache', 1);

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


