-- Таблица для корзины товаров
CREATE TABLE basket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    productId INT NOT NULL,
    sessionId VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    size VARCHAR(10) DEFAULT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES product(id) ON DELETE CASCADE
);

-- Индекс для быстрого поиска по сессии
CREATE INDEX idx_basket_session ON basket(sessionId);

-- Индекс для быстрого поиска по товару и сессии
CREATE INDEX idx_basket_product_session ON basket(productId, sessionId);