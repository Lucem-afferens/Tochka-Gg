# Структура проекта "Точка Gg" - WordPress Theme

## 🎯 Общий подход

- **WordPress Theme** - основная тема для сайта
- **Advanced Custom Fields (ACF Pro)** - управление всем контентом через админ-панель
- **Custom Post Types** - только для турниров и новостей (динамический контент)
- **Vite** - сборка фронтенд ресурсов (SASS → CSS, JS модули)
- **Все остальное через ACF** - цены, оборудование, контакты и т.д.

---

## 📁 WordPress Theme Structure

```
tochkagg-theme/
│
├── style.css (заголовок темы - обязательный)
├── functions.php (инициализация, подключение модулей)
├── screenshot.png (превью темы в админке)
│
├── index.php (fallback шаблон)
├── header.php (шапка сайта)
├── footer.php (подвал сайта)
├── sidebar.php (если нужен)
│
├── front-page.php (главная страница)
├── page.php (шаблон страницы)
├── single.php (шаблон записи)
├── single-tournament.php (шаблон турнира)
├── single-news.php (шаблон новости)
├── archive.php (архив записей)
├── archive-tournament.php (архив турниров)
├── archive-news.php (архив новостей)
├── 404.php (страница 404)
├── search.php (результаты поиска)
│
├── templates/ (кастомные шаблоны страниц)
│   ├── template-pricing.php (страница цен)
│   ├── template-equipment.php (страница оборудования)
│   ├── template-contacts.php (страница контактов)
│   └── template-homepage.php (кастомная главная, если нужно)
│
├── template-parts/ (переиспользуемые компоненты)
│   ├── content/
│   │   ├── content-none.php (нет контента)
│   │   ├── content-page.php (контент страницы)
│   │   ├── content-single.php (контент записи)
│   │   ├── content-tournament.php (контент турнира)
│   │   └── content-news.php (контент новости)
│   │
│   ├── components/ (компоненты секций)
│   │   ├── hero-section.php
│   │   ├── about-section.php
│   │   ├── advantages-section.php
│   │   ├── services-section.php
│   │   ├── equipment-section.php
│   │   ├── pricing-table.php
│   │   ├── contacts-section.php
│   │   ├── vr-section.php
│   │   ├── gallery-section.php
│   │   └── tournaments-preview.php
│   │
│   └── sections/ (части шаблонов)
│       ├── header-nav.php
│       ├── header-mobile-menu.php
│       └── footer-widgets.php
│
├── assets/ (компилированные ресурсы - результат сборки Vite)
│   ├── css/
│   │   └── style.css (компилированный из src/sass/)
│   ├── js/
│   │   └── main.js (скомпилированный из src/js/)
│   ├── images/ (статические изображения)
│   │   ├── logo.svg
│   │   └── favicon/
│   └── fonts/ (локальные шрифты)
│       ├── orbitron/
│       ├── inter/
│       └── roboto-mono/
│
├── src/ (исходные файлы для сборки Vite)
│   ├── sass/
│   │   ├── style.scss (главный файл)
│   │   ├── base/
│   │   │   ├── _reset.scss
│   │   │   ├── _variables.scss (цвета, типографика из дизайн-системы)
│   │   │   ├── _mixins.scss
│   │   │   └── _typography.scss
│   │   ├── components/
│   │   │   ├── _buttons.scss
│   │   │   ├── _cards.scss
│   │   │   ├── _modal.scss
│   │   │   └── _forms.scss
│   │   ├── sections/
│   │   │   ├── _header.scss
│   │   │   ├── _hero.scss
│   │   │   ├── _about.scss
│   │   │   ├── _pricing.scss
│   │   │   ├── _equipment.scss
│   │   │   ├── _contacts.scss
│   │   │   └── _footer.scss
│   │   └── utilities/
│   │       ├── _helpers.scss
│   │       └── _animations.scss
│   │
│   └── js/
│       ├── main.js (главный файл)
│       └── modules/
│           ├── navigation.js
│           ├── slider.js
│           ├── modal.js
│           ├── forms.js
│           ├── animations.js
│           └── scroll-effects.js
│
├── inc/ (вспомогательные PHP файлы - модульная структура)
│   ├── theme-setup.php (настройка темы, поддержка функций)
│   ├── enqueue-assets.php (подключение CSS/JS)
│   ├── custom-post-types.php (Custom Post Types: tournaments, news)
│   ├── acf-fields.php (настройка ACF - только если нужно программно)
│   ├── theme-functions.php (кастомные функции темы)
│   ├── theme-security.php (функции безопасности)
│   └── theme-helpers.php (вспомогательные функции)
│
└── languages/ (переводы, если будут)
    └── tochkagg-theme.pot
```

---

## 📄 Ключевые файлы

### style.css

```css
/*
Theme Name: Tochka Gg
Theme URI: https://kungur-tochkagg.ru
Author: Николай Д.
Author URI: https://develonik.ru
Description: Премиальный компьютерный клуб "Точка Gg" в Кунгуре. 
Кастомная WordPress тема с полным управлением контентом через ACF Pro.
Версия: 1.0.0
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 8.1
License: Proprietary
License URI: 
Text Domain: tochkagg
Version: 1.0.0
*/
```

### functions.php

```php
<?php
/**
 * Tochka Gg Theme Functions
 * 
 * @package Tochkagg_Theme
 */

// Защита от прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

// Константы темы
define('TOCHKAGG_THEME_VERSION', '1.0.0');
define('TOCHKAGG_THEME_PATH', get_template_directory());
define('TOCHKAGG_THEME_URI', get_template_directory_uri());

// Подключение модулей темы
require_once TOCHKAGG_THEME_PATH . '/inc/theme-setup.php';
require_once TOCHKAGG_THEME_PATH . '/inc/enqueue-assets.php';
require_once TOCHKAGG_THEME_PATH . '/inc/custom-post-types.php';
require_once TOCHKAGG_THEME_PATH . '/inc/theme-functions.php';
require_once TOCHKAGG_THEME_PATH . '/inc/theme-security.php';
require_once TOCHKAGG_THEME_PATH . '/inc/theme-helpers.php';
```

### header.php

```php
<?php
/**
 * Header Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="tgg-header">
    <div class="tgg-container">
        <div class="tgg-header__logo">
            <?php
            $logo = get_field('logo', 'option');
            if ($logo) :
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url($logo['url']); ?>" 
                         alt="<?php echo esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>">
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo esc_html(get_bloginfo('name')); ?>
                </a>
            <?php endif; ?>
        </div>

        <nav class="tgg-header__nav" role="navigation" aria-label="<?php esc_attr_e('Главное меню', 'tochkagg'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'main_menu',
                'container' => false,
                'menu_class' => 'tgg-nav__list',
                'fallback_cb' => false,
            ]);
            ?>
        </nav>

        <button class="tgg-header__burger" aria-label="<?php esc_attr_e('Открыть меню', 'tochkagg'); ?>">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
```

### footer.php

```php
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

            <?php if ($social_links) : ?>
                <div class="tgg-footer__social">
                    <?php foreach ($social_links as $social) : ?>
                        <a href="<?php echo esc_url($social['url']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           aria-label="<?php echo esc_attr($social['platform_name']); ?>">
                            <?php if ($social['icon']) : ?>
                                <img src="<?php echo esc_url($social['icon']['url']); ?>" 
                                     alt="<?php echo esc_attr($social['icon']['alt']); ?>">
                            <?php endif; ?>
                        </a>
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
```

### front-page.php

```php
<?php
/**
 * Front Page Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main">
    <?php
    // Hero секция
    get_template_part('template-parts/components/hero-section');

    // О клубе
    if (get_field('about_section_enabled')) {
        get_template_part('template-parts/components/about-section');
    }

    // Преимущества
    if (get_field('advantages_section_enabled')) {
        get_template_part('template-parts/components/advantages-section');
    }

    // Услуги
    if (get_field('services_section_enabled')) {
        get_template_part('template-parts/components/services-section');
    }

    // Оборудование (краткий обзор)
    if (get_field('equipment_preview_enabled')) {
        get_template_part('template-parts/components/equipment-section');
    }

    // Ближайшие турниры
    if (get_field('tournaments_preview_enabled')) {
        get_template_part('template-parts/components/tournaments-preview');
    }

    // CTA секция
    if (get_field('cta_section_enabled')) {
        get_template_part('template-parts/components/footer-cta');
    }
    ?>
</main>

<?php get_footer(); ?>
```

---

## 🎮 Custom Post Types (только для динамического контента)

### inc/custom-post-types.php

```php
<?php
/**
 * Custom Post Types
 * 
 * Только для контента, который добавляется регулярно:
 * - Турниры
 * - Новости
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Регистрация Custom Post Type: Турниры
 */
function tochkagg_register_tournament_post_type() {
    $labels = [
        'name' => __('Турниры', 'tochkagg'),
        'singular_name' => __('Турнир', 'tochkagg'),
        'menu_name' => __('Турниры', 'tochkagg'),
        'add_new' => __('Добавить турнир', 'tochkagg'),
        'add_new_item' => __('Добавить новый турнир', 'tochkagg'),
        'edit_item' => __('Редактировать турнир', 'tochkagg'),
        'new_item' => __('Новый турнир', 'tochkagg'),
        'view_item' => __('Просмотреть турнир', 'tochkagg'),
        'search_items' => __('Поиск турниров', 'tochkagg'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'tournaments'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-awards',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true, // Поддержка Gutenberg
    ];

    register_post_type('tournament', $args);
}
add_action('init', 'tochkagg_register_tournament_post_type');

/**
 * Регистрация Custom Post Type: Новости
 */
function tochkagg_register_news_post_type() {
    $labels = [
        'name' => __('Новости', 'tochkagg'),
        'singular_name' => __('Новость', 'tochkagg'),
        'menu_name' => __('Новости', 'tochkagg'),
        'add_new' => __('Добавить новость', 'tochkagg'),
        'add_new_item' => __('Добавить новую новость', 'tochkagg'),
        'edit_item' => __('Редактировать новость', 'tochkagg'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'news'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'author'],
        'show_in_rest' => true,
    ];

    register_post_type('news', $args);
}
add_action('init', 'tochkagg_register_news_post_type');
```

---

## 📋 Управление контентом через ACF

### Основной принцип: Все изменяемые данные через ACF

**Не создаем Custom Post Types для:**
- ❌ Тарифов (используем ACF Repeater на странице цен)
- ❌ Оборудования (используем ACF Repeater на странице оборудования)
- ❌ Меню еды (используем ACF Repeater)
- ❌ PC конфигураций (используем ACF Repeater)
- ❌ VR игр (используем ACF Fields)

**Создаем Custom Post Types только для:**
- ✅ Турниров (регулярно добавляются новые)
- ✅ Новостей (регулярно добавляются новые)

### Структура ACF полей

#### Options Page (глобальные настройки)

```
theme_options/
├── contact_info/
│   ├── phone_main
│   ├── phone_vr
│   ├── email
│   ├── address_full
│   ├── map_coordinates (group)
│   │   ├── latitude
│   │   └── longitude
│   └── working_hours
├── social_networks/
│   └── social_links (repeater)
│       ├── platform_name
│       ├── url
│       └── icon
├── logos/
│   ├── logo (header)
│   └── footer_logo
└── copyright_text
```

#### Главная страница (front-page.php)

```
homepage/
├── hero_section/
│   ├── title
│   ├── subtitle
│   ├── description
│   ├── background_image
│   └── cta_button
├── about_section_enabled (true/false)
├── about_section/
│   ├── title
│   ├── description
│   └── image
├── advantages_section_enabled
└── advantages/
    └── items (repeater)
        ├── icon
        ├── title
        └── description
```

#### Страница цен (template-pricing.php)

```
pricing_page/
├── page_title
├── page_description
└── price_table/
    └── packages (repeater)
        ├── package_name
        ├── time_period
        ├── lite_weekday_price
        ├── lite_weekend_price
        ├── vip_weekday_price
        └── vip_weekend_price
```

#### Страница оборудования (template-equipment.php)

```
equipment_page/
├── pc_section/
│   └── pc_types (repeater)
│       ├── category (select: LITE/VIP)
│       ├── quantity
│       ├── video_card
│       ├── processor
│       ├── ram
│       ├── description
│       └── image
└── peripherals/
    └── items (repeater)
        ├── type (select)
        ├── model
        ├── description
        └── image
```

---

## 🔧 Vite Configuration

### vite.config.mjs (в корне темы)

```javascript
import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  root: resolve(__dirname, 'src'),
  build: {
    outDir: resolve(__dirname, 'assets'),
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return 'assets/[name]-[hash][extname]';
        },
      },
    },
    cssCodeSplit: false,
  },
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
      },
    },
  },
});
```

---

## 📦 Package.json для темы

```json
{
  "name": "tochkagg-theme",
  "version": "1.0.0",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "watch": "vite build --watch"
  },
  "devDependencies": {
    "sass": "^1.90.0",
    "vite": "^7.0.6"
  },
  "dependencies": {
    "swiper": "^11.2.10"
  }
}
```

---

## 🎯 Ключевые принципы структуры

### ✅ Правильно

1. **Модульная структура** - все разделено на логические файлы
2. **Разделение исходников и компилированных файлов** - src/ и assets/
3. **ACF для управления контентом** - не Custom Post Types для всего
4. **Custom Post Types только для динамического контента** - турниры, новости
5. **Защита файлов** - проверка ABSPATH во всех PHP файлах
6. **Безопасность** - экранирование всех данных
7. **Template Parts** - переиспользуемые компоненты

### ❌ Неправильно

1. ~~Отдельный плагин для всех Custom Post Types~~ - только в теме, только для турниров/новостей
2. ~~Хардкод контента в шаблонах~~ - все через ACF
3. ~~Все в одном файле functions.php~~ - модульная структура
4. ~~Отсутствие защиты файлов~~ - всегда проверка ABSPATH

---

## 📝 Следующие шаги

1. ✅ Структура готова
2. ⏳ Создать базовые файлы темы
3. ⏳ Настроить ACF поля
4. ⏳ Настроить Vite для сборки
5. ⏳ Создать первые шаблоны

---

**Версия:** 1.0.0  
**Дата:** 2025  
**Статус:** Готово к реализации


