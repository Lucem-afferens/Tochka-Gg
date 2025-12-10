<?php
/**
 * News Preview Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$news_title = get_field('news_preview_title') ?: 'Новости и события';
$news_count = get_field('news_preview_count') ?: 3;

// Получаем URL архива новостей через WordPress функции
$news_url_default = get_post_type_archive_link('news') ?: home_url('/news/');
$news_link = get_field('news_preview_link') ?: $news_url_default;

// Получаем закрепленные новости
$pinned_news_query = new WP_Query([
    'post_type' => 'news',
    'posts_per_page' => -1, // Получаем все закрепленные
    'post_status' => 'publish',
    'meta_query' => [
        [
            'key' => 'news_pinned',
            'value' => '1',
            'compare' => '='
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);

// Получаем обычные новости (не закрепленные)
$regular_news_count = $news_count - $pinned_news_query->found_posts;
if ($regular_news_count < 0) {
    $regular_news_count = 0;
}

$regular_news_query = new WP_Query([
    'post_type' => 'news',
    'posts_per_page' => $regular_news_count,
    'post_status' => 'publish',
    'meta_query' => [
        'relation' => 'OR',
        [
            'key' => 'news_pinned',
            'compare' => 'NOT EXISTS'
        ],
        [
            'key' => 'news_pinned',
            'value' => '1',
            'compare' => '!='
        ]
    ],
    'orderby' => 'date',
    'order' => 'DESC',
]);

// Объединяем результаты: сначала закрепленные, затем обычные
$all_news_posts = [];
if ($pinned_news_query->have_posts()) {
    while ($pinned_news_query->have_posts()) {
        $pinned_news_query->the_post();
        $all_news_posts[] = get_post();
    }
    wp_reset_postdata();
}
if ($regular_news_query->have_posts()) {
    while ($regular_news_query->have_posts()) {
        $regular_news_query->the_post();
        $all_news_posts[] = get_post();
    }
    wp_reset_postdata();
}

// Ограничиваем общее количество новостей
$all_news_posts = array_slice($all_news_posts, 0, $news_count);

// Если новостей нет, не показываем секцию (если включено в настройках, но новостей нет - покажем пустое состояние)
$has_news = !empty($all_news_posts);
?>

<section class="tgg-news-preview">
    <div class="tgg-container">
        <?php if ($news_title) : ?>
            <h2 class="tgg-news-preview__title">
                <?php echo esc_html($news_title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if ($has_news) : ?>
            <div class="tgg-news-preview__items">
                <?php foreach ($all_news_posts as $post) : 
                    setup_postdata($post);
                    $news_type = get_field('news_type') ?: 'news'; // 'news' или 'vacancy'
                    $news_external_link = get_field('news_external_link'); // Для внешних ссылок (например, на Telegram бота)
                    $news_pinned = get_field('news_pinned'); // Закрепленная новость
                    $news_date = get_the_date('d.m.Y');
                ?>
                    <article class="tgg-news-preview__item">
                        <?php 
                        $news_permalink = $news_external_link ? esc_url($news_external_link) : get_permalink();
                        $news_target = $news_external_link ? ' target="_blank" rel="noopener noreferrer"' : '';
                        ?>
                        <a href="<?php echo $news_permalink; ?>" class="tgg-news-preview__item-link"<?php echo $news_target; ?>>
                            <div class="tgg-news-preview__item-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php else : ?>
                                    <?php 
                                    $news_placeholder = function_exists('tochkagg_get_placeholder_image') 
                                        ? tochkagg_get_placeholder_image(400, 300, get_the_title() . ' - Новость', '1a1d29', '3b82f6')
                                        : 'https://placehold.co/400x300/1a1d29/3b82f6?text=' . urlencode(get_the_title() . ' - Новость');
                                    ?>
                                    <img src="<?php echo esc_url($news_placeholder); ?>" 
                                         alt="<?php echo esc_attr(get_the_title() . ' (заглушка - загрузите изображение)'); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                                
                                <div class="tgg-news-preview__item-badges">
                                    <?php if ($news_pinned) : ?>
                                        <div class="tgg-news-preview__item-badge tgg-news-preview__item-badge--pinned" title="Закреплено">
                                            📌
                                        </div>
                                    <?php endif; ?>
                                    <div class="tgg-news-preview__item-badge tgg-news-preview__item-badge--<?php echo esc_attr($news_type); ?>">
                                        <?php 
                                        if ($news_type === 'vacancy') {
                                            echo 'Вакансия';
                                        } elseif ($news_type === 'announcement') {
                                            echo 'Объявление';
                                        } else {
                                            echo 'Новость';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tgg-news-preview__item-content">
                                <div class="tgg-news-preview__item-meta">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="tgg-news-preview__item-date">
                                        <?php echo esc_html($news_date); ?>
                                    </time>
                                </div>
                                
                                <h3 class="tgg-news-preview__item-title">
                                    <?php the_title(); ?>
                                </h3>
                                
                                <?php if (get_the_excerpt()) : ?>
                                    <div class="tgg-news-preview__item-excerpt">
                                        <?php echo esc_html(wp_trim_words(get_the_excerpt(), 15, '...')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <span class="tgg-news-preview__item-cta">
                                    <?php echo $news_external_link ? 'Узнать больше →' : 'Читать далее →'; ?>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <?php if ($news_link) : ?>
                <div class="tgg-news-preview__cta">
                    <a href="<?php echo esc_url($news_link); ?>" class="tgg-btn-secondary">
                        Все новости
                    </a>
                </div>
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="tgg-news-preview__empty">
                <p>Новостей пока нет. Следите за обновлениями!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

