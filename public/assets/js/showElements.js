$(document).ready(() => {

    // Configuración inicial basada en la URL y el contexto (libros o posts)
    const context = window.location.pathname.includes("/books") ? "books" : "posts";
    const fetchUrl = `fetch-${context}`;
    const defaultOrder = context === "books" ? "asc" : "desc";
    let currentPage = parseInt(window.location.pathname.split("/").pop().split("=")[1]) || 1;
    let order = defaultOrder;

    const $search = $("#search"), $liked = $("#liked");
    const filtersExpanded = { "#author-filter": false, "#genre-filter": false, "#language-filter": false, "publisher-filter": false };

    // ------------------------------------------------------------------------
    // Cargar los datos y actualizar la vista según los filtros y la paginación
    // ------------------------------------------------------------------------
    function load_data(page = 1, updateHistory = true) {
        // Valores actuales de los filtros
        const filters = {
            query: $search.val(),
            author: context === "books" ? $("#author-filter input:checked").map((_, el) => el.value).get() : [],
            genre: $("#genre-filter input:checked").map((_, el) => el.value).get(),
            publisher: $("#publisher-filter input:checked").map((_, el) => el.value).get(),
            language: $("#language-filter input:checked").map((_, el) => el.value).get(),
            year: context === "books" ? $("#year").val() : null,
            pages: $("#pages").val(),
            words: context === "posts" ? $("#words").val() : null,
            liked: $liked.is(":checked") ? "on" : ""
        };

        // Construir la URL y actualizar el historial
        const url = page === 1 ? `/${context}` : `/${context}/page=${page}`;
        updateHistory ? history.pushState({ page }, "", url) : history.replaceState({ page }, "", url);

        // Solicitud POST para obtener los datos filtrados y actualizar el contenido
        $.post(fetchUrl, { ...filters, page, limit: $("#limitSelect").val(), order }, data => {
            const response = JSON.parse(data);
            $(".content-container").html(response.html);

            // Actualizar las opciones de los filtros según la respuesta
            updateFilterOptions("#author-filter", response.filters.authors, "name", filters.author);
            updateFilterOptions("#year", response.filters.years, "year", filters.year);
            updateFilterOptions("#genre-filter", response.filters.genres, "name", filters.genre);
            updateFilterOptions("#publisher-filter", response.filters.publishers, "name", filters.publisher);
            updateFilterOptions("#language-filter", response.filters.languages, "language", filters.language);
            updateFilterOptions("#pages", response.filters.pages, "pages", filters.pages);
            context === "posts" && updateFilterOptions("#words", response.filters.words, "words", filters.words);
        });
    }

    // Exponer la función de carga de datos de forma global para su uso en otros ficheros
    window.myApp = { ...window.myApp, load_data };

    // Cargar los datos iniciales y manejar la navegación del historial
    load_data(currentPage, false);
    $(window).on("popstate", event => event.originalEvent.state?.page && load_data(event.originalEvent.state.page, false));

    // --------------------------------------
    // Actualizar las opciones de los filtros
    // --------------------------------------
    function updateFilterOptions(selector, options = [], key, selectedValues) {

        // Verificar que los valores y elementos sean válidos
        if (!Array.isArray(selectedValues) || !Array.isArray(options) || !$(selector).length) return;

        // Determinar el estado de expansión
        const isExpanded = filtersExpanded[selector];
        // Generar los botones de mostrar/ocultar
        const buttonHtml = (expanded, type, icon) =>
            `<button class="btn-filter ${type}" data-target="${selector}" ${expanded ? "style='display: none;'" : ""}>
                <span>Ver ${type === "show-all" ? "todos" : "menos"} <i class="fas fa-chevron-${icon}"></i></span>
            </button>`;

        // Generar el HTML para las opciones de filtro (Con más de 3 opciones aparecen los botones)
        const optionsHtml = options.map((option, index) => {
            const value = option.id || option[key];
            const isChecked = selectedValues.includes(value.toString()) ? " checked" : "";
            const additionalClass = index >= 3 && !isExpanded ? " additional-option" : "";
            const displayStyle = index >= 3 && !isExpanded ? " style='display: none;'" : "";
            return `<div class="option${additionalClass}"${displayStyle}><input type="checkbox" value="${value}"${isChecked}> ${option[key]}</div>`;
        }).join("");

        // Insertar el HTML generado en el selector
        $(selector).html(optionsHtml + (options.length > 3 ? buttonHtml(isExpanded, "show-all", "down") + buttonHtml(!isExpanded, "show-less", "up") : ""));
    }

    // Eventos para las diferentes acciones de usuario
    $("#limitSelect, #year, #pages, #words, #author-filter, #genre-filter, #publisher-filter, #language-filter, #search, #liked").on("change input", () => load_data(1));
    $(document).on("click", ".page-link", function () { load_data($(this).data("page")); });

    // Limpiar filtros
    $("#clear-filters").click(() => {
        $('input[type="checkbox"]').prop("checked", false);
        $('input[type="text"], select').val("");
        $("#order").val(defaultOrder);
        order = defaultOrder;
        setTimeout(() => load_data(1), 10);
    });

    // Cambiar el orden de los resultados
    $("#order").change(function () {
        order = $(this).val();
        load_data(1);
    });

    // Mostrar todas las opciones de los filtros reducidos
    $(document).on("click", ".show-all", function () {
        const target = $(this).data("target");
        $(target).find(".option").show();
        $(this).hide();
        $(target).find(".show-less").show();
        filtersExpanded[target] = true;
    });

    // Ocultar las opciones extra de los filtros
    $(document).on("click", ".show-less", function () {
        const target = $(this).data("target");
        $(target).find(".option").hide().slice(0, 3).show();
        $(this).hide();
        $(target).find(".show-all").show();
        filtersExpanded[target] = false;
    });

    // ------------------------------------------
    // Visualización de los filtros en responsive 
    // ------------------------------------------
    let filtersVisible = false;

    $("#filter-icon").on("click", () => {
        filtersVisible = !filtersVisible;
        updateFiltersVisibility();
    });

    $(window).on("resize", updateFiltersVisibility);

    // Ocultar filtros por defecto en responsive
    function updateFiltersVisibility() {
        $("#filters").toggle(window.innerWidth > 1024 || filtersVisible);
    }

    updateFiltersVisibility();
});