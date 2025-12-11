<?php
/**
 * Theme Helpers
 * 
 * Вспомогательные функции
 *
 * @package Tochkagg_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Безопасная функция для получения поля SCF (Secure Custom Fields)
 * Работает даже если SCF не установлен
 * SCF совместим с API ACF, поэтому используем get_field()
 */
function tochkagg_get_field($selector, $post_id = false, $format_value = true) {
    if (function_exists('get_field')) {
        return get_field($selector, $post_id, $format_value);
    }
    return false;
}

/**
 * Безопасная функция для проверки существования поля SCF
 */
function tochkagg_has_field($selector, $post_id = false) {
    if (function_exists('get_field')) {
        $value = get_field($selector, $post_id);
        return $value !== false && $value !== null && $value !== '';
    }
    return false;
}

/**
 * Получить SCF поле с проверкой существования
 *
 * @param string $field_name Имя поля
 * @param mixed $default Значение по умолчанию
 * @return mixed
 */
function tochkagg_get_field_safe($field_name, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name);
        return $value !== false && $value !== null ? $value : $default;
    }
    return $default;
}

/**
 * Экранирование и вывод текста
 *
 * @param string $text Текст для вывода
 */
function tochkagg_echo($text) {
    echo esc_html($text);
}

/**
 * Экранирование и вывод HTML
 *
 * @param string $html HTML для вывода
 */
function tochkagg_echo_html($html) {
    echo wp_kses_post($html);
}

/**
 * Получить URL страницы по её slug (постоянной ссылке) или названию
 * 
 * @param string $slug Slug страницы (например: 'equipment', 'pricing', 'vr')
 * @param string $fallback URL по умолчанию, если страница не найдена
 * @return string URL страницы
 */
function tochkagg_get_page_url($slug, $fallback = '#') {
    // Маппинг английских slug на русские названия и slug
    $page_map = [
        'equipment' => [
            'title' => 'Оборудование',
            'slug_ru' => 'оборудование',
        ],
        'pricing' => [
            'title' => 'Цены',
            'slug_ru' => 'цены',
        ],
        'contacts' => [
            'title' => 'Контакты',
            'slug_ru' => 'контакты',
        ],
        'vr' => [
            'title' => 'VR арена',
            'slug_ru' => 'vr',
        ],
        'bar' => [
            'title' => 'Клубный бар',
            'slug_ru' => 'bar',
        ],
        'booking' => [
            'title' => 'Бронирование',
            'slug_ru' => 'бронирование',
        ],
    ];
    
    $page = null;
    
    // 1. Сначала пытаемся найти по английскому slug
    $page = get_page_by_path($slug);
    
    // 2. Если не найдено и есть маппинг, пытаемся найти по русскому slug
    if (!$page && isset($page_map[$slug])) {
        $page = get_page_by_path($page_map[$slug]['slug_ru']);
    }
    
    // 3. Если все еще не найдено, ищем по названию страницы
    if (!$page && isset($page_map[$slug])) {
        $pages = get_pages([
            'post_status' => 'publish',
            'number' => 50, // Достаточно для поиска
        ]);
        
        foreach ($pages as $p) {
            if (trim($p->post_title) === trim($page_map[$slug]['title'])) {
                $page = $p;
                break;
            }
        }
    }
    
    // 4. Если нашли страницу, возвращаем её URL
    if ($page && $page->post_status === 'publish') {
        return get_permalink($page->ID);
    }
    
    // 5. Если страница не найдена, возвращаем fallback или пробуем русский slug
    if ($fallback !== '#') {
        return $fallback;
    }
    
    // Если есть русский slug, пробуем его
    if (isset($page_map[$slug])) {
        return home_url('/' . $page_map[$slug]['slug_ru'] . '/');
    }
    
    return home_url('/' . $slug . '/');
}

/**
 * Получить URL placeholder изображения
 * 
 * Используется для визуальной индикации мест, где нужно загрузить медиа-файлы
 * 
 * @param int $width Ширина изображения
 * @param int $height Высота изображения
 * @param string $text Текст на placeholder (будет закодирован в URL)
 * @param string $bg_color Цвет фона (hex без #)
 * @param string $text_color Цвет текста (hex без #)
 * @return string URL placeholder изображения
 */
function tochkagg_get_placeholder_image($width = 800, $height = 600, $text = 'Placeholder', $bg_color = '1a1d29', $text_color = '3b82f6') {
    // Используем placehold.co - простой и надежный сервис
    // Если сервис недоступен, можно использовать альтернативу через picsum.photos
    $text_encoded = urlencode($text);
    // Используем более простой формат для надежности
    return "https://placehold.co/{$width}x{$height}/{$bg_color}/{$text_color}?text={$text_encoded}";
}

/**
 * Получить placeholder видео URL (для демонстрации)
 * 
 * @param string $label Метка для понимания назначения видео
 * @return string URL placeholder видео
 */
function tochkagg_get_placeholder_video($label = 'Video') {
    // Используем простое placeholder видео (можно заменить на реальное демо-видео)
    // Для демонстрации используем простой URL, который можно будет заменить
    // В реальности это должен быть URL на ваше демо-видео или просто инструкция
    return home_url('/wp-content/themes/tochkagg-theme/assets/videos/placeholder-video.mp4');
}

/**
 * Получить placeholder изображение или реальное SCF изображение
 * 
 * @param mixed $scf_image SCF изображение (array или false)
 * @param int $width Ширина placeholder
 * @param int $height Высота placeholder
 * @param string $placeholder_text Текст для placeholder
 * @return array Массив с URL и alt для изображения
 */
function tochkagg_get_image_or_placeholder($scf_image, $width = 800, $height = 600, $placeholder_text = 'Изображение') {
    // Проверяем, есть ли реальное изображение из SCF
    // SCF использует тот же формат данных, что и ACF
    if ($scf_image && is_array($scf_image) && !empty($scf_image['url']) && filter_var($scf_image['url'], FILTER_VALIDATE_URL)) {
        // Пытаемся получить реальные размеры изображения
        $image_width = isset($scf_image['width']) ? intval($scf_image['width']) : $width;
        $image_height = isset($scf_image['height']) ? intval($scf_image['height']) : $height;
        
        // Если размеры не указаны в данных SCF, пытаемся получить их из attachment
        if (isset($scf_image['ID'])) {
            $attachment_meta = wp_get_attachment_metadata($scf_image['ID']);
            if ($attachment_meta && isset($attachment_meta['width']) && isset($attachment_meta['height'])) {
                $image_width = $attachment_meta['width'];
                $image_height = $attachment_meta['height'];
            }
        }
        
        return [
            'url' => $scf_image['url'],
            'alt' => $scf_image['alt'] ?? $placeholder_text,
            'width' => $image_width,
            'height' => $image_height
        ];
    }
    
    // Всегда возвращаем placeholder, если реального изображения нет
    $placeholder_url = tochkagg_get_placeholder_image($width, $height, $placeholder_text);
    
    return [
        'url' => $placeholder_url,
        'alt' => $placeholder_text . ' (заглушка - загрузите своё изображение)',
        'width' => $width,
        'height' => $height
    ];
}

/**
 * Форматировать дату турнира в зависимости от типа
 * 
 * @param string $date_type Тип даты: 'exact' или 'month_only'
 * @param string $date Точная дата (Y-m-d) для типа 'exact'
 * @param int|string $month Месяц (1-12) для типа 'month_only'
 * @param int|string $year Год для типа 'month_only'
 * @return string Отформатированная строка даты
 */
function tochkagg_format_tournament_date($date_type, $date = '', $month = '', $year = '') {
    if ($date_type === 'month_only' && $month && $year) {
        // Только месяц и год
        $month_names = [
            1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
            5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
            9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
        ];
        $month_name = isset($month_names[intval($month)]) 
            ? $month_names[intval($month)] 
            : intval($month);
        return $month_name . ' ' . intval($year);
    } elseif ($date_type === 'exact' && $date) {
        // Точная дата
        return date_i18n('d F Y', strtotime($date));
    }
    return '';
}


