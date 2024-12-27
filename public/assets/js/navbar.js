document.addEventListener("DOMContentLoaded", () => {
  // --------------------------------
  // Manejar los dropdowns del navbar
  // --------------------------------

  // Mostrar/ocultar el contenido del dropdown al hacer clic
  document.querySelectorAll(".menu .dropbtn").forEach(button => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const dropdownContent = button.nextElementSibling;
      dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
      button.classList.toggle("active");
    });
  });

  // Cerrar el dropdown si se hace clic fuera de el
  window.addEventListener("click", event => {
    if (!event.target.closest(".dropbtn, .user-menu")) {
      document.querySelectorAll(".user-menu-content").forEach(dropdown => dropdown.style.display = "none");
      document.querySelectorAll(".menu .dropbtn").forEach(button => button.classList.remove("active"));
    }
  });

  // ---------------------------
  // Alternar el menú responsive
  // ---------------------------
  const menuIcon = document.querySelector("#menu-icon");
  const menu = document.querySelector(".menu");

  const toggleMenu = () => {
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
  };

  // Cambiar el icono del menú y mostrar/ocultar el menú al hacer clic
  menuIcon.addEventListener("click", toggleMenu);

  // Cerrar el menú al hacer clic en un enlace
  document.querySelectorAll(".menu a").forEach((link) =>
    link.addEventListener("click", () => {
      if (menu.classList.contains("show-menu")) toggleMenu();
    })
  );

  // --------------------
  // Modal de ayuda (FAQ)
  // --------------------
  const modal = document.getElementById("faq-modal");

  document.getElementById("open-modal").addEventListener("click", (event) => {
    event.preventDefault();
    modal.style.display = "block";
    document.body.classList.add("no-scroll");
  });

  modal.addEventListener("click", (event) => {
    if (event.target.matches(".close, #faq-modal")) {
      event.preventDefault();
      modal.style.display = "none";
      document.body.classList.remove("no-scroll"); // Reactiva el scroll
    }
  });

  // -----------------------
  // Modal de Notificaciones
  // -----------------------
  const notifications = document.getElementById("notifications");
  const notificationModal = document.getElementById("notification-modal");
  const notificationCount = document.getElementById("notification-count");
  const notificationList = document.getElementById("notification-list");

  const toggleNotificationModal = () => {
    notificationModal.style.display = notificationModal.style.display === "block" ? "none" : "block";
    if (notificationModal.style.display === "block") loadNotifications();
  };

  // Abrir/cerrar modal al hacer clic en la campana
  notifications?.addEventListener("click", event => {
    event.preventDefault();
    event.stopPropagation();
    toggleNotificationModal();
  });

  // Cerrar modal si se hace clic fuera de él
  document.addEventListener("click", event => {
    if (!notificationModal.contains(event.target) && event.target !== notifications) {
      notificationModal.style.display = "none";
    }
  });

  // Actualizar el contador de notificaciones
  const updateNotificationCount = async () => {
    try {
      const response = await fetch("/get-notifications");
      const data = await response.json();
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
      const response = await fetch("/get-notifications");
      const data = await response.json();

      // Actualizar la lista de notificaciones
      notificationList.innerHTML = data.success && data.notifications.length
        ? data.notifications.map(notification => `
        <div class="notification-item" data-post-id="${notification.post_id}" data-post-title="${notification.post_title}">
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

      // Actualizar botón
      document.getElementById("mark-all-read")?.classList.toggle("disabled", !(data.success && data.notifications.length));
      updateNotificationCount();

      // Evento de clic en cada notificación para abrir el post correspondiente
      document.querySelectorAll(".notification-item").forEach(item => {
        item.addEventListener("click", () => {
          const postId = item.getAttribute("data-post-id");
          const postTitle = item.getAttribute("data-post-title");
          if (postId && postTitle) { window.location.href = `/posts/${postId}/${postTitle}`; }
        });
      });

    } catch {
      notificationList.innerHTML = "Error al cargar notificaciones.";
      document.getElementById("mark-all-read")?.classList.add("disabled");
    }
  };

  // Obtener el mensaje correspondiente segun el tipo de notificación
  const getMessage = notification => {
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
          notificationList.innerHTML = `<div class="notification-item">No tienes nuevas notificaciones.</div>`;
          updateNotificationCount();
        }
      } catch (error) {
        console.error(error);
      }
    });
  }

  // Inicializar el contador al cargar la página
  updateNotificationCount();

  // Actualizar el contador y las notificaciones cada minuto para obtener los cambios en tiempo real
  setInterval(() => {
    updateNotificationCount();
    loadNotifications();
  }, 60000);

});
