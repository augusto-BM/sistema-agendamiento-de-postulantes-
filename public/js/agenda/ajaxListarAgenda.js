// VARIABLES GLOBALES
var page = 1;
var limit = 50;
var totalRecords = 0;

var idrolSesion = $("#idrolSesion").val();
var nombreUsuarioSesion = $("#nombreUsuarioSesion").val();
var filtroSedes = $("#filtroSedes").val();
var filtroEstado = $("#filtroEstado").val();
var filtroRecultador = $("#filtroRecultador").val();
var filtroInput = $("#buscarSearchData").val();

var nombreSedeSesion = $("#nombreSedeSesion").val();

var fechaActual = $("#fechaActual").val();
var filtroFecha = $("#filtroFecha").val();



// Función para actualizar la paginación
function actualizarPaginacion() {
  const totalPages = Math.ceil(totalRecords / limit);
  let paginationHtml = "";

  const maxVisiblePages = 5; // Número de páginas visibles antes de los puntos suspensivos

  // Mostrar botón de "Inicio"
  if (page > 1) {
    paginationHtml += `
      <li class="page-item">
        <button class="btn page-link" data-page="1">Inicio</button>
      </li>
    `;
  }

  // Mostrar las primeras páginas
  const startPage = Math.max(page - Math.floor(maxVisiblePages / 2), 1);
  const endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);

  // Si la página de inicio no es la primera página, mostrar "..." antes de las páginas
  if (startPage > 1) {
    paginationHtml += `
      <li class="page-item">
        <button class="btn page-link" data-page="${1}">1</button>
      </li>
      <li class="page-item disabled">
        <span class="page-link">...</span>
      </li>
    `;
  }

  // Páginas del medio
  for (let i = startPage; i <= endPage; i++) {
    paginationHtml += `
      <li class="page-item">
        <button class="btn page-link" data-page="${i}">${i}</button>
      </li>
    `;
  }

  // Si la página final no es la última página, mostrar "..." después de las páginas
  if (endPage < totalPages) {
    paginationHtml += `
      <li class="page-item disabled">
        <span class="page-link">...</span>
      </li>
      <li class="page-item">
        <button class="btn page-link" data-page="${totalPages}">${totalPages}</button>
      </li>
    `;
  }

  // Mostrar botón de "Fin"
  if (page < totalPages) {
    paginationHtml += `
      <li class="page-item">
        <button class="btn page-link" data-page="${totalPages}">Fin</button>
      </li>
    `;
  }

  // Insertar la paginación generada en el contenedor
  $("#pagination").html(paginationHtml);

  // Marcar la página actual como activa
  $(".page-item").removeClass("active");
  $('.page-item button[data-page="' + page + '"]')
    .closest("li")
    .addClass("active");
}

// Delegación de evento para paginación
$("#pagination").on("click", ".page-item button", function () {
  page = $(this).data("page");
  cargarAgendas();
});

// Delegación de evento para paginación
$("#pagination").on("click", ".page-item button", function () {
  page = $(this).data("page");
  //console.log("Página seleccionada:", page);
  cargarAgendas();
});

// Modificar el código donde se genera la fila de la agenda
function generarFilaAgenda(agenda, counter) {
  // Si no hay resultados, mostrar una fila con el mensaje "Sin resultados"
  if (!agenda || Object.keys(agenda).length === 0) {
    return `
      <tr>
        <td colspan="13" class="text-center">Sin resultados</td>
      </tr>
    `;
  }

  // Si hay datos, generar la fila normalmente
  return `
    <tr>
      <td style="display: none;">${agenda.idagenda}</td>
      <td><i class='fab fa-angular fa-lg me-1'></i>${counter}</td>
      <td>${formatearFechaVista(agenda.fecharegistro)}</td>
      <td>${agenda.postulante}</td>
      <td>${agenda.numerodocumento}</td>
      <td>${agenda.celular}</td>
      <td>${agenda.nombreusuario}</td>
      <td>${agenda.agenda ? agenda.agenda : "NO DEFINIDO"}</td>
      
      <td>${formatearFechaVista(agenda.fechaagenda)}</td>
      <td>
        <a href="#" class="btn-ver ms-0 abrirModal" data-id="verAgendas" data-titulo="Ver Agenda" data-prefix="Agenda/" data-identificador="${
          agenda.idagenda
        }">
          <i class="fa-solid fa-eye fa-lg" title="Ver" style="color: #19727A;"></i>
        </a>
        <a href="#" class="btn-editar ms-0 abrirModal" data-id="editarAgendas" data-titulo="Editar Agenda" data-prefix="Agenda/" data-identificador="${
          agenda.idagenda
        }">
          <i class="fa-solid fa-pen-to-square fa-lg" title="Editar" style="color: #19727A;"></i>
        </a>
      </td>
    </tr>
  `;
}

