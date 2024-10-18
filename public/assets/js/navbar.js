document.addEventListener("DOMContentLoaded", () => {

  // --------------------------------
  // Manejar los dropdowns del navbar
  // --------------------------------
  const dropdownButtons = document.querySelectorAll(".menu .dropbtn");
  const menuIcon = document.querySelector("#menu-icon");
  const menu = document.querySelector(".menu");
  const menuLinks = document.querySelectorAll(".menu a");

  // Mostrar/ocultar el contenido del dropdown al hacer clic
  dropdownButtons.forEach(button => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const dropdownContent = button.nextElementSibling;
      dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
      button.classList.toggle("active");
    });
  });

  // Cerrar el dropdown si se hace clic fuera de él
  window.addEventListener("click", event => {
    if (!event.target.matches(".dropbtn") && !event.target.closest(".user-menu")) {
      document.querySelectorAll(".user-menu-content").forEach(dropdown => dropdown.style.display = "none");
      dropdownButtons.forEach(button => button.classList.remove("active"));
    }
  });

  // ---------------------------
  // Alternar el menú responsive
  // ---------------------------
  function toggleMenu() {
    const centerMenu = document.querySelector(".menu ul.center");

    if (!menu.classList.contains("show-menu")) {

      // Abrir el menú
      menu.classList.add("show-menu");
      document.body.classList.add("no-scroll");
      menuIcon.classList.remove("fa-bars");
      menuIcon.classList.add("fa-times");

      // Animación de apertura
      centerMenu.style.transform = "translateX(-100%)";
      centerMenu.style.display = "flex";
      setTimeout(() => {
        centerMenu.style.transition = "transform 0.2s ease-out";
        centerMenu.style.transform = "translateX(0)";
      }, 10);
    } else {

      // Animación de cierre
      centerMenu.style.transition = "transform 0.2s ease-out";
      centerMenu.style.transform = "translateX(-100%)";

      setTimeout(() => {
        menu.classList.remove("show-menu");
        document.body.classList.remove("no-scroll");
        menuIcon.classList.add("fa-bars");
        menuIcon.classList.remove("fa-times");
        centerMenu.style.display = "none";
        centerMenu.style.transform = "";
        centerMenu.style.transition = "";
      }, 300);
    }
  }
  
  // Cambiar el icono del menú y mostrar/ocultar el menú al hacer clic
  menuIcon.addEventListener("click", toggleMenu);

  // Cerrar el menú al hacer clic en un enlace
  menuLinks.forEach(link => link.addEventListener("click", () => {
    if (menu.classList.contains("show-menu")) toggleMenu();
  }));

  // --------------------
  // Modal de ayuda (FAQ)
  // --------------------
  const modal = document.getElementById("faq-modal");

  // Apertura
  document.getElementById("open-modal").onclick = (event) => {
    event.preventDefault();
    modal.style.display = "block";
    document.body.classList.add("no-scroll");
  };

  // Cierre
  modal.onclick = (event) => {
    if (event.target.matches(".close, #faq-modal")) {
      modal.style.display = "none";
      document.body.classList.remove("no-scroll"); // Reactiva el scroll
    }
  };
});

