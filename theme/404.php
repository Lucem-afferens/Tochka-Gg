<?php
/**
 * 404 Error Template
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>


<main class="tgg-main">
    <section class="tgg-404">
        <div class="tgg-container">
            <div class="tgg-404__content">
                <h1 class="tgg-404__title">404</h1>
                <h2 class="tgg-404__subtitle">Страница не найдена</h2>
                <p class="tgg-404__text">
                    К сожалению, запрашиваемая страница не существует. 
                    Возможно, она была удалена или перемещена.
                </p>
                <div class="tgg-404__actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="tgg-btn-primary">
                        Вернуться на главную
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>

