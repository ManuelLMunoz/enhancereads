<?php

namespace Src\Controllers;

// Clase base para los controladores
class Controller
{
    // -----------------------------
    // Método para cargar las vistas
    // -----------------------------
    public function view($route, $data = [])
    {
        // Ruta de la vista (Se sustituyen los puntos por barras)
        $viewFile = "../src/views/" . str_replace(".", "/", $route) . ".php";

        // Si la vista existe
        if (file_exists($viewFile)) {
            extract($data); // Se extraem los datos como variables
            ob_start(); // Se inicia el buffer
            require_once $viewFile; // Se carga la vista
            return  ob_get_clean(); // Se retorna el contenido renderizado y se cierra el buffer

        } else { // Si la vista no existe, carga un error 404
            require_once "../src/views/404.html";
        }
    }
}
