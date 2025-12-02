<?php
/**
 * Tournaments Preview Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$tournaments_title = get_field('tournaments_preview_title') ?: 'Ближайшие турниры';
$tournaments_count = get_field('tournaments_preview_count') ?: 3;
$tournaments_link = get_field('tournaments_preview_link') ?: '/tournaments';

// Получаем последние турниры
$tournaments_query = new WP_Query([
    'post_type' => 'tournament',
    'posts_per_page' => $tournaments_count,
    'post_status' => 'publish',
    'meta_query' => [
        [
            'key' => 'tournament_date',
            'value' => date('Y-m-d'),
            'compare' => '>=',
            'type' => 'DATE'
        ]
    ],
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => 'tournament_date'
]);
?>

<section class="tgg-tournaments-preview">
    <div class="tgg-container">
        <?php if ($tournaments_title) : ?>
            <h2 class="tgg-tournaments-preview__title">
                <?php echo esc_html($tournaments_title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if ($tournaments_query->have_posts()) : ?>
            <div class="tgg-tournaments-preview__items">
                <?php while ($tournaments_query->have_posts()) : $tournaments_query->the_post(); 
                    $tournament_date = get_field('tournament_date');
                    $tournament_time = get_field('tournament_time');
                    $tournament_prize = get_field('tournament_prize');
                ?>
                    <div class="tgg-tournaments-preview__item">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="tgg-tournaments-preview__item-image">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="tgg-tournaments-preview__item-content">
                            <h3 class="tgg-tournaments-preview__item-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <?php if ($tournament_date) : ?>
                                <div class="tgg-tournaments-preview__item-date">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                        <path d="M3 10h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                    <?php echo esc_html(date_i18n('d F Y', strtotime($tournament_date))); ?>
                                    <?php if ($tournament_time) : ?>
                                        <span>в <?php echo esc_html($tournament_time); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tournament_prize) : ?>
                                <div class="tgg-tournaments-preview__item-prize">
                                    <span>Призовой фонд:</span>
                                    <strong><?php echo esc_html($tournament_prize); ?></strong>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (get_the_excerpt()) : ?>
                                <p class="tgg-tournaments-preview__item-excerpt">
                                    <?php echo esc_html(get_the_excerpt()); ?>
                                </p>
                            <?php endif; ?>
                            
                            <a href="<?php the_permalink(); ?>" class="tgg-tournaments-preview__item-link">
                                Узнать подробнее →
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <?php if ($tournaments_link) : ?>
                <div class="tgg-tournaments-preview__cta">
                    <a href="<?php echo esc_url($tournaments_link); ?>" class="tgg-btn-secondary">
                        Все турниры
                    </a>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="tgg-tournaments-preview__empty">
                <p>Ближайших турниров пока нет. Следите за новостями!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

