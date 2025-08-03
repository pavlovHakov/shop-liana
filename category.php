<?php

require_once 'function/init.php';
require_once 'function/db.php';

// Выполнение запроса для получения категорий
$categories = getCategories($mysqli);

// Получаем и валидируем ID категории из URL
$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 1;

// Проверяем, существует ли категория
if (!isset($categories[$categoryId])) {
   // Если категория не найдена, перенаправляем на главную
   header('Location: /index.php');
   exit;
}

// Выполнение запроса для получения продуктов
$stmt = $mysqli->prepare("SELECT * FROM product WHERE categoryId = ?");
$stmt->bind_param("i", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $products[] = $row;
   }
}

// Получаем название категории
$categoryName = $categories[$categoryId]['name'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style/reset.css">
   <link rel="stylesheet" href="style/index.css">
   <link rel="stylesheet" href="style/fonts/robotocondensed.css">
   <link rel="stylesheet" href="style/header.css">
   <link rel="stylesheet" href="style/filter.css">
   <link rel="stylesheet" href="style/btn-scroll.css">

   <title><?= htmlspecialchars($categoryName) ?> - Каталог</title>
</head>

<body>
   <?php require_once 'templase/header.php'; ?>
   <?php require_once 'templase/filter.php'; ?>
   <div class="filter-btn">
      <img src="/img/icon/filter.svg" alt="Фильтр товаров">
   </div>
   <div class="wrapper">
      <h1 class="category-title">Каталог женской одежды - <?= htmlspecialchars($categoryName) ?></h1>

      <div class="content">

         <?php if (count($products) > 0) : ?>

         <?php require_once 'templase/card-product.php'; ?>
         <?php else : ?>
         <p>Продукты не найдены</p>
         <?php endif; ?>
      </div>
      <div class="footer"></div>
      <button id="btn-scroll">Вверх</button>
   </div>

   <script src="/js/header-favorites.js"></script>
   <script src="/js/icon-favorite.js"></script>
   <script src="/js/btn-scroll.js"></script>
   <script src="/js/toogle-filter.js"></script>
   <script src="/js/size-handler.js"></script>
</body>

</html>
<!-- вход в базу данных MySQL: -->

<!-- MAMP\bin\mysql\bin\mysql.exe --user oleg -p -->

<!-- пароль shop -->