<?php
/**
 * Template Name: FAQ - Часто задаваемые вопросы
 * 
 * Шаблон для страницы часто задаваемых вопросов
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="tgg-main tgg-page tgg-page--faq">
    <div class="tgg-container">
        <article class="tgg-page__content">
            <header class="tgg-page__header">
                <h1 class="tgg-page__title">Часто задаваемые вопросы (FAQ)</h1>
                <p class="tgg-page__subtitle">Ответы на самые популярные вопросы о нашем клубе</p>
            </header>
            
            <div class="tgg-page__body">
                <section class="tgg-page__section">
                    <h2>Общие вопросы</h2>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Где находится клуб?</h3>
                        <div class="tgg-faq__answer">
                            <?php
                            $address = function_exists('get_field') ? get_field('address_full', 'option') : 'Пермский край, г. Кунгур, ул. Голованова, 43, вход с торца здания, цокольный этаж';
                            ?>
                            <p>Наш клуб расположен по адресу: <strong><?php echo esc_html($address); ?></strong></p>
                            <p>Вход с торца здания, цокольный этаж. Есть удобная парковка рядом с клубом.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какие часы работы клуба?</h3>
                        <div class="tgg-faq__answer">
                            <?php
                            $working_hours = function_exists('get_field') ? get_field('working_hours', 'option') : 'Круглосуточно, без выходных';
                            ?>
                            <p><strong><?php echo esc_html($working_hours); ?></strong></p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Как забронировать игровое время?</h3>
                        <div class="tgg-faq__answer">
                            <p>Вы можете забронировать время несколькими способами:</p>
                            <ul>
                                <li>По телефону - позвоните нам и мы зарезервируем место</li>
                                <li>В Telegram-канале - подпишитесь на @tochaGgKungur</li>
                                <li>Через социальные сети - напишите нам в ВКонтакте</li>
                                <li>Через мобильное приложение Langame</li>
                                <li>Лично в клубе - подойдите к администратору</li>
                            </ul>
                            <p>Бронирование подтверждается после оплаты. При неявке в течение 15 минут бронирование аннулируется.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какой возраст посетителей?</h3>
                        <div class="tgg-faq__answer">
                            <p>Лицам до 18 лет разрешено посещение клуба только в сопровождении совершеннолетних или с письменного разрешения родителей.</p>
                            <p>Для игр с возрастными ограничениями (18+) требуется подтверждение совершеннолетия (предъявление документа).</p>
                        </div>
                    </div>
                </section>
                
                <section class="tgg-page__section">
                    <h2>Оборудование и игры</h2>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какое оборудование доступно в клубе?</h3>
                        <div class="tgg-faq__answer">
                            <p>В нашем клубе доступно:</p>
                            <ul>
                                <li><strong>Игровые ПК VIP:</strong> мощные компьютеры с RTX видеокартами, процессорами Intel/AMD, 32GB RAM</li>
                                <li><strong>Игровые ПК LITE:</strong> компьютеры среднего класса с отличной производительностью</li>
                                <li><strong>PlayStation 5:</strong> несколько консолей с 4 джойстиками и более чем 50 играми</li>
                                <li><strong>VR Арена:</strong> виртуальная реальность на площади 840 м², до 10 игроков одновременно</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Можно ли установить свои игры?</h3>
                        <div class="tgg-faq__answer">
                            <p>Да, вы можете установить свои игры, но только легальное программное обеспечение. Запрещается установка пиратских версий игр и программ.</p>
                            <p>Важно: сохраняйте важные файлы на внешние носители, так как компьютеры периодически очищаются от пользовательских данных.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какие игры доступны на компьютерах?</h3>
                        <div class="tgg-faq__answer">
                            <p>На компьютерах установлен широкий выбор популярных игр различных жанров: шутеры, стратегии, RPG, гонки, симуляторы и многие другие.</p>
                            <p>Вы также можете установить свои игры через Steam, Epic Games и другие платформы (при наличии аккаунта).</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Что включено в VR Арену?</h3>
                        <div class="tgg-faq__answer">
                            <p>VR Арена "Другие миры" - это:</p>
                            <ul>
                                <li>Площадь 840 м² виртуальной реальности</li>
                                <li>Возможность игры до 10 игроков одновременно</li>
                                <li>Уникальные игры в форматах "игрок против игрока" и "игрок против компьютера"</li>
                                <li>Зона отдыха и банкета</li>
                                <li>Современное VR-оборудование</li>
                            </ul>
                        </div>
                    </div>
                </section>
                
                <section class="tgg-page__section">
                    <h2>Цены и оплата</h2>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какие цены на игровое время?</h3>
                        <div class="tgg-faq__answer">
                            <p>У нас действуют разные тарифы в зависимости от типа оборудования (LITE или VIP) и дня недели (будни или выходные).</p>
                            <p>Актуальные цены вы можете посмотреть на странице <a href="<?php echo esc_url(function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('pricing') : '#'); ?>">Цены</a> или уточнить у администратора по телефону.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какие способы оплаты доступны?</h3>
                        <div class="tgg-faq__answer">
                            <p>Мы принимаем оплату:</p>
                            <ul>
                                <li>Наличными в кассе клуба</li>
                                <li>Банковскими картами (Visa, MasterCard, МИР)</li>
                                <li>Электронными средствами оплаты</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Можно ли вернуть деньги, если не использовал все время?</h3>
                        <div class="tgg-faq__answer">
                            <p>При досрочном завершении игровой сессии возврат средств не производится. Рекомендуем внимательно планировать время посещения.</p>
                            <p>Неиспользованное время можно будет использовать в другой раз в течение текущего дня (по согласованию с администрацией).</p>
                        </div>
                    </div>
                </section>
                
                <section class="tgg-page__section">
                    <h2>Турниры и мероприятия</h2>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Как принять участие в турнире?</h3>
                        <div class="tgg-faq__answer">
                            <p>Регистрация на турниры осуществляется заранее. Информацию о предстоящих турнирах вы можете найти:</p>
                            <ul>
                                <li>На странице <a href="<?php echo esc_url(function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('tournaments') : '#'); ?>">Турниры</a> нашего сайта</li>
                                <li>В наших группах в социальных сетях</li>
                                <li>У администратора клуба</li>
                            </ul>
                            <p>Для участия необходимо зарегистрироваться и оплатить взнос (если требуется).</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Какие призы разыгрываются в турнирах?</h3>
                        <div class="tgg-faq__answer">
                            <p>Призовой фонд зависит от конкретного турнира. Обычно разыгрываются денежные призы, подарочные сертификаты на игровое время, игровое оборудование и другие призы.</p>
                            <p>Подробная информация о призах указывается в правилах каждого турнира.</p>
                        </div>
                    </div>
                </section>
                
                <section class="tgg-page__section">
                    <h2>Клубный бар</h2>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Что можно заказать в клубном баре?</h3>
                        <div class="tgg-faq__answer">
                            <p>В нашем клубном баре вы можете заказать:</p>
                            <ul>
                                <li>Кофе и другие горячие напитки</li>
                                <li>Энергетики</li>
                                <li>Прохладительные напитки и газировку</li>
                                <li>Чипсы и снеки</li>
                                <li>Бургеры и другую еду</li>
                            </ul>
                            <p>Полное меню и цены доступны на странице <a href="<?php echo esc_url(function_exists('tochkagg_get_page_url') ? tochkagg_get_page_url('bar') : '#'); ?>">Клубный бар</a>.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Можно ли приносить свою еду и напитки?</h3>
                        <div class="tgg-faq__answer">
                            <p>Нет, приносить свою еду и напитки запрещено правилами клуба. Это связано с соблюдением санитарных норм и поддержанием чистоты.</p>
                            <p>Вы можете заказать еду и напитки в нашем клубном баре или в специально отведенных зонах отдыха.</p>
                        </div>
                    </div>
                </section>
                
                <section class="tgg-page__section">
                    <h2>Другие вопросы</h2>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Что делать, если оборудование не работает?</h3>
                        <div class="tgg-faq__answer">
                            <p>Если вы заметили, что оборудование работает неправильно, немедленно сообщите администратору. Мы оперативно решим проблему или предоставим вам другое рабочее место.</p>
                            <p>Ни в коем случае не пытайтесь самостоятельно исправлять неисправности - это может привести к повреждению оборудования.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Можно ли оставить личные вещи в клубе?</h3>
                        <div class="tgg-faq__answer">
                            <p>Вы можете оставить личные вещи, но клуб не несет ответственности за их сохранность. Рекомендуем не оставлять ценные вещи без присмотра или брать их с собой.</p>
                            <p>Администрация не несет ответственности за утерю или кражу личных вещей посетителей.</p>
                        </div>
                    </div>
                    
                    <div class="tgg-faq__item">
                        <h3 class="tgg-faq__question">Как связаться с администрацией?</h3>
                        <div class="tgg-faq__answer">
                            <?php
                            $phone = function_exists('get_field') ? get_field('phone_main', 'option') : '8 (992) 222-62-72';
                            $email = function_exists('get_field') ? get_field('email_main', 'option') : 'vr.kungur@mail.ru';
                            $telegram = function_exists('get_field') ? get_field('telegram_username', 'option') : '@tochaGgKungur';
                            ?>
                            <p>Вы можете связаться с нами:</p>
                            <ul>
                                <?php if ($phone) : ?>
                                    <li><strong>По телефону:</strong> <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></li>
                                <?php endif; ?>
                                <?php if ($email) : ?>
                                    <li><strong>По email:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
                                <?php endif; ?>
                                <?php if ($telegram) : ?>
                                    <li><strong>Telegram-канал:</strong> <a href="https://t.me/<?php echo esc_attr(ltrim($telegram, '@')); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($telegram); ?></a></li>
                                <?php endif; ?>
                                <li><strong>В социальных сетях</strong> - найдите нас в ВКонтакте</li>
                                <li><strong>Лично в клубе</strong> - подойдите к администратору</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
            
            <?php if (locate_template('template-parts/components/info-notice.php')) : ?>
                <?php get_template_part('template-parts/components/info-notice'); ?>
            <?php endif; ?>
        </article>
    </div>
</main>

<?php get_footer(); ?>
