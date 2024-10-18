document.addEventListener("DOMContentLoaded", () => {

  // -------------------------------------------------------
  // Cargar el formulario seleccionado o "login" por defecto
  // -------------------------------------------------------
  const form = document.querySelector("#main").dataset.activeForm || "login";
  toggleForm(form, false);
  history.replaceState({ form }, "", `/${form}`); // Se actualiza el historial

  // Mostrar/ocultar los formularios según el seleccionado
  function toggleForm(formName, updateHistory = true) {
    ["login", "register", "reset-password"].forEach(form => {
      const formElement = document.getElementById(`${form}-form`);
      if (formElement) {
        formElement.style.display = form === formName ? "block" : "none";
      }
      document.querySelectorAll(`.error-${form}`).forEach(el => el.innerHTML = "");
    });

    // Se actualiza el historial si está habilitado
    updateHistory && history.pushState({ form: formName }, "", `/${formName}`);
  }

  // Manejar los clics en los enlaces de los formularios
  ["forgot-password-link", "register-link", "back-login-from-register", "back-login-from-reset-pass"].forEach(id => {
    document.getElementById(id)?.addEventListener("click", (event) => {
      event.preventDefault();
      toggleForm(
        id === "forgot-password-link" ? "reset-password" :
          id === "back-login-from-register" ? "login" :
            id === "back-login-from-reset-pass" ? "login" :
              "register"
      );
    });
  });

  // Mostrar el formulario correcto sin actualizar de nuevo el historial
  window.addEventListener("popstate", (event) => {
    const formName = event.state?.form || "login";
    toggleForm(formName, false);
  });

});