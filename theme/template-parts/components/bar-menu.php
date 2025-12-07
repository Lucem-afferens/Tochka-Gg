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

$bar_title = get_field('bar_title') ?: 'Клубный бар';
$bar_description = get_field('bar_description') ?: 'Кофе, энергетики, закуски и многое другое';

// Категории товаров
$categories = get_field('bar_categories') ?: [
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
?>

<section class="tgg-bar">
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
                <?php foreach ($categories as $category) : 
                    $category_name = $category['name'] ?? '';
                    $items = $category['items'] ?? [];
                ?>
                    <?php if ($category_name && !empty($items)) : ?>
                        <div class="tgg-bar__category">
                            <h2 class="tgg-bar__category-title">
                                <?php echo esc_html($category_name); ?>
                            </h2>
                            
                            <div class="tgg-bar__items">
                                <?php foreach ($items as $item) : 
                                    $item_name = $item['name'] ?? '';
                                    $item_price = $item['price'] ?? '0';
                                    $item_description = $item['description'] ?? '';
                                    $item_image = $item['image'] ?? null;
                                ?>
                                    <div class="tgg-bar__item">
                                        <div class="tgg-bar__item-image">
                                            <?php
                                            $product_image_data = tochkagg_get_image_or_placeholder($item_image, 300, 300, $item_name);
                                            ?>
                                            <img src="<?php echo esc_url($product_image_data['url']); ?>" 
                                                 alt="<?php echo esc_attr($product_image_data['alt']); ?>"
                                                 loading="lazy">
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
                                                <?php echo esc_html($item_price); ?> ₽
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


