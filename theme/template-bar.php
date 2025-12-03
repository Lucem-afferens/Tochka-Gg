<?php
/**
 * Template Name: Клубный бар
 * 
 * Шаблон для страницы клубного бара с каталогом товаров
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php get_template_part('template-parts/components/bar-menu'); ?>
</main>

<?php get_footer(); ?>


