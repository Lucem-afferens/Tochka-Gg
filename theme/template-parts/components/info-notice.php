<?php
/**
 * Info Notice Component
 * 
 * Компонент для отображения уведомления об актуальности информации
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Проверяем, включено ли уведомление через SCF
$info_notice_enabled = get_field('info_notice_enabled', 'option');
if ($info_notice_enabled === false || $info_notice_enabled === '0' || $info_notice_enabled === 0) {
    return; // Уведомление отключено
}

// Получаем текст уведомления из SCF или используем значение по умолчанию
$info_notice_text = get_field('info_notice_text', 'option');
if (empty($info_notice_text)) {
    $info_notice_text = 'Уточняйте актуальность информации у администратора клуба';
}
?>

<div class="tgg-info-notice">
    <div class="tgg-info-notice__icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="10" cy="10" r="9" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <text x="10" y="14" font-family="Arial, sans-serif" font-size="12" font-weight="bold" text-anchor="middle" fill="currentColor">i</text>
        </svg>
    </div>
    <p class="tgg-info-notice__text">
        <?php echo esc_html($info_notice_text); ?>
    </p>
</div>

