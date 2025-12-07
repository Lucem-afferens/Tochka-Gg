<?php
/**
 * Template Name: Страница бронирования
 * 
 * Шаблон для страницы бронирования мест
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php get_template_part('template-parts/components/booking-section'); ?>
</main>

<?php get_footer(); ?>

