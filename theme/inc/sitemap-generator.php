<?php
/**
 * Dynamic XML Sitemap Generator
 * 
 * Генерация динамического XML sitemap для поисковых систем
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Регистрация rewrite rule для sitemap.xml
 */
function tochkagg_add_sitemap_rewrite_rule() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?tochkagg_sitemap=1', 'top');
}
add_action('init', 'tochkagg_add_sitemap_rewrite_rule');

/**
 * Добавление query var для sitemap
 */
function tochkagg_add_sitemap_query_var($vars) {
    $vars[] = 'tochkagg_sitemap';
    return $vars;
}
add_filter('query_vars', 'tochkagg_add_sitemap_query_var');

/**
 * Генерация XML sitemap
 */
function tochkagg_generate_sitemap() {
    // Проверяем через query var (для rewrite rule)
    $is_sitemap = get_query_var('tochkagg_sitemap');
    
    // Проверяем через REQUEST_URI (для прямого доступа к /sitemap.xml)
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $is_sitemap_uri = (strpos($request_uri, '/sitemap.xml') !== false);
    
    // Также проверяем через GET параметр (для обратной совместимости)
    $is_sitemap_get = (isset($_GET['sitemap']) && $_GET['sitemap'] === 'xml');
    
    if (!$is_sitemap && !$is_sitemap_uri && !$is_sitemap_get) {
        return;
    }
    
    // Устанавливаем заголовки
    status_header(200);
    header('Content-Type: application/xml; charset=utf-8');
    nocache_headers();
    
    $site_url = home_url('/');
    $lastmod = date('c');
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
    echo '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
    echo '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
    echo '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
    
    // Главная страница
    echo '  <url>' . "\n";
    echo '    <loc>' . esc_url($site_url) . '</loc>' . "\n";
    echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
    echo '    <changefreq>daily</changefreq>' . "\n";
    echo '    <priority>1.0</priority>' . "\n";
    echo '  </url>' . "\n";
    
    // Обычные страницы
    $pages = get_pages([
        'post_status' => 'publish',
        'number' => 500,
        'sort_column' => 'post_date',
        'sort_order' => 'DESC'
    ]);
    
    foreach ($pages as $page) {
        $page_url = get_permalink($page->ID);
        $page_modified = get_the_modified_date('c', $page->ID);
        
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url($page_url) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html($page_modified) . '</lastmod>' . "\n";
        echo '    <changefreq>weekly</changefreq>' . "\n";
        echo '    <priority>0.8</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    // Новости
    $news_query = new WP_Query([
        'post_type' => 'news',
        'posts_per_page' => 500,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
    
    if ($news_query->have_posts()) {
        while ($news_query->have_posts()) {
            $news_query->the_post();
            $news_url = get_permalink();
            $news_modified = get_the_modified_date('c');
            
            echo '  <url>' . "\n";
            echo '    <loc>' . esc_url($news_url) . '</loc>' . "\n";
            echo '    <lastmod>' . esc_html($news_modified) . '</lastmod>' . "\n";
            echo '    <changefreq>weekly</changefreq>' . "\n";
            echo '    <priority>0.7</priority>' . "\n";
            echo '  </url>' . "\n";
        }
        wp_reset_postdata();
    }
    
    // Архив новостей
    $news_archive = get_post_type_archive_link('news');
    if ($news_archive) {
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url($news_archive) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
        echo '    <changefreq>daily</changefreq>' . "\n";
        echo '    <priority>0.6</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    // Турниры
    $tournaments_query = new WP_Query([
        'post_type' => 'tournament',
        'posts_per_page' => 500,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
    
    if ($tournaments_query->have_posts()) {
        while ($tournaments_query->have_posts()) {
            $tournaments_query->the_post();
            $tournament_url = get_permalink();
            $tournament_modified = get_the_modified_date('c');
            
            echo '  <url>' . "\n";
            echo '    <loc>' . esc_url($tournament_url) . '</loc>' . "\n";
            echo '    <lastmod>' . esc_html($tournament_modified) . '</lastmod>' . "\n";
            echo '    <changefreq>weekly</changefreq>' . "\n";
            echo '    <priority>0.7</priority>' . "\n";
            echo '  </url>' . "\n";
        }
        wp_reset_postdata();
    }
    
    // Архив турниров
    $tournaments_archive = get_post_type_archive_link('tournament');
    if ($tournaments_archive) {
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url($tournaments_archive) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html($lastmod) . '</lastmod>' . "\n";
        echo '    <changefreq>daily</changefreq>' . "\n";
        echo '    <priority>0.6</priority>' . "\n";
        echo '  </url>' . "\n";
    }
    
    echo '</urlset>';
    exit;
}
// Используем более ранний хук для перехвата запроса
add_action('template_redirect', 'tochkagg_generate_sitemap', 1);

