<?php
session_start();
require_once 'function/db.php';
require_once 'function/functions.php';

// Получаем избранные товары
$sessionId = session_id();
$favorites = getFavorites($mysqli, $sessionId);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style/favorite.css">
   <link rel="stylesheet" href="style/reset.css">
   <link rel="stylesheet" href="style/fonts/robotocondensed.css">
   <link rel="stylesheet" href="style/header.css">
   <link rel="stylesheet" href="style/btn-scroll.css">
   <title>Избранное</title>
</head>

<body>
   <?php include 'templase/header.php'; ?>

   <div class="wrapper-favorite">
      <div class="container">
         <h1 class="favorite-title">Избранное</h1>

         <?php if (empty($favorites)): ?>
            <div class="empty-favorites">
               <p>У вас пока нет избранных товаров</p>
               <a href="/" class="btn-back-to-shop">Вернуться к покупкам</a>
            </div>
         <?php else: ?>
            <div class="favorites-grid">
               <?php foreach ($favorites as $product): ?>
                  <div class="favorite-item">
                     <div class="favorite-item-image">
                        <img src="/img/<?= htmlspecialchars($product['img']) ?>"
                           alt="<?= htmlspecialchars($product['name']) ?>">
                     </div>
                     <div class="favorite-item-info">
                        <h3 class="favorite-item-name"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="favorite-item-price"><?= $product['price'] ?> ₴</p>
                        <div class="favorite-item-actions">
                           <a href="/product.php?id=<?= $product['id'] ?>" class="btn-view-product">Посмотреть товар</a>
                           <button class="btn-remove-favorite" data-product-id="<?= $product['id'] ?>">Удалить из
                              избранного</button>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>
   </div>

   <script src="js/header-favorites.js"></script>
   <script src="js/icon-favorite.js"></script>
   <script>
      // Обработчик для кнопки удаления из избранного
      document.querySelectorAll('.btn-remove-favorite').forEach(button => {
         button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const favoriteItem = this.closest('.favorite-item');

            fetch('/api/favorites.php', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                     action: 'remove',
                     productId: productId
                  })
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     // Удаляем элемент из DOM
                     favoriteItem.remove();

                     // Проверяем, остались ли еще товары
                     const remainingItems = document.querySelectorAll('.favorite-item');
                     if (remainingItems.length === 0) {
                        location.reload(); // Перезагружаем страницу для показа пустого состояния
                     }

                     showNotification(data.message);

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
                  showNotification('Произошла ошибка при удалении товара', 'error');
               });
         });
      });
   </script>
</body>

</html>