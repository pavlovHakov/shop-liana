-- Создание таблицы для избранных товаров
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    productId INT NOT NULL,
    sessionId VARCHAR(255) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES product(id) ON DELETE CASCADE
);

-- Создание индекса для быстрого поиска по sessionId и productId
CREATE INDEX idx_favorites_session_product ON favorites(sessionId, productId); 