const cardIcon = document.querySelectorAll(".block-card-icon");

cardIcon.forEach((item) => {
  item.addEventListener("click", function (e) {
    e.preventDefault();

    let svg = item.querySelector("svg");
    let currentColor = svg.querySelector("path");

    if (currentColor.style.fill === "rgb(6, 173, 168)") {
      currentColor.style.fill = "rgb(228, 8, 8)";
    } else {
      currentColor.style.fill = "rgb(6, 173, 168)";
    }
  });
});
