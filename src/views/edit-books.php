<?php
// Inicia la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si el usuario no es "admin", se redirige a la página de inicio
if ($_SESSION["role"] !== "admin") {
    header("Location:/");
    exit();
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Configuración base -->
    <?php require_once("components/head.php"); ?>

    <!-- Configuración concreta -->
    <title>Editar - <?php echo htmlspecialchars($book["title"]); ?></title>
    <link rel="stylesheet" href="assets/css/forms.css">
    <script src="assets/js/books.js" type="module"></script>

    <script>
        // Pasar variables PHP a JavaScript
        window.selectedAuthor = <?php echo isset($book["author"]) ? json_encode($book["author"]) : "null"; ?>;
        window.selectedGenre = <?php echo isset($book["genre"]) ? json_encode($book["genre"]) : "null"; ?>;
        window.selectedPublisher = <?php echo isset($book["publisher"]) ? json_encode($book["publisher"]) : "null"; ?>;
    </script>

    <!-- Yearpicker para seleccionar el año en formato calendario -->
    <link rel="stylesheet" href="assets/css/yearpicker.css">
    <script src="assets/js/yearpicker.js" type="module"></script>
    <script>
        $(document).ready(function() {
            // Se asigna el valor del año obtenido
            var year = <?php echo json_encode($book["year"]); ?>;
            $("#year").val(year);

            // Se inicializa el yearpicker con el rango de años y el valor seleccionado
            $("#year").yearpicker({
                startYear: 1900,
                endYear: new Date().getFullYear(),
                year: year // Establecer el año seleccionado como predeterminado
            });
        });
    </script>

</head>

<body>

    <section id="main">

        <!-- Logo -->
        <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1><br>

        <div class="form">

            <h1>Editar Libros</h1>

            <!-- ----------------------------- -->
            <!-- Formulario para editar libros -->
            <!-- ----------------------------- -->
            <form id="edit-books" action="/update-book" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo htmlspecialchars($book["id"]); ?>">

                <!-- Título -->
                <label for="title"> Título
                    <i class="fas fa-book icon"></i>
                    <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($book["title"]); ?>" required aria-label="Título" />
                </label>

                <!-- Autor -->
                <label for="author"> Autor
                    <i class="fas fa-user icon"></i>
                    <select id="author" name="author" required aria-label="Autor" data-selected="<?php echo isset($book["author"]) ? $book["author"] : "null"; ?>">
                        <option value="" disabled selected>Seleccione una opción</option> <!-- Obtenidos de forma dinámica de la BBDD -->
                    </select>
                </label>

                <!-- Género -->
                <label for="genre"> Género
                    <i class="fas fa-list-alt icon"></i>
                    <select id="genre" name="genre" required aria-label="Género" data-selected="<?php echo isset($book["genre"]) ? $book["genre"] : "null"; ?>">
                        <option value="" disabled selected>Seleccione una opción</option> <!-- Obtenidos de forma dinámica de la BBDD -->
                    </select>
                </label>

                <!-- Editorial -->
                <label for="publisher"> Editorial
                    <i class="fas fa-book-open icon"></i>
                    <select id="publisher" name="publisher" aria-label="Editorial" data-selected="<?php echo isset($book["publisher"]) ? $book["publisher"] : "null"; ?>">
                        <option value="" disabled selected>Seleccione una opción</option> <!-- Obtenidos de forma dinámica de la BBDD -->
                    </select>
                </label>

                <!-- Páginas -->
                <label for="pages"> Páginas
                    <i class="fa-solid fa-book-open icon"></i>
                    <input id="pages" type="number" name="pages" value="<?php echo htmlspecialchars($book["pages"]); ?>" aria-label="Páginas" />
                </label>

                <!-- Año -->
                <label for="year"> Año
                    <i class="fas fa-calendar-alt icon"></i>
                    <input id="year" type="text" name="year" maxlength="4" value="<?php echo htmlspecialchars($book["year"]); ?>" aria-label="Año" />
                </label>

                <!-- Idioma -->
                <label for="language"> Idioma
                    <i class="fas fa-language icon"></i>
                    <select id="language" name="language" required aria-label="Idioma">
                        <option value="Español" <?php echo (htmlspecialchars($book["language"]) == "Español") ? "selected" : ""; ?>>Español</option>
                        <option value="Inglés" <?php echo (htmlspecialchars($book["language"]) == "Inglés") ? "selected" : ""; ?>>Inglés</option>
                    </select>
                </label>

                <!-- ISBN -->
                <label for="isbn"> ISBN
                    <i class="fas fa-barcode icon"></i>
                    <input id="isbn" type="text" name="isbn" aria-label="ISBN" value="<?php echo htmlspecialchars($book['isbn']); ?>" maxlength="13" />
                </label>

                <!-- Portada -->
                <label for="cover"> Portada
                    <i class="fas fa-image icon"></i>
                    <input id="cover" type="file" name="cover" aria-label="Foto" accept="image/*" />
                </label>

                <!-- Descripción -->
                <label for="description"> Descripción
                    <textarea id="description" name="description" required aria-label="Descripción"><?php echo htmlspecialchars($book['description']); ?></textarea>
                </label>

                <!-- Enlaces -->
                <label for="links"> Enlaces </label>
                <div id="links-container">
                    <?php
                    // Decodificar el JSON de los enlaces y generar un input por cada enlace
                    $links = json_decode($book["link"], true) ?: [];
                    foreach ($links as $index => $link) {
                        echo "<div class=\"link-input\">
            <div class=\"remove-link-icon-container\">
                <i class=\"fas fa-trash-alt remove-link-icon\" onclick=\"removeLink(this)\"></i>
            </div>
            <input type=\"url\" name=\"links[]\" id=\"link_$index\" value=\"" . htmlspecialchars($link) . "\" aria-label=\"Enlace\" />
        </div>";
                    }
                    ?>
                </div>
                <a href="#" id="add-link">Agregar enlace</a>

                <!-- Mensajes de error / success -->
                <div class="message error" style="display: none;"></div>
                <div class="message success" style="display: none;"></div>

                <button type="submit" style="margin-top: 15px;">Actualizar </button>
                <button type="button" onclick="window.history.back()">Regresar</button>

            </form>
        </div>

    </section>

</body>

</html>