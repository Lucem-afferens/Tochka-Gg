<?php
/**
 * Pricing Table Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$pricing_title = get_field('pricing_title') ?: 'Тарифы и цены';
$pricing_note = get_field('pricing_note') ?: 'Все актуальные скидки и акции можно посмотреть в приложении LANGAME';

// Заголовки таблицы (можно настроить)
$pricing_table_header_category = get_field('pricing_table_header_category') ?: 'Категория';
$pricing_table_header_weekdays = get_field('pricing_table_header_weekdays') ?: 'Пн-Чт';
$pricing_table_header_weekend = get_field('pricing_table_header_weekend') ?: 'Пт-Вс';

// Названия категорий (можно настроить)
$pricing_category_lite_label = get_field('pricing_category_lite_label') ?: 'LITE';
$pricing_category_vip_label = get_field('pricing_category_vip_label') ?: 'VIP';

// Символ валюты
$pricing_currency_symbol = get_field('pricing_currency_symbol') ?: '₽';

// Тарифные пакеты (Repeater)
$pricing_packages = get_field('pricing_packages');

// Если пакеты не заданы, используем значения по умолчанию
if (!$pricing_packages || empty($pricing_packages)) {
    $pricing_packages = [
        [
            'package_title' => '1 час',
            'package_lite_weekday_price' => '100',
            'package_lite_weekend_price' => '120',
            'package_vip_weekday_price' => '110',
            'package_vip_weekend_price' => '130',
        ],
        [
            'package_title' => 'Пакет СТАРТ (08:00-13:00)',
            'package_lite_weekday_price' => '350',
            'package_lite_weekend_price' => '430',
            'package_vip_weekday_price' => '390',
            'package_vip_weekend_price' => '460',
        ],
        [
            'package_title' => 'Пакет GG (20:00-03:00) 🎁',
            'package_lite_weekday_price' => '550',
            'package_lite_weekend_price' => '700',
            'package_vip_weekday_price' => '600',
            'package_vip_weekend_price' => '750',
        ],
        [
            'package_title' => 'Пакет NIGHT (23:00-07:00)',
            'package_lite_weekday_price' => '450',
            'package_lite_weekend_price' => '550',
            'package_vip_weekday_price' => '500',
            'package_vip_weekend_price' => '600',
        ],
        [
            'package_title' => '1 час - night (03:00-08:00)',
            'package_lite_weekday_price' => '80',
            'package_lite_weekend_price' => '90',
            'package_vip_weekday_price' => '90',
            'package_vip_weekend_price' => '100',
        ],
    ];
}
?>

<section class="tgg-pricing">
    <div class="tgg-container">
        <?php if ($pricing_title) : ?>
            <h1 class="tgg-pricing__title">
                <?php echo esc_html($pricing_title); ?>
            </h1>
        <?php endif; ?>
        
        <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
            <?php get_template_part('template-parts/components/info-notice'); ?>
        <?php endif; ?>
        
        <?php if ($pricing_note) : ?>
            <p class="tgg-pricing__note">
                <?php echo esc_html($pricing_note); ?>
            </p>
        <?php endif; ?>
        
        <?php if ($pricing_packages && is_array($pricing_packages) && !empty($pricing_packages)) : ?>
            <div class="tgg-pricing__tables">
                <?php foreach ($pricing_packages as $package) : 
                    $package_title = isset($package['package_title']) ? $package['package_title'] : '';
                    $package_lite_weekday_price = isset($package['package_lite_weekday_price']) ? $package['package_lite_weekday_price'] : '0';
                    $package_lite_weekend_price = isset($package['package_lite_weekend_price']) ? $package['package_lite_weekend_price'] : '0';
                    $package_vip_weekday_price = isset($package['package_vip_weekday_price']) ? $package['package_vip_weekday_price'] : '0';
                    $package_vip_weekend_price = isset($package['package_vip_weekend_price']) ? $package['package_vip_weekend_price'] : '0';
                    $package_description = isset($package['package_description']) ? $package['package_description'] : '';
                    
                    if (empty($package_title)) continue;
                ?>
                    <div class="tgg-pricing__table">
                        <?php if ($package_title) : ?>
                            <h3 class="tgg-pricing__table-title"><?php echo esc_html($package_title); ?></h3>
                        <?php endif; ?>
                        
                        <?php if ($package_description) : ?>
                            <p class="tgg-pricing__table-description">
                                <?php echo esc_html($package_description); ?>
                            </p>
                        <?php endif; ?>
                        
                        <table class="tgg-pricing-table">
                            <thead>
                                <tr>
                                    <th><?php echo esc_html($pricing_table_header_category); ?></th>
                                    <th><?php echo esc_html($pricing_table_header_weekdays); ?></th>
                                    <th><?php echo esc_html($pricing_table_header_weekend); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong><?php echo esc_html($pricing_category_lite_label); ?></strong></td>
                                    <td class="tgg-price"><?php echo esc_html($package_lite_weekday_price); ?> <?php echo esc_html($pricing_currency_symbol); ?></td>
                                    <td class="tgg-price"><?php echo esc_html($package_lite_weekend_price); ?> <?php echo esc_html($pricing_currency_symbol); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo esc_html($pricing_category_vip_label); ?></strong></td>
                                    <td class="tgg-price tgg-price--vip"><?php echo esc_html($package_vip_weekday_price); ?> <?php echo esc_html($pricing_currency_symbol); ?></td>
                                    <td class="tgg-price tgg-price--vip"><?php echo esc_html($package_vip_weekend_price); ?> <?php echo esc_html($pricing_currency_symbol); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
