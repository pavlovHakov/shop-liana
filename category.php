<?php

require_once 'function/init.php';
require_once 'function/db.php';

// Выполнение запроса для получения категорий
$categories = getCategories($mysqli);

// Получаем параметры фильтрации
$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;
$name = isset($_GET['name']) ? trim($_GET['name']) : '';
$priceFrom = isset($_GET['priceFrom']) ? (int)$_GET['priceFrom'] : 0;
$priceTo = isset($_GET['priceTo']) ? (int)$_GET['priceTo'] : 0;

// Если категория не указана, показываем все товары
if ($categoryId == 0) {
   $categoryName = 'Все товары';
} else {
   // Проверяем, существует ли категория
   if (!isset($categories[$categoryId])) {
      // Если категория не найдена, перенаправляем на главную
      header('Location: /index.php');
      exit;
   }
   $categoryName = $categories[$categoryId]['name'];
}

// Получаем отфильтрованные продукты
$products = getFilteredProducts($mysqli, $categoryId, $name, $priceFrom, $priceTo);
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
   <link rel="stylesheet" href="style/loader.css">
</head>

<body>
   <script src="js/header-show-hide.js"></script>
   <script src="js/product-lazy-loader.js"></script>

   <?php require_once 'templase/header.php'; ?>
   <?php require_once 'templase/filter.php'; ?>
   <div class="filter-btn">
      <img src="/img/icon/filter.svg" alt="Фильтр товаров">
   </div>
   <div class="wrapper">

      <div class="content">

         <?php if (count($products) > 0) : ?>
            <div class="block-info-category">
               <?php require_once 'templase/card-product.php'; ?>
            </div>
         <?php else : ?>
            <p class="empty-message-category">Продукты не найдены</p>
         <?php endif; ?>
      </div>

      <?php require_once 'templase/viewed.php'; ?>
      <div class="footer"></div>
      <button id="btn-scroll">Вверх</button>
   </div>

   <script src="/js/header-favorites.js"></script>
   <script src="/js/icon-favorite.js"></script>
   <script src="/js/btn-scroll.js"></script>
   <script src="/js/toogle-filter.js"></script>
   <script src="/js/size-handler.js"></script>
   <script src="/js/basket.js"></script>
</body>

</html>
<!-- вход в базу данных MySQL: -->

<!-- MAMP\bin\mysql\bin\mysql.exe --user oleg -p -->

<!-- пароль shop -->