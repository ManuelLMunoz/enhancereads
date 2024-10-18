// --------------------------------------
// Borrado de cuentas de usuario dinámico
// --------------------------------------
document.addEventListener("click", async (event) => {
    const deleteButton = event.target.closest("#delete-account");
    if (!deleteButton) return;
    event.preventDefault();

    // Mostrar modal de confirmación
    const modal = document.getElementById("delete-modal");
    modal.classList.remove("hidden");

    // Ocultar modal al cancelar
    document.getElementById("cancel-delete").onclick = () => modal.classList.add("hidden");

    // Confirmar borrado
    document.getElementById("confirm-delete").onclick = async () => {
        try {
            // Solicitud POST al servidor para eliminar la cuenta
            const response = await fetch("/delete-account", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: deleteButton.dataset.userId })
            });
            const result = await response.json();

            // Si el borrado es exitoso, se guarda el mensaje en localStorage y se redirige al usuario
            result.success
                ? (localStorage.setItem("deleteSuccess", result.message), window.location.replace("/"))
                : showToast(result.message, "error");
        } catch {
            showToast("Error al procesar la solicitud.", "error");
        } finally { // Ocultar el modal al terminar la solicitud
            modal.classList.add("hidden");
        }
    };
});
// ------------------------------------
// Crear y mostrar los mensajes "toast"
// ------------------------------------
const showToast = (message, type) => {
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.innerText = message;
    document.body.appendChild(toast);

    // Mostrar el toast y ocultarlo tras 3 segundos
    setTimeout(() => toast.style.opacity = 1, 10);
    setTimeout(() => { toast.style.opacity = 0; setTimeout(() => toast.remove(), 500); }, 3000);
};

// ------------------------------------------------------
// Alternar la visibilidad para restablecer la contraseña
// ------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("change-password-button").onclick = (event) => {
        event.preventDefault();
        const fields = document.getElementById("password-fields");
        fields.style.display = fields.style.display === "none" ? "block" : "none";
    };
});