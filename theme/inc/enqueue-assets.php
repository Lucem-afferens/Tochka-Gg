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
        wp_enqueue_style(
            'tochkagg-style',
            TOCHKAGG_THEME_URI . '/assets/css/style.css',
            array(),
            filemtime($style_path)
        );
    }

    // Основной скрипт
    $script_path = TOCHKAGG_THEME_PATH . '/assets/js/main.js';
    if (file_exists($script_path)) {
        wp_enqueue_script(
            'tochkagg-script',
            TOCHKAGG_THEME_URI . '/assets/js/main.js',
            array(),
            filemtime($script_path),
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
 * Отключение Gutenberg стилей (опционально)
 */
function tochkagg_disable_gutenberg_styles() {
    // Можно раскомментировать если не используете Gutenberg
    // wp_dequeue_style('wp-block-library');
    // wp_dequeue_style('wp-block-library-theme');
    // wp_dequeue_style('wc-block-style');
}
add_action('wp_enqueue_scripts', 'tochkagg_disable_gutenberg_styles', 100);
