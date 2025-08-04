<?php
session_start();
require_once 'function/db.php';
require_once 'function/functions.php';

// Получаем товары из корзины
$sessionId = session_id();
$basketItems = getBasketItems($mysqli, $sessionId);
$basketTotal = getBasketTotal($mysqli, $sessionId);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="style/basket.css">
   <link rel="stylesheet" href="style/reset.css">
   <link rel="stylesheet" href="style/fonts/robotocondensed.css">
   <link rel="stylesheet" href="style/header.css">
   <link rel="stylesheet" href="style/btn-scroll.css">
   <title>Корзина</title>
</head>

<body>
   <?php include 'templase/header.php'; ?>

   <div class="wrapper-basket">
      <div class="container">
         <h1 class="basket-title">Корзина</h1>

         <?php if (empty($basketItems)): ?>
            <div class="empty-basket">
               <p>Ваша корзина пуста</p>
               <a href="/" class="btn-back-to-shop">Вернуться к покупкам</a>
            </div>
         <?php else: ?>
            <div class="basket-content">
               <div class="basket-items">
                  <?php foreach ($basketItems as $item): ?>
                     <div class="basket-item" data-basket-id="<?= $item['id'] ?>">
                        <div class="basket-item-image">
                           <img src="/img/<?= htmlspecialchars($item['img']) ?>"
                              alt="<?= htmlspecialchars($item['name']) ?>">
                        </div>
                        <div class="basket-item-info">
                           <h3 class="basket-item-name"><?= htmlspecialchars($item['name']) ?></h3>
                           <?php if ($item['size']): ?>
                              <p class="basket-item-size">Размер: <?= htmlspecialchars($item['size']) ?></p>
                           <?php endif; ?>
                           <p class="basket-item-price"><?= $item['price'] ?> ₴</p>
                        </div>
                        <div class="basket-item-controls">
                           <div class="quantity-controls">
                              <button class="quantity-btn quantity-minus" data-basket-id="<?= $item['id'] ?>">-</button>
                              <input type="number" class="quantity-input" value="<?= $item['quantity'] ?>" 
                                     min="1" data-basket-id="<?= $item['id'] ?>">
                              <button class="quantity-btn quantity-plus" data-basket-id="<?= $item['id'] ?>">+</button>
                           </div>
                           <div class="basket-item-total">
                              <?= $item['price'] * $item['quantity'] ?> ₴
                           </div>
                           <button class="btn-remove-item" data-basket-id="<?= $item['id'] ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 56 59">
                                 <path d="M52 14a4 4 0 1 0 0-8H41.985a.982.982 0 0 0-.2-.625l-1-1.247A10.946 10.946 0 0 0 32.193 0h-8.386a10.942 10.942 0 0 0-8.589 4.128l-1 1.247a.982.982 0 0 0-.2.625H4a4 4 0 1 0 0 8zM16.779 5.378A8.955 8.955 0 0 1 23.807 2h8.386a8.958 8.958 0 0 1 7.029 3.378l.5.622H16.281zM4 16l3.45 41.22A2.24 2.24 0 0 0 9.85 59h36.3a2.24 2.24 0 0 0 2.4-1.78L52 16zm15 8a1 1 0 0 1-1-1 2 2 0 1 0-4 0v27a1 1 0 0 1-2 0V23a4 4 0 1 1 8 0 1 1 0 0 1-1 1zm12 0a1 1 0 0 1-1-1 2 2 0 1 0-4 0v27a1 1 0 0 1-2 0V23a4 4 0 1 1 8 0 1 1 0 0 1-1 1zm12 0a1 1 0 0 1-1-1 2 2 0 1 0-4 0v27a1 1 0 0 1-2 0V23a4 4 0 1 1 8 0 1 1 0 0 1-1 1z"/>
                              </svg>
                           </button>
                        </div>
                     </div>
                  <?php endforeach; ?>
               </div>
               
               <div class="basket-summary">
                  <div class="summary-content">
                     <h3>Итого к оплате</h3>
                     <div class="summary-total">
                        <span class="total-amount"><?= $basketTotal ?> ₴</span>
                     </div>
                     <div class="summary-actions">
                        <button class="btn-clear-basket">Очистить корзину</button>
                        <button class="btn-checkout">Оформить заказ</button>
                     </div>
                  </div>
               </div>
            </div>
         <?php endif; ?>
      </div>
   </div>

   <button id="btn-scroll">Вверх</button>

   <script src="js/header-favorites.js"></script>
   <script src="js/btn-scroll.js"></script>
   <script>
      // Функция для показа уведомлений
      function showNotification(message, type = 'success') {
         // Простая реализация уведомлений
         alert(message);
      }

      // Функция для обновления итоговой суммы
      function updateBasketTotal() {
         let total = 0;
         document.querySelectorAll('.basket-item').forEach(item => {
            const price = parseFloat(item.querySelector('.basket-item-price').textContent);
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            const itemTotal = price * quantity;
            
            item.querySelector('.basket-item-total').textContent = itemTotal + ' ₴';
            total += itemTotal;
         });
         
         document.querySelector('.total-amount').textContent = total + ' ₴';
      }

      // Обработчики для кнопок количества
      document.querySelectorAll('.quantity-minus').forEach(button => {
         button.addEventListener('click', function() {
            const basketId = this.getAttribute('data-basket-id');
            const input = document.querySelector(`.quantity-input[data-basket-id="${basketId}"]`);
            let quantity = parseInt(input.value);
            
            if (quantity > 1) {
               quantity--;
               input.value = quantity;
               updateQuantity(basketId, quantity);
            }
         });
      });

      document.querySelectorAll('.quantity-plus').forEach(button => {
         button.addEventListener('click', function() {
            const basketId = this.getAttribute('data-basket-id');
            const input = document.querySelector(`.quantity-input[data-basket-id="${basketId}"]`);
            let quantity = parseInt(input.value);
            
            quantity++;
            input.value = quantity;
            updateQuantity(basketId, quantity);
         });
      });

      // Обработчик для прямого ввода количества
      document.querySelectorAll('.quantity-input').forEach(input => {
         input.addEventListener('change', function() {
            const basketId = this.getAttribute('data-basket-id');
            let quantity = parseInt(this.value);
            
            if (quantity < 1) {
               quantity = 1;
               this.value = quantity;
            }
            
            updateQuantity(basketId, quantity);
         });
      });

      // Функция для обновления количества товара
      function updateQuantity(basketId, quantity) {
         fetch('/api/basket.php', {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json',
               },
               body: JSON.stringify({
                  action: 'update',
                  basketId: basketId,
                  quantity: quantity
               })
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  updateBasketTotal();
                  
                  // Обновляем счетчик в header
                  if (typeof updateBasketCount === 'function') {
                     updateBasketCount();
                  }
               } else {
                  showNotification(data.message, 'error');
               }
            })
            .catch(error => {
               console.error('Ошибка:', error);
               showNotification('Произошла ошибка при обновлении количества', 'error');
            });
      }

      // Обработчик для удаления товара из корзины
      document.querySelectorAll('.btn-remove-item').forEach(button => {
         button.addEventListener('click', function() {
            const basketId = this.getAttribute('data-basket-id');
            const basketItem = this.closest('.basket-item');

            if (confirm('Удалить товар из корзины?')) {
               fetch('/api/basket.php', {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                        action: 'remove',
                        basketId: basketId
                     })
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        basketItem.remove();

                        // Проверяем, остались ли еще товары
                        const remainingItems = document.querySelectorAll('.basket-item');
                        if (remainingItems.length === 0) {
                           location.reload();
                        } else {
                           updateBasketTotal();
                        }

                        showNotification(data.message);

                        // Обновляем счетчик в header
                        if (typeof updateBasketCount === 'function') {
                           updateBasketCount();
                        }
                     } else {
                        showNotification(data.message, 'error');
                     }
                  })
                  .catch(error => {
                     console.error('Ошибка:', error);
                     showNotification('Произошла ошибка при удалении товара', 'error');
                  });
            }
         });
      });

      // Обработчик для очистки корзины
      document.querySelector('.btn-clear-basket')?.addEventListener('click', function() {
         if (confirm('Очистить всю корзину?')) {
            fetch('/api/basket.php', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                     action: 'clear'
                  })
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     location.reload();
                  } else {
                     showNotification(data.message, 'error');
                  }
               })
               .catch(error => {
                  console.error('Ошибка:', error);
                  showNotification('Произошла ошибка при очистке корзины', 'error');
               });
         }
      });

      // Обработчик для оформления заказа
      document.querySelector('.btn-checkout')?.addEventListener('click', function() {
         // Здесь можно добавить логику оформления заказа
         showNotification('Функция оформления заказа будет добавлена позже');
      });
   </script>
</body>

</html>