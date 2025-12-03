# Настройка ACF полей для темы "Точка Gg"

## 🎯 Быстрый старт

После установки ACF нужно создать поля. Ниже полная инструкция по настройке.

---

## 📋 Структура ACF полей

### 1. Options Page (Глобальные настройки)

**Создайте Options Page:**
1. В админке: **Custom Fields → Options Pages**
2. Нажмите **"Add New"**
3. Заполните:
   - **Page Title:** `Настройки темы`
   - **Menu Slug:** `theme-options`
   - Нажмите **"Publish"**

**Создайте группу полей для Options:**

1. **Custom Fields → Field Groups → Add New**
2. **Group Title:** `Настройки темы (Options)`
3. **Location Rules:**
   - Show this field group if: `Options Page` → `is equal to` → `theme-options`
4. **Fields:**

#### Логотип
- **Field Label:** `Логотип`
- **Field Name:** `logo`
- **Field Type:** `Image`
- **Return Format:** `Image Array`

#### Логотип в футере
- **Field Label:** `Логотип в футере`
- **Field Name:** `footer_logo`
- **Field Type:** `Image`
- **Return Format:** `Image Array`

#### Контакты
- **Field Label:** `Основной телефон`
- **Field Name:** `phone_main`
- **Field Type:** `Text`
- **Default Value:** `+7 992 222-62-72`

- **Field Label:** `Адрес (полный)`
- **Field Name:** `address_full`
- **Field Type:** `Textarea`
- **Default Value:** `Пермский край, г. Кунгур, ул. Голованова, 43, вход с торца здания, цокольный этаж`

#### Социальные сети
- **Field Label:** `Социальные сети`
- **Field Name:** `social_networks`
- **Field Type:** `Repeater`
- **Sub Fields:**
  - `platform_name` (Text) - Название платформы
  - `url` (URL) - Ссылка
  - `icon` (Image) - Иконка

#### Копирайт
- **Field Label:** `Текст копирайта`
- **Field Name:** `copyright_text`
- **Field Type:** `Text`
- **Default Value:** `ИП Морозов Алексей Алексеевич`

---

### 2. Главная страница (Front Page)

**Создайте группу полей для главной страницы:**

1. **Custom Fields → Field Groups → Add New**
2. **Group Title:** `Главная страница`
3. **Location Rules:**
   - Show this field group if: `Page Template` → `is equal to` → `Front Page`
   - OR: `Front Page` → `is equal to` → `true`

#### Hero секция
- **Field Label:** `Заголовок`
- **Field Name:** `hero_title`
- **Field Type:** `Text`
- **Default Value:** `Точка Gg`

- **Field Label:** `Подзаголовок`
- **Field Name:** `hero_subtitle`
- **Field Type:** `Text`
- **Default Value:** `Премиальный компьютерный клуб нового поколения`

- **Field Label:** `Описание`
- **Field Name:** `hero_description`
- **Field Type:** `Textarea`
- **Default Value:** `Стильное и технологичное игровое пространство, где сочетаются мощное железо, комфорт и высокий стандарт сервиса`

- **Field Label:** `Тип фона`
- **Field Name:** `hero_background_type`
- **Field Type:** `Select`
- **Choices:**
  - `image` → `Изображение`
  - `video` → `Видео`
- **Default Value:** `image`
- **Required:** `Yes`

- **Field Label:** `Фоновое изображение`
- **Field Name:** `hero_background_image`
- **Field Type:** `Image`
- **Return Format:** `Image Array`
- **Conditional Logic:** Показывать если `hero_background_type` равно `image`

- **Field Label:** `Фоновое видео`
- **Field Name:** `hero_background_video`
- **Field Type:** `File`
- **Return Format:** `File URL`
- **Library:** `all`
- **Mime Types:** `mp4, webm, ogv`
- **Conditional Logic:** Показывать если `hero_background_type` равно `video`
- **Instructions:** Загрузите видео в формате MP4. Рекомендуется использовать сжатое видео для быстрой загрузки. Если видео не загрузится, будет показано фоновое изображение (если оно указано).

- **Field Label:** `Текст кнопки`
- **Field Name:** `hero_cta_text`
- **Field Type:** `Text`
- **Default Value:** `Узнать больше`

- **Field Label:** `Ссылка кнопки`
- **Field Name:** `hero_cta_link`
- **Field Type:** `Text`
- **Default Value:** `#about`

#### Переключатели секций (опционально)
- **Field Label:** `Показать секцию "О клубе"`
- **Field Name:** `about_section_enabled`
- **Field Type:** `True / False`
- **Default Value:** `1`

- **Field Label:** `Показать секцию "Преимущества"`
- **Field Name:** `advantages_section_enabled`
- **Field Type:** `True / False`
- **Default Value:** `1`

#### Секция "Ближайшие турниры"
- **Field Label:** `Заголовок секции`
- **Field Name:** `tournaments_preview_title`
- **Field Type:** `Text`
- **Default Value:** `Ближайшие турниры`

- **Field Label:** `Количество турниров`
- **Field Name:** `tournaments_preview_count`
- **Field Type:** `Number`
- **Default Value:** `3`
- **Min:** `1`
- **Max:** `6`

- **Field Label:** `Тип фона`
- **Field Name:** `tournaments_preview_bg_type`
- **Field Type:** `Select`
- **Choices:**
  - `image` → `Изображение`
  - `video` → `Видео`
- **Default Value:** `image`
- **Required:** `Yes`

- **Field Label:** `Фоновое изображение`
- **Field Name:** `tournaments_preview_bg_image`
- **Field Type:** `Image`
- **Return Format:** `Image Array`
- **Conditional Logic:** Показывать если `tournaments_preview_bg_type` равно `image`

- **Field Label:** `Фоновое видео`
- **Field Name:** `tournaments_preview_bg_video`
- **Field Type:** `File`
- **Return Format:** `File URL`
- **Library:** `all`
- **Mime Types:** `mp4, webm, ogv`
- **Conditional Logic:** Показывать если `tournaments_preview_bg_type` равно `video`
- **Instructions:** Загрузите видео в формате MP4. Фон будет затемнен для удобства чтения текста.

- **Field Label:** `Ссылка "Все турниры"`
- **Field Name:** `tournaments_preview_link`
- **Field Type:** `Text`
- **Instructions:** Оставьте пустым для автоматического определения ссылки на архив турниров.

- **Field Label:** `Показать секцию "Ближайшие турниры"`
- **Field Name:** `tournaments_preview_enabled`
- **Field Type:** `True / False`
- **Default Value:** `1`

*(И так далее для каждой секции)*

---

## 🚀 Альтернативный способ (импорт)

Если у вас есть возможность, можно импортировать готовые группы полей через JSON.

**Инструкция:**
1. Custom Fields → Tools → Import Field Groups
2. Загрузите JSON файл (если создам)
3. Нажмите Import

---

## 📝 Что дальше?

После создания полей:
1. Заполните их данными из файла `CLUB_CHARACTERISTICS.md`
2. Загрузите изображения через WordPress Media Library
3. Проверьте отображение на сайте

---

**Важно:** Поля с суффиксом `_enabled` (например, `about_section_enabled`) используются для включения/отключения секций. Если поле = `true` или пустое - секция показывается.

