// VARIABLES GLOBALES
var sedeSeleccionadaDesactivos = $("#filtroSedesDesactivos").val();
var pageDesactivos = 1;
var limitDesactivos = 50;
var totalRecordsDesactivos = 0;
var searchQueryDesactivos = $("#buscarSearchDataDesactivos").val();

// Función para actualizar la paginación
function actualizarPaginacionDesactivos() {
  const totalPagesDesactivos = Math.ceil(totalRecordsDesactivos / limitDesactivos);
  let paginationHtmlDesactivos = "";

  const maxVisiblePagesDesactivos = 5; // Número de páginas visibles antes de los puntos suspensivos

  // Mostrar botón de "Inicio"
  if (pageDesactivos > 1) {
    paginationHtmlDesactivos += `
      <li class="page-item">
        <button class="btn page-link" data-page="1">Inicio</button>
      </li>
    `;
  }

  // Mostrar las primeras páginas
  const startPageDesactivos = Math.max(pageDesactivos - Math.floor(maxVisiblePagesDesactivos / 2), 1);
  const endPageDesactivos = Math.min(startPageDesactivos + maxVisiblePagesDesactivos - 1, totalPagesDesactivos);

  // Si la página de inicio no es la primera página, mostrar "..." antes de las páginas
  if (startPageDesactivos > 1) {
    paginationHtmlDesactivos += `
      <li class="page-item">
        <button class="btn page-link" data-page="${1}">1</button>
      </li>
      <li class="page-item disabled">
        <span class="page-link">...</span>
      </li>
    `;
  }

  // Páginas del medio
  for (let i = startPageDesactivos; i <= endPageDesactivos; i++) {
    paginationHtmlDesactivos += `
      <li class="page-item">
        <button class="btn page-link" data-page="${i}">${i}</button>
      </li>
    `;
  }

  // Si la página final no es la última página, mostrar "..." después de las páginas
  if (endPageDesactivos < totalPagesDesactivos) {
    paginationHtmlDesactivos += `
      <li class="page-item disabled">
        <span class="page-link">...</span>
      </li>
      <li class="page-item">
        <button class="btn page-link" data-page="${totalPagesDesactivos}">${totalPagesDesactivos}</button>
      </li>
    `;
  }

  // Mostrar botón de "Fin"
  if (pageDesactivos < totalPagesDesactivos) {
    paginationHtmlDesactivos += `
      <li class="page-item">
        <button class="btn page-link" data-page="${totalPagesDesactivos}">Fin</button>
      </li>
    `;
  }

  // Insertar la paginación generada en el contenedor
  $("#paginationDesactivos").html(paginationHtmlDesactivos);

  // Marcar la página actual como activa
  $(".page-item").removeClass("active");
  $('.page-item button[data-page="' + pageDesactivos + '"]')
    .closest("li")
    .addClass("active");
}

// Delegación de evento para paginación
$("#paginationDesactivos").on("click", ".page-item button", function () {
  pageDesactivos = $(this).data("page");
  cargarUsuariosDesactivos();
});

// Delegación de evento para paginación
$("#paginationDesactivos").on("click", ".page-item button", function () {
  pageDesactivos = $(this).data("page");
  //console.log("Página seleccionada:", page);
  cargarUsuariosDesactivos();
});

