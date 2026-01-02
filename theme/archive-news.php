<?php
/**
 * Archive Template: News
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <section class="tgg-archive">
        <div class="tgg-container">
            <?php 
            // Хлебные крошки
            if (locate_template('template-parts/components/breadcrumbs.php')) {
                get_template_part('template-parts/components/breadcrumbs');
            }
            ?>
            
            <header class="tgg-archive__header">
                <h1 class="tgg-archive__title">Новости</h1>
                <?php if (get_the_archive_description()) : ?>
                    <div class="tgg-archive__description">
                        <?php the_archive_description(); ?>
                    </div>
                <?php endif; ?>
            </header>
            
            <?php if (have_posts()) : ?>
                <div class="tgg-archive__items">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="tgg-archive__item">
                            <div class="tgg-archive__item-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php 
                                        // Оптимизированное изображение с srcset и sizes
                                        $thumbnail_id = get_post_thumbnail_id();
                                        echo wp_get_attachment_image(
                                            $thumbnail_id,
                                            'medium',
                                            false,
                                            [
                                                'loading' => 'lazy',
                                                'decoding' => 'async',
                                                'alt' => get_the_title()
                                            ]
                                        );
                                        ?>
                                    <?php else : ?>
                                        <?php 
                                        $news_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                            ? tochkagg_get_placeholder_image(600, 400, get_the_title() . ' - Новость', '1a1d29', '3b82f6')
                                            : 'https://placehold.co/600x400/1a1d29/3b82f6?text=' . urlencode(get_the_title() . ' - Новость');
                                        ?>
                                        <img src="<?php echo esc_url($news_placeholder); ?>" 
                                             alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                             loading="lazy">
                                    <?php endif; ?>
                                </a>
                            </div>
                            
                            <div class="tgg-archive__item-content">
                                <div class="tgg-archive__item-meta">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('d F Y')); ?>
                                    </time>
                                </div>
                                
                                <h2 class="tgg-archive__item-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <?php if (get_the_excerpt()) : ?>
                                    <div class="tgg-archive__item-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="tgg-archive__item-link">
                                    Читать далее →
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <?php
                the_posts_pagination([
                    'prev_text' => '← Назад',
                    'next_text' => 'Вперёд →',
                ]);
                ?>
            <?php else : ?>
                <div class="tgg-archive__empty">
                    <p>Новостей пока нет.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>


