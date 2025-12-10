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
                    <div class="tgg-single-news__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else : ?>
                            <?php 
                            $news_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                ? tochkagg_get_placeholder_image(1200, 600, get_the_title() . ' - Новость', '1a1d29', '3b82f6')
                                : 'https://placehold.co/1200x600/1a1d29/3b82f6?text=' . urlencode(get_the_title() . ' - Новость');
                            ?>
                            <img src="<?php echo esc_url($news_placeholder); ?>" 
                                 alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                 loading="eager">
                        <?php endif; ?>
                    </div>
                    
                    <div class="tgg-single-news__meta">
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date('d F Y')); ?>
                        </time>
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


