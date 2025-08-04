<?php
session_start();
require_once 'function/db.php';
require_once 'function/functions.php';

// Получаем товары из корзины
$sessionId = session_id();
$basketItems = getBasketItems($mysqli, $sessionId);
$basketTotal = getBasketTotal($mysqli, $sessionId);

// Если корзина пуста, перенаправляем на главную
if (empty($basketItems)) {
    header('Location: /basket.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/reset.css">
    <link rel="stylesheet" href="style/fonts/robotocondensed.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/checkout.css">
    <link rel="stylesheet" href="style/btn-scroll.css">
    <title>Оформление заказа</title>
</head>

<body>
    <?php include 'templase/header.php'; ?>

    <div class="wrapper-checkout">
        <div class="container">
            <h1 class="checkout-title">Оформление заказа</h1>

            <div class="checkout-content">
                <!-- Форма заказа -->
                <div class="checkout-form">
                    <form id="orderForm">
                        <div class="form-section">
                            <h2>Контактная информация</h2>
                            <div class="form-group">
                                <label for="customerName">Имя и фамилия *</label>
                                <input type="text" id="customerName" name="customerName" required>
                            </div>
                            <div class="form-group">
                                <label for="customerPhone">Телефон *</label>
                                <input type="tel" id="customerPhone" name="customerPhone" required 
                                       placeholder="+380 (XX) XXX-XX-XX">
                            </div>
                            <div class="form-group">
                                <label for="customerEmail">Email</label>
                                <input type="email" id="customerEmail" name="customerEmail" 
                                       placeholder="example@email.com">
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Доставка</h2>
                            <div class="delivery-options">
                                <div class="delivery-option">
                                    <input type="radio" id="delivery-pickup" name="deliveryType" value="pickup" checked>
                                    <label for="delivery-pickup">
                                        <span class="option-title">Самовывоз</span>
                                        <span class="option-description">Бесплатно</span>
                                    </label>
                                </div>
                                <div class="delivery-option">
                                    <input type="radio" id="delivery-courier" name="deliveryType" value="courier">
                                    <label for="delivery-courier">
                                        <span class="option-title">Курьерская доставка</span>
                                        <span class="option-description">100 ₴</span>
                                    </label>
                                </div>
                                <div class="delivery-option">
                                    <input type="radio" id="delivery-post" name="deliveryType" value="post">
                                    <label for="delivery-post">
                                        <span class="option-title">Новая Почта</span>
                                        <span class="option-description">По тарифам перевозчика</span>
                                    </label>
                                </div>
                            </div>

                            <div class="delivery-address" id="deliveryAddress" style="display: none;">
                                <div class="form-group">
                                    <label for="deliveryCity">Город *</label>
                                    <input type="text" id="deliveryCity" name="deliveryCity">
                                </div>
                                <div class="form-group">
                                    <label for="deliveryAddress">Адрес *</label>
                                    <textarea id="deliveryAddressText" name="deliveryAddress" rows="3" 
                                              placeholder="Улица, дом, квартира"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Оплата</h2>
                            <div class="payment-options">
                                <div class="payment-option">
                                    <input type="radio" id="payment-cash" name="paymentType" value="cash" checked>
                                    <label for="payment-cash">
                                        <span class="option-title">Наличными при получении</span>
                                    </label>
                                </div>
                                <div class="payment-option">
                                    <input type="radio" id="payment-card" name="paymentType" value="card">
                                    <label for="payment-card">
                                        <span class="option-title">Картой при получении</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Комментарий к заказу</h2>
                            <div class="form-group">
                                <textarea id="orderComment" name="orderComment" rows="4" 
                                          placeholder="Дополнительная информация к заказу"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Сводка заказа -->
                <div class="order-summary">
                    <div class="summary-content">
                        <h2>Ваш заказ</h2>
                        
                        <div class="order-items">
                            <?php foreach ($basketItems as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="/img/<?= htmlspecialchars($item['img']) ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?= htmlspecialchars($item['name']) ?></h4>
                                        <?php if ($item['size']): ?>
                                            <p class="item-size">Размер: <?= htmlspecialchars($item['size']) ?></p>
                                        <?php endif; ?>
                                        <p class="item-quantity">Количество: <?= $item['quantity'] ?></p>
                                    </div>
                                    <div class="item-price">
                                        <?= $item['price'] * $item['quantity'] ?> ₴
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="summary-totals">
                            <div class="total-line">
                                <span>Товары:</span>
                                <span id="itemsTotal"><?= $basketTotal ?> ₴</span>
                            </div>
                            <div class="total-line">
                                <span>Доставка:</span>
                                <span id="deliveryTotal">0 ₴</span>
                            </div>
                            <div class="total-line total-final">
                                <span>Итого:</span>
                                <span id="finalTotal"><?= $basketTotal ?> ₴</span>
                            </div>
                        </div>

                        <button type="submit" form="orderForm" class="btn-place-order">
                            Оформить заказ
                        </button>
                        
                        <a href="/basket.php" class="btn-back-to-basket">Вернуться в корзину</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button id="btn-scroll">Вверх</button>

    <script src="js/header-favorites.js"></script>
    <script src="js/btn-scroll.js"></script>
    <script src="js/checkout.js"></script>
</body>

</html>