<?php
/**
 * Equipment Full Page Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="tgg-equipment-full">
    <div class="tgg-container">
        <h1 class="tgg-equipment-full__title">Оборудование</h1>
        
        <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
            <?php get_template_part('template-parts/components/info-notice'); ?>
        <?php endif; ?>
        
        <!-- VIP ПК -->
        <div class="tgg-equipment-full__category">
            <h2 class="tgg-equipment-full__category-title">VIP-компьютеры (6 шт.)</h2>
            
            <div class="tgg-equipment-full__specs">
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Видеокарта</div>
                    <div class="tgg-equipment-full__spec-value">RTX 5060 Ti</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Процессор</div>
                    <div class="tgg-equipment-full__spec-value">Intel Core i5-13400F</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Оперативная память</div>
                    <div class="tgg-equipment-full__spec-value">ADATA XPG 32 Гб</div>
                </div>
            </div>
            
            <div class="tgg-equipment-full__peripherals-wrapper">
                <div class="tgg-equipment-full__peripherals">
                    <h3 class="tgg-equipment-full__peripherals-title">Периферия VIP</h3>
                    <ul class="tgg-equipment-full__peripherals-list">
                        <li><strong>Монитор:</strong> Titan Army 24.5" 240 Гц</li>
                        <li><strong>Кресло:</strong> ARDOR GAMING Chaos Guard</li>
                        <li><strong>Наушники:</strong> ARDOR GAMING H9</li>
                        <li><strong>Мышь:</strong> ARDOR GAMING Impact PRO</li>
                        <li><strong>Клавиатура:</strong> ARDOR GAMING Pathfinder</li>
                    </ul>
                </div>
                
                <?php
                // Галерея фотографий VIP ПК
                $vip_gallery = tochkagg_get_field('vip_pc_gallery') ?: [];
                if (!empty($vip_gallery) || true) { // Показываем всегда для демонстрации
                ?>
                <div class="tgg-equipment-full__gallery">
                    <div class="tgg-equipment-full__gallery-slider" data-gallery="vip-pc">
                        <div class="tgg-equipment-full__gallery-track">
                            <?php
                            // Если есть реальные изображения из ACF
                            if (!empty($vip_gallery) && is_array($vip_gallery)) {
                                foreach ($vip_gallery as $image) {
                                    if (is_array($image) && !empty($image['url'])) {
                                        echo '<div class="tgg-equipment-full__gallery-slide">';
                                        echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? 'VIP ПК') . '">';
                                        echo '</div>';
                                    }
                                }
                            } else {
                                // Placeholder изображения
                                for ($i = 1; $i <= 3; $i++) {
                                    $placeholder = tochkagg_get_placeholder_image(400, 300, "VIP ПК - Фото {$i}", '1a1d29', '3b82f6');
                                    echo '<div class="tgg-equipment-full__gallery-slide">';
                                    echo '<img src="' . esc_url($placeholder) . '" alt="VIP ПК - Фото ' . $i . ' (заглушка)">';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                        <div class="tgg-equipment-full__gallery-nav">
                            <button class="tgg-equipment-full__gallery-btn" data-gallery-prev="vip-pc" aria-label="Предыдущее фото">←</button>
                            <div class="tgg-equipment-full__gallery-dots" data-gallery-dots="vip-pc"></div>
                            <button class="tgg-equipment-full__gallery-btn" data-gallery-next="vip-pc" aria-label="Следующее фото">→</button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        
        <!-- LITE ПК -->
        <div class="tgg-equipment-full__category">
            <h2 class="tgg-equipment-full__category-title">LITE-компьютеры (6 шт.)</h2>
            
            <div class="tgg-equipment-full__specs">
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Видеокарта</div>
                    <div class="tgg-equipment-full__spec-value">RTX 4060</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Процессор</div>
                    <div class="tgg-equipment-full__spec-value">Intel Core i5-12400F</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Оперативная память</div>
                    <div class="tgg-equipment-full__spec-value">ADATA XPG 32 Гб</div>
                </div>
            </div>
            
            <div class="tgg-equipment-full__peripherals-wrapper">
                <div class="tgg-equipment-full__peripherals">
                    <h3 class="tgg-equipment-full__peripherals-title">Периферия LITE</h3>
                    <ul class="tgg-equipment-full__peripherals-list">
                        <li><strong>Монитор:</strong> Titan Army 24.5" 240 Гц</li>
                        <li><strong>Кресло:</strong> Zombie CAME Penta</li>
                        <li><strong>Наушники:</strong> ARDOR GAMING H9</li>
                        <li><strong>Мышь:</strong> Logitech G102 LIGHTSYNC</li>
                        <li><strong>Клавиатура:</strong> ARDOR GAMING Pathfinder</li>
                    </ul>
                </div>
                
                <?php
                // Галерея фотографий LITE ПК
                $lite_gallery = tochkagg_get_field('lite_pc_gallery') ?: [];
                if (!empty($lite_gallery) || true) { // Показываем всегда для демонстрации
                ?>
                <div class="tgg-equipment-full__gallery">
                    <div class="tgg-equipment-full__gallery-slider" data-gallery="lite-pc">
                        <div class="tgg-equipment-full__gallery-track">
                            <?php
                            // Если есть реальные изображения из ACF
                            if (!empty($lite_gallery) && is_array($lite_gallery)) {
                                foreach ($lite_gallery as $image) {
                                    if (is_array($image) && !empty($image['url'])) {
                                        echo '<div class="tgg-equipment-full__gallery-slide">';
                                        echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt'] ?? 'LITE ПК') . '">';
                                        echo '</div>';
                                    }
                                }
                            } else {
                                // Placeholder изображения
                                for ($i = 1; $i <= 3; $i++) {
                                    $placeholder = tochkagg_get_placeholder_image(400, 300, "LITE ПК - Фото {$i}", '1a1d29', '8b5cf6');
                                    echo '<div class="tgg-equipment-full__gallery-slide">';
                                    echo '<img src="' . esc_url($placeholder) . '" alt="LITE ПК - Фото ' . $i . ' (заглушка)">';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                        <div class="tgg-equipment-full__gallery-nav">
                            <button class="tgg-equipment-full__gallery-btn" data-gallery-prev="lite-pc" aria-label="Предыдущее фото">←</button>
                            <div class="tgg-equipment-full__gallery-dots" data-gallery-dots="lite-pc"></div>
                            <button class="tgg-equipment-full__gallery-btn" data-gallery-next="lite-pc" aria-label="Следующее фото">→</button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        
        <!-- PS-зона -->
        <div class="tgg-equipment-full__category" id="ps5">
            <h2 class="tgg-equipment-full__category-title">PS-зона</h2>
            
            <div class="tgg-equipment-full__specs">
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">PlayStation 5</div>
                    <div class="tgg-equipment-full__spec-value">1 приставка</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Джойстики</div>
                    <div class="tgg-equipment-full__spec-value">4 шт.</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Игровой руль</div>
                    <div class="tgg-equipment-full__spec-value">1 шт.</div>
                </div>
                
                <div class="tgg-equipment-full__spec-item">
                    <div class="tgg-equipment-full__spec-label">Подписка</div>
                    <div class="tgg-equipment-full__spec-value">PS Plus</div>
                </div>
            </div>
            
            <div class="tgg-equipment-full__games-wrapper">
                <div class="tgg-equipment-full__games">
                    <h3 class="tgg-equipment-full__games-title">Игры</h3>
                    <div class="tgg-equipment-full__games-info">
                        <p><strong>Более 50 игр</strong> в библиотеке, включая:</p>
                        <ul class="tgg-equipment-full__games-list">
                            <li>Эксклюзивы PlayStation</li>
                            <li>Популярные мультиплеерные игры</li>
                            <li>Новинки из подписки PS Plus</li>
                            <li>Гонки с поддержкой игрового руля</li>
                        </ul>
                    </div>
                </div>
                
                <?php
                // Галерея фотографий PS-зона
                $ps5_gallery = tochkagg_get_field('ps5_zone_gallery') ?: [];
                if (!empty($ps5_gallery) || true) { // Показываем всегда для демонстрации
                ?>
                <div class="tgg-equipment-full__gallery">
                    <div class="tgg-equipment-full__gallery-slider" data-gallery="ps5-zone">
                        <div class="tgg-equipment-full__gallery-track">
                            <?php
                            // Если есть реальные изображения из ACF
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
                                    $placeholder = tochkagg_get_placeholder_image(400, 300, "PS-зона - Фото {$i}", '1a1d29', 'ec4899');
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
                <?php } ?>
            </div>
            
            <div class="tgg-equipment-full__description">
                <p>Давно мечтаешь погонять в новинки на PS5? Лови момент — у нас уже стоит одна красавица, ждущая, чтобы ты взял в руки геймпад! Больше 50 игр, плюс крутые тайтлы из подписки PS Plus. Также доступен игровой руль для реалистичных гонок. Остальные приставки уже в пути — так что торопись занять место у экрана!</p>
            </div>
        </div>
    </div>
</section>


