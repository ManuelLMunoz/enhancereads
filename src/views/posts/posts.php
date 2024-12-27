<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(__DIR__ . "/../components/head.php"); ?>
    <title>Posts</title>
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/posts.css">
    <script src="assets/js/showElements.js" type="module"></script>
    <script src="assets/js/posts.js" type="module"></script>
</head>

<body>

    <section id="main">
        <?php require_once(__DIR__ . "/../components/navbar.php"); ?>

        <div class="brand">
            <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1>
            <h2 id="type">Posts</h2>
            <input type="search" id="search" placeholder="Busca por título o autor">

            <!-- Botón para crear un post si el usuario está autenticado -->
            <?php if (isset($_SESSION["user"])) : ?>
                <div class="manage-buttons">
                    <a id="create-post-button" class="create-button" href="javascript:void(0);">
                        <i class="fa-solid fa-plus"></i> Crear Post
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </section>

    <section id="content">

        <div class="filters-container">

            <p>Filtros <span id="filter-icon" class="fas fa-filter"></span></p>

            <div id="filters">

                <div class="filter-option">
                    <button id="clear-filters">Limpiar <i class="fa-solid fa-eraser"></i></button>
                </div>

                <?php if (isset($_SESSION["user"])) : ?>
                    <div class="filter-option">
                        <input type="checkbox" id="liked" name="liked">
                        <label for="liked">Favoritos</label>
                    </div>
                <?php endif; ?>

                <div class="filter-option">
                    <label for="genre">Categoría</label>
                    <div id="genre-filter"></div>
                </div>

                <div class="filter-option">
                    <label for="language">Idioma</label>
                    <div id="language-filter"></div>
                </div>

                <div class="filter-option">
                    <label for="words">Palabras</label><br>
                    <select id="words">
                        <option value="">Todos</option>
                        <option value="100">+100 palabras</option>
                        <option value="200">+200 palabras</option>
                        <option value="500">+500 palabras</option>
                        <option value="1000">+1000 palabras</option>
                    </select>
                </div>

                <div class="filter-option">
                    <label for="order">Orden</label><br>
                    <select id="order" name="order">
                        <option value="desc">Más recientes</option>
                        <option value="asc">Más antiguos</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Contenedor para mostrar los elementos (Obtenidos de la BBDD) -->
        <div class="content-container"></div>

    </section>

    <!-- Modal de confirmación de borrado de posts y comentarios -->
    <div id="delete-modal" class="modal hidden">
        <div class="delete-content content">
            <p id="delete-message"></p>
            <button id="confirm-delete" class="confirm-button">Eliminar</button>
            <button id="cancel-delete" class="cancel-button">Cancelar</button>
        </div>
    </div>

    <!-- Formulario modal para crear un nuevo post -->
    <div id="create-post-modal" class="modal hidden">
        <div class="create-content content">

            <button id="close-popup" class="close-button"><i class="fas fa-times"></i></button>

            <div class="post-data">

                <form id="create-post-form" method="post" action="create-post">

                    <div class="user-data">
                        <img class="user-avatar" src="/assets/img/avatars/<?php echo $_SESSION["avatar"]; ?>" alt="Avatar del usuario">
                        <div class="user-details">
                            <p><strong><?php echo htmlspecialchars($_SESSION["user"]); ?></strong></p>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_SESSION["id"]); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="post-genre"><strong>Categoría:</strong></label>
                        <select id="post-genre" name="genre" required> <!-- Obtenidos de forma dinámica de la BBDD --> </select>
                    </div>

                    <div class="form-group">
                        <label for="post-language"><strong>Idioma:</strong></label>
                        <select id="post-language" name="language" required>
                            <option value="Español">Español</option>
                            <option value="Inglés">Inglés</option>
                        </select>
                    </div>

                    <div class="post-content">
                        <input type="text" id="post-title" name="title" placeholder="Título" required>
                        <textarea id="post-content" name="content" placeholder="Contenido" required></textarea>
                    </div>

                    <button type="submit" class="confirm-button">Publicar</button>
                    <button type="button" class="cancel-button" id="cancel-create-post">Cancelar</button>

                </form>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . "/../components/footer.php"); ?>

</body>

</html>