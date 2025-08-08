// Функция для показа уведомлений
function showNotification(message, type = "success") {
  // Создаем элемент уведомления
  const notification = document.createElement("div");
  notification.className = `notification notification-${type}`;
  notification.textContent = message;

  // Добавляем стили
  notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 220px;
        padding: 15px 20px;
        border-radius: 3px;
        color: white;
        font-weight: bold;
        z-index: 10000;
        max-width: 300px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        ${
          type === "error"
            ? "background-color: #dc3545;"
            : "background-color: #28a745;"
        }
    `;

  // Добавляем на страницу
  document.body.appendChild(notification);

  // Анимация появления
  setTimeout(() => {
    notification.style.transform = "translateX(0)";
    notification.style.opacity = "1";
  }, 10);

  // Удаляем через 3 секунды
  setTimeout(() => {
    notification.style.transform = "translateX(100%)";
    notification.style.opacity = "0";
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }, 3000);
}

// Функция для обновления счетчика корзины в заголовке
function updateBasketCount() {
  fetch("/api/basket.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "get",
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const basketCountElement = document.querySelector(".basket-count");
        const basketContainer = document.querySelector(".basket");

        if (data.count > 0) {
          if (basketCountElement) {
            basketCountElement.textContent = data.count;
          } else {
            // Создаем элемент счетчика, если его нет
            const countSpan = document.createElement("span");
            countSpan.className = "basket-count";
            countSpan.textContent = data.count;
            basketContainer.querySelector("a").appendChild(countSpan);
          }
        } else {
          // Удаляем счетчик, если корзина пуста
          if (basketCountElement) {
            basketCountElement.remove();
          }
        }
      }
    })
    .catch((error) => {
    });
}

// Функция для добавления товара в корзину
function addToBasket(productId, quantity = 1, size = null) {
  fetch("/api/basket.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "add",
      productId: productId,
      quantity: quantity,
      size: size,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showNotification(data.message);
        updateBasketCount();
      } else {
        showNotification(data.message, "error");
      }
    })
    .catch((error) => {
      showNotification(
        "Произошла ошибка при добавлении товара в корзину",
        "error"
      );
    });
}

// Обработчики для кнопок "В корзину"
document.addEventListener("DOMContentLoaded", function () {
  // Обработчик для простых кнопок "В корзину"
  document.querySelectorAll(".btn-add-to-basket").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const productId = this.getAttribute("data-product-id");
      const quantity = parseInt(this.getAttribute("data-quantity")) || 1;

      // Проверяем выбранный размер
      const productItem = this.closest(".product-item");
      const selectedSizeButton = productItem.querySelector(".size-item.active");

      if (!selectedSizeButton) {
        showNotification("размер не выбран", "error");
        return;
      }

      const size = selectedSizeButton.textContent.trim();

      if (productId) {
        addToBasket(productId, quantity, size);
      }
    });
  });

  // Обработчик для кнопок "В корзину" с выбором размера
  document
    .querySelectorAll(".btn-add-to-basket-with-size")
    .forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const productId = this.getAttribute("data-product-id");

        // Ищем выбранный размер
        const sizeSelector = document.querySelector(
          `input[name="size-${productId}"]:checked`
        );
        const size = sizeSelector ? sizeSelector.value : null;

        if (!size) {
          showNotification("размер не выбран", "error");
          return;
        }

        const quantity =
          parseInt(document.querySelector(`#quantity-${productId}`)?.value) ||
          1;

        if (productId) {
          addToBasket(productId, quantity, size);
        }
      });
    });

  // Обновляем счетчик при загрузке страницы
  updateBasketCount();
});

