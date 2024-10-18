<?php

namespace Src\Models;

use PDO;
use PDOException;

// Clase base para los modelos
class Connection
{
    // Propiedades de la conexión
    private $host = "localhost";
    private $db_name = "enhancereads";
    private $username = "root";
    private $password = "Vives2013";

    // Variable para almacenar la conexión
    protected $connection;

    // Constructor de la clase 
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
            // Conexión a la BBDD MySQL con PDO 
            $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

            // En desarrollo se permiten mostrar los errores para manejarlos
            // $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // En producción se silencian los mensajes
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

            return $this->connection; // Se retorna la conexión
        } catch (PDOException $e) {
            // Si ocurre un fallo, se guarda el error en un log y se finaliza la conexión
            error_log("Error de conexión: " . $e->getMessage());
            die("Error al conectar con la base de datos.");
        }
    }
}
