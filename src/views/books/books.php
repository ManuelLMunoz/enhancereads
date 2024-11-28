<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(__DIR__ . "/../components/head.php"); ?>
    <title>Libros</title>
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <script src="assets/js/showElements.js" type="module"></script>
    <script src="assets/js/books.js" type="module"></script>
</head>

<body>

    <section id="main">
        <?php require_once(__DIR__ . "/../components/navbar.php"); ?>

        <div class="brand">
            <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1>
            <h2 id="type">Libros</h2>
            <input type="search" id="search" placeholder="Busca por título o autor">

            <!-- Botón para agregar libros (Solo disponible para administradores) -->
            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") : ?>
                <div class="manage-buttons">
                    <a class="create-button" href="manage-books">
                        <i class="fa-solid fa-plus"></i> Gestionar libros
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

                <div class="filter-option">
                    <label for="author">Autor</label>
                    <div id="author-filter">
                    </div>
                </div>

                <div class="filter-option">
                    <label for="genre">Género</label>
                    <div id="genre-filter"></div>
                </div>

                <div class="filter-option">
                    <label for="publisher">Editorial</label>
                    <div id="publisher-filter"></div>
                </div>

                <div class="filter-option">
                    <label for="language">Idioma</label>
                    <div id="language-filter"></div>
                </div>

                <div class="filter-option">
                    <label for="pages">Páginas</label><br>
                    <select id="pages">
                        <option value="">Todos</option>
                        <option value="50">+50 páginas</option>
                        <option value="100">+100 páginas</option>
                        <option value="200">+200 páginas</option>
                        <option value="500">+500 páginas</option>
                    </select>
                </div>

                <div class="filter-option">
                    <label for="order">Orden</label><br>
                    <select id="order" name="order">
                        <option value="asc">Ascendente</option>
                        <option value="desc">Descendente</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- Contenedor para mostrar los elementos (Obtenidos de la BBDD) -->
        <div class="content-container"> </div>

    </section>

    <?php require_once(__DIR__ . "/../components/footer.php"); ?>

</body>

</html>