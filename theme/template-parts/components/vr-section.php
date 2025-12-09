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
$vr_area_label = get_field('vr_area_label') ?: 'Площадь виртуальной арены';
$vr_area_unit = get_field('vr_area_unit') ?: 'м²';
$vr_players = get_field('vr_players') ?: '10';
$vr_players_label = get_field('vr_players_label') ?: 'Игроков одновременно';
$vr_players_prefix = get_field('vr_players_prefix') ?: 'до';
$vr_media_type = get_field('vr_media_type') ?: 'image'; // 'image' или 'video'
$vr_image = get_field('vr_image');
$vr_video = get_field('vr_video');
$vr_link = get_field('vr_link') ?: 'https://vk.com/another_world_kungur';
$vr_button_text = get_field('vr_button_text') ?: 'ВКонтакте арены';
$vr_phone = get_field('vr_phone') ?: '+7 912 068-34-17';
$vr_services = get_field('vr_services');
?>

<section class="tgg-vr" id="vr">
    <div class="tgg-container">
        <h2 class="tgg-vr__title">
            <?php echo esc_html($vr_title); ?>
        </h2>
        
        <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
            <?php get_template_part('template-parts/components/info-notice'); ?>
        <?php endif; ?>
        
        <div class="tgg-vr__wrapper">
            <div class="tgg-vr__content">
                
                <?php if ($vr_description) : ?>
                    <p class="tgg-vr__description">
                        <?php echo esc_html($vr_description); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-vr__features">
                    <div class="tgg-vr__feature">
                        <div class="tgg-vr__feature-value"><?php echo esc_html($vr_area); ?> <?php echo esc_html($vr_area_unit); ?></div>
                        <div class="tgg-vr__feature-label"><?php echo esc_html($vr_area_label); ?></div>
                    </div>
                    
                    <div class="tgg-vr__feature">
                        <div class="tgg-vr__feature-value"><?php echo esc_html($vr_players_prefix); ?> <?php echo esc_html($vr_players); ?></div>
                        <div class="tgg-vr__feature-label"><?php echo esc_html($vr_players_label); ?></div>
                    </div>
                </div>
                
                <?php if ($vr_services && is_array($vr_services) && !empty($vr_services)) : ?>
                    <div class="tgg-vr__services">
                        <ul>
                            <?php foreach ($vr_services as $service) : 
                                $service_text = isset($service['service_text']) ? $service['service_text'] : '';
                                if (!empty($service_text)) :
                                    // Автоматическая подстановка количества игроков в тексте
                                    // Заменяем плейсхолдеры: [количество игроков], [игроков], {players}, {vr_players}
                                    $service_text = str_replace(
                                        ['[количество игроков]', '[игроков]', '{players}', '{vr_players}', '{players_prefix}'],
                                        [$vr_players, $vr_players, $vr_players, $vr_players, $vr_players_prefix],
                                        $service_text
                                    );
                                    // Также заменяем полную фразу "до [количество игроков]"
                                    $service_text = str_replace(
                                        'до [количество игроков]',
                                        $vr_players_prefix . ' ' . $vr_players,
                                        $service_text
                                    );
                                    // И заменяем "до [игроков]"
                                    $service_text = str_replace(
                                        'до [игроков]',
                                        $vr_players_prefix . ' ' . $vr_players,
                                        $service_text
                                    );
                            ?>
                                <li><?php echo esc_html($service_text); ?></li>
                            <?php 
                                endif;
                            endforeach; ?>
                        </ul>
                    </div>
                <?php else : ?>
                    <!-- Значения по умолчанию, если список услуг не задан -->
                    <div class="tgg-vr__services">
                        <ul>
                            <li>Телепорт в виртуальный мир с помощью современного оборудования</li>
                            <li>Уникальные игры в форматах «игрок против игрока» и «игрок против компьютера»</li>
                            <li>Арена VR для командных игр <?php echo esc_html($vr_players_prefix); ?> <?php echo esc_html($vr_players); ?> человек</li>
                            <li>Зона отдыха и банкета на все время праздника</li>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="tgg-vr__contacts">
                    <?php if ($vr_link && $vr_button_text) : ?>
                        <a href="<?php echo esc_url($vr_link); ?>" target="_blank" rel="noopener noreferrer" class="tgg-btn-fire">
                            <?php echo esc_html($vr_button_text); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($vr_phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $vr_phone)); ?>" class="tgg-footer-cta__phone">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <?php echo esc_html($vr_phone); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="tgg-vr__media">
                <?php if ($vr_media_type === 'video' && $vr_video) : 
                    // Если выбран тип "видео" и видео указано
                    $vr_video_url = is_array($vr_video) ? ($vr_video['url'] ?? '') : $vr_video;
                    // Если это массив (File field), берем URL
                    if (is_array($vr_video) && isset($vr_video['url'])) {
                        $vr_video_url = $vr_video['url'];
                    } elseif (is_string($vr_video) && filter_var($vr_video, FILTER_VALIDATE_URL)) {
                        $vr_video_url = $vr_video;
                    } else {
                        // Если видео невалидно, показываем изображение
                        $vr_media_type = 'image';
                    }
                    
                    if ($vr_media_type === 'video' && !empty($vr_video_url)) :
                        // Используем изображение как poster для видео
                        $vr_poster_data = tochkagg_get_image_or_placeholder($vr_image, 800, 600, 'VR Arena');
                ?>
                    <video class="tgg-vr__media-video" 
                           controls 
                           preload="metadata"
                           poster="<?php echo esc_url($vr_poster_data['url']); ?>"
                           aria-label="Видео VR арены">
                        <source src="<?php echo esc_url($vr_video_url); ?>" type="video/mp4">
                        Ваш браузер не поддерживает воспроизведение видео.
                    </video>
                <?php else : 
                    // Если видео не указано или невалидно, показываем изображение
                    $vr_image_data = tochkagg_get_image_or_placeholder($vr_image, 800, 600, 'VR Arena');
                ?>
                    <img src="<?php echo esc_url($vr_image_data['url']); ?>" 
                         alt="<?php echo esc_attr($vr_image_data['alt']); ?>"
                         loading="lazy">
                <?php endif; ?>
                <?php else : 
                    // Если выбран тип "изображение"
                    $vr_image_data = tochkagg_get_image_or_placeholder($vr_image, 800, 600, 'VR Arena');
                ?>
                    <img src="<?php echo esc_url($vr_image_data['url']); ?>" 
                         alt="<?php echo esc_attr($vr_image_data['alt']); ?>"
                         loading="lazy">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


