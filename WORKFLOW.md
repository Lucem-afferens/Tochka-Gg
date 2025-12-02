# Workflow разработки WordPress темы

## 🎯 Как работает разработка

Для WordPress темы используется **гибридный подход**:

1. **Vite** - для разработки фронтенда (SASS, JS) с hot reload
2. **Локальный WordPress** - для PHP, ACF, динамического контента

---

## 📋 Настройка окружения

### Вариант 1: Полная разработка (рекомендуется)

**Нужно:**
- Локальный WordPress сервер (Local, XAMPP, MAMP, или Docker)
- Vite dev сервер для фронтенда

**Как работает:**
1. Запускаете локальный WordPress (например, `http://tochkagg.local`)
2. Запускаете Vite dev сервер (`npm run dev`)
3. Vite компилирует SASS/JS и автоматически обновляет файлы в теме
4. Открываете WordPress в браузере и видите изменения

### Вариант 2: Только фронтенд

**Нужно:**
- Только Vite

**Ограничения:**
- Не будет работать PHP
- Не будет работать ACF
- Только статическая верстка

---

## ⚙️ Настройка Vite для WordPress темы

### Структура проекта

```
Tochka-Gg/                          # Корень проекта (Git репозиторий)
├── src/                            # Исходники для Vite
│   ├── sass/
│   └── js/
│
├── wp-content/themes/tochkagg-theme/  # WordPress тема (симлинк или копия)
│   ├── assets/                     # Скомпилированные файлы (из Vite)
│   │   ├── css/
│   │   └── js/
│   ├── inc/
│   ├── template-parts/
│   └── ...
│
└── vite.config.mjs
```

### Вариант A: Симлинк (рекомендуется)

Создаете симлинк темы в WordPress и разрабатываете в основном проекте:

```bash
# В папке wp-content/themes/
ln -s /path/to/Tochka-Gg/theme/ tochkagg-theme
```

### Вариант B: Копирование

Vite автоматически копирует скомпилированные файлы в тему при изменении.

---

## 🔧 Конфигурация Vite

### vite.config.mjs (для WordPress темы)

```javascript
import { defineConfig } from 'vite';
import { resolve } from 'path';
import { copyFileSync, mkdirSync, existsSync } from 'fs';

const THEME_PATH = resolve(__dirname, 'wp-content/themes/tochkagg-theme');
const ASSETS_PATH = resolve(THEME_PATH, 'assets');

// Плагин для копирования файлов в тему
const copyToTheme = () => {
  return {
    name: 'copy-to-theme',
    writeBundle() {
      // Копирование будет происходить после сборки
      console.log('✅ Files compiled. Copy to theme if needed.');
    },
    configureServer(server) {
      // При изменении файлов в dev режиме
      server.ws.on('vite:beforeFullReload', () => {
        console.log('🔄 Hot reload triggered');
      });
    }
  };
};

export default defineConfig({
  root: resolve(__dirname, 'src'),
  
  build: {
    outDir: ASSETS_PATH,
    emptyOutDir: true,
    manifest: true,
    
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name]-[hash].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return 'assets/[name]-[hash][extname]';
        },
      },
    },
    
    cssCodeSplit: false,
    sourcemap: true, // Включаем для dev
  },
  
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
        additionalData: `@import "${resolve(__dirname, 'src/sass/base/_variables.scss')}";`,
      },
    },
  },
  
  server: {
    port: 3000,
    open: false, // Не открываем браузер автоматически
    watch: {
      usePolling: true, // Для лучшей работы с симлинками
    },
  },
  
  plugins: [
    copyToTheme(),
  ],
});
```

### Упрощенная конфигурация (если тема в этом же проекте)

Если тема находится в `theme/` в корне проекта:

```javascript
import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  root: resolve(__dirname, 'src'),
  
  build: {
    outDir: resolve(__dirname, 'theme/assets'),
    emptyOutDir: true,
    
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/js/main.js'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return 'assets/[name]-[hash][extname]';
        },
      },
    },
  },
  
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
      },
    },
  },
  
  server: {
    port: 3000,
    open: false,
    watch: {
      usePolling: true,
    },
  },
});
```

---

## 🚀 Команды разработки

### package.json

```json
{
  "name": "tochkagg-theme",
  "version": "1.0.0",
  "scripts": {
    "dev": "vite",
    "build": "vite build",
    "build:watch": "vite build --watch",
    "preview": "vite preview"
  },
  "devDependencies": {
    "sass": "^1.90.0",
    "vite": "^7.0.6"
  }
}
```

### Процесс разработки

**Терминал 1: Vite dev сервер**
```bash
npm run dev
```
- Компилирует SASS → CSS
- Компилирует JS
- Hot reload при изменениях
- Выводит файлы в `theme/assets/`

**Терминал 2: Локальный WordPress**
- Запущен на `http://tochkagg.local` (или другой адрес)
- Открываете в браузере
- Видите изменения сразу после сохранения файлов

---

## 📁 Рекомендуемая структура

### Если тема в отдельной папке WordPress

```
project-root/
├── src/                    # Исходники
│   ├── sass/
│   └── js/
├── vite.config.mjs
├── package.json
└── [симлинк или копия в WordPress]
```

### Если тема в этом же проекте

```
project-root/
├── src/                    # Исходники для Vite
│   ├── sass/
│   └── js/
├── theme/                  # WordPress тема
│   ├── assets/            # Скомпилированные файлы (из Vite)
│   ├── inc/
│   ├── template-parts/
│   ├── style.css
│   └── functions.php
├── vite.config.mjs
└── package.json
```

---

## 🔄 Workflow по шагам

### 1. Первая настройка

```bash
# 1. Установить зависимости
npm install

# 2. Настроить путь к теме в vite.config.mjs

# 3. Запустить Vite dev
npm run dev

# 4. Открыть WordPress в браузере
# http://tochkagg.local (или ваш адрес)
```

### 2. Разработка

**Изменяете SASS:**
1. Редактируете `src/sass/style.scss`
2. Vite автоматически компилирует → `theme/assets/css/style.css`
3. Обновляете страницу WordPress → видите изменения

**Изменяете JavaScript:**
1. Редактируете `src/js/main.js`
2. Vite автоматически компилирует → `theme/assets/js/main.js`
3. Обновляете страницу WordPress → видите изменения

**Изменяете PHP/шаблоны:**
1. Редактируете файлы в `theme/`
2. Обновляете страницу WordPress → видите изменения
3. Vite не нужен для PHP

### 3. Сборка для продакшена

```bash
npm run build
```

Компилирует и минифицирует все файлы в `theme/assets/`

---

## 🎨 Hot Reload для CSS/JS

### Вариант 1: Автоматическое обновление

Vite компилирует файлы → WordPress подхватывает изменения → обновление страницы

### Вариант 2: Browser Sync (опционально)

Можно добавить плагин для автоматического обновления браузера:

```javascript
// vite.config.mjs
import { defineConfig } from 'vite';

export default defineConfig({
  // ... config
  server: {
    port: 3000,
    proxy: {
      '/': {
        target: 'http://tochkagg.local',
        changeOrigin: true,
      },
    },
  },
});
```

---

## ⚠️ Важные моменты

### 1. Пути к файлам

В WordPress используйте правильные пути:

```php
// functions.php
wp_enqueue_style(
    'tochkagg-style',
    get_template_directory_uri() . '/assets/css/style.css',
    array(),
    wp_get_theme()->get('Version')
);

wp_enqueue_script(
    'tochkagg-script',
    get_template_directory_uri() . '/assets/js/main.js',
    array(),
    wp_get_theme()->get('Version'),
    true
);
```

### 2. Версионирование

В dev режиме можно использовать версию с timestamp для избежания кеша:

```php
wp_enqueue_style(
    'tochkagg-style',
    get_template_directory_uri() . '/assets/css/style.css',
    array(),
    filemtime(get_template_directory() . '/assets/css/style.css')
);
```

### 3. Source maps

В development включайте source maps для отладки:

```javascript
// vite.config.mjs
build: {
  sourcemap: true, // или 'inline' для dev
}
```

---

## 📝 Примеры

### Пример 1: Простая разработка

```bash
# Терминал 1
npm run dev

# Открываете WordPress в браузере
# Редактируете src/sass/style.scss
# Видите изменения после обновления страницы
```

### Пример 2: Разработка с watch режимом

```bash
# Для production сборки с watch
npm run build:watch

# Редактируете файлы
# Автоматическая пересборка
```

---

## 🎯 Рекомендации

1. **Для разработки:** Используйте `npm run dev` - быстрый hot reload
2. **Для тестирования:** Периодически делайте `npm run build` для проверки production сборки
3. **Для продакшена:** Всегда используйте `npm run build` перед деплоем
4. **Путь к теме:** Настройте в `vite.config.mjs` под вашу структуру

---

## ✅ Преимущества этого подхода

- ✅ Hot reload для SASS/JS
- ✅ Современный стек (Vite, SASS модули)
- ✅ Быстрая разработка
- ✅ Полная интеграция с WordPress
- ✅ Работа с ACF, PHP, динамическим контентом
- ✅ Оптимизация для продакшена

---

**Готово к использованию!** 🚀

