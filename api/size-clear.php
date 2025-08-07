<?php
require_once '../function/db.php';
session_start();
$sessionId = session_id();

// Получаем размер для удаления из запроса
$input = json_decode(file_get_contents('php://input'), true);
$size = $input['size'] ?? null;

if ($size) {
   // Удаляем только выбранный размер из корзины
   $stmt = $mysqli->prepare("DELETE FROM basket WHERE sessionId = ? AND size = ?");
   $stmt->bind_param('ss', $sessionId, $size);
   $stmt->execute();
   $stmt->close();
   $result = ['success' => true, 'message' => 'Размер удалён из корзины'];
} else {
   $result = ['success' => false, 'message' => 'Размер не указан'];
}

header('Content-Type: application/json');
echo json_encode($result);
