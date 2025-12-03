<?php
/**
 * Footer Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$phone = function_exists('get_field') ? get_field('phone_main', 'option') : false;
$address = function_exists('get_field') ? get_field('address_full', 'option') : false;
$social_links = function_exists('get_field') ? get_field('social_networks', 'option') : false;
?>
<footer class="tgg-footer">
    <div class="tgg-container">
        <div class="tgg-footer__content">
            <div class="tgg-footer__logo">
                <?php
                $footer_logo = function_exists('get_field') ? get_field('footer_logo', 'option') : false;
                $footer_logo_data = tochkagg_get_image_or_placeholder($footer_logo, 200, 60, 'Footer Logo');
                ?>
                <img src="<?php echo esc_url($footer_logo_data['url']); ?>" 
                     alt="<?php echo esc_attr($footer_logo_data['alt']); ?>">
            </div>

            <?php if ($phone) : ?>
                <div class="tgg-footer__phone">
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($address) : ?>
                <address class="tgg-footer__address">
                    <?php echo esc_html($address); ?>
                </address>
            <?php endif; ?>

            <?php if ($social_links && is_array($social_links)) : ?>
                <div class="tgg-footer__social">
                    <?php foreach ($social_links as $social) : ?>
                        <?php if (!empty($social['url'])) : ?>
                            <a href="<?php echo esc_url($social['url']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="<?php echo esc_attr($social['platform_name'] ?? 'Социальная сеть'); ?>">
                                <?php if (!empty($social['icon'])) : ?>
                                    <img src="<?php echo esc_url($social['icon']['url']); ?>" 
                                         alt="<?php echo esc_attr($social['icon']['alt'] ?? ''); ?>">
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="tgg-footer__copyright">
            <p>&copy; <?php echo esc_html(date('Y')); ?> 
               <?php 
               $copyright = function_exists('get_field') ? get_field('copyright_text', 'option') : false;
               echo esc_html($copyright ?: 'ИП Морозов Алексей Алексеевич'); 
               ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>


