<?php
/**
 * About Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$about_title = get_field('about_title') ?: 'Компьютерный клуб в Кунгуре Точка Gg';
$about_text = get_field('about_text') ?: 'Точка Gg - это больше, чем просто компьютерный клуб. Стильное и технологичное игровое пространство нового уровня, где сочетаются мощное железо, комфорт и высокий стандарт сервиса. Нас выбирают геймеры, стримеры и все, кто хочет отдохнуть с комфортом и поиграть на топовом оборудовании без лагов и очередей.';
$about_image = get_field('about_image');
?>

<section class="tgg-about" id="about">
    <div class="tgg-container">
        <div class="tgg-about__wrapper">
            <div class="tgg-about__text">
                <h2 class="tgg-about__title">
                    <?php echo esc_html($about_title); ?>
                </h2>
                
                <div class="tgg-about__content">
                    <?php echo wp_kses_post($about_text); ?>
                </div>
            </div>
            
            <div class="tgg-about__image">
                <?php
                $about_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                    ? tochkagg_get_image_or_placeholder($about_image, 800, 600, 'About Club')
                    : [
                        'url' => 'https://placehold.co/800x600/1a1d29/3b82f6?text=About+Club',
                        'alt' => 'About Club (заглушка - загрузите своё изображение)'
                    ];
                ?>
                <img src="<?php echo esc_url($about_image_data['url']); ?>" 
                     alt="<?php echo esc_attr($about_image_data['alt']); ?>"
                     width="<?php echo esc_attr($about_image_data['width'] ?? 800); ?>"
                     height="<?php echo esc_attr($about_image_data['height'] ?? 600); ?>"
                     loading="lazy"
                     decoding="async">
            </div>
        </div>
    </div>
</section>


