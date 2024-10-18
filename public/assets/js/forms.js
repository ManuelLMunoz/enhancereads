document.addEventListener("DOMContentLoaded", () => {

    // ---------------------------------------------------------------------
    // Verificar que las contraseñas coincidan antes de mandar el formulario
    // ---------------------------------------------------------------------
    const verifyPass = (event, password, verifyPassword) => {
        const pass = document.getElementById(password).value;
        const verifyPass = document.getElementById(verifyPassword).value;

        // Eliminar cualquier mensaje de error previo
        event.target.querySelector(".error")?.remove();

        // Si las contraseñas no coinciden, se evita el envío del formulario y se muestra un error
        if (pass !== verifyPass) {
            event.preventDefault();
            const errorDiv = document.createElement("div");
            errorDiv.className = "message error";
            errorDiv.textContent = "Las contraseñas no coinciden";
            // Insertar el div antes del botón de enviar
            const submitButton = event.target.querySelector("button[type='submit']");
            submitButton.parentNode.insertBefore(errorDiv, submitButton);
        }
    };

    // Eventos para validar las contraseñas en los formularios de la página "account"
    if (window.location.pathname === "/account") {
        document.getElementById("register-form").addEventListener("submit", event => verifyPass(event, "register-pass", "register-verify-pass"));
        document.getElementById("reset-password-form").addEventListener("submit", event => verifyPass(event, "reset-pass", "reset-verify-pass"));
    }
    // Eventos para validar las contraseñas en los formularios de la página "profile"
    if (window.location.pathname === "/profile") {
        document.getElementById("update-form").addEventListener("submit", event => verifyPass(event, "pass", "verify-pass"));
    }

});