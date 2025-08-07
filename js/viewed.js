// Получает просмотренные товары из LocalStorage
function getViewedProducts() {
  return JSON.parse(localStorage.getItem("viewedProducts") || "[]");
}

// Сохраняет просмотренный товар в LocalStorage
function addViewedProduct(productId) {
  let viewed = JSON.parse(localStorage.getItem("viewedProducts") || "[]");
  productId = String(productId);
  if (!viewed.includes(productId)) {
    viewed.push(productId);
    if (viewed.length > 8) viewed = viewed.slice(-8); // максимум 8 товаров
    localStorage.setItem("viewedProducts", JSON.stringify(viewed));
  }
}

// Загружает просмотренные товары с сервера и выводит их в слайдер
function renderViewedProducts() {
  const ids = getViewedProducts();
  const slider = document.querySelector(".viewed-slider");
  if (!slider) return;
  if (!ids.length) {
    slider.innerHTML =
      '<p style="color:#888;text-align:center;width:100%">Нет просмотренных товаров</p>';
    return;
  }
  fetch("/api/viewed.php?ids=" + ids.join(","))
    .then((res) => res.json())
    .then((products) => {
      slider.innerHTML = "";
      products.forEach((product) => {
        const card = document.createElement("div");
        card.className = "viewed-card";
        card.innerHTML = `
          <a href="/product.php?id=${product.id}" class="viewed-link">
            <img src="${product.img}" alt="${product.name}" class="viewed-img">
            <div class="viewed-name">${product.name}</div>
          </a>
        `;
        slider.appendChild(card);
      });
    });
}

// --- Слайдер просмотренных товаров ---
document.addEventListener("DOMContentLoaded", function () {
  renderViewedProducts();

  const slider = document.querySelector(".viewed-slider");
  const leftArrow = document.querySelector(".viewed-slider-arrow-left");
  const rightArrow = document.querySelector(".viewed-slider-arrow-right");
  let scrollStep = 320; // ширина одной карточки + отступ

  if (slider && leftArrow && rightArrow) {
    leftArrow.addEventListener("click", function () {
      slider.scrollBy({ left: -scrollStep, behavior: "smooth" });
    });
    rightArrow.addEventListener("click", function () {
      slider.scrollBy({ left: scrollStep, behavior: "smooth" });
    });
  }
});
