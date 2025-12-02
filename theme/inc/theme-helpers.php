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


