// product-lazy-loader.js
// Универсальная ленивка для всех браузеров + лоадер

document.addEventListener("DOMContentLoaded", function () {
  function hideLoader(img) {
    const loader = img.previousElementSibling;
    if (loader && loader.classList.contains("img-loader")) {
      loader.style.display = "none";
    }
    img.classList.add("loaded");
  }

  function showLoader(img) {
    const loader = img.previousElementSibling;
    if (loader && loader.classList.contains("img-loader")) {
      loader.style.display = "";
    }
    img.classList.remove("loaded");
  }

  function lazyLoadImage(img) {
    showLoader(img);
    img.src = img.dataset.src;
    img.onload = function () {
      hideLoader(img);
    };
    img.onerror = function () {
      hideLoader(img);
    };
  }

  if ("loading" in HTMLImageElement.prototype) {
    document.querySelectorAll("img.lazy-img").forEach((img) => {
      lazyLoadImage(img);
    });
  } else if ("IntersectionObserver" in window) {
    let observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          let img = entry.target;
          lazyLoadImage(img);
          obs.unobserve(img);
        }
      });
    });
    document.querySelectorAll("img.lazy-img").forEach((img) => {
      observer.observe(img);
    });
  } else {
    document.querySelectorAll("img.lazy-img").forEach((img) => {
      lazyLoadImage(img);
    });
  }
});
