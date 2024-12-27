document.addEventListener("DOMContentLoaded", () => {

    // ------------------------------------------------------------
    // Mostrar el formulario seleccionado y actualizar el historial
    // ------------------------------------------------------------
    const forms = ["add-book", "add-author", "add-genre", "add-publisher"];

    // Cargar el formulario seleccionado y cambiar el historial
    const updateForm = formName => {
        forms.forEach(form => document.getElementById(form)?.classList.toggle("hidden", form !== formName));
        document.querySelectorAll(".form-option").forEach(opt => opt.classList.toggle("active", opt.dataset.form === formName));
        window.history.replaceState({ form: formName }, "", `/${formName}`);
    };

    // Manejar los clics en las opciones de formulario
    document.querySelectorAll(".form-option").forEach(option =>
        option.addEventListener("click", () => updateForm(option.dataset.form))
    );

    // Manejar el retroceso del navegador para mostrar el formulario correspondiente
    window.onpopstate = event => updateForm(event.state?.form || "books");

    // Inicializar el formulario basado en la URL
    updateForm(window.location.pathname.substring(1) || "books");
});