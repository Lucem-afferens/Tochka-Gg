<?php
/**
 * Enqueue Scripts and Styles
 * 
 * Подключение CSS и JavaScript файлов
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Подключение стилей и скриптов
 */
function tochkagg_enqueue_assets() {
    // Основной стиль
    $style_path = TOCHKAGG_THEME_PATH . '/assets/css/style.css';
    if (file_exists($style_path)) {
        // Используем версию темы + время изменения файла для надежного сброса кеша
        // Добавляем случайное число для принудительного обновления
        $style_version = TOCHKAGG_THEME_VERSION . '.' . filemtime($style_path) . '.' . rand(1000, 9999);
        wp_enqueue_style(
            'tochkagg-style',
            TOCHKAGG_THEME_URI . '/assets/css/style.css',
            array(),
            $style_version
        );
    }

    // Основной скрипт
    $script_path = TOCHKAGG_THEME_PATH . '/assets/js/main.js';
    if (file_exists($script_path)) {
        // Используем версию темы + время изменения файла для надежного сброса кеша
        $script_version = TOCHKAGG_THEME_VERSION . '.' . filemtime($script_path);
        wp_enqueue_script(
            'tochkagg-script',
            TOCHKAGG_THEME_URI . '/assets/js/main.js',
            array(),
            $script_version,
            true
        );
    }

    // Яндекс.Карты (для страницы контактов)
    if (is_page_template('template-contacts.php') || is_page_template('page-contacts.php')) {
        wp_enqueue_script(
            'yandex-maps',
            'https://api-maps.yandex.ru/2.1/?lang=ru_RU',
            array(),
            null,
            true
        );
    }

    // Локализация скрипта (если нужно)
    wp_localize_script('tochkagg-script', 'tochkaggData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tochkagg_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'tochkagg_enqueue_assets');

/**
 * Исправление проблемы с lodash для admin bar
 * Обеспечивает правильное подключение lodash перед admin bar скриптами
 */
function tochkagg_fix_admin_bar_lodash() {
    // Проверяем, что пользователь залогинен и admin bar должен отображаться
    if (!is_admin() && is_user_logged_in()) {
        // Отключаем текущую регистрацию lodash, если она есть
        wp_deregister_script('lodash');
        
        // Регистрируем lodash из WordPress core
        wp_register_script(
            'lodash',
            includes_url('js/dist/lodash.min.js'),
            array(),
            '4.17.21',
            false
        );
        
        // Добавляем inline скрипт для обеспечения совместимости
        wp_add_inline_script('lodash', '
            if (typeof window._ === "undefined" && typeof lodash !== "undefined") {
                window._ = lodash;
            }
            if (typeof window._ !== "undefined" && typeof window._.noConflict === "undefined") {
                window._.noConflict = function() {
                    return window._;
                };
            }
        ', 'after');
        
        // Убеждаемся, что admin bar зависит от lodash
        add_filter('script_loader_tag', function($tag, $handle) {
            if ($handle === 'admin-bar') {
                global $wp_scripts;
                if (isset($wp_scripts->registered['admin-bar'])) {
                    if (!in_array('lodash', $wp_scripts->registered['admin-bar']->deps)) {
                        $wp_scripts->registered['admin-bar']->deps[] = 'lodash';
                    }
                }
            }
            return $tag;
        }, 10, 2);
        
        // Принудительно загружаем lodash перед admin bar
        wp_enqueue_script('lodash');
    }
}
add_action('wp_enqueue_scripts', 'tochkagg_fix_admin_bar_lodash', 1);

/**
 * Отключение Gutenberg стилей (опционально)
 */
function tochkagg_disable_gutenberg_styles() {
    // Можно раскомментировать если не используете Gutenberg
    // wp_dequeue_style('wp-block-library');
    // wp_dequeue_style('wp-block-library-theme');
    // wp_dequeue_style('wc-block-style');
}
add_action('wp_enqueue_scripts', 'tochkagg_disable_gutenberg_styles', 100);
