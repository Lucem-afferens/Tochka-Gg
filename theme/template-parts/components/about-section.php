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
                <?php if ($about_image) : ?>
                    <img src="<?php echo esc_url($about_image['url']); ?>" 
                         alt="<?php echo esc_attr($about_image['alt'] ?: 'Компьютерный клуб Точка Gg'); ?>"
                         loading="lazy">
                <?php else : ?>
                    <!-- Заглушка для изображения -->
                    <div class="tgg-about__image-placeholder">
                        <span>Здесь будет фото клуба</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


