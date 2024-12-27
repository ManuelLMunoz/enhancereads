<?php

namespace Src\Models;

use PDOException;

class Users extends Connection
{
    // ---------------
    // Buscar usuarios
    // ---------------
    public function findUser($login)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE (user = :login OR email = :login) AND role IN ('user', 'admin')");
        $stmt->execute([":login" => $login]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findUserByEmail($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findUserById($id)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // ------------------------------------------
    // Obtener el rol de un usuario por su email
    // ------------------------------------------
    public function getUserRole($email)
    {
        try {
            $stmt = $this->connection->prepare("SELECT role FROM users WHERE email = :email");
            $stmt->execute([":email" => $email]);
            return $stmt->fetchColumn() ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener el rol del usuario: " . $e->getMessage());
            return null;
        }
    }

    // ----------------
    // Insertar usuario
    // ----------------
    public function insertUser($user, $email, $password, $role)
    {
        // Al dar de alta un usuario, se inserta el avatar por defecto
        $query = "INSERT INTO users (user, email, password, role, avatar) VALUES (:user, :email, :password, :role, 'default.svg')";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(compact("user", "email", "password", "role"));
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al insertar el usuario: " . $e->getMessage());
            return false;
        }
    }

    // --------------
    // Borrar usuario
    // --------------
    public function deleteUser($id)
    {
        try {
            // Obtener el avatar y si es personalizado eliminarlo del servidor
            $user = $this->connection->query("SELECT avatar FROM users WHERE id = $id")->fetch();
            if ($user && $user["avatar"] && $user["avatar"] !== "default.svg") {
                $avatarPath = __DIR__ . "/../../public/assets/img/avatars/" . $user["avatar"];
                if (file_exists($avatarPath)) unlink($avatarPath);
            }

            $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(["id" => $id]);
        } catch (PDOException $e) {
            error_log("Error al borrar el usuario: " . $e->getMessage());
            return false;
        }
    }

    // ----------------------------
    // Actualizar datos del usuario
    // ----------------------------
    public function updateUser($id, $user, $email, $password = null, $avatar = null)
    {
        try {
            $params = [":user" => $user, ":email" => $email, ":id" => $id];

            // NOTA: La contraseña SOLO se actualiza si se rellenan los campos. En caso contrario, se mantiene la actual
            if ($password) $params[":password"] = $password;

            if ($avatar) $params[":avatar"] = $avatar;

            $query = "UPDATE users SET user = :user, email = :email" . ($password ? ", password = :password" : "") .
                ($avatar ? ", avatar = :avatar" : "") . " WHERE id = :id";

            $stmt = $this->connection->prepare($query);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar el usuario: " . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($email, $newPassword)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE users SET password = :password WHERE email = :email");
            $stmt->execute([":password" => $newPassword, ":email" => $email]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar la contraseña: " . $e->getMessage());
            return false;
        }
    }

    public function update2FASecret($userId, $secret)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE users SET 2fa_secret = :secret WHERE id = :id");
            $stmt->execute(compact("secret", "userId"));
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar el 2FA: " . $e->getMessage());
            return false;
        }
    }
}
