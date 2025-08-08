
   <ul class="product-list">
      <?php foreach ($products as $product) : ?>

         <li class="product-item">

            <span class="loader"></span>

            <div class="product-card">
               <a href="/product.php?id=<?= $product['id'] ?>" class="product-link">

                  <div class="loadingio-spinner-rolling-nq4q5u6dq7r">
                     <div class="ldio-x2uulkbinbj">
                        <div></div>
                     </div>
                  </div>
                  <span class="loader"></span>
                  <div class="container-card">
                     <?php if ($product['availability'] == 'Нет') : ?>
                        <div class="container-availability">
                           <span class="availability"><?= $product['availability'] ?> в наличии</span>
                        </div>
                     <?php endif; ?>
                     <div class="block-img">
                        <span class="img-loader"></span>
                        <img class="lazy-img" src="/img/placeholder.webp" data-src="/img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                     </div>
                  </div>

                  <h2 class="product-name"><?= htmlspecialchars($product['name']) ?></h2>
                  <p class="product-price">Цена: <?= $product['price'] ?> ₴</p>
               </a>

               <div class="block-card-icon" data-product-id="<?= $product['id'] ?>">
                  <svg id="iconCard" xmlns="http://www.w3.org/2000/svg" version="1.1"
                     xmlns:xlink="http://www.w3.org/1999/xlink" width="50" height="50" x="0" y="0"
                     viewbox="0 0 437.775 437.774" xml:space="preserve">

                     <path class="icon-favorite"
                        d="M316.722 29.761c66.852 0 121.053 54.202 121.053 121.041 0 110.478-218.893 257.212-218.893 257.212S0 266.569 0 150.801c0-83.217 54.202-121.04 121.041-121.04 40.262 0 75.827 19.745 97.841 49.976 22.017-30.231 57.588-49.976 97.84-49.976z"
                        style="fill: rgb(6, 173, 168)" opacity="1" data-original="#000000" />

                  </svg>
               </div>

               <div class="card-block-info">
                  <div class="block-size">
                     <?php
                     if (!empty($product['size'])) {
                        $sizes = explode(',', $product['size']);
                        foreach ($sizes as $size) {
                           $size = trim($size);
                           if (!empty($size)) {
                              echo '<button class="size-item">' . htmlspecialchars($size) . '</button>';
                           }
                        }
                     } else {
                        echo '<span style="color: #fff; font-size: 14px;">Размеры не указаны</span>';
                     }
                     ?>
                  </div>
                  <button class="btn-add-to-basket" data-product-id="<?= $product['id'] ?>" data-quantity="1">Добавить в корзину</button>
               </div>
            </div>
         </li>
      <?php endforeach; ?>
   </ul>
  
