<?php
/**
 * Template Name: Страница контактов
 * 
 * Шаблон для страницы контактов
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php get_template_part('template-parts/components/contacts-section'); ?>
</main>

<?php get_footer(); ?>

