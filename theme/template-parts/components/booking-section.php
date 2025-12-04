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
        
        <div class="tgg-booking__options">
            <!-- Вариант 1: По телефону -->
            <div class="tgg-booking__option">
                <div class="tgg-booking__option-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                </div>
                
                <h2 class="tgg-booking__option-title">По телефону</h2>
                
                <p class="tgg-booking__option-description">
                    Позвоните нам, и мы поможем выбрать удобное время
                </p>
                
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="tgg-booking__option-button tgg-btn-primary">
                    <?php echo esc_html($phone); ?>
                </a>
            </div>
            
            <!-- Вариант 2: ВКонтакте -->
            <div class="tgg-booking__option">
                <div class="tgg-booking__option-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.785 16.241s.287-.03.435-.183c.135-.138.131-.395.131-.395s-.02-1.23.553-1.412c.564-.179 1.289.896 2.058 1.292.568.293 1 .229 1 .229l2.046-.03s1.069-.067.561-.915c-.042-.067-.298-.636-1.538-1.797-1.301-1.218-1.124-.509.439-1.56 1.001-.686 1.403-1.104 1.275-1.283-.119-.164-.855-.12-1.175-.07-.253.038-.437-.072-.437-.072s-.785-.501-1.75-.501c-1.852 0-2.448.969-2.553 1.366 0 0-.209.662.484 1.068.467.276 1.096.426 1.643.683.688.326.945.536.945.915 0 .326-.239.642-.522.766-.625.276-1.635.719-2.688 1.006-.96.259-1.459.206-1.459.206s-1.099-.068-.807-1.012c.053-.171.173-.354.36-.552.616-.651 1.588-1.551 1.588-1.551s.18-.144.044-.334c-.134-.19-.24-.138-.24-.138l-1.597.019s-.357.011-.488.223c-.117.194-.01.602-.01.602s1.255 2.953 1.693 3.943c.429.958.898 1.14.898 1.14z"/>
                    </svg>
                </div>
                
                <h2 class="tgg-booking__option-title">ВКонтакте</h2>
                
                <p class="tgg-booking__option-description">
                    Напишите нам в сообщениях сообщества ВКонтакте
                </p>
                
                <a href="<?php echo esc_url($vk_link ?: '#'); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="tgg-booking__option-button tgg-btn-secondary">
                    Написать в ВК
                </a>
            </div>
            
            <!-- Вариант 3: Langame приложение -->
            <div class="tgg-booking__option tgg-booking__option--langame">
                <div class="tgg-booking__option-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                        <line x1="12" y1="18" x2="12.01" y2="18"/>
                    </svg>
                </div>
                
                <h2 class="tgg-booking__option-title">Через приложение Langame</h2>
                
                <p class="tgg-booking__option-description">
                    Забронируйте место через мобильное приложение Langame
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
</section>

