<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="assets/css/navbar.css">
    <script src="assets/js/navbar.js" type="module"></script>
</head>

<body>

    <nav class="menu">

        <!-- Icono para desplegar el menú en dispositivos pequeños -->
        <i id="menu-icon" class="fas fa-bars" title="Menú"></i>

        <!-- Vacío para alinear las otras opciones -->
        <ul class="left"></ul>

        <ul class="center">
            <li><a href="books/">Libros</a></li>
            <li><a href="posts/">Posts</a></li>
            <li><a href="#about-us">Sobre Nosotros</a></li>
        </ul>

        <ul class="right">

            <!-- Modal de FAQ -->
            <li title="Ayuda"><a href="javascript:void(0);" id="open-modal" aria-label="Preguntas frecuentes"> <i class="fa-regular fa-circle-question"></i></a></li>

            <div id="faq-modal" class="help-modal">
                <div class="help-modal-content">
                    <span class="close" title="Cerrar">&times;</span>
                    <h2>Preguntas Frecuentes</h2>
                    <div class="accordion">
                        <details class="accordion-item">
                            <summary class="accordion-header">¿Es gratis crear una cuenta?</summary>
                            <div class="accordion-body">Sí, la creación de una cuenta es gratuita.</div>
                        </details>
                        <details class="accordion-item">
                            <summary class="accordion-header">¿Necesito estar logueado para publicar posts?</summary>
                            <div class="accordion-body">Sí, es necesario iniciar sesión para publicar posts, dar likes y realizar comentarios.</div>
                        </details>
                        <details class="accordion-item">
                            <summary class="accordion-header">¿Se puede chatear entre usuarios?</summary>
                            <div class="accordion-body">Por el momento, la web no cuenta con esa funcionalidad, pero estamos trabajando en ello 😉.</div>
                        </details>
                    </div>
                </div>
            </div>

            <!-- Apartado de notificaciones (Solo disponible si el usuario ha iniciado sesión) -->
            <?php
            if (!isset($_SESSION)) {
                session_start();
            }
            if (isset($_SESSION["user"])): ?>
                <li id="notifications" class="notifications">
                    <a href="javascript:void(0);">
                        <i title="Notificaciones" class="fa-regular fa-bell"></i>
                        <span id="notification-count" class="notification-count"></span>
                    </a>
                </li>
            <?php endif; ?>

            <div id="notification-modal" class="notification-modal">
                <div class="modal-header">
                    <p>Notificaciones</p>
                    <span id="mark-all-read" title="Marcar todo como leído"><i class="fa-solid fa-check"></i></span>
                </div>
                <div id="notification-list" class="notification-list">
                    <!-- Las notificaciones se cargan dinámicamente -->
                </div>
            </div>

            <!-- Panel del usuario -->
            <li class="user-menu">
                <?php
                // Instancia del controlador de Google para cargar los datos del usuario logado
                (new \Src\Controllers\GoogleController())->callback();

                // Verificar si existe la sesión del usuario y obtener sus datos
                if (isset($_SESSION["user"])) {
                    $isGoogleLogin = $_SESSION["login_source"] ?? "" === "google";
                    $userInfo = $isGoogleLogin ? $_SESSION["user_info"] : null;

                ?>
                    <a href="#" class="dropbtn">
                        <div class="account-info"> Mi cuenta
                            <img src="assets/img/avatars/<?= $_SESSION["avatar"] ?>" alt="Foto de perfil" class="profile-pic">
                        </div>
                    </a>

                    <div class="user-menu-content">
                        <a><?= $isGoogleLogin ? $userInfo["full_name"] : $_SESSION["user"]; ?></a>
                        <?php if ($isGoogleLogin) : ?>
                            <a href="/google-logout"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Salir </a>
                        <?php else : ?>
                            <a href="../../profile"> <i class="fa-solid fa-address-card"></i> Perfil</a>
                            <a href="/logout"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Salir </a>
                        <?php endif; ?>

                    </div>

                <?php
                } else {
                ?>
                    <!-- Si no hay sesión activa, permitir iniciar sesión -->
                    <a href="account">Acceder</a>
                <?php } ?>
            </li>
        </ul>
    </nav>

</body>

</html>