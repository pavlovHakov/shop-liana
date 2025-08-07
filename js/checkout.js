// js/checkout.js
// JS-валидация и динамика для формы оформления заказа

document.addEventListener("DOMContentLoaded", function () {
  // Элементы для вывода итогов
  const deliveryTotal = document.getElementById("deliveryTotal");
  const itemsTotal = document.getElementById("itemsTotal");
  const finalTotal = document.getElementById("finalTotal");
  const form = document.getElementById("orderForm");
  const deliveryRadios = document.querySelectorAll(
    'input[name="deliveryType"]'
  );
  const deliveryAddressBlock = document.getElementById("deliveryAddress");
  const btnPlaceOrder = document.querySelector(".btn-place-order");

  // Функция для расчета доставки
  function getDeliveryCost(type) {
    if (type === "courier") return 100;
    if (type === "post") return 150;
    return 0;
  }

  // Функция для обновления итогов
  function updateTotals() {
    const deliveryType = document.querySelector(
      'input[name="deliveryType"]:checked'
    );
    const itemsSum = parseInt(itemsTotal?.textContent) || 0;
    let delivery = 0;
    if (deliveryType) {
      delivery = getDeliveryCost(deliveryType.value);
    }
    if (deliveryTotal) deliveryTotal.textContent = delivery + " ₴";
    if (finalTotal) finalTotal.textContent = itemsSum + delivery + " ₴";
  }

  // Слушатели на выбор доставки
  deliveryRadios.forEach((radio) => {
    radio.addEventListener("change", updateTotals);
  });
  // Инициализация при загрузке
  updateTotals();

  // Динамика адреса доставки
  deliveryRadios.forEach((radio) => {
    radio.addEventListener("change", function () {
      if (this.value === "courier" || this.value === "post") {
        deliveryAddressBlock.style.display = "block";
      } else {
        deliveryAddressBlock.style.display = "none";
      }
      updateTotals(); // Обновлять deliveryTotal при смене доставки
    });
  });

  // Валидация формы с выводом ошибок по каждому полю
  if (form) {
    form.addEventListener("submit", function (e) {
      let errors = [];
      const name = form.customerName.value.trim();
      const phone = form.customerPhone.value.trim();
      const email = form.customerEmail.value.trim();
      const deliveryType = form.deliveryType.value;
      const city = form.deliveryCity ? form.deliveryCity.value.trim() : "";
      const address = form.deliveryAddress
        ? form.deliveryAddress.value.trim()
        : "";

      if (!name) errors.push("Введите имя и фамилию.");
      if (!phone.match(/^\+?\d[\d\s\-\(\)]{9,}$/))
        errors.push("Введите корректный телефон.");
      if (
        (deliveryType === "courier" || deliveryType === "post") &&
        (!city || !address)
      ) {
        errors.push("Заполните город и адрес доставки.");
      }
      if (!form.paymentType.value) errors.push("Выберите способ оплаты.");
      if (
        email &&
        !/^([a-zA-Z0-9_\.-]+)@([a-zA-Z0-9\.-]+)\.([a-zA-Z]{2,})$/.test(email)
      ) {
        errors.push("Введите корректный email.");
      }

      if (errors.length > 0) {
        e.preventDefault();
        showToast(errors, false);
      }
    });
  }

  // Всплывающее уведомление
  window.showToast = function (message, success = true) {
    let toast = document.createElement("div");
    toast.className = "checkout-toast" + (success ? " success" : " error");
    if (Array.isArray(message)) {
      toast.innerHTML = message.map((e) => `<div>${e}</div>`).join("");
    } else {
      toast.textContent = message;
    }
    toast.style.position = "fixed";
    toast.style.top = "30px";
    toast.style.left = "50%";
    toast.style.transform = "translateX(-50%)";
    toast.style.zIndex = "9999";
    toast.style.minWidth = "320px";
    toast.style.maxWidth = "90vw";
    toast.style.padding = "18px 24px";
    toast.style.borderRadius = "8px";
    toast.style.background = success ? "#4caf50" : "#f53f4a";
    toast.style.color = "#fff";
    toast.style.fontSize = "1.1em";
    toast.style.boxShadow = "0 4px 16px rgba(0,0,0,0.18)";
    toast.style.textAlign = "left";
    document.body.appendChild(toast);
    setTimeout(() => {
      toast.classList.add("show");
    }, 100);
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 400);
    }, 3500);
  };

  // Кнопка оформления заказа (для демонстрации)
  if (btnPlaceOrder) {
    btnPlaceOrder.addEventListener("click", function (e) {
      updateTotals(); // На всякий случай обновить deliveryTotal перед оформлением
      // showToast("Заказ успешно оформлен!", true); // Уведомление убрано по запросу
    });
  }
});