// Экспортируем функции для использования в других скриптах
window.addToBasket = addToBasket;
window.updateBasketCount = updateBasketCount;


      // Функция для показа уведомлений
      function showNotification(message, type = 'success') {
         // Простая реализация уведомлений
         alert(message);
      }

      // Функция для обновления итоговой суммы
      function updateBasketTotal() {
         let total = 0;
         document.querySelectorAll('.basket-item').forEach(item => {
            const price = parseFloat(item.querySelector('.basket-item-price').textContent);
            const quantity = parseInt(item.querySelector('.quantity-input').value);
            const itemTotal = price * quantity;

            item.querySelector('.basket-item-total').textContent = itemTotal + ' ₴';
            total += itemTotal;
         });

         document.querySelector('.total-amount').textContent = total + ' ₴';
      }

      // Обработчики для кнопок количества
      document.querySelectorAll('.quantity-minus').forEach(button => {
         button.addEventListener('click', function() {
            const basketId = this.getAttribute('data-basket-id');
            const input = document.querySelector(`.quantity-input[data-basket-id="${basketId}"]`);
            let quantity = parseInt(input.value);

            if (quantity > 1) {
               quantity--;
               input.value = quantity;
               updateQuantity(basketId, quantity);
            }
         });
      });

      document.querySelectorAll('.quantity-plus').forEach(button => {
         button.addEventListener('click', function() {
            const basketId = this.getAttribute('data-basket-id');
            const input = document.querySelector(`.quantity-input[data-basket-id="${basketId}"]`);
            let quantity = parseInt(input.value);

            quantity++;
            input.value = quantity;
            updateQuantity(basketId, quantity);
         });
      });

      // Обработчик для прямого ввода количества
      document.querySelectorAll('.quantity-input').forEach(input => {
         input.addEventListener('change', function() {
            const basketId = this.getAttribute('data-basket-id');
            let quantity = parseInt(this.value);

            if (quantity < 1) {
               quantity = 1;
               this.value = quantity;
            }

            updateQuantity(basketId, quantity);
         });
      });

      // Функция для обновления количества товара
      function updateQuantity(basketId, quantity) {
         fetch('/api/basket.php', {
               method: 'POST',
               headers: {
                  'Content-Type': 'application/json',
               },
               body: JSON.stringify({
                  action: 'update',
                  basketId: basketId,
                  quantity: quantity
               })
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  updateBasketTotal();

                  // Обновляем счетчик в header
                  if (typeof updateBasketCount === 'function') {
                     updateBasketCount();
                  }
               } else {
                  showNotification(data.message, 'error');
               }
            })
            .catch(error => {
               console.error('Ошибка:', error);
               showNotification('Произошла ошибка при обновлении количества', 'error');
            });
      }

      // Обработчик для удаления товара из корзины
      document.querySelectorAll('.btn-remove-item').forEach(button => {
         button.addEventListener('click', function() {
            const basketId = this.getAttribute('data-basket-id');
            const basketItem = this.closest('.basket-item');

            if (confirm('Удалить товар из корзины?')) {
               fetch('/api/basket.php', {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                        action: 'remove',
                        basketId: basketId
                     })
                  })
                  .then(response => response.json())
                  .then(data => {
                     if (data.success) {
                        basketItem.remove();

                        // Проверяем, остались ли еще товары
                        const remainingItems = document.querySelectorAll('.basket-item');
                        if (remainingItems.length === 0) {
                           location.reload();
                        } else {
                           updateBasketTotal();
                        }

                        showNotification(data.message);

                        // Обновляем счетчик в header
                        if (typeof updateBasketCount === 'function') {
                           updateBasketCount();
                        }
                     } else {
                        showNotification(data.message, 'error');
                     }
                  })
                  .catch(error => {
                     console.error('Ошибка:', error);
                     showNotification('Произошла ошибка при удалении товара', 'error');
                  });
            }
         });
      });

      // Обработчик для очистки корзины
      document.querySelector('.btn-clear-basket')?.addEventListener('click', function() {
         if (confirm('Очистить всю корзину?')) {
            fetch('/api/basket.php', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                     action: 'clear'
                  })
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     location.reload();
                  } else {
                     showNotification(data.message, 'error');
                  }
               })
               .catch(error => {
                  console.error('Ошибка:', error);
                  showNotification('Произошла ошибка при очистке корзины', 'error');
               });
         }
      }); 