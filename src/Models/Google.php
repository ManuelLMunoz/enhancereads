<?php

namespace Src\Models;

use Google_Client;
use Google\Service\Oauth2;

class Google extends Connection
{
    private $client;

    // Se configura una instancia del cliente de Google
    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId("623628310388-3m23goi4fnt8vaagi2r5qal8tb0eluo2.apps.googleusercontent.com");
        $this->client->setClientSecret("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");
        $this->client->setRedirectUri("http://www.enhancereads.com/google-callback");
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    // Se retorna el cliente de Google para la autenticación
    public function getClient()
    {
        return $this->client;
    }

    // ----------------------------------------------------------------------
    // Obtener la información del usuario a partir del código de autorización
    // ----------------------------------------------------------------------
    public function fetchUserInfo($code)
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token["error"])) {
            throw new \Exception("Error fetching access token: " . $token["error"]);
        }

        // Se establece el token de acceso en el cliente de Google
        $this->client->setAccessToken($token);
        return (new Oauth2($this->client))->userinfo->get();
    }

    // -------------------
    // Guardado del avatar
    // -------------------
    private function saveAvatar($pictureUrl, $uploadDirectory, $prefix)
    {
        $filename = "{$prefix}.webp";
        file_put_contents($uploadDirectory . $filename, file_get_contents($pictureUrl));
        return $filename;
    }

    // ---------------------------
    // Buscar usuario por su email
    // ---------------------------
    public function getUserIdByEmail($email)
    {
        $stmt = $this->connection()->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ? $user["id"] : null;
    }

    // ------------------
    // Manejar al usuario
    // ------------------
    public function handleUser($userInfo, $uploadDirectory)
    {
        // Buscar al usuario
        $stmt = $this->connection()->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => $userInfo->email]);

        // Si el email no está registrado, se inserta el usuario
        if ($stmt->rowCount() == 0) {
            $avatarFilename = $this->saveAvatar($userInfo->picture, $uploadDirectory, "temp");
            $stmt = $this->connection()->prepare("INSERT INTO users (user, email, password, avatar, role) VALUES (:user, :email, '', :avatar , :role)");
            $stmt->execute([":user" => $userInfo->name, ":email" => $userInfo->email, ":avatar" => $avatarFilename, ":role" => "google"]);

            // Obtener el ID del usuario creado y renombrar el archivo del avatar
            $stmt = $this->connection()->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([":email" => $userInfo->email]);
            $lastInsertId = $stmt->fetchColumn();
            $correctAvatarFilename = "avatar_user_" . $lastInsertId . ".webp";
            rename($uploadDirectory . $avatarFilename, $uploadDirectory . $correctAvatarFilename);

            // Se actualiza el usuario con el nombre correcto del avatar
            $this->connection()->prepare("UPDATE users SET avatar = :avatar WHERE id = :id")
                ->execute([":avatar" => $correctAvatarFilename, ":id" => $lastInsertId]);
            return $correctAvatarFilename;
        } else {
            // Si el usuario ya existe, se maneja la actualización del avatar en caso necesario
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user["avatar"] !== $userInfo->picture) {
                // Se guarda el nuevo avatar para el usuario
                $avatarFilename = $this->saveAvatar($userInfo->picture, $uploadDirectory, "avatar_user_" . $user["id"]);
                $this->connection()->prepare("UPDATE users SET avatar = :avatar WHERE email = :email")
                    ->execute([":avatar" => $avatarFilename, ":email" => $userInfo->email]);
            } else {
                // Se mantiene el avatar existente si no hay cambios
                $avatarFilename = $user["avatar"];
            }
            return $avatarFilename;
        }
    }

    // ------------------------------
    // Revocación del token de acceso
    // ------------------------------
    public function revokeToken()
    {
        $this->client->revokeToken();
    }
}
