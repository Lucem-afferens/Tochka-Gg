<?php
/**
 * Content Page Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<article class="tgg-page">
    <div class="tgg-container">
        <?php if (get_the_title()) : ?>
            <h1 class="tgg-page__title">
                <?php the_title(); ?>
            </h1>
        <?php endif; ?>
        
        <div class="tgg-page__content">
            <?php the_content(); ?>
        </div>
        
        <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
            <?php get_template_part('template-parts/components/info-notice'); ?>
        <?php endif; ?>
    </div>
</article>


