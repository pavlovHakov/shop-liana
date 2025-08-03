<?php

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

$current_page = $_SERVER['REQUEST_URI'];

function isActive($page, $current_page)
{
   return strpos($current_page, $page) !== false ? 'active' : '';
}
