document.addEventListener("DOMContentLoaded", function () {
  const header = document.getElementById("mainHeader");
  let lastScroll = window.scrollY;

  function onScroll() {
    const currentScroll = window.scrollY;
    if (currentScroll < lastScroll && currentScroll > 5) {
      // Скролл вверх
      header.classList.add("header-fixed");
      header.classList.remove("hide");
    } else if (currentScroll > lastScroll && currentScroll > 5) {
      // Скролл вниз
      header.classList.remove("header-fixed");
      header.classList.add("hide");
    } else if (currentScroll <= 5) {
      // В самом верху
      header.classList.remove("header-fixed");
      header.classList.remove("hide");
    }
    lastScroll = currentScroll;
  }
  window.addEventListener("scroll", onScroll);
});
