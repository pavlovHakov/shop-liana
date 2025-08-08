document.addEventListener("DOMContentLoaded", function () {
  // Таймер неактивности
  let inactivityTimeout;
  function resetInactivityTimer() {
    clearTimeout(inactivityTimeout);
    inactivityTimeout = setTimeout(() => {
      clearAllSelectedSizes();
    }, 15 * 60 * 1000); // 15 минут
  }
  // Функция для сохранения выбранного размера
  async function saveSelectedSize(productId, size) {
    try {
      const response = await fetch("/api/size-selection.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          action: "select",
          productId: productId,
          size: size,
        }),
      });

      if (response.ok) {
        const result = await response.json();
        if (result.success) {
          // Размер успешно сохранен
        } else {
          // Обработка ошибки на сервере
        }
      } else {
        // Обработка ошибки сети
      }
    } catch (error) {}
  }

  // Функция для получения сохраненного размера
  async function getSelectedSize(productId) {
    try {
      const response = await fetch(
        `/api/size-selection.php?productId=${productId}`
      );
      const result = await response.json();
      if (result.success && result.selectedSize) {
        return result.selectedSize;
      }
    } catch (error) {
      // Обработка ошибки сети
    }
    return null;
  }

  // Функция для сброса всех выбранных размеров
  function clearAllSelectedSizes() {
    // Сброс только на клиенте: убираем активные классы со всех размеров
    document.querySelectorAll(".size-item.active").forEach((button) => {
      button.classList.remove("active");
    });
  }

  // Обработка кликов по размерам
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("size-item")) {
      // Убираем активный класс у всех размеров в этом блоке
      const sizeBlock = e.target.closest(".block-size");
      const allSizes = sizeBlock.querySelectorAll(".size-item");
      allSizes.forEach((size) => size.classList.remove("active"));

      // Добавляем активный класс к выбранному размеру
      e.target.classList.add("active");

      // Сохраняем выбранный размер
      const productId = e.target
        .closest(".product-item")
        .querySelector(".block-card-icon").dataset.productId;
      const selectedSize = e.target.textContent.trim();

      saveSelectedSize(productId, selectedSize);
    }
  });

  // Обработка кликов по кнопке "Добавить в корзину"
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("btn-add-to-cart")) {
      e.preventDefault(); // Предотвращаем переход по ссылке

      const productItem = e.target.closest(".product-item");
      const productId =
        productItem.querySelector(".block-card-icon").dataset.productId;
      const selectedSize = productItem.querySelector(".size-item.active");

      if (selectedSize) {
        const size = selectedSize.textContent.trim();

        // Здесь можно добавить логику добавления в корзину
        // Например, отправку AJAX запроса на сервер
        addToCart(productId, size);
      } else {
        alert("размер не выбран");
      }
    }
  });

  // Функция для добавления товара в корзину
  async function addToCart(productId, size) {
    try {
      // Здесь можно добавить AJAX запрос для добавления в корзину
      // Пока что просто показываем уведомление
      const productName = document
        .querySelector(`[data-product-id="${productId}"]`)
        .closest(".product-item")
        .querySelector(".product-name").textContent;

      alert(`Товар "${productName}" добавлен в корзину!\nРазмер: ${size}`);

      // Можно добавить визуальную обратную связь
      const addButton = document
        .querySelector(`[data-product-id="${productId}"]`)
        .closest(".product-item")
        .querySelector(".btn-add-to-cart");

      const originalText = addButton.textContent;
      addButton.textContent = "Добавлено!";
      addButton.style.backgroundColor = "#28a745";

      setTimeout(() => {
        addButton.textContent = originalText;
        addButton.style.backgroundColor = "#06ada8";
      }, 2000);
    } catch (error) {
      console.error("Ошибка при добавлении в корзину:", error);
      alert("Ошибка при добавлении товара в корзину");
    }
  }

  // Отслеживаем активность пользователя
  [
    "mousedown",
    "mousemove",
    "keypress",
    "scroll",
    "touchstart",
    "click",
  ].forEach((event) => {
    document.addEventListener(event, resetInactivityTimer, true);
  });

  // Экспортируем функцию для внешнего использования
  window.clearAllSelectedSizes = clearAllSelectedSizes;
});

document.querySelectorAll(".size-product-item").forEach((label) => {
  label.addEventListener("click", function () {
    // Убираем активный класс со всех размеров
    document.querySelectorAll(".size-product-item").forEach((item) => {
      item.classList.remove("active");
    });

    // Добавляем активный класс к выбранному размеру
    this.classList.add("active");

    // Отмечаем соответствующий radio button
    const radio = this.querySelector('input[type="radio"]');
    if (radio) {
      radio.checked = true;
    }
  });
});
