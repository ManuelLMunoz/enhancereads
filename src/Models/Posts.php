<?php

namespace Src\Models;

use PDO;
use PDOException;

class Posts extends Connection
{
    public function getAllPosts($params)
    {
        try {
            // Parámetros básicos
            $search = "%" . $params["search"] . "%";
            $limit = $params["limit"];
            $offset = ($params["page"] - 1) * $limit;
            $order = $params["order"] ?? "desc";
            $userId = $params["user_id"] ?? null;

            // Configuración de filtros
            $filters = [
                "genre" => ["field" => "g.id", "values" => $params["genre"] ?? []],
                "language" => ["field" => "p.language", "values" => $params["language"] ?? []],
                // Conteo de palabras del post para el filtro
                "words" => ["field" => "LENGTH(p.content) - LENGTH(REPLACE(p.content, \" \", \"\")) + 1", "values" => $params["words"] ?? null],
                "favorites" => ["field" => "p.id", "values" => $userId && $params["liked"] == "on" ? $userId : null]
            ];

            // Construcción de cláusulas y valores de filtro
            $filterClauses = [];
            $filterValues = [];
            foreach ($filters as $filterName => $filter) {
                if (!empty($filter["values"])) {
                    // Filtro específico para favoritos
                    if ($filterName === "favorites" && $userId && $params["liked"] == "on") {
                        $filterClauses[] = "{$filter['field']} IN (SELECT post_id FROM post_likes WHERE user_id = ? AND comment_id IS NULL)";
                        $filterValues[] = $userId;
                    } else {
                        // Filtros con múltiples valores o valor único
                        $placeholders = is_array($filter["values"]) ? implode(",", array_fill(0, count($filter["values"]), "?")) : "?";
                        $operator = is_array($filter["values"]) ? "IN" : ($filter["values"] > 0 ? ">=" : "=");
                        $filterClauses[] = "{$filter["field"]} $operator ($placeholders)";
                        $filterValues = array_merge($filterValues, is_array($filter["values"]) ? $filter["values"] : [$filter["values"]]);
                    }
                }
            }
            // Combinar las cláusulas con "AND" para la consulta final
            $filterQuery = $filterClauses ? "AND " . implode(" AND ", $filterClauses) : "";

            $baseQuery = "FROM posts p LEFT JOIN genres g ON p.genre_id = g.id LEFT JOIN users u ON p.user_id = u.id WHERE (p.title LIKE ? OR u.user LIKE ?) $filterQuery";

            $postsQuery = "SELECT p.id, p.title, p.content, p.user_id as user_id, u.user as user_name, p.language, p.created_at, g.name as genre, u.avatar as user_avatar, p.edited_at
               $baseQuery ORDER BY p.created_at $order LIMIT ? OFFSET ?";

            $stmt = $this->connection->prepare($postsQuery);
            $params = array_merge([$search, $search], $filterValues, [$limit, $offset]);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Obtener los likes y comentarios relacionados con cada post
            $currentUserId = $_SESSION["id"] ?? null;
            foreach ($posts as &$post) {
                $post["likes_count"] = $this->getPostLikes($post["id"]);
                $commentsData = $this->getPostComments($post["id"], $currentUserId);
                $post["comments"] = $commentsData["commentTree"];
                $post["comments_count"] = $commentsData["totalCount"];
                $post["word_count"] = str_word_count($post["content"]);
                $post["user_liked"] = $this->isLikePost($currentUserId, $post["id"]);
                $post["edited_at"] = $post["edited_at"] ?? null;
                // Formatear la fecha del post antes de pasarlo a la vista
                $post["formatted_date"] = $this->formatDateAgo($post["created_at"]);
                $post["is_edited"] = !empty($post["edited_at"]) ? "(editado)" : "";
            }

            // Consulta para obtener el conteo total de posts
            $totalQuery = "SELECT COUNT(*) as total $baseQuery";
            $totalStmt = $this->connection->prepare($totalQuery);
            foreach (array_merge([$search, $search], $filterValues) as $index => $value) {
                $totalStmt->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $totalStmt->execute();
            $totalRow = $totalStmt->fetch(PDO::FETCH_ASSOC)["total"];

            // Devolver los posts y el conteo total
            return ["posts" => $posts, "total" => $totalRow];
        } catch (PDOException $e) {
            error_log("Error al mostrar los posts: " . $e->getMessage());
            return ["posts" => [], "total" => 0];
        }
    }

    // --------------------
    // Formateo de la fecha
    // --------------------
    public function formatDateAgo($createdAt)
    {
        // Obtener la diferencia entre la fecha actual y la fecha del post y devolver la unidad correspondiente
        $interval = (new \DateTime($createdAt))->diff(new \DateTime());
        $units = ["y" => "año", "m" => "mes", "d" => "día", "h" => "hora", "i" => "minuto"];

        foreach ($units as $key => $label) {
            if ($interval->$key) {
                return "Hace {$interval->$key} {$label}" . ($interval->$key > 1 ? "s" : "");
            }
        }

        return "Hace un momento";
    }

    // ---------
    // Búsquedas
    // ---------
    public function getGenres()
    {
        return $this->connection->query("SELECT DISTINCT g.id, g.name FROM genres g JOIN posts p ON g.id = p.genre_id ORDER BY g.name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGenres()
    {
        return $this->connection->query("SELECT id, name FROM genres ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLanguages()
    {
        return $this->connection->query("SELECT DISTINCT language FROM posts")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostById($id)
    {
        try {
            $query = "SELECT p.id, p.title, p.content, p.user_id AS user_id, u.user AS user_name, p.language, p.created_at,
            g.name AS genre, u.avatar AS user_avatar, p.edited_at FROM posts p LEFT JOIN genres g ON p.genre_id = g.id
            LEFT JOIN users u ON p.user_id = u.id WHERE p.id = ?";

            $stmt = $this->connection->prepare($query);
            $stmt->execute([$id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Si el post se encuentra, agregar datos adicionales y obtener los likes y comentarios relacionados
            if ($post) {
                $post["likes_count"] = $this->getPostLikes($post["id"]);
                $currentUserId = $_SESSION["id"] ?? null;
                $commentsData = $this->getPostComments($post["id"], $currentUserId);
                $post["comments"] = $commentsData["commentTree"];
                $post["comments_count"] = $commentsData["totalCount"];
                $post["word_count"] = str_word_count($post["content"]);
                $post["user_liked"] = $this->isLikePost($currentUserId, $post["id"]);
                $post["edited_at"] = $post["edited_at"] ?? null;
                $post["formatted_date"] = $this->formatDateAgo($post["created_at"]);
                $post["is_edited"] = !empty($post["edited_at"]) ? "(editado)" : "";
            }

            return $post;
        } catch (PDOException $e) {
            error_log("Error al obtener el post: " . $e->getMessage());
            return null;
        }
    }

    // -------------
    // Insertar post
    // -------------
    public function createPost($userId, $title, $content, $genreId, $language)
    {
        $query = "INSERT INTO posts (user_id, title, content, genre_id, language, created_at) VALUES (:userId, :title, :content, :genreId, :language, NOW())";
        try {
            return $this->connection->prepare($query)->execute(compact("userId", "title", "content", "genreId", "language"));
        } catch (PDOException $e) {
            error_log("Error al insertar post: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------------------------------------------------------
    // Eliminar post (Se borran los likes y comentarios asociados antes de la propia publicación)
    // ------------------------------------------------------------------------------------------
    public function deletePost($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM posts WHERE id = :id");
            return $stmt->execute(compact("id"));
        } catch (PDOException $e) {
            error_log("Error al borrar el post: " . $e->getMessage());
            return false;
        }
    }
    
    // ---------------
    // Actualizar post
    // ---------------
    public function updatePost($postId, $title, $content, $genre, $language)
    {
        $query = "UPDATE posts SET title = :title, content = :content, genre_id = :genre, language = :language, edited_at = NOW() WHERE id = :postId";
        try {
            return $this->connection->prepare($query)->execute(compact("title", "content", "genre", "language", "postId"));
        } catch (PDOException $e) {
            error_log("Error al actualizar el post: " . $e->getMessage());
            return false;
        }
    }

    // ----------------------------------------------------------------
    // Obtener todos los comentarios de un post ordenados por jerarquía
    // ----------------------------------------------------------------
    public function getPostComments($postId, $currentUserId = null)
    {
        try {
            $query = "SELECT pc.id, pc.user_id, pc.comment, pc.created_at, pc.edited_at, pc.parent_comment_id, u.user as user_name, u.avatar as user_avatar,
                CASE WHEN pc.user_id = ? THEN 1 ELSE 0 END as is_user_comment,
                (SELECT COUNT(*) FROM post_likes WHERE comment_id = pc.id) as likes_count,
                CASE WHEN EXISTS (SELECT 1 FROM post_likes WHERE comment_id = pc.id AND user_id = ?) THEN 1 ELSE 0 END as user_liked
                FROM post_comments pc JOIN users u ON pc.user_id = u.id WHERE pc.post_id = ? ORDER BY pc.parent_comment_id ASC, pc.created_at DESC ";

            $stmt = $this->connection->prepare($query);
            $stmt->execute([$currentUserId, $currentUserId, $postId]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $commentTree = [];
            $commentsById = [];

            // Organizar los comentarios por ID
            foreach ($comments as $comment) {
                $comment["formatted_date"] = $this->formatDateAgo($comment["created_at"]);
                $comment["is_edited"] = !empty($comment["edited_at"]) ? "(editado)" : "";
                $comment["children"] = [];
                $commentsById[$comment["id"]] = $comment;
            }

            // Construir la jerarquía
            foreach ($commentsById as $id => $comment) {
                if ($comment["parent_comment_id"]) {
                    // Si el comentario tiene un padre, se añade como hijo
                    $commentsById[$comment["parent_comment_id"]]["children"][] = &$commentsById[$id];
                } else {
                    // Si no tiene padre, se añade al árbol principal
                    $commentTree[] = &$commentsById[$id];
                }
            }
            return ["commentTree" => $commentTree, "totalCount" => count($comments)];
        } catch (PDOException $e) {
            error_log("Error al obtener los comentarios del post: " . $e->getMessage());
            return ["commentTree" => [], "totalCount" => 0];
        }
    }

    // -------------------------------------
    // Insertar comentario dentro de un post
    // -------------------------------------
    public function insertComment($postId, $userId, $comment, $parentCommentId = null)
    {
        $query = "INSERT INTO post_comments (post_id, user_id, comment, created_at, parent_comment_id)
                  VALUES (:postId, :userId, :comment, NOW(), :parentCommentId)";

        try {
            $stmt = $this->connection->prepare($query);
            // Si el comentario es a nivel de post se inserta "null", y si es para responder otro comentario, el id del comentario padre
            $success = $stmt->execute([
                "postId" => $postId,
                "userId" => $userId,
                "comment" => $comment,
                "parentCommentId" => $parentCommentId ?: null
            ]);

            if ($success) {
                // Notificar al creador del post o del comentario que ha recibido un nuevo comentario o respuesta
                $recipientId = $parentCommentId ? $this->getOwnerId("post_comments", "user_id", $parentCommentId) : $this->getOwnerId("posts", "user_id", $postId);
                if ($recipientId !== $userId) { // Evitar notificaciones a uno mismo

                    $notificationParams = [
                        "notifierId" => $userId,
                        "recipientId" => $recipientId,
                        "postId" => $parentCommentId ? $this->getPostIdByComment($parentCommentId) : $postId,
                        "commentId" => $parentCommentId ?: null,
                        "type" => $parentCommentId ? "reply" : "comment"
                    ];

                    // Insertar la notificación
                    $queryNotification = "INSERT INTO notifications (notifier_id, recipient_id, post_id, comment_id, created_at, is_read, type)
                                           VALUES (:notifierId, :recipientId, :postId, :commentId, NOW(), 0, :type)";
                    $this->connection->prepare($queryNotification)->execute($notificationParams);
                }
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al insertar el comentario: " . $e->getMessage());
            return false;
        }
    }

    // --------------------------------------------------------------------------------
    // Borrar comentarios (Tanto anidados a otros comentarios como comentarios de post)
    // --------------------------------------------------------------------------------
    public function deleteCommentAndChildren($commentId)
    {
        try {
            $this->connection->beginTransaction();

            // Obtener los comentarios a eliminar (incluyendo anidados)
            $queryCommentIds = "WITH RECURSIVE comment_tree AS (SELECT id FROM post_comments WHERE id = :id
            UNION ALL SELECT c.id FROM post_comments c JOIN comment_tree ct ON c.parent_comment_id = ct.id) SELECT id FROM comment_tree";

            $stmtCommentIds = $this->connection->prepare($queryCommentIds);
            $stmtCommentIds->bindParam(":id", $commentId);
            $stmtCommentIds->execute();
            $commentIds = $stmtCommentIds->fetchAll(PDO::FETCH_COLUMN);

            // Eliminar likes asociados a todos los comentarios
            $queryDeleteLikes = "DELETE FROM post_likes WHERE comment_id IN (" . implode(",", array_fill(0, count($commentIds), "?")) . ")";
            $stmtDeleteLikes = $this->connection->prepare($queryDeleteLikes);
            $stmtDeleteLikes->execute($commentIds);

            // Eliminar los comentarios
            $queryDeleteComments = "DELETE FROM post_comments WHERE id IN (" . implode(",", array_fill(0, count($commentIds), "?")) . ")";
            $stmtDeleteComments = $this->connection->prepare($queryDeleteComments);
            $result = $stmtDeleteComments->execute($commentIds);

            $this->connection->commit();
            return $result;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("Error al eliminar el comentario: " . $e->getMessage());
            return false;
        }
    }

    // ---------------------
    // Actualizar comentario
    // ---------------------
    public function updateComment($commentId, $content)
    {
        $query = "UPDATE post_comments SET comment = :content, edited_at = NOW() WHERE id = :commentId";

        try {
            $this->connection->prepare($query)->execute(compact("content", "commentId"));
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar el comentario: " . $e->getMessage());
            return false;
        }
    }

    // ------------------------------------------
    // Manejo de los likes en posts y comentarios
    // ------------------------------------------
    public function checkExistingLike($userId, $itemId, $type)
    {
        $isPost = $type === "post";
        $column = $isPost ? "post_id" : "comment_id";
        $query = "SELECT * FROM post_likes WHERE user_id = ? AND $column = ? AND " . ($isPost ? "comment_id IS NULL" : "comment_id IS NOT NULL");

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$userId, $itemId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error al verificar el like: " . $e->getMessage());
            return false;
        }
    }

    private function addLike($userId, $itemId, $type)
    {
        if ($this->checkExistingLike($userId, $itemId, $type)) {
            return false;
        }

        // Diferenciar si el like es para un post o para un comentario
        $isPost = $type === "post";
        $params = [
            "userId" => $userId,
            "postId" => $isPost ? $itemId : $this->getPostIdByComment($itemId),
            "commentId" => $isPost ? null : $itemId
        ];

        $queryLike = "INSERT INTO post_likes (user_id, post_id, comment_id, created_at) VALUES (:userId, :postId, :commentId, NOW())";

        try {
            $this->connection->beginTransaction();
            $this->connection->prepare($queryLike)->execute($params);

            // Obtener el ID del destinatario (propietario del post o comentario)
            $recipientId = $isPost ? $this->getOwnerId("posts", "user_id", $itemId) : $this->getOwnerId("post_comments", "user_id", $itemId);

            // Evitar notificaciones a uno mismo
            if ($recipientId !== $userId) {
                $notificationParams = [
                    "notifierId" => $userId,
                    "recipientId" => $recipientId,
                    "postId" => $isPost ? $itemId : $this->getPostIdByComment($itemId),
                    "commentId" => $isPost ? null : $itemId,
                    "type" => "like"
                ];

                // Insertar la notificación
                $queryNotification = "INSERT INTO notifications (notifier_id, recipient_id, post_id, comment_id, created_at, is_read, type)
                                       VALUES (:notifierId, :recipientId, :postId, :commentId, NOW(), 0, :type)";
                $this->connection->prepare($queryNotification)->execute($notificationParams);
            }

            $this->connection->commit();
            return true;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log("Error al agregar el like: " . $e->getMessage());
            return false;
        }
    }
    private function removeLike($userId, $itemId, $type)
    {
        $isPost = $type === "post";
        $query = "DELETE FROM post_likes WHERE user_id = :userId AND " . ($isPost ? "post_id = :itemId AND comment_id IS NULL" : "comment_id = :itemId");

        try {
            return $this->connection->prepare($query)->execute(["userId" => $userId, "itemId" => $itemId]);
        } catch (PDOException $e) {
            error_log("Error al eliminar el like: " . $e->getMessage());
            return false;
        }
    }

    // Obtener el ID del post por comentario
    private function getPostIdByComment($commentId)
    {
        $query = "SELECT post_id FROM post_comments WHERE id = :commentId";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(["commentId" => $commentId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al obtener el ID del post por comentario: " . $e->getMessage());
            return null;
        }
    }

    // Obtener la cantidad de likes del post
    public function getPostLikes($postId)
    {
        $query = "SELECT COUNT(*) as likes_count FROM post_likes WHERE post_id = :postId AND comment_id IS NULL";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(["postId" => $postId]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al obtener la cantidad de likes del post " . $postId . ": " . $e->getMessage());
            return 0;
        }
    }

    // Métodos para likes en posts
    // ---------------------------
    public function isLikePost($userId, $postId)
    {
        return $this->checkExistingLike($userId, $postId, "post") !== false;
    }

    public function likePost($userId, $postId)
    {
        return $this->addLike($userId, $postId, "post");
    }

    public function unlikePost($userId, $postId)
    {
        return $this->removeLike($userId, $postId, "post");
    }

    // Métodos para likes en comentarios
    // ---------------------------------
    public function isLikeComment($userId, $commentId)
    {
        return $this->checkExistingLike($userId, $commentId, "comment") !== false;
    }

    public function likeComment($userId, $commentId)
    {
        return $this->addLike($userId, $commentId, "comment");
    }

    public function unlikeComment($userId, $commentId)
    {
        return $this->removeLike($userId, $commentId, "comment");
    }

    // -----------------------------
    // Gestión de las notificaciones
    // -----------------------------

    // Obtener el propietario del post o comentario
    private function getOwnerId($table, $column, $id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT $column FROM $table WHERE id = :id");
            $stmt->execute(["id" => $id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error al obtener el propietario del post o comentario: " . $e->getMessage());
            return null;
        }
    }

    public function getNotifications($userId)
    {
        $query = "SELECT n.*, u.user AS notifier, u.avatar AS notifier_avatar,
              CASE WHEN n.type = 'reply' THEN (SELECT comment FROM post_comments WHERE id = n.comment_id)
              WHEN n.post_id IS NOT NULL THEN (SELECT title FROM posts WHERE id = n.post_id)
              ELSE (SELECT comment FROM post_comments WHERE id = n.comment_id)
              END AS content FROM notifications n JOIN users u ON n.notifier_id = u.id
              WHERE n.recipient_id = :userId AND n.is_read = 0 ORDER BY n.created_at DESC";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(["userId" => $userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Formatear la fecha para cada notificación
            foreach ($notifications as &$notification) {
                $notification["formatted_date"] = $this->formatDateAgo($notification["created_at"]);
            }

            return $notifications;
        } catch (PDOException $e) {
            error_log("Error al obtener las notificaciones del usuario " . $userId . ": " . $e->getMessage());
            return [];
        }
    }

    public function markAllAsRead($userId)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE notifications SET is_read = 1 WHERE recipient_id = :userId AND is_read = 0");
            $stmt->execute(["userId" => $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error al marcar notificaciones del usuario " . $userId . " como leídas: " . $e->getMessage());
            return false;
        }
    }
}
