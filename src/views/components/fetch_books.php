<?php
// Se inicia la sesión si no lo está 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<script>
    //Script para regresar al top tras cambiar de página
    document.querySelector("#pagination").addEventListener("click", (event) => {
        if (event.target.classList.contains("page-link") && !event.target.classList.contains("disabled")) {
            window.scrollTo({
                top: 0,
                behavior: "instant" // Asegura que el scroll se realice de inmediato
            });
        }
    });
</script>

<!-- Se muestran los libros si existen resultados en la BBDD -->
<?php if (!empty($books)) : ?>
    <div class="book-container">
        <?php foreach ($books as $book) : ?>

            <!-- Información del libro -->
            <div class="book-info">

                <!-- Enlace de compra a Amazon -->
                <a href="<?php echo ($book["link"]); ?>" target="_blank">
                    <img src="assets/img/books/<?php echo $book["cover"]; ?>" alt="Portada del libro">
                </a>

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

                <!-- Datos del libro -->
                <p id="title"><?php echo $book["title"]; ?></p>
                <p><strong>Autor:</strong> <?php echo $book["author_name"]; ?></p>
                <p><strong>Género:</strong> <?php echo $book["genre_name"]; ?></p>
                <p><strong>Idioma:</strong> <?php echo $book["language"]; ?></p>
                <p><strong>Año:</strong> <?php echo $book["year"]; ?></p>
                <p><strong>Páginas:</strong> <?php echo $book["pages"]; ?></p>
            </div>

        <?php endforeach; ?>
    </div>

    <?php
    // Configuración de la paginación
    $page = isset($page) ? $page : 1;
    $totalPages = isset($totalPages) ? $totalPages : 1;
    $maxPagesToShow = 3;

    // Cálculo del rango de páginas a mostrar
    $startPage = max(1, $page - floor($maxPagesToShow / 2));
    $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);
    if ($endPage - $startPage + 1 < $maxPagesToShow) $startPage = max(1, $endPage - $maxPagesToShow + 1);

    // Función para generar el enlace de cada página
    function pageLink($p, $currentPage)
    {
        return "<span class='page-link" . ($p == $currentPage ? " active" : "") . "' data-page='$p'>" . sprintf("%02d", $p) . "</span>";
    }
    ?>

    <!-- Paginación de los elementos -->
    <div id="pagination">

        <!-- Botón de página anterior -->
        <span class="page-link prev<?php echo ($page > 1 ? "" : " disabled"); ?>" data-page="<?php echo max($page - 1, 1); ?>">
            <i class="fa-solid fa-angle-left" title="Anterior"></i>
        </span>

        <!-- Enlaces a las páginas -->
        <?php if ($startPage > 1) echo pageLink(1, $page) . ($startPage > 2 ? "<span class='page-link dots'>...</span>" : ""); ?>
        <?php foreach (range($startPage, $endPage) as $i) echo pageLink($i, $page); ?>
        <?php if ($endPage < $totalPages) echo ($endPage < $totalPages - 1 ? "<span class='page-link dots'>...</span>" : "") . pageLink($totalPages, $page); ?>

        <!-- Botón de página siguiente -->
        <span class="page-link next<?php echo ($page < $totalPages ? "" : " disabled"); ?>" data-page="<?php echo min($page + 1, $totalPages); ?>">
            <i class="fa-solid fa-angle-right" title="Siguiente"></i>
        </span>

    </div>

    <!-- Total de elementos encontrados -->
    <div id="element-range"><?php echo "$startElement - $endElement de $totalRows resultados"; ?></div>

    <!-- Si no existen libros, se muestra un error -->
<?php else : ?>
    <p>No se encontraron libros 😥</p>
<?php endif; ?>

<!-- Modal de confirmación del borrado del libro -->
<div id="delete-modal" class="modal hidden">
    <div class="delete-content content">
        <p id="delete-message">¿Está seguro de borrar este libro?</p>
        <button id="confirm-delete" class="confirm-button">Eliminar</button>
        <button id="cancel-delete" class="cancel-button">Cancelar</button>
    </div>
</div>