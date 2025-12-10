<?php
/**
 * Contacts Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$phone = get_field('phone_main', 'option') ?: '8 (992) 222-62-72';
$email = get_field('email_main', 'option') ?: 'vr.kungur@mail.ru';
$telegram = get_field('telegram_username', 'option') ?: '@tochaGgKungur';
$address = get_field('address_full', 'option') ?: 'Пермский край, г. Кунгур, ул. Голованова, 43, вход с торца здания, цокольный этаж';
$working_hours = get_field('working_hours', 'option') ?: 'Круглосуточно, без выходных';
$map_lat = get_field('map_latitude', 'option') ?: '57.424953';
$map_lng = get_field('map_longitude', 'option') ?: '56.963968';

$social_links = get_field('social_networks', 'option');
?>

<section class="tgg-contacts" id="contacts">
    <div class="tgg-container">
        <div class="tgg-contacts__wrapper">
            <div class="tgg-contacts__info">
                <h2 class="tgg-contacts__title">Контакты</h2>
                
                <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
                    <?php get_template_part('template-parts/components/info-notice'); ?>
                <?php endif; ?>
                
                <?php if ($phone) : ?>
                    <div class="tgg-contacts__item">
                        <div class="tgg-contacts__item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                        </div>
                        <div class="tgg-contacts__item-content">
                            <div class="tgg-contacts__item-label">Телефон</div>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="tgg-contacts__item-value">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($email) : ?>
                    <div class="tgg-contacts__item">
                        <div class="tgg-contacts__item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <div class="tgg-contacts__item-content">
                            <div class="tgg-contacts__item-label">Email</div>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="tgg-contacts__item-value">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($telegram) : ?>
                    <div class="tgg-contacts__item">
                        <div class="tgg-contacts__item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                                <path d="M22 2l-7 6"/>
                            </svg>
                        </div>
                        <div class="tgg-contacts__item-content">
                            <div class="tgg-contacts__item-label">Telegram-канал</div>
                            <a href="https://t.me/<?php echo esc_attr(ltrim($telegram, '@')); ?>" target="_blank" rel="noopener noreferrer" class="tgg-contacts__item-value">
                                <?php echo esc_html($telegram); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($address) : ?>
                    <div class="tgg-contacts__item">
                        <div class="tgg-contacts__item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="tgg-contacts__item-content">
                            <div class="tgg-contacts__item-label">Адрес</div>
                            <a href="https://yandex.ru/maps/?pt=<?php echo esc_attr($map_lng); ?>,<?php echo esc_attr($map_lat); ?>&z=17&l=map" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="tgg-contacts__item-value">
                                <?php echo esc_html($address); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($working_hours) : ?>
                    <div class="tgg-contacts__item">
                        <div class="tgg-contacts__item-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div class="tgg-contacts__item-content">
                            <div class="tgg-contacts__item-label">Время работы</div>
                            <div class="tgg-contacts__item-value">
                                <?php echo esc_html($working_hours); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($social_links && is_array($social_links)) : ?>
                    <div class="tgg-contacts__social">
                        <div class="tgg-contacts__item-label">Мы в соцсетях</div>
                        <div class="tgg-contacts__social-links">
                            <?php foreach ($social_links as $social) : ?>
                                <?php if (!empty($social['url'])) : ?>
                                    <a href="<?php echo esc_url($social['url']); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="tgg-contacts__social-link"
                                       aria-label="<?php echo esc_attr($social['platform_name'] ?? 'Социальная сеть'); ?>">
                                        <?php
                                        $social_icon = !empty($social['icon']) && is_array($social['icon']) ? $social['icon'] : null;
                                        $social_icon_data = tochkagg_get_image_or_placeholder($social_icon, 40, 40, ($social['platform_name'] ?? 'Social') . ' Icon');
                                        ?>
                                        <img src="<?php echo esc_url($social_icon_data['url']); ?>" 
                                             alt="<?php echo esc_attr($social_icon_data['alt']); ?>">
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tgg-contacts__map">
                <?php
                // Получаем логотип для маркера карты
                $map_logo = function_exists('get_field') ? get_field('logo', 'option') : false;
                $map_logo_url = '';
                if ($map_logo) {
                    $map_logo_data = function_exists('tochkagg_get_image_or_placeholder') 
                        ? tochkagg_get_image_or_placeholder($map_logo, 80, 80, 'Map Marker Logo')
                        : null;
                    $map_logo_url = $map_logo_data ? $map_logo_data['url'] : '';
                }
                // Если нет логотипа, используем fallback через функцию
                if (!$map_logo_url && function_exists('tochkagg_get_image_or_placeholder')) {
                    $fallback_logo = function_exists('get_field') ? get_field('logo', 'option') : false;
                    if ($fallback_logo) {
                        $fallback_data = tochkagg_get_image_or_placeholder($fallback_logo, 80, 80, 'Logo');
                        $map_logo_url = $fallback_data['url'];
                    }
                }
                ?>
                <div class="tgg-contacts__map-container" 
                     id="map" 
                     data-lat="<?php echo esc_attr($map_lat); ?>" 
                     data-lng="<?php echo esc_attr($map_lng); ?>"
                     data-logo="<?php echo esc_attr($map_logo_url); ?>">
                    <!-- Карта будет инициализирована через JavaScript -->
                    <div class="tgg-contacts__map-placeholder">
                        <p>Карта загружается...</p>
                        <p class="tgg-contacts__map-link">
                            <a href="https://yandex.ru/maps/?pt=<?php echo esc_attr($map_lng); ?>,<?php echo esc_attr($map_lat); ?>&z=17&l=map" 
                               target="_blank" 
                               rel="noopener noreferrer">
                                Открыть карту в новом окне
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


