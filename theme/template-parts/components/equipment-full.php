<?php
/**
 * Equipment Full Page Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Получаем заголовок страницы
$equipment_title = get_field('equipment_page_title') ?: 'Оборудование';

// Категории оборудования (PC) - Repeater
$equipment_categories = get_field('equipment_categories') ?: [];

// PS-зона - отдельные поля
$ps5_title = get_field('ps5_zone_title') ?: 'PS-зона';
$ps5_specs = get_field('ps5_zone_specs') ?: []; // Repeater для характеристик PS-зоны
$ps5_games_title = get_field('ps5_zone_games_title') ?: 'Игры';
$ps5_games_description = get_field('ps5_zone_games_description') ?: 'Более 50 игр в библиотеке, включая:';
$ps5_games_list = get_field('ps5_zone_games_list') ?: []; // Repeater для списка игр
$ps5_description = get_field('ps5_zone_description') ?: '';
$ps5_gallery = get_field('ps5_zone_gallery') ?: [];
?>

<section class="tgg-equipment-full">
    <div class="tgg-container">
        <h1 class="tgg-equipment-full__title"><?php echo esc_html($equipment_title); ?></h1>
        
        <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
            <?php get_template_part('template-parts/components/info-notice'); ?>
        <?php endif; ?>
        
        <?php 
        // Выводим категории оборудования (PC)
        if (!empty($equipment_categories) && is_array($equipment_categories)) :
            foreach ($equipment_categories as $index => $category) :
                $category_name = isset($category['category_name']) ? $category['category_name'] : '';
                $category_quantity = isset($category['category_quantity']) ? $category['category_quantity'] : '';
                $category_type = isset($category['category_type']) ? $category['category_type'] : 'standard';
                $category_specs = isset($category['category_specs']) && is_array($category['category_specs']) ? $category['category_specs'] : [];
                $category_peripherals = isset($category['category_peripherals']) && is_array($category['category_peripherals']) ? $category['category_peripherals'] : [];
                $category_gallery = isset($category['category_gallery']) ? $category['category_gallery'] : [];
                $category_peripherals_title = isset($category['category_peripherals_title']) ? $category['category_peripherals_title'] : 'Периферия';
                
                if (empty($category_name)) continue;
                
                // Формируем заголовок категории
                $category_title = $category_name;
                if ($category_quantity) {
                    $category_title .= ' (' . $category_quantity . ')';
                }
                
                // Уникальный ID для галереи
                $gallery_id = 'equipment-category-' . $index;
        ?>
            <div class="tgg-equipment-full__category tgg-equipment-full__category--<?php echo esc_attr($category_type); ?>">
                <h2 class="tgg-equipment-full__category-title"><?php echo esc_html($category_title); ?></h2>
                
                <?php if (!empty($category_specs)) : ?>
                    <div class="tgg-equipment-full__specs">
                        <?php foreach ($category_specs as $spec) : 
                            $spec_label = isset($spec['spec_label']) ? $spec['spec_label'] : '';
                            $spec_value = isset($spec['spec_value']) ? $spec['spec_value'] : '';
                            if (empty($spec_label) && empty($spec_value)) continue;
                        ?>
                            <div class="tgg-equipment-full__spec-item">
                                <div class="tgg-equipment-full__spec-label"><?php echo esc_html($spec_label); ?></div>
                                <div class="tgg-equipment-full__spec-value"><?php echo esc_html($spec_value); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="tgg-equipment-full__peripherals-wrapper">
                    <?php if (!empty($category_peripherals)) : ?>
                        <div class="tgg-equipment-full__peripherals">
                            <h3 class="tgg-equipment-full__peripherals-title"><?php echo esc_html($category_peripherals_title); ?></h3>
                            <ul class="tgg-equipment-full__peripherals-list">
                                <?php foreach ($category_peripherals as $peripheral) : 
                                    $peripheral_name = isset($peripheral['peripheral_name']) ? $peripheral['peripheral_name'] : '';
                                    $peripheral_value = isset($peripheral['peripheral_value']) ? $peripheral['peripheral_value'] : '';
                                    if (empty($peripheral_name) && empty($peripheral_value)) continue;
                                ?>
                                    <li>
                                        <?php if ($peripheral_name) : ?>
                                            <strong><?php echo esc_html($peripheral_name); ?>:</strong>
                                        <?php endif; ?>
                                        <?php echo esc_html($peripheral_value); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($category_gallery) || true) : // Показываем галерею всегда для демонстрации ?>
                        <div class="tgg-equipment-full__gallery">
                            <div class="tgg-equipment-full__gallery-slider" data-gallery="<?php echo esc_attr($gallery_id); ?>">
                                <div class="tgg-equipment-full__gallery-track">
                                    <?php
                                    if (!empty($category_gallery) && is_array($category_gallery)) {
                                        foreach ($category_gallery as $image) {
                                            if (is_array($image) && !empty($image['url'])) {
                                                echo '<div class="tgg-equipment-full__gallery-slide">';
                                                echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? $category_name) . '">';
                                                echo '</div>';
                                            }
                                        }
                                    } else {
                                        // Placeholder изображения
                                        for ($i = 1; $i <= 3; $i++) {
                                            $placeholder = function_exists('tochkagg_get_placeholder_image') 
                                                ? tochkagg_get_placeholder_image(400, 300, "{$category_name} - Фото {$i}", '1a1d29', '3b82f6')
                                                : 'https://placehold.co/400x300/1a1d29/3b82f6?text=' . urlencode("{$category_name} - Фото {$i}");
                                            echo '<div class="tgg-equipment-full__gallery-slide">';
                                            echo '<img src="' . esc_url($placeholder) . '" alt="' . esc_attr("{$category_name} - Фото {$i} (заглушка)") . '">';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="tgg-equipment-full__gallery-nav">
                                    <button class="tgg-equipment-full__gallery-btn" data-gallery-prev="<?php echo esc_attr($gallery_id); ?>" aria-label="Предыдущее фото">←</button>
                                    <div class="tgg-equipment-full__gallery-dots" data-gallery-dots="<?php echo esc_attr($gallery_id); ?>"></div>
                                    <button class="tgg-equipment-full__gallery-btn" data-gallery-next="<?php echo esc_attr($gallery_id); ?>" aria-label="Следующее фото">→</button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            endforeach;
        endif; 
        ?>
        
        <!-- PS-зона -->
        <?php if ($ps5_title) : ?>
            <div class="tgg-equipment-full__category tgg-equipment-full__category--ps5" id="ps5">
                <h2 class="tgg-equipment-full__category-title"><?php echo esc_html($ps5_title); ?></h2>
                
                <?php if (!empty($ps5_specs)) : ?>
                    <div class="tgg-equipment-full__specs">
                        <?php foreach ($ps5_specs as $spec) : 
                            $spec_label = isset($spec['spec_label']) ? $spec['spec_label'] : '';
                            $spec_value = isset($spec['spec_value']) ? $spec['spec_value'] : '';
                            if (empty($spec_label) && empty($spec_value)) continue;
                        ?>
                            <div class="tgg-equipment-full__spec-item">
                                <div class="tgg-equipment-full__spec-label"><?php echo esc_html($spec_label); ?></div>
                                <div class="tgg-equipment-full__spec-value"><?php echo esc_html($spec_value); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="tgg-equipment-full__games-wrapper">
                    <?php if ($ps5_games_title || $ps5_games_description || !empty($ps5_games_list)) : ?>
                        <div class="tgg-equipment-full__games">
                            <?php if ($ps5_games_title) : ?>
                                <h3 class="tgg-equipment-full__games-title"><?php echo esc_html($ps5_games_title); ?></h3>
                            <?php endif; ?>
                            
                            <div class="tgg-equipment-full__games-info">
                                <?php if ($ps5_games_description) : ?>
                                    <p><?php echo wp_kses_post($ps5_games_description); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($ps5_games_list) && is_array($ps5_games_list)) : ?>
                                    <ul class="tgg-equipment-full__games-list">
                                        <?php foreach ($ps5_games_list as $game) : 
                                            $game_name = isset($game['game_name']) ? $game['game_name'] : '';
                                            if (empty($game_name)) continue;
                                        ?>
                                            <li><?php echo esc_html($game_name); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($ps5_gallery) || true) : // Показываем галерею всегда для демонстрации ?>
                        <div class="tgg-equipment-full__gallery">
                            <div class="tgg-equipment-full__gallery-slider" data-gallery="ps5-zone">
                                <div class="tgg-equipment-full__gallery-track">
                                    <?php
                                    if (!empty($ps5_gallery) && is_array($ps5_gallery)) {
                                        foreach ($ps5_gallery as $image) {
                                            if (is_array($image) && !empty($image['url'])) {
                                                echo '<div class="tgg-equipment-full__gallery-slide">';
                                                echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? 'PS-зона') . '">';
                                                echo '</div>';
                                            }
                                        }
                                    } else {
                                        // Placeholder изображения
                                        for ($i = 1; $i <= 3; $i++) {
                                            $placeholder = function_exists('tochkagg_get_placeholder_image') 
                                                ? tochkagg_get_placeholder_image(400, 300, "PS-зона - Фото {$i}", '1a1d29', 'ec4899')
                                                : 'https://placehold.co/400x300/1a1d29/ec4899?text=' . urlencode("PS-зона - Фото {$i}");
                                            echo '<div class="tgg-equipment-full__gallery-slide">';
                                            echo '<img src="' . esc_url($placeholder) . '" alt="PS-зона - Фото ' . $i . ' (заглушка)">';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="tgg-equipment-full__gallery-nav">
                                    <button class="tgg-equipment-full__gallery-btn" data-gallery-prev="ps5-zone" aria-label="Предыдущее фото">←</button>
                                    <div class="tgg-equipment-full__gallery-dots" data-gallery-dots="ps5-zone"></div>
                                    <button class="tgg-equipment-full__gallery-btn" data-gallery-next="ps5-zone" aria-label="Следующее фото">→</button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($ps5_description) : ?>
                    <div class="tgg-equipment-full__description">
                        <?php echo wp_kses_post($ps5_description); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
