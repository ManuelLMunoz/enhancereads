<?php

namespace Src;

class Route
{
    // Array para almacenar las rutas según el método HTTP (GET y POST)
    private static $routes = [
        "GET" => [],
        "POST" => []
    ];

    // Agregar una ruta al método HTTP especificado
    private static function addRoute($method, $uri, $callback)
    {
        self::$routes[$method][trim($uri, "/")] = $callback;
    }

    public static function get($uri, $callback)
    {
        self::addRoute("GET", $uri, $callback);
    }

    public static function post($uri, $callback)
    {
        self::addRoute("POST", $uri, $callback);
    }

    // Despachar la solicitud actual, comparando la URI con las rutas registradas
    public static function dispatch()
    {
        // Obtener la URI y el método de la solicitud
        $uri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $method = $_SERVER["REQUEST_METHOD"];

        // Recorrer las rutas registradas del método actual
        foreach (self::$routes[$method] as $route => $callback) {
            // Reemplazar cualquier parámetro de la ruta en una expresión regular
            if (strpos($route, "{") !== false) {
                $route = preg_replace_callback("#\{([a-zA-Z0-9_\-]+)\}#", function ($matches) {
                    return "(?P<" . $matches[1] . ">[a-zA-Z0-9_\-]+)";
                }, $route);
            }

            // Si la URI coincide con la ruta actual
            if (preg_match("#^$route$#", $uri, $matches)) {
                // Obtener los parámetros de la URI
                $params = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), "is_string")));

                // Llamar al callback correspondiente
                $response = is_callable($callback)
                    ? $callback(...array_values($params))
                    : (new $callback[0])->{$callback[1]}(...array_values($params));

                // Enviar la respuesta en formato JSON o en caso contrario en texto plano
                if (is_array($response) || is_object($response)) {
                    header("Content-Type: application/json");
                    echo json_encode($response);
                } else {
                    echo $response;
                }

                return; // Finalizar el despacho tras una coincidencia
            }
        }

        // Cargar la página de error 404 si no hay coincidencias
        require_once "../src/views/404.html";
    }
}
