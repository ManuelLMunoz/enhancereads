document.addEventListener("DOMContentLoaded", () => {

    // Configuración inicial basada en el contexto (libros o posts)
    const context = window.location.pathname.includes("/books") ? "books" : "posts";
    const defaultOrder = context === "books" ? "asc" : "desc";
    let order = defaultOrder;
    let currentPage = parseInt(window.location.pathname.split("/").pop().split("=")[1]) || 1;
    const filtersExpanded = {};

    // ------------------------------------------------------------------------
    // Cargar los datos y actualizar la vista según los filtros y la paginación
    // ------------------------------------------------------------------------
    const load_data = async (page = 1, updateHistory = true) => {

        const getValues = (selector) =>
            Array.from(document.querySelectorAll(selector)).map(element => element.value);

        // Obtener los filtros seleccionados
        const filters = new FormData();
        filters.append("query", document.querySelector("#search")?.value || "");
        ["genre", "language"].forEach(type => getValues(`#${type}-filter input:checked`).forEach(value => filters.append(`${type}[]`, value)));

        // Fltros especiales en base al contexto
        if (context === "books") {
            ["author", "publisher"].forEach(type => getValues(`#${type}-filter input:checked`).forEach(value => filters.append(`${type}[]`, value)));
            filters.append("pages", document.querySelector("#pages")?.value || "");
        } else if (context === "posts") {
            filters.append("words", document.querySelector("#words")?.value || "");
            if (document.querySelector("#liked")?.checked) filters.append("liked", "on");
        }

        // Agregar la paginación y el orden de los resultados
        filters.append("page", page);
        filters.append("order", order);

        // Construir la URL con paginación y actualizar el historial (La página 1 redirecciona a la URL sin paginación)
        const url = `/${context}${page > 1 ? "/page=" + page : ""}`;
        history[updateHistory ? "pushState" : "replaceState"]({ page }, "", url);

        try {
            const response = await fetch(`fetch-${context}`, {
                method: "POST",
                body: filters
            });
            const data = await response.json();

            // Actualizar el contenido con los resultados
            document.querySelector(".content-container").innerHTML = data.html;

            // Actualizar el scroll de la web al hacer clic en la paginación
            document.querySelector("#pagination")?.addEventListener("click", (event) => {
                if (event.target.closest(".page-link")) window.scrollTo({ top: 0, behavior: "instant" });
            });

            // Actualizar las opciones de los filtros según la respuesta
            updateFilterOptions("#author-filter", "name", filters.getAll("author[]"), data.filters.authors);
            updateFilterOptions("#genre-filter", "name", filters.getAll("genre[]"), data.filters.genres);
            updateFilterOptions("#publisher-filter", "name", filters.getAll("publisher[]"), data.filters.publishers);
            updateFilterOptions("#language-filter", "language", filters.getAll("language[]"), data.filters.languages);
            updateFilterOptions("#pages", "pages", filters.get("pages"), data.filters.pages);
            updateFilterOptions("#words", "words", filters.get("words"), data.filters.words);
        } catch (error) {
            console.error("Error al cargar los datos:", error);
        }
    };

    // Exponer la función de carga de datos de forma global para su uso en otros ficheros
    window.myApp = { ...window.myApp, load_data };

    // Cargar los datos iniciales y manejar la navegación del historial
    load_data(currentPage, false);
    window.onpopstate = () => load_data(history.state?.page, false);

    // --------------------------------------
    // Actualizar las opciones de los filtros
    // --------------------------------------
    const updateFilterOptions = (selector, key, selectedValues, options = []) => {

        // Verificar que los valores y elementos sean válidos
        if (![selectedValues, options].every(Array.isArray) || !document.querySelector(selector)) return;

        // Determinar el estado de expansión y generar los botones de mostrar/ocultar
        const isExpanded = filtersExpanded[selector];
        const button = (expanded, type, icon) =>
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

        // Insertar el HTML generado en el selector correspondiente
        const container = document.querySelector(selector);
        if (container) {
            container.innerHTML = optionsHtml + (options.length > 3 ? button(isExpanded, "show-all", "down") + button(!isExpanded, "show-less", "up") : "");
        }
    }

    // Eventos para las diferentes acciones de usuario
    document.querySelectorAll("#pages, #words, #author-filter, #genre-filter, #publisher-filter, #language-filter, #search, #liked")
        .forEach(element => element.addEventListener("input", () => load_data(1)));

    // Manejar la paginación de los resultados
    document.addEventListener("click", (event) => {
        if (event.target.closest(".page-link")) {
            load_data(event.target.closest(".page-link").dataset.page);
        }
    });

    // Cambiar el orden de los resultados
    document.querySelector("#order").addEventListener("change", (event) => {
        order = event.target.value;
        load_data(1);
    });

    // Limpiar los filtros
    document.getElementById("clear-filters").addEventListener("click", () => {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
        document.querySelectorAll('input[type="search"], select').forEach(input => input.value = "");
        document.getElementById("order").value = defaultOrder;
        order = defaultOrder;
        load_data(1); // Cargar la primera página
    });

    // Mostrar/ocultar las opciones de filtro
    document.addEventListener("click", ({ target }) => {
        const button = target.closest(".show-all, .show-less");
        if (!button) return;
        const isShowAll = button.classList.contains("show-all");
        const targetSelector = button.dataset.target;

        // Mostrar el botón de "Ver todos" si hay más de 3 opciones en el filtro
        document.querySelectorAll(`${targetSelector} .option`).forEach((option, index) => {
            option.style.display = isShowAll || index < 3 ? "block" : "none";
        });

        document.querySelector(`${targetSelector} .show-all`).style.display = isShowAll ? "none" : "inline-block";
        document.querySelector(`${targetSelector} .show-less`).style.display = isShowAll ? "inline-block" : "none";

        // Actualizar el estado de expansión
        filtersExpanded[targetSelector] = isShowAll;
    });

    // ----------------------------------------------------------------
    // Visualización de los filtros en responsive (Por defecto ocultos)
    // ----------------------------------------------------------------
    let filtersVisible = false;
    const toggleFilters = () => document.querySelector("#filters").style.display = (window.innerWidth > 1024 || filtersVisible) ? "block" : "none";

    document.querySelector("#filter-icon").addEventListener("click", () => {
        filtersVisible = !filtersVisible;
        toggleFilters();
    });

    window.addEventListener("resize", toggleFilters);
    toggleFilters();
});
