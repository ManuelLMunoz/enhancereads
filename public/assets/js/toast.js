// ------------------------------------
// Crear y mostrar los mensajes "toast"
// ------------------------------------
const showToast = (message, type) => {
    // Iconos según el tipo de toast
    const icons = {
        success: "fas fa-check-circle",
        info: "fas fa-info-circle",
        warning: "fas fa-exclamation-circle",
        error: "fas fa-times-circle",
    };

    // Generar y agregar el toast al body
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="${icons[type] || ""}"></i> ${message}`;
    document.body.appendChild(toast);

    // Mostrar el toast y ocultarlo después de 3 segundos
    setTimeout(() => toast.style.opacity = 1, 10);
    setTimeout(() => {
        toast.style.opacity = 0;
        setTimeout(() => toast.remove(), 500);
    }, 3000);
};
