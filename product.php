<?php
require_once 'function/init.php';
require_once 'function/db.php';
require_once 'function/functions.php';

$categories = getCategories($mysqli);
$imgGallery = getGallery($mysqli);

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
   header('Location: /');
   exit;
}
$result = $mysqli->query("SELECT * FROM product WHERE id = " . $id);
if ($result->num_rows > 0) {
   $product = $result->fetch_assoc();
} else {
   header('Location: /');
   exit;
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
   <link rel="stylesheet" href="style/product.css">
   <link rel="stylesheet" href="style/header.css">
   <link rel="stylesheet" href="style/filter.css">
   <link rel="stylesheet" href="style/basket.css">
   <link rel="stylesheet" href="style/modal-size.css">
   <link rel="stylesheet" href="style/modal-buy-one-click.css">
   <link rel="stylesheet" href="style/btn-scroll.css">

   <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
   <title><?= $product['name'] ?></title>
</head>

<body>
   <?php require_once 'templase/filter.php'; ?>
   <?php require_once 'modal/modal-size.php'; ?>
   <?php require_once 'modal/modal-buy-one-click.php'; ?>
   <?php require_once 'templase/header.php'; ?>
   <div class="filter-btn">
      <img src="/img/icon/filter.svg" alt="Фильтр товаров">
   </div>
   <div class="wrapper">
      <div class="content-product">
         <div class="wrapp-container-img-product-gallery">
            <div class="container-img-product-gallery">
               <!-- Вывод галлереи изображений -->
               <div class="block-gallery">
                  <?php if (!empty($imgGallery)): ?>
                     <?php foreach ($imgGallery as $img): ?>
                        <?php if ($img['img_storage'] != $product['id']) continue; ?>

                        <a
                           data-fancybox="gallery"
                           href="/img/<?= $img['img'] ?>" class="product-img">
                           <img src="/img/<?= $img['img'] ?>" alt="<?= $product['name'] ?>" />
                        </a>

                     <?php endforeach; ?>
                  <?php endif; ?>
               </div>
            </div>
            <!-- Основное изображение товара -->
            <div class="container-img-product">

               <a
                  data-fancybox="gallery"
                  href="/img/<?= $product['img'] ?>" class="product-img">

                  <img src="/img/<?= $product['img'] ?>" alt="<?= $product['name'] ?>" />
               </a>
            </div>
         </div>
         <div class="order-goods">

            <div class="product-description">

               <div class="block-article-availability">
                  <p class="availability">Наличие: <span>
                        <?php if ($product['availability'] == 'Нет') : ?>
                           <span class="no-products"><?= $product['availability'] ?></span>
                        <?php else : ?>
                           <span class="yes-products"><?= $product['availability'] ?></span>
                        <?php endif; ?>
                  </p>
                  <p class="article">Артикул: <span><?= htmlspecialchars($product['id']) ?></span></p>
               </div>

               <h1><?= htmlspecialchars($product['name']) ?></h1>
               <p class="price"><?= $product['price'] ?> ₴</p>
               <div class="product-size_table-size">

                  <ul class="size-list">
                     <p>Размер</p>
                     <?php
                     $sizes = explode(',', $product['size']);
                     foreach ($sizes as $size) {
                        echo '<button class="size-product-item">' . htmlspecialchars(trim($size)) . '</button>';
                     }
                     ?>
                  </ul>
                  <div class="table-size">
                     <a href="#!" class="table-size-link">Таблица размеров</a>

                  </div>
               </div>
               <div class="buy_click"><a href="#!">Купить в 1 клик</a></div>
               <div class="add-to-basket">
                  <button class="item-btn btn-buy">Добавить в корзину</button>
                  <button class="item-btn btn-favorites">Избранное</button>
               </div>
               <p><?= htmlspecialchars($product['description']) ?></p>
            </div>

         </div>
      </div>

      <div class="wrapper_slider-content">

         <h2>Другие товары в категории</h2>
         <ul class="product-list">
            <?php
            $categoryId = $product['categoryId'];
            $result = $mysqli->query("SELECT * FROM product WHERE categoryId = " . $categoryId . " AND id != " . $id);
            if ($result->num_rows > 0) {
               while ($row = $result->fetch_assoc()) {
                  echo '<li class="product-item">';
                  echo '<a href="/product.php?id=' . $row['id'] . '">';
                  echo '<div class="container-img">';
                  echo '<img src="/img/' . $row['img'] . '" alt="' . htmlspecialchars($row['name']) . '">';
                  echo '</div>';
                  echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                  echo '<p class="price">Цена: ' . $row['price'] . ' руб.</p>';
                  echo '</a>';
                  echo '</li>';
               }
            } else {
               echo '<p>Другие товары не найдены</p>';
            }
            ?>
         </ul>



      </div>
      <div class="footer"></div>
      <button id="btn-scroll">Вверх</button>
   </div>


   <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
   <script>
      Fancybox.bind("[data-fancybox]", {

      });
   </script>
   <script src="js/buy-click.js"></script>
   <script src="js/toogle-filter.js"></script>
   <script src="js/btn-scroll.js"></script>
</body>

</html>