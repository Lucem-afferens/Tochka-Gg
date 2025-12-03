<?php
/**
 * VR Arena Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$vr_title = get_field('vr_title') ?: 'VR Арена "Другие миры"';
$vr_description = get_field('vr_description') ?: 'Привыкли управлять героем в игре? Это в прошлом! Теперь вы и есть герой!';
$vr_area = get_field('vr_area') ?: '840';
$vr_players = get_field('vr_players') ?: '10';
$vr_image = get_field('vr_image');
$vr_link = get_field('vr_link') ?: 'https://vk.com/another_world_kungur';
$vr_phone = get_field('vr_phone') ?: '+7 912 068-34-17';
?>

<section class="tgg-vr" id="vr">
    <div class="tgg-container">
        <div class="tgg-vr__wrapper">
            <div class="tgg-vr__content">
                <h2 class="tgg-vr__title">
                    <?php echo esc_html($vr_title); ?>
                </h2>
                
                <?php if ($vr_description) : ?>
                    <p class="tgg-vr__description">
                        <?php echo esc_html($vr_description); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-vr__features">
                    <div class="tgg-vr__feature">
                        <div class="tgg-vr__feature-value"><?php echo esc_html($vr_area); ?> м²</div>
                        <div class="tgg-vr__feature-label">Площадь виртуальной арены</div>
                    </div>
                    
                    <div class="tgg-vr__feature">
                        <div class="tgg-vr__feature-value">до <?php echo esc_html($vr_players); ?></div>
                        <div class="tgg-vr__feature-label">Игроков одновременно</div>
                    </div>
                </div>
                
                <div class="tgg-vr__services">
                    <ul>
                        <li>Телепорт в виртуальный мир с помощью современного оборудования</li>
                        <li>Уникальные игры в форматах «игрок против игрока» и «игрок против компьютера»</li>
                        <li>Арена VR для командных игр до <?php echo esc_html($vr_players); ?> человек</li>
                        <li>Зона отдыха и банкета на все время праздника</li>
                    </ul>
                </div>
                
                <div class="tgg-vr__contacts">
                    <?php if ($vr_phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $vr_phone)); ?>" class="tgg-btn-primary">
                            Позвонить: <?php echo esc_html($vr_phone); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($vr_link) : ?>
                        <a href="<?php echo esc_url($vr_link); ?>" target="_blank" rel="noopener noreferrer" class="tgg-btn-secondary">
                            ВКонтакте арены
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="tgg-vr__image">
                <?php
                $vr_image_data = tochkagg_get_image_or_placeholder($vr_image, 800, 600, 'VR Arena');
                ?>
                <img src="<?php echo esc_url($vr_image_data['url']); ?>" 
                     alt="<?php echo esc_attr($vr_image_data['alt']); ?>"
                     loading="lazy">
            </div>
        </div>
    </div>
</section>


