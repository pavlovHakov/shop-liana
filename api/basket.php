<?php
require_once '../function/db.php';
require_once '../function/functions.php';

session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Неверные данные']);
    exit;
}

$action = $input['action'] ?? '';
$sessionId = session_id();

switch ($action) {
    case 'add':
        $productId = (int)($input['productId'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 1);
        $size = $input['size'] ?? null;
        
        if ($productId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Неверный ID товара']);
            exit;
        }
        
        if ($quantity <= 0) {
            $quantity = 1;
        }
        
        $result = addToBasket($mysqli, $productId, $sessionId, $quantity, $size);
        
        // Получаем обновленное количество товаров в корзине
        $basketCount = getBasketCount($mysqli, $sessionId);
        $result['basketCount'] = $basketCount;
        
        echo json_encode($result);
        break;
        
    case 'remove':
        $basketId = (int)($input['basketId'] ?? 0);
        
        if ($basketId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Неверный ID записи корзины']);
            exit;
        }
        
        $result = removeFromBasket($mysqli, $basketId, $sessionId);
        
        // Получаем обновленное количество товаров в корзине
        $basketCount = getBasketCount($mysqli, $sessionId);
        $result['basketCount'] = $basketCount;
        
        echo json_encode($result);
        break;
        
    case 'update':
        $basketId = (int)($input['basketId'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 1);
        
        if ($basketId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Неверный ID записи корзины']);
            exit;
        }
        
        $result = updateBasketQuantity($mysqli, $basketId, $sessionId, $quantity);
        
        // Получаем обновленное количество товаров в корзине и общую стоимость
        $basketCount = getBasketCount($mysqli, $sessionId);
        $basketTotal = getBasketTotal($mysqli, $sessionId);
        $result['basketCount'] = $basketCount;
        $result['basketTotal'] = $basketTotal;
        
        echo json_encode($result);
        break;
        
    case 'clear':
        $result = clearBasket($mysqli, $sessionId);
        
        $result['basketCount'] = 0;
        $result['basketTotal'] = 0;
        
        echo json_encode($result);
        break;
        
    case 'get':
        $basketItems = getBasketItems($mysqli, $sessionId);
        $basketCount = getBasketCount($mysqli, $sessionId);
        $basketTotal = getBasketTotal($mysqli, $sessionId);
        
        echo json_encode([
            'success' => true,
            'items' => $basketItems,
            'count' => $basketCount,
            'total' => $basketTotal
        ]);
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
        break;
}
?>