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
        // Версия темы + время изменения файла (без time() для кеширования)
        $style_version = TOCHKAGG_THEME_VERSION . '.' . filemtime($style_path);
        
        wp_enqueue_style(
            'tochkagg-style',
            TOCHKAGG_THEME_URI . '/assets/css/style.css',
            array(),
            $style_version,
            'all'
        );
    }

    // Основной скрипт (отложенная загрузка для лучшей производительности)
    $script_path = TOCHKAGG_THEME_PATH . '/assets/js/main.js';
    if (file_exists($script_path)) {
        // Версия темы + время изменения файла (без time() для кеширования)
        $script_version = TOCHKAGG_THEME_VERSION . '.' . filemtime($script_path);
        
        wp_enqueue_script(
            'tochkagg-script',
            TOCHKAGG_THEME_URI . '/assets/js/main.js',
            array(),
            $script_version,
            true
        );
        // Добавляем defer для неблокирующей загрузки
        wp_script_add_data('tochkagg-script', 'defer', true);
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
 * Улучшение кеширования ресурсов
 */
function tochkagg_set_cache_headers() {
    // Устанавливаем заголовки кеширования для статических ресурсов
    if (!is_admin() && !is_user_logged_in()) {
        // CSS и JS файлы - кеш на 1 год
        if (strpos($_SERVER['REQUEST_URI'], '/assets/css/') !== false || 
            strpos($_SERVER['REQUEST_URI'], '/assets/js/') !== false) {
            header('Cache-Control: public, max-age=31536000, immutable');
        }
        // Изображения - кеш на 1 год
        if (strpos($_SERVER['REQUEST_URI'], '/uploads/') !== false) {
            header('Cache-Control: public, max-age=31536000, immutable');
        }
    }
}
add_action('send_headers', 'tochkagg_set_cache_headers');

/**
 * Добавление атрибутов width и height для изображений WordPress
 */
function tochkagg_add_image_dimensions($html, $post_id, $post_thumbnail_id, $size, $attr) {
    // Пропускаем, если уже есть width и height
    if (strpos($html, 'width=') !== false && strpos($html, 'height=') !== false) {
        return $html;
    }
    
    // Получаем размеры изображения
    $image_meta = wp_get_attachment_metadata($post_thumbnail_id);
    if ($image_meta && isset($image_meta['width']) && isset($image_meta['height'])) {
        $width = $image_meta['width'];
        $height = $image_meta['height'];
        
        // Если задан конкретный размер, пытаемся получить его размеры
        if ($size && is_array($size)) {
            $width = $size[0] ?? $width;
            $height = $size[1] ?? $height;
        } elseif ($size && is_string($size)) {
            $image_sizes = wp_get_registered_image_subsizes();
            if (isset($image_sizes[$size])) {
                $width = $image_sizes[$size]['width'] ?? $width;
                $height = $image_sizes[$size]['height'] ?? $height;
            }
        }
        
        // Добавляем атрибуты, если их нет
        $html = preg_replace(
            '/<img\s+([^>]*?)>/i',
            '<img $1 width="' . esc_attr($width) . '" height="' . esc_attr($height) . '">',
            $html
        );
    }
    
    return $html;
}
add_filter('post_thumbnail_html', 'tochkagg_add_image_dimensions', 10, 5);

/**
 * Отключение admin bar на фронтенде
 * Это устраняет ошибки с lodash и улучшает производительность
 * Администраторы могут заходить в админку через прямой URL: /wp-admin/
 */
add_filter('show_admin_bar', '__return_false');

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
