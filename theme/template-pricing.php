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
    
    <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
        <div class="tgg-container">
            <?php get_template_part('template-parts/components/info-notice'); ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>


