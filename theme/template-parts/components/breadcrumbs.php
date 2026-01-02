<?php
/**
 * Breadcrumbs Component
 * 
 * Хлебные крошки с Schema.org разметкой BreadcrumbList
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Генерация хлебных крошек
 */
function tochkagg_get_breadcrumbs() {
    $breadcrumbs = [];
    $home_url = home_url('/');
    $home_title = get_bloginfo('name');
    
    // Всегда добавляем главную страницу
    $breadcrumbs[] = [
        'name' => $home_title,
        'url' => $home_url
    ];
    
    if (is_front_page() || is_home()) {
        // На главной странице только один элемент
        return $breadcrumbs;
    }
    
    if (is_singular('post') || is_singular('news')) {
        // Для новостей
        $breadcrumbs[] = [
            'name' => 'Новости',
            'url' => get_post_type_archive_link('news') ?: home_url('/news/')
        ];
        $breadcrumbs[] = [
            'name' => get_the_title(),
            'url' => get_permalink()
        ];
    } elseif (is_singular('tournament')) {
        // Для турниров
        $breadcrumbs[] = [
            'name' => 'Турниры',
            'url' => get_post_type_archive_link('tournament') ?: home_url('/tournaments/')
        ];
        $breadcrumbs[] = [
            'name' => get_the_title(),
            'url' => get_permalink()
        ];
    } elseif (is_post_type_archive('news')) {
        // Архив новостей
        $breadcrumbs[] = [
            'name' => 'Новости',
            'url' => get_post_type_archive_link('news') ?: home_url('/news/')
        ];
    } elseif (is_post_type_archive('tournament')) {
        // Архив турниров
        $breadcrumbs[] = [
            'name' => 'Турниры',
            'url' => get_post_type_archive_link('tournament') ?: home_url('/tournaments/')
        ];
    } elseif (is_page()) {
        // Для обычных страниц
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor_id) {
                $breadcrumbs[] = [
                    'name' => get_the_title($ancestor_id),
                    'url' => get_permalink($ancestor_id)
                ];
            }
        }
        $breadcrumbs[] = [
            'name' => get_the_title(),
            'url' => get_permalink()
        ];
    } elseif (is_category()) {
        // Для категорий
        $category = get_queried_object();
        $breadcrumbs[] = [
            'name' => $category->name,
            'url' => get_category_link($category->term_id)
        ];
    } elseif (is_tag()) {
        // Для тегов
        $tag = get_queried_object();
        $breadcrumbs[] = [
            'name' => 'Тег: ' . $tag->name,
            'url' => get_tag_link($tag->term_id)
        ];
    } elseif (is_search()) {
        // Для страницы поиска
        $breadcrumbs[] = [
            'name' => 'Поиск: ' . get_search_query(),
            'url' => get_search_link()
        ];
    } elseif (is_404()) {
        // Для 404
        $breadcrumbs[] = [
            'name' => 'Страница не найдена',
            'url' => ''
        ];
    }
    
    return $breadcrumbs;
}

/**
 * Вывод хлебных крошек
 */
function tochkagg_breadcrumbs() {
    $breadcrumbs = tochkagg_get_breadcrumbs();
    
    if (count($breadcrumbs) <= 1) {
        return; // Не показываем хлебные крошки, если только главная
    }
    
    // Schema.org разметка
    $schema_items = [];
    $position = 1;
    foreach ($breadcrumbs as $crumb) {
        $schema_items[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $crumb['name'],
            'item' => $crumb['url'] ?: ''
        ];
        $position++;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $schema_items
    ];
    
    ?>
    <nav class="tgg-breadcrumbs" aria-label="Хлебные крошки">
        <ol class="tgg-breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $index => $crumb) : 
                $is_last = ($index === count($breadcrumbs) - 1);
            ?>
                <li class="tgg-breadcrumbs__item" 
                    itemprop="itemListElement" 
                    itemscope 
                    itemtype="https://schema.org/ListItem">
                    <?php if ($is_last) : ?>
                        <span class="tgg-breadcrumbs__current" itemprop="name">
                            <?php echo esc_html($crumb['name']); ?>
                        </span>
                        <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                    <?php else : ?>
                        <a href="<?php echo esc_url($crumb['url']); ?>" 
                           class="tgg-breadcrumbs__link" 
                           itemprop="item">
                            <span itemprop="name"><?php echo esc_html($crumb['name']); ?></span>
                        </a>
                        <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                        <span class="tgg-breadcrumbs__separator" aria-hidden="true">/</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
        <script type="application/ld+json">
            <?php echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
        </script>
    </nav>
    <?php
}

