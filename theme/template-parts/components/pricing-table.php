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
        
        <div class="tgg-pricing__tables">
            <!-- 1 час -->
            <div class="tgg-pricing__table">
                <h3 class="tgg-pricing__table-title">1 час</h3>
                <table class="tgg-pricing-table">
                    <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Пн-Чт</th>
                            <th>Пт-Вс</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>LITE</strong></td>
                            <td class="tgg-price">100 ₽</td>
                            <td class="tgg-price">120 ₽</td>
                        </tr>
                        <tr>
                            <td><strong>VIP</strong></td>
                            <td class="tgg-price">110 ₽</td>
                            <td class="tgg-price">130 ₽</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Пакет СТАРТ -->
            <div class="tgg-pricing__table">
                <h3 class="tgg-pricing__table-title">Пакет СТАРТ (08:00-13:00)</h3>
                <table class="tgg-pricing-table">
                    <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Пн-Чт</th>
                            <th>Пт-Вс</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>LITE</strong></td>
                            <td class="tgg-price">350 ₽</td>
                            <td class="tgg-price">430 ₽</td>
                        </tr>
                        <tr>
                            <td><strong>VIP</strong></td>
                            <td class="tgg-price">390 ₽</td>
                            <td class="tgg-price">460 ₽</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Пакет GG -->
            <div class="tgg-pricing__table">
                <h3 class="tgg-pricing__table-title">Пакет GG (20:00-03:00) 🎁</h3>
                <table class="tgg-pricing-table">
                    <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Пн-Чт</th>
                            <th>Пт-Вс</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>LITE</strong></td>
                            <td class="tgg-price">550 ₽</td>
                            <td class="tgg-price">700 ₽</td>
                        </tr>
                        <tr>
                            <td><strong>VIP</strong></td>
                            <td class="tgg-price">600 ₽</td>
                            <td class="tgg-price">750 ₽</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Пакет NIGHT -->
            <div class="tgg-pricing__table">
                <h3 class="tgg-pricing__table-title">Пакет NIGHT (23:00-07:00)</h3>
                <table class="tgg-pricing-table">
                    <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Пн-Чт</th>
                            <th>Пт-Вс</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>LITE</strong></td>
                            <td class="tgg-price">450 ₽</td>
                            <td class="tgg-price">550 ₽</td>
                        </tr>
                        <tr>
                            <td><strong>VIP</strong></td>
                            <td class="tgg-price">500 ₽</td>
                            <td class="tgg-price">600 ₽</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- 1 час - night -->
            <div class="tgg-pricing__table">
                <h3 class="tgg-pricing__table-title">1 час - night (03:00-08:00)</h3>
                <table class="tgg-pricing-table">
                    <thead>
                        <tr>
                            <th>Категория</th>
                            <th>Пн-Чт</th>
                            <th>Пт-Вс</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>LITE</strong></td>
                            <td class="tgg-price">80 ₽</td>
                            <td class="tgg-price">90 ₽</td>
                        </tr>
                        <tr>
                            <td><strong>VIP</strong></td>
                            <td class="tgg-price">90 ₽</td>
                            <td class="tgg-price">100 ₽</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


