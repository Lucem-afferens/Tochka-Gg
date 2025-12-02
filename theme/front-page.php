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
    // Hero секция (всегда показываем)
    get_template_part('template-parts/components/hero-section');

    // О клубе (по умолчанию показываем, если не отключено)
    if (get_field('about_section_enabled') !== false) {
        get_template_part('template-parts/components/about-section');
    }

    // Преимущества (по умолчанию показываем)
    if (get_field('advantages_section_enabled') !== false) {
        get_template_part('template-parts/components/advantages-section');
    }

    // Услуги (по умолчанию показываем)
    if (get_field('services_section_enabled') !== false) {
        get_template_part('template-parts/components/services-section');
    }

    // Оборудование (краткий обзор, по умолчанию показываем)
    if (get_field('equipment_preview_enabled') !== false) {
        get_template_part('template-parts/components/equipment-section');
    }

    // Ближайшие турниры (по умолчанию показываем)
    if (get_field('tournaments_preview_enabled') !== false) {
        get_template_part('template-parts/components/tournaments-preview');
    }

    // CTA секция (по умолчанию показываем)
    if (get_field('cta_section_enabled') !== false) {
        get_template_part('template-parts/components/footer-cta');
    }
    ?>
</main>

<?php get_footer(); ?>

