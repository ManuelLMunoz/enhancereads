<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(__DIR__ . "/../components/head.php"); ?>
    <title><?php echo $book["title"]; ?></title>
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/books.css">
    <script src="assets/js/books.js" type="module"></script>
</head>

<body>

    <section id="main">
        <?php require_once(__DIR__ . "/../components/navbar.php"); ?>

        <div class="brand">
            <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1>
            <h2 id="type">Detalles del Libro</h2>
        </div>
    </section>

    <section id="content">

        <div class="book-details">

            <div class="book-cover-container">
                <img class="book-cover" src="assets/img/books/<?php echo $book["cover"]; ?>" alt="Portada del libro">

                <!-- Botones para editar y borrar libros (Solo disponible para administradores) -->
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
                $links = json_decode($book["link"], true);

                if (!empty($links) && is_array($links)): ?>

                    <div class="link-container">
                        <p><strong>Enlaces:</strong></p>
                        <?php
                        $logos = [
                            "amazon" => "assets/img/amazon.svg",
                            "casadellibro" => "assets/img/casadellibro.webp",
                            "fnac" => "assets/img/fnac.svg",
                            "buscalibre" => "assets/img/buscalibre.webp",
                        ];

                        // Iterar sobre los enlaces y mostrar el logo correspondiente
                        foreach ($links as $link): ?>
                            <div class="link-item">
                                <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" rel="noopener noreferrer">
                                    <?php
                                    $logo = "";
                                    foreach ($logos as $keyword => $url) {
                                        if (strpos($link, $keyword) !== false) {
                                            $logo = "<img class='shop-logo' title='Link a " . ucfirst($keyword) . "' src='" . $url . "' alt='" . ucfirst($keyword) . "'>";
                                            break;
                                        }
                                    }
                                    // Mostrar el logo correspondiente al sitio o usar el logo el predeterminado
                                    echo $logo ?: "<img class='shop-logo' src='assets/img/document.webp' alt='documento'>";
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
                    <span class="view-more">Ver más</span>
                <?php endif; ?>

            </div>
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
        <div id="image-modal" class="img-zoom-modal">
            <i class="fas fa-times close-button"></i>
            <img src="" alt="Portada ampliada">
        </div>

    </section>

    <?php require_once(__DIR__ . "/../components/footer.php"); ?>

</body>

</html>