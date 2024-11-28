<!DOCTYPE html>
<html lang="es">

<head>
  <?php require_once(__DIR__ . "/../components/head.php"); ?>
  <title>Acceso</title>
  <link rel="stylesheet" href="assets/css/forms.css">
  <script src="assets/js/account.js" type="module"></script>
  <script src="assets/js/forms.js" type="module"></script>
</head>

<body>

  <section id="main" data-active-form="<?php echo isset($form) ? htmlspecialchars($form) : "login"; ?>">

    <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1><br>

    <div class="form">

      <!-- ------------------------------------------ -->
      <!-- Formulario de login (Mostrado por defecto) -->
      <!-- ------------------------------------------ -->
      <form id="login-form" action="/login" method="POST">

        <h1>Iniciar sesión</h1>

        <label for="login-user"> Usuario / Email
          <i class="fas fa-user icon"></i>
          <input id="login-user" type="text" name="user" maxlength="100" required aria-label="Usuario o Email" />
        </label>

        <label for="login-pass">Contraseña
          <i class="fas fa-lock icon"></i>
          <input id="login-pass" type="password" name="pass" required aria-label="Contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="off" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <p><a id="forgot-password-link" href="#">¿Olvidaste la contraseña?</a></p>

        <div class="input-container"><button type="submit">Acceder</button></div>
        <p>¿Sin registrar? <a id="register-link" href="#">Crea una cuenta</a></p>
        <p>o</p>
        <div class="google-button-container">
          <a href="/google-login" class="google-button">
            <img src="assets/img/google-logo.svg" alt="Google icon" class="google-icon"> Inicia sesión con Google
          </a>
        </div>

      </form>

      <!-- ---------------------- -->
      <!-- Formulario de registro -->
      <!-- ---------------------- -->
      <form id="register-form" class="hidden" action="/register" method="POST">

        <h1>Crear Cuenta</h1>

        <label for="register-user"> Usuario
          <i class="fas fa-user icon"></i>
          <input id="register-user" type="text" name="user" maxlength="50" required aria-label="Usuario" />
        </label>

        <label for="register-email"> Email
          <i class="fas fa-envelope icon"></i>
          <input id="register-email" type="email" name="email" maxlength="100" required aria-label="Email" autocomplete="email" />
        </label>

        <label for="register-pass"> Contraseña
          <i class="fas fa-lock icon"></i>
          <input id="register-pass" type="password" name="pass" required aria-label="Contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="new-password" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <label for="register-verify-pass"> Validar contraseña
          <i class="fas fa-lock icon"></i>
          <input id="register-verify-pass" type="password" name="verify_pass" aria-label="Validar contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="new-password" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <button type="submit">Crear Cuenta</button>
        <p>¿Ya tienes una cuenta? <a id="back-login-from-register" href="#">Acceder</a></p>

      </form>

      <!-- ----------------------------------------- -->
      <!-- Formulario para restablecer la contraseña -->
      <!-- ----------------------------------------- -->
      <form id="reset-password-form" class="hidden" action="/reset-password" method="POST">

        <h1>Restablecer contraseña</h1>

        <label for="email"> Email
          <i class="fas fa-envelope icon"></i>
          <input id="email" type="email" name="email" required aria-label="Email" autocomplete="email" />
        </label>

        <label for="reset-pass"> Contraseña
          <i class="fas fa-lock icon"></i>
          <input id="reset-pass" type="password" name="pass" required aria-label="Contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="new-password" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <label for="reset-verify-pass"> Validar contraseña
          <i class="fas fa-lock icon"></i>
          <input id="reset-verify-pass" type="password" name="verify_pass" required aria-label="Validar contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="new-password" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <div class="input-container"><button type="submit">Aceptar</button></div>
        <p>¿Ya tienes una cuenta? <a id="back-login-from-reset-pass" href="#">Acceder</a></p>

      </form>

    </div>

  </section>

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

</body>

</html>