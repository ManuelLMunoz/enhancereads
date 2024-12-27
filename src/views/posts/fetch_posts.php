<!-- Mostrar todos los posts encontrados en la BBDD -->
<?php if (!empty($posts)) : ?>
    <div class="post-container">
        <?php foreach ($posts as $post) : ?>

            <div class="post-info" id="post-<?php echo $post["id"]; ?>">

                <button class="close-button" style="display: none;"><i class="fas fa-times"></i></button>

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
                        <div class="manage-posts">
                            <a class="delete-button" data-id="<?php echo $post["id"]; ?>">
                                <i class="fa-solid fa-trash"></i> Borrar
                            </a>
                            <a class="edit-button" href="editPosts/<?php echo $post["id"]; ?>">
                                <i class="fa-solid fa-edit"></i> Editar
                            </a>
                        </div>

                        <!-- El admin puede borrar todos los posts -->
                    <?php elseif (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") : ?>
                        <div class="manage-posts">
                            <a class="delete-button" data-id="<?php echo $post["id"]; ?>">
                                <i class="fa-solid fa-trash"></i> Borrar
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="post-content">
                        <h2 class="post-title" tabindex="0"><?php echo htmlspecialchars($post["title"]); ?></h2>
                        <p class="formatted-text"><?php echo preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank">$1</a>', $post["content"]) ?></p>
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
                        <div class="post-comments">
                            <?php
                            require_once "comment-template.php";
                            renderCommentsIfAvailable($post["comments"]);
                            ?>
                        </div>

                        <!-- Formulario para escribir comentarios (Solo funciona con usuarios logados) -->
                        <div class="comment-form" id="comment-form" data-logged-in="<?php echo isset($_SESSION["id"]) ? "true" : "false"; ?>">
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
        <?php endforeach; ?>
    </div>

    <?php require_once(__DIR__ . "/../components/pagination.php"); ?>

<?php else : ?>
    <p>No se encontraron posts 😥</p>
<?php endif; ?>