<?php
session_start();
require_once 'function/db.php';
require_once 'function/functions.php';

echo "<h2>Тест функциональности избранного</h2>";

// Проверяем подключение к базе данных
if ($mysqli->ping()) {
   echo "<p style='color: green;'>✓ Подключение к базе данных успешно</p>";
} else {
   echo "<p style='color: red;'>✗ Ошибка подключения к базе данных</p>";
   exit;
}

// Проверяем существование таблицы favorites
$result = $mysqli->query("SHOW TABLES LIKE 'favorites'");
if ($result->num_rows > 0) {
   echo "<p style='color: green;'>✓ Таблица favorites существует</p>";
} else {
   echo "<p style='color: red;'>✗ Таблица favorites не найдена</p>";
   echo "<p>Выполните SQL-скрипт create_favorites_table.sql</p>";
}

// Проверяем существование таблицы product
$result = $mysqli->query("SHOW TABLES LIKE 'product'");
if ($result->num_rows > 0) {
   echo "<p style='color: green;'>✓ Таблица product существует</p>";

   // Показываем количество товаров
   $result = $mysqli->query("SELECT COUNT(*) as count FROM product");
   $row = $result->fetch_assoc();
   echo "<p>Количество товаров в базе: " . $row['count'] . "</p>";
} else {
   echo "<p style='color: red;'>✗ Таблица product не найдена</p>";
}

// Проверяем session ID
$sessionId = session_id();
echo "<p>Session ID: " . $sessionId . "</p>";

// Показываем избранные товары текущей сессии
$favorites = getFavorites($mysqli, $sessionId);
echo "<p>Количество избранных товаров: " . count($favorites) . "</p>";

if (!empty($favorites)) {
   echo "<h3>Избранные товары:</h3>";
   echo "<ul>";
   foreach ($favorites as $product) {
      echo "<li>" . htmlspecialchars($product['name']) . " - " . $product['price'] . " ₴</li>";
   }
   echo "</ul>";
}

// Показываем несколько товаров для тестирования
$result = $mysqli->query("SELECT * FROM product LIMIT 3");
if ($result->num_rows > 0) {
   echo "<h3>Доступные товары для тестирования:</h3>";
   echo "<ul>";
   while ($row = $result->fetch_assoc()) {
      $isFavorite = isInFavorites($mysqli, $row['id'], $sessionId);
      $status = $isFavorite ? " (в избранном)" : " (не в избранном)";
      echo "<li>ID: " . $row['id'] . " - " . htmlspecialchars($row['name']) . $status . "</li>";
   }
   echo "</ul>";
}

echo "<h3>Тестирование API:</h3>";
echo "<p><a href='api/favorites.php' target='_blank'>Проверить API endpoint</a></p>";
echo "<p><a href='favorite.php' target='_blank'>Открыть страницу избранного</a></p>";
echo "<p><a href='index.php' target='_blank'>Открыть главную страницу</a></p>";
