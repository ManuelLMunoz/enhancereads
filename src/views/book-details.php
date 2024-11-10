<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuración base -->
    <?php require_once("components/head.php"); ?>

    <!-- Configuración concreta -->
    <title><?php echo $book["title"]; ?></title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <script src="assets/js/books.js"></script>

</head>

<body>
    <!-- Sección 1 - Inicio -->
    <section id="main">

        <!-- Menú de navegación -->
        <?php require_once("components/navbar.php"); ?>

        <!-- Logo y slogan de la marca -->
        <div class="brand">
            <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1>
            <h2 id="type">Detalles del Libro</h2>
        </div>
    </section>

    <!-- Sección 2 - Contenido -->
    <section id="content">
        <div class="book-details">

            <div class="book-cover-container">
                <img class="book-cover" src="assets/img/books/<?php echo $book["cover"]; ?>" alt="Portada del libro">

                <!-- Si el usuario es "admin" se mostrarán los botones para editar y borrar los libros -->
                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") : ?>
                    <div class="manage-buttons">
                        <a class="edit-button" href="edit-books/<?php echo $book["id"]; ?>">
                            <i class="fa-solid fa-edit"></i> Editar
                        </a>
                        <a class="delete-button" href="#" data-id="<?php echo $book["id"]; ?>">
                            <i class="fa-solid fa-trash"></i> Borrar
                        </a>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Modal de confirmación del borrado del libro -->
            <div id="delete-modal" class="modal hidden">
                <div class="delete-content content">
                    <p id="delete-message">¿Está seguro de borrar este libro?</p>
                    <button id="confirm-delete" class="confirm-button">Eliminar</button>
                    <button id="cancel-delete" class="cancel-button">Cancelar</button>
                </div>
            </div>

            <!-- Contenedor para mostrar la imagen ampliada -->
            <div id="image-modal" class="zoom-modal">
                <i class="fas fa-times close-button"></i>
                <img src="" alt="Portada ampliada">
            </div>

            <div class="book-info">

                <h1 id="title"><?php echo $book["title"]; ?></h1>
                <p><strong>Autor:</strong> <?php echo $book["author_name"]; ?></p>
                <p><strong>Género:</strong> <?php echo $book["genre_name"]; ?></p>
                <p><strong>Idioma:</strong> <?php echo $book["language"]; ?></p>
                <p><strong>Páginas:</strong> <?php echo $book["pages"]; ?></p>
                <p><strong>Año:</strong> <?php echo $book["year"]; ?></p>
                <p><?php echo !empty($book["publisher_name"]) ? "<strong>Editorial:</strong> " . $book["publisher_name"] : ""; ?></p>
                <p><?php echo !empty($book["isbn"]) ? "<strong>ISBN:</strong> " . htmlspecialchars($book["isbn"]) : ""; ?></p>

                <?php
                // Decodificar el JSON en un array
                $links = json_decode($book["link"], true);

                if (!empty($links) && is_array($links)): ?>

                    <div class="link-container">
                        <p><strong>Enlaces de compra:</strong></p>
                        <?php
                        $logos = [
                            "amazon" => "assets/img/amazon.svg",
                            "casadellibro" => "assets/img/casadellibro.webp",
                            "fnac" => "assets/img/fnac.svg",
                            "buscalibre" => "assets/img/buscalibre.webp",
                        ];

                        // Iterar sobre los enlaces y mostrar el logo correspondiente segun la URL
                        foreach ($links as $link): ?>
                            <div class="link-item">
                                <a href="<?php echo htmlspecialchars($link); ?>" target="_blank">
                                    <?php
                                    $logo = "";
                                    foreach ($logos as $keyword => $url) {
                                        if (strpos($link, $keyword) !== false) {
                                            $logo = "<img class='shop-logo' title='Link a " . ucfirst($keyword) . "' src='" . $url . "' alt='" . ucfirst($keyword) . "'>";
                                            break;
                                        }
                                    }
                                    echo $logo ?: htmlspecialchars($link); // Mostrar el logo o el enlace si no existe imagen
                                    ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($book["description"])): ?>
                    <p><strong>Descripción:</strong></p>
                    <div class="description" id="description">
                        <?php echo nl2br(htmlspecialchars($book["description"])); ?>
                    </div>
                    <span class="view-more" onclick="toggleDescription(this)">Ver más</span>
                <?php endif; ?>

            </div>
    </section>

    <!-- Footer -->
    <?php require_once("components/footer.php"); ?>

</body>

</html>