const sizeModal = document.querySelector(".wrapper-modal-size");
const btnSizeOpen = document.querySelector(".table-size-link");
const closeSizeBtn = document.querySelector(".close-modal-size");

const btnBuy = document.querySelector(".buy_click");
const closeBuyBtn = document.querySelector(".close-modal-buy-one-click");

const buyModal = document.querySelector(".wrapper-modal-buy-one-click");

//function закрыть модальное окно
function closeModal(cnp, modal) {
  cnp.addEventListener("click", () => {
    modal.style.display = "none";
  });
}

// закрыть модальное окно - Размер таблицы
closeModal(closeSizeBtn, sizeModal);
// закрыть модальное окно - Быстрая покупка
closeModal(closeBuyBtn, buyModal);
// ////////////////////////////////////////////////////////////////////////

//function открыть модальное окно при клике на ссылку
function openModalOnClickOutside(link, modal) {
  link.addEventListener("click", () => {
    modal.style.display = "flex";
  });
}

// открыть модальное окно - Размер таблицы
openModalOnClickOutside(btnSizeOpen, sizeModal);
// открыть модальное окно - Быстрая покупка
openModalOnClickOutside(btnBuy, buyModal);
// /////////////////////////////////////////////////////////
