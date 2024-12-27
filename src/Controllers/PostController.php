<?php

namespace Src\Controllers;

use Src\Models\Posts;

class PostController extends Controller
{
    public function posts($page = 1)
    {
        return $this->view("posts/posts", ["page" => $page]);
    }

    public function viewPostDetails($id, $title)
    {
        $post = (new Posts())->getPostById($id);

        // Verificar si el post existe y si el título coincide con el formateado
        if (!$post || $this->sanitizeTitle($post["title"]) !== $title) {
            return $this->view("404", [], 404);
        }

        return $this->view("posts/post-details", ["post" => $post]);
    }

    private function sanitizeTitle($title)
    {
        // Eliminar acentos y caracteres especiales, convertir a minúsculas y reemplazar espacios por guiones
        $unaccented = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $title);
        $sanitized = preg_replace("/[^a-z0-9 ]/", "", strtolower($unaccented));
        return str_replace(" ", "-", trim($sanitized));
    }

    // ---------------------------------------------
    // Obtener y filtrar los posts según parámetros
    // ---------------------------------------------
    public function fetchPosts()
    {
        session_start();
        $userId = $_SESSION["id"] ?? null;

        // Parámetros de búsqueda
        $params = [
            "search" => $_POST["query"] ?? "",
            "order" => $_POST["order"] ?? "desc",
            "limit" => 5, // Nº de elementos a mostrar por página
            "page" => intval($_POST["page"] ?? 1),
            "genre" => $_POST["genre"] ?? [],
            "language" => $_POST["language"] ?? [],
            "words" => $_POST["words"] ?? null,
            "liked" => $_POST["liked"] ?? null,
            "user_id" => $userId
        ];

        // Obtener los posts y la información de paginación
        $postsData = (new Posts())->getAllPosts($params);
        $posts = $postsData["posts"];
        $totalRows = $postsData["total"];
        $totalPages = ceil($totalRows / $params["limit"]);
        $page = max(min($params["page"], $totalPages), 1);
        $startElement = ($page - 1) * $params["limit"] + 1;
        $endElement = min($page * $params["limit"], $totalRows);

        // Renderizar la vista de los posts
        ob_start();
        include(__DIR__ . "/../views/posts/fetch_posts.php");
        $output = ob_get_clean();

        // Respuesta con los datos en formato JSON
        $post = new Posts();
        echo json_encode([
            "html" => $output,
            "filters" => [
                "genres" =>  $post->getGenres(),
                "languages" =>  $post->getLanguages()
            ],
            "pagination" => [
                "total_pages" => $totalPages,
                "current_page" => $page,
                "start_element" => $startElement,
                "end_element" => $endElement,
                "total_row" => $totalRows
            ],
            "current_user_id" => $userId,
            "posts_model" => $post
        ]);
    }

    public function getGenres()
    {
        header("Content-Type: application/json");
        echo json_encode(["genres" => (new Posts())->getAllGenres()]);
    }

    // -------------
    // Insertar post
    // -------------
    public function createPost()
    {
        $userId = $_POST["id"];
        $title = htmlspecialchars($_POST["title"]);
        $content = htmlspecialchars($_POST["content"]);
        $genre = htmlspecialchars($_POST["genre"]);
        $language = htmlspecialchars($_POST["language"]);

        if (empty($title) || empty($content) || empty($genre) || empty($language)) {
            echo json_encode(["success" => false, "message" => "Todos los campos son requeridos"]);
            return;
        }

        $success = (new Posts())->createPost($userId, $title, $content, $genre, $language);
        echo json_encode(["success" => $success, "message" => $success ? "Post creado con éxito" : "Error al crear el post"]);
    }

    // -----------
    // Borrar post
    // -----------
    public function deletePost($id)
    {
        $success = (new Posts())->deletePost($id);
        echo json_encode(["success" => $success, "message" => $success ? "Post borrado con éxito" : "Error al borrar el post"]);
    }

    // ---------------
    // Actualizar post
    // ---------------
    public function updatePost()
    {
        // Convertir el JSON recibido en un array asociativo
        $data = json_decode(file_get_contents("php://input"), true);
        $postId = $data["post_id"];
        $title = htmlspecialchars($data["title"]);
        $content = htmlspecialchars($data["content"]);
        $genre = htmlspecialchars($data["genre"]);
        $language = htmlspecialchars($data["language"]);

        if (empty($title) || empty($content) || empty($genre) || empty($language)) {
            echo json_encode(["success" => false, "message" => "Todos los campos son requeridos"]);
            return;
        }

        $success = (new Posts())->updatePost($postId, $title, $content, $genre, $language);
        echo json_encode(["success" => $success, "message" => $success ? "Post actualizado con éxito" : "Error al actualizar el post"]);
    }

    // -------------------
    // Mostrar comentarios
    // -------------------
    public function fetchComments()
    {
        session_start();
        $postId = $_POST["post_id"];
        $currentUserId = $_SESSION["id"] ?? null;
        $commentsData = (new Posts())->getPostComments($postId, $currentUserId);

        // Incluir el archivo de plantilla para los comentarios
        ob_start();
        include(__DIR__ . "/../views/posts/comment-template.php");

        // Renderizar los comentarios principales
        if (!empty($commentsData["commentTree"])) {
            renderComments($commentsData["commentTree"], new Posts());
        }

        // Devolver el HTML y el conteo de comentarios como respuesta JSON
        $output = ob_get_clean();
        echo json_encode([
            "success" => true,
            "html" => $output,
            "comments_count" => $commentsData["totalCount"]
        ]);
    }

    // -------------------
    // Insertar comentario
    // -------------------
    public function submitComment()
    {
        session_start();

        $userId = $_SESSION["id"] ?? null;
        $postId = $_POST["post_id"] ?? null;
        $comment = htmlspecialchars($_POST["comment"] ?? " ");
        $parentCommentId = $_POST["parent_comment_id"] ?? null;

        // Verificar si el usuario está autenticado y si se proporcionó el ID del post
        if ($userId && $postId && !empty($comment)) {
            $success = (new Posts())->insertComment($postId, $userId, $comment, $parentCommentId);
            $response = ["success" => $success];
        } else {
            $response = ["success" => false, "error" => "Error al agregar el comentario"];
        }

        echo json_encode($response);
    }

    // -----------------
    // Borrar comentario
    // -----------------
    public function deleteComment($id)
    {
        $success = (new Posts())->deleteCommentAndChildren($id);
        echo json_encode(["success" => $success, "message" => $success ? "Comentario borrado con éxito" : "Error al borrar el comentario"]);
    }

    // ---------------------
    // Actualizar comentario
    // ---------------------
    public function updateComment()
    {
        // Convertir el JSON recibido en un array asociativo
        $data = json_decode(file_get_contents("php://input"), true);
        $commentId = $data["comment_id"];
        $content = htmlspecialchars($data["content"]);

        if (empty($content)) {
            echo json_encode(["success" => false, "message" => "El contenido del comentario es requerido"]);
            return;
        }
        $success = (new Posts())->updateComment($commentId, $content);
        echo json_encode(["success" => $success, "message" => $success ? "Comentario actualizado con éxito" : "Error al actualizar el comentario"]);
    }

    // ------------------------------------------------
    // Gestión de likes (Tanto a posts y a comentarios)
    // ------------------------------------------------
    public function toggleLike($type)
    {
        session_start();
        $userId = $_SESSION["id"] ?? null;
        $itemId = $_POST[$type . "_id"] ?? null;

        // Verificar si el usuario está autenticado y si se proporcionó el ID del post o comentario
        if (!$userId || !$itemId) {
            echo json_encode(["success" => false,  "error" => !$userId ? "Debes iniciar sesión para votar" : "ID del $type no proporcionado"]);
            return;
        }

        $posts = new Posts();
        // Diferenciar entre un like de post y un like a un comentario
        $methodPrefix = $type === "post" ? "Post" : "Comment";
        $isLiked = $posts->{"isLike{$methodPrefix}"}($userId, $itemId);
        $method = ($isLiked ? "unlike" : "like") . $methodPrefix;
        $success = $posts->$method($userId, $itemId);

        echo json_encode(["success" => $success, "action" => $isLiked ? "unliked" : "liked"]);
    }

    public function likePost()
    {
        $this->toggleLike("post");
    }

    public function likeComment()
    {
        $this->toggleLike("comment");
    }

    // -------------------------
    // Gestión de notificaciones
    // -------------------------
    public function getNotifications()
    {
        session_start();
        $userId = $_SESSION["id"] ?? null;
        $posts = new Posts();

        if ($userId) {
            $notifications = $posts->getNotifications($userId);

            // Incluir el título del post en cada notificación
            foreach ($notifications as &$notification) {
                $post = $posts->getPostById($notification["post_id"]);
                if ($post) {
                    $notification["post_title"] = $this->sanitizeTitle($post["title"]);
                }
            }

            $response = ["success" => true, "notifications" => $notifications];
        } else {
            $response = ["success" => false, "notifications" => [], "error" => "Usuario no autenticado"];
        }

        echo json_encode($response);
    }

    public function markAllAsRead()
    {
        session_start();
        $userId = $_SESSION["id"] ?? null;

        if ($userId) {
            $result = (new Posts())->markAllAsRead($userId);
            $response = ["success" => $result];

            if (!$result) {
                $response["error"] = "No se pudieron marcar las notificaciones como leídas";
            }
        } else {
            $response = ["success" => false, "error" => "Usuario no autenticado"];
        }

        echo json_encode($response);
    }
}
