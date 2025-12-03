<?php
/**
 * Header Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="tgg-header">
    <div class="tgg-container">
        <div class="tgg-header__logo">
            <?php
            $logo = function_exists('get_field') ? get_field('logo', 'option') : false;
            $logo_data = tochkagg_get_image_or_placeholder($logo, 200, 60, 'Logo');
            ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <img src="<?php echo esc_url($logo_data['url']); ?>" 
                     alt="<?php echo esc_attr($logo_data['alt']); ?>">
            </a>
        </div>

        <nav class="tgg-header__nav" role="navigation" aria-label="<?php esc_attr_e('Главное меню', 'tochkagg'); ?>">
            <div class="tgg-nav__wrapper">
                <?php
                if (has_nav_menu('main_menu')) {
                    wp_nav_menu([
                        'theme_location' => 'main_menu',
                        'container' => false,
                        'menu_class' => 'tgg-nav__list',
                        'fallback_cb' => false,
                        'link_before' => '<span class="tgg-nav__link-text">',
                        'link_after' => '</span>',
                    ]);
                } else {
                    // Fallback меню, если WordPress меню не настроено
                    echo '<ul class="tgg-nav__list">';
                    echo '<li><a href="' . esc_url(home_url('/')) . '" class="tgg-nav__link"><span class="tgg-nav__link-text">Главная</span></a></li>';
                    if (function_exists('tochkagg_get_page_url')) {
                        $pages = [
                            'equipment' => 'Оборудование',
                            'pricing' => 'Цены',
                            'vr' => 'VR арена',
                            'bar' => 'Клубный бар',
                            'contacts' => 'Контакты'
                        ];
                        foreach ($pages as $slug => $title) {
                            $url = tochkagg_get_page_url($slug);
                            if ($url && $url !== '#') {
                                echo '<li><a href="' . esc_url($url) . '" class="tgg-nav__link"><span class="tgg-nav__link-text">' . esc_html($title) . '</span></a></li>';
                            }
                        }
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            
            <div class="tgg-header__actions">
                <?php
                // Получаем телефон из ACF
                $phone = function_exists('get_field') ? get_field('phone_main', 'option') : false;
                $phone_display = $phone ?: '+7 992 222-62-72';
                $phone_clean = preg_replace('/[^0-9+]/', '', $phone_display);
                ?>
                
                <?php if ($phone_display) : ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="tgg-header__phone" aria-label="Позвонить">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                    </svg>
                    <span class="tgg-header__phone-text"><?php echo esc_html($phone_display); ?></span>
                </a>
                <?php endif; ?>
                
                <?php
                // Кнопка бронирования
                $booking_link = function_exists('get_field') ? get_field('booking_link', 'option') : '#booking';
                $booking_text = function_exists('get_field') ? get_field('booking_button_text', 'option') : 'Забронировать место';
                ?>
                <a href="<?php echo esc_url($booking_link ?: '#booking'); ?>" class="tgg-header__booking tgg-btn-primary">
                    <?php echo esc_html($booking_text ?: 'Забронировать место'); ?>
                </a>
            </div>
        </nav>

        <button class="tgg-header__burger" aria-label="<?php esc_attr_e('Открыть меню', 'tochkagg'); ?>" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>


