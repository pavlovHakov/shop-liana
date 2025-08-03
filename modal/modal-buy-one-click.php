<div style="display: none;" class="wrapper-modal-buy-one-click">
   <div class="modal-buy-one-click">

      <div class="close-modal-buy-one-click">
         <img src="/img/icon/close-modal.svg" alt="">
      </div>

      <div class="block-modal-buy-info">

         <div class="block-image-buy-info">
            <div class="block-modal-buy-product-image">
               <img src="/img/<?= $product['img'] ?>" alt="<?= $product['name'] ?>" />
            </div>
            <div class="block-image-buy-info">
               <p class="product-name"><?= htmlspecialchars($product['name']) ?></p>
               <p class="product-price"><?= htmlspecialchars($product['price']) ?> руб.</p>
            </div>
         </div>
      </div>
      <div class="block-modal-buy-form">
         <h3 class="modal-buy-form-title">Заполните форму</h3>
         <p class="modal-buy-form-subtitle">Мы свяжемся с вами для подтверждения заказа</p>
         <form action="/order.php" method="POST">
            <input type="hidden" name="productId" value="<?= $product['id'] ?>">
            <input type="hidden" name="price" value="<?= $product['price'] ?>">
            <input class="modal-name-input" type="text" name="username" placeholder="Ваше имя" required>
            <input class="modal-phone-input" type="text" name="phone" placeholder="Ваш телефон" required>

            <button class="btn-buy" type="submit">Купить</button>
         </form>
      </div>

   </div>
</div>