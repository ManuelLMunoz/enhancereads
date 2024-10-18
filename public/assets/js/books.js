$(document).ready(function () {

    // -----------------------------------------------------------------------------------------------
    // Cargar los autores y géneros desde el servidor en los formularios de agregar y modificar libros
    // -----------------------------------------------------------------------------------------------
    const loadAuthorsAndGenres = (selectedAuthor, selectedGenre) => {
        $.post("fetch-authors-and-genres", ({ success, authors, genres }) => {
            if (success) {
                // Actualizar los selectores con las opciones recibidas
                ["#author", "#genre"].forEach((selector, index) =>
                    populateSelect(selector, [authors, genres][index], [selectedAuthor, selectedGenre][index])
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
    loadAuthorsAndGenres(selectedAuthor, selectedGenre);

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