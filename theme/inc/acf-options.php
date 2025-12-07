<?php
/**
 * ACF Options Page Registration
 * 
 * Регистрация Options Page для Advanced Custom Fields
 * Работает с ACF Pro или плагином-расширением для ACF Free
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Добавление Options Page для ACF
 * 
 * Примечание: Options Pages требуют ACF Pro или плагин-расширение "ACF Options Page"
 * Если у вас ACF Free, установите плагин:
 * https://wordpress.org/plugins/acf-options-page/
 * 
 * Или купите ACF Pro: https://www.advancedcustomfields.com/pro/
 */
function tochkagg_add_acf_options_page() {
    // Проверяем наличие функции ACF Pro или плагина-расширения
    if (function_exists('acf_add_options_page')) {
        // Основная Options Page
        acf_add_options_page([
            'page_title'    => __('Настройки темы', 'tochkagg'),
            'menu_title'    => __('Настройки темы', 'tochkagg'),
            'menu_slug'     => 'theme-options',
            'capability'    => 'edit_posts',
            'icon_url'      => 'dashicons-admin-settings',
            'position'      => 2,
            'redirect'      => false,
        ]);
    } else {
        // Если функция недоступна, выводим уведомление в админке
        add_action('admin_notices', 'tochkagg_acf_options_page_notice');
    }
}
add_action('acf/init', 'tochkagg_add_acf_options_page');

/**
 * Уведомление о необходимости ACF Pro или плагина-расширения
 */
function tochkagg_acf_options_page_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $class = 'notice notice-warning is-dismissible';
    $message = sprintf(
        __('%sВнимание:%s Для работы Options Page требуется %sACF Pro%s или плагин %sACF Options Page%s. Установите один из вариантов, чтобы получить доступ к настройкам темы.', 'tochkagg'),
        '<strong>',
        '</strong>',
        '<a href="https://www.advancedcustomfields.com/pro/" target="_blank">',
        '</a>',
        '<a href="https://wordpress.org/plugins/acf-options-page/" target="_blank">',
        '</a>'
    );
    
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
}

