<?php
require_once 'function/functions.php';
require_once 'function/db.php';
session_start();

// Получаем количество избранных товаров и товаров в корзине
$sessionId = session_id();
$favorites = getFavorites($mysqli, $sessionId);
$favoritesCount = count($favorites);
$basketCount = getBasketCount($mysqli, $sessionId);

// Получаем категории для меню
$categories = getCategories($mysqli);

// Определяем текущую страницу для подсветки активного пункта меню
$currentPage = basename($_SERVER['PHP_SELF']);
$currentCategoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : null;


// Проверяем, существует ли категория с таким ID
if ($currentCategoryId && !isset($categories[$currentCategoryId])) {
   $currentCategoryId = null; // Если категория не существует, сбрасываем
}
?>


<header class="header" id="mainHeader">
   <div class="header-top">
      <div class="logo">
         <a class="" href="/index.php">Liana</a>
      </div>

      <div class="block-icon">
         <div class="basket">
            <a href="/basket.php">
               <img src="/img/icon/basket.svg" alt="Корзина">
               <?php if ($basketCount > 0): ?>
                  <span class="basket-count"><?= $basketCount ?></span>
               <?php endif; ?>
            </a>
         </div>
         <div class="favorites">
            <a href="/favorite.php">
               <img src="/img/icon/card.svg" alt="Избранное">
               <?php if ($favoritesCount > 0): ?>
                  <span class="favorites-count"><?= $favoritesCount ?></span>
               <?php endif; ?>
            </a>
         </div>
         <div class="user">
            <img src="/img/icon/user.svg" alt="Пользователь">
         </div>
      </div>
   </div>

   <nav class="nav-menu">
      <div class="nav-container">
         <ul class="nav-list">
            <li class="nav-item">
               <a href="/index.php" class="nav-link <?= ($currentPage === 'index.php') ? 'active' : '' ?>">Главная</a>
            </li>
            <?php foreach ($categories as $category): ?>
               <li class="nav-item">
                  <a href="/category.php?categoryId=<?= $category['id'] ?>" class="nav-link <?= ($currentPage === 'category.php' && $currentCategoryId == $category['id']) ? 'active' : '' ?>">
                     <?= htmlspecialchars($category['name']) ?>
                  </a>
               </li>
            <?php endforeach; ?>
         </ul>
      </div>
   </nav>
</header>
