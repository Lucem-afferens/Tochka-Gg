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
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="tgg-archive__item-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="tgg-archive__item-content">
                                <div class="tgg-archive__item-meta">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('d F Y')); ?>
                                    </time>
                                    <?php if (get_the_author()) : ?>
                                        <span class="tgg-archive__item-author">
                                            <?php echo esc_html(get_the_author()); ?>
                                        </span>
                                    <?php endif; ?>
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


