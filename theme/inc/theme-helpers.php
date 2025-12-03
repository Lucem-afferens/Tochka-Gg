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


