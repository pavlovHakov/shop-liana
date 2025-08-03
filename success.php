<?php
require_once 'function/init.php';
require_once 'function/db.php';

$categories = getCategories($mysqli);

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style/reset.css">
   <link rel="stylesheet" href="style/success.css">
   <link rel="stylesheet" href="style/header.css">
   <link rel="stylesheet" href="style/filter.css">
   <link rel="stylesheet" href="style/fonts/robotocondensed.css">
   <title>Document</title>
</head>

<body>
   <?php require_once 'templase/header.php'; ?>
   <?php require_once 'templase/filter.php'; ?>

   <div class="wrapper">
      <div class="filter-btn">
         <img src="/img/icon/filter.svg" alt="Фильтр товаров">
      </div>
      <h1>Success!</h1>
      <p>Your order has been placed successfully.</p>
   </div>
   <a href="/">Go back to home</a>

   <div class="footer"></div>
   </div>
   <script src="/js/toogle-filter.js"></script>
</body>

</html>