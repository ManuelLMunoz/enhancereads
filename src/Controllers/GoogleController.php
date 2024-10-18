<?php

namespace Src\Controllers;

use Src\Models\Google;

class GoogleController extends Controller
{
    private $google;

    // -----------------------------------------------
    // Inicializar la clase Google en el constructor
    // -----------------------------------------------
    public function __construct()
    {
        $this->google = new Google();
    }

    // --------------------------------------------------------
    // Redirigir al usuario a la URL de autenticación de Google
    // --------------------------------------------------------
    public function login()
    {
        header("Location: " . filter_var($this->google->getClient()->createAuthUrl(), FILTER_SANITIZE_URL));
        exit();
    }

    // -----------------------------------------------------------------
    // Callback para manejar la devolución de la llamada de Google OAuth
    // -----------------------------------------------------------------
    public function callback()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        try {
            if (isset($_GET["code"])) {
                $userInfo = $this->google->fetchUserInfo($_GET["code"]);

                // Se almacenan los datos del usuario en un array
                $userinfo = [
                    "email" => $userInfo->email,
                    "first_name" => $userInfo->givenName,
                    "last_name" => $userInfo->familyName,
                    "full_name" => $userInfo->name,
                    "picture" => $userInfo->picture,
                    "verified_email" => $userInfo->verifiedEmail,
                    "token" => $userInfo->id,
                ];

                // Se maneja el avatar del usuario
                $avatarFilename = $this->google->handleUser($userInfo, __DIR__ . "/../../public/assets/img/avatars/");

                // Se obtiene el ID del usuario de Google guardado en la BBDD
                $userId = $this->google->getUserIdByEmail($userInfo->email);

                // Se almacenan los datos del usuario en la sesión
                $_SESSION["user"] = $userinfo["full_name"];
                $_SESSION["user_info"] = $userinfo;
                $_SESSION["user_token"] = $_GET["code"];
                $_SESSION["login_source"] = "google";
                $_SESSION["avatar"] = $avatarFilename;
                $_SESSION["id"] = $userId;

                header("Location: /");
                exit();
            }
        } catch (\Exception $e) {
            error_log("Error: " . $e->getMessage());
            exit();
        }
    }

    // ----------------
    // Cerrar la sesión
    // ----------------
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (isset($_SESSION["user_token"])) {
            $this->google->revokeToken();
            session_destroy();
        }

        header("Location: /");
        exit();
    }
}
