<?php
/**
 * SEO Functions
 * 
 * Функции для SEO-оптимизации сайта
 * - Schema.org разметка (JSON-LD)
 * - Open Graph мета-теги
 * - Twitter Card
 * - Canonical URL
 * - Meta description
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================
 * SCHEMA.ORG РАЗМЕТКА (JSON-LD)
 * ============================================
 */

/**
 * Добавление Schema.org разметки Organization
 */
function tochkagg_schema_organization() {
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $logo = function_exists('get_field') ? get_field('logo', 'option') : false;
    $logo_url = $logo && isset($logo['url']) ? $logo['url'] : '';
    
    $phone = (function_exists('get_field') ? get_field('phone_main', 'option') : null) ?: '8 (992) 222-62-72';
    $email = (function_exists('get_field') ? get_field('email_main', 'option') : null) ?: 'vr.kungur@mail.ru';
    $address = (function_exists('get_field') ? get_field('address_full', 'option') : null) ?: 'Пермский край, г. Кунгур, ул. Голованова, 43, вход с торца здания, цокольный этаж';
    $map_lat = (function_exists('get_field') ? get_field('map_latitude', 'option') : null) ?: '57.424953';
    $map_lng = (function_exists('get_field') ? get_field('map_longitude', 'option') : null) ?: '56.963968';
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => $site_name,
        'url' => $site_url,
        'logo' => $logo_url ?: $site_url . 'logo.png',
        'image' => $logo_url ?: $site_url . 'logo.png',
        'description' => get_bloginfo('description') ?: 'Премиальный компьютерный клуб нового поколения в Кунгуре. Мощное оборудование, комфортная атмосфера и незабываемый игровой опыт.',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
            'addressLocality' => 'Кунгур',
            'addressRegion' => 'Пермский край',
            'addressCountry' => 'RU'
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => $map_lat,
            'longitude' => $map_lng
        ],
        'telephone' => preg_replace('/[^0-9+]/', '', $phone),
        'email' => $email,
        'priceRange' => '$$',
        'openingHours' => 'Mo-Su 00:00-23:59',
        'sameAs' => []
    ];
    
    // Добавляем социальные сети
    $social_links = function_exists('get_field') ? get_field('social_networks', 'option') : false;
    if ($social_links && is_array($social_links)) {
        foreach ($social_links as $social) {
            if (isset($social['url']) && !empty($social['url'])) {
                $schema['sameAs'][] = $social['url'];
            }
        }
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'tochkagg_schema_organization', 5);

/**
 * Добавление Schema.org разметки Article для постов
 */
function tochkagg_schema_article() {
    if (!is_singular('post') && !is_singular('news') && !is_singular('tournament')) {
        return;
    }
    
    global $post;
    if (!$post) {
        return;
    }
    
    $author = get_the_author_meta('display_name', $post->post_author);
    $published_date = get_the_date('c', $post->ID);
    $modified_date = get_the_modified_date('c', $post->ID);
    $image_url = '';
    
    if (has_post_thumbnail($post->ID)) {
        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        $image_url = $thumbnail ? $thumbnail[0] : '';
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title($post->ID),
        'description' => wp_trim_words(get_the_excerpt($post->ID) ?: get_the_content($post->ID), 30, '...'),
        'author' => [
            '@type' => 'Person',
            'name' => $author
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => function_exists('get_field') && get_field('logo', 'option') 
                    ? get_field('logo', 'option')['url'] 
                    : home_url('/logo.png')
            ]
        ],
        'datePublished' => $published_date,
        'dateModified' => $modified_date,
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => get_permalink($post->ID)
        ]
    ];
    
    if ($image_url) {
        $schema['image'] = [
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => 1200,
            'height' => 630
        ];
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'tochkagg_schema_article', 6);

/**
 * ============================================
 * OPEN GRAPH И TWITTER CARD
 * ============================================
 */

/**
 * Добавление Open Graph и Twitter Card мета-тегов
 */
function tochkagg_og_meta_tags() {
    global $post;
    
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $site_description = get_bloginfo('description') ?: 'Премиальный компьютерный клуб нового поколения в Кунгуре';
    
    // Определяем тип контента
    if (is_singular()) {
        $title = get_the_title();
        $description = wp_trim_words(get_the_excerpt() ?: get_the_content(), 30, '...');
        $url = get_permalink();
        $image = '';
        
        if (has_post_thumbnail()) {
            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            $image = $thumbnail ? $thumbnail[0] : '';
        }
        
        $type = is_singular('post') || is_singular('news') || is_singular('tournament') ? 'article' : 'website';
    } elseif (is_home() || is_front_page()) {
        $title = $site_name;
        $description = $site_description;
        $url = $site_url;
        $image = function_exists('get_field') && get_field('logo', 'option') 
            ? get_field('logo', 'option')['url'] 
            : '';
        $type = 'website';
    } else {
        $title = wp_get_document_title();
        $description = $site_description;
        $url = get_permalink();
        $image = '';
        $type = 'website';
    }
    
    // Если нет изображения, используем логотип
    if (empty($image)) {
        $logo = function_exists('get_field') ? get_field('logo', 'option') : false;
        $image = $logo && isset($logo['url']) ? $logo['url'] : '';
    }
    
    // Open Graph
    echo '<meta property="og:type" content="' . esc_attr($type) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    if ($image) {
        echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
        echo '<meta property="og:image:width" content="1200">' . "\n";
        echo '<meta property="og:image:height" content="630">' . "\n";
        echo '<meta property="og:image:alt" content="' . esc_attr($title) . '">' . "\n";
    }
    echo '<meta property="og:locale" content="ru_RU">' . "\n";
    
    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    if ($image) {
        echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
    }
}
add_action('wp_head', 'tochkagg_og_meta_tags', 7);

/**
 * ============================================
 * CANONICAL URL
 * ============================================
 */

/**
 * Добавление Canonical URL
 */
function tochkagg_canonical_url() {
    $canonical = '';
    
    if (is_singular()) {
        $canonical = get_permalink();
    } elseif (is_home() || is_front_page()) {
        $canonical = home_url('/');
    } elseif (is_category()) {
        $canonical = get_category_link(get_queried_object_id());
    } elseif (is_tag()) {
        $canonical = get_tag_link(get_queried_object_id());
    } elseif (is_tax()) {
        $canonical = get_term_link(get_queried_object());
    } elseif (is_post_type_archive()) {
        $canonical = get_post_type_archive_link(get_post_type());
    } elseif (is_author()) {
        $canonical = get_author_posts_url(get_queried_object_id());
    } elseif (is_search()) {
        $canonical = home_url('/?s=' . urlencode(get_query_var('s')));
    } elseif (is_404()) {
        return; // Не добавляем canonical для 404
    } else {
        $canonical = home_url(add_query_arg(null, null));
    }
    
    // Убираем параметры из URL (кроме необходимых)
    $canonical = remove_query_arg(['page', 'paged', 'preview', 'replytocom'], $canonical);
    
    if ($canonical) {
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }
}
add_action('wp_head', 'tochkagg_canonical_url', 8);

/**
 * ============================================
 * META DESCRIPTION
 * ============================================
 */

/**
 * Добавление Meta Description (если не установлен через плагин)
 */
function tochkagg_meta_description() {
    // Проверяем, не установлен ли уже meta description через плагин (Yoast, Rank Math и т.д.)
    if (has_action('wp_head', 'wpseo_head') || 
        has_action('wp_head', 'rank_math_head') ||
        has_action('wp_head', 'aioseo_head')) {
        return; // Плагин SEO уже обрабатывает meta description
    }
    
    $description = '';
    
    if (is_singular()) {
        $description = get_the_excerpt();
        if (empty($description)) {
            $description = wp_trim_words(get_the_content(), 30, '...');
        }
    } elseif (is_home() || is_front_page()) {
        $description = get_bloginfo('description');
    } elseif (is_category() || is_tag() || is_tax()) {
        $description = term_description();
    } elseif (is_post_type_archive()) {
        $description = get_the_archive_description();
    }
    
    if (empty($description)) {
        $description = get_bloginfo('description') ?: 'Премиальный компьютерный клуб нового поколения в Кунгуре';
    }
    
    $description = wp_strip_all_tags($description);
    $description = wp_trim_words($description, 30, '...');
    
    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }
}
add_action('wp_head', 'tochkagg_meta_description', 9);

/**
 * ============================================
 * ДОПОЛНИТЕЛЬНЫЕ SEO МЕТА-ТЕГИ
 * ============================================
 */

/**
 * Добавление дополнительных SEO мета-тегов
 */
function tochkagg_additional_seo_tags() {
    // Robots meta
    if (is_singular() && get_post_meta(get_the_ID(), '_tochkagg_noindex', true)) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    } elseif (is_search() || is_404()) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
    
    // Author
    if (is_singular() && get_the_author_meta('display_name')) {
        echo '<meta name="author" content="' . esc_attr(get_the_author_meta('display_name')) . '">' . "\n";
    }
    
    // Geo tags (для локального бизнеса)
    $map_lat = (function_exists('get_field') ? get_field('map_latitude', 'option') : null) ?: '57.424953';
    $map_lng = (function_exists('get_field') ? get_field('map_longitude', 'option') : null) ?: '56.963968';
    if ($map_lat && $map_lng) {
        echo '<meta name="geo.region" content="RU-PER">' . "\n";
        echo '<meta name="geo.placename" content="Кунгур">' . "\n";
        echo '<meta name="geo.position" content="' . esc_attr($map_lat) . ';' . esc_attr($map_lng) . '">' . "\n";
        echo '<meta name="ICBM" content="' . esc_attr($map_lat) . ', ' . esc_attr($map_lng) . '">' . "\n";
    }
}
add_action('wp_head', 'tochkagg_additional_seo_tags', 10);

