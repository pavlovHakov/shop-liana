<?php
session_start();
header('Content-Type: application/json');

require_once '../function/db.php';
require_once '../function/functions.php';

// Получаем session ID
$sessionId = session_id();

// Проверяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
   $input = json_decode(file_get_contents('php://input'), true);

   if (!isset($input['action']) || !isset($input['productId'])) {
      echo json_encode(['success' => false, 'message' => 'Неверные параметры']);
      exit;
   }

   $action = $input['action'];
   $productId = (int)$input['productId'];

   switch ($action) {
      case 'add':
         $result = addToFavorites($mysqli, $productId, $sessionId);
         echo json_encode($result);
         break;

      case 'remove':
         $result = removeFromFavorites($mysqli, $productId, $sessionId);
         echo json_encode($result);
         break;

      default:
         echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
   }
} elseif ($method === 'GET') {
   if (isset($_GET['action']) && $_GET['action'] === 'check') {
      $productId = (int)$_GET['productId'];
      $isFavorite = isInFavorites($mysqli, $productId, $sessionId);
      echo json_encode(['isFavorite' => $isFavorite]);
   } else {
      $favorites = getFavorites($mysqli, $sessionId);
      echo json_encode(['favorites' => $favorites]);
   }
} else {
   echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
}