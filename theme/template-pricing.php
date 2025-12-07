<?php
/**
 * Template Name: Страница цен
 * 
 * Шаблон для страницы с тарифами
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php get_template_part('template-parts/components/pricing-table'); ?>
</main>

<?php get_footer(); ?>


