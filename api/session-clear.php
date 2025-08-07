<?php
require_once '../function/db.php';
session_start();
$sessionId = session_id();

// Очищаем корзину для текущей сессии
$mysqli->query("DELETE FROM basket WHERE sessionId='$sessionId'");
// Можно добавить очистку других сессионных данных

header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Корзина очищена']);
