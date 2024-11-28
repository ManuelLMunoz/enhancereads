<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION["user"])) {
  header("Location:account");
  exit();
}

$allowed_roles = ["user", "admin"];
if (!in_array($_SESSION["role"], $allowed_roles)) {
  header("Location: /");
  exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php require_once(__DIR__ . "/../components/head.php"); ?>
  <title>Mi cuenta</title>
  <link rel="stylesheet" href="assets/css/forms.css">
  <script src="assets/js/forms.js" type="module"></script>
  <script src="assets/js/profile.js" type="module"></script>
</head>

<body>

  <section id="main">

    <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logo de la marca"></a></h1><br>

    <!-- ------------------------------------------------ -->
    <!-- Formulario para actualizar los datos del usuario -->
    <!-- ------------------------------------------------ -->
    <form class="form" id="update-form" action="/update-profile" method="POST" enctype="multipart/form-data">

      <h1>Actualizar datos</h1>

      <label for="user"> Usuario
        <i class="fas fa-user icon"></i>
        <input type="text" id="user" name="user" value="<?php echo $_SESSION["user"]; ?>" maxlength="50" required aria-label="Usuario" />
      </label>

      <label for="email"> Email
        <i class="fas fa-envelope icon"></i>
        <input type="email" id="email" name="email" value="<?php echo $_SESSION["email"]; ?>" maxlength="100" required aria-label="Correo" autocomplete="current-email" />
      </label>

      <label for="avatar"> Avatar
        <i class="fas fa-image icon"></i>
        <input type="file" id="avatar" name="avatar" aria-label="Avatar" accept="image/*" />
      </label>

      <a href="#" id="change-password-button">Cambiar contraseña</a>

      <!-- Campos para la contraseña, inicialmente ocultos -->
      <div id="password-fields" style="display: none; margin-top: 20px;">

        <label for="pass"> Contraseña
          <i class="fas fa-lock icon"></i>
          <input type="password" id="pass" name="pass" aria-label="Contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="new-password" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <label for="verify-pass"> Validar contraseña
          <i class="fas fa-lock icon"></i>
          <input type="password" id="verify-pass" name="verify_pass" aria-label="Validar contraseña" pattern="(?=.*\d)(?=.*[a-zA-Z]).{8,}"
            title="La contraseña debe tener al menos 8 caracteres e incluir al menos un número." autocomplete="new-password" />
          <i class="fas fa-eye icon show-pass"></i>
        </label>

        <p>Nota: Si no se indica contraseña, se mantiene la actual</p>

      </div>

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

      <button type="submit" style="margin-top: 15px;">Aceptar</button>
      <button type="button" onclick="window.location.href='/'">Regresar</button>

      <!-- Borrado de la cuenta (Solo para usuarios) -->
      <?php if ($_SESSION["role"] !== "admin") : ?>
        <p>Para borrar la cuenta, pulse <a href="#" id="delete-account" data-user-id="<?= $_SESSION["id"] ?>">aquí</a></p>
      <?php endif; ?>

    </form>

    <!-- Modal de confirmación del borrado de la cuenta -->
    <div id="delete-modal" class="modal hidden">
      <div class="delete-content content">
        <p id="delete-message">¿Está seguro de borrar esta cuenta?</p>
        <button id="confirm-delete" class="confirm-button">Eliminar</button>
        <button id="cancel-delete" class="cancel-button">Cancelar</button>
      </div>
    </div>

  </section>

</body>

</html>