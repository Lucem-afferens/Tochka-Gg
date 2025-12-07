<?php
/**
 * Footer Template - Максимально полная версия
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем данные из ACF с значениями по умолчанию
$phone = (function_exists('get_field') ? get_field('phone_main', 'option') : null) ?: '8 (992) 222-62-72';
$email = (function_exists('get_field') ? get_field('email_main', 'option') : null) ?: 'vr.kungur@mail.ru';
$telegram = (function_exists('get_field') ? get_field('telegram_username', 'option') : null) ?: '@tochaGgKungur';
$address = (function_exists('get_field') ? get_field('address_full', 'option') : null) ?: 'Пермский край, г. Кунгур, ул. Голованова, 43, вход с торца здания, цокольный этаж';
$map_lat = (function_exists('get_field') ? get_field('map_latitude', 'option') : null) ?: '57.424953';
$map_lng = (function_exists('get_field') ? get_field('map_longitude', 'option') : null) ?: '56.963968';
$working_hours = (function_exists('get_field') ? get_field('working_hours', 'option') : null) ?: 'Круглосуточно, без выходных';
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

// Информационные ссылки - ищем страницы по шаблонам или slug
$footer_info_links = [];

// Функция для поиска страницы по шаблону
function tochkagg_find_page_by_template($template_name) {
    $pages = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => $template_name,
        'post_status' => 'publish',
        'number' => 1,
    ]);
    return !empty($pages) ? $pages[0] : null;
}

// Функция для поиска страницы по slug или названию
function tochkagg_find_page_by_slugs($slugs, $title) {
    foreach ($slugs as $slug) {
        $page = get_page_by_path($slug);
        if ($page && $page->post_status === 'publish') {
            return $page;
        }
    }
    
    // Поиск по названию
    $pages = get_pages([
        'post_status' => 'publish',
        'number' => 50,
    ]);
    
    foreach ($pages as $page) {
        if (trim($page->post_title) === trim($title)) {
            return $page;
        }
    }
    
    return null;
}

// Политика конфиденциальности
$privacy_page = tochkagg_find_page_by_template('template-privacy.php') 
    ?: tochkagg_find_page_by_slugs(['privacy', 'политика-конфиденциальности', 'политика'], 'Политика конфиденциальности');
if ($privacy_page) {
    $footer_info_links[] = [
        'title' => 'Политика конфиденциальности',
        'url' => get_permalink($privacy_page->ID),
    ];
}

// Пользовательское соглашение
$terms_page = tochkagg_find_page_by_template('template-terms.php')
    ?: tochkagg_find_page_by_slugs(['terms', 'пользовательское-соглашение', 'соглашение'], 'Пользовательское соглашение');
if ($terms_page) {
    $footer_info_links[] = [
        'title' => 'Пользовательское соглашение',
        'url' => get_permalink($terms_page->ID),
    ];
}

// Правила клуба
$rules_page = tochkagg_find_page_by_template('template-rules.php')
    ?: tochkagg_find_page_by_slugs(['rules', 'правила-клуба', 'правила'], 'Правила клуба');
if ($rules_page) {
    $footer_info_links[] = [
        'title' => 'Правила клуба',
        'url' => get_permalink($rules_page->ID),
    ];
}

// FAQ
$faq_page = tochkagg_find_page_by_template('template-faq.php')
    ?: tochkagg_find_page_by_slugs(['faq', 'частые-вопросы', 'вопросы', 'часто-задаваемые-вопросы'], 'FAQ');
if ($faq_page) {
    $footer_info_links[] = [
        'title' => 'FAQ',
        'url' => get_permalink($faq_page->ID),
    ];
}

// Если страницы не найдены, используем заглушки
if (empty($footer_info_links)) {
    $footer_info_links = [
        ['title' => 'Политика конфиденциальности', 'url' => '#privacy'],
        ['title' => 'Пользовательское соглашение', 'url' => '#terms'],
        ['title' => 'Правила клуба', 'url' => '#rules'],
        ['title' => 'FAQ', 'url' => '#faq'],
    ];
}
?>
<footer class="tgg-footer">
    <div class="tgg-container">
        <!-- Основной контент футера -->
        <div class="tgg-footer__content">
            <!-- Колонка 1: О компании -->
            <div class="tgg-footer__col tgg-footer__col--about">
                <div class="tgg-footer__logo">
                    <?php 
                    // Всегда показываем логотип (из ACF или placeholder)
                    if ($footer_logo) {
                        $footer_logo_data = tochkagg_get_image_or_placeholder($footer_logo, 200, 60, 'Footer Logo');
                    } else {
                        // Создаем placeholder логотипа, если нет загруженного
                        $footer_logo_data = function_exists('tochkagg_get_placeholder_image') 
                            ? ['url' => tochkagg_get_placeholder_image(200, 60, 'Логотип', '1a1d29', '3b82f6'), 'alt' => 'Логотип Точка Gg']
                            : ['url' => 'https://placehold.co/200x60/1a1d29/3b82f6?text=Логотип', 'alt' => 'Логотип Точка Gg'];
                    }
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="tgg-footer__logo-link">
                        <img src="<?php echo esc_url($footer_logo_data['url']); ?>" 
                             alt="<?php echo esc_attr($footer_logo_data['alt']); ?>"
                             width="200"
                             height="60"
                             loading="lazy">
                    </a>
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
            </div>
            
            <!-- Колонка 2: Информация -->
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
            
            <!-- Колонка 3: Контакты -->
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
                    
                    <?php if ($telegram) : ?>
                        <div class="tgg-footer__contact-item">
                            <span class="tgg-footer__contact-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.13-.31-1.09-.66.02-.18.27-.37.74-.56 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/>
                                </svg>
                            </span>
                            <a href="https://t.me/<?php echo esc_attr(ltrim($telegram, '@')); ?>" target="_blank" rel="noopener noreferrer" class="tgg-footer__contact-link">
                                Telegram-канал: <?php echo esc_html($telegram); ?>
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
                            <a href="https://yandex.ru/maps/?pt=<?php echo esc_attr($map_lng); ?>,<?php echo esc_attr($map_lat); ?>&z=17&l=map" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="tgg-footer__contact-link">
                                <?php echo esc_html($address); ?>
                            </a>
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

<?php
// Модальное окно VR арены (не показываем на странице VR арены)
if (!is_page_template('template-vr.php') && locate_template('template-parts/components/vr-modal.php')) {
    get_template_part('template-parts/components/vr-modal');
}
?>

<?php wp_footer(); ?>
</body>
</html>