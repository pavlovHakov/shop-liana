<?php
// Файл: viewed.php
// Сохраняет и отображает просмотренные товары для пользователя через cookie

// Максимальное количество товаров для отображения
$maxViewed = 8;

// Получаем id текущего товара, если есть
$currentProductId = isset($product['id']) ? $product['id'] : (isset($id) ? $id : null);

// Получаем список просмотренных товаров из cookie
$viewed = isset($_COOKIE['viewed']) ? explode(',', $_COOKIE['viewed']) : [];

// Если есть текущий товар и он не в списке, добавляем
if ($currentProductId && !in_array($currentProductId, $viewed)) {
   array_unshift($viewed, $currentProductId);
}

// Оставляем только уникальные и максимум $maxViewed
$viewed = array_unique($viewed);
$viewed = array_slice($viewed, 0, $maxViewed);

// Сохраняем обратно в cookie
setcookie('viewed', implode(',', $viewed), time() + 60 * 60 * 24 * 30, '/');

// Получаем товары из БД
$viewedProducts = [];
if (!empty($viewed)) {
   $ids = implode(',', array_map('intval', $viewed));
   $result = $mysqli->query("SELECT * FROM product WHERE id IN ($ids)");
   while ($row = $result->fetch_assoc()) {
      $viewedProducts[$row['id']] = $row;
   }
}

// Выводим просмотренные товары
if (!empty($viewedProducts)) {
   echo '<div class="viewed-products-block">';
   echo '<h2>Вы смотрели</h2>';
   echo '<ul class="viewed-products-list">';
   foreach ($viewed as $vid) {
      if (!isset($viewedProducts[$vid])) continue;
      $vp = $viewedProducts[$vid];
      echo '<li class="viewed-product-item">';
      echo '<a href="/product.php?id=' . $vp['id'] . '">';
      echo '<img src="/img/' . $vp['img'] . '" alt="' . htmlspecialchars($vp['name']) . '" style="width:80px; height:80px; object-fit:cover; border-radius:6px;">';
      echo '<div class="viewed-product-name">' . htmlspecialchars($vp['name']) . '</div>';
      echo '<div class="viewed-product-price">' . $vp['price'] . ' ₽</div>';
      echo '</a>';
      echo '</li>';
   }
   echo '</ul>';
   echo '</div>';
}
