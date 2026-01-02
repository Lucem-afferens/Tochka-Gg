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
            <?php 
            // Хлебные крошки
            if (locate_template('template-parts/components/breadcrumbs.php')) {
                get_template_part('template-parts/components/breadcrumbs');
            }
            ?>
            
            <?php while (have_posts()) : the_post(); ?>
                <header class="tgg-single-news__header">
                    <div class="tgg-single-news__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php 
                            // Оптимизированное изображение с srcset и sizes
                            $thumbnail_id = get_post_thumbnail_id();
                            echo wp_get_attachment_image(
                                $thumbnail_id,
                                'large',
                                false,
                                [
                                    'loading' => 'eager',
                                    'decoding' => 'async',
                                    'alt' => get_the_title(),
                                    'fetchpriority' => 'high'
                                ]
                            );
                            ?>
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
                    <?php
                    // Связанные новости (внутренняя перелинковка)
                    $related_news = get_posts([
                        'post_type' => 'news',
                        'posts_per_page' => 3,
                        'post__not_in' => [get_the_ID()],
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ]);
                    
                    if ($related_news) : ?>
                        <div class="tgg-single-news__related">
                            <h2 class="tgg-single-news__related-title">Похожие новости</h2>
                            <div class="tgg-single-news__related-items">
                                <?php foreach ($related_news as $related_post) : ?>
                                    <article class="tgg-single-news__related-item">
                                        <a href="<?php echo esc_url(get_permalink($related_post->ID)); ?>" class="tgg-single-news__related-link">
                                            <?php if (has_post_thumbnail($related_post->ID)) : ?>
                                                <div class="tgg-single-news__related-image">
                                                    <?php echo get_the_post_thumbnail($related_post->ID, 'thumbnail', ['loading' => 'lazy', 'decoding' => 'async']); ?>
                                                </div>
                                            <?php endif; ?>
                                            <h3 class="tgg-single-news__related-title-item">
                                                <?php echo esc_html(get_the_title($related_post->ID)); ?>
                                            </h3>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <nav class="tgg-single-news__navigation" aria-label="Навигация по новостям">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        ?>
                        
                        <?php if ($prev_post) : ?>
                            <div class="tgg-single-news__nav-prev">
                                <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" rel="prev">
                                    ← <?php echo esc_html(get_the_title($prev_post)); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="tgg-single-news__nav-next">
                                <a href="<?php echo esc_url(get_permalink($next_post)); ?>" rel="next">
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


