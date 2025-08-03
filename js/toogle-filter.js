// toggle filter
const filter = document.querySelector(".form-block");
const filterBtn = document.querySelector(".filter-btn");

filterBtn.addEventListener("click", () => {
  filter.classList.toggle("active-filter");
});