<?php
/**
 * Footer Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$phone = get_field('phone_main', 'option');
$address = get_field('address_full', 'option');
$social_links = get_field('social_networks', 'option');
?>
<footer class="tgg-footer">
    <div class="tgg-container">
        <div class="tgg-footer__content">
            <div class="tgg-footer__logo">
                <?php
                $footer_logo = get_field('footer_logo', 'option');
                if ($footer_logo) :
                    ?>
                    <img src="<?php echo esc_url($footer_logo['url']); ?>" 
                         alt="<?php echo esc_attr($footer_logo['alt'] ?: get_bloginfo('name')); ?>">
                <?php else : ?>
                    <span><?php echo esc_html(get_bloginfo('name')); ?></span>
                <?php endif; ?>
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
               <?php echo esc_html(get_field('copyright_text', 'option') ?: 'ИП Морозов Алексей Алексеевич'); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

