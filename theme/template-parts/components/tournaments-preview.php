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
$tournaments_count = get_field('tournaments_preview_count') ?: 6;
$tournaments_bg_type = function_exists('get_field') ? get_field('tournaments_preview_bg_type') : 'image'; // 'image' или 'video'
$tournaments_bg_image = function_exists('get_field') ? get_field('tournaments_preview_bg_image') : false;
$tournaments_bg_video = function_exists('get_field') ? get_field('tournaments_preview_bg_video') : false; // URL видео

// Получаем URL архива турниров через WordPress функции
$tournaments_url_default = get_post_type_archive_link('tournament') ?: home_url('/tournament/');
$tournaments_link = get_field('tournaments_preview_link') ?: $tournaments_url_default;

// Получаем изображение или placeholder для фона
$tournaments_bg_image_data = function_exists('tochkagg_get_image_or_placeholder') 
    ? tochkagg_get_image_or_placeholder($tournaments_bg_image, 1920, 1080, 'Tournaments Background')
    : [
        'url' => 'https://placehold.co/1920x1080/1a1d29/3b82f6?text=Tournaments+Background',
        'alt' => 'Tournaments Background (заглушка - загрузите своё изображение)'
    ];
// Получаем видео URL или placeholder
$tournaments_bg_video_url = '';
if ($tournaments_bg_type === 'video' && $tournaments_bg_video) {
    // Обрабатываем видео - может быть массивом (File field) или строкой (URL)
    if (is_array($tournaments_bg_video)) {
        // Если это массив (File field), берем URL
        $tournaments_bg_video_url = isset($tournaments_bg_video['url']) ? $tournaments_bg_video['url'] : '';
    } elseif (is_string($tournaments_bg_video)) {
        // Если это строка, проверяем, что это валидный URL или относительный путь
        $tournaments_bg_video = trim($tournaments_bg_video);
        if (!empty($tournaments_bg_video)) {
            // Проверяем, является ли это абсолютным URL
            if (filter_var($tournaments_bg_video, FILTER_VALIDATE_URL)) {
                $tournaments_bg_video_url = $tournaments_bg_video;
            } elseif (strpos($tournaments_bg_video, '/') === 0 || strpos($tournaments_bg_video, './') === 0) {
                // Относительный путь от корня сайта
                $tournaments_bg_video_url = $tournaments_bg_video;
            } elseif (strpos($tournaments_bg_video, 'http') === 0 || strpos($tournaments_bg_video, '//') === 0) {
                // URL без протокола или с протоколом
                $tournaments_bg_video_url = $tournaments_bg_video;
            } else {
                // Пробуем как относительный путь
                $tournaments_bg_video_url = '/' . ltrim($tournaments_bg_video, '/');
            }
        }
    }
    
    // Если URL не получен, переключаемся на изображение
    if (empty($tournaments_bg_video_url)) {
        $tournaments_bg_type = 'image';
    }
}

