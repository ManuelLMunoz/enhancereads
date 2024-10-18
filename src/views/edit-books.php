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

                <!-- Páginas -->
                <label for="pages"> Páginas
                    <i class="fa-solid fa-book-open icon"></i>
                    <input id="pages" type="text" name="pages" value="<?php echo htmlspecialchars($book["pages"]); ?>" required aria-label="Páginas" />
                </label>

                <!-- Año -->
                <label for="year"> Año
                    <i class="fas fa-calendar-alt icon"></i>
                    <input id="year" type="text" name="year" maxlength="4" value="<?php echo htmlspecialchars($book["year"]); ?>" required aria-label="Año" />
                </label>

                <!-- Enlace -->
                <label for="link"> Enlace
                    <i class="fas fa-link icon"></i>
                    <input id="link" type="url" name="link" value="<?php echo htmlspecialchars($book["link"]); ?>" required aria-label="Enlace" />
                </label>

                <!-- Idioma -->
                <label for="language"> Idioma
                    <i class="fas fa-language icon"></i>
                    <select id="language" name="language" required aria-label="Idioma">
                        <option value="Español" <?php echo (htmlspecialchars($book["language"]) == 'Español') ? 'selected' : ''; ?>>Español</option>
                        <option value="Inglés" <?php echo (htmlspecialchars($book["language"]) == 'Inglés') ? 'selected' : ''; ?>>Inglés</option>
                    </select>
                </label>

                <!-- Portada -->
                <label for="cover"> Portada
                    <i class="fas fa-image icon"></i>
                    <input id="cover" type="file" name="cover" aria-label="Foto" accept="image/*" />
                </label>

                <!-- Mensajes de error / success -->
                <div class="message error" style="display: none;"></div>
                <div class="message success" style="display: none;"></div>

                <button type="submit">Actualizar </button>
                <button type="button" onclick="window.location.href='/books'">Regresar</button>

            </form>
        </div>

    </section>

</body>

</html>