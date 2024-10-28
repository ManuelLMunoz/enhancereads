document.addEventListener("DOMContentLoaded", () => {
  // --------------------------------
  // Manejar los dropdowns del navbar
  // --------------------------------
  const dropdownButtons = document.querySelectorAll(".menu .dropbtn");
  const menuIcon = document.querySelector("#menu-icon");
  const menu = document.querySelector(".menu");
  const menuLinks = document.querySelectorAll(".menu a");

  // Mostrar/ocultar el contenido del dropdown al hacer clic
  dropdownButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const dropdownContent = button.nextElementSibling;
      dropdownContent.style.display =
        dropdownContent.style.display === "block" ? "none" : "block";
      button.classList.toggle("active");
    });
  });

  // Cerrar el dropdown si se hace clic fuera de él
  window.addEventListener("click", (event) => {
    if (
      !event.target.matches(".dropbtn") &&
      !event.target.closest(".user-menu")
    ) {
      document
        .querySelectorAll(".user-menu-content")
        .forEach((dropdown) => (dropdown.style.display = "none"));
      dropdownButtons.forEach((button) => button.classList.remove("active"));
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
  menuLinks.forEach((link) =>
    link.addEventListener("click", () => {
      if (menu.classList.contains("show-menu")) toggleMenu();
    })
  );

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

  // -----------------------
  // Modal de Notificaciones
  // -----------------------
  const notifications = document.getElementById("notifications");
  const notificationModal = document.getElementById("notification-modal");
  const notificationCount = document.getElementById("notification-count");
  const notificationList = document.getElementById("notification-list");

  // Mostrar/ocultar el modal de las notificaciones
  const toggleNotificationModal = () => {
    notificationModal.style.display = notificationModal.style.display === "block" ? "none" : "block";
    if (notificationModal.style.display === "block") loadNotifications();
  };

  // Abrir/cerrar el modal al hacer clic en la campana
  if (notifications) {
    notifications.addEventListener("click", (event) => {
      event.stopPropagation();
      toggleNotificationModal();
    });
  }

  // Cerrar el modal si se hace clic fuera de él
  document.addEventListener("click", (event) => {
    if (!notificationModal.contains(event.target) && event.target !== notifications) {
      notificationModal.style.display = "none";
    }
  });

  // Actualizar el contador de notificaciones
  const updateNotificationCount = async () => {
    try {
      // Solicitud para obtener las notificaciones en forma JSON
      const response = await fetch("/get-notifications");
      const data = await response.json();
      // Actualiza el contador si se obtuvo una respuesta exitosa
      if (notificationCount) {
        const count = data.success ? data.notifications.length : 0;
        notificationCount.textContent = count;
        notificationCount.style.display = count > 0 ? "inline" : "none";
      }
    } catch (error) {
      console.error(error);
    }
  };
  // Cargar las notificaciones en el modal
  const loadNotifications = async () => {
    try {
      // Solicitud para obtener las notificaciones en forma JSON
      const response = await fetch("/get-notifications");
      const data = await response.json();

      // Actualizar la lista de notificaciones
      notificationList.innerHTML = data.success && data.notifications.length
        ? data.notifications.map(notification => `
      <div class="notification-item">
          <img class="avatar" src="assets/img/avatars/${notification.notifier_avatar}" alt="Avatar">
          <div class="notification-content">
              <p class="notification-header">
                  <strong class="notifier">${notification.notifier}</strong> ${getMessage(notification)}
                  <span class="notification-date"> (${notification.formatted_date})</span>
              </p>
              <p class="notification-body">"${notification.content.slice(0, 50)}"...</p>
          </div>
      </div>`).join("")
        : `<div class="notification-item"> No tienes nuevas notificaciones. </div>`;

      $("#mark-all-read").toggleClass("disabled", !data.success || !data.notifications.length);
      updateNotificationCount();
    } catch {
      notificationList.innerHTML = "Error al cargar notificaciones.";
      $("#mark-all-read").addClass("disabled");
    }
  };

  // Obtener el mensaje correspondiente segun el tipo de notificación
  const getMessage = (notification) => {
    switch (notification.type) {
      case "like": return notification.comment_id ? "ha dado like a tu comentario:" : "ha dado like a tu post:";
      case "comment": return "ha comentado en tu post:";
      case "reply": return "ha respondido a tu comentario:";
      default: return "";
    }
  };

  // Marcar todas las notificaciones como leídas
  if (notifications) {
    document.getElementById("mark-all-read").addEventListener("click", async () => {
      try {
        const response = await fetch("/mark-all-notifications-read", { method: "POST" });
        const data = await response.json();
        if (data.success) {
          notificationList.textContent = "Todas las notificaciones han sido marcadas como leídas.";
          updateNotificationCount();
        }
      } catch (error) {
        console.error(error);
      }
    });
  }

  // Inicializar el contador al cargar la página
  updateNotificationCount();

  // Actualizar el contador y las notificaciones cada minuto
  setInterval(() => {
    updateNotificationCount();
    loadNotifications();
  }, 60000);

});
