<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">

            <!-- Datos de contacto -->
            <div class="footer-column">
                <p>Contacto</p>
                <ul>
                    <li>Teléfono: <br> <a href="tel:+34111111111">+34111111111</a></li>
                    <li>Correo: <br> <a href="mailto:Enhancereads@gmail.com">Enhancereads@gmail.com</a></li>
                    <li> Dirección: <br> <a href="https://maps.app.goo.gl/oTPNABETLVFUi7r5A" target="_blank">
                            C. Institutos, 4 <br>28923
                            Alcorcón, Madrid</a>
                    </li>
                </ul>
            </div>

            <!-- Apartado legal de la web -->
            <div class="footer-column">
                <p>Legal</p>
                <ul>
                    <li><a href="/terms-of-use">Términos y Condiciones</a></li>
                    <li><a href="/privacy-policy">Política de Privacidad</a></li>
                </ul>
            </div>

            <!-- Redes sociales -->
            <div class="footer-column">
                <p>Síguenos</p>
                <nav id="social">
                    <ul>
                        <li><a href="https://x.com/Enhancereads" target="_blank"><img class="redes"
                                    src="assets/img/1-x.svg" alt="Twitter" title="X"></a></li>
                        <li><a href="https://instagram.com/Enhancereads" target="_blank"><img class="redes"
                                    src="assets/img/2-instagram.svg" alt="Instagram" title="Instagram"></a>
                        </li>
                        <li><a href="https://facebook.com/Enhancereads" target="_blank"><img class="redes"
                                    src="assets/img/3-facebook.svg" alt="Facebook" title="Facebook"></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Derechos de autor -->
        <div class="footer-bottom">
            <p>Enhancereads<br>Copyright © All rights reserved <?php echo date("Y"); ?> </p>
        </div>

    </footer>

    <!-- Botón para volver al inicio de la página (Aparece al hacer scroll) -->
    <button id="up">
        <img id="arrow" src="assets/img/arrow.svg" alt="Flecha para ir al inicio" title="Ir arriba">
    </button>

    <script>
        const topButton = document.getElementById("up");
        window.onscroll = () => topButton.style.display = scrollY > 500 ? "block" : "none";
        topButton.onclick = () => scrollTo(0, 0);
    </script>

</body>

</html>