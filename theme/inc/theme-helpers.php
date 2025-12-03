<?php
/**
 * Theme Helpers
 * 
 * Вспомогательные функции
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Безопасная функция для получения поля ACF
 * Работает даже если ACF не установлен
 */
function tochkagg_get_field($selector, $post_id = false, $format_value = true) {
    if (function_exists('get_field')) {
        return get_field($selector, $post_id, $format_value);
    }
    return false;
}

/**
 * Безопасная функция для проверки существования поля ACF
 */
function tochkagg_has_field($selector, $post_id = false) {
    if (function_exists('get_field')) {
        $value = get_field($selector, $post_id);
        return $value !== false && $value !== null && $value !== '';
    }
    return false;
}

/**
 * Получить ACF поле с проверкой существования
 *
 * @param string $field_name Имя поля
 * @param mixed $default Значение по умолчанию
 * @return mixed
 */
function tochkagg_get_field_safe($field_name, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name);
        return $value !== false && $value !== null ? $value : $default;
    }
    return $default;
}

/**
 * Экранирование и вывод текста
 *
 * @param string $text Текст для вывода
 */
function tochkagg_echo($text) {
    echo esc_html($text);
}

/**
 * Экранирование и вывод HTML
 *
 * @param string $html HTML для вывода
 */
function tochkagg_echo_html($html) {
    echo wp_kses_post($html);
}

/**
 * Получить URL страницы по её slug (постоянной ссылке) или названию
 * 
 * @param string $slug Slug страницы (например: 'equipment', 'pricing', 'vr')
 * @param string $fallback URL по умолчанию, если страница не найдена
 * @return string URL страницы
 */
function tochkagg_get_page_url($slug, $fallback = '#') {
    // Сначала пытаемся найти по slug
    $page = get_page_by_path($slug);
    
    // Если не найдено, пытаемся найти по названию (на русском)
    if (!$page) {
        $title_map = [
            'equipment' => 'Оборудование',
            'pricing' => 'Цены',
            'contacts' => 'Контакты',
            'vr' => 'VR арена',
        ];
        
        if (isset($title_map[$slug])) {
            $pages = get_pages([
                'post_status' => 'publish',
                'number' => 1,
                'title' => $title_map[$slug],
            ]);
            
            if (!empty($pages)) {
                $page = $pages[0];
            }
        }
    }
    
    if ($page && $page->post_status === 'publish') {
        return get_permalink($page->ID);
    }
    
    // Если страница не найдена, возвращаем fallback или home_url со slug
    return $fallback !== '#' ? $fallback : home_url('/' . $slug . '/');
}


