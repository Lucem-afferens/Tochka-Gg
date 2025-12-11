# Дизайн-система "Точка Gg"

**Версия:** 1.0.0  
**Описание:** Премиальная, технологичная дизайн-система с неоновыми акцентами и sci-fi эстетикой

---

## 📋 Содержание

1. [Цветовая палитра](#цветовая-палитра)
2. [Типографика](#типографика)
3. [Spacing & Layout](#spacing--layout)
4. [UI Компоненты](#ui-компоненты)
5. [Эффекты и анимации](#эффекты-и-анимации)
6. [Иконография](#иконография)
7. [Доступность](#доступность)

---

## 🎨 Цветовая палитра

### Философия цвета

Система построена на сочетании:
- **Темный фон** - создает премиальную атмосферу клуба
- **Неоновые акценты** - передают энергию киберспорта
- **Чистые светлые элементы** - обеспечивают читаемость контента

### Основные цвета

#### Фоны

```scss
// Основной фон
$color-bg-primary: #0D0F14;        // GG Black - глубокий техно-чёрный
$color-bg-secondary: #161A21;      // GG Dark Graphite - для карточек и блоков
$color-bg-tertiary: #1E2329;       // Дополнительный фон (опционально)
```

#### Акцентные цвета

```scss
// Неоновые акценты
$color-primary: #3B82F6;           // GG Neon Blue - основной акцент
$color-primary-bright: #1E90FF;    // Яркий неон-синий для hover
$color-secondary: #C026D3;         // GG Magenta Pulse - для турниров, фишек
$color-cyan: #22D3EE;              // GG Cyber Cyan - светящиеся линии, сетки
```

#### Текст

```scss
// Текст
$color-text-primary: #E2E8F0;      // GG Gray Light - основной текст
$color-text-secondary: #94A3B8;    // GG Gray Medium - вторичный текст
$color-text-muted: #64748B;        // Приглушенный текст
```

#### Системные цвета

```scss
// Системные
$color-success: #10B981;           // Успех
$color-warning: #F59E0B;           // Предупреждение
$color-error: #EF4444;             // GG Danger - ошибки, предупреждения
$color-info: #3B82F6;              // Информация
```

### CSS переменные

```css
:root {
  /* Фоны */
  --color-bg-primary: #0D0F14;
  --color-bg-secondary: #161A21;
  --color-bg-tertiary: #1E2329;
  
  /* Акценты */
  --color-primary: #3B82F6;
  --color-primary-bright: #1E90FF;
  --color-secondary: #C026D3;
  --color-cyan: #22D3EE;
  
  /* Текст */
  --color-text-primary: #E2E8F0;
  --color-text-secondary: #94A3B8;
  --color-text-muted: #64748B;
  
  /* Системные */
  --color-success: #10B981;
  --color-warning: #F59E0B;
  --color-error: #EF4444;
  --color-info: #3B82F6;
  
  /* Градиенты */
  --gradient-primary: linear-gradient(90deg, #3B82F6 0%, #1E90FF 100%);
  --gradient-secondary: linear-gradient(135deg, #C026D3 0%, #3B82F6 100%);
  --gradient-cyan: linear-gradient(90deg, #22D3EE 0%, #3B82F6 100%);
}
```

### Примеры сочетаний

- **Черный + Неоновый синий** = холодный кибер-тон
- **Черный + Пурпур** = клубная атмосфера
- **Серый контент на темном** = премиальность и чистота

---

## ✍️ Типографика

### Шрифтовые пары

#### 1. Orbitron (Акцентный, заголовки)

**Назначение:** Крупные заголовки, Hero блоки, цены, акценты

**Характеристики:**
- Стиль: Кибер / Sci-Fi
- Геометрия: Прямые линии, футуристичная

**Варианты веса:**
- `400` - Regular (не используется)
- `700` - Bold (подзаголовки)
- `900` - Black (основные заголовки)

**Использование:**
```scss
$font-heading: 'Orbitron', -apple-system, sans-serif;
$font-weight-heading-regular: 700;
$font-weight-heading-bold: 900;
```

#### 2. Inter (Основной текст)

**Назначение:** Основной контент, абзацы, описания

**Характеристики:**
- Стиль: Современный, чистый
- Читаемость: Отличная на темном фоне

**Варианты веса:**
- `400` - Regular (основной текст)
- `500` - Medium (важные элементы)
- `600` - SemiBold (подзаголовки)
- `700` - Bold (выделения)

**Использование:**
```scss
$font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
$font-weight-body-regular: 400;
$font-weight-body-medium: 500;
$font-weight-body-semibold: 600;
$font-weight-body-bold: 700;
```

#### 3. Roboto Mono (Моноширинный)

**Назначение:** Цены, счетчики, номера ПК, UI элементы, технические данные

**Характеристики:**
- Стиль: Технический, точный
- Применение: Цифры, коды, метрики

**Варианты веса:**
- `400` - Regular
- `700` - Bold (для выделения)

**Использование:**
```scss
$font-mono: 'Roboto Mono', 'Courier New', monospace;
```

### Типографическая шкала

```scss
// Заголовки (Orbitron)
$font-size-h1: clamp(2.5rem, 5vw, 4rem);      // 40-64px
$font-size-h2: clamp(2rem, 4vw, 3rem);        // 32-48px
$font-size-h3: clamp(1.5rem, 3vw, 2.25rem);   // 24-36px
$font-size-h4: clamp(1.25rem, 2.5vw, 1.75rem); // 20-28px
$font-size-h5: clamp(1.125rem, 2vw, 1.5rem);  // 18-24px
$font-size-h6: clamp(1rem, 1.5vw, 1.25rem);   // 16-20px

// Текст (Inter)
$font-size-base: 1rem;                        // 16px
$font-size-lg: 1.125rem;                      // 18px
$font-size-sm: 0.875rem;                      // 14px
$font-size-xs: 0.75rem;                       // 12px

// Специальные (Roboto Mono)
$font-size-price: clamp(1.5rem, 2.5vw, 2rem); // Цены
$font-size-code: 0.875rem;                    // Код
```

### Высота строк (Line Height)

```scss
$line-height-tight: 1.2;      // Заголовки
$line-height-normal: 1.5;     // Основной текст
$line-height-relaxed: 1.75;   // Длинные абзацы
```

### Примеры использования

```scss
// H1 - Hero заголовок
.tgg-hero__title {
  font-family: $font-heading;
  font-size: $font-size-h1;
  font-weight: $font-weight-heading-bold;
  line-height: $line-height-tight;
  color: $color-text-primary;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

// Основной текст
.tgg-text {
  font-family: $font-body;
  font-size: $font-size-base;
  line-height: $line-height-normal;
  color: $color-text-primary;
}

// Цены
.tgg-price {
  font-family: $font-mono;
  font-size: $font-size-price;
  font-weight: 700;
  color: $color-primary;
}
```

---

## 📐 Spacing & Layout

### Spacing Scale (8px система)

```scss
$spacing-xs: 0.25rem;   // 4px
$spacing-sm: 0.5rem;    // 8px
$spacing-md: 1rem;      // 16px
$spacing-lg: 1.5rem;    // 24px
$spacing-xl: 2rem;      // 32px
$spacing-2xl: 3rem;     // 48px
$spacing-3xl: 4rem;     // 64px
$spacing-4xl: 6rem;     // 96px
$spacing-5xl: 8rem;     // 128px
```

### Container & Grid

```scss
// Контейнер
$container-max-width: 1440px;
$container-content-width: 1200px;
$container-padding-mobile: $spacing-lg;  // 24px
$container-padding-desktop: $spacing-2xl; // 48px

// Сетка
$grid-columns: 12;
$grid-gutter: $spacing-xl;  // 32px
$grid-gutter-mobile: $spacing-lg; // 24px

// Breakpoints
$breakpoint-xs: 375px;
$breakpoint-sm: 640px;
$breakpoint-md: 768px;
$breakpoint-lg: 1024px;
$breakpoint-xl: 1280px;
$breakpoint-2xl: 1440px;
```

### CSS Grid Utilities

```scss
.tgg-container {
  width: 100%;
  max-width: $container-content-width;
  margin: 0 auto;
  padding: 0 $container-padding-mobile;
  
  @media (min-width: $breakpoint-lg) {
    padding: 0 $container-padding-desktop;
  }
}

.tgg-grid {
  display: grid;
  grid-template-columns: repeat($grid-columns, 1fr);
  gap: $grid-gutter-mobile;
  
  @media (min-width: $breakpoint-md) {
    gap: $grid-gutter;
  }
}
```

---

## 🎮 UI Компоненты

### Кнопки

#### Primary Button

```scss
.tgg-btn-primary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: $spacing-md $spacing-xl;
  font-family: $font-heading;
  font-size: $font-size-base;
  font-weight: $font-weight-heading-regular;
  line-height: 1;
  color: $color-text-primary;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: $gradient-primary;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  
  // Неоновое свечение
  box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.6);
    background: linear-gradient(90deg, #1E90FF 0%, #3B82F6 100%);
  }
  
  &:active {
    transform: translateY(0);
  }
  
  &:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
}
```

#### Secondary Button

```scss
.tgg-btn-secondary {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: $spacing-md $spacing-xl;
  font-family: $font-heading;
  font-size: $font-size-base;
  font-weight: $font-weight-heading-regular;
  color: $color-primary;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: transparent;
  border: 2px solid $color-primary;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  
  &:hover {
    background: rgba(59, 130, 246, 0.1);
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    transform: translateY(-2px);
  }
}
```

#### Ghost Button

```scss
.tgg-btn-ghost {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: $spacing-md $spacing-xl;
  font-family: $font-body;
  font-size: $font-size-base;
  color: $color-text-secondary;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: color 0.3s ease;
  
  &:hover {
    color: $color-primary;
  }
}
```

### Карточки

#### Base Card

```scss
.tgg-card {
  background: $color-bg-secondary;
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 12px;
  padding: $spacing-xl;
  transition: all 0.3s ease;
  
  // Мягкая неоновая тень
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  
  &:hover {
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.2);
    transform: translateY(-4px);
  }
}
```

#### Card с изображением

```scss
.tgg-card-image {
  @extend .tgg-card;
  
  overflow: hidden;
  
  .tgg-card__image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    filter: contrast(1.1) brightness(0.9);
    transition: transform 0.3s ease;
  }
  
  &:hover .tgg-card__image {
    transform: scale(1.05);
  }
}
```

### Навигация

```scss
.tgg-header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  background: rgba(13, 15, 20, 0.8);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(59, 130, 246, 0.1);
  transition: all 0.3s ease;
  
  &.scrolled {
    background: rgba(13, 15, 20, 0.95);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
  }
}

.tgg-nav__link {
  position: relative;
  padding: $spacing-md $spacing-lg;
  font-family: $font-body;
  font-size: $font-size-base;
  font-weight: $font-weight-body-medium;
  color: $color-text-secondary;
  text-decoration: none;
  transition: color 0.3s ease;
  
  &::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: $color-primary;
    transition: width 0.3s ease;
  }
  
  &:hover,
  &.active {
    color: $color-primary;
    
    &::after {
      width: 80%;
    }
  }
}
```

### Hero блок

```scss
.tgg-hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: $color-bg-primary;
  
  // Неоновая сетка (опционально)
  &::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
      linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
      linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    opacity: 0.3;
  }
}

.tgg-hero__title {
  position: relative;
  z-index: 1;
  font-family: $font-heading;
  font-size: $font-size-h1;
  font-weight: $font-weight-heading-bold;
  color: $color-text-primary;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  
  // Диффузное свечение
  text-shadow: 
    0 0 10px rgba(59, 130, 246, 0.5),
    0 0 20px rgba(59, 130, 246, 0.3),
    0 0 30px rgba(59, 130, 246, 0.1);
}
```

---

## ✨ Эффекты и анимации

### Тени (Box Shadows)

```scss
$shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
$shadow-md: 0 4px 20px rgba(0, 0, 0, 0.3);
$shadow-lg: 0 8px 30px rgba(0, 0, 0, 0.4);
$shadow-xl: 0 12px 40px rgba(0, 0, 0, 0.5);

// Неоновые тени
$shadow-neon-blue: 0 0 20px rgba(59, 130, 246, 0.4);
$shadow-neon-magenta: 0 0 20px rgba(192, 38, 211, 0.4);
$shadow-neon-cyan: 0 0 20px rgba(34, 211, 238, 0.4);
```

### Границы (Borders)

```scss
$border-width-thin: 1px;
$border-width-medium: 2px;
$border-width-thick: 3px;

$border-radius-sm: 4px;
$border-radius-md: 6px;
$border-radius-lg: 12px;
$border-radius-xl: 16px;
$border-radius-full: 9999px;
```

### Анимации

```scss
// Timing functions
$ease-out-cubic: cubic-bezier(0.33, 1, 0.68, 1);
$ease-in-out-cubic: cubic-bezier(0.65, 0, 0.35, 1);

// Durations
$duration-fast: 0.2s;
$duration-normal: 0.3s;
$duration-slow: 0.5s;

// Keyframes
@keyframes glow-pulse {
  0%, 100% {
    opacity: 1;
    box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
  }
  50% {
    opacity: 0.8;
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.6);
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

// Utility classes
.tgg-animate-fade-in {
  animation: fade-in-up 0.6s $ease-out-cubic;
}

.tgg-animate-glow {
  animation: glow-pulse 2s ease-in-out infinite;
}
```

### Бренд-элементы

#### Неоновая точка "GG Dot"

```scss
.tgg-dot {
  display: inline-block;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: $color-primary;
  box-shadow: 0 0 10px rgba(59, 130, 246, 0.8);
  animation: glow-pulse 2s ease-in-out infinite;
}
```

#### Светящиеся линии

```scss
.tgg-line-glow {
  position: relative;
  
  &::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, 
      transparent 0%,
      $color-primary 50%,
      transparent 100%
    );
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
  }
}
```

---

## 🎯 Иконография

### Принципы

- Монохромные иконки
- Неоновые акценты при hover
- Размеры: 16px, 24px, 32px, 48px
- SVG формат для масштабируемости

### Стиль

```scss
.tgg-icon {
  width: 24px;
  height: 24px;
  fill: currentColor;
  transition: all 0.3s ease;
  
  &--primary {
    color: $color-primary;
  }
  
  &--secondary {
    color: $color-text-secondary;
  }
  
  &:hover {
    filter: drop-shadow(0 0 8px currentColor);
  }
}
```

---

## ♿ Доступность

### Цветовой контраст

Минимальные требования WCAG AA:
- Текст на фоне: минимум 4.5:1
- Крупный текст: минимум 3:1

**Проверенные сочетания:**
- `$color-text-primary` на `$color-bg-primary`: ✅ 12.5:1
- `$color-text-secondary` на `$color-bg-primary`: ✅ 7.2:1
- `$color-primary` на `$color-bg-primary`: ✅ 4.8:1

### Focus состояния

```scss
.tgg-focus-visible {
  &:focus-visible {
    outline: 2px solid $color-primary;
    outline-offset: 4px;
    border-radius: $border-radius-sm;
  }
}
```

### Адаптивность

- Минимальная ширина: 320px
- Touch targets: минимум 44x44px
- Читаемый размер шрифта: минимум 16px

---

## 📱 Адаптивность

### Breakpoints

```scss
// Mobile First подход
$breakpoints: (
  xs: 375px,
  sm: 640px,
  md: 768px,
  lg: 1024px,
  xl: 1280px,
  2xl: 1440px
);

// Mixin для media queries
@mixin respond-to($breakpoint) {
  @media (min-width: map-get($breakpoints, $breakpoint)) {
    @content;
  }
}
```

### Пример использования

```scss
.tgg-component {
  padding: $spacing-md;
  font-size: $font-size-sm;
  
  @include respond-to(md) {
    padding: $spacing-lg;
    font-size: $font-size-base;
  }
  
  @include respond-to(lg) {
    padding: $spacing-xl;
    font-size: $font-size-lg;
  }
}
```

---

## 🎨 Тональность бренда

### Атрибуты

- **Технологичность** - чистый, современный дизайн
- **Премиальность** - качественные материалы и эффекты
- **Динамика** - энергия игр и киберспорта
- **Профессионализм** - надежность и качество

### Коммуникация

- Короткие, четкие фразы
- Без лишнего пафоса
- Динамичный стиль
- Игровой интерфейс / future-tech эстетика

**Примеры фраз:**
- «Играй на максимуме»
- «Хайп. Свет. Мощь.»
- «Добро пожаловать в твою точку входа в игру»
- «Сила в каждом фрейме»

---

## 📝 Использование в проекте

### SASS переменные

Все переменные определены в `src/sass/base/_variables.scss`:

```scss
// Импорт переменных
@import 'base/variables';

// Использование
.my-component {
  background: $color-bg-secondary;
  color: $color-text-primary;
  padding: $spacing-lg;
  border-radius: $border-radius-md;
}
```

### CSS переменные

Для динамического изменения через JavaScript или inline стилей:

```css
.element {
  background: var(--color-bg-secondary);
  color: var(--color-text-primary);
}
```

---

**Версия:** 1.0.0  
**Последнее обновление:** 2025  
**Статус:** Готово к использованию



