<?php
/**
 * Equipment Section Template (Preview)
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$equipment_title = get_field('equipment_preview_title') ?: 'Топовое оборудование';
$equipment_text = get_field('equipment_preview_text') ?: 'Играй на мощном железе без лагов и очередей';

// Получаем URL страницы оборудования через WordPress функции
$equipment_link_default = function_exists('tochkagg_get_page_url') 
    ? tochkagg_get_page_url('equipment') 
    : home_url('/equipment/');
$equipment_link = get_field('equipment_preview_link') ?: $equipment_link_default;
$equipment_button_text = get_field('equipment_preview_button_text') ?: 'Подробнее об оборудовании';

// Получаем ID страницы оборудования
$equipment_page_id = null;
$equipment_page = function_exists('tochkagg_get_page_url') 
    ? get_page_by_path('equipment') 
    : null;
if (!$equipment_page) {
    // Пробуем найти по русскому slug
    $equipment_page = get_page_by_path('оборудование');
}
if (!$equipment_page) {
    // Ищем по названию
    $pages = get_pages([
        'post_status' => 'publish',
        'number' => 50,
    ]);
    foreach ($pages as $page) {
        if (trim($page->post_title) === 'Оборудование') {
            $equipment_page = $page;
            break;
        }
    }
}
$equipment_page_id = $equipment_page ? $equipment_page->ID : null;

// Получаем категории оборудования для превью со страницы оборудования (первые 3 категории)
$equipment_categories = [];
if ($equipment_page_id) {
    $equipment_categories = get_field('equipment_categories', $equipment_page_id) ?: [];
}
$preview_categories = array_slice($equipment_categories, 0, 3); // Берем первые 3 категории для превью

// Получаем данные PS-зоны для превью со страницы оборудования
$ps5_title = 'PS-зона';
$ps5_specs = [];
if ($equipment_page_id) {
    $ps5_title = get_field('ps5_zone_title', $equipment_page_id) ?: 'PS-зона';
    $ps5_specs = get_field('ps5_zone_specs', $equipment_page_id) ?: [];
}
?>

<section class="tgg-equipment-preview">
    <div class="tgg-container">
        <div class="tgg-equipment-preview__wrapper">
            <div class="tgg-equipment-preview__content">
                <?php if ($equipment_title) : ?>
                    <h2 class="tgg-equipment-preview__title">
                        <?php echo esc_html($equipment_title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($equipment_text) : ?>
                    <p class="tgg-equipment-preview__text">
                        <?php echo esc_html($equipment_text); ?>
                    </p>
                <?php endif; ?>
                
                <div class="tgg-equipment-preview__specs">
                    <?php 
                    // Выводим категории оборудования (первые 3)
                    if (!empty($preview_categories) && is_array($preview_categories)) :
                        foreach ($preview_categories as $category) :
                            $category_name = isset($category['category_name']) ? $category['category_name'] : '';
                            $category_quantity = isset($category['category_quantity']) ? $category['category_quantity'] : '';
                            $category_specs = isset($category['category_specs']) && is_array($category['category_specs']) ? $category['category_specs'] : [];
                            
                            if (empty($category_name)) continue;
                            
                            // Формируем логичное описание из ключевых характеристик
                            $spec_desc = '';
                            if (!empty($category_specs)) {
                                // Ищем ключевые характеристики: видеокарта, процессор, память
                                $key_specs = [];
                                foreach ($category_specs as $spec) {
                                    $label = isset($spec['spec_label']) ? mb_strtolower(trim($spec['spec_label'])) : '';
                                    $value = isset($spec['spec_value']) ? trim($spec['spec_value']) : '';
                                    
                                    if (empty($value)) continue;
                                    
                                    // Приоритетные характеристики для отображения
                                    if (stripos($label, 'видеокарт') !== false || stripos($label, 'gpu') !== false) {
                                        $key_specs['gpu'] = $value;
                                    } elseif (stripos($label, 'процессор') !== false || stripos($label, 'cpu') !== false) {
                                        $key_specs['cpu'] = $value;
                                    } elseif (stripos($label, 'памят') !== false || stripos($label, 'ram') !== false) {
                                        $key_specs['ram'] = $value;
                                    }
                                }
                                
                                // Формируем описание в логичном порядке
                                $desc_parts = [];
                                if (isset($key_specs['gpu'])) {
                                    $desc_parts[] = $key_specs['gpu'];
                                }
                                if (isset($key_specs['cpu'])) {
                                    $desc_parts[] = $key_specs['cpu'];
                                }
                                // Если нет ключевых характеристик, берем первые 2
                                if (empty($desc_parts) && !empty($category_specs)) {
                                    $first_two = array_slice($category_specs, 0, 2);
                                    foreach ($first_two as $spec) {
                                        if (isset($spec['spec_value']) && !empty(trim($spec['spec_value']))) {
                                            $desc_parts[] = trim($spec['spec_value']);
                                        }
                                    }
                                }
                                
                                $spec_desc = !empty($desc_parts) ? implode(' • ', $desc_parts) : '';
                            }
                    ?>
                        <div class="tgg-equipment-preview__spec">
                            <div class="tgg-equipment-preview__spec-label"><?php echo esc_html($category_name); ?></div>
                            <div class="tgg-equipment-preview__spec-value"><?php echo esc_html($category_quantity ?: '—'); ?></div>
                            <?php if ($spec_desc) : ?>
                                <div class="tgg-equipment-preview__spec-desc"><?php echo esc_html($spec_desc); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php 
                        endforeach;
                    endif;
                    
                    // Выводим PS-зону, если есть
                    if ($ps5_title && !empty($ps5_specs)) :
                        // Формируем логичное описание PS-зоны
                        $ps5_desc = '';
                        if (!empty($ps5_specs)) {
                            $ps5_desc_parts = [];
                            foreach ($ps5_specs as $spec) {
                                $label = isset($spec['spec_label']) ? mb_strtolower(trim($spec['spec_label'])) : '';
                                $value = isset($spec['spec_value']) ? trim($spec['spec_value']) : '';
                                
                                if (empty($value)) continue;
                                
                                // Пропускаем PlayStation 5 (уже показано в количестве)
                                if (stripos($label, 'playstation') !== false || stripos($label, 'ps5') !== false) {
                                    continue;
                                }
                                
                                // Добавляем другие характеристики (джойстики, руль и т.д.)
                                $ps5_desc_parts[] = $value;
                                
                                // Ограничиваем до 2 характеристик для краткости
                                if (count($ps5_desc_parts) >= 2) {
                                    break;
                                }
                            }
                            
                            $ps5_desc = !empty($ps5_desc_parts) ? implode(' • ', $ps5_desc_parts) : '';
                        }
                        
                        // Находим количество PS5
                        $ps5_count = '';
                        foreach ($ps5_specs as $spec) {
                            if (isset($spec['spec_label']) && stripos($spec['spec_label'], 'playstation') !== false || stripos($spec['spec_label'], 'ps5') !== false) {
                                $ps5_count = $spec['spec_value'] ?? '';
                                break;
                            }
                        }
                    ?>
                        <div class="tgg-equipment-preview__spec">
                            <div class="tgg-equipment-preview__spec-label"><?php echo esc_html($ps5_title); ?></div>
                            <div class="tgg-equipment-preview__spec-value"><?php echo esc_html($ps5_count ?: '—'); ?></div>
                            <?php if ($ps5_desc) : ?>
                                <div class="tgg-equipment-preview__spec-desc"><?php echo esc_html($ps5_desc); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($equipment_link && $equipment_button_text) : ?>
                    <div class="tgg-equipment-preview__cta">
                        <a href="<?php echo esc_url($equipment_link); ?>" class="tgg-btn-fire">
                            <?php echo esc_html($equipment_button_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tgg-equipment-preview__image">
                <?php
                $equipment_image = get_field('equipment_preview_image');
                $equipment_image_data = tochkagg_get_image_or_placeholder($equipment_image, 800, 600, 'Equipment');
                ?>
                <img src="<?php echo esc_url($equipment_image_data['url']); ?>" 
                     alt="<?php echo esc_attr($equipment_image_data['alt']); ?>"
                     loading="lazy">
            </div>
        </div>
    </div>
</section>


