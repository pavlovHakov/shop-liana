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
   $checkQuery = "SELECT id FROM favorites WHERE productId = ? AND sessionId = ?";
   $checkStmt = $mysqli->prepare($checkQuery);
   $checkStmt->bind_param("is", $productId, $sessionId);
   $checkStmt->execute();
   $result = $checkStmt->get_result();

   if ($result->num_rows > 0) {
      return false; // Товар уже в избранном
   }

   // Добавляем товар в избранное
   $query = "INSERT INTO favorites (productId, sessionId) VALUES (?, ?)";
   $stmt = $mysqli->prepare($query);
   $stmt->bind_param("is", $productId, $sessionId);

   return $stmt->execute();
}

// Функция для удаления товара из избранного
function removeFromFavorites($mysqli, $productId, $sessionId)
{
   $query = "DELETE FROM favorites WHERE productId = ? AND sessionId = ?";
   $stmt = $mysqli->prepare($query);
   $stmt->bind_param("is", $productId, $sessionId);

   return $stmt->execute();
}

// Функция для проверки, находится ли товар в избранном
function isInFavorites($mysqli, $productId, $sessionId)
{
   $query = "SELECT id FROM favorites WHERE productId = ? AND sessionId = ?";
   $stmt = $mysqli->prepare($query);
   $stmt->bind_param("is", $productId, $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   return $result->num_rows > 0;
}

// Функция для получения избранных товаров
function getFavoriteProducts($mysqli, $sessionId)
{
   $query = "SELECT p.* FROM product p 
             INNER JOIN favorites f ON p.id = f.productId 
             WHERE f.sessionId = ?";
   $stmt = $mysqli->prepare($query);
   $stmt->bind_param("s", $sessionId);
   $stmt->execute();
   $result = $stmt->get_result();

   $favorites = [];
   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $favorites[] = $row;
      }
   }

   return $favorites;
}

$current_page = $_SERVER['REQUEST_URI'];

function isActive($page, $current_page)
{
   return strpos($current_page, $page) !== false ? 'active' : '';
}