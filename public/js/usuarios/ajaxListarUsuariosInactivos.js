// VARIABLES GLOBALES
var sedeSeleccionadaInactivos = $("#filtroSedesInactivos").val();
var pageInactivos = 1;
var limitinactivos = 50; 
var totalRecordsInactivos = 0;

// Modificar el código donde se genera el botón de estado en la fila
function generarFilaUsuarioInactivos(usuario, counter) {
  const estadoClase = usuario.estado === 2 ? "success" : usuario.estado === 3 ? "danger" : "secondary";
  const estadoTexto = usuario.estado === 2 ? "ACTIVO" : usuario.estado === 3 ? "INACTIVO" : "DESCONOCIDO";
  return `
    <tr class='bg-white align-middle text-center'>
        <td class='usuario_id' style='display: none;'>${usuario.idusuario}</td>
        <td><i class='fab fa-angular fa-lg me-1'></i>${counter}</td>
        <td>${usuario.nombreusuario}</td>
        <td>${usuario.dni}</td>
        <td style='display: none;'>${usuario.correo}</td>
        <td>${usuario.celular}</td>
        <td>${usuario.sede}</td>
        <td>${usuario.fechaingreso}</td>
        <td>
          <button class="btn btn-${estadoClase}" style="width: 110px; opacity: 0.65;" title="Cambiar estado" onclick="handleEstadoChange(${usuario.idusuario}, ${usuario.estado})">
            ${estadoTexto}
          </button>
        </td>
        <td scope="col" style='display: none;'>
            <a href="" class="btn-ver ms-0 abrirModal" data-id="verUsuarios" data-titulo="Ver Usuario" data-prefix="Usuarios/" data-identificador="${usuario.idusuario}"><i class="fa-solid fa-eye fa-lg" title="Ver" style="color: #19727A;"></i></a>
            <a href="" class="btn-editar ms-0 abrirModal" data-id="editarUsuarios" data-titulo="Editar Usuario" data-prefix="Usuarios/" data-identificador="${usuario.idusuario}"><i class="fa-solid fa-pen-to-square fa-lg" title="Editar" style="color: #19727A;"></i></a>
        </td>
    </tr>
  `;
}

// Función para cargar usuarios o buscar según query
function cargarUsuariosInactivos(sedeId, pageInactivos, limitinactivos, query = '') {
    //console.log("Cargando usuarios con parámetros:", filtroSedesInactivos, page, limitinactivos);
    const url = query 
        ? "../../controller/usuarios/usuariosController.php?accion=buscarUsuariosInactivosAjax" 
        : "../../controller/usuarios/usuariosController.php?accion=mostrarUsuariosInactivosAjax";

    $.ajax({
      url: url,
      type: "GET",
      data: { query: query, idempresa: sedeId, page: pageInactivos, limit: limitinactivos },
      dataType: "json",
      success: function (data) {
        let tableBody = $("#data-bodyDesactivos");
        tableBody.empty();
        let resultadoCountDesactivos = $("#resultadoCountDesactivos");
        let busquedaRealizadaUsuariosDesactivos = $("#busquedaRealizadaUsuariosDesactivos");

        if (Array.isArray(data.users) && data.users.length > 0) {
          let counter = (pageInactivos - 1) * limitinactivos + 1;

          data.users.forEach(function (usuario) {
            tableBody.append(generarFilaUsuarioInactivos(usuario, counter));
            counter++;
          });

          totalRecordsInactivos = data.total;
          resultadoCountDesactivos.text(query ? `Se encontró ${totalRecordsInactivos} resultados de "${query}"` : `Se encontró ${totalRecordsInactivos} resultados`);
          busquedaRealizadaUsuariosDesactivos.text(query ? `Resultados de búsqueda para "${query}"` : "");
          actualizarPaginacionInactivos();
        } else {
          tableBody.append('<tr><td colspan="9" class="text-center">No se encontraron resultados.</td></tr>');
          resultadoCountDesactivos.text(query ? `Se encontró 0 resultados de "${query}"` : "Se encontró 0 resultados");
          busquedaRealizadaUsuariosDesactivos.text(query ? `No se encontraron resultados para "${query}"` : "");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        console.log("Error en la solicitud AJAX.");
        console.log("Estado de la solicitud:", textStatus);
        console.log("Error:", errorThrown);
        console.log("Respuesta del servidor:", jqXHR.responseText);
        alert("Hubo un error al listar los datos del usuario.");
      }
    });
}

// Función para actualizar la paginación
function actualizarPaginacionInactivos() {
    const totalPages = Math.ceil(totalRecordsInactivos / limitinactivos);
    let paginationHtml = "";

    for (let i = 1; i <= totalPages; i++) {
      paginationHtml += `
        <li class="page-item">
            <button class="btn page-link" data-page="${i}">${i}</button>
        </li>
      `;
    }

    $("#paginationDesactivos").html(paginationHtml);

    $(".page-item").removeClass("active");
    $('.page-item button[data-page="' + pageInactivos + '"]')
      .closest("li")
      .addClass("active");

    $(".page-item button").on("click", function () {
        pageInactivos = $(this).data("page");
        manejarBusquedaYCargaUsuariosInactivos();
    });
}

// Función para manejar la búsqueda y carga de usuarios
function manejarBusquedaYCargaUsuariosInactivos() {
  const searchQuery = $("#buscarSearchDataInactivos").val().trim();
  const sedeId = $("#filtroSedesInactivos").val();
  if (searchQuery.length > 0) {
    cargarUsuariosInactivos(sedeId, pageInactivos, limitinactivos, searchQuery);
  } else {
    cargarUsuariosInactivos(sedeId, pageInactivos, limitinactivos);
  }
}

// Función para manejar el cambio en el filtro de sede
function handleSedeChangeInactivos() {
    pageInactivos = 1;
    manejarBusquedaYCargaUsuariosInactivos();
}

// Función para manejar la búsqueda de usuarios
function handleSearchInputInactivos() {
    const searchText = $(this).val().trim();
    if (searchText.length > 0) {
      $("#btnBuscarSearchDataInactivos").show();
    } else {
      $("#btnBuscarSearchDataInactivos").hide();
      manejarBusquedaYCargaUsuariosInactivos();
    }
}

// Función para manejar la acción del botón de búsqueda
function handleSearchButtonClickInactivos() {
    manejarBusquedaYCargaUsuariosInactivos();
}

// Llamar a las funciones dentro de document.ready
$(document).ready(function () {
  //console.log("Document ready Inactivos");
  cargarUsuariosInactivos(sedeSeleccionadaInactivos, pageInactivos, limitinactivos);
  // Eventos de búsqueda y filtros
  $("#buscarSearchDataInactivos").on("input", handleSearchInputInactivos);
  $("#btnBuscarSearchDataInactivos").on("click", handleSearchButtonClickInactivos);
  $("#filtroSedesInactivos").on("change", handleSedeChangeInactivos);
});


