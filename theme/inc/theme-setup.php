<?php
/**
 * Theme Setup
 * 
 * Настройка темы, поддержка функций, регистрация меню
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Настройка темы
 */
function tochkagg_theme_setup() {
    // Поддержка автоматического заголовка страницы
    add_theme_support('title-tag');

    // Поддержка миниатюр записей
    add_theme_support('post-thumbnails');

    // Поддержка HTML5 разметки
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Поддержка широких и полных блоков в редакторе
    add_theme_support('align-wide');

    // Поддержка Gutenberg
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');

    // Поддержка выборочного обновления стилей в редакторе
    add_theme_support('editor-styles');
    add_editor_style('assets/css/style.css');

    // Размеры изображений для адаптивности
    add_image_size('tgg-thumbnail', 300, 300, true);
    add_image_size('tgg-medium', 768, 512, true);
    add_image_size('tgg-large', 1200, 800, true);
    add_image_size('tgg-xlarge', 1920, 1080, false); // Для hero секций
    
    // Включаем автоматическую генерацию srcset для изображений
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'tochkagg_theme_setup');

/**
 * Регистрация меню
 */
function tochkagg_register_menus() {
    register_nav_menus([
        'main_menu' => __('Главное меню', 'tochkagg'),
        'footer_menu' => __('Меню в подвале', 'tochkagg'),
    ]);
}
add_action('init', 'tochkagg_register_menus');

/**
 * Регистрация сайдбаров (если нужны)
 */
function tochkagg_register_sidebars() {
    // Можно добавить сайдбары при необходимости
}
add_action('widgets_init', 'tochkagg_register_sidebars');

/**
 * Обновление правил перезаписи при активации темы
 */
function tochkagg_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'tochkagg_flush_rewrite_rules');

/**
 * Добавление data-nav атрибутов к ссылкам меню для стилизации
 */
function tochkagg_add_nav_attributes($atts, $item, $args) {
    // Проверяем, что это главное меню
    if ($args->theme_location === 'main_menu') {
        $url = $item->url;
        $nav_value = null;
        
        // Определяем data-nav на основе URL
        if (empty($url) || $url === home_url('/') || $url === home_url()) {
            $nav_value = 'home';
        } elseif (strpos($url, 'equipment') !== false || strpos($url, 'оборудование') !== false) {
            $nav_value = 'equipment';
        } elseif (strpos($url, 'pricing') !== false || strpos($url, 'цены') !== false) {
            $nav_value = 'pricing';
        } elseif (strpos($url, 'vr') !== false) {
            $nav_value = 'vr';
        } elseif (strpos($url, 'bar') !== false || strpos($url, 'бар') !== false) {
            $nav_value = 'bar';
        } elseif (strpos($url, 'contacts') !== false || strpos($url, 'контакты') !== false) {
            $nav_value = 'contacts';
        }
        
        // Добавляем data-nav атрибут, если определен
        if ($nav_value) {
            $atts['data-nav'] = $nav_value;
        }
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'tochkagg_add_nav_attributes', 10, 3);


