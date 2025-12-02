<?php
/**
 * Theme Security
 * 
 * Функции безопасности
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Отключение XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Удаление информации о версии WordPress
 */
remove_action('wp_head', 'wp_generator');

/**
 * Отключение REST API для неавторизованных пользователей (опционально)
 */
// add_filter('rest_authentication_errors', function($result) {
//     if (!empty($result)) {
//         return $result;
//     }
//     if (!is_user_logged_in()) {
//         return new WP_Error('rest_not_logged_in', 'You are not logged in.', ['status' => 401]);
//     }
//     return $result;
// });

