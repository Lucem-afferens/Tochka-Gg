<?php
/**
 * Page Template
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
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content/content', 'page');
    endwhile;
    ?>
</main>

<?php get_footer(); ?>


