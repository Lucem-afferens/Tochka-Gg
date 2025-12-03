<?php
/**
 * Single Tournament Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <article class="tgg-single-tournament">
        <div class="tgg-container">
            <?php while (have_posts()) : the_post(); 
                $tournament_date = get_field('tournament_date');
                $tournament_time = get_field('tournament_time');
                $tournament_prize = get_field('tournament_prize');
                $tournament_rules = get_field('tournament_rules');
                $tournament_registration = get_field('tournament_registration_link');
            ?>
                <header class="tgg-single-tournament__header">
                    <div class="tgg-single-tournament__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large'); ?>
                        <?php else : ?>
                            <?php 
                            $tournament_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                ? tochkagg_get_placeholder_image(1200, 600, get_the_title() . ' - Турнир', '1a1d29', 'c026d3')
                                : 'https://placehold.co/1200x600/1a1d29/c026d3?text=' . urlencode(get_the_title() . ' - Турнир');
                            ?>
                            <img src="<?php echo esc_url($tournament_placeholder); ?>" 
                                 alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                 loading="eager">
                        <?php endif; ?>
                    </div>
                    
                    <div class="tgg-single-tournament__meta">
                        <?php if ($tournament_date) : ?>
                            <div class="tgg-single-tournament__date">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M3 10h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                <span><?php echo esc_html(date_i18n('d F Y', strtotime($tournament_date))); ?></span>
                                <?php if ($tournament_time) : ?>
                                    <span>в <?php echo esc_html($tournament_time); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($tournament_prize) : ?>
                            <div class="tgg-single-tournament__prize">
                                <span>Призовой фонд:</span>
                                <strong><?php echo esc_html($tournament_prize); ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="tgg-single-tournament__title">
                        <?php the_title(); ?>
                    </h1>
                </header>
                
                <div class="tgg-single-tournament__content">
                    <?php the_content(); ?>
                </div>
                
                <?php if ($tournament_rules) : ?>
                    <div class="tgg-single-tournament__rules">
                        <h2>Правила турнира</h2>
                        <div class="tgg-single-tournament__rules-content">
                            <?php echo wp_kses_post($tournament_rules); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($tournament_registration) : ?>
                    <div class="tgg-single-tournament__registration">
                        <a href="<?php echo esc_url($tournament_registration); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="tgg-btn-primary">
                            Зарегистрироваться
                        </a>
                    </div>
                <?php endif; ?>
                
            <?php endwhile; ?>
        </div>
    </article>
</main>

<?php get_footer(); ?>


