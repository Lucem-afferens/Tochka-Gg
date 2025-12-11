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
                $tournament_date_type = get_field('tournament_date_type') ?: 'exact';
                $tournament_date = get_field('tournament_date');
                $tournament_date_month = get_field('tournament_date_month');
                $tournament_date_year = get_field('tournament_date_year');
                $tournament_time = get_field('tournament_time');
                $tournament_prize = get_field('tournament_prize');
                $tournament_rules = get_field('tournament_rules');
                $tournament_registration = get_field('tournament_registration_link');
                
                // Формируем строку даты в зависимости от типа
                $tournament_date_display = '';
                if ($tournament_date_type === 'month_only' && $tournament_date_month && $tournament_date_year) {
                    // Только месяц и год
                    $month_names = [
                        1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
                        5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
                        9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
                    ];
                    $month_name = isset($month_names[intval($tournament_date_month)]) 
                        ? $month_names[intval($tournament_date_month)] 
                        : intval($tournament_date_month);
                    $tournament_date_display = $month_name . ' ' . intval($tournament_date_year);
                } elseif ($tournament_date_type === 'exact' && $tournament_date) {
                    // Точная дата
                    $tournament_date_display = date_i18n('d F Y', strtotime($tournament_date));
                }
            ?>
                <header class="tgg-single-tournament__header">
                    <div class="tgg-single-tournament__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium'); ?>
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
                        <?php if ($tournament_date_display) : ?>
                            <div class="tgg-single-tournament__date">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                    <path d="M3 10h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                <span><?php echo esc_html($tournament_date_display); ?></span>
                                <?php if ($tournament_date_type === 'exact' && $tournament_time) : ?>
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
                           class="tgg-btn-fire" 
                           target="_blank" 
                           rel="noopener noreferrer">
                            Зарегистрироваться на турнир
                        </a>
                    </div>
                <?php endif; ?>
                
                <nav class="tgg-single-tournament__navigation">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ($prev_post) : ?>
                        <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="tgg-single-tournament__nav-link tgg-single-tournament__nav-link--prev">
                            ← <?php echo esc_html(get_the_title($prev_post)); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($next_post) : ?>
                        <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="tgg-single-tournament__nav-link tgg-single-tournament__nav-link--next">
                            <?php echo esc_html(get_the_title($next_post)); ?> →
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endwhile; ?>
        </div>
    </article>
</main>

<?php get_footer(); ?>
