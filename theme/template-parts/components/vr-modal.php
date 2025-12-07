<?php
/**
 * VR Arena Modal Template
 * 
 * Ненавязчивое модальное окно с рекламой VR арены
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем данные VR арены из ACF или используем значения по умолчанию
$vr_title = (function_exists('get_field') ? get_field('vr_title', 'option') : null) ?: 'VR Арена "Другие миры"';
$vr_description = (function_exists('get_field') ? get_field('vr_description', 'option') : null) ?: 'Привыкли управлять героем в игре? Это в прошлом! Теперь вы и есть герой!';
$vr_link = (function_exists('get_field') ? get_field('vr_link', 'option') : null) ?: 'https://vk.com/another_world_kungur';
$vr_phone = (function_exists('get_field') ? get_field('vr_phone', 'option') : null) ?: '+7 912 068-34-17';
$vr_image = function_exists('get_field') ? get_field('vr_image', 'option') : false;

// Получаем путь к странице VR арены
$vr_page_link = get_permalink(get_page_by_path('vr')) ?: home_url('/vr/');
?>

<div class="tgg-vr-modal" id="vr-modal" aria-hidden="true" role="dialog" aria-labelledby="vr-modal-title">
    <div class="tgg-vr-modal__overlay"></div>
    <div class="tgg-vr-modal__content">
        <button class="tgg-vr-modal__close" aria-label="Закрыть окно" type="button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        
        <div class="tgg-vr-modal__body">
            <?php if ($vr_image) : ?>
                <div class="tgg-vr-modal__image">
                    <?php
                    $vr_image_data = tochkagg_get_image_or_placeholder($vr_image, 400, 300, 'VR Arena');
                    ?>
                    <img src="<?php echo esc_url($vr_image_data['url']); ?>" 
                         alt="<?php echo esc_attr($vr_image_data['alt']); ?>"
                         loading="lazy">
                </div>
            <?php endif; ?>
            
            <div class="tgg-vr-modal__text">
                <h3 class="tgg-vr-modal__title" id="vr-modal-title">
                    <?php echo esc_html($vr_title); ?>
                </h3>
                
                <?php if ($vr_description) : ?>
                    <p class="tgg-vr-modal__description">
                        <?php echo esc_html($vr_description); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-vr-modal__actions">
                    <a href="<?php echo esc_url($vr_page_link); ?>" class="tgg-btn-fire tgg-vr-modal__btn">
                        Узнать больше
                    </a>
                    
                    <?php if ($vr_phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $vr_phone)); ?>" class="tgg-vr-modal__phone">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                            <?php echo esc_html($vr_phone); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

