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
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
            <!-- Точка буквы i -->
            <circle cx="12" cy="8" r="1.5" fill="currentColor"/>
            <!-- Палочка буквы i -->
            <line x1="12" y1="10" x2="12" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </div>
    <p class="tgg-info-notice__text">
        Уточняйте актуальность информации у администратора клуба
    </p>
</div>

