<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../function/db.php';

$ids = isset($_GET['ids']) ? $_GET['ids'] : '';
if (!$ids) {
   echo json_encode([]);
   exit;
}
$idArr = array_filter(array_map('intval', explode(',', $ids)));
if (!$idArr) {
   echo json_encode([]);
   exit;
}

// Получаем товары по ID
$in = implode(',', $idArr);
$res = $mysqli->query("SELECT id, name, img FROM product WHERE id IN ($in)");
$products = [];
while ($row = $res->fetch_assoc()) {
   // Корректируем путь к изображению
   if (!empty($row['img'])) {
      $row['img'] = '/img/' . $row['img'];
   } else {
      $row['img'] = '/img/no-image.png';
   }
   $products[] = $row;
}
echo json_encode($products);
