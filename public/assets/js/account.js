document.addEventListener("DOMContentLoaded", () => {

  // -------------------------------------------------------
  // Cargar el formulario seleccionado o "login" por defecto
  // -------------------------------------------------------
  const activeForm = document.querySelector("#main").dataset.activeForm || "login";

  // Alternar la visibilidad de los formularios
  const toggleForm = (formName, updateHistory = true) => {
    const forms = ["login", "register", "reset-password"];
    forms.forEach(form => {
      const element = document.getElementById(`${form}-form`);
      if (element) {
        element.style.display = form === formName ? "block" : "none";
      }
    });

    // Actualizar la URL sin recargar la página
    const historyMethod = updateHistory ? "pushState" : "replaceState";
    history[historyMethod]({ form: formName }, "", `/${formName}`);
  }

  // Mostrar el formulario correcto cuando hay un cambio en el historial sin actualizarlo
  toggleForm(activeForm, false);
  window.onpopstate = ({ state }) => toggleForm(state?.form || "login", false);

  // ---------------------------------------------------
  // Manejar los clics en los enlaces de los formularios
  // ---------------------------------------------------

  // Objeto con los enlaces y sus respectivos formularios
  const links = {
    "forgot-password-link": "reset-password",
    "register-link": "register",
    "back-login-from-register": "login",
    "back-login-from-reset-pass": "login"
  };

  // Iterar y agregar los eventos de clic a los enlaces
  Object.entries(links).forEach(([id, form]) => {
    document.getElementById(id)?.addEventListener("click", (event) => {
      event.preventDefault();
      toggleForm(form);
    });
  });

});