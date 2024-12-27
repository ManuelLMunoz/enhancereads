document.addEventListener("DOMContentLoaded", () => {

    // ---------------------------------------------------------------------------------------------------------
    // Cargar los autores, géneros y editoriales desde el servidor en los formularios de agregar y editar libros
    // ---------------------------------------------------------------------------------------------------------
    const loadAndPopulateSelectors = async (selectedAuthor, selectedGenre, selectedPublisher) => {
        try {
            const response = await fetch("fetch-authors-genres-and-publishers", { method: "POST" });
            const data = await response.json();

            if (data.success) {
                // Arrays para las opciones, selectores y opciones marcadas
                const options = [data.authors, data.genres, data.publishers];
                const selectors = ["#author", "#genre", "#publisher"];
                const selectedOptions = [selectedAuthor, selectedGenre, selectedPublisher];

                selectors.forEach((selector, index) => {
                    // Obtener el elemento del selector
                    const selectElement = document.querySelector(selector);
                    if (selectElement) {
                        // Establecer las opciones del selector, incluyendo la opción por defecto
                        selectElement.innerHTML = `
                            <option value="" disabled ${selectedOptions[index] === null ? "selected" : ""}>Seleccione una opción</option>
                            ${options[index].map(option => `
                                <option value="${option.id}" ${option.id == selectedOptions[index] ? "selected" : ""}>${option.name}</option>
                            `).join("")}
                        `;
                    }
                });
            }
        } catch (error) {
            console.error("Error:", error);
        }
    };

    // Obtener los valores marcados de los selectores
    const getSelectedValue = (selector) => {
        const element = document.querySelector(selector);
        return element?.dataset.selected !== "null" ? element?.dataset.selected : null;
    };

    loadAndPopulateSelectors(getSelectedValue("#author"), getSelectedValue("#genre"), getSelectedValue("#publisher"));

    // --------------------------------------------
    // Borrar libros de forma dinámica sin recargar
    // --------------------------------------------
    document.addEventListener("click", (event) => {
        if (event.target.matches(".delete-button")) {
            event.preventDefault();
            const id = event.target.dataset.id;

            if (id) {
                const modal = document.querySelector("#delete-modal");
                modal.classList.remove("hidden");
                modal.dataset.id = id;
            } else {
                showToast("Error al capturar el ID del libro", "error");
            }
        }
    });

    document.getElementById("cancel-delete")?.addEventListener("click", () => {
        document.getElementById("delete-modal").classList.add("hidden");
    })

    document.querySelector("#confirm-delete")?.addEventListener("click", async () => {
        const modal = document.querySelector("#delete-modal");
        const id = modal.dataset.id;

        if (!id) {
            showToast("No se ha seleccionado ningún libro para borrar", "error");
            modal.classList.add("hidden");
        }
        
        try {
            const response = await fetch(`/delete-book/${id}`, { method: "POST" });
            const { success } = await response.json();

            if (success) {
                // Si la respuesta es exitosa, guardar el mensaje en localStorage y redirigir a la vista de libros
                localStorage.setItem("deleteSuccess", "Libro borrado con éxito");
                window.location.href = "/books";
            } else {
                showToast("Error al borrar el libro", "error");
            }
        } catch {
            showToast("Error al procesar la solicitud", "error");
        } finally {
            modal.classList.add("hidden");
        }
    });

    // Mostrar toast si hay un mensaje de borrado en localStorage
    const message = localStorage.getItem("deleteSuccess");
    if (message) {
        showToast(message, "success");
        localStorage.removeItem("deleteSuccess");
    }

    // ------------------------------------------------------------
    // Actualizar los datos del libro tras editar de forma dinámica 
    // ------------------------------------------------------------
    document.getElementById("edit-books")?.addEventListener("submit", async (event) => {
        event.preventDefault();
        try {
            const response = await fetch(event.target.action, {
                method: event.target.method,
                body: new FormData(event.target), // Objeto FormData con los datos del formulario
            });
            const { message, success } = await response.json();
            showToast(message, success ? "success" : "error");
        } catch (error) {
            showToast("Error al actualizar el libro", "error");
        }
    });

    // --------------------------------
    // Ampliar la descripción del libro
    // --------------------------------
    document.querySelector(".view-more")?.addEventListener("click", (event) => {
        const description = document.getElementById("description");
        description.classList.toggle("expanded");
        event.target.textContent = description.classList.contains("expanded") ? "Ver menos" : "Ver más";
    });

    // ----------------------------------------------
    // Ampliar/cerrar la imagen al hacer clic en ella
    // ----------------------------------------------
    document.addEventListener("click", ({ target }) => {
        const modal = document.getElementById("image-modal");

        if (target.classList.contains("book-cover")) {
            modal.querySelector("img").src = target.src;
            modal.style.display = "flex";
        } else if (target.matches(".close-button, #image-modal")) {
            modal.style.display = "none";
        }
    });

    // ---------------------------------------------------------------------------
    // Agregar inputs para los enlaces en los formularios de crear y editar libros
    // ---------------------------------------------------------------------------
    document.getElementById("add-link")?.addEventListener("click", (event) => {
        event.preventDefault();
        // Agregar un nuevo input dentro del contenedor de enlaces (Por defecto se crea uno)
        document.getElementById("links-container").insertAdjacentHTML("beforeend", `
        <div class="link-input">
            <div class="remove-link-icon-container">
                <i class="fas fa-trash-alt remove-link-icon"></i>
            </div>
            <input type="url" name="links[]" required placeholder="Ingresa un enlace">
        </div>`);
    });

    // Borrar el input seleccionado
    document.getElementById("links-container")?.addEventListener("click", (event) => {
        if (event.target.closest(".remove-link-icon")) {
            event.target.closest(".link-input").remove();
        }
    });

});