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
                    echo '<li><a href="' . esc_url(home_url('/')) . '" class="tgg-nav__link" data-nav="home"><span class="tgg-nav__link-text">Главная</span></a></li>';
                    if (function_exists('tochkagg_get_page_url')) {
                        $pages = [
                            'equipment' => ['title' => 'Оборудование', 'nav' => 'equipment'],
                            'pricing' => ['title' => 'Цены', 'nav' => 'pricing'],
                            'vr' => ['title' => 'VR арена', 'nav' => 'vr'],
                            'bar' => ['title' => 'Клубный бар', 'nav' => 'bar'],
                            'contacts' => ['title' => 'Контакты', 'nav' => 'contacts']
                        ];
                        foreach ($pages as $slug => $page_data) {
                            $url = tochkagg_get_page_url($slug);
                            if ($url && $url !== '#') {
                                $title = is_array($page_data) ? $page_data['title'] : $page_data;
                                $nav_attr = is_array($page_data) && isset($page_data['nav']) ? $page_data['nav'] : $slug;
                                echo '<li><a href="' . esc_url($url) . '" class="tgg-nav__link" data-nav="' . esc_attr($nav_attr) . '"><span class="tgg-nav__link-text">' . esc_html($title) . '</span></a></li>';
                            }
                        }
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            
            <div class="tgg-header__actions">
                <?php
                // Кнопка бронирования
                $booking_text = function_exists('get_field') ? get_field('booking_button_text', 'option') : 'Забронировать место';
                $booking_link = function_exists('get_field') ? get_field('booking_link', 'option') : null;
                
                // Если ссылка не задана в ACF, используем автоматический поиск страницы бронирования
                if (!$booking_link || $booking_link === '#booking') {
                    if (function_exists('tochkagg_get_page_url')) {
                        $booking_link = tochkagg_get_page_url('booking', home_url('/бронирование/'));
                    } else {
                        $booking_link = home_url('/бронирование/');
                    }
                }
                ?>
                <a href="<?php echo esc_url($booking_link); ?>" class="tgg-header__booking tgg-btn-fire">
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


