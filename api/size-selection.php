<?php
session_start();
header('Content-Type: application/json');

require_once '../function/db.php';

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
      case 'select':
         if (!isset($input['size'])) {
            echo json_encode(['success' => false, 'message' => 'Размер не указан']);
            exit;
         }

         $size = trim($input['size']);

         // Сохраняем выбранный размер в сессии
         if (!isset($_SESSION['selected_sizes'])) {
            $_SESSION['selected_sizes'] = [];
         }

         $_SESSION['selected_sizes'][$productId] = $size;

         echo json_encode([
            'success' => true,
            'message' => 'Размер выбран',
            'productId' => $productId,
            'size' => $size
         ]);
         break;

      case 'get':
         $selectedSize = isset($_SESSION['selected_sizes'][$productId])
            ? $_SESSION['selected_sizes'][$productId]
            : null;

         echo json_encode([
            'success' => true,
            'productId' => $productId,
            'selectedSize' => $selectedSize
         ]);
         break;

      case 'clear':
         if (isset($_SESSION['selected_sizes'][$productId])) {
            unset($_SESSION['selected_sizes'][$productId]);
         }

         echo json_encode([
            'success' => true,
            'message' => 'Выбор размера очищен',
            'productId' => $productId
         ]);
         break;

      case 'clear_all':
         // Очищаем все выбранные размеры
         if (isset($_SESSION['selected_sizes'])) {
            unset($_SESSION['selected_sizes']);
         }

         echo json_encode([
            'success' => true,
            'message' => 'Все выбранные размеры очищены'
         ]);
         break;

      default:
         echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
   }
} elseif ($method === 'GET') {
   if (isset($_GET['productId'])) {
      $productId = (int)$_GET['productId'];
      $selectedSize = isset($_SESSION['selected_sizes'][$productId])
         ? $_SESSION['selected_sizes'][$productId]
         : null;

      echo json_encode([
         'success' => true,
         'productId' => $productId,
         'selectedSize' => $selectedSize
      ]);
   } else {
      // Возвращаем все выбранные размеры
      $selectedSizes = isset($_SESSION['selected_sizes']) ? $_SESSION['selected_sizes'] : [];
      echo json_encode([
         'success' => true,
         'selectedSizes' => $selectedSizes
      ]);
   }
} else {
   echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
}
