<?php
/**
 * Custom Post Types
 * 
 * Регистрация кастомных типов записей: турниры, новости
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Регистрация Custom Post Type: Турниры
 */
function tochkagg_register_tournament_post_type() {
    $labels = [
        'name' => __('Турниры', 'tochkagg'),
        'singular_name' => __('Турнир', 'tochkagg'),
        'menu_name' => __('Турниры', 'tochkagg'),
        'add_new' => __('Добавить турнир', 'tochkagg'),
        'add_new_item' => __('Добавить новый турнир', 'tochkagg'),
        'edit_item' => __('Редактировать турнир', 'tochkagg'),
        'new_item' => __('Новый турнир', 'tochkagg'),
        'view_item' => __('Просмотреть турнир', 'tochkagg'),
        'search_items' => __('Поиск турниров', 'tochkagg'),
        'not_found' => __('Турниры не найдены', 'tochkagg'),
        'not_found_in_trash' => __('В корзине нет турниров', 'tochkagg'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'tournaments'],
        'capability_type' => 'post',
        'map_meta_cap' => true, // Использовать стандартные права доступа WordPress (автоматически создаст все нужные capabilities)
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-awards',
        'supports' => [
            'title',           // Заголовок
            'editor',          // Редактор контента (Gutenberg/классический)
            'thumbnail',       // Миниатюра записи
            'excerpt',         // Краткое описание
            'custom-fields',   // Дополнительные поля
            'revisions',       // Ревизии
        ],
        'show_in_rest' => true, // Поддержка Gutenberg
        'rest_base' => 'tournaments',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'can_export' => true, // Разрешить экспорт
    ];

    register_post_type('tournament', $args);
}
add_action('init', 'tochkagg_register_tournament_post_type');

/**
 * Регистрация Custom Post Type: Новости
 */
function tochkagg_register_news_post_type() {
    $labels = [
        'name' => __('Новости', 'tochkagg'),
        'singular_name' => __('Новость', 'tochkagg'),
        'menu_name' => __('Новости', 'tochkagg'),
        'add_new' => __('Добавить новость', 'tochkagg'),
        'add_new_item' => __('Добавить новую новость', 'tochkagg'),
        'edit_item' => __('Редактировать новость', 'tochkagg'),
        'new_item' => __('Новая новость', 'tochkagg'),
        'view_item' => __('Просмотреть новость', 'tochkagg'),
        'search_items' => __('Поиск новостей', 'tochkagg'),
        'not_found' => __('Новости не найдены', 'tochkagg'),
        'not_found_in_trash' => __('В корзине нет новостей', 'tochkagg'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'news'],
        'capability_type' => 'post',
        'map_meta_cap' => true, // Использовать стандартные права доступа WordPress (автоматически создаст все нужные capabilities)
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => [
            'title',           // Заголовок
            'editor',          // Редактор контента (Gutenberg/классический)
            'thumbnail',       // Миниатюра записи
            'excerpt',         // Краткое описание
            'author',          // Автор
            'custom-fields',   // Дополнительные поля
            'revisions',       // Ревизии
        ],
        'show_in_rest' => true, // Поддержка Gutenberg
        'rest_base' => 'news',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'can_export' => true, // Разрешить экспорт
    ];

    register_post_type('news', $args);
}
add_action('init', 'tochkagg_register_news_post_type');


