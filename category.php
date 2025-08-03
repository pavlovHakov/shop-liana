<?php

require_once 'function/init.php';
require_once 'function/db.php';
// Выполнение запроса для получения категорий
$categories = getCategories($mysqli);

// Выполнение запроса для получения продуктов
$categoryId = $_GET['categoryId']; // Получаем ID категории из URL, по умолчанию 1

$result = $mysqli->query("SELECT * FROM product WHERE categoryId = " . $categoryId);
$products = [];
if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
      $products[] = $row;
   }
}
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

   <title>Главная</title>
</head>

<body>
   <?php require_once 'templase/header.php'; ?>
   <?php require_once 'templase/filter.php'; ?>
   <div class="filter-btn">
      <img src="/img/icon/filter.svg" alt="Фильтр товаров">
   </div>
   <div class="wrapper">
      <h1 class="category-title">Каталог женской одежды - <span></span> <?= $categories[$categoryId]['name'] ?></h1>

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

   <script src="/js/icon-favorite.js"></script>
   <script src="/js/btn-scroll.js"></script>
   <script src="/js/toogle-filter.js"></script>
</body>

</html>
<!-- вход в базу данных MySQL: -->

<!-- MAMP\bin\mysql\bin\mysql.exe --user oleg -p -->

<!-- пароль shop -->