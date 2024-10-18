<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuración base -->
    <?php require_once("components/head.php"); ?>

    <!-- Configuración concreta -->
    <title>Verificación OTP</title>
    <link rel="stylesheet" href="assets/css/forms.css">
</head>

<body>

    <section id="main">

        <!-- Logo -->
        <h1><img id="logotype" src="assets/img/logo.webp" alt="Logo de la marca"></h1><br>

        <!-- Formulario para validar el OTP -->
        <div class="form">
            <h1>Verificación OTP</h1>

            <form action="/verify-2FA" class="OTP" method="POST">
                <p>Escanea el código QR con <br> Google Authenticator</p>

                <!-- Imagen del código QR codificada en base64 -->
                <img src="data:image/png;base64,<?php echo $_SESSION["2fa_qr"]; ?>" alt="Código QR para Google Authenticator">

                <!-- OTP de 6 dígitos (Se obtendrá de Google Authenticator) -->
                <label for="2fa-code">Indica el Código OTP
                    <input type="text" id="2fa-code" name="2fa_code" maxlength="6" pattern="\d{6}" inputmode="numeric" required autofocus>
                </label>

                <!-- Mensaje de error en caso de OTP incorrecto -->
                <?php if (isset($error)) : ?>
                    <div class="message error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <button type="submit">Verificar</button>
            </form>
        </div>

</html>