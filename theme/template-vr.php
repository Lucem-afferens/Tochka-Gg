<?php
/**
 * Template Name: Страница VR арены
 * 
 * Шаблон для страницы VR арены "Другие миры"
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php get_template_part('template-parts/components/vr-section'); ?>
    
    <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
        <div class="tgg-container">
            <?php get_template_part('template-parts/components/info-notice'); ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>


