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
    // Безопасная функция для получения ACF поля
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
    if ($get_field_safe('about_section_enabled', true) !== false) {
        if (locate_template('template-parts/components/about-section.php')) {
            get_template_part('template-parts/components/about-section');
        }
    }

    // Преимущества (по умолчанию показываем)
    if ($get_field_safe('advantages_section_enabled', true) !== false) {
        if (locate_template('template-parts/components/advantages-section.php')) {
            get_template_part('template-parts/components/advantages-section');
        }
    }

    // Услуги (по умолчанию показываем)
    if ($get_field_safe('services_section_enabled', true) !== false) {
        if (locate_template('template-parts/components/services-section.php')) {
            get_template_part('template-parts/components/services-section');
        }
    }

    // Оборудование (краткий обзор, по умолчанию показываем)
    if ($get_field_safe('equipment_preview_enabled', true) !== false) {
        if (locate_template('template-parts/components/equipment-section.php')) {
            get_template_part('template-parts/components/equipment-section');
        }
    }

    // Ближайшие турниры (по умолчанию показываем)
    if ($get_field_safe('tournaments_preview_enabled', true) !== false) {
        if (locate_template('template-parts/components/tournaments-preview.php')) {
            get_template_part('template-parts/components/tournaments-preview');
        }
    }

    // CTA секция (по умолчанию показываем)
    if ($get_field_safe('cta_section_enabled', true) !== false) {
        if (locate_template('template-parts/components/footer-cta.php')) {
            get_template_part('template-parts/components/footer-cta');
        }
    }
    ?>
</main>

<?php get_footer(); ?>

