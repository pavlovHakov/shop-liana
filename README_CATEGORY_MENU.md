# Меню категорий в Header

## Обзор

Меню категорий добавлено в `header.php` и отображает все доступные категории товаров из базы данных. Меню автоматически подсвечивает активную категорию и обеспечивает навигацию между страницами.

## Структура меню

### HTML структура в header.php

```html
<header class="header">
  <div class="header-top">
    <!-- Логотип и иконки -->
  </div>

  <nav class="nav-menu">
    <div class="nav-container">
      <ul class="nav-list">
        <li class="nav-item active">
          <a href="/index.php" class="nav-link">Главная</a>
        </li>
        <?php foreach ($categories as $category): ?>
        <li class="nav-item">
          <a
            href="/category.php?categoryId=<?= $category['id'] ?>"
            class="nav-link"
          >
            <?= htmlspecialchars($category['name']) ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>
</header>
```

### PHP логика

```php
// Получение категорий из базы данных
$categories = getCategories($mysqli);

// Определение текущей страницы и категории
$currentPage = basename($_SERVER['PHP_SELF']);
$currentCategoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : null;

// Подсветка активного пункта меню
$isActive = ($currentCategoryId === $category['id']) ? 'active' : '';
```

## CSS стили

### Основные классы

- `.header` - основной контейнер header
- `.header-top` - верхняя часть с логотипом и иконками
- `.nav-menu` - навигационное меню
- `.nav-container` - контейнер для центрирования меню
- `.nav-list` - список пунктов меню
- `.nav-item` - отдельный пункт меню
- `.nav-link` - ссылка в пункте меню

### Стили меню

```css
.nav-menu {
  background-color: #3a6b8a;
  border-top: 1px solid #2d5a7a;
  border-bottom: 1px solid #2d5a7a;
}

.nav-link {
  display: block;
  color: white;
  text-decoration: none;
  padding: 15px 20px;
  font-size: 16px;
  font-weight: 500;
  transition: all 0.3s ease;
  border-bottom: 3px solid transparent;
}

.nav-link:hover {
  background-color: #2d5a7a;
  color: #fff;
  border-bottom-color: #06ada8;
}

.nav-item.active .nav-link {
  background-color: #2d5a7a;
  color: #06ada8;
  border-bottom-color: #06ada8;
  font-weight: 600;
}
```

## Функциональность

### Автоматическое определение активной категории

1. **Главная страница**: активен пункт "Главная"
2. **Страница категории**: активен соответствующий пункт меню
3. **Другие страницы**: активен пункт "Главная"

### Навигация

- Клик по "Главная" → переход на `/index.php`
- Клик по категории → переход на `/category.php?categoryId=X`
- Все ссылки открываются в том же окне

### Адаптивность

Меню адаптируется под мобильные устройства:

- На экранах меньше 768px меню становится вертикальным
- Уменьшается отступы и размеры шрифтов
- Сохраняется функциональность подсветки активного пункта

## Интеграция с существующим кодом

### Подключенные файлы

- `templase/header.php` - основной файл с меню
- `style/header.css` - стили для меню
- `function/functions.php` - функция `getCategories()`
- `function/db.php` - подключение к базе данных

### Используемые функции

```php
// Получение всех категорий
function getCategories($mysqli) {
   $result = $mysqli->query("SELECT id, name FROM category");
   $categories = [];

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $categories[$row['id']] = $row;
      }
   }

   return $categories;
}
```

## Доступные категории

Согласно тестированию, в базе данных доступны следующие категории:

1. **ID: 1** - Платья
2. **ID: 2** - Футболка и ...
3. **ID: 3** - Костюмы
4. **ID: 4** - Рубашка и ...
5. **ID: 5** - Рубашки
6. **ID: 6** - Жакет

## Использование

### В существующих страницах

Меню автоматически подключается через:

```php
<?php require_once 'templase/header.php'; ?>
```

### Добавление новых категорий

1. Добавить категорию в таблицу `category` базы данных
2. Меню автоматически обновится при следующей загрузке страницы

### Кастомизация стилей

Для изменения внешнего вида меню отредактируйте файл `style/header.css`:

```css
/* Изменение цвета фона меню */
.nav-menu {
  background-color: #your-color;
}

/* Изменение цвета активного пункта */
.nav-item.active .nav-link {
  color: #your-active-color;
}
```

## Совместимость

Меню категорий совместимо с:

- ✅ Системой избранных товаров
- ✅ Системой выбора размеров
- ✅ Фильтрацией товаров
- ✅ Адаптивным дизайном
- ✅ Всеми существующими страницами

## Отладка

### Проверка работы меню

1. Откройте любую страницу сайта
2. Проверьте отображение меню в header
3. Кликните на разные пункты меню
4. Убедитесь, что активный пункт подсвечивается
5. Проверьте работу на мобильных устройствах

### Возможные проблемы

1. **Меню не отображается**: проверьте подключение `header.php`
2. **Категории не загружаются**: проверьте подключение к базе данных
3. **Стили не применяются**: проверьте подключение `header.css`
4. **Активный пункт не подсвечивается**: проверьте логику определения текущей страницы
