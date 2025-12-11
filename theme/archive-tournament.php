<?php
/**
 * Archive Template: Tournaments
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
                <h1 class="tgg-archive__title">Турниры</h1>
                <?php if (get_the_archive_description()) : ?>
                    <div class="tgg-archive__description">
                        <?php the_archive_description(); ?>
                    </div>
                <?php endif; ?>
            </header>
            
            <?php if (have_posts()) : ?>
                <div class="tgg-archive__items">
                    <?php while (have_posts()) : the_post(); 
                        $tournament_date_type = get_field('tournament_date_type') ?: 'exact';
                        $tournament_date = get_field('tournament_date');
                        $tournament_date_month = get_field('tournament_date_month');
                        $tournament_date_year = get_field('tournament_date_year');
                        $tournament_time = get_field('tournament_time');
                        $tournament_prize = get_field('tournament_prize');
                        
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
                        <article class="tgg-archive__item">
                            <div class="tgg-archive__item-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium'); ?>
                                    <?php else : ?>
                                        <?php 
                                        $tournament_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                            ? tochkagg_get_placeholder_image(600, 400, get_the_title() . ' - Турнир', '1a1d29', 'c026d3')
                                            : 'https://placehold.co/600x400/1a1d29/c026d3?text=' . urlencode(get_the_title() . ' - Турнир');
                                        ?>
                                        <img src="<?php echo esc_url($tournament_placeholder); ?>" 
                                             alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                             loading="lazy">
                                    <?php endif; ?>
                                </a>
                            </div>
                            
                            <div class="tgg-archive__item-content">
                                <h2 class="tgg-archive__item-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <?php if ($tournament_date_display) : ?>
                                    <div class="tgg-archive__item-date">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                                            <path d="M3 10h18M8 2v4M16 2v4" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        <?php echo esc_html($tournament_date_display); ?>
                                        <?php if ($tournament_date_type === 'exact' && $tournament_time) : ?>
                                            <span>в <?php echo esc_html($tournament_time); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($tournament_prize) : ?>
                                    <div class="tgg-archive__item-prize">
                                        <span>Призовой фонд:</span>
                                        <strong><?php echo esc_html($tournament_prize); ?></strong>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (get_the_excerpt()) : ?>
                                    <div class="tgg-archive__item-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="tgg-archive__item-link">
                                    Подробнее →
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
                    <p>Турниров пока нет. Следите за новостями!</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
