<?php
require_once 'function/init.php';
require_once 'function/db.php';
require_once 'function/functions.php';

$categories = getCategories($mysqli);

// Параметры пагинации
$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Получение общего количества товаров
$totalProducts = $mysqli->query("SELECT COUNT(*) FROM product")->fetch_row()[0];
$totalPages = ceil($totalProducts / $limit);

// Получение товаров для текущей страницы
$products = [];
$result = $mysqli->query("SELECT * FROM product LIMIT $limit OFFSET $offset");
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}
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
  <link rel="stylesheet" href="style/checkout.css">
  <link rel="stylesheet" href="style/btn-scroll.css">
  <link rel="stylesheet" href="style/loader.css">

  <title>Главная</title>
</head>

<body>
  <script src="js/header-show-hide.js"></script>
  <script src="js/product-lazy-loader.js"></script>
  <?php require_once 'templase/filter.php'; ?>
  <?php require_once 'templase/header.php'; ?>

  <div class="filter-btn">
    <img src="/img/icon/filter.svg" alt="Фильтр товаров">
  </div>
  <div class="wrapper">

    <div class="content">
      <?php
      // Выводим товары

      include 'templase/card-product.php';

      ?>
    </div>
    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?page=<?= $page - 1 ?>" class="pagination-link">&laquo; Назад</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="?page=<?= $i ?>" class="pagination-link" style="background:<?= $i == $page ? '#06ada8' : '#f0f0f0' ?>; color:<?= $i == $page ? '#fff' : '#347424' ?>;"> <?= $i ?> </a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $page + 1 ?>" class="pagination-link">Вперёд &raquo;</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php require_once 'templase/viewed.php'; ?>
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