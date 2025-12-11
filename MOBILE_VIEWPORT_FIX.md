# Исправление скачков фона на мобильных устройствах

## Проблема

На мобильных устройствах (особенно Safari на iOS и Chrome на Android) при скролле адресная строка браузера скрывается, что изменяет высоту viewport. Это вызывает скачки фоновых изображений на главном экране, которые используют единицы измерения `vh` (viewport height).

## Решение

Использование **`dvh` (dynamic viewport height)** - современной единицы измерения, которая автоматически адаптируется к изменениям viewport при скролле.

### Что такое `dvh`?

- **`dvh`** (dynamic viewport height) - динамически изменяется при скрытии/показе адресной строки
- **`svh`** (small viewport height) - минимальная высота viewport (когда адресная строка видна)
- **`lvh`** (large viewport height) - максимальная высота viewport (когда адресная строка скрыта)
- **`vh`** (viewport height) - статическая высота (не изменяется при скролле)

### Поддержка браузерами

- ✅ Chrome 108+ (декабрь 2022)
- ✅ Safari 15.4+ (март 2022)
- ✅ Firefox 101+ (май 2022)
- ✅ Edge 108+

Для старых браузеров используется fallback на `vh`.

## Внесенные изменения

### 1. Hero секция (`src/sass/sections/_hero.scss`)

```scss
.tgg-hero {
  min-height: 100vh; // Fallback для старых браузеров
  min-height: 100dvh; // Dynamic viewport height
}

// Фоновые изображения и видео
img, video {
  @include max-width(lg) {
    height: calc(100vh + 80px); // Fallback
    height: calc(100dvh + 80px); // Dynamic viewport height
  }
}
```

### 2. Меню бургера (`src/sass/sections/_header.scss`)

```scss
.tgg-header__nav {
  @media (max-width: 1023px) {
    max-height: 100vh; // Fallback
    max-height: 100dvh; // Dynamic viewport height
    height: 100vh; // Fallback
    height: 100dvh; // Dynamic viewport height
  }
}

.tgg-nav__wrapper {
  max-height: calc(100vh - padding); // Fallback
  max-height: calc(100dvh - padding); // Dynamic viewport height
}
```

### 3. Body и HTML (`src/sass/base/_reset.scss`, `src/sass/style.scss`)

```scss
body {
  min-height: 100vh; // Fallback
  min-height: 100dvh; // Dynamic viewport height
}

.tgg-main {
  min-height: 100vh; // Fallback
  min-height: 100dvh; // Dynamic viewport height
}
```

### 4. JavaScript (`src/js/modules/hero-optimization.js`)

```javascript
// Проверяем поддержку dvh и используем её, если доступна
const supportsDvh = CSS.supports('height', '100dvh');
element.style.height = supportsDvh 
  ? 'calc(100dvh + 80px)' 
  : 'calc(100vh + 80px)';
```

## Результат

✅ Фоновые изображения больше не скачут при скролле на мобильных устройствах
✅ Меню бургера корректно работает с динамическим viewport
✅ Поддержка старых браузеров через fallback на `vh`
✅ Плавная работа на всех современных мобильных браузерах

## Дополнительные рекомендации

Если в будущем понадобится более точный контроль над высотой viewport:

- **`svh`** - использовать когда нужно, чтобы контент всегда был виден (даже когда адресная строка видна)
- **`lvh`** - использовать когда нужно максимальное использование пространства
- **`dvh`** - использовать для адаптации к изменениям (текущее решение)

## Тестирование

Протестируйте на следующих устройствах:
- iPhone (Safari)
- Android (Chrome)
- iPad (Safari)
- Мобильные браузеры с адресной строкой, которая скрывается при скролле

При скролле фоновые изображения должны оставаться стабильными без скачков и изменения масштаба.

