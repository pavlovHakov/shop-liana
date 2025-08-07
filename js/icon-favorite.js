const cardIcon = document.querySelectorAll(".block-card-icon");

console.log("Найдено иконок избранного:", cardIcon.length);

cardIcon.forEach((item, index) => {

  item.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    let svg = item.querySelector("svg");
    let currentColor = svg.querySelector("path");
    let productId = item.getAttribute("data-product-id");

    // Определяем текущее состояние (в избранном или нет)
    let isFavorite = currentColor.style.fill === "rgb(228, 8, 8)";
    let action = isFavorite ? "remove" : "add";


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
        return response.json();
      })
      .then((data) => {
        if (data.success) {
          // Обновляем визуальное состояние
          if (action === "add") {
            currentColor.style.fill = "rgb(228, 8, 8)";
          } else {
            currentColor.style.fill = "rgb(6, 173, 168)";
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
        showNotification("Произошла ошибка при работе с избранным", "error");
      });
  });
});

// Функция для показа уведомлений
function showNotification(message, type = "success") {

  // Создаем элемент уведомления
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.textContent = message;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 220px;
    padding: 15px 20px;
    border-radius: 3px;
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

  const productItems = document.querySelectorAll(".product-item");

  productItems.forEach((item, index) => {
    const icon = item.querySelector(".block-card-icon");
    if (!icon) {
      return;
    }

    const productId = icon.getAttribute("data-product-id");

    if (!productId) {
      return;
    }

    // Проверяем, находится ли товар в избранном
    fetch(`/api/favorites.php?action=check&productId=${productId}`)
      .then((response) => response.json())
      .then((data) => {

        const svg = icon.querySelector("svg");
        const path = svg.querySelector("path");

        if (data.isFavorite) {
          path.style.fill = "rgb(228, 8, 8)";
        } else {
          path.style.fill = "rgb(6, 173, 168)";
        }
      })
      .catch((error) => {
      });
  });
}

// Инициализируем состояние избранного при загрузке страницы
document.addEventListener("DOMContentLoaded", function () {
  initializeFavorites();
});
