// script.js
document.addEventListener("DOMContentLoaded", function () {
  const carousels = document.querySelectorAll(".carousel-container");

  carousels.forEach((carouselContainer) => {
    const carousel = carouselContainer.querySelector(".carousel");
    const leftButton = carouselContainer.querySelector(".carousel-button.left");
    const rightButton = carouselContainer.querySelector(".carousel-button.right");

    function updateButtons() {
      if (carousel.scrollLeft <= 0) {
        leftButton.classList.add("hidden");
      } else {
        leftButton.classList.remove("hidden");
      }

      if (carousel.scrollLeft + carousel.offsetWidth >= carousel.scrollWidth) {
        rightButton.classList.add("hidden");
      } else {
        rightButton.classList.remove("hidden");
      }
    }

    leftButton.addEventListener("click", function () {
      carousel.scrollBy({
        left: -200, // Adjust scroll amount as needed
        behavior: "smooth",
      });
    });

    rightButton.addEventListener("click", function () {
      carousel.scrollBy({
        left: 200, // Adjust scroll amount as needed
        behavior: "smooth",
      });
    });

    carousel.addEventListener("scroll", updateButtons);
    window.addEventListener("resize", updateButtons); // Ensure buttons are updated on resize

    updateButtons(); // Initial check
  });
});
