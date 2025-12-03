<?php
/**
 * Services Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$services_title = get_field('services_title') ?: 'Выбери свой источник удовольствия!';

// Получаем URL страниц через WordPress функции
$equipment_url = function_exists('tochkagg_get_page_url') 
    ? tochkagg_get_page_url('equipment') 
    : home_url('/equipment/');
$vr_url = function_exists('tochkagg_get_page_url') 
    ? tochkagg_get_page_url('vr') 
    : home_url('/vr/');
$bar_url = function_exists('tochkagg_get_page_url') 
    ? tochkagg_get_page_url('bar') 
    : home_url('/bar/');

$services = get_field('services') ?: [
    [
        'title' => 'Игровые ПК',
        'subtitle' => 'VIP и LITE',
        'description' => '12 мощных игровых компьютеров с RTX видеокартами',
        'link' => $equipment_url,
        'image' => null,
        'type' => 'pc'
    ],
    [
        'title' => 'PlayStation 5',
        'subtitle' => '4 джойстика',
        'description' => 'Более 50 игр и подписка PS Plus',
        'link' => $equipment_url . '#ps5',
        'image' => null,
        'type' => 'ps5'
    ],
    [
        'title' => 'VR Арена',
        'subtitle' => 'Другие миры',
        'description' => '840 м² виртуальной реальности, до 10 игроков',
        'link' => $vr_url,
        'image' => null,
        'type' => 'vr'
    ],
    [
        'title' => 'Еда и напитки',
        'subtitle' => 'Кафе в клубе',
        'description' => 'Кофе, бургеры, энергетики и прохладительные напитки',
        'link' => $bar_url,
        'image' => null,
        'type' => 'food'
    ]
];
?>

<section class="tgg-services" id="services">
    <div class="tgg-container">
        <?php if ($services_title) : ?>
            <h2 class="tgg-services__title">
                <?php echo esc_html($services_title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if ($services && is_array($services)) : ?>
            <div class="tgg-services__items">
                <?php foreach ($services as $service) : 
                    $title = $service['title'] ?? '';
                    $subtitle = $service['subtitle'] ?? '';
                    $description = $service['description'] ?? '';
                    $link = $service['link'] ?? '#';
                    $image = $service['image'] ?? null;
                    $type = $service['type'] ?? '';
                ?>
                    <div class="tgg-services__item tgg-services__item--<?php echo esc_attr($type); ?>" data-type="<?php echo esc_attr($type); ?>">
                        <?php if ($link) : ?>
                            <a href="<?php echo esc_url($link); ?>" class="tgg-services__item-link">
                        <?php endif; ?>
                        
                        <div class="tgg-services__item-image">
                            <?php if ($image) : ?>
                                <img src="<?php echo esc_url($image['url']); ?>" 
                                     alt="<?php echo esc_attr($image['alt'] ?: $title); ?>"
                                     loading="lazy">
                            <?php else : ?>
                                <!-- Заглушка для изображения -->
                                <div class="tgg-services__item-image-placeholder">
                                    <?php if ($type === 'pc') : ?>
                                        <span>🖥️</span>
                                    <?php elseif ($type === 'ps5') : ?>
                                        <span>🎮</span>
                                    <?php elseif ($type === 'vr') : ?>
                                        <span>🥽</span>
                                    <?php else : ?>
                                        <span>🍔</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="tgg-services__item-content">
                            <h3 class="tgg-services__item-title">
                                <?php echo esc_html($title); ?>
                            </h3>
                            
                            <?php if ($subtitle) : ?>
                                <div class="tgg-services__item-subtitle">
                                    <?php echo esc_html($subtitle); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($description) : ?>
                                <p class="tgg-services__item-description">
                                    <?php echo esc_html($description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($link) : ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


