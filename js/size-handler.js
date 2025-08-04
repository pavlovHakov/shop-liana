document.addEventListener("DOMContentLoaded", function () {
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

      const result = await response.json();
      if (result.success) {
        console.log("Размер сохранен:", result);
      } else {
        console.error("Ошибка сохранения размера:", result.message);
      }
    } catch (error) {
      console.error("Ошибка при сохранении размера:", error);
    }
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
      console.error("Ошибка при получении размера:", error);
    }
    return null;
  }

  // Функция для сброса всех выбранных размеров
  async function clearAllSelectedSizes() {
    try {
      const response = await fetch("/api/size-selection.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          action: "clear_all"
        }),
      });

      const result = await response.json();
      if (result.success) {
        console.log("Все размеры сброшены:", result.message);
        // Убираем активные классы со всех размеров на странице
        document.querySelectorAll(".size-item.active").forEach(button => {
          button.classList.remove("active");
        });
      } else {
        console.error("Ошибка сброса размеров:", result.message);
      }
    } catch (error) {
      console.error("Ошибка при сбросе размеров:", error);
    }
  }

  // Функция для восстановления выбранных размеров при загрузке страницы
  async function restoreSelectedSizes() {
    const productItems = document.querySelectorAll(".product-item");

    for (const productItem of productItems) {
      const productId =
        productItem.querySelector(".block-card-icon").dataset.productId;
      const selectedSize = await getSelectedSize(productId);

      if (selectedSize) {
        const sizeButtons = productItem.querySelectorAll(".size-item");
        sizeButtons.forEach((button) => {
          if (button.textContent.trim() === selectedSize) {
            button.classList.add("active");
          }
        });
      }
    }
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
      console.log("Выбран размер:", selectedSize, "для товара ID:", productId);
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
        console.log(
          "Добавление в корзину:",
          "Товар ID:",
          productId,
          "Размер:",
          size
        );

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

  // Обработчики для сброса размеров при завершении сессии
  window.addEventListener('beforeunload', function() {
    // Сбрасываем размеры при закрытии страницы/вкладки
    clearAllSelectedSizes();
  });

  window.addEventListener('unload', function() {
    // Дополнительный сброс при выгрузке страницы
    clearAllSelectedSizes();
  });

  // Сброс размеров при неактивности (через 30 минут)
  let inactivityTimer;
  const INACTIVITY_TIME = 30 * 60 * 1000; // 30 минут в миллисекундах

  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
      console.log("Сброс размеров из-за неактивности");
      clearAllSelectedSizes();
    }, INACTIVITY_TIME);
  }

  // Отслеживаем активность пользователя
  ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
    document.addEventListener(event, resetInactivityTimer, true);
  });

  // Запускаем таймер неактивности
  resetInactivityTimer();

  // Восстанавливаем выбранные размеры при загрузке страницы
  restoreSelectedSizes();

  // Экспортируем функцию для внешнего использования
  window.clearAllSelectedSizes = clearAllSelectedSizes;
});
