<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>

    <footer>
        <div class="footer-container">

            <div class="footer-column">
                <p>Contacto</p>
                <ul>
                    <li>Teléfono: <br> <a href="tel:+34111111111">+34111111111</a></li>
                    <li>Correo: <br> <a href="mailto:Enhancereads@gmail.com">Enhancereads@gmail.com</a></li>
                    <li> Dirección: <br> <a href="https://maps.app.goo.gl/oTPNABETLVFUi7r5A" target="_blank" rel="noopener noreferrer">
                            C. Institutos, 4 <br>28923 Alcorcón, Madrid</a>
                    </li>
                </ul>
            </div>

            <div class="footer-column">
                <p>Legal</p>
                <ul>
                    <li><a href="/terms-of-use">Términos y Condiciones</a></li>
                    <li><a href="/privacy-policy">Política de Privacidad</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <p>Síguenos</p>
                <nav id="social">
                    <ul>
                        <li><a href="https://x.com/Enhancereads" target="_blank" rel="noopener noreferrer"><img class="social-media"
                                    src="assets/img/1-x.svg" alt="Twitter" title="X" loading="lazy"></a></li>
                        <li><a href="https://instagram.com/Enhancereads" target="_blank" rel="noopener noreferrer"><img class="social-media"
                                    src="assets/img/2-instagram.svg" alt="Instagram" title="Instagram" loading="lazy"></a>
                        </li>
                        <li><a href="https://facebook.com/Enhancereads" target="_blank" rel="noopener noreferrer"><img class="social-media"
                                    src="assets/img/3-facebook.svg" alt="Facebook" title="Facebook" loading="lazy"></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="footer-bottom">
            <p>Enhancereads &copy; <?php echo date("Y"); ?> - Licencia <a href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank" rel="noopener noreferrer">CC BY-SA 4.0</a></p>
            <p>Diseñado por <a href="https://www.linkedin.com/in/manuelluismunozsanchez/" target="_blank" rel="noopener noreferrer">Manuel Luis Muñoz Sánchez</a></p>
        </div>

    </footer>

    <!-- Botón para volver al inicio de la página (Aparece al hacer scroll) -->
    <button id="up">
        <img id="arrow" src="assets/img/arrow.svg" alt="Flecha para ir al inicio" title="Ir arriba">
    </button>

    <script>
        const topButton = document.getElementById("up");
        window.addEventListener("scroll", () => {
            topButton.style.display = window.scrollY > 500 ? "block" : "none";
        });
        topButton.addEventListener("click", () => window.scrollTo(0, 0));
    </script>

</body>

</html>