<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(__DIR__ . "/../components/head.php"); ?>
    <title><?php echo $post["title"]; ?></title>
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/posts.css">
    <script src="assets/js/posts.js" type="module"></script>
</head>

<body>

    <section id="main">
        <?php require_once(__DIR__ . "/../components/navbar.php"); ?>

        <div class="brand">
            <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1>
            <h2 id="type">Detalles del post</h2>
        </div>

    </section>

    <section id="content">

        <div class="post-container" style="width: 100%; max-width: 1000px;">

            <div class="post-info details" id="post-<?php echo $post["id"]; ?>">

                <div class="post-data">

                    <div class="user-data">
                        <img class="user-avatar" src="/assets/img/avatars/<?php echo $post["user_avatar"]; ?>" alt="Avatar de <?php echo htmlspecialchars($post["user_name"]); ?>">
                        <div class="user-details">
                            <p><strong><?php echo htmlspecialchars($post["user_name"]); ?></strong></p>
                            <p class="timestamp">
                                <?php echo htmlspecialchars($post["formatted_date"]); ?>
                                <?php echo !empty($post["is_edited"]) ? htmlspecialchars($post["is_edited"]) : ""; ?>
                            </p>
                        </div>
                    </div>

                    <div class="post-details">
                        <p><strong>Categoría:</strong> <?php echo htmlspecialchars($post["genre"]); ?></p>
                        <p><strong>Idioma:</strong> <?php echo htmlspecialchars($post["language"]); ?></p>
                    </div>

                    <!-- Botones de editar y borrar (Aparecen en aquellos posts creados por el usuario logado) -->
                    <?php if (isset($_SESSION["id"]) && $_SESSION["id"] == $post["user_id"]) : ?>
                        <div class="manage-posts" style="display: grid;">
                            <a class="delete-button" data-id="<?php echo $post["id"]; ?>">
                                <i class="fa-solid fa-trash"></i> Borrar
                            </a>
                            <a class="edit-button" href="editPosts/<?php echo $post["id"]; ?>">
                                <i class="fa-solid fa-edit"></i> Editar
                            </a>
                        </div>
                        <!-- El admin puede borrar todos los posts -->
                    <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") : ?>
                        <div class="manage-posts" style="display: grid;">
                            <a class="delete-button" data-id="<?php echo $post["id"]; ?>">
                                <i class="fa-solid fa-trash"></i> Borrar
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <h2 class="post-title" tabindex="0"><?php echo htmlspecialchars($post["title"]); ?></h2>
                        <p class="formatted-text" style="max-height: none;"><?php echo preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank">$1</a>', $post["content"]) ?></p>
                    </div>

                    <!-- Likes y comentarios del post -->
                    <div class="post-community">

                        <div class="post-share">
                            <button class="like-button <?php echo $post["user_liked"] ? "liked" : ""; ?>" data-type="post" data-id="<?php echo $post["id"]; ?>">
                                <i class="fa fa-thumbs-up" title="Votar"></i> <span><?php echo $post["likes_count"]; ?></span>
                            </button>
                            <button class="comment-button" data-id="<?php echo $post["id"]; ?>">
                                <i class="fa fa-comment"></i> <span><?php echo $post["comments_count"]; ?></span>
                            </button>
                        </div>

                        <!-- Incluir el archivo que actúa de plantilla para los comentarios -->
                        <div class="post-comments" style="display: block;">
                            <?php
                            require_once "comment-template.php";
                            renderCommentsIfAvailable($post["comments"]);
                            ?>
                        </div>

                        <!-- Formulario para escribir comentarios (Solo funciona con usuarios logados) -->
                        <div class="comment-form" style="display: block;" id="comment-form" data-logged-in="<?php echo isset($_SESSION["id"]) ? "true" : "false"; ?>">
                            <form method="post">
                                <input type="hidden" name="post_id" value="<?php echo $post["id"]; ?>">
                                <input type="hidden" name="parent_comment_id" id="parent_comment_id" value="">
                                <textarea id="comment-textarea" name="comment" rows="1" placeholder="Escribe un comentario"></textarea>
                                <button type="submit" id="confirm-comment" class="confirm-button hidden">Comentar</button>
                                <button type="reset" id="cancel-comment" class="cancel-button hidden">Cancelar</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </section>

    <!-- Modal de confirmación de borrado de posts y comentarios -->
    <div id="delete-modal" class="modal hidden">
        <div class="delete-content content">
            <p id="delete-message"></p>
            <button id="confirm-delete" class="confirm-button">Eliminar</button>
            <button id="cancel-delete" class="cancel-button">Cancelar</button>
        </div>
    </div>

    <?php require_once(__DIR__ . "/../components/footer.php"); ?>

</body>

</html>