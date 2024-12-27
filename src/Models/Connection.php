<?php

namespace Src\Models;

use PDO;
use PDOException;

class Connection
{
    private $host = "localhost";
    private $db_name = "enhancereads";
    private $username = "root";
    private $password = "V1O=z6N^6=wv";

    protected $connection;

    public function __construct()
    {
        $this->connection();
    }

    // ------------------
    // Conexión a la BBDD
    // ------------------
    public function connection()
    {
        try {
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Silenciar los errores en la conexión
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
            return $this->connection;
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die("Error al conectar con la base de datos.");
        }
    }
}
