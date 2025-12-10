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
        <span>i</span>
    </div>
    <p class="tgg-info-notice__text">
        <?php echo esc_html($info_notice_text); ?>
    </p>
</div>

