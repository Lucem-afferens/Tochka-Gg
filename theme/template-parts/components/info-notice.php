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
?>

<div class="tgg-info-notice">
    <div class="tgg-info-notice__icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
    </div>
    <p class="tgg-info-notice__text">
        Уточняйте актуальность информации у администратора клуба
    </p>
</div>