// Función para cargar agendas
let ajaxRequest;
async function cargarAgendas() {
  // Actualizar los valores de los filtros con los valores actuales del DOM
  filtroSedes = $("#filtroSedes").val();
  filtroEstado = $("#filtroEstado").val();
  filtroRecultador = $("#filtroRecultador").val();
  filtroInput = $("#buscarSearchData").val();

  const dataToSend = {
    idrolSesion,
    filtroSedes,
    filtroEstado,
    filtroRecultador,
    filtroInput,
    filtroNombreUsuarioSesion: nombreUsuarioSesion,
    fechaInicio: $("#fechaInicio").val(),
    fechaFin: $("#fechaFin").val(),
    page,
    limit,
  };

  //console.log("Datos a enviar:", dataToSend);

  // Cancelar la petición AJAX anterior si existe
  if (ajaxRequest) {
    ajaxRequest.abort();
  }

  try {
    ajaxRequest = $.ajax({
      url: "../../controller/agenda/agendaController.php?accion=AgendasAjax",
      method: "GET",
      data: dataToSend,
    });

    const response = await ajaxRequest;
    //console.log("Respuesta del servidor:", response);

    if (response && response.agendas) {
      const tbody = $("#data-body");
      tbody.empty(); // Limpiar datos previos
      let resultadoCount = $("#resultadoCountAgenda");
      let counter = (page - 1) * limit + 1;

      response.agendas.forEach((agenda) => {
        tbody.append(generarFilaAgenda(agenda, counter));
        counter++;
      });

      totalRecords = response.total;

      let textoBusqueda = filtroInput ? ` para "${filtroInput}"` : "";
      resultadoCount.text(
        `Se encontró ${totalRecords} resultados${textoBusqueda}`
      );
      actualizarPaginacion();
    } else {
      $("#resultadoCountAgenda").text("Se encontró 0 resultados");
    }
  } catch (error) {
    console.error("Error al cargar las agendas:", error);
  }
}

$(document).ready(function () {
  let hasTextInputSearchAgenda = false;

  // SI ES MODERADOR APARECE SELECCIONADO EN NOMBRE DE SU SEDE Y NO PUEDE CAMBIARLO
  if (idrolSesion == 2) {
    $("#filtroSedes").val(nombreSedeSesion).prop("disabled", true);
  }

  $("#filtroSedes, #filtroEstado, #filtroRecultador").on("change", function () {
    if ($("#filtroSedes").prop("disabled") === false) {
      // Solo resetea la página si el filtro no está deshabilitado
      page = 1; // Resetea la página a 1
      cargarAgendas();
    }
  });

  // Mostrar u ocultar el botón de búsqueda por fecha
  $("#fechaInicio, #fechaFin").on("input change", function () {
    if ($("#fechaInicio").val() && $("#fechaFin").val()) {
      $("#btn_buscarAgendaFiltros").show();
    } else {
      $("#btn_buscarAgendaFiltros").hide();
    }

    if ($("#fechaInicio").val() && $("#fechaFin").val()) {
      $('#filtroFecha option[value=""]').show();
      $("#filtroFecha").val("");
    }
  });

  // Mostrar u ocultar el botón de búsqueda de colaboradores en función del texto
  $("#buscarSearchData").on("input", function () {
    const inputValue = $(this).val().trim();

    if (inputValue !== "") {
      $("#btn_buscarAgendaFiltros").show();
      hasTextInputSearchAgenda = true;
    } else {
      $("#btn_buscarAgendaFiltros").hide();
      if (hasTextInputSearchAgenda) {
        page = 1;
        cargarAgendas();
      }
    }
  });

  // Detectar clic en el botón de búsqueda por fecha
  $("#btn_buscarAgendaFiltros").on("click", function () {
    page = 1;
    cargarAgendas();
    $("#btn_buscarAgendaFiltros").hide();
  });

  // Evento para manejar cambios en el filtro de fecha
  $("#filtroFecha").on("change", function () {
    var filtro = $(this).val();
    if (filtro !== "") {
      $('#filtroFecha option[value=""]').hide();
    }

    $("#fechaInicio").val(filtro);

    $("#btn_buscarAgendaFiltros").hide();
    page = 1;
    cargarAgendas();
  });

  $("#fechaInicio").val(filtroFecha);
  $("#fechaFin").val(fechaActual);

  cargarAgendas();
});
