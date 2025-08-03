<?php
require_once 'function/functions.php';
?>


<header class="header">
  <div class="logo">
    <a class="" href="/index.php">Liana</a>
  </div>
  <nav class="nav">
    <ul>
      <li><a class="<?= isActive('/index.php', $current_page) ?>" href="/index.php">Все товары</a></li>
      <?php foreach ($categories as $category) : ?>
        <li><a class="<?= isActive('/category.php?categoryId=' . $category['id'], $current_page) ?>" href="/category.php?categoryId=<?= $category['id'] ?>"><?= $category['name'] ?></a></li>

      <?php endforeach; ?>

    </ul>
  </nav>
  <div class="block-icon">
    <div class="basket">
      <img src="/img/icon/basket.svg" alt="Корзина">
    </div>
    <div class="favorites">
      <img src="/img/icon/card.svg" alt="Избранное">
    </div>
    <div class="user">
      <img src="/img/icon/user.svg" alt="Пользователь">
    </div>
  </div>
</header>