<?php
// Este archivo se encargará de renderizar los comentarios (Fichero separado para poder ser reutilizado)
if (!function_exists("renderComments")) {
    function renderComments($comments)
    {
        // Iterar sobre cada comentario
        foreach ($comments as $comment) : ?>
            <div class="comment" id="comment-<?php echo htmlspecialchars($comment["id"]); ?>">

                <div class="user-data">
                    <img class="user-avatar" src="/assets/img/avatars/<?php echo htmlspecialchars($comment["user_avatar"]); ?>"
                        alt="Avatar de <?php echo htmlspecialchars($comment["user_name"]); ?>">
                    <div class="user-details">
                        <p><strong><?php echo htmlspecialchars($comment["user_name"]); ?></strong></p>
                        <p class="timestamp">
                            <?php echo htmlspecialchars($comment["formatted_date"]); ?>
                            <?php echo !empty($comment["is_edited"]) ? htmlspecialchars($comment["is_edited"]) : ""; ?>
                        </p>
                    </div>
                </div>

                <p class="formatted-text comment-content"><?php echo preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank">$1</a>', htmlspecialchars($comment["comment"])) ?></p>

                <!-- Si el comentario pertenece al usuario logado, se muestran los botones de administración -->
                <?php if (isset($_SESSION["id"]) && $_SESSION["id"] == $comment["user_id"]) : ?>
                    <div class="manage-comments">
                        <a class="edit-comment-button" href="editComment/<?php echo htmlspecialchars($comment["id"]); ?>">
                            <i class="fa-solid fa-edit" title="Editar"></i>
                        </a>
                        <a class="delete-comment-button" data-id="<?php echo htmlspecialchars($comment["id"]); ?>">
                            <i class="fa-solid fa-trash" title="Borrar"></i>
                        </a>
                    </div>
                    <!-- El admin puede borrar todos los comentarios -->
                <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") : ?>
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
                        <?php renderComments($comment["children"]); ?>
                    </div>
                <?php endif; ?>
            </div>
<?php
        endforeach;
    }
}

// Se define la función para mostrar los comentarios disponibles si no existe
if (!function_exists("renderCommentsIfAvailable")) {
    function renderCommentsIfAvailable($comments)
    {
        if ($comments) renderComments($comments);
    }
}
?>