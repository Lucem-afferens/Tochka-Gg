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

// Проверяем, включена ли универсальная реклама
// Поддерживаем оба варианта имен полей для обратной совместимости (vr_ad_* и ad_*)
$ad_enabled = function_exists('get_field') 
    ? (get_field('ad_enabled', 'option') !== null ? get_field('ad_enabled', 'option') : get_field('vr_ad_enabled', 'option'))
    : true;

// Если реклама отключена, не показываем модальное окно
if (!$ad_enabled) {
    return;
}

// Получаем задержку показа (в секундах, по умолчанию 30)
// Поддерживаем оба варианта имен полей
$ad_delay = function_exists('get_field') 
    ? (get_field('ad_delay', 'option') !== null ? get_field('ad_delay', 'option') : get_field('vr_ad_delay', 'option'))
    : 30;
$ad_delay = is_numeric($ad_delay) && $ad_delay >= 5 ? intval($ad_delay) : 30;

// Получаем данные рекламы из Options Page (универсальная реклама, не привязана к VR)
// Поддерживаем оба варианта имен полей для обратной совместимости
$ad_title = function_exists('get_field') 
    ? (get_field('ad_title', 'option') ?: get_field('vr_ad_title', 'option'))
    : null;
$ad_title = $ad_title ?: 'VR Арена "Другие миры"';
    
$ad_description = function_exists('get_field') 
    ? (get_field('ad_description', 'option') ?: get_field('vr_ad_description', 'option'))
    : null;
$ad_description = $ad_description ?: 'Привыкли управлять героем в игре? Это в прошлом! Теперь вы и есть герой!';
    
$ad_image = function_exists('get_field') 
    ? (get_field('ad_image', 'option') ?: get_field('vr_ad_image', 'option'))
    : false;

$ad_button_text = function_exists('get_field') 
    ? (get_field('ad_button_text', 'option') ?: get_field('vr_ad_button_text', 'option'))
    : null;
$ad_button_text = $ad_button_text ?: 'Узнать больше';

$ad_phone = function_exists('get_field') 
    ? (get_field('ad_phone', 'option') ?: get_field('vr_ad_phone', 'option'))
    : null;

// Получаем ссылку для кнопки рекламы (универсальная - можно указать любую ссылку)
$ad_link = function_exists('get_field') 
    ? (get_field('ad_link', 'option') ?: get_field('vr_ad_link', 'option'))
    : null;
// Если ссылка не указана, используем ссылку на страницу VR арены
if (!$ad_link) {
    $ad_link = function_exists('tochkagg_get_page_url') 
        ? tochkagg_get_page_url('vr', home_url('/vr/'))
        : (get_permalink(get_page_by_path('vr')) ?: home_url('/vr/'));
}
?>

<div class="tgg-vr-modal" 
     id="vr-modal" 
     aria-hidden="true" 
     role="dialog" 
     aria-labelledby="vr-modal-title"
     data-delay="<?php echo esc_attr($ad_delay * 1000); ?>">
    <div class="tgg-vr-modal__overlay"></div>
    <div class="tgg-vr-modal__content">
        <button class="tgg-vr-modal__close" aria-label="Закрыть окно" type="button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        
        <div class="tgg-vr-modal__body">
            <?php if ($ad_image) : ?>
                <div class="tgg-vr-modal__image">
                    <?php
                    $ad_image_data = tochkagg_get_image_or_placeholder($ad_image, 400, 300, 'Advertisement');
                    ?>
                    <img src="<?php echo esc_url($ad_image_data['url']); ?>" 
                         alt="<?php echo esc_attr($ad_image_data['alt']); ?>"
                         loading="lazy">
                </div>
            <?php endif; ?>
            
            <!-- Модальное окно показывается даже без изображения -->
            
            <div class="tgg-vr-modal__text">
                <h3 class="tgg-vr-modal__title" id="vr-modal-title">
                    <?php echo esc_html($ad_title); ?>
                </h3>
                
                <?php if ($ad_description) : ?>
                    <p class="tgg-vr-modal__description">
                        <?php echo esc_html($ad_description); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-vr-modal__actions">
                    <a href="<?php echo esc_url($ad_link); ?>" class="tgg-btn-fire tgg-vr-modal__btn">
                        <?php echo esc_html($ad_button_text); ?>
                    </a>
                    
                    <?php if ($ad_phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $ad_phone)); ?>" class="tgg-vr-modal__phone">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                            <?php echo esc_html($ad_phone); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

