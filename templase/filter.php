<?php
$nameInput = $_GET['name'] ?? '';
$priceFrom = intval($_GET['priceFrom'] ?? 0);
$priceTo = intval($_GET['priceTo'] ?? 0);
$selectedCategoryId = intval($_GET['categoryId'] ?? 0);
$name = strip_tags($_GET['name'] ?? '');
?>

<div class="form-block">
   <form action="/category.php" method="GET">
      <input type="text" class="search-input" name="name" placeholder="Поиск..." value="<?= htmlspecialchars($nameInput) ?>">
      <select class="category-option" name="categoryId">
         <option class="category-option" value="0" <?= $selectedCategoryId == 0 ? 'selected' : '' ?>>Все категории</option>
         <?php foreach ($categories as $category) : ?>
         <option class="category-option" value="<?= $category['id'] ?>" <?= $selectedCategoryId == $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
         <?php endforeach; ?>
      </select>

      <div class="price-product">
         <input type="number" class="price-input" name="priceFrom" placeholder="Мин. цена" value="<?= $priceFrom > 0 ? $priceFrom : '' ?>" min="0">
         <input type="number" class="price-input" name="priceTo" placeholder="Макс. цена" value="<?= $priceTo > 0 ? $priceTo : '' ?>" min="0">
      </div>
      <button class="category-button" type="submit">Найти</button>
   </form>
</div>