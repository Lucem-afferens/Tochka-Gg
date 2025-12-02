<?php
/**
 * Single News Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <article class="tgg-single-news">
        <div class="tgg-container">
            <?php while (have_posts()) : the_post(); ?>
                <header class="tgg-single-news__header">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="tgg-single-news__image">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="tgg-single-news__meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date('d F Y')); ?>
                        </time>
                        <?php if (get_the_author()) : ?>
                            <span class="tgg-single-news__author">
                                Автор: <?php echo esc_html(get_the_author()); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="tgg-single-news__title">
                        <?php the_title(); ?>
                    </h1>
                </header>
                
                <div class="tgg-single-news__content">
                    <?php the_content(); ?>
                </div>
                
                <footer class="tgg-single-news__footer">
                    <nav class="tgg-single-news__navigation">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        
                        <?php if ($prev_post) : ?>
                            <div class="tgg-single-news__nav-prev">
                                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>">
                                    ← <?php echo esc_html(get_the_title($prev_post)); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="tgg-single-news__nav-next">
                                <a href="<?php echo esc_url(get_permalink($next_post)); ?>">
                                    <?php echo esc_html(get_the_title($next_post)); ?> →
                                </a>
                            </div>
                        <?php endif; ?>
                    </nav>
                </footer>
                
            <?php endwhile; ?>
        </div>
    </article>
</main>

<?php get_footer(); ?>

