<?php
/**
 * Advantages Section Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$advantages_title = get_field('advantages_title') ?: 'Почему выбирают нас?';
$advantages = get_field('advantages') ?: [
    [
        'title' => 'Высокая производительность',
        'text' => 'У нас вы сможете поиграть в любимые игры с максимальным комфортом благодаря производительной технике',
        'icon' => 'performance'
    ],
    [
        'title' => 'Поддержка',
        'text' => 'Наши специалисты всегда готовы оказать помощь, а также подсказать в случаях недопонимания',
        'icon' => 'support'
    ],
    [
        'title' => 'Комфорт',
        'text' => 'Уютная атмосфера, мягкий ковролин, фоновая музыка и возможность перекусить - все для комфорта, "как дома"',
        'icon' => 'home'
    ]
];
?>

<section class="tgg-advantages">
    <div class="tgg-container">
        <?php if ($advantages_title) : ?>
            <h2 class="tgg-advantages__title">
                <?php echo esc_html($advantages_title); ?>
            </h2>
        <?php endif; ?>
        
        <?php if ($advantages && is_array($advantages)) : ?>
            <div class="tgg-advantages__items">
                <?php foreach ($advantages as $index => $advantage) : 
                    $title = $advantage['title'] ?? '';
                    $text = $advantage['text'] ?? '';
                    $icon = $advantage['icon'] ?? '';
                    $icon_image = $advantage['icon_image'] ?? null;
                ?>
                    <div class="tgg-advantages__item" data-index="<?php echo esc_attr($index); ?>">
                        <div class="tgg-advantages__item-icon">
                            <div class="tgg-advantages__item-icon-glow"></div>
                            <?php if ($icon_image) : ?>
                                <img src="<?php echo esc_url($icon_image['url']); ?>" 
                                     alt="<?php echo esc_attr($icon_image['alt'] ?: $title); ?>">
                            <?php else : ?>
                                <!-- Заглушка для иконки -->
                                <div class="tgg-advantages__item-icon-placeholder">
                                    <?php echo esc_html($icon ?: 'icon'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="tgg-advantages__item-title">
                            <?php echo esc_html($title); ?>
                        </h3>
                        
                        <div class="tgg-advantages__item-divider"></div>
                        
                        <div class="tgg-advantages__item-content">
                            <?php echo esc_html($text); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>


