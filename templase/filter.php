<?php 
$nameInput = $_GET['name'] ?? '';
$priceFrom = intval($_GET['priceFrom'] ?? 0);
$priceTo = intval($_GET['priceTo'] ?? 0);
$name=strip_tags($_GET['name'] ?? '');
?>

<div class="form-block">
   <form action="/find.php" method="GET">
      <input type="text" class="search-input" name="name" placeholder="Поиск..." value="<?= $nameInput ?>">
      <select class="category-option" name="categoryId">
         <option class="category-option" value="0">Все категории</option>
         <?php foreach ($categories as $category) : ?>
            <option class="category-option" value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
         <?php endforeach; ?>
      </select>

      <div class="price-product">
         <input type="number" class="price-input" name="priceFrom" placeholder="Мин. цена" value="<?= $priceFrom ?>">
         <input type="number" class="price-input" name="priceTo" placeholder="Макс. цена" value="<?= $priceTo ?>">
      </div>
      <button class="category-button" type="submit">Найти</button>
   </form>
</div>