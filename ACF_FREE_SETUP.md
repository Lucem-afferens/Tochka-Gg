# Настройка Options Page для ACF Free

## ⚠️ Важно

Options Pages **не включены** в бесплатную версию ACF. У вас есть два варианта:

---

## Вариант 1: Установить плагин-расширение (рекомендуется для ACF Free)

### Шаг 1: Установите плагин ACF Options Page

1. В админке WordPress перейдите: **Плагины → Добавить новый**
2. Найдите плагин: **"ACF Options Page"**
3. Или установите вручную: https://wordpress.org/plugins/acf-options-page/
4. Активируйте плагин

### Шаг 2: Проверьте работу

После активации плагина:
1. Перезагрузите страницу админки
2. В левом меню должно появиться **"Настройки темы"**
3. Если не появилось - проверьте, что код из `theme/inc/acf-options.php` подключен

---

## Вариант 2: Купить ACF Pro

1. Перейдите на: https://www.advancedcustomfields.com/pro/
2. Купите лицензию ACF Pro
3. Установите ACF Pro вместо ACF Free
4. Options Pages будут доступны автоматически

---

## Что уже сделано в теме

✅ Код для регистрации Options Page уже добавлен в `theme/inc/acf-options.php`
✅ Файл подключен в `functions.php`
✅ Options Page будет создана автоматически после установки плагина или ACF Pro

---

## После установки плагина

1. **Создайте группу полей:**
   - Перейдите: **Custom Fields → Field Groups → Add New**
   - Назовите группу: `Настройки темы (Options)`
   - В **Location Rules** добавьте:
     ```
     Show this field group if:
     Options Page → is equal to → theme-options
     ```
   - Добавьте нужные поля (см. `ACF_COMPLETE_GUIDE.md`)
   - Нажмите **"Publish"**

2. **Проверьте:**
   - В левом меню должно быть **"Настройки темы"**
   - Перейдите туда и проверьте, что поля отображаются

---

## Устранение проблем

### Options Page не появилась в меню

1. Проверьте, что плагин "ACF Options Page" активирован
2. Проверьте, что файл `theme/inc/acf-options.php` существует
3. Проверьте, что файл подключен в `functions.php`
4. Очистите кеш (если используете плагины кеширования)
5. Перезагрузите страницу админки

### Ошибки в консоли

- Убедитесь, что ACF активирован
- Проверьте версию PHP (требуется 7.4+)
- Проверьте версию WordPress (требуется 5.0+)

---

## Ссылки

- Плагин ACF Options Page: https://wordpress.org/plugins/acf-options-page/
- ACF Pro: https://www.advancedcustomfields.com/pro/
- Документация ACF: https://www.advancedcustomfields.com/resources/

