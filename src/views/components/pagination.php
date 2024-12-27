<?php
// Configuración de la paginación
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
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

<div id="pagination">

    <span class="page-link prev<?php echo ($page > 1 ? "" : " disabled"); ?>" data-page="<?php echo max($page - 1, 1); ?>">
        <i class="fa-solid fa-angle-left" title="Anterior"></i>
    </span>

    <!-- Enlaces a las páginas (Mostrar puntos suspensivos cuando se superen 3 páginas por encima y/o por debajo) -->
    <?php if ($startPage > 1) echo pageLink(1, $page) . ($startPage > 2 ? "<span class='page-link dots disabled'>...</span>" : ""); ?>
    <?php foreach (range($startPage, $endPage) as $i) echo pageLink($i, $page); ?>
    <?php if ($endPage < $totalPages) echo ($endPage < $totalPages - 1 ? "<span class='page-link dots disabled'>...</span>" : "") . pageLink($totalPages, $page); ?>

    <span class="page-link next<?php echo ($page < $totalPages ? "" : " disabled"); ?>" data-page="<?php echo min($page + 1, $totalPages); ?>">
        <i class="fa-solid fa-angle-right" title="Siguiente"></i>
    </span>

</div>

<!-- Total de elementos encontrados -->
<div id="element-range"><?php echo "$startElement - $endElement de $totalRows resultados"; ?></div>