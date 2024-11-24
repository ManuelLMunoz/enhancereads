document.addEventListener("DOMContentLoaded", () => {

    // ---------------------------------------------------------------------
    // Verificar que las contraseñas coincidan antes de mandar el formulario
    // ---------------------------------------------------------------------
    const verifyPass = (event, password, verifyPassword) => {
        const pass = document.getElementById(password).value;
        const verifyPass = document.getElementById(verifyPassword).value;

        if (pass !== verifyPass) {
            event.preventDefault();
            showToast("Las contraseñas no coinciden", "error");
        }
    };

    // Validación de formularios al hacer submit
    const handlePassVerification = (formId, passId, verifyPassId) => {
        const form = document.getElementById(formId);
        if (form) form.onsubmit = event => verifyPass(event, passId, verifyPassId);
    }

    handlePassVerification("register-form", "register-pass", "register-verify-pass");
    handlePassVerification("reset-password-form", "reset-pass", "reset-verify-pass");
    handlePassVerification("update-form", "pass", "verify-pass");

});

// ----------------------------------------
// Boton para mostrar/ocultar la contraseña
// ----------------------------------------
document.querySelectorAll(".show-pass").forEach(toggle => {
    const input = toggle.previousElementSibling;
    toggle.onclick = () => {
        input.type = input.type === "password" ? "text" : "password";
        toggle.classList.toggle("fa-eye");
        toggle.classList.toggle("fa-eye-slash");
    };
});
