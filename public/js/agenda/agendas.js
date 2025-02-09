//console.log("JS de agendas");

$(document).ready(function () {
  // Función para filtrar las asistencias por las fechas seleccionadas
  function filtrarAsistencias() {
    var fechaInicio = $("#fechaInicio").val();
    var fechaFin = $("#fechaFin").val();

    if (!fechaInicio || !fechaFin) {
      alert("Por favor, selecciona ambas fechas antes de filtrar.");
      return;
    }

    var searchQuery = $("#buscarSearchData").val().trim(); // Capturamos la búsqueda por colaborador, empresa, o cargo

    $.ajax({
      url: "datosReporte.php",
      method: "GET",
      data: {
        fechaInicio: fechaInicio,
        fechaFin: fechaFin,
        searchQuery: searchQuery, // Enviamos la consulta de búsqueda al backend
      },
      success: function (response) {
        arrrayTodaLaData = JSON.parse(response); // Almacenar todos los datos
        totalResultadoData = arrrayTodaLaData.length; // Total de resultados
        mostrarPagina(paginaActual); // Mostrar los datos de la página actual
        actualizarResultadoCount(); // Actualizar el mensaje de cantidad de resultados
      },
      error: function (xhr, status, error) {
        console.error("Error en la solicitud AJAX: ", status, error);
      },
    });
  }

  // Filtrar por el selector de fechas
  $("#filtroFecha").on("change", function () {
    var filtro = $(this).val();

    // Si se selecciona cualquier opción diferente de "Fecha Personalizada", se oculta la opción "Fecha Personalizada"
    if (filtro !== "") {
      $('#filtroFecha option[value=""]').hide();
    }

    // Cambiar las fechas según el filtro seleccionado
    switch (filtro) {
      case "hoy":
        var hoy = obtenerFechaLima();
        $("#fechaInicio").val(formatearFecha(hoy));
        $("#fechaFin").val(formatearFecha(hoy));
        break;
      case "ayer":
        var ayer = obtenerFechaLima();
        ayer.setDate(ayer.getDate() - 1);
        $("#fechaInicio").val(formatearFecha(ayer));
        $("#fechaFin").val(formatearFecha(ayer));
        break;
      case "semana":
        var inicioSemana = obtenerFechaLima();
        inicioSemana.setDate(inicioSemana.getDate() - 7);
        $("#fechaInicio").val(formatearFecha(inicioSemana));
        $("#fechaFin").val(formatearFecha(obtenerFechaLima()));
        break;
      case "mes":
        var mes = obtenerFechaLima();
        mes.setMonth(mes.getMonth() - 1);
        $("#fechaInicio").val(formatearFecha(mes));
        $("#fechaFin").val(formatearFecha(obtenerFechaLima()));
        break;
      case "tresMeses":
        var tresMeses = obtenerFechaLima();
        tresMeses.setMonth(tresMeses.getMonth() - 3);
        $("#fechaInicio").val(formatearFecha(tresMeses));
        $("#fechaFin").val(formatearFecha(obtenerFechaLima()));
        break;
      case "seisMeses":
        var seisMeses = obtenerFechaLima();
        seisMeses.setMonth(seisMeses.getMonth() - 6);
        $("#fechaInicio").val(formatearFecha(seisMeses));
        $("#fechaFin").val(formatearFecha(obtenerFechaLima()));
        break;
      case "doceMeses":
        var doceMeses = obtenerFechaLima();
        doceMeses.setMonth(doceMeses.getMonth() - 12);
        $("#fechaInicio").val(formatearFecha(doceMeses));
        $("#fechaFin").val(formatearFecha(obtenerFechaLima()));
        break;
      default:
        $("#fechaInicio").val("");
        $("#fechaFin").val("");
        break;
    }

    // Al cambiar el filtro en el select, se oculta el botón de búsqueda
    $("#btnBuscarFecha").hide();
    filtrarAsistencias();
  });

  // Establecer la fecha de hoy como valor predeterminado al cargar la página
  var mes = obtenerFechaLima();
  mes.setMonth(mes.getMonth() - 1);
  $("#fechaInicio").val(formatearFecha(mes));
  $("#fechaFin").val(formatearFecha(obtenerFechaLima()));
});
