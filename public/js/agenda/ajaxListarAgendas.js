console.log("JS de agendas 2");

// VARIABLES GLOBALES
var page = 1;
var limit = 50;
var totalRecords = 0;

var idrolSesion = $("#idrolSesion").val();
var nombreUsuarioSesion = $("#nombreUsuarioSesion").val();
var filtroSedes = $("#filtroSedes").val();
var filtroEstado = $("#filtroEstado").val();
var filtroRecultador = $("#filtroRecultador").val();

// Función para actualizar las fechas según el filtro
function actualizarFechas(filtro) {
  var fechaInicio, fechaFin;
  var fechaActual = obtenerFechaLima();

  // Cambiar las fechas según el filtro seleccionado
  switch (filtro) {
    case "hoy":
      fechaInicio = fechaActual;
      fechaFin = fechaActual;
      break;
    case "ayer":
      fechaActual.setDate(fechaActual.getDate() - 1);
      fechaInicio = fechaActual;
      fechaFin = fechaActual;
      break;
    case "semana":
      fechaActual.setDate(fechaActual.getDate() - 7);
      fechaInicio = fechaActual;
      fechaFin = obtenerFechaLima();
      break;
    case "mes":
      fechaActual.setMonth(fechaActual.getMonth() - 1);
      fechaInicio = fechaActual;
      fechaFin = obtenerFechaLima();
      break;
    case "tresMeses":
      fechaActual.setMonth(fechaActual.getMonth() - 3);
      fechaInicio = fechaActual;
      fechaFin = obtenerFechaLima();
      break;
    case "seisMeses":
      fechaActual.setMonth(fechaActual.getMonth() - 6);
      fechaInicio = fechaActual;
      fechaFin = obtenerFechaLima();
      break;
    case "doceMeses":
      fechaActual.setMonth(fechaActual.getMonth() - 12);
      fechaInicio = fechaActual;
      fechaFin = obtenerFechaLima();
      break;
    default:
      fechaInicio = null;
      fechaFin = null;
      break;
  }

  // Asignar las fechas a los campos
  if (fechaInicio && fechaFin) {
    $("#fechaInicio").val(formatearFecha(fechaInicio));
    $("#fechaFin").val(formatearFecha(fechaFin));
  } else {
    $("#fechaInicio").val("");
    $("#fechaFin").val("");
  }
}

$(document).ready(function () {
  // Filtrar por el selector de fechas
  $("#filtroFecha").on("change", function () {
    var filtro = $(this).val();

    // Si se selecciona cualquier opción diferente de "Fecha Personalizada", se oculta la opción "Fecha Personalizada"
    if (filtro !== "") {
      $('#filtroFecha option[value=""]').hide();
    }

    actualizarFechas(filtro);
    $("#btnBuscarFecha").hide();
  });

  // Establecer la fecha de hoy como valor predeterminado al cargar la página
  var fechaPredeterminada = obtenerFechaLima();
  fechaPredeterminada.setMonth(fechaPredeterminada.getMonth() - 1);
  $("#fechaInicio").val(formatearFecha(fechaPredeterminada));
  $("#fechaFin").val(formatearFecha(obtenerFechaLima()));

  console.log("Fecha inicio: " + $("#fechaInicio").val());
  console.log("Fecha fin: " + $("#fechaFin").val());

  // Validar y asignar null a los filtros si es igual a "TODOS"
  if (filtroSedes === "TODOS") {
    filtroSedes = null;
  }
  if (filtroEstado === "TODOS") {
    filtroEstado = null;
  }
  if (filtroRecultador === "TODOS") {
    filtroRecultador = null;
  }

  // Si el rol es 2 o 4, entonces nombreUsuario debe ser null
  if (idrolSesion == 2 || idrolSesion == 4) {
    nombreUsuarioSesion = null;
  }

  // Verificar los valores que se enviarán en la solicitud
  var dataToSend = {
    idrolSesion: idrolSesion,
    filtroSedes: filtroSedes === "TODOS" ? null : filtroSedes, // Asegúrate de enviar null
    filtroEstado: filtroEstado === "TODOS" ? null : filtroEstado,
    filtroRecultador: filtroRecultador === "TODOS" ? null : filtroRecultador,
    filtroNombreUsuarioSesion: nombreUsuarioSesion,
    fechaInicio: $("#fechaInicio").val(),
    fechaFin: $("#fechaFin").val(),
    page: page,
    limit: limit,
  };

  // Mostrar los datos en consola
  console.log("Datos que se enviarán:", dataToSend);

  // Realizar la solicitud AJAX para enviar los datos
  $.ajax({
    url: "../../controller/agenda/agendaController.php?accion=AgendasAjax", // Reemplaza con la URL a la que deseas enviar los datos
    method: "GET",
    data: dataToSend,
    success: function (response) {
      console.log("Datos enviados correctamente");
      // Aquí puedes manejar la respuesta si es necesario
      console.log(response);
    },
    error: function (xhr, status, error) {
      console.log("Error al enviar los datos: " + error);
    },
  });
});
