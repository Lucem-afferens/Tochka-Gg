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
            <text x="12" y="17" text-anchor="middle" fill="currentColor" font-family="Arial, sans-serif" font-size="14" font-weight="bold" font-style="normal">i</text>
        </svg>
    </div>
    <p class="tgg-info-notice__text">
        Уточняйте актуальность информации у администратора клуба
    </p>
</div>

