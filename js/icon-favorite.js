const cardIcon = document.querySelectorAll(".block-card-icon");

console.log("Найдено иконок избранного:", cardIcon.length);

cardIcon.forEach((item, index) => {
  console.log(
    `Инициализация иконки ${index + 1}, productId:`,
    item.getAttribute("data-product-id")
  );

  item.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    let svg = item.querySelector("svg");
    let currentColor = svg.querySelector("path");
    let productId = item.getAttribute("data-product-id");

    console.log("Клик по иконке избранного, productId:", productId);

    // Определяем текущее состояние (в избранном или нет)
    let isFavorite = currentColor.style.fill === "rgb(228, 8, 8)";
    let action = isFavorite ? "remove" : "add";

    console.log(
      "Текущее состояние:",
      isFavorite ? "в избранном" : "не в избранном"
    );
    console.log("Действие:", action);

    // Отправляем AJAX запрос
    fetch("/api/favorites.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        action: action,
        productId: productId,
      }),
    })
      .then((response) => {
        console.log("Ответ от сервера:", response.status);
        return response.json();
      })
      .then((data) => {
        console.log("Данные от сервера:", data);

        if (data.success) {
          // Обновляем визуальное состояние
          if (action === "add") {
            currentColor.style.fill = "rgb(228, 8, 8)";
            console.log("Товар добавлен в избранное");
          } else {
            currentColor.style.fill = "rgb(6, 173, 168)";
            console.log("Товар удален из избранного");
          }

                     // Показываем уведомление
           showNotification(data.message);
           
           // Обновляем счетчик в header
           if (typeof updateFavoritesCount === 'function') {
              updateFavoritesCount();
           }
         } else {
           showNotification(data.message, "error");
         }
      })
      .catch((error) => {
        console.error("Ошибка:", error);
        showNotification("Произошла ошибка при работе с избранным", "error");
      });
  });
});

// Функция для показа уведомлений
function showNotification(message, type = "success") {
  console.log("Показываем уведомление:", message, "тип:", type);

  // Создаем элемент уведомления
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.textContent = message;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    z-index: 1000;
    transition: all 0.3s ease;
    ${
      type === "success"
        ? "background-color: #4CAF50;"
        : "background-color: #f44336;"
    }
  `;

  document.body.appendChild(notification);

  // Удаляем уведомление через 3 секунды
  setTimeout(() => {
    notification.style.opacity = "0";
    setTimeout(() => {
      document.body.removeChild(notification);
    }, 300);
  }, 3000);
}

// Функция для инициализации состояния избранного при загрузке страницы
function initializeFavorites() {
  console.log("Инициализация состояния избранного...");

  const productItems = document.querySelectorAll(".product-item");
  console.log("Найдено товаров:", productItems.length);

  productItems.forEach((item, index) => {
    const icon = item.querySelector(".block-card-icon");
    if (!icon) {
      console.log(`Товар ${index + 1}: иконка не найдена`);
      return;
    }

    const productId = icon.getAttribute("data-product-id");
    console.log(`Товар ${index + 1}: productId = ${productId}`);

    if (!productId) {
      console.log(`Товар ${index + 1}: productId не найден`);
      return;
    }

    // Проверяем, находится ли товар в избранном
    fetch(`/api/favorites.php?action=check&productId=${productId}`)
      .then((response) => response.json())
      .then((data) => {
        console.log(
          `Товар ${productId}:`,
          data.isFavorite ? "в избранном" : "не в избранном"
        );

        const svg = icon.querySelector("svg");
        const path = svg.querySelector("path");

        if (data.isFavorite) {
          path.style.fill = "rgb(228, 8, 8)";
        } else {
          path.style.fill = "rgb(6, 173, 168)";
        }
      })
      .catch((error) => {
        console.error(`Ошибка при проверке товара ${productId}:`, error);
      });
  });
}

// Инициализируем состояние избранного при загрузке страницы
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM загружен, инициализируем избранное...");
  initializeFavorites();
});
