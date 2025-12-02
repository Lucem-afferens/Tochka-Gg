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

    // Размеры изображений
    add_image_size('tgg-thumbnail', 300, 300, true);
    add_image_size('tgg-medium', 768, 512, true);
    add_image_size('tgg-large', 1200, 800, true);
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


