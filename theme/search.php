<?php
/**
 * Search Results Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <section class="tgg-search">
        <div class="tgg-container">
            <header class="tgg-search__header">
                <h1 class="tgg-search__title">
                    Результаты поиска
                    <?php if (get_search_query()) : ?>
                        по запросу: "<?php echo esc_html(get_search_query()); ?>"
                    <?php endif; ?>
                </h1>
            </header>
            
            <?php if (have_posts()) : ?>
                <div class="tgg-search__results">
                    <?php while (have_posts()) : the_post(); ?>
                        <article class="tgg-search__item">
                            <h2 class="tgg-search__item-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            
                            <div class="tgg-search__item-meta">
                                <span class="tgg-search__item-type">
                                    <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                                </span>
                                <?php if (get_the_date()) : ?>
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('d F Y')); ?>
                                    </time>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (get_the_excerpt()) : ?>
                                <div class="tgg-search__item-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="tgg-search__item-link">
                                Читать далее →
                            </a>
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
                <div class="tgg-search__empty">
                    <p>По вашему запросу ничего не найдено.</p>
                    <div class="tgg-search__empty-actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="tgg-btn-primary">
                            Вернуться на главную
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>


