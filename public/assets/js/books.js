$(document).ready(function () {

    // -----------------------------------------------------------------------------------------------
    // Cargar los autores y géneros desde el servidor en los formularios de agregar y modificar libros
    // -----------------------------------------------------------------------------------------------
    const loadAuthorsGenresAndPublishers = (selectedAuthor, selectedGenre, selectedPublisher) => {
        $.post("fetch-authors-and-genres", ({ success, authors, genres, publishers }) => {
            if (success) {
                // Actualizar los selectores con las opciones recibidas
                ["#author", "#genre", "#publisher"].forEach((selector, index) =>
                    populateSelect(selector, [authors, genres, publishers][index], [selectedAuthor, selectedGenre, selectedPublisher][index])
                );
            }
        }, "json").fail((_, status) => console.error(`Error AJAX: ${status}`));
    };

    // Llenar los selectores <select> con las opciones y seleccionar la opción por defecto
    const populateSelect = (selector, options, selectedOption) => {
        $(selector).empty().append(
            `<option value="" disabled ${selectedOption === null ? "selected" : ""}>Seleccione una opción</option>`,
            options.map(option =>
                `<option value="${option.id}" ${option.id == selectedOption ? "selected" : ""}>${option.name}</option>`
            )
        );
    };

    // Obtener los valores seleccionados
    const selectedAuthor = $("#author").data("selected");
    const selectedGenre = $("#genre").data("selected");
    const selectedPublisher = $("#publisher").data("selected");
    loadAuthorsGenresAndPublishers(selectedAuthor, selectedGenre, selectedPublisher);

    // --------------------------------------------
    // Borrar libros de forma dinámica sin recargar
    // --------------------------------------------

    // Evento en el botón para borrar un libro
    $(document).on("click", ".delete-button", function (event) {
        event.preventDefault();
        const id = $(this).data("id");
        id ? $("#delete-modal").removeClass("hidden").data("id", id) : showToast("Error al capturar el ID del libro", "error");
    });

    // Evento al cancelar la confirmación
    $(document).on("click", "#cancel-delete", () => $("#delete-modal").addClass("hidden"));

    // Evento al botón de confirmación
    $(document).on("click", "#confirm-delete", function () {
        const id = $("#delete-modal").data("id");

        // Si no hay un ID, muestra un mensaje de error y oculta el modal
        if (!id) {
            showToast("No se ha seleccionado ningún libro para borrar", "error");
            $("#delete-modal").addClass("hidden");
            return;
        }

        // Solicitud POST al servidor para borrar el libro
        $.post(`/delete-book/${id}`)
            .done(response => {
                try {
                    // Si la respuesta es exitosa, se recargan los datos y muestra un mensaje de éxito
                    if (JSON.parse(response).success) {
                        window.myApp?.load_data?.(1);
                        showToast("Libro borrado con éxito", "success");
                    }
                } catch {
                    showToast("Error al borrar el libro", "error");
                }
            })
            .fail(() => showToast("Error al procesar la solicitud", "error"))
            // Se oculta el modal al final de la operación
            .always(() => $("#delete-modal").addClass("hidden"));
    });
});

// ------------------------------------
// Crear y mostrar los mensajes "toast"
// ------------------------------------
const showToast = (message, type) => {
    const $toast = $("<div>").addClass(`toast ${type}`).text(message);
    $("body").append($toast);

    // Mostrar el toast y ocultarlo después de 3 segundos
    setTimeout(() => $toast.css("opacity", 1), 10);
    setTimeout(() => { $toast.css("opacity", 0); setTimeout(() => $toast.remove(), 500); }, 3000);
};

// -------------------------------------------------------------
// Actualizar los datos del libro de forma dinámica sin recargar
// -------------------------------------------------------------
$("#edit-books").submit(async (event) => {
    event.preventDefault();
    try {
        // Solicitud al servidor con los datos del formulario
        const { success, message } = await fetch(event.target.action, {
            method: event.target.method,
            body: new FormData(event.target),
        }).then(res => res.json());
        // Mensaje basado en la respuesta del servidor
        showMessage(success, message);
    } catch {
        showMessage(false, "Error al actualizar el libro");
    }
});
// Mostrar el mensaje en la vista
const showMessage = (success, message) => {
    const msg = $(`.message.${success ? "success" : "error"}`);
    msg.text(message).fadeIn().delay(3000).fadeOut();
};

// --------------------------------
// Ampliar la descripción del libro
// --------------------------------
const toggleDescription = (button) => {
    const description = document.getElementById("description");
    description.classList.toggle("expanded");
    button.textContent = description.classList.contains("expanded") ? "Ver menos" : "Ver más";
};

// ---------------------------------------
// Ampliar la imagen al hacer clic en ella
// ---------------------------------------
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
const container = document.getElementById("links-container");
document.getElementById("add-link").onclick = (event) => {
    event.preventDefault();
    // Agregar un nuevo input dentro del contenedor de enlaces
    container.insertAdjacentHTML("beforeend",
        `<div class="link-input">
        <div class="remove-link-icon-container">
            <i class="fas fa-trash-alt remove-link-icon"></i>
        </div>
        <input type="url" name="links[]" required placeholder="Ingresa un enlace">
    </div>`);
};
// Borrar del contenedor el input seleccionado
container.onclick = (event) => {
    if (event.target.classList.contains("remove-link-icon")) {
        event.target.closest(".link-input").remove();
    }
};