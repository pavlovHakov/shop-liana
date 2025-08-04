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
                           <div class="btn-remove-favorite" data-product-id="<?= $product['id'] ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="512" fill="currentColor" height="512" x="0" y="0" viewBox="0 0 56 59" style="enable-background:new 0 0 512 512" xml:space="preserve">
                                 <path d="M52 14a4 4 0 1 0 0-8H41.985a.982.982 0 0 0-.2-.625l-1-1.247A10.946 10.946 0 0 0 32.193 0h-8.386a10.942 10.942 0 0 0-8.589 4.128l-1 1.247a.982.982 0 0 0-.2.625H4a4 4 0 1 0 0 8zM16.779 5.378A8.955 8.955 0 0 1 23.807 2h8.386a8.958 8.958 0 0 1 7.029 3.378l.5.622H16.281zM4 16l3.45 41.22A2.24 2.24 0 0 0 9.85 59h36.3a2.24 2.24 0 0 0 2.4-1.78L52 16zm15 8a1 1 0 0 1-1-1 2 2 0 1 0-4 0v27a1 1 0 0 1-2 0V23a4 4 0 1 1 8 0 1 1 0 0 1-1 1zm12 0a1 1 0 0 1-1-1 2 2 0 1 0-4 0v27a1 1 0 0 1-2 0V23a4 4 0 1 1 8 0 1 1 0 0 1-1 1zm12 0a1 1 0 0 1-1-1 2 2 0 1 0-4 0v27a1 1 0 0 1-2 0V23a4 4 0 1 1 8 0 1 1 0 0 1-1 1z" opacity="1" data-original="#000000" class="" />
                              </svg>
                           </div>
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