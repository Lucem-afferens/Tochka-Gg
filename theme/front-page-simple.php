<?php
/**
 * Front Page Template (Simple version - works without ACF)
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <section class="tgg-hero" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0D0F14; color: #E2E8F0; text-align: center; padding: 2rem;">
        <div class="tgg-container">
            <h1 style="font-size: 3rem; color: #3B82F6; margin-bottom: 1rem;">
                <?php echo esc_html(get_bloginfo('name')); ?>
            </h1>
            <p style="font-size: 1.5rem; margin-bottom: 2rem;">
                Премиальный компьютерный клуб нового поколения
            </p>
            <p style="font-size: 1.125rem; max-width: 600px; margin: 0 auto;">
                Стильное и технологичное игровое пространство, где сочетаются мощное железо, комфорт и высокий стандарт сервиса
            </p>
        </div>
    </section>

    <?php if (function_exists('get_field')) : ?>
        <?php
        // Показываем компоненты только если ACF установлен
        $get_field_safe = function($field, $default = null) {
            if (function_exists('get_field')) {
                $value = get_field($field);
                return $value !== false && $value !== null ? $value : $default;
            }
            return $default;
        };

        if ($get_field_safe('about_section_enabled', true) !== false && locate_template('template-parts/components/about-section.php')) {
            get_template_part('template-parts/components/about-section');
        }
        ?>
    <?php else : ?>
        <section style="padding: 4rem 2rem; background: #161A21; text-align: center;">
            <div class="tgg-container">
                <h2 style="color: #FF6B6B; margin-bottom: 1rem;">⚠️ Требуется установка ACF</h2>
                <p style="color: #E2E8F0; margin-bottom: 1rem;">
                    Для полной функциональности темы необходимо установить плагин <strong>Advanced Custom Fields (ACF Pro)</strong>.
                </p>
                <p style="color: #E2E8F0;">
                    После установки плагина все секции сайта будут доступны.
                </p>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>