// Получаем закрепленные турниры
$pinned_tournaments_query = new WP_Query([
    'post_type' => 'tournament',
    'posts_per_page' => -1, // Получаем все закрепленные
    'post_status' => 'publish',
    'meta_query' => [
        'relation' => 'AND',
        [
            'key' => 'tournament_pinned',
            'value' => '1',
            'compare' => '='
        ],
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

// Получаем обычные турниры (не закрепленные)
$regular_tournaments_count = $tournaments_count - $pinned_tournaments_query->found_posts;
if ($regular_tournaments_count < 0) {
    $regular_tournaments_count = 0;
}

$regular_tournaments_query = new WP_Query([
    'post_type' => 'tournament',
    'posts_per_page' => $regular_tournaments_count,
    'post_status' => 'publish',
    'meta_query' => [
        'relation' => 'AND',
        [
            'relation' => 'OR',
            [
                'key' => 'tournament_pinned',
                'compare' => 'NOT EXISTS'
            ],
            [
                'key' => 'tournament_pinned',
                'value' => '1',
                'compare' => '!='
            ]
        ],
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

// Объединяем результаты: сначала закрепленные, затем обычные
$all_tournaments_posts = [];
if ($pinned_tournaments_query->have_posts()) {
    while ($pinned_tournaments_query->have_posts()) {
        $pinned_tournaments_query->the_post();
        $all_tournaments_posts[] = get_post();
    }
    wp_reset_postdata();
}
if ($regular_tournaments_query->have_posts()) {
    while ($regular_tournaments_query->have_posts()) {
        $regular_tournaments_query->the_post();
        $all_tournaments_posts[] = get_post();
    }
    wp_reset_postdata();
}

// Ограничиваем общее количество турниров
$all_tournaments_posts = array_slice($all_tournaments_posts, 0, $tournaments_count);
?>

<section class="tgg-tournaments-preview">
    <div class="tgg-tournaments-preview__bg">
        <?php if ($tournaments_bg_type === 'video' && $tournaments_bg_video_url) : ?>
            <!-- Фоновое видео -->
            <video class="tgg-tournaments-preview__bg-video" 
                   autoplay 
                   muted 
                   loop 
                   playsinline 
                   aria-hidden="true"
                   poster="<?php echo esc_url($tournaments_bg_image_data['url']); ?>">
                <source src="<?php echo esc_url($tournaments_bg_video_url); ?>" type="video/mp4">
                <!-- Fallback на изображение, если видео не поддерживается -->
                <img src="<?php echo esc_url($tournaments_bg_image_data['url']); ?>" 
                     alt="<?php echo esc_attr($tournaments_bg_image_data['alt']); ?>"
                     loading="lazy">
            </video>
        <?php else : ?>
            <!-- Фоновое изображение (или placeholder) -->
            <img src="<?php echo esc_url($tournaments_bg_image_data['url']); ?>" 
                 alt="<?php echo esc_attr($tournaments_bg_image_data['alt']); ?>"
                 loading="lazy">
        <?php endif; ?>
    </div>
    
    <div class="tgg-tournaments-preview__overlay"></div>
    
    <div class="tgg-container">
        <?php if ($tournaments_title) : ?>
            <h2 class="tgg-tournaments-preview__title">
                <?php echo esc_html($tournaments_title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if (!empty($all_tournaments_posts)) : ?>
            <div class="tgg-tournaments-preview__carousel swiper tgg-slider-tournaments">
                <div class="swiper-wrapper">
                    <?php foreach ($all_tournaments_posts as $post) : 
                        setup_postdata($post);
                        $tournament_date = get_field('tournament_date');
                        $tournament_time = get_field('tournament_time');
                        $tournament_prize = get_field('tournament_prize');
                    ?>
                        <div class="swiper-slide">
                            <div class="tgg-tournaments-preview__card">
                                <a href="<?php the_permalink(); ?>" class="tgg-tournaments-preview__card-link">
                                    <div class="tgg-tournaments-preview__card-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium'); ?>
                                        <?php else : ?>
                                            <?php 
                                            $tournament_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                                ? tochkagg_get_placeholder_image(400, 300, get_the_title() . ' - Турнир', '1a1d29', 'c026d3')
                                                : 'https://placehold.co/400x300/1a1d29/c026d3?text=' . urlencode(get_the_title() . ' - Турнир');
                                            ?>
                                            <img src="<?php echo esc_url($tournament_placeholder); ?>" 
                                                 alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                                 loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="tgg-tournaments-preview__card-content">
                                        <h3 class="tgg-tournaments-preview__card-title" data-title="<?php echo esc_attr(get_the_title()); ?>">
                                            <?php the_title(); ?>
                                        </h3>
                                        
                                        <?php if ($tournament_prize) : ?>
                                            <div class="tgg-tournaments-preview__card-prize">
                                                <span class="tgg-tournaments-preview__card-prize-label">Призовой фонд</span>
                                                <span class="tgg-tournaments-preview__card-prize-value"><?php echo esc_html($tournament_prize); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($tournament_date) : ?>
                                            <div class="tgg-tournaments-preview__card-date">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                                    <path d="M3 10h18M8 2v4M16 2v4"/>
                                                </svg>
                                                <span><?php echo esc_html(date_i18n('d.m.Y', strtotime($tournament_date))); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Навигация карусели -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                
                <!-- Пагинация -->
                <div class="swiper-pagination"></div>
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


