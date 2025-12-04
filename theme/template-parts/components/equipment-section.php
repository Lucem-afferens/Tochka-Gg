<?php
/**
 * Equipment Section Template (Preview)
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$equipment_title = get_field('equipment_preview_title') ?: 'Топовое оборудование';
$equipment_text = get_field('equipment_preview_text') ?: 'Играй на мощном железе без лагов и очередей';

// Получаем URL страницы оборудования через WordPress функции
$equipment_link_default = function_exists('tochkagg_get_page_url') 
    ? tochkagg_get_page_url('equipment') 
    : home_url('/equipment/');
$equipment_link = get_field('equipment_preview_link') ?: $equipment_link_default;
?>

<section class="tgg-equipment-preview">
    <div class="tgg-container">
        <div class="tgg-equipment-preview__wrapper">
            <div class="tgg-equipment-preview__content">
                <?php if ($equipment_title) : ?>
                    <h2 class="tgg-equipment-preview__title">
                        <?php echo esc_html($equipment_title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($equipment_text) : ?>
                    <p class="tgg-equipment-preview__text">
                        <?php echo esc_html($equipment_text); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-equipment-preview__specs">
                    <div class="tgg-equipment-preview__spec">
                        <div class="tgg-equipment-preview__spec-label">VIP ПК</div>
                        <div class="tgg-equipment-preview__spec-value">6 шт.</div>
                        <div class="tgg-equipment-preview__spec-desc">RTX 5060 Ti, i5-13400F</div>
                    </div>
                    
                    <div class="tgg-equipment-preview__spec">
                        <div class="tgg-equipment-preview__spec-label">LITE ПК</div>
                        <div class="tgg-equipment-preview__spec-value">6 шт.</div>
                        <div class="tgg-equipment-preview__spec-desc">RTX 4060, i5-12400F</div>
                    </div>
                    
                    <div class="tgg-equipment-preview__spec">
                        <div class="tgg-equipment-preview__spec-label">PS-зона</div>
                        <div class="tgg-equipment-preview__spec-value">2 PS5</div>
                        <div class="tgg-equipment-preview__spec-desc">4 джойстика, руль</div>
                    </div>
                </div>
                
                <?php if ($equipment_link) : ?>
                    <div class="tgg-equipment-preview__cta">
                        <a href="<?php echo esc_url($equipment_link); ?>" class="tgg-btn-fire">
                            Подробнее об оборудовании
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tgg-equipment-preview__image">
                <?php
                $equipment_image = get_field('equipment_preview_image');
                $equipment_image_data = tochkagg_get_image_or_placeholder($equipment_image, 800, 600, 'Equipment');
                ?>
                <img src="<?php echo esc_url($equipment_image_data['url']); ?>" 
                     alt="<?php echo esc_attr($equipment_image_data['alt']); ?>"
                     loading="lazy">
            </div>
        </div>
    </div>
</section>


