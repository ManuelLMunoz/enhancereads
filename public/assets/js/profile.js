// --------------------------------------
// Borrado de cuentas de usuario dinámico
// --------------------------------------
document.addEventListener("click", async (event) => {
    const deleteButton = event.target.closest("#delete-account");
    if (!deleteButton) return;
    event.preventDefault();

    const modal = document.getElementById("delete-modal");
    modal.classList.remove("hidden");

    document.getElementById("cancel-delete").onclick = () => modal.classList.add("hidden");

    document.getElementById("confirm-delete").onclick = async () => {
        try {
            const response = await fetch("/delete-account", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: deleteButton.dataset.userId })
            });
            const result = await response.json();

            // Si la respuesta es exitosa, guardar el mensaje en localStorage y redirigir a la página de inicio
            result.success
                ? (localStorage.setItem("deleteSuccess", result.message), window.location.replace("/"))
                : showToast(result.message, "error");
        } catch {
            showToast("Error al procesar la solicitud.", "error");
        } finally {
            modal.classList.add("hidden");
        }
    };
});

// --------------------------------------------------
// Alternar la visibilidad para cambiar la contraseña
// --------------------------------------------------
document.getElementById("change-password-button").onclick = (event) => {
    event.preventDefault();
    const fields = document.getElementById("password-fields");
    fields.style.display = fields.style.display === "none" ? "block" : "none";
};
