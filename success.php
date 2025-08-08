<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

   <div class="wrapper-checkout">
      <div class="container-success">
         <div class="checkout-success">
            <strong>Спасибо за заказ!</strong><br>
            Ваш заказ успешно оформлен и принят в обработку.<br>
            Мы свяжемся с вами для подтверждения.
         </div>
         <div class="success-back">
            <a href="/" class="success-back-link">Вернуться на главную</a>
         </div>
      </div>
   </div>

   <div class="footer"></div>
</body>

</html>