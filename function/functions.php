<?php

// Функция для получения общего количества товаров
// Эта функция выполняет SQL-запрос для получения количества товаров в таблице products
function getTotalProducts($mysqli)
{
   $result = $mysqli->query("SELECT COUNT(*) as total FROM products");
   if ($result) {
      $row = $result->fetch_assoc();
      return (int)$row['total'];
   }
   return 0;
}


function getCategories($mysqli)
{
   $result = $mysqli->query("SELECT id, name FROM category");
   $categories = [];

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $categories[$row['id']] = $row;
      }
   }

   return $categories;
}

function getProducts($mysqli)
{
   $result = $mysqli->query("SELECT * FROM product WHERE 1 ");
   $products = [];
   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $products[] = $row;
      }
   }
   return $products;
}

function getGallery($mysqli)
{
   $result = $mysqli->query("SELECT * FROM id_1");
   $img_gallery = [];
   if ($result) {
      while ($row = $result->fetch_assoc()) {
         $img_gallery[] = $row;
      }
   }
   return $img_gallery;
}

// Функция для добавления товара в избранное
function addToFavorites($mysqli, $productId, $sessionId)
{
   // Проверяем, не добавлен ли уже товар в избранное
   $stmt = $mysqli->prepare("SELECT id FROM favorites WHERE productId = ? AND sessionId = ?");
   $stmt->bind_param("is", $productId, $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      return ['success' => false, 'message' => 'Товар уже в избранном'];
   }

   // Добавляем товар в избранное
   $stmt = $mysqli->prepare("INSERT INTO favorites (productId, sessionId) VALUES (?, ?)");
   $stmt->bind_param("is", $productId, $sessionId);

   if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Товар добавлен в избранное'];
   } else {
      return ['success' => false, 'message' => 'Ошибка при добавлении товара'];
   }
}

// Функция для удаления товара из избранного
function removeFromFavorites($mysqli, $productId, $sessionId)
{
   $stmt = $mysqli->prepare("DELETE FROM favorites WHERE productId = ? AND sessionId = ?");
   $stmt->bind_param("is", $productId, $sessionId);

   if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Товар удален из избранного'];
   } else {
      return ['success' => false, 'message' => 'Ошибка при удалении товара'];
   }
}

// Функция для получения избранных товаров
function getFavorites($mysqli, $sessionId)
{
   $stmt = $mysqli->prepare("
      SELECT p.*, f.id as favorite_id 
      FROM favorites f 
      JOIN product p ON f.productId = p.id 
      WHERE f.sessionId = ?
   ");
   $stmt->bind_param("s", $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   $favorites = [];
   while ($row = $result->fetch_assoc()) {
      $favorites[] = $row;
   }

   return $favorites;
}

// Функция для проверки, находится ли товар в избранном
function isInFavorites($mysqli, $productId, $sessionId)
{
   $stmt = $mysqli->prepare("SELECT id FROM favorites WHERE productId = ? AND sessionId = ?");
   $stmt->bind_param("is", $productId, $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   return $result->num_rows > 0;
}

// ========== ФУНКЦИИ ДЛЯ РАБОТЫ С КОРЗИНОЙ ==========

// Функция для добавления товара в корзину
function addToBasket($mysqli, $productId, $sessionId, $quantity = 1, $size = null)
{
   // Проверяем, есть ли уже такой товар с таким размером в корзине
   $stmt = $mysqli->prepare("SELECT id, quantity FROM basket WHERE productId = ? AND sessionId = ? AND size = ?");
   $stmt->bind_param("iss", $productId, $sessionId, $size);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      // Если товар уже есть, увеличиваем количество
      $row = $result->fetch_assoc();
      $newQuantity = $row['quantity'] + $quantity;

      $updateStmt = $mysqli->prepare("UPDATE basket SET quantity = ? WHERE id = ?");
      $updateStmt->bind_param("ii", $newQuantity, $row['id']);

      if ($updateStmt->execute()) {
         return ['success' => true, 'message' => 'Количество товара в корзине увеличено'];
      } else {
         return ['success' => false, 'message' => 'Ошибка при обновлении количества'];
      }
   } else {
      // Добавляем новый товар в корзину
      $stmt = $mysqli->prepare("INSERT INTO basket (productId, sessionId, quantity, size) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("isis", $productId, $sessionId, $quantity, $size);

      if ($stmt->execute()) {
         return ['success' => true, 'message' => 'Товар добавлен в корзину'];
      } else {
         return ['success' => false, 'message' => 'Ошибка при добавлении товара'];
      }
   }
}

// Функция для удаления товара из корзины
function removeFromBasket($mysqli, $basketId, $sessionId)
{
   $stmt = $mysqli->prepare("DELETE FROM basket WHERE id = ? AND sessionId = ?");
   $stmt->bind_param("is", $basketId, $sessionId);

   if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Товар удален из корзины'];
   } else {
      return ['success' => false, 'message' => 'Ошибка при удалении товара'];
   }
}

// Функция для обновления количества товара в корзине
function updateBasketQuantity($mysqli, $basketId, $sessionId, $quantity)
{
   if ($quantity <= 0) {
      return removeFromBasket($mysqli, $basketId, $sessionId);
   }

   $stmt = $mysqli->prepare("UPDATE basket SET quantity = ? WHERE id = ? AND sessionId = ?");
   $stmt->bind_param("iis", $quantity, $basketId, $sessionId);

   if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Количество товара обновлено'];
   } else {
      return ['success' => false, 'message' => 'Ошибка при обновлении количества'];
   }
}

// Функция для получения товаров из корзины
function getBasketItems($mysqli, $sessionId)
{
   $stmt = $mysqli->prepare("
      SELECT b.*, p.name, p.price, p.img, p.description
      FROM basket b
      JOIN product p ON b.productId = p.id
      WHERE b.sessionId = ?
      ORDER BY b.createdAt DESC
   ");
   $stmt->bind_param("s", $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   $basketItems = [];
   while ($row = $result->fetch_assoc()) {
      $basketItems[] = $row;
   }

   return $basketItems;
}

// Функция для получения количества товаров в корзине
function getBasketCount($mysqli, $sessionId)
{
   $stmt = $mysqli->prepare("SELECT SUM(quantity) as total FROM basket WHERE sessionId = ?");
   $stmt->bind_param("s", $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return (int)$row['total'];
   }

   return 0;
}

// Функция для получения общей стоимости корзины
function getBasketTotal($mysqli, $sessionId)
{
   $stmt = $mysqli->prepare("
      SELECT SUM(b.quantity * p.price) as total
      FROM basket b
      JOIN product p ON b.productId = p.id
      WHERE b.sessionId = ?
   ");
   $stmt->bind_param("s", $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return (float)$row['total'];
   }

   return 0.0;
}

// Функция для очистки корзины
function clearBasket($mysqli, $sessionId)
{
   $stmt = $mysqli->prepare("DELETE FROM basket WHERE sessionId = ?");
   $stmt->bind_param("s", $sessionId);

   if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Корзина очищена'];
   } else {
      return ['success' => false, 'message' => 'Ошибка при очистке корзины'];
   }
}

$current_page = $_SERVER['REQUEST_URI'];

function isActive($page, $current_page)
{
   return strpos($current_page, $page) !== false ? 'active' : '';
}

// Функция для фильтрации товаров с учетом категории, цены и поиска
function getFilteredProducts($mysqli, $categoryId = 0, $name = '', $priceFrom = 0, $priceTo = 0)
{
   $conditions = [];
   $params = [];
   $types = '';

   // Базовый запрос
   $sql = "SELECT * FROM product WHERE 1=1";

   // Фильтр по категории
   if ($categoryId > 0) {
      $conditions[] = "categoryId = ?";
      $params[] = $categoryId;
      $types .= 'i';
   }

   // Фильтр по названию
   if (!empty($name)) {
      $conditions[] = "name LIKE ?";
      $params[] = '%' . $name . '%';
      $types .= 's';
   }

   // Фильтр по минимальной цене
   if ($priceFrom > 0) {
      $conditions[] = "price >= ?";
      $params[] = $priceFrom;
      $types .= 'i';
   }

   // Фильтр по максимальной цене
   if ($priceTo > 0) {
      $conditions[] = "price <= ?";
      $params[] = $priceTo;
      $types .= 'i';
   }

   // Добавляем условия к запросу
   if (!empty($conditions)) {
      $sql .= " AND " . implode(" AND ", $conditions);
   }

   $sql .= " ORDER BY id DESC";

   // Подготавливаем и выполняем запрос
   if (!empty($params)) {
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param($types, ...$params);
      $stmt->execute();
      $result = $stmt->get_result();
   } else {
      $result = $mysqli->query($sql);
   }

   $products = [];
   if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $products[] = $row;
      }
   }

   return $products;
}
