<?php
/**
 * Template Name: Страница оборудования
 * 
 * Шаблон для страницы с полными характеристиками оборудования
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php get_template_part('template-parts/components/equipment-full'); ?>
</main>

<?php get_footer(); ?>


