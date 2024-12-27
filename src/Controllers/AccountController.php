<?php

namespace Src\Controllers;

use Src\Models\Users;
use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class AccountController extends Controller
{
    public function account()
    {
        return $this->view("account/account");
    }

    public function profile()
    {
        return $this->view("account/profile");
    }

    public function admin2FA()
    {
        session_start();
        return $this->view("account/2FA-verification");
    }

    // ----------------------------
    // Inicio de sesión del usuario
    // ----------------------------
    public function login()
    {
        if (!isset($_POST["user"], $_POST["pass"])) {
            return $this->view("account/account", ["error" => "Por favor, ingresa usuario y contraseña", "form" => "login"]);
        }

        $user = (new Users())->findUser(htmlspecialchars($_POST["user"]));

        // Verificar si el usuario existe y la contraseña coincide con la almacenada en BBDD
        if ($user && password_verify($_POST["pass"], $user["password"])) {
            session_start();

            // Usuario común
            // -------------
            if ($user["role"] == "user") {
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

                // Usar el secreto del usuario o generar y guardar uno si no existe
                $secret = $user["2fa_secret"] ?: (new Google2FA())->generateSecretKey();
                if (!$user["2fa_secret"]) {
                    (new Users())->update2FASecret($user["id"], $secret);
                }

                // Guardar los datos en una variable temporal para no iniciar sesión antes de validar el 2FA
                $_SESSION["temp_user"] = [
                    "id" => $user["id"],
                    "user" => $user["user"],
                    "email" => $user["email"],
                    "role" => $user["role"],
                    "avatar" => $user["avatar"],
                    "2fa_secret" => $secret
                ];

                // Generar el código QR de Google Authenticator y convertirlo en una imagen PNG en base64
                $qrCodeUrl = "otpauth://totp/" . urlencode("Enhancereads:" . $user["email"]) . "?secret={$secret}&issuer=" . urlencode("Enhancereads");
                $qrCodeImage = (new PngWriter())->write(QrCode::create($qrCodeUrl));
                $_SESSION["2fa_qr"] = base64_encode($qrCodeImage->getString());

                header("Location: /2FA-verification");
                exit();
            }
        }

        return $this->view("account/account", ["error" => "Las credenciales son incorrectas", "form" => "login"]);
    }

    // -----------------------------------------
    // Verificación 2FA con Google Authenticator
    // -----------------------------------------
    public function verify2FA()
    {
        session_start();

        if ((new Google2FA())->verifyKey($_SESSION["temp_user"]["2fa_secret"], $_POST["2fa_code"])) {

            // Pasar los datos a la sesión principal
            $_SESSION["id"] = $_SESSION["temp_user"]["id"];
            $_SESSION["user"] = $_SESSION["temp_user"]["user"];
            $_SESSION["email"] = $_SESSION["temp_user"]["email"];
            $_SESSION["role"] = $_SESSION["temp_user"]["role"];
            $_SESSION["avatar"] = $_SESSION["temp_user"]["avatar"];
            $_SESSION["2fa_verified"] = true;

            // Eliminar la sesión temporal y redirigir a la página principal
            unset($_SESSION["temp_user"]);
            header("Location: /");
            exit();
        } else {
            return $this->view("account/2FA-verification", ["error" => "Código OTP incorrecto"]);
        }
    }

    // -------------
    // Cerrar sesión
    // -------------
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

        header("Content-Type: application/json");
        if ($success) {
            session_destroy();
            $response = ["success" => true, "message" => "Cuenta borrada con éxito"];
        } else {
            $response = ["success" => false, "message" => "Error al borrar la cuenta"];
        }

        echo json_encode($response);
        exit();
    }

    // --------------------------------
    // Procesamiento de los formularios
    // --------------------------------
    public function processForm($form)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = htmlspecialchars($_POST["user"] ?? null);
        $email = htmlspecialchars($_POST["email"] ?? null);
        $pass = htmlspecialchars($_POST["pass"] ?? null);
        $verifyPass = htmlspecialchars($_POST["verify_pass"] ?? null);

        $accountManager = new Users();
        $registeredEmail = $accountManager->findUserByEmail($email);

        // Validación de contraseñas tanto para la vista de acceso como la de actualización de perfil
        if (($form === "reset-password" || !empty($pass)) && ($pass !== $verifyPass || strlen($pass) < 8 || !preg_match("/[A-Za-z]/", $pass) || !preg_match("/[0-9]/", $pass))) {
            $view = $form === "update-profile" ? $this->profile() : $this->account();
            return $this->view($view, ["error" => "La contraseña es inválida o no coinciden", "form" => $form]);
        }

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
        $id = $_SESSION["id"];
        $currentUserData = $accountManager->findUserById($id);

        if ($registeredEmail && $registeredEmail["id"] !== $id) {
            return $this->view("account/profile", ["error" => "El email ya está registrado"]);
        }

        // Manejo de la subida del avatar
        // ------------------------------
        $avatarError = null;

        if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === UPLOAD_ERR_OK) {

            $uploadDirectory = __DIR__ . "/../../public/assets/img/avatars/";
            $avatarExtension = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
            $allowedExtensions = ["jpg", "jpeg", "png", "webp"];

            // Verificar el tamaño y la extensión del archivo (Máximo 200KB)
            if (!in_array($avatarExtension, $allowedExtensions)) {
                $avatarError = "Extensión no válida. Los archivos permitidos son: jpg, jpeg, png y webp";
            } elseif ($_FILES["avatar"]["size"] > 200 * 1024) {
                $avatarError = "El tamaño máximo de imagen permitido son 200KB";
            } else {
                $avataName = "avatar_user_" . $id . ".webp";
                $destPath = $uploadDirectory . $avataName;

                // Si existe un avatar previo del mismo usuario, se elimina
                if (!empty($currentUserData["avatar"]) && $currentUserData["avatar"] !== "default.svg") {
                    unlink($uploadDirectory . $currentUserData["avatar"]);
                }

                // Mover el avatar seleccionado al servidor
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $destPath)) {
                    $avatar = $avataName;
                } else {
                    $avatarError = "Error al subir el archivo. Pruebe de nuevo";
                }
            }
            // En caso de no subir avatar, mantener el actual
        } else {
            $avatar = $currentUserData["avatar"];
        }

        // Guardado de los nuevos datos si hay cambios
        $updates = array_filter([
            "user" => $user && $user !== $currentUserData["user"] ? $user : null,
            "email" => $email && $email !== $currentUserData["email"] ? $email : null,
            "pass" => $cipheredPass,
            "avatar" => $avatar ?? $currentUserData["avatar"]
        ]);

        if (empty($updates) && !$avatarError) return $this->view("account/profile");

        if ($avatarError) {
            return $this->view("account/profile", ["error" => $avatarError, "form" => "update-profile"]);
        }

        // Actualizar los datos del usuario
        if ($accountManager->updateUser(
            $id,
            $updates["user"] ?? $currentUserData["user"],
            $updates["email"] ?? $currentUserData["email"],
            // Si no se indica contraseña, se mantiene la actual
            $updates["pass"] ?? null,
            $updates["avatar"] ?? $currentUserData["avatar"]
        )) {
            $_SESSION = array_merge($_SESSION, array_filter($updates));
            return $this->view("account/profile", ["success" => "Datos actualizados con éxito"]);
        } else {
            return $this->view("account/profile", ["error" => "Error al actualizar los datos"]);
        }
    }

    // ----------------------
    // Formulario de registro
    // ----------------------
    private function registerForm($accountManager, $registeredEmail, $user, $email, $cipheredPass)
    {
        if ($registeredEmail) {
            return $this->view("account/account", ["error" => "El email ya está registrado", "form" => "register"]);
        }

        if ($accountManager->insertUser($user, $email, $cipheredPass, "user")) {
            return $this->view("account/account", ["success" => "Usuario registrado con éxito", "form" => "login"]);
        } else {
            return $this->view("account/account", ["error" => "Error al registrar el usuario", "form" => "register"]);
        }
    }

    // --------------------------------------
    // Formulario para restablecer contraseña
    // --------------------------------------
    private function resetPasswordForm($accountManager, $registeredEmail, $email, $cipheredPass)
    {
        if (!$registeredEmail || $accountManager->getUserRole($email) === "google") {
            return $this->view("account/account", ["error" => "El email es inválido", "form" => "reset-password"]);
        }

        if ($accountManager->updatePassword($email, $cipheredPass)) {
            return $this->view("account/account", ["success" => "Contraseña actualizada con éxito", "form" => "login"]);
        } else {
            return $this->view("account/account", ["error" => "Error al actualizar la contraseña", "form" => "reset-password"]);
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
