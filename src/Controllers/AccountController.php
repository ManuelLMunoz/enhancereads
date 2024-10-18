<?php

namespace Src\Controllers;

use Src\Models\Users;
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class AccountController extends Controller
{
    // Se retorna la vista de la cuenta
    public function account()
    {
        return $this->view("account");
    }

    // Se retorna la vista del perfil
    public function profile()
    {
        return $this->view("profile");
    }

    // Se retorna la vista de verificación 2FA para el administrador
    public function admin2FA()
    {
        session_start();
        return $this->view("2FA-verification");
    }

    // ----------------------------
    // Inicio de sesión del usuario
    // ----------------------------
    public function login()
    {
        if (!isset($_POST["user"], $_POST["pass"])) {
            return $this->view("account", ["error" => "Por favor, ingresa usuario y contraseña", "form" => "login"]);
        }

        $user = (new Users())->findUser($_POST["user"]);

        // Se verifica si el usuario existe y la contraseña coincide con la almacenada en BBDD
        if ($user && password_verify($_POST["pass"], $user["password"])) {
            session_start();

            // Usuario común 
            // -------------
            if ($user["role"] == "user") {
                // Se guardan los datos en la sesión y se redirige a la página principal
                $_SESSION["id"] = $user["id"];
                $_SESSION["user"] = $user["user"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["avatar"] = $user["avatar"];
                header("Location: /");
                exit();

                // Usuario admin
                // -------------
            } elseif ($user["role"] == "admin") {

                // Se usa el secreto del usuario o se genera uno
                $secret = $user["2fa_secret"] ?: (new Google2FA())->generateSecretKey();

                // Se actualiza el secreto en la BBDD si no existía
                if (!$user["2fa_secret"]) {
                    (new Users())->update2FASecret($user["id"], $secret);
                }

                // Se guardan los datos en una variable temporal (Para no iniciar sesión antes de validar el 2FA)
                $_SESSION["temp_user"] = [
                    "id" => $user["id"],
                    "user" => $user["user"],
                    "email" => $user["email"],
                    "role" => $user["role"],
                    "avatar" => $user["avatar"],
                    "2fa_secret" => $secret
                ];

                // URL del código QR
                $qrCodeUrl = "otpauth://totp/" . urlencode("Enhancereads:" . $user["email"]) . "?secret={$secret}&issuer=" . urlencode("Enhancereads");

                // Se genera el código QR y se convierte en una imagen PNG en base64
                $qrCodeImage = (new PngWriter())->write(QrCode::create($qrCodeUrl));
                $_SESSION["2fa_qr"] = base64_encode($qrCodeImage->getString());

                // Se redirige a la página 2FA
                header("Location: /2FA-verification");
                exit();
            }
        }

        // En caso de credenciales incorrectas, se retorna un error
        return $this->view("account", ["error" => "Credenciales incorrectas", "form" => "login"]);
    }

    // -----------------------------------------
    // Verificación 2FA con Google Authenticator
    // -----------------------------------------
    public function verify2FA()
    {
        session_start();
        $code = $_POST["2fa_code"];
        $secret = $_SESSION["temp_user"]["2fa_secret"];

        // Si el OTP es correcto
        if ((new Google2FA())->verifyKey($secret, $code)) {

            // Se pasan los datos de la variable temporal a la sesión
            $_SESSION["id"] = $_SESSION["temp_user"]["id"];
            $_SESSION["user"] = $_SESSION["temp_user"]["user"];
            $_SESSION["email"] = $_SESSION["temp_user"]["email"];
            $_SESSION["role"] = $_SESSION["temp_user"]["role"];
            $_SESSION["avatar"] = $_SESSION["temp_user"]["avatar"];
            $_SESSION["2fa_verified"] = true;

            unset($_SESSION["temp_user"]);

            // Se redirige a la página principal
            header("Location: /");
            exit();
        } else {
            // En caso de OTP incorrecto, se retorna un error
            return $this->view("2FA-verification", ["error" => "Código OTP incorrecto"]);
        }
    }

    // -------------------------------------------------
    // Cerrar sesión (Se redirige a la página de inicio)
    // -------------------------------------------------
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /");
        exit();
    }

    // -------------
    // Borrar cuenta
    // -------------
    public function deleteAccount()
    {
        session_start();
        $success = (new Users())->deleteUser($_SESSION["id"]);

        // Respuesta JSON
        header("Content-Type: application/json");
        if ($success) {
            session_unset();
            session_destroy();
            $response = ["success" => true, "message" => "Cuenta borrada con éxito"];
        } else {
            $response = ["success" => false, "message" => "Error al borrar la cuenta"];
        }

        echo json_encode($response);
        exit();
    }

    // ------------------------
    // Procesamiento de los formularios
    // ------------------------
    public function processForm($form)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Se verifica si se ha enviado el email o se redirige a la vista con un error
        if (empty($_POST["email"])) {
            return $this->view($form === "update-profile" ? "profile" : "account", ["error" => "Por favor, completa todos los campos", "form" => $form]);
        }

        // Variables comunes a los formularios
        $user = trim($_POST["user"] ?? null);
        $email = $_POST["email"];
        $pass = $_POST["pass"] ?? null;
        $verifyPass = $_POST["verify_pass"] ?? null;

        $accountManager = new Users();
        $registeredEmail = $accountManager->findUserByEmail($email);

        // Validación de contraseñas
        if (($form === "reset-password" || !empty($pass)) && ($pass !== $verifyPass || strlen($pass) < 8 || !preg_match("/[A-Za-z]/", $pass) || !preg_match("/[0-9]/", $pass))) {
            return $this->view($form === "update-profile" ? "profile" : "account", ["error" => "Contraseña inválida o no coinciden", "form" => $form]);
        }

        // Cifrado de la contraseña
        $cipheredPass = !empty($pass) ? password_hash($pass, PASSWORD_DEFAULT, ["cost" => 12]) : null;

        // Manejo de los formularios
        switch ($form) {
            case "update-profile":
                return $this->updateProfileForm($accountManager, $registeredEmail, $user, $email, $cipheredPass);
            case "register":
                return $this->registerForm($accountManager, $registeredEmail, $user, $email, $cipheredPass);
            case "reset-password":
                return $this->resetPasswordForm($accountManager, $registeredEmail, $email, $cipheredPass);
        }
    }

    // ------------------------------------
    // Formulario para actualizar el perfil
    // ------------------------------------
    private function updateProfileForm($accountManager, $registeredEmail, $user, $email, $cipheredPass)
    {
        // Se obtienen los datos del usuario actual
        $id = $_SESSION["id"];
        $currentUserData = $accountManager->findUserById($id);

        // Se verifica si el email ya está registrado
        if ($registeredEmail && $registeredEmail["id"] !== $id) {
            return $this->view("profile", ["error" => "Email ya registrado"]);
        }

        // Manejo de la subida del avatar 
        // ------------------------------
        $avatarError = null;

        if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === UPLOAD_ERR_OK) {

            $uploadDirectory = __DIR__ . "/../../public/assets/img/avatars/";
            $avatarExtension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
            $allowedExtensions = ["jpg", "jpeg", "png", "gif", "webp"];

            // Se verifica el tamaño y la extensión del archivo (Máximo 200KB)
            if (!in_array($avatarExtension, $allowedExtensions)) {
                $avatarError = "Extensión no válida. Los archivos permitidos son: jpg, jpeg, png, gif y webp";
            } elseif ($_FILES["avatar"]["size"] > 200 * 1024) {
                $avatarError = "El tamaño máximo permitido son 200KB";
            } else {
                // Ruta y nombre del avatar
                $avataName = "avatar_user_" . $id . ".webp";
                $destPath = $uploadDirectory . $avataName;

                // Si existe otro avatar del mismo usuario se elimina
                if (!empty($currentUserData["avatar"]) && $currentUserData["avatar"] !== "default.svg") {
                    unlink($uploadDirectory . $currentUserData["avatar"]);
                }

                // Se mueve el avatar seleccionado al servidor
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $destPath)) {
                    $avatar = $avataName;
                } else {
                    $avatarError = "Error al subir el archivo. Pruebe de nuevo";
                }
            }
            // En caso de no subir avatar, se mantiene el actual
        } else {
            $avatar = $currentUserData["avatar"];
        }

        // Guardado de los nuevos datos
        $updates = array_filter([
            "user" => $user && $user !== $currentUserData["user"] ? $user : null,
            "email" => $email && $email !== $currentUserData["email"] ? $email : null,
            "pass" => $cipheredPass,
            "avatar" => $avatar ?? $currentUserData["avatar"]
        ]);

        // Si no hay actualizaciones de los datos ni errores en el avatar, se redirige sin cambios
        if (empty($updates) && !$avatarError) return $this->view("profile");

        // En caso de error al subir el avatar, se muestra un error
        if ($avatarError) {
            return $this->view("profile", ["error" => $avatarError, "form" => "update-profile"]);
        }

        // Se actualizan los datos del usuario
        if ($accountManager->updateUser(
            $id,
            $updates["user"] ?? $currentUserData["user"],
            $updates["email"] ?? $currentUserData["email"],
            // Si no se indica contraseña, se mantiene la actual
            $updates["pass"] ?? null,
            $updates["avatar"] ?? $currentUserData["avatar"]
        )) {
            $_SESSION = array_merge($_SESSION, array_filter($updates));
            return $this->view("profile", ["success" => "Datos actualizados"]);
        } else {
            return $this->view("profile", ["error" => "Error al actualizar los datos"]);
        }
    }

    // ----------------------
    // Formulario de registro
    // ----------------------
    private function registerForm($accountManager, $registeredEmail, $user, $email, $cipheredPass)
    {
        // Se verifica si el email ya está registrado
        if ($registeredEmail) {
            return $this->view("account", ["error" => "Email ya registrado", "form" => "register"]);
        }

        // Insertar el nuevo usuario (El rol siempre será "user")
        if ($accountManager->insertUser($user, $email, $cipheredPass, "user")) {
            // Se redirige a la vista de login y se muestra el mensaje de cambio exitoso
            $_SESSION["success"] = "Usuario registrado";
            unset($_SESSION["error"]);
            return $this->view("account", ["form" => "login"]);
        } else {
            return $this->view("account", ["error" => "Error al registrar usuario", "form" => "register"]);
        }
    }

    // --------------------------------------
    // Formulario para restablecer contraseña
    // --------------------------------------
    private function resetPasswordForm($accountManager, $registeredEmail, $email, $cipheredPass)
    {
        // Si el email no está registrado o si el usuario tiene el rol "google", no se permite cambiar la contraseña
        if (!$registeredEmail || $accountManager->getUserRole($email) === "google") {
            return $this->view("account", ["error" => "Email inválido", "form" => "reset-password"]);
        }
        // Actualizar la contraseña del usuario en la BBDD
        if ($accountManager->updatePassword($email, $cipheredPass)) {
            // Se redirige a la vista de login y se muestra el mensaje de cambio exitoso
            $_SESSION["success"] = "Contraseña actualizada";
            unset($_SESSION["error"]);
            return $this->view("account", ["form" => "login"]);
        } else {
            return $this->view("account", ["error" => "Error al actualizar la contraseña", "form" => "reset-password"]);
        }
    }

    public function register()
    {
        return $this->processForm("register");
    }
    public function resetPassword()
    {
        return $this->processForm("reset-password");
    }
    public function updateProfile()
    {
        return $this->processForm("update-profile");
    }
}
