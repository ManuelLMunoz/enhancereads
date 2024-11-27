const themeIcon = document.getElementById("theme-icon");

const setTheme = (isDark) => {
  document.body.setAttribute("data-theme", isDark ? "dark" : "");
  themeIcon?.classList.toggle("fa-sun", isDark);
  themeIcon?.classList.toggle("fa-moon", !isDark);
  // Modificar el scroll
  document.documentElement.classList.toggle("dark-mode-scroll", isDark);
  localStorage.setItem("darkMode", isDark);
};

// Inicializar el tema según la preferencia del usuario o del sistema
setTheme(localStorage.getItem("darkMode") === "true" ||
  (!localStorage.getItem("darkMode") && window.matchMedia("(prefers-color-scheme: dark)").matches)
);

// Alternar el tema al hacer clic en el icono
document.getElementById("theme-toggle")?.addEventListener("click", (event) => {
  event.preventDefault();
  setTheme(document.body.getAttribute("data-theme") !== "dark");
});
