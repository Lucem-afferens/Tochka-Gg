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

// Получаем данные из ACF
$booking_title = function_exists('get_field') ? get_field('booking_title') : null;
$booking_description = function_exists('get_field') ? get_field('booking_description') : null;
$phone = function_exists('get_field') ? get_field('phone_main', 'option') : '+7 992 222-62-72';
$phone_clean = preg_replace('/[^0-9+]/', '', $phone);
$vk_link = function_exists('get_field') ? get_field('vk_link', 'option') : '#';
$langame_app_id_ios = function_exists('get_field') ? get_field('langame_app_id_ios', 'option') : null;
$langame_app_id_android = function_exists('get_field') ? get_field('langame_app_id_android', 'option') : null;
$langame_deep_link = function_exists('get_field') ? get_field('langame_deep_link', 'option') : 'langame://booking';
$langame_ios_url = $langame_app_id_ios ? "https://apps.apple.com/app/id{$langame_app_id_ios}" : 'https://apps.apple.com/ru/app/langame';
$langame_android_url = $langame_app_id_android ? "https://play.google.com/store/apps/details?id={$langame_app_id_android}" : 'https://play.google.com/store/apps/details?id=ru.langame.app';

// Значения по умолчанию
$booking_title = $booking_title ?: 'Забронировать место';
$booking_description = $booking_description ?: 'Выберите удобный способ бронирования';
?>

<section class="tgg-booking">
    <div class="tgg-container">
        <div class="tgg-booking__header">
            <?php if ($booking_title) : ?>
                <h1 class="tgg-booking__title">
                    <?php echo esc_html($booking_title); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ($booking_description) : ?>
                <p class="tgg-booking__description">
                    <?php echo esc_html($booking_description); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <!-- Десктоп: треугольное расположение -->
        <div class="tgg-booking__triangle-wrapper" id="booking-triangle">
            <!-- Вариант 1: По телефону -->
            <?php
            $phone_image = function_exists('get_field') ? get_field('booking_phone_image') : null;
            $phone_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($phone_image, 280, 200, 'Телефон')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(280, 200, 'Телефон', '1a1d29', '3b82f6') : 'https://placehold.co/280x200/1a1d29/3b82f6?text=Телефон',
                    'alt' => 'Бронирование по телефону (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option" data-booking-option="phone">
                <div class="tgg-booking__option-image">
                    <img src="<?php echo esc_url($phone_image_data['url']); ?>" alt="<?php echo esc_attr($phone_image_data['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <h2 class="tgg-booking__option-title">По телефону</h2>
                    
                    <p class="tgg-booking__option-description">
                        Позвоните нам для бронирования
                    </p>
                    
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="tgg-booking__option-button tgg-btn-primary">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
            </div>
            
            <!-- Вариант 2: ВКонтакте -->
            <?php
            $vk_image = function_exists('get_field') ? get_field('booking_vk_image') : null;
            $vk_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($vk_image, 280, 200, 'ВКонтакте')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(280, 200, 'ВКонтакте', '1a1d29', '8b5cf6') : 'https://placehold.co/280x200/1a1d29/8b5cf6?text=ВКонтакте',
                    'alt' => 'Бронирование ВКонтакте (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option" data-booking-option="vk">
                <div class="tgg-booking__option-image">
                    <img src="<?php echo esc_url($vk_image_data['url']); ?>" alt="<?php echo esc_attr($vk_image_data['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <h2 class="tgg-booking__option-title">ВКонтакте</h2>
                    
                    <p class="tgg-booking__option-description">
                        Напишите нам в сообщениях
                    </p>
                    
                    <a href="<?php echo esc_url($vk_link ?: '#'); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="tgg-booking__option-button tgg-btn-secondary">
                        Написать в ВК
                    </a>
                </div>
            </div>
            
            <!-- Вариант 3: Langame приложение -->
            <?php
            $langame_image = function_exists('get_field') ? get_field('booking_langame_image') : null;
            $langame_image_data = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($langame_image, 280, 200, 'Langame')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(280, 200, 'Langame', '1a1d29', 'ff6b35') : 'https://placehold.co/280x200/1a1d29/ff6b35?text=Langame',
                    'alt' => 'Бронирование через Langame (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option tgg-booking__option--langame" data-booking-option="langame">
                <div class="tgg-booking__option-image">
                    <img src="<?php echo esc_url($langame_image_data['url']); ?>" alt="<?php echo esc_attr($langame_image_data['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <h2 class="tgg-booking__option-title">Через Langame</h2>
                    
                    <p class="tgg-booking__option-description">
                        Забронируйте через приложение
                    </p>
                    
                    <button class="tgg-booking__option-button tgg-btn-primary" 
                            data-langame-deep-link="<?php echo esc_attr($langame_deep_link); ?>"
                            data-langame-ios="<?php echo esc_attr($langame_ios_url); ?>"
                            data-langame-android="<?php echo esc_attr($langame_android_url); ?>"
                            id="langame-booking-btn">
                        Открыть приложение
                    </button>
                    
                    <div class="tgg-booking__option-links">
                        <?php if ($langame_ios_url) : ?>
                            <a href="<?php echo esc_url($langame_ios_url); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="tgg-booking__option-link">
                                iOS
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($langame_android_url) : ?>
                            <a href="<?php echo esc_url($langame_android_url); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="tgg-booking__option-link">
                                Android
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Мобильные: компактные карточки -->
        <div class="tgg-booking__options-list">
            <!-- Вариант 1: По телефону -->
            <?php
            $phone_image_mobile = function_exists('get_field') ? get_field('booking_phone_image') : null;
            $phone_image_data_mobile = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($phone_image_mobile, 400, 200, 'Телефон')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(400, 200, 'Телефон', '1a1d29', '3b82f6') : 'https://placehold.co/400x200/1a1d29/3b82f6?text=Телефон',
                    'alt' => 'Бронирование по телефону (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option">
                <div class="tgg-booking__option-image">
                    <img src="<?php echo esc_url($phone_image_data_mobile['url']); ?>" alt="<?php echo esc_attr($phone_image_data_mobile['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <h2 class="tgg-booking__option-title">По телефону</h2>
                    
                    <p class="tgg-booking__option-description">
                        Позвоните нам, и мы поможем выбрать удобное время для посещения клуба
                    </p>
                    
                    <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="tgg-booking__option-button tgg-btn-primary">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
            </div>
            
            <!-- Вариант 2: ВКонтакте -->
            <?php
            $vk_image_mobile = function_exists('get_field') ? get_field('booking_vk_image') : null;
            $vk_image_data_mobile = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($vk_image_mobile, 400, 200, 'ВКонтакте')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(400, 200, 'ВКонтакте', '1a1d29', '8b5cf6') : 'https://placehold.co/400x200/1a1d29/8b5cf6?text=ВКонтакте',
                    'alt' => 'Бронирование ВКонтакте (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option">
                <div class="tgg-booking__option-image">
                    <img src="<?php echo esc_url($vk_image_data_mobile['url']); ?>" alt="<?php echo esc_attr($vk_image_data_mobile['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <h2 class="tgg-booking__option-title">ВКонтакте</h2>
                    
                    <p class="tgg-booking__option-description">
                        Напишите нам в сообщениях сообщества ВКонтакте для бронирования места
                    </p>
                    
                    <a href="<?php echo esc_url($vk_link ?: '#'); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="tgg-booking__option-button tgg-btn-secondary">
                        Написать в ВК
                    </a>
                </div>
            </div>
            
            <!-- Вариант 3: Langame приложение -->
            <?php
            $langame_image_mobile = function_exists('get_field') ? get_field('booking_langame_image') : null;
            $langame_image_data_mobile = function_exists('tochkagg_get_image_or_placeholder') 
                ? tochkagg_get_image_or_placeholder($langame_image_mobile, 400, 200, 'Langame')
                : [
                    'url' => function_exists('tochkagg_get_placeholder_image') ? tochkagg_get_placeholder_image(400, 200, 'Langame', '1a1d29', 'ff6b35') : 'https://placehold.co/400x200/1a1d29/ff6b35?text=Langame',
                    'alt' => 'Бронирование через Langame (заглушка)'
                ];
            ?>
            <div class="tgg-booking__option tgg-booking__option--langame">
                <div class="tgg-booking__option-image">
                    <img src="<?php echo esc_url($langame_image_data_mobile['url']); ?>" alt="<?php echo esc_attr($langame_image_data_mobile['alt']); ?>" loading="lazy">
                </div>
                
                <div class="tgg-booking__option-content">
                    <h2 class="tgg-booking__option-title">Через приложение Langame</h2>
                    
                    <p class="tgg-booking__option-description">
                        Забронируйте место через мобильное приложение Langame на вашем устройстве
                    </p>
                    
                    <button class="tgg-booking__option-button tgg-btn-primary" 
                            data-langame-deep-link="<?php echo esc_attr($langame_deep_link); ?>"
                            data-langame-ios="<?php echo esc_attr($langame_ios_url); ?>"
                            data-langame-android="<?php echo esc_attr($langame_android_url); ?>"
                            id="langame-booking-btn-mobile">
                        Открыть приложение
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
        </div>
    </div>
</section>

