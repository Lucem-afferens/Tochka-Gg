<?php
/**
 * Footer Template - Максимально полная версия
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем данные из ACF
$phone = function_exists('get_field') ? get_field('phone_main', 'option') : false;
$email = function_exists('get_field') ? get_field('email_main', 'option') : false;
$address = function_exists('get_field') ? get_field('address_full', 'option') : false;
$working_hours = function_exists('get_field') ? get_field('working_hours', 'option') : false;
$social_links = function_exists('get_field') ? get_field('social_networks', 'option') : false;
$footer_logo = function_exists('get_field') ? get_field('footer_logo', 'option') : false;
$copyright = function_exists('get_field') ? get_field('copyright_text', 'option') : false;
$footer_description = function_exists('get_field') ? get_field('footer_description', 'option') : false;

// Формируем навигационное меню
$footer_menu_items = [];
if (has_nav_menu('main_menu')) {
    $menu = wp_get_nav_menu_items('main_menu');
    if ($menu) {
        foreach ($menu as $item) {
            if ($item->menu_item_parent == 0) {
                $footer_menu_items[] = [
                    'title' => $item->title,
                    'url' => $item->url,
                ];
            }
        }
    }
} else {
    if (function_exists('tochkagg_get_page_url')) {
        $pages = [
            'equipment' => 'Оборудование',
            'pricing' => 'Цены',
            'vr' => 'VR арена',
            'bar' => 'Клубный бар',
            'contacts' => 'Контакты',
        ];
        foreach ($pages as $slug => $title) {
            $url = tochkagg_get_page_url($slug);
            if ($url && $url !== '#') {
                $footer_menu_items[] = [
                    'title' => $title,
                    'url' => $url,
                ];
            }
        }
    }
}

// Услуги для футера
$footer_services = [
    ['title' => 'Игровые ПК', 'url' => function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('equipment') : '#'],
    ['title' => 'PlayStation 5', 'url' => function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('equipment') : '#'],
    ['title' => 'VR Арена', 'url' => function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('vr') : '#'],
    ['title' => 'Турниры', 'url' => function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('tournaments') : '#'],
    ['title' => 'Клубный бар', 'url' => function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('bar') : '#'],
    ['title' => 'Бронирование', 'url' => function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('booking') : '#'],
];

// Информационные ссылки
$footer_info_links = [
    ['title' => 'Политика конфиденциальности', 'url' => '#privacy'],
    ['title' => 'Пользовательское соглашение', 'url' => '#terms'],
    ['title' => 'Правила клуба', 'url' => '#rules'],
    ['title' => 'FAQ', 'url' => '#faq'],
];
?>
<footer class="tgg-footer">
    <div class="tgg-container">
        <!-- Основной контент футера -->
        <div class="tgg-footer__content">
            <!-- Колонка 1: О компании -->
            <div class="tgg-footer__col tgg-footer__col--about">
                <div class="tgg-footer__logo">
                    <?php if ($footer_logo) : 
                        $footer_logo_data = tochkagg_get_image_or_placeholder($footer_logo, 200, 60, 'Footer Logo');
                    ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="tgg-footer__logo-link">
                            <img src="<?php echo esc_url($footer_logo_data['url']); ?>" 
                                 alt="<?php echo esc_attr($footer_logo_data['alt']); ?>">
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="tgg-footer__logo-link">
                            <?php echo esc_html(get_bloginfo('name')); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($footer_description) : ?>
                    <p class="tgg-footer__description">
                        <?php echo esc_html($footer_description); ?>
                    </p>
                <?php else : ?>
                    <p class="tgg-footer__description">
                        Премиальный компьютерный клуб нового поколения в Кунгуре. Мощное оборудование, комфортная атмосфера и незабываемый игровой опыт.
                    </p>
                <?php endif; ?>
                
                <?php if ($social_links && is_array($social_links)) : ?>
                    <div class="tgg-footer__social">
                        <span class="tgg-footer__social-label">Мы в соцсетях:</span>
                        <div class="tgg-footer__social-links">
                            <?php foreach ($social_links as $social) : ?>
                                <?php if (!empty($social['url'])) : ?>
                                    <a href="<?php echo esc_url($social['url']); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="tgg-footer__social-link"
                                       aria-label="<?php echo esc_attr($social['platform_name'] ?? 'Социальная сеть'); ?>">
                                        <?php if (!empty($social['icon'])) : ?>
                                            <img src="<?php echo esc_url($social['icon']['url']); ?>" 
                                                 alt="<?php echo esc_attr($social['icon']['alt'] ?? ''); ?>">
                                        <?php else : ?>
                                            <span><?php echo esc_html($social['platform_name'] ?? ''); ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Колонка 2: Навигация -->
            <?php if (!empty($footer_menu_items)) : ?>
                <div class="tgg-footer__col tgg-footer__col--nav">
                    <h3 class="tgg-footer__col-title">Навигация</h3>
                    <nav class="tgg-footer__nav" role="navigation" aria-label="<?php esc_attr_e('Футер меню', 'tochkagg'); ?>">
                        <ul class="tgg-footer__nav-list">
                            <?php foreach ($footer_menu_items as $item) : ?>
                                <li>
                                    <a href="<?php echo esc_url($item['url']); ?>" class="tgg-footer__nav-link">
                                        <?php echo esc_html($item['title']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
            
            <!-- Колонка 3: Услуги -->
            <div class="tgg-footer__col tgg-footer__col--services">
                <h3 class="tgg-footer__col-title">Наши услуги</h3>
                <ul class="tgg-footer__nav-list">
                    <?php foreach ($footer_services as $service) : ?>
                        <?php if (!empty($service['url']) && $service['url'] !== '#') : ?>
                            <li>
                                <a href="<?php echo esc_url($service['url']); ?>" class="tgg-footer__nav-link">
                                    <?php echo esc_html($service['title']); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Колонка 4: Информация -->
            <div class="tgg-footer__col tgg-footer__col--info">
                <h3 class="tgg-footer__col-title">Информация</h3>
                <ul class="tgg-footer__nav-list">
                    <?php foreach ($footer_info_links as $link) : ?>
                        <li>
                            <a href="<?php echo esc_url($link['url']); ?>" class="tgg-footer__nav-link">
                                <?php echo esc_html($link['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Колонка 5: Контакты -->
            <div class="tgg-footer__col tgg-footer__col--contacts">
                <h3 class="tgg-footer__col-title">Контакты</h3>
                <div class="tgg-footer__contacts">
                    <?php if ($phone) : ?>
                        <div class="tgg-footer__contact-item">
                            <span class="tgg-footer__contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </span>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="tgg-footer__contact-link">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($email) : ?>
                        <div class="tgg-footer__contact-item">
                            <span class="tgg-footer__contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </span>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="tgg-footer__contact-link">
                                <?php echo esc_html($email); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($address) : ?>
                        <div class="tgg-footer__contact-item">
                            <span class="tgg-footer__contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </span>
                            <address class="tgg-footer__contact-link">
                                <?php echo esc_html($address); ?>
                            </address>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($working_hours) : ?>
                        <div class="tgg-footer__contact-item">
                            <span class="tgg-footer__contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </span>
                            <div class="tgg-footer__contact-link">
                                <?php echo esc_html($working_hours); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Нижняя часть футера -->
        <div class="tgg-footer__bottom">
            <div class="tgg-footer__bottom-content">
                <!-- Копирайт -->
                <div class="tgg-footer__copyright">
                    <p class="tgg-footer__copyright-main">
                        &copy; <?php echo esc_html(date('Y')); ?> 
                        <?php echo esc_html($copyright ?: 'ИП Морозов Алексей Алексеевич'); ?>
                    </p>
                    <p class="tgg-footer__copyright-secondary">
                        Все права защищены
                    </p>
                </div>
                
                <!-- Способы оплаты (опционально) -->
                <div class="tgg-footer__payment">
                    <span class="tgg-footer__payment-label">Принимаем к оплате:</span>
                    <div class="tgg-footer__payment-methods">
                        <span class="tgg-footer__payment-method" title="Наличные">💵</span>
                        <span class="tgg-footer__payment-method" title="Банковские карты">💳</span>
                        <span class="tgg-footer__payment-method" title="Электронные кошельки">📱</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>