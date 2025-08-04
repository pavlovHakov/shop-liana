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

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
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

                  <a data-fancybox="gallery" href="/img/<?= $img['img'] ?>" class="product-img">
                     <img src="/img/<?= $img['img'] ?>" alt="<?= $product['name'] ?>" />
                  </a>

                  <?php endforeach; ?>
                  <?php endif; ?>
               </div>
            </div>
            <!-- Основное изображение товара -->
            <div class="container-img-product">

               <a data-fancybox="gallery" href="/img/<?= $product['img'] ?>" class="product-img">

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
                     foreach ($sizes as $index => $size) {
                        $size = trim($size);
                        $sizeId = "size-{$product['id']}-{$index}";
                        echo '<label for="' . $sizeId . '" class="size-product-item">';
                        echo '<input type="radio" id="' . $sizeId . '" name="size-' . $product['id'] . '" value="' . htmlspecialchars($size) . '" style="display: none;">';
                        echo htmlspecialchars($size);
                        echo '</label>';
                     }
                     ?>
                  </ul>
                  <div class="table-size">
                     <a href="#!" class="table-size-link">Таблица размеров</a>

                  </div>
               </div>
               <div class="buy_click"><a href="#!">Купить в 1 клик</a></div>
               <div class="add-to-basket">
                  <button class="item-btn btn-add-to-basket-with-size" data-product-id="<?= $product['id'] ?>">Добавить в корзину</button>
                  <button class="item-btn btn-favorites" data-product-id="<?= $product['id'] ?>">
                     <svg class="favorite-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        viewBox="0 0 437.775 437.774">
                        <path class="favorite-path"
                           d="M316.722 29.761c66.852 0 121.053 54.202 121.053 121.041 0 110.478-218.893 257.212-218.893 257.212S0 266.569 0 150.801c0-83.217 54.202-121.04 121.041-121.04 40.262 0 75.827 19.745 97.841 49.976 22.017-30.231 57.588-49.976 97.84-49.976z"
                           style="fill: rgb(6, 173, 168)" />
                     </svg>
                     Избранное
                  </button>
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
   <script src="js/header-favorites.js"></script>
   <script src="js/basket.js"></script>
   <script src="js/buy-click.js"></script>
   <script src="js/toogle-filter.js"></script>
   <script src="js/btn-scroll.js"></script>
   <script src="js/icon-favorite.js"></script>
   <script>
   // Обработчик для выбора размера
   document.addEventListener('DOMContentLoaded', function() {
      // Обработка выбора размера
      document.querySelectorAll('.size-product-item').forEach(label => {
         label.addEventListener('click', function() {
            // Убираем активный класс со всех размеров
            document.querySelectorAll('.size-product-item').forEach(item => {
               item.classList.remove('active');
            });
            
            // Добавляем активный класс к выбранному размеру
            this.classList.add('active');
            
            // Отмечаем соответствующий radio button
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
               radio.checked = true;
            }
         });
      });

      // Специальный обработчик для кнопки избранного на странице товара
      const favoriteBtn = document.querySelector('.btn-favorites');
      if (favoriteBtn) {
         const productId = favoriteBtn.getAttribute('data-product-id');
         const path = favoriteBtn.querySelector('.favorite-path');

         // Проверяем текущее состояние
         fetch(`/api/favorites.php?action=check&productId=${productId}`)
            .then(response => response.json())
            .then(data => {
               if (data.isFavorite) {
                  path.style.fill = "rgb(228, 8, 8)";
                  favoriteBtn.textContent = "В избранном";
               } else {
                  path.style.fill = "rgb(6, 173, 168)";
                  favoriteBtn.textContent = "Добавить в избранное";
               }
            })
            .catch(error => {
               console.error('Ошибка при проверке избранного:', error);
            });

         // Обработчик клика
         favoriteBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const isFavorite = path.style.fill === "rgb(228, 8, 8)";
            const action = isFavorite ? 'remove' : 'add';

            fetch('/api/favorites.php', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                     action: action,
                     productId: productId
                  })
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     if (action === 'add') {
                        path.style.fill = "rgb(228, 8, 8)";
                        favoriteBtn.textContent = "В избранном";
                        showNotification(data.message);
                     } else {
                        path.style.fill = "rgb(6, 173, 168)";
                        favoriteBtn.textContent = "Добавить в избранное";
                        showNotification(data.message);
                     }

                     // Обновляем счетчик в header
                     if (typeof updateFavoritesCount === 'function') {
                        updateFavoritesCount();
                     }
                  } else {
                     showNotification(data.message, 'error');
                  }
               })
               .catch(error => {
                  console.error('Ошибка:', error);
                  showNotification('Произошла ошибка при работе с избранным', 'error');
               });
         });
      }
   });

   // Функция для показа уведомлений
   function showNotification(message, type = 'success') {
      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.textContent = message;
      notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            transition: all 0.3s ease;
            ${type === 'success' ? 'background-color: #4CAF50;' : 'background-color: #f44336;'}
         `;

      document.body.appendChild(notification);

      setTimeout(() => {
         notification.style.opacity = '0';
         setTimeout(() => {
            document.body.removeChild(notification);
         }, 300);
      }, 3000);
   }
   </script>
</body>

</html>