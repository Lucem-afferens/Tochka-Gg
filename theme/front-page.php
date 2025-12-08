<?php
/**
 * Front Page Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php
    // Безопасная функция для получения SCF поля
    $get_field_safe = function($field, $default = null) {
        if (function_exists('get_field')) {
            $value = get_field($field);
            return $value !== false && $value !== null ? $value : $default;
        }
        return $default;
    };

    // Hero секция (всегда показываем)
    if (locate_template('template-parts/components/hero-section.php')) {
        get_template_part('template-parts/components/hero-section');
    }

    // О клубе (по умолчанию показываем, если не отключено)
    $about_enabled = function_exists('get_field') ? get_field('about_section_enabled') : true;
    if ($about_enabled === null || $about_enabled === '' || $about_enabled === true || $about_enabled === '1' || $about_enabled === 1) {
        if (locate_template('template-parts/components/about-section.php')) {
            get_template_part('template-parts/components/about-section');
        }
    }

    // Преимущества (по умолчанию показываем)
    $advantages_enabled = function_exists('get_field') ? get_field('advantages_section_enabled') : true;
    if ($advantages_enabled === null || $advantages_enabled === '' || $advantages_enabled === true || $advantages_enabled === '1' || $advantages_enabled === 1) {
        if (locate_template('template-parts/components/advantages-section.php')) {
            get_template_part('template-parts/components/advantages-section');
        }
    }

    // Услуги (по умолчанию показываем)
    $services_enabled = function_exists('get_field') ? get_field('services_section_enabled') : true;
    if ($services_enabled === null || $services_enabled === '' || $services_enabled === true || $services_enabled === '1' || $services_enabled === 1) {
        if (locate_template('template-parts/components/services-section.php')) {
            get_template_part('template-parts/components/services-section');
        }
    }

    // Оборудование (краткий обзор, по умолчанию показываем)
    $equipment_enabled = function_exists('get_field') ? get_field('equipment_preview_enabled') : true;
    if ($equipment_enabled === null || $equipment_enabled === '' || $equipment_enabled === true || $equipment_enabled === '1' || $equipment_enabled === 1) {
        if (locate_template('template-parts/components/equipment-section.php')) {
            get_template_part('template-parts/components/equipment-section');
        }
    }

    // Новости и события (по умолчанию показываем, если есть новости)
    $news_enabled = function_exists('get_field') ? get_field('news_preview_enabled') : true;
    if ($news_enabled === null || $news_enabled === '' || $news_enabled === true || $news_enabled === '1' || $news_enabled === 1) {
        if (locate_template('template-parts/components/news-preview.php')) {
            get_template_part('template-parts/components/news-preview');
        }
    }

    // Ближайшие турниры (по умолчанию показываем)
    $tournaments_enabled = function_exists('get_field') ? get_field('tournaments_preview_enabled') : true;
    if ($tournaments_enabled === null || $tournaments_enabled === '' || $tournaments_enabled === true || $tournaments_enabled === '1' || $tournaments_enabled === 1) {
        if (locate_template('template-parts/components/tournaments-preview.php')) {
            get_template_part('template-parts/components/tournaments-preview');
        }
    }

    // CTA секция (по умолчанию показываем)
    $cta_enabled = function_exists('get_field') ? get_field('cta_section_enabled') : true;
    if ($cta_enabled === null || $cta_enabled === '' || $cta_enabled === true || $cta_enabled === '1' || $cta_enabled === 1) {
        if (locate_template('template-parts/components/footer-cta.php')) {
            get_template_part('template-parts/components/footer-cta');
        }
    }
    ?>
</main>

<?php get_footer(); ?>

