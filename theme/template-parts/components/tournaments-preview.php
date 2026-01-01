<?php
/**
 * Tournaments Preview Component
 * 
 * Отображает карусель ближайших турниров на главной странице
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем настройки секции турниров из SCF
// Проверяем оба варианта названия поля для обратной совместимости
$tournaments_enabled = function_exists('get_field') 
    ? (get_field('tournaments_preview_enabled') !== false ? get_field('tournaments_preview_enabled') : get_field('tournaments_enabled'))
    : true;
$tournaments_title = (function_exists('get_field') 
    ? (get_field('tournaments_preview_title') ?: get_field('tournaments_title'))
    : null) ?: 'Ближайшие турниры';
$tournaments_description = function_exists('get_field') 
    ? (get_field('tournaments_preview_description') ?: get_field('tournaments_description'))
    : null;
$tournaments_count = function_exists('get_field') 
    ? (get_field('tournaments_preview_count') ?: get_field('tournaments_count'))
    : 3;
// Ограничиваем максимум 3 турнира (1, 2 или 3)
$tournaments_count = is_numeric($tournaments_count) && $tournaments_count > 0 ? min(intval($tournaments_count), 3) : 3;

// Получаем ссылку на все турниры
$tournaments_link = function_exists('get_field') ? get_field('tournaments_preview_link') : null;
// Если ссылка не указана, используем автоматическое определение URL архива
if (empty($tournaments_link)) {
    $tournaments_link = get_post_type_archive_link('tournament') ?: home_url('/tournament/');
}

// Получаем текст кнопки (если есть отдельное поле, иначе используем дефолт)
$tournaments_button_text = function_exists('get_field') 
    ? (get_field('tournaments_preview_button_text') ?: 'Все турниры')
    : 'Все турниры';

// Если секция отключена явно, не показываем её
if ($tournaments_enabled === false || $tournaments_enabled === '0' || $tournaments_enabled === 0) {
    return;
}

// Получаем тип фона (изображение или видео)
$tournaments_bg_type = function_exists('get_field') ? get_field('tournaments_preview_bg_type') : 'image';
$tournaments_bg_image = function_exists('get_field') ? get_field('tournaments_preview_bg_image') : false;
$tournaments_bg_video = function_exists('get_field') ? get_field('tournaments_preview_bg_video') : false;
$tournaments_bg_image_mobile = function_exists('get_field') ? get_field('tournaments_preview_bg_image_mobile') : false;

// Обработка видео фона
$tournaments_bg_video_url = '';
if ($tournaments_bg_type === 'video' && $tournaments_bg_video) {
    // Если это массив (File field), получаем URL
    if (is_array($tournaments_bg_video) && !empty($tournaments_bg_video['url'])) {
        $tournaments_bg_video_url = $tournaments_bg_video['url'];
    } 
    // Если это строка (URL field или прямая ссылка)
    elseif (is_string($tournaments_bg_video)) {
        // Проверяем, является ли это абсолютным URL
        if (filter_var($tournaments_bg_video, FILTER_VALIDATE_URL)) {
            $tournaments_bg_video_url = $tournaments_bg_video;
        } 
        // Если это относительный путь, делаем его абсолютным
        elseif (strpos($tournaments_bg_video, '/') === 0 || strpos($tournaments_bg_video, './') === 0) {
            $tournaments_bg_video_url = home_url($tournaments_bg_video);
        } 
        // Если это просто путь без слеша, добавляем его
        else {
            $tournaments_bg_video_url = '/' . ltrim($tournaments_bg_video, '/');
        }
    }
    
    // Если URL не получен, переключаемся на изображение
    if (empty($tournaments_bg_video_url)) {
        $tournaments_bg_type = 'image';
    }
}

// Получаем закрепленные турниры
// Для закрепленных турниров не фильтруем по дате - они всегда показываются
$pinned_tournaments_query = new WP_Query([
    'post_type' => 'tournament',
    'posts_per_page' => -1, // Получаем все закрепленные
    'post_status' => 'publish',
    'meta_query' => [
        [
            'key' => 'tournament_pinned',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC'
]);

// Получаем обычные турниры (не закрепленные)
$regular_tournaments_count = $tournaments_count - $pinned_tournaments_query->found_posts;
if ($regular_tournaments_count < 0) {
    $regular_tournaments_count = 0;
}

// Получаем обычные турниры (не закрепленные)
// Фильтруем по дате: либо точная дата >= сегодня, либо год >= текущего года
$current_date = date('Y-m-d');
$current_year = intval(date('Y'));

// Используем более простую логику: получаем все не закрепленные турниры,
// а фильтрацию по дате делаем в PHP для более гибкой логики
$regular_tournaments_query = new WP_Query([
    'post_type' => 'tournament',
    'posts_per_page' => -1, // Получаем все для фильтрации
    'post_status' => 'publish',
    'meta_query' => [
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
        ]
    ],
    'orderby' => 'date',
    'order' => 'ASC'
]);

// Фильтруем результаты в PHP
$filtered_tournaments = [];
if ($regular_tournaments_query->have_posts()) {
    while ($regular_tournaments_query->have_posts()) {
        $regular_tournaments_query->the_post();
        $post = get_post();
        
        $date_type = get_field('tournament_date_type', $post->ID) ?: 'exact';
        $should_include = false;
        
        if ($date_type === 'exact') {
            // Точная дата: проверяем, что дата >= сегодня
            $tournament_date = get_field('tournament_date', $post->ID);
            if ($tournament_date) {
                $tournament_timestamp = strtotime($tournament_date);
                if ($tournament_timestamp !== false && $tournament_timestamp >= strtotime($current_date)) {
                    $should_include = true;
                }
            }
        } elseif ($date_type === 'month_only') {
            // Только месяц/год: проверяем, что год >= текущего года
            $tournament_year = get_field('tournament_date_year', $post->ID);
            if ($tournament_year) {
                $year_int = intval($tournament_year);
                if ($year_int >= $current_year) {
                    $should_include = true;
                }
            }
        } else {
            // Старые записи без типа даты: используем точную дату, если она есть
            // Если даты нет вообще, тоже включаем (для обратной совместимости)
            $tournament_date = get_field('tournament_date', $post->ID);
            if ($tournament_date) {
                $tournament_timestamp = strtotime($tournament_date);
                if ($tournament_timestamp !== false && $tournament_timestamp >= strtotime($current_date)) {
                    $should_include = true;
                }
            } else {
                // Если даты нет, включаем турнир (для обратной совместимости со старыми записями)
                $should_include = true;
            }
        }
        
        if ($should_include) {
            $filtered_tournaments[] = $post;
        }
    }
    wp_reset_postdata();
}

// Ограничиваем количество результатов
$filtered_tournaments = array_slice($filtered_tournaments, 0, $regular_tournaments_count);

// Объединяем результаты: сначала закрепленные, затем обычные
$all_tournaments_posts = [];
if ($pinned_tournaments_query->have_posts()) {
    while ($pinned_tournaments_query->have_posts()) {
        $pinned_tournaments_query->the_post();
        $all_tournaments_posts[] = get_post();
    }
    wp_reset_postdata();
}
// Добавляем отфильтрованные обычные турниры
$all_tournaments_posts = array_merge($all_tournaments_posts, $filtered_tournaments);

// Если нет турниров, все равно показываем секцию (но без карточек)
// Это позволит видеть секцию даже если турниры еще не добавлены или все в прошлом

// Подготовка данных для фонового изображения
$tournaments_bg_image_data = function_exists('tochkagg_get_image_or_placeholder')
    ? tochkagg_get_image_or_placeholder($tournaments_bg_image, 1920, 1080, 'Tournaments Background')
    : [
        'url' => 'https://placehold.co/1920x1080/1a1d29/3b82f6?text=Tournaments+Background',
        'alt' => 'Tournaments Background (заглушка - загрузите своё изображение)',
        'width' => 1920,
        'height' => 1080
    ];

$tournaments_bg_image_mobile_data = false;
if ($tournaments_bg_image_mobile) {
    $tournaments_bg_image_mobile_data = function_exists('tochkagg_get_image_or_placeholder')
        ? tochkagg_get_image_or_placeholder($tournaments_bg_image_mobile, 768, 1024, 'Tournaments Background Mobile')
        : [
            'url' => 'https://placehold.co/768x1024/1a1d29/3b82f6?text=Tournaments+Background+Mobile',
            'alt' => 'Tournaments Background Mobile (заглушка - загрузите своё изображение)',
            'width' => 768,
            'height' => 1024
        ];
}
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
            <!-- Фоновое изображение (или placeholder) с адаптивностью для мобильных -->
            <?php if ($tournaments_bg_image_mobile_data) : ?>
                <picture>
                    <!-- Мобильное изображение для экранов до 768px -->
                    <source media="(max-width: 767px)"
                            srcset="<?php echo esc_url($tournaments_bg_image_mobile_data['url']); ?>"
                            width="<?php echo esc_attr($tournaments_bg_image_mobile_data['width'] ?? 768); ?>"
                            height="<?php echo esc_attr($tournaments_bg_image_mobile_data['height'] ?? 1024); ?>">
                    <!-- Десктопное изображение для экранов от 768px -->
                    <img src="<?php echo esc_url($tournaments_bg_image_data['url']); ?>"
                         alt="<?php echo esc_attr($tournaments_bg_image_data['alt']); ?>"
                         width="<?php echo esc_attr($tournaments_bg_image_data['width'] ?? 1920); ?>"
                         height="<?php echo esc_attr($tournaments_bg_image_data['height'] ?? 1080); ?>"
                         loading="lazy"
                         decoding="async">
                </picture>
            <?php else : ?>
                <!-- Если мобильное изображение не указано, используем основное -->
                <img src="<?php echo esc_url($tournaments_bg_image_data['url']); ?>"
                     alt="<?php echo esc_attr($tournaments_bg_image_data['alt']); ?>"
                     width="<?php echo esc_attr($tournaments_bg_image_data['width'] ?? 1920); ?>"
                     height="<?php echo esc_attr($tournaments_bg_image_data['height'] ?? 1080); ?>"
                     loading="lazy"
                     decoding="async">
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="tgg-tournaments-preview__overlay"></div>
    
    <div class="tgg-container">
        <?php if ($tournaments_title) : ?>
            <h2 class="tgg-tournaments-preview__title">
                <?php echo esc_html($tournaments_title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if ($tournaments_description) : ?>
            <div class="tgg-tournaments-preview__description">
                <?php echo wp_kses_post($tournaments_description); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($all_tournaments_posts)) : ?>
            <div class="tgg-tournaments-preview__carousel swiper tgg-slider-tournaments" data-count="<?php echo esc_attr(count($all_tournaments_posts)); ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($all_tournaments_posts as $post) : 
                        setup_postdata($post);
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
                            $tournament_date_display = date_i18n('d.m.Y', strtotime($tournament_date));
                        }
                    ?>
                        <div class="swiper-slide">
                            <div class="tgg-tournaments-preview__card">
                                <a href="<?php the_permalink(); ?>" class="tgg-tournaments-preview__card-link">
                                    <div class="tgg-tournaments-preview__card-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php 
                                            // Используем wp_get_attachment_image для контроля над атрибутами
                                            $thumbnail_id = get_post_thumbnail_id();
                                            echo wp_get_attachment_image(
                                                $thumbnail_id,
                                                'thumbnail',
                                                false,
                                                array(
                                                    'loading' => 'lazy',
                                                    'decoding' => 'async',
                                                    'alt' => get_the_title()
                                                )
                                            );
                                            ?>
                                        <?php else : ?>
                                            <?php 
                                            $tournament_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                                ? tochkagg_get_placeholder_image(400, 300, get_the_title() . ' - Турнир', '1a1d29', 'c026d3')
                                                : 'https://placehold.co/400x300/1a1d29/c026d3?text=' . urlencode(get_the_title() . ' - Турнир');
                                            ?>
                                            <img src="<?php echo esc_url($tournament_placeholder); ?>" 
                                                 alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                                 width="400"
                                                 height="300"
                                                 loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="tgg-tournaments-preview__card-content">
                                        <h3 class="tgg-tournaments-preview__card-title">
                                            <span class="tgg-tournaments-preview__card-title-text"><?php the_title(); ?></span>
                                        </h3>
                                        
                                        <?php if ($tournament_prize) : ?>
                                            <div class="tgg-tournaments-preview__card-prize">
                                                <span class="tgg-tournaments-preview__card-prize-label">Призовой фонд</span>
                                                <span class="tgg-tournaments-preview__card-prize-value"><?php echo esc_html($tournament_prize); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($tournament_date_display) : ?>
                                            <div class="tgg-tournaments-preview__card-date">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                                    <path d="M3 10h18M8 2v4M16 2v4"/>
                                                </svg>
                                                <span><?php echo esc_html($tournament_date_display); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Навигация Swiper (скрыта на десктопе) -->
                <div class="swiper-button-next tgg-slider-tournaments-next"></div>
                <div class="swiper-button-prev tgg-slider-tournaments-prev"></div>
                
                <!-- Пагинация Swiper (скрыта на десктопе) -->
                <div class="swiper-pagination tgg-slider-tournaments-pagination"></div>
            </div>
            
            <?php if ($tournaments_link) : ?>
                <div class="tgg-tournaments-preview__cta">
                    <a href="<?php echo esc_url($tournaments_link); ?>" class="tgg-btn-fire">
                        <?php echo esc_html($tournaments_button_text); ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="tgg-tournaments-preview__empty">
                <p>Ближайших турниров пока нет. Следите за новостями!</p>
            </div>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
</section>
