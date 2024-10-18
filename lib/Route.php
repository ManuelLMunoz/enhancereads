<?php
// Espacio de nombres
namespace Lib;

// Clase para gestionar las rutas de la aplicación
class Route
{
    // Se almacenan las rutas registradas, separadas por el método HTTP (GET y POST)
    private static $routes = [
        "GET" => [],
        "POST" => []
    ];

    // Método para agregar una ruta al método HTTP especificado
    private static function addRoute($method, $uri, $callback)
    {
        self::$routes[$method][trim($uri, "/")] = $callback;
    }

    // Método para registrar una ruta GET
    public static function get($uri, $callback)
    {
        self::addRoute("GET", $uri, $callback);
    }

    // Método para registrar una ruta POST
    public static function post($uri, $callback)
    {
        self::addRoute("POST", $uri, $callback);
    }

    // Método para despachar la solicitud actual, comparando la URI con las rutas registradas
    public static function dispatch()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes[$method] as $route => $callback) {
            // Se reemplaza cualquier parámetro de la ruta en una expresión regular
            if (strpos($route, "{") !== false) {
                $route = preg_replace_callback("#\{([a-zA-Z0-9_\-]+)\}#", function ($matches) {
                    return "(?P<" . $matches[1] . ">[a-zA-Z0-9_\-]+)";
                }, $route);
            }

            // Se verifica si la URI coincide con la ruta
            if (preg_match("#^$route$#", $uri, $matches)) {
                // Se obtienen los parámetros de la URI
                $params = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), 'is_string')));

                // Se llama al callback correspondiente
                $response = is_callable($callback)
                    ? $callback(...array_values($params))
                    : (new $callback[0])->{$callback[1]}(...array_values($params));

                // Se envía la respuesta como JSON si es un array o un objeto; de lo contrario, en texto plano
                if (is_array($response) || is_object($response)) {
                    header("Content-Type: application/json");
                    echo json_encode($response);
                } else {
                    echo $response;
                }

                return;
            }
        }

        // Se muestra una página de error 404 si no se encuentra una ruta que coincida
        require_once "../src/views/404.html";
    }
}