async function cargarUsuariosDesactivos() {
  // Mover la variable aquí para que solo exista dentro de esta función
  let ajaxRequestDesactivos;

  // Obtener el valor de los filtros
  sedeSeleccionadaDesactivos = $("#filtroSedesDesactivos").val();
  searchQueryDesactivos = $("#buscarSearchDataDesactivos").val();

  const dataToSendDesactivos = {
    sedeSeleccionadaDesactivos,
    searchQueryDesactivos,
    pageDesactivos,
    limitDesactivos,
  };

  //console.log("Datos a enviar:", dataToSendDesactivos);

  // Cancelar la petición AJAX anterior si existe
  if (ajaxRequestDesactivos) {
    ajaxRequestDesactivos.abort();
  }

  try {
    ajaxRequestDesactivos = $.ajax({
      url: "../../controller/usuarios/usuariosController.php?accion=mostrarUsuariosAjaxDesactivos",
      method: "GET",
      data: dataToSendDesactivos,
    });

    const responseDesactivos = await ajaxRequestDesactivos;
    //console.log("Respuesta del servidor:", responseDesactivos);

    if (responseDesactivos && responseDesactivos.users) {
      const tbodyDesactivos = $("#data-bodyDesactivos");
      tbodyDesactivos.empty(); // Limpiar datos previos
      let resultadoCountDesactivos = $("#resultadoCountDesactivos");
      let counterDesactivos = (pageDesactivos - 1) * limitDesactivos + 1;

      responseDesactivos.users.forEach((user) => {
        tbodyDesactivos.append(generarFilaUsuarioDesactivos(user, counterDesactivos));
        counterDesactivos++;
      });

      totalRecordsDesactivos = responseDesactivos.total;

      let textoBusquedaDesactivos = searchQueryDesactivos ? ` para "${searchQueryDesactivos}"` : "";
      resultadoCountDesactivos.text(
        `Se encontró ${totalRecordsDesactivos} resultados${textoBusquedaDesactivos}`
      );
      actualizarPaginacionDesactivos();
    } else {
      $("#resultadoCountDesactivos").text("Se encontró 0 resultados");
    }
  } catch (error) {
    console.error("Error al cargar los usuarios:", error);
  }
}


 // Modificar el código donde se genera el botón de estado en la fila
function generarFilaUsuarioDesactivos(usuario, counterDesactivos) {
  const estadoClaseDesactivos = usuario.estado === 2 ? "success" : usuario.estado === 3 ? "danger" : "secondary";
  const estadoTextoDesactivos = usuario.estado === 2 ? "ACTIVO" : usuario.estado === 3 ? "INACTIVO" : "DESCONOCIDO";
  return `
      <tr class='bg-white align-middle text-center'>
        <td class='usuario_id' style='display: none;'>${usuario.idusuario}</td>
        <td><i class='fab fa-angular fa-lg me-1'></i>${counterDesactivos}</td>
        <td>${usuario.nombreusuario}</td>
        <td>${usuario.dni}</td>
        <td style='display: none;'>${usuario.correo}</td>
        <td>${usuario.celular}</td>
        <td>${usuario.sede}</td>
        <td>${formatearFechaVista(usuario.fechaingreso)}</td>
        <td>
          <button class="btn btn-${estadoClaseDesactivos}" style="width: 110px; opacity: 0.65;" title="Cambiar estado" onclick="handleEstadoChange(${usuario.idusuario}, ${usuario.estado})">
            ${estadoTextoDesactivos}
          </button>
        </td>
      </tr>
    `;
}

// Llamar a las funciones dentro de document.ready
$(document).ready(function () {
  let hasTextInputSearchUsuarioDesactivos= false;

  $("#filtroSedesDesactivos").on("change", function () {
    pageDesactivos = 1; // Resetea la página a 1
    cargarUsuariosDesactivos();
  });

  // Mostrar u ocultar el botón de búsqueda de colaboradores en función del texto
  $("#buscarSearchDataDesactivos").on("input", function () {
    const inputValueDesactivos = $(this).val().trim();

    if (inputValueDesactivos !== "") {
      $("#btnBuscarSearchDataDesactivos").show();
      hasTextInputSearchUsuarioDesactivos = true;
    } else {
      $("#btnBuscarSearchDataDesactivos").hide();
      if (hasTextInputSearchUsuarioDesactivos) {
        pageDesactivos = 1;
        cargarUsuariosDesactivos();
      }
    }
  });

    // Detectar clic en el botón de búsqueda por fecha
    $("#btnBuscarSearchDataDesactivos").on("click", function () {
      pageDesactivos = 1;
      cargarUsuariosDesactivos();
      $("#btnBuscarSearchDataDesactivos").hide();
    });
  cargarUsuariosDesactivos();

});
