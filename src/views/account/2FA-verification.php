<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(__DIR__ . "/../components/head.php"); ?>
    <title>Verificación OTP</title>
    <link rel="stylesheet" href="assets/css/forms.css">
</head>

<body>

    <section id="main">

        <h1><img id="logotype" src="assets/img/logo.webp" alt="Logo de la marca"></h1><br>

        <div class="form">
            <h1>Verificación OTP</h1>

            <form action="/verify-2FA" class="OTP" method="POST">
                <p>Escanea el código QR con <br> Google Authenticator</p>

                <!-- Imagen del código QR codificada en base64 -->
                <img src="data:image/png;base64,<?php echo $_SESSION["2fa_qr"]; ?>" alt="Código QR para Google Authenticator">

                <!-- Código OTP de 6 dígitos (Se obtendrá de Google Authenticator) -->
                <label for="2fa-code">Indica el Código OTP
                    <input type="text" id="2fa-code" name="2fa_code" maxlength="6" pattern="\d{6}" inputmode="numeric" required autofocus>
                </label>

                <!-- Mostrar mensajes toast al usuario según el tipo indicado en el controlador -->
                <script>
                    <?php
                    $types = ["error", "success", "warning", "info"];
                    foreach ($types as $type) {
                        if (!empty($$type)) {
                            echo "showToast(" . json_encode($$type) . ", \"$type\");";
                            break;
                        }
                    }
                    ?>
                </script>

                <button type="submit">Verificar</button>
            </form>
        </div>

    </section>

</html>