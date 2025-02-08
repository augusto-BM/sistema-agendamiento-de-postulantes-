$(document).on('click', '.abrirModalCalendario', function(event) {
    event.preventDefault();
    
    // Obtener el ID, el título y el prefijo desde los atributos 'data-id', 'data-titulo' y 'data-prefix' del botón
    var modalId = $(this).data("id");
    var prefix = $(this).data("prefix");
    var modalTitle = $(this).data("titulo");

    // Actualizar el título del modal con el prefijo dinámico y el título
    $("#modalCalendarioLabel").html(
      '<span class="fw-light">' + prefix + "</span> " + modalTitle
    );

    // Hacer la petición Ajax con el modalId para cargar el contenido correspondiente
    $.ajax({
      url: "../calendario/" + modalId + ".php",
      type: "GET",
      success: function (response) {
        // Cargar el contenido recibido en el cuerpo del modal
        $("#modalCalendario .modal-body").html(response);

        // Mostrar el modal automáticamente
        $("#modalCalendario").modal("show");
      },
      error: function () {
        alert("Hubo un error al cargar el contenido.");
      },
    });
  });

$("#modalCalendario").on("hidden.bs.modal", function () {
  // Limpiar el contenido de la modal para liberar recursos
  $("#modalCalendario .modal-body").empty();
});
