<?php
/**
 * Hero Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$hero_title = (function_exists('get_field') ? get_field('hero_title') : null) ?: 'Good Games';
// Убираем subtitle
$hero_subtitle = null;
$hero_description = (function_exists('get_field') ? get_field('hero_description') : null) ?: 'Стильное и технологичное игровое пространство, где сочетаются мощное железо, комфорт и высокий стандарт сервиса';
$hero_bg_type = function_exists('get_field') ? get_field('hero_background_type') : 'image'; // 'image' или 'video'
$hero_image = function_exists('get_field') ? get_field('hero_background_image') : false;
$hero_video = function_exists('get_field') ? get_field('hero_background_video') : false; // URL видео
$hero_cta_text = (function_exists('get_field') ? get_field('hero_cta_text') : null) ?: 'Узнать больше';
$hero_cta_link = (function_exists('get_field') ? get_field('hero_cta_link') : null) ?: '#about';

// Получаем изображение или placeholder (всегда должен быть результат)
$hero_image_data = function_exists('tochkagg_get_image_or_placeholder') 
    ? tochkagg_get_image_or_placeholder($hero_image, 1920, 1080, 'Hero Background')
    : [
        'url' => 'https://placehold.co/1920x1080/1a1d29/3b82f6?text=Hero+Background',
        'alt' => 'Hero Background (заглушка - загрузите своё изображение)'
    ];

// Получаем видео URL или placeholder
$hero_video_url = '';
if ($hero_bg_type === 'video') {
    if ($hero_video && filter_var($hero_video, FILTER_VALIDATE_URL)) {
        $hero_video_url = $hero_video;
    } else {
        // Если видео не указано, но тип выбран "video", используем placeholder изображение
        // (так как реального placeholder видео нет, показываем изображение)
        $hero_bg_type = 'image';
    }
}
?>

<section class="tgg-hero">
    <div class="tgg-hero__bg">
        <?php if ($hero_bg_type === 'video' && $hero_video_url) : ?>
            <!-- Фоновое видео -->
            <video class="tgg-hero__bg-video" 
                   autoplay 
                   muted 
                   loop 
                   playsinline 
                   aria-hidden="true"
                   poster="<?php echo esc_url($hero_image_data['url']); ?>">
                <source src="<?php echo esc_url($hero_video_url); ?>" type="video/mp4">
                <!-- Fallback на изображение, если видео не поддерживается -->
                <img src="<?php echo esc_url($hero_image_data['url']); ?>" 
                     alt="<?php echo esc_attr($hero_image_data['alt']); ?>"
                     loading="eager">
            </video>
        <?php else : ?>
            <!-- Фоновое изображение (или placeholder) -->
            <img src="<?php echo esc_url($hero_image_data['url']); ?>" 
                 alt="<?php echo esc_attr($hero_image_data['alt']); ?>"
                 loading="eager">
        <?php endif; ?>
    </div>
    
    <div class="tgg-hero__overlay"></div>
    
    <div class="tgg-container">
        <div class="tgg-hero__content">
            <h1 class="tgg-hero__title tgg-hero__title--cyber">
                <?php 
                // Разбиваем текст на буквы для анимации мигания
                $title_text = esc_html($hero_title);
                $letters = mb_str_split($title_text, 1, 'UTF-8');
                // Индексы букв, которые будут мигать чаще (для эффекта электричества)
                $flicker_letters = [0, 1, 4, 5, 9, 10]; // G, o, d, G, a, m
                foreach ($letters as $index => $letter) {
                    // Случайная задержка для более реалистичного эффекта
                    $base_delay = $index * 0.08;
                    $random_offset = (($index * 7) % 5) * 0.05; // Псевдослучайная задержка
                    $delay = $base_delay + $random_offset;
                    // Буквы из списка мигают чаще
                    $duration = in_array($index, $flicker_letters) ? '1.8s' : '2.5s';
                    $letter_class = in_array($index, $flicker_letters) ? ' tgg-hero__title-letter--flicker' : '';
                    echo '<span class="tgg-hero__title-letter' . $letter_class . '" style="animation-delay: ' . $delay . 's; animation-duration: ' . $duration . ';">' . ($letter === ' ' ? '&nbsp;' : $letter) . '</span>';
                }
                ?>
            </h1>
            
            <?php if ($hero_subtitle) : ?>
                <p class="tgg-hero__subtitle tgg-fire-text">
                    <?php echo esc_html($hero_subtitle); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($hero_description) : ?>
                <p class="tgg-hero__description">
                    <?php echo esc_html($hero_description); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($hero_cta_text && $hero_cta_link) : ?>
                <div class="tgg-hero__cta">
                    <a href="<?php echo esc_url($hero_cta_link); ?>" class="tgg-btn-fire">
                        <?php echo esc_html($hero_cta_text); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
