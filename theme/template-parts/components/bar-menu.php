<?php
/**
 * Bar Menu Template
 * 
 * Каталог товаров клубного бара
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем поля SCF (Secure Custom Fields / ACF)
$bar_title = function_exists('get_field') ? get_field('bar_title') : null;
$bar_description = function_exists('get_field') ? get_field('bar_description') : null;
$bar_currency_symbol = function_exists('get_field') ? get_field('bar_currency_symbol') : null;
$bar_categories = function_exists('get_field') ? get_field('bar_categories') : null;

// Значения по умолчанию
$bar_title = $bar_title ?: 'Клубный бар';
$bar_description = $bar_description ?: 'Кофе, энергетики, закуски и многое другое';
$bar_currency_symbol = $bar_currency_symbol ?: '₽';

// Fallback категории (если SCF не настроен)
$default_categories = [
    [
        'name' => 'Кофе',
        'items' => [
            ['name' => 'Эспрессо', 'price' => '120'],
            ['name' => 'Американо', 'price' => '130'],
            ['name' => 'Капучино', 'price' => '150'],
            ['name' => 'Латте', 'price' => '160'],
        ]
    ],
    [
        'name' => 'Энергетики',
        'items' => [
            ['name' => 'Red Bull', 'price' => '180'],
            ['name' => 'Monster', 'price' => '200'],
            ['name' => 'Burn', 'price' => '150'],
        ]
    ],
    [
        'name' => 'Закуски',
        'items' => [
            ['name' => 'Чипсы', 'price' => '120'],
            ['name' => 'Орешки', 'price' => '150'],
            ['name' => 'Сухарики', 'price' => '100'],
        ]
    ],
    [
        'name' => 'Бургеры',
        'items' => [
            ['name' => 'Чизбургер', 'price' => '280'],
            ['name' => 'Гамбургер', 'price' => '250'],
            ['name' => 'Двойной чизбургер', 'price' => '350'],
        ]
    ],
    [
        'name' => 'Напитки',
        'items' => [
            ['name' => 'Кола', 'price' => '120'],
            ['name' => 'Спрайт', 'price' => '120'],
            ['name' => 'Вода', 'price' => '80'],
            ['name' => 'Сок', 'price' => '140'],
        ]
    ],
    [
        'name' => 'Газировка',
        'items' => [
            ['name' => 'Pepsi', 'price' => '130'],
            ['name' => 'Fanta', 'price' => '130'],
            ['name' => 'Mirinda', 'price' => '130'],
        ]
    ],
];

// Используем данные из SCF или fallback
$categories = $bar_categories && is_array($bar_categories) && !empty($bar_categories) 
    ? $bar_categories 
    : $default_categories;
?>

<section class="tgg-bar" data-bar-page="true">
    <div class="tgg-container">
        <?php if ($bar_title) : ?>
            <h1 class="tgg-bar__title">
                <?php echo esc_html($bar_title); ?>
            </h1>
        <?php endif; ?>
        
        <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
            <?php get_template_part('template-parts/components/info-notice'); ?>
        <?php endif; ?>
        
        <?php if ($bar_description) : ?>
            <p class="tgg-bar__description">
                <?php echo esc_html($bar_description); ?>
            </p>
        <?php endif; ?>
        
        <?php if ($categories && is_array($categories)) : ?>
            <div class="tgg-bar__categories">
                <?php foreach ($categories as $index => $category) : 
                    $category_name = isset($category['name']) ? $category['name'] : '';
                    $category_description = isset($category['category_description']) ? $category['category_description'] : '';
                    $category_description = isset($category['description']) ? $category['description'] : $category_description; // Альтернативное поле
                    $items = isset($category['items']) && is_array($category['items']) ? $category['items'] : [];
                ?>
                    <?php if ($category_name && !empty($items)) : ?>
                        <div class="tgg-bar__category" data-category-index="<?php echo esc_attr($index); ?>">
                            <div class="tgg-bar__category-header">
                                <h2 class="tgg-bar__category-title" id="bar-category-title-<?php echo esc_attr($index); ?>">
                                    <?php echo esc_html($category_name); ?>
                                </h2>
                            </div>
                            
                            <?php if ($category_description) : ?>
                                <p class="tgg-bar__category-description">
                                    <?php echo esc_html($category_description); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="tgg-bar__items" 
                                 id="bar-category-<?php echo esc_attr($index); ?>"
                                 aria-labelledby="bar-category-title-<?php echo esc_attr($index); ?>"
                                 data-bar-items>
                                <?php foreach ($items as $item) : 
                                    $item_name = isset($item['name']) ? $item['name'] : '';
                                    $item_price = isset($item['price']) ? $item['price'] : '0';
                                    $item_description = isset($item['description']) ? $item['description'] : '';
                                    $item_image = isset($item['image']) ? $item['image'] : null;
                                ?>
                                    <?php if ($item_name) : ?>
                                        <div class="tgg-bar__item" data-bar-item>
                                            <div class="tgg-bar__item-image">
                                                <?php
                                                $product_image_data = function_exists('tochkagg_get_image_or_placeholder')
                                                    ? tochkagg_get_image_or_placeholder($item_image, 300, 300, $item_name)
                                                    : [
                                                        'url' => 'https://placehold.co/300x300/1a1d29/3b82f6?text=' . urlencode($item_name),
                                                        'alt' => esc_attr($item_name),
                                                        'width' => 300,
                                                        'height' => 300
                                                    ];
                                                ?>
                                                <img src="<?php echo esc_url($product_image_data['url']); ?>" 
                                                     alt="<?php echo esc_attr($product_image_data['alt']); ?>"
                                                     width="<?php echo esc_attr($product_image_data['width'] ?? 300); ?>"
                                                     height="<?php echo esc_attr($product_image_data['height'] ?? 300); ?>"
                                                     loading="lazy"
                                                     decoding="async"
                                                     draggable="false">
                                            </div>
                                            
                                            <div class="tgg-bar__item-content">
                                                <h3 class="tgg-bar__item-name">
                                                    <?php echo esc_html($item_name); ?>
                                                </h3>
                                                
                                                <?php if ($item_description) : ?>
                                                    <p class="tgg-bar__item-description">
                                                        <?php echo esc_html($item_description); ?>
                                                    </p>
                                                <?php endif; ?>
                                                
                                                <div class="tgg-bar__item-price">
                                                    <?php echo esc_html($item_price); ?> <?php echo esc_html($bar_currency_symbol); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
