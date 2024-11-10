document.addEventListener("DOMContentLoaded", () => {

  // ----------------------------------------
  // Animar el texto al entrar en el viewport
  // ----------------------------------------
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate");
      }
    });
  }, { threshold: 0.1 }).observe(document.querySelector(".text"));

  // -------------------------------------
  // Slider automático de las valoraciones
  // -------------------------------------
  const slides = document.querySelector(".slides");
  const slideCount = document.querySelectorAll(".slide").length;
  let index = 0;

  // Desplazar el slider hacia la izquierda cada 5 segundos para mostrar la siguiente diapositiva 
  setInterval(() => { slides.style.transform = `translateX(-${(++index % slideCount) * 100}%)`; }, 5000);

  // -----------------------------------------------------------
  // Dibujar las estrellas de valoración en base a la puntuación
  // -----------------------------------------------------------
  document.querySelectorAll(".rating").forEach(rating => {
    const ratingValue = parseFloat(rating.getAttribute("data-rating"));
    const fullStars = Math.floor(ratingValue);
    const hasHalfStar = ratingValue % 1 > 0;
    const emptyStars = 5 - Math.ceil(ratingValue);

    rating.innerHTML =
      "<i class='fas fa-star'></i>".repeat(fullStars) +
      (hasHalfStar ? "<i class='fas fa-star-half-alt'></i>" : "") +
      "<i class='far fa-star'></i>".repeat(emptyStars);
  });

  // Mostrar toast si hay un mensaje de borrado en localStorage
  const deleteSuccess = localStorage.getItem("deleteSuccess");
  if (deleteSuccess) {
    showToast(deleteSuccess, "success");
    localStorage.removeItem("deleteSuccess");
  }

});

