<?php
/**
 * Tochka Gg Theme Functions
 * 
 * @package Tochkagg_Theme
 */

// Защита от прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

// Константы темы
define('TOCHKAGG_THEME_VERSION', '1.0.1'); // Обновляем версию для сброса кеша
define('TOCHKAGG_THEME_PATH', get_template_directory());
define('TOCHKAGG_THEME_URI', get_template_directory_uri());

// Подключение модулей темы
require_once TOCHKAGG_THEME_PATH . '/inc/theme-setup.php';
require_once TOCHKAGG_THEME_PATH . '/inc/enqueue-assets.php';
require_once TOCHKAGG_THEME_PATH . '/inc/custom-post-types.php';
require_once TOCHKAGG_THEME_PATH . '/inc/theme-functions.php';
require_once TOCHKAGG_THEME_PATH . '/inc/theme-security.php';
require_once TOCHKAGG_THEME_PATH . '/inc/theme-helpers.php';


