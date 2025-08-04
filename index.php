 <?php
  require_once 'function/init.php';
  require_once 'function/db.php';
  require_once 'function/functions.php';


  $categories = getCategories($mysqli);

  // Выполнение запроса для получения продуктов

  $products = getProducts($mysqli);
  ?>

 <!DOCTYPE html>
 <html lang="en">

 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style/reset.css">
   <link rel="stylesheet" href="style/fonts/robotocondensed.css">
   <link rel="stylesheet" href="style/product.css">
   <link rel="stylesheet" href="style/header.css">
   <link rel="stylesheet" href="style/filter.css">
   <link rel="stylesheet" href="style/basket.css">
   <link rel="stylesheet" href="style/modal-size.css">
   <link rel="stylesheet" href="style/modal-buy-one-click.css">
   <link rel="stylesheet" href="style/index.css">
   <link rel="stylesheet" href="style/btn-scroll.css">
   <title>Главная</title>

 </head>

 <body>
   <?php require_once 'templase/filter.php'; ?>
   <?php require_once 'templase/header.php'; ?>
   <div class="filter-btn">
     <img src="/img/icon/filter.svg" alt="Фильтр товаров">
   </div>
   <div class="wrapper">
     <h1 class="category-title">Все товары</h1>

     <div class="content">
       <?php require_once 'templase/card-product.php'; ?>
     </div>
   </div>

   <div class="footer"></div>

   <button id="btn-scroll">Вверх</button>

   </div>
   <script src="js/header-favorites.js"></script>
   <script src="js/icon-favorite.js"></script>
   <script src="js/basket.js"></script>
   <script src="js/toogle-filter.js"></script>
   <script src="js/btn-scroll.js"></script>
   <script src="js/size-handler.js"></script>
 </body>

 </html>
 <!-- вход в базу данных MySQL: -->
 <!-- MAMP\bin\mysql\bin\mysql.exe --user oleg -p -->

 <!-- пароль shop -->

 <!-- Этот запрос добавляет столбец "age" со значением по умолчанию 0. -->
 <!-- ALTER TABLE users -->
 <!-- ADD COLUMN age INT DEFAULT 0; -->

 <!-- XS, S, M, L, XL, XXL -->