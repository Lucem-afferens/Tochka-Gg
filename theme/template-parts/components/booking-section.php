<?php
/**
 * Booking Section Template
 *
 * Страница бронирования мест с несколькими вариантами
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем данные из SCF
$booking_title = function_exists('get_field') ? get_field('booking_title') : null;
$booking_description = function_exists('get_field') ? get_field('booking_description') : null;
$phone_raw = function_exists('get_field') ? get_field('phone_main', 'option') : null;
$phone = $phone_raw ?: '8 (992) 222-62-72';
$phone_clean = preg_replace('/[^0-9+]/', '', $phone);
$vk_link = function_exists('get_field') ? get_field('vk_link', 'option') : '#';
$langame_app_id_ios = function_exists('get_field') ? get_field('langame_app_id_ios', 'option') : null;
$langame_app_id_android = function_exists('get_field') ? get_field('langame_app_id_android', 'option') : null;
$langame_deep_link = function_exists('get_field') ? get_field('langame_deep_link', 'option') : 'langame://booking';
$langame_ios_url = $langame_app_id_ios ? "https://apps.apple.com/app/id{$langame_app_id_ios}" : 'https://apps.apple.com/ru/app/langame';
$langame_android_url = $langame_app_id_android ? "https://play.google.com/store/apps/details?id={$langame_app_id_android}" : 'https://play.google.com/store/apps/details?id=ru.langame.app';

// Значения по умолчанию для заголовка и описания страницы
$booking_title = $booking_title ?: 'Забронировать место';
$booking_description = $booking_description ?: 'Выберите удобный способ бронирования';

// Поля для карточки Langame
$langame_image = function_exists('get_field') ? get_field('booking_langame_image') : null;
$langame_title = function_exists('get_field') ? get_field('booking_langame_title') : null;
$langame_description = function_exists('get_field') ? get_field('booking_langame_description') : null;
$langame_button_text = function_exists('get_field') ? get_field('booking_langame_button_text') : null;
$langame_badges = function_exists('get_field') ? get_field('booking_langame_badges') : null;
$langame_show_popular = function_exists('get_field') ? get_field('booking_langame_show_popular') : true;
$langame_popular_text = function_exists('get_field') ? get_field('booking_langame_popular_text') : null;

// Значения по умолчанию для карточки Langame
$langame_title = $langame_title ?: 'Через приложение Langame';
$langame_description = $langame_description ?: 'Забронируйте место через мобильное приложение Langame на вашем устройстве в любое время';
$langame_button_text = $langame_button_text ?: 'Открыть приложение';
$langame_popular_text = $langame_popular_text ?: 'Выбор большинства пользователей';

// Если бейджи не заданы, используем значения по умолчанию
if (!$langame_badges || empty($langame_badges)) {
    $langame_badges = [
        ['badge_text' => 'Быстро'],
        ['badge_text' => 'Удобно'],
        ['badge_text' => '24/7']
    ];
}

// Поля для карточки ВКонтакте
$vk_image = function_exists('get_field') ? get_field('booking_vk_image') : null;
$vk_title = function_exists('get_field') ? get_field('booking_vk_title') : null;
$vk_description = function_exists('get_field') ? get_field('booking_vk_description') : null;
$vk_button_text = function_exists('get_field') ? get_field('booking_vk_button_text') : null;
$vk_badges = function_exists('get_field') ? get_field('booking_vk_badges') : null;

// Значения по умолчанию для карточки ВКонтакте
$vk_title = $vk_title ?: 'ВКонтакте';
$vk_description = $vk_description ?: 'Напишите нам в сообщениях сообщества ВКонтакте для бронирования места';
$vk_button_text = $vk_button_text ?: 'Написать в ВК';

// Если бейджи не заданы, используем значение по умолчанию
if (!$vk_badges || empty($vk_badges)) {
    $vk_badges = [
        ['badge_text' => 'Удобно']
    ];
}

// Поля для карточки телефона
$phone_image = function_exists('get_field') ? get_field('booking_phone_image') : null;
$phone_title = function_exists('get_field') ? get_field('booking_phone_title') : null;
$phone_description = function_exists('get_field') ? get_field('booking_phone_description') : null;
$phone_badges = function_exists('get_field') ? get_field('booking_phone_badges') : null;

// Значения по умолчанию для карточки телефона
$phone_title = $phone_title ?: 'По телефону';
$phone_description = $phone_description ?: 'Позвоните нам, и мы поможем выбрать удобное время для посещения клуба';

// Если бейджи не заданы, используем значение по умолчанию
if (!$phone_badges || empty($phone_badges)) {
    $phone_badges = [
        ['badge_text' => 'Быстро']
    ];
}
?>

<section class="tgg-booking">
    <div class="tgg-container">
        <div class="tgg-booking__header">
            <?php if ($booking_title) : ?>
                <h1 class="tgg-booking__title">
                    <?php echo esc_html($booking_title); ?>
                </h1>
            <?php endif; ?>
            
            <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
                <?php get_template_part('template-parts/components/info-notice'); ?>
            <?php endif; ?>
            
            <?php if ($booking_description) : ?>
                <p class="tgg-booking__description">
                    <?php echo esc_html($booking_description); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <div class="tgg-booking__options">
            <!-- Вариант 1: Langame приложение (первым - приоритетный) -->
            <?php
            $langame_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($langame_image, 400, 300, 'Бронирование через Langame')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(400, 300, 'Langame', '1a1d29', 'ff6b35') : 'https://placehold.co/400x300/1a1d29/ff6b35?text=Langame',
                    'alt' => 'Бронирование через Langame (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option tgg-booking__option--langame">
                <div class="tgg-booking__option-image">
                    <div class="tgg-booking__option-overlay"></div>
                    <img src="<?php echo esc_url($langame_image_data['url']); ?>" alt="<?php echo esc_attr($langame_image_data['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <?php if ($langame_badges && !empty($langame_badges)) : ?>
                        <div class="tgg-booking__option-badges">
                            <?php foreach ($langame_badges as $badge) : 
                                $badge_text = isset($badge['badge_text']) ? $badge['badge_text'] : '';
                                if (!empty($badge_text)) :
                            ?>
                                <div class="tgg-booking__option-badge"><?php echo esc_html($badge_text); ?></div>
                            <?php 
                                endif;
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($langame_show_popular) : ?>
                        <div class="tgg-booking__option-popular">
                            <span class="tgg-booking__option-popular-icon">⭐</span>
                            <span class="tgg-booking__option-popular-text"><?php echo esc_html($langame_popular_text); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="tgg-booking__option-title"><?php echo esc_html($langame_title); ?></h2>
                    
                    <p class="tgg-booking__option-description">
                        <?php echo esc_html($langame_description); ?>
                    </p>
                    
                    <button class="tgg-booking__option-button tgg-btn-fire" 
                            data-langame-deep-link="<?php echo esc_attr($langame_deep_link); ?>"
                            data-langame-ios="<?php echo esc_attr($langame_ios_url); ?>"
                            data-langame-android="<?php echo esc_attr($langame_android_url); ?>"
                            id="langame-booking-btn">
                        <?php echo esc_html($langame_button_text); ?>
                    </button>
                    
                    <div class="tgg-booking__option-links">
                        <?php if ($langame_ios_url) : ?>
                            <a href="<?php echo esc_url($langame_ios_url); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="tgg-booking__option-link">
                                Установить для iOS
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($langame_android_url) : ?>
                            <a href="<?php echo esc_url($langame_android_url); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="tgg-booking__option-link">
                                Установить для Android
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Вариант 2: ВКонтакте -->
            <?php
            $vk_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($vk_image, 400, 300, 'Бронирование ВКонтакте')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(400, 300, 'ВКонтакте', '1a1d29', '8b5cf6') : 'https://placehold.co/400x300/1a1d29/8b5cf6?text=ВКонтакте',
                    'alt' => 'Бронирование ВКонтакте (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option tgg-booking__option--vk">
                <div class="tgg-booking__option-image">
                    <div class="tgg-booking__option-overlay"></div>
                    <img src="<?php echo esc_url($vk_image_data['url']); ?>" alt="<?php echo esc_attr($vk_image_data['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <?php if ($vk_badges && !empty($vk_badges)) : ?>
                        <div class="tgg-booking__option-badges">
                            <?php foreach ($vk_badges as $badge) : 
                                $badge_text = isset($badge['badge_text']) ? $badge['badge_text'] : '';
                                if (!empty($badge_text)) :
                            ?>
                                <div class="tgg-booking__option-badge"><?php echo esc_html($badge_text); ?></div>
                            <?php 
                                endif;
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="tgg-booking__option-title"><?php echo esc_html($vk_title); ?></h2>
                    
                    <p class="tgg-booking__option-description">
                        <?php echo esc_html($vk_description); ?>
                    </p>
                    
                    <a href="<?php echo esc_url($vk_link ?: '#'); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="tgg-booking__option-button tgg-btn-fire">
                        <?php echo esc_html($vk_button_text); ?>
                    </a>
                </div>
            </div>
            
            <!-- Вариант 3: По телефону -->
            <?php
            $phone_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($phone_image, 400, 300, 'Бронирование по телефону')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(400, 300, 'Телефон', '1a1d29', '3b82f6') : 'https://placehold.co/400x300/1a1d29/3b82f6?text=Телефон',
                    'alt' => 'Бронирование по телефону (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option tgg-booking__option--phone">
                <div class="tgg-booking__option-image">
                    <div class="tgg-booking__option-overlay"></div>
                    <img src="<?php echo esc_url($phone_image_data['url']); ?>" alt="<?php echo esc_attr($phone_image_data['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <?php if ($phone_badges && !empty($phone_badges)) : ?>
                        <div class="tgg-booking__option-badges">
                            <?php foreach ($phone_badges as $badge) : 
                                $badge_text = isset($badge['badge_text']) ? $badge['badge_text'] : '';
                                if (!empty($badge_text)) :
                            ?>
                                <div class="tgg-booking__option-badge"><?php echo esc_html($badge_text); ?></div>
                            <?php 
                                endif;
                            endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <h2 class="tgg-booking__option-title"><?php echo esc_html($phone_title); ?></h2>
                    
                    <p class="tgg-booking__option-description">
                        <?php echo esc_html($phone_description); ?>
                    </p>
                    
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="tgg-booking__option-button tgg-btn-fire">
                        <?php echo esc_html($phone ?: '8 (992) 222-62-72'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
