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
    // Маппинг английских slug на русские названия и slug
    $page_map = [
        'equipment' => [
            'title' => 'Оборудование',
            'slug_ru' => 'оборудование',
        ],
        'pricing' => [
            'title' => 'Цены',
            'slug_ru' => 'цены',
        ],
        'contacts' => [
            'title' => 'Контакты',
            'slug_ru' => 'контакты',
        ],
        'vr' => [
            'title' => 'VR арена',
            'slug_ru' => 'vr',
        ],
        'bar' => [
            'title' => 'Клубный бар',
            'slug_ru' => 'bar',
        ],
    ];
    
    $page = null;
    
    // 1. Сначала пытаемся найти по английскому slug
    $page = get_page_by_path($slug);
    
    // 2. Если не найдено и есть маппинг, пытаемся найти по русскому slug
    if (!$page && isset($page_map[$slug])) {
        $page = get_page_by_path($page_map[$slug]['slug_ru']);
    }
    
    // 3. Если все еще не найдено, ищем по названию страницы
    if (!$page && isset($page_map[$slug])) {
        $pages = get_pages([
            'post_status' => 'publish',
            'number' => 50, // Достаточно для поиска
        ]);
        
        foreach ($pages as $p) {
            if (trim($p->post_title) === trim($page_map[$slug]['title'])) {
                $page = $p;
                break;
            }
        }
    }
    
    // 4. Если нашли страницу, возвращаем её URL
    if ($page && $page->post_status === 'publish') {
        return get_permalink($page->ID);
    }
    
    // 5. Если страница не найдена, возвращаем fallback или пробуем русский slug
    if ($fallback !== '#') {
        return $fallback;
    }
    
    // Если есть русский slug, пробуем его
    if (isset($page_map[$slug])) {
        return home_url('/' . $page_map[$slug]['slug_ru'] . '/');
    }
    
    return home_url('/' . $slug . '/');
}


