 <?php   // Выполнение запроса для получения категорий
   require_once 'function/init.php';
   require_once 'function/db.php';


   $result = $mysqli->query("SELECT id, name FROM category");

   $categories = [];
   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $categories[$row['id']] = $row;
      }
   }


   $where = '';
   if (isset($_GET['name']) && $_GET['name']) {
      $name = strip_tags($_GET['name']);
      $name = $mysqli->real_escape_string($name);
      // Экранирование специальных символов в строке
      $where = $where . ' AND name LIKE "%' . $name . '%"';
   }
   if (isset($_GET['categoryId']) && $_GET['categoryId']) {
      $categoryId = (int)$_GET['categoryId'];
      $where = $where . " AND categoryId = $categoryId";
   }
   if (isset($_GET['priceFrom']) && $_GET['priceFrom']) {
      $priceFrom = (int)$_GET['priceFrom'];
      $where = $where . " AND price >= $priceFrom";
   }
   if (isset($_GET['priceTo']) && $_GET['priceTo']) {
      $priceTo = (int)$_GET['priceTo'];
      $where = $where . " AND price <= $priceTo";
   }


   // Выполнение запроса для получения продуктов
   $result = $mysqli->query("SELECT * FROM product WHERE 1 " . $where);
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
    <link rel="stylesheet" href="style/fonts/robotocondensed.css">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/filter.css">
    <link rel="stylesheet" href="style/category.css">
    <link rel="stylesheet" href="style/btn-scroll.css">
    <title>Главная</title>
 </head>

 <body>
    <?php require_once 'templase/header.php'; ?>
    <div class="filter-btn">
       <img src="/img/icon/filter.svg" alt="Фильтр товаров">
    </div>
    <div class="wrapper">
       <h1>Добро пожаловать в наш магазин!</h1>

       <div class="content">
          <!-- form -->
          <?php require_once 'templase/filter.php'; ?>
          <?php if (count($products) > 0) : ?>
             <?php require_once 'templase/card-product.php'; ?>

          <?php else : ?>
             <p>Продукты не найдены</p>
          <?php endif; ?>

       </div>
       <div class="footer">
          <p>&copy; 2023 Магазин</p>
       </div>
       <button id="btn-scroll">Вверх</button>
    </div>

    <script src="js/icon-favorite.js"></script>
    <script src="js/toogle-filter.js"></script>
    <script src="js/btn-scroll.js"></script>


 </body>

 </html>
 <!-- вход в базу данных MySQL: -->

 <!-- MAMP\bin\mysql\bin\mysql.exe --user oleg -p -->

 <!-- пароль shop -->