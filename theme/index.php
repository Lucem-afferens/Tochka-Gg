<?php
/**
 * Main Template File (Fallback)
 * 
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            get_template_part('template-parts/content/content', get_post_type());
        endwhile;
    else :
        get_template_part('template-parts/content/content', 'none');
    endif;
    ?>
</main>

<?php get_footer(); ?>


