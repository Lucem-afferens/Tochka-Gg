<?php
/**
 * Hero Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$hero_title = get_field('hero_title') ?: 'Точка Gg';
$hero_subtitle = get_field('hero_subtitle') ?: 'Премиальный компьютерный клуб нового поколения';
$hero_description = get_field('hero_description') ?: 'Стильное и технологичное игровое пространство, где сочетаются мощное железо, комфорт и высокий стандарт сервиса';
$hero_image = get_field('hero_background_image');
$hero_cta_text = get_field('hero_cta_text') ?: 'Узнать больше';
$hero_cta_link = get_field('hero_cta_link') ?: '#about';
?>

<section class="tgg-hero">
    <?php if ($hero_image) : ?>
        <div class="tgg-hero__bg">
            <img src="<?php echo esc_url($hero_image['url']); ?>" 
                 alt="<?php echo esc_attr($hero_image['alt']); ?>"
                 loading="eager">
        </div>
    <?php else : ?>
        <!-- Заглушка для фона -->
        <div class="tgg-hero__bg tgg-hero__bg-placeholder"></div>
    <?php endif; ?>
    
    <div class="tgg-hero__overlay"></div>
    
    <div class="tgg-container">
        <div class="tgg-hero__content">
            <h1 class="tgg-hero__title">
                <?php echo esc_html($hero_title); ?>
            </h1>
            
            <?php if ($hero_subtitle) : ?>
                <p class="tgg-hero__subtitle">
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
                    <a href="<?php echo esc_url($hero_cta_link); ?>" class="tgg-btn-primary">
                        <?php echo esc_html($hero_cta_text); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="tgg-hero__scroll">
        <span>Прокрутите вниз</span>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </div>
</section>
