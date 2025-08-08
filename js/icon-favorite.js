const cardIcon = document.querySelectorAll(".block-card-icon");

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
          if (typeof window.updateFavoritesCount === "function") {
            window.updateFavoritesCount();
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
  notification.className =
    "notification" + (type === "success" ? " success" : " error");
  notification.textContent = message;
  notification.style.position = "fixed";
  notification.style.top = "20px";
  notification.style.right = "220px";
  notification.style.padding = "15px 20px";
  notification.style.borderRadius = "3px";
  notification.style.color = "white";
  notification.style.fontWeight = "bold";
  notification.style.zIndex = "1000";
  notification.style.transition = "all 0.3s ease";
  notification.style.backgroundColor =
    type === "success" ? "#4CAF50" : "#f44336";
  notification.style.opacity = "1";

  document.body.appendChild(notification);

  // Удаляем уведомление через 3 секунды
  setTimeout(function () {
    notification.style.opacity = "0";
    setTimeout(function () {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
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
      .catch((error) => {});
  });
}

// Инициализируем состояние избранного при загрузке страницы
document.addEventListener("DOMContentLoaded", function () {
  initializeFavorites();
});

// Специальный обработчик для кнопки избранного на странице товара
document.addEventListener("DOMContentLoaded", function () {
  const favoriteBtn = document.querySelector(".btn-favorites");
  if (favoriteBtn) {
    const productId = favoriteBtn.getAttribute("data-product-id");
    const path = favoriteBtn.querySelector(".favorite-path");

    // Проверяем текущее состояние
    fetch(`/api/favorites.php?action=check&productId=${productId}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.isFavorite) {
          path.style.fill = "rgb(228, 8, 8)";
          favoriteBtn.textContent = "В избранном";
        } else {
          path.style.fill = "rgb(6, 173, 168)";
          favoriteBtn.textContent = "Добавить в избранное";
        }
      })
      .catch((error) => {
        console.error("Ошибка при проверке избранного:", error);
      });

    // Обработчик клика
    favoriteBtn.addEventListener("click", function (e) {
      e.preventDefault();

      const isFavorite = path.style.fill === "rgb(228, 8, 8)";
      const action = isFavorite ? "remove" : "add";

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
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            if (action === "add") {
              path.style.fill = "rgb(228, 8, 8)";
              favoriteBtn.textContent = "В избранном";
              showNotification(data.message);
            } else {
              path.style.fill = "rgb(6, 173, 168)";
              favoriteBtn.textContent = "Добавить в избранное";
              showNotification(data.message);
            }

            // Обновляем счетчик в header
            if (typeof updateFavoritesCount === "function") {
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
  }
});
