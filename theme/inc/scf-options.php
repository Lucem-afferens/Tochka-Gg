<?php
/**
 * SCF Options Page Registration
 * 
 * Регистрация Options Page для Secure Custom Fields (SCF)
 * SCF является форком ACF и совместим с его API
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Добавление Options Page для SCF
 * 
 * SCF поддерживает Options Page из коробки (как ACF Pro)
 * Хук scf/init аналогичен acf/init в ACF
 */
function tochkagg_add_scf_options_page() {
    // Проверяем наличие функции SCF для добавления Options Page
    // SCF использует тот же API, что и ACF Pro
    if (function_exists('scf_add_options_page')) {
        // Основная Options Page
        scf_add_options_page([
            'page_title'    => __('Настройки темы', 'tochkagg'),
            'menu_title'    => __('Настройки темы', 'tochkagg'),
            'menu_slug'     => 'theme-options',
            'capability'    => 'edit_posts',
            'icon_url'      => 'dashicons-admin-settings',
            'position'      => 2,
            'redirect'      => false,
        ]);
    } elseif (function_exists('acf_add_options_page')) {
        // Fallback для совместимости: если SCF использует acf_add_options_page
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
        add_action('admin_notices', 'tochkagg_scf_options_page_notice');
    }
}

// Пробуем оба хука для максимальной совместимости
add_action('scf/init', 'tochkagg_add_scf_options_page');
add_action('acf/init', 'tochkagg_add_scf_options_page'); // Fallback для совместимости

/**
 * Уведомление о необходимости установки SCF
 */
function tochkagg_scf_options_page_notice() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $class = 'notice notice-warning is-dismissible';
    $message = sprintf(
        __('%sВнимание:%s Для работы Options Page требуется установить плагин %sSecure Custom Fields%s. Установите плагин через раздел "Плагины" > "Добавить новый" и найдите "Secure Custom Fields".', 'tochkagg'),
        '<strong>',
        '</strong>',
        '<a href="https://wordpress.org/plugins/secure-custom-fields/" target="_blank">',
        '</a>'
    );
    
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
}

