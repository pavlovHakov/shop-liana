<?php
session_start();
require_once 'function/db.php';
require_once 'function/functions.php';

// Получаем количество избранных товаров
$sessionId = session_id();
$favorites = getFavorites($mysqli, $sessionId);
$favoritesCount = count($favorites);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style/reset.css">
   <link rel="stylesheet" href="style/header.css">
   <title>Тест Header с избранным</title>
</head>
<body>
   <?php include 'templase/header.php'; ?>
   
   <div style="padding: 20px;">
      <h1>Тест функциональности Header</h1>
      
      <h2>Информация о избранном:</h2>
      <p>Session ID: <?= $sessionId ?></p>
      <p>Количество избранных товаров: <?= $favoritesCount ?></p>
      
      <?php if (!empty($favorites)): ?>
         <h3>Избранные товары:</h3>
         <ul>
            <?php foreach ($favorites as $product): ?>
               <li><?= htmlspecialchars($product['name']) ?> - <?= $product['price'] ?> ₴</li>
            <?php endforeach; ?>
         </ul>
      <?php else: ?>
         <p>У вас пока нет избранных товаров</p>
      <?php endif; ?>
      
      <h2>Тестирование:</h2>
      <p>1. Кликните по иконке избранного в header - должна открыться страница favorite.php</p>
      <p>2. Добавьте товары в избранное на главной странице</p>
      <p>3. Проверьте, что счетчик в header обновляется</p>
      
      <div style="margin-top: 20px;">
         <a href="/index.php" style="padding: 10px 20px; background: #06ada8; color: white; text-decoration: none; border-radius: 5px;">Перейти на главную</a>
         <a href="/favorite.php" style="padding: 10px 20px; background: #ff4757; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">Страница избранного</a>
      </div>
   </div>

   <script src="js/header-favorites.js"></script>
</body>
</html> 