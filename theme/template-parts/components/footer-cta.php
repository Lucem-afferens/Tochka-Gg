<?php
/**
 * Footer CTA Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$cta_title = get_field('footer_cta_title') ?: 'Готов начать играть?';
$cta_text = get_field('footer_cta_text') ?: 'Приходи к нам и окунись в атмосферу настоящего гейминга';
$cta_button_text = get_field('footer_cta_button_text') ?: 'Связаться с нами';
$cta_button_link = get_field('footer_cta_button_link') ?: '/contacts';
$cta_phone = get_field('phone_main', 'option') ?: '+7 992 222-62-72';
?>

<section class="tgg-footer-cta">
    <div class="tgg-container">
        <div class="tgg-footer-cta__wrapper">
            <div class="tgg-footer-cta__content">
                <?php if ($cta_title) : ?>
                    <h2 class="tgg-footer-cta__title">
                        <?php echo esc_html($cta_title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($cta_text) : ?>
                    <p class="tgg-footer-cta__text">
                        <?php echo esc_html($cta_text); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-footer-cta__actions">
                    <?php if ($cta_button_text && $cta_button_link) : ?>
                        <a href="<?php echo esc_url($cta_button_link); ?>" class="tgg-btn-primary">
                            <?php echo esc_html($cta_button_text); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($cta_phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $cta_phone)); ?>" class="tgg-footer-cta__phone">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            <?php echo esc_html($cta_phone); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

