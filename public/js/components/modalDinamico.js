//console.log("modal dinámico cargado");
$(document).on("click", ".abrirModal", function (event) {
  event.preventDefault();
  //console.log("Botón de modal clickeado");

  // Obtener los datos del botón
  var modalId = $(this).data("id"); // Este es el nombre del archivo donde está el contenido del modal
  var prefix = $(this).data("prefix");
  var modalTitle = $(this).data("titulo");
  var identificador = $(this).data("identificador"); // Datos adicionales si son necesarios

  // Actualizar el título del modal dinámicamente
  $("#modalDinamicoLabel").html(
    '<span class="fw-light">' + prefix + "</span> " + modalTitle
  );

  // Ruta del nombre del archivo que contiene el contenido del modal
  var url = modalId + ".php"; 
 
  // Datos adicionales que se enviarán si el identificador existe
  var dataToSend = identificador ? { identificador: identificador } : {};

  // Hacer la petición Ajax para cargar el contenido en el modal dinámico
  $.ajax({
    url: url, 
    type: "GET",
    data: dataToSend,
    success: function (response) {
      // Cargar el contenido recibido en el cuerpo del modal
      $("#modalDinamico .modal-body").html(response);

      // Mostrar el modal dinámicamente
      var myModal = new bootstrap.Modal(
        document.getElementById("modalDinamico")
      );
      myModal.show();
    },
    error: function () {
      alert("Hubo un error al cargar el contenido.");
    },
  });
});

// Limpiar el contenido del modal cuando se cierre
$("#modalDinamico").on("hidden.bs.modal", function () {

  //console.log("Modal cerrado");
  // Vaciar el contenido HTML del modal (esto incluye tablas, scripts, formularios, etc.)
  $("#modalDinamico .modal-body").empty();

  // Eliminar cualquier script cargado dinámicamente
  $("#modalDinamico script").remove();

  // Limpiar formularios 
  $("#modalDinamico form").trigger("reset"); // Resetea todos los campos del formulario

  // Limpiar entradas específicas
  $(
    "#modalDinamico .modal-body input, #modalDinamico .modal-body textarea"
  ).val("");

  // Si tienes tablas, puedes eliminarlas explícitamente (aunque `empty()` también las elimina)
  $("#modalDinamico .modal-body table").remove();

  // Obtener la instancia del modal y destruirla para evitar acumulación de instancias
  var myModal = bootstrap.Modal.getInstance(
    document.getElementById("modalDinamico")
  );
  myModal.dispose(); // Limpia la instancia para evitar conflictos si el modal se vuelve a abrir
});
