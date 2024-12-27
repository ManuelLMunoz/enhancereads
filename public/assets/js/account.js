document.addEventListener("DOMContentLoaded", () => {

  // ----------------------------------
  // Mostrar el formulario seleccionado
  // ----------------------------------
  const showForm = (formName, updateHistory = false) => {
    ["login", "register", "reset-password"].forEach(form => {
      const element = document.getElementById(`${form}-form`);
      if (element) {
        element.style.display = form === formName ? "block" : "none";
      }
    });

    // Actualizar la URL y reemplazarla en el historial en caso necesario
    history[updateHistory ? "pushState" : "replaceState"]({ form: formName }, "", `/${formName}`);
  }

  // Mostrar el formulario "login" por defecto
  showForm("login");
  // Cambiar de formulario al cambiar la URL
  window.addEventListener("popstate", ({ state }) => showForm(state?.form || "login"));

  // ---------------------------------------------------
  // Manejar los clics en los enlaces de los formularios
  // ---------------------------------------------------
  const links = {
    "forgot-password-link": "reset-password",
    "register-link": "register",
    "back-login-from-register": "login",
    "back-login-from-reset-pass": "login"
  };

  // Agregar los eventos de clic a los enlaces
  Object.entries(links).forEach(([id, form]) => {
    document.getElementById(id)?.addEventListener("click", (event) => {
      event.preventDefault();
      showForm(form, true); // Cambiar el formulario y actualizar el historial
    });
  });

});