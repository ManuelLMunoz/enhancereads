const showToast = (message, type) => {
    const icons = {
        success: "check",
        info: "info",
        warning: "exclamation",
        error: "times"
    };

    // Generar y agregar el toast al final del body
    document.body.insertAdjacentHTML("beforeend", `<div class="toast ${type}"> <i class="fas fa-${icons[type]}-circle"></i> ${message} </div>`);

    // Mostrar el toast y ocultarlo después de 3 segundos
    const toast = document.querySelector(".toast:last-child");
    setTimeout(() => toast.style.opacity = 1, 10);
    setTimeout(() => {
        toast.style.opacity = 0;
        setTimeout(() => toast.remove(), 500);
    }, 3000);
};