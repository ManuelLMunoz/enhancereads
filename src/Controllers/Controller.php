<?php

namespace Src\Controllers;

class Controller
{
    // -----------------------------
    // Método para cargar las vistas
    // -----------------------------
    public function view($route, $data = [])
    {
        // Ruta de la vista (Se sustituyen los puntos por barras)
        $viewFile = "../src/views/" . str_replace(".", "/", $route) . ".php";

        // Si la vista existe, se extraen sus datos y se devuelve el contenido renderizado
        if (file_exists($viewFile)) {
            extract($data);
            ob_start();
            require_once $viewFile;
            return  ob_get_clean();
        } else {
            require_once "../src/views/404.html";
        }
    }
}
