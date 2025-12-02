<?php
/**
 * Content None Template
 * 
 * Шаблон когда контент не найден
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="tgg-content-none">
    <h2><?php esc_html_e('Ничего не найдено', 'tochkagg'); ?></h2>
    <p><?php esc_html_e('Попробуйте поискать что-то другое.', 'tochkagg'); ?></p>
</div>

