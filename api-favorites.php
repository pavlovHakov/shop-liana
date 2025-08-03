<?php
session_start();
require_once 'function/db.php';
require_once 'function/functions.php';

header('Content-Type: application/json');

// Получаем или создаем session ID
if (!isset($_SESSION['session_id'])) {
   $_SESSION['session_id'] = uniqid();
}
$sessionId = $_SESSION['session_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $input = json_decode(file_get_contents('php://input'), true);

   if (isset($input['action']) && isset($input['productId'])) {
      $action = $input['action'];
      $productId = (int)$input['productId'];

      switch ($action) {
         case 'add':
            $result = addToFavorites($mysqli, $productId, $sessionId);
            if ($result) {
               echo json_encode(['success' => true, 'message' => 'Товар добавлен в избранное']);
            } else {
               echo json_encode(['success' => false, 'message' => 'Товар уже в избранном или произошла ошибка']);
            }
            break;

         case 'remove':
            $result = removeFromFavorites($mysqli, $productId, $sessionId);
            if ($result) {
               echo json_encode(['success' => true, 'message' => 'Товар удален из избранного']);
            } else {
               echo json_encode(['success' => false, 'message' => 'Произошла ошибка при удалении']);
            }
            break;

         case 'check':
            $isFavorite = isInFavorites($mysqli, $productId, $sessionId);
            echo json_encode(['success' => true, 'isFavorite' => $isFavorite]);
            break;

         default:
            echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
      }
   } else {
      echo json_encode(['success' => false, 'message' => 'Отсутствуют необходимые параметры']);
   }
} else {
   echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
}