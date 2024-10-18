<?php
// Este archivo se encargará de renderizar los comentarios (Fichero separado para poder ser reutilizado)

// Se define la función para mostrar los comentarios si no existe
if (!function_exists("renderComments")) {
    function renderComments($comments, $postsModel)
    {
        // Bucle que itera sobre cada comentario
        foreach ($comments as $comment) : ?>
            <div class="comment" id="comment-<?php echo htmlspecialchars($comment["id"]); ?>">

                <!-- Datos del usuario -->
                <div class="user-data">
                    <img class="user-avatar" src="/assets/img/avatars/<?php echo htmlspecialchars($comment["user_avatar"]); ?>" alt="Avatar de <?php echo htmlspecialchars($comment["user_name"]); ?>">
                    <div class="user-details">
                        <p><strong><?php echo htmlspecialchars($comment["user_name"]); ?></strong></p>
                        <p class="timestamp">
                            <?php echo $postsModel->formatDateAgo($comment["created_at"]); ?>
                            <?php if (!empty($comment["edited_at"])): ?>
                                (editado)
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Contenido del comentario -->
                <p class="formatted-text comment-content"><?php echo htmlspecialchars($comment["comment"]); ?></p>

                <!-- Si el comentario pertenece al usuario logado, se muestran los botones de administración -->
                <?php if ($comment["is_user_comment"]) : ?>
                    <div class="manage-comments">
                        <a class="edit-comment-button" href="editComment/<?php echo htmlspecialchars($comment["id"]); ?>">
                            <i class="fa-solid fa-edit" title="Editar"></i>
                        </a>
                        <a class="delete-comment-button" data-id="<?php echo htmlspecialchars($comment["id"]); ?>">
                            <i class="fa-solid fa-trash" title="Borrar"></i>
                        </a>
                    </div>
                    <!-- El admin puede borrar todos los comentarios -->
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                    <div class="manage-comments">
                        <a class="delete-comment-button" data-id="<?php echo htmlspecialchars($comment["id"]); ?>">
                            <i class="fa-solid fa-trash" title="Borrar"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Conteo de likes y comentarios -->
                <div class="post-share">
                    <button class="like-button <?php echo ($comment["user_liked"] ?? false) ? "liked" : ""; ?>" data-type="comment" data-id="<?php echo htmlspecialchars($comment["id"]); ?>">
                        <i class="fa fa-thumbs-up" title="Votar"></i> <span><?php echo htmlspecialchars($comment["likes_count"] ?? 0); ?></span>
                    </button>
                    <button id="comment-reply" class="reply-button" data-parent-id="<?php echo htmlspecialchars($comment["id"]); ?>">
                        <i class="fa-solid fa-reply" title="Responder"></i>
                    </button>
                </div>

                <!-- Si el comentario tiene respuestas, se muestran de forma anidada -->
                <?php if (!empty($comment["children"])) : ?>
                    <div class="comment-children">
                        <?php renderComments($comment["children"], $postsModel); ?>
                    </div>
                <?php endif; ?>
            </div>
<?php
        endforeach;
    }
}

// Se define la función para mostrar los comentarios disponibles si no existe
if (!function_exists("renderCommentsIfAvailable")) {
    function renderCommentsIfAvailable($comments, $postsModel)
    {
        // Si existen comentarios, se muestran llamando a la función "renderComments()"
        if (!empty($comments)) {
            renderComments($comments, $postsModel);
        }
    }
}
?>