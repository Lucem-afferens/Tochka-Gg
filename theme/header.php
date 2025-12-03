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
            if ($logo && is_array($logo) && !empty($logo['url'])) :
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <img src="<?php echo esc_url($logo['url']); ?>" 
                         alt="<?php echo esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>">
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo esc_html(get_bloginfo('name')); ?>
                </a>
            <?php endif; ?>
        </div>

        <nav class="tgg-header__nav" role="navigation" aria-label="<?php esc_attr_e('Главное меню', 'tochkagg'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'main_menu',
                'container' => false,
                'menu_class' => 'tgg-nav__list',
                'fallback_cb' => false,
            ]);
            ?>
        </nav>

        <button class="tgg-header__burger" aria-label="<?php esc_attr_e('Открыть меню', 'tochkagg'); ?>" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>


