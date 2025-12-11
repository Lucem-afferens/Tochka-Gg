<?php
/**
 * Test Page Template
 * Простой тестовый шаблон для проверки работы темы
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main" style="min-height: 100vh; padding: 2rem; background: #0D0F14; color: #E2E8F0;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h1 style="color: #3B82F6; margin-bottom: 1rem;">
            ✅ Тема работает!
        </h1>
        
        <p style="margin-bottom: 1rem;">
            Если вы видите этот текст, значит тема загружается правильно.
        </p>
        
        <div style="background: #161A21; padding: 1rem; border-radius: 8px; margin-top: 2rem;">
            <h2 style="color: #E2E8F0; margin-bottom: 0.5rem;">Информация:</h2>
            <ul style="list-style: none; padding: 0;">
                <li>Название сайта: <strong><?php echo esc_html(get_bloginfo('name')); ?></strong></li>
                <li>URL сайта: <strong><?php echo esc_url(home_url()); ?></strong></li>
                <li>Версия темы: <strong><?php echo esc_html(wp_get_theme()->get('Version')); ?></strong></li>
                <li>ACF установлен: <strong><?php echo function_exists('get_field') ? 'Да ✅' : 'Нет ⚠️'; ?></strong></li>
            </ul>
        </div>
        
        <?php if (!function_exists('get_field')) : ?>
            <div style="background: #FF6B6B; color: #fff; padding: 1rem; border-radius: 8px; margin-top: 2rem;">
                <strong>⚠️ Внимание:</strong> Advanced Custom Fields (ACF) не установлен или не активирован.
                Для полной функциональности темы необходимо установить ACF Pro.
            </div>
        <?php endif; ?>
        
        <div style="margin-top: 2rem;">
            <a href="<?php echo esc_url(admin_url()); ?>" 
               style="display: inline-block; background: #3B82F6; color: #fff; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 4px;">
                Перейти в админку
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>


