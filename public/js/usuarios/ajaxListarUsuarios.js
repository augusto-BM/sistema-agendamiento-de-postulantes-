// VARIABLES GLOBALES
var sedeSeleccionada = $("#filtroSedes").val();
var page = 1;
var limit = 50;
var totalRecords = 0;

// Modificar el código donde se genera el botón de estado en la fila
function generarFilaUsuario(usuario, counter) {
  const estadoClase =
    usuario.estado === 2
      ? "success"
      : usuario.estado === 3
      ? "danger"
      : "secondary";
  const estadoTexto =
    usuario.estado === 2
      ? "ACTIVO"
      : usuario.estado === 3
      ? "INACTIVO"
      : "DESCONOCIDO";
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
          <button class="btn btn-${estadoClase}" style="width: 100px; opacity: 0.65;" title="Cambiar estado" onclick="handleEstadoChange(${usuario.idusuario}, ${usuario.estado})">
            ${estadoTexto}
          </button>
        </td>
        <td scope="col">
            <a href="" class="btn-ver ms-0 abrirModal" data-id="verUsuarios" data-titulo="Ver Usuario" data-prefix="Usuarios/" data-identificador="${usuario.idusuario}">
              <i class="fa-solid fa-eye fa-lg" title="Ver" style="color: #19727A;"></i>
            </a>
            <a href="" class="btn-editar ms-0 abrirModal" data-id="editarUsuarios" data-titulo="Editar Usuario" data-prefix="Usuarios/" data-identificador="${usuario.idusuario}">
              <i class="fa-solid fa-pen-to-square fa-lg" title="Editar" style="color: #19727A;"></i>
            </a>
        </td>
      </tr>
    `;
}

// Función para cargar usuarios o buscar según query
async function cargarUsuarios(sedeId, page, limit, query = "") {
  const url = query
    ? "../../controller/usuarios/usuariosController.php?accion=buscarUsuariosAjax"
    : "../../controller/usuarios/usuariosController.php?accion=mostrarUsuariosAjax";

  try {
    // Realizar la solicitud con fetch
    const response = await fetch(
      `${url}&query=${query}&idempresa=${sedeId}&page=${page}&limit=${limit}`
    );

    // Verificar si la respuesta fue exitosa
    if (!response.ok) {
      throw new Error("Error en la solicitud");
    }

    const data = await response.json(); // Parseamos la respuesta como JSON

    let tableBody = $("#data-body");
    tableBody.empty();
    let resultadoCount = $("#resultadoCount");
    let busquedaRealizadaUsuarios = $("#busquedaRealizadaUsuarios");

    if (Array.isArray(data.users) && data.users.length > 0) {
      let counter = (page - 1) * limit + 1;

      data.users.forEach(function (usuario) {
        tableBody.append(generarFilaUsuario(usuario, counter));
        counter++;
      });

      totalRecords = data.total;
      resultadoCount.text(
        query
          ? `Se encontró ${totalRecords} resultados de "${query}"`
          : `Se encontró ${totalRecords} resultados`
      );
      busquedaRealizadaUsuarios.text(
        query ? `Resultados de búsqueda para "${query}"` : ""
      );
      actualizarPaginacion();
    } else {
      tableBody.append(
        '<tr><td colspan="9" class="text-center">No se encontraron resultados.</td></tr>'
      );
      resultadoCount.text(
        query
          ? `Se encontró 0 resultados de "${query}"`
          : "Se encontró 0 resultados"
      );
      busquedaRealizadaUsuarios.text(
        query ? `No se encontraron resultados para "${query}"` : ""
      );
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
    alert("Hubo un error al listar los datos del usuario.");
  }
}

// Función para actualizar la paginación
function actualizarPaginacion() {
  const totalPages = Math.ceil(totalRecords / limit);
  let paginationHtml = "";

  for (let i = 1; i <= totalPages; i++) {
    paginationHtml += `
        <li class="page-item">
            <button class="btn page-link" data-page="${i}">${i}</button>
        </li>
      `;
  }

  $("#pagination").html(paginationHtml);
  $(".page-item").removeClass("active");
  $('.page-item button[data-page="' + page + '"]')
    .closest("li")
    .addClass("active");

  $(".page-item button").on("click", function () {
    page = $(this).data("page");
    manejarBusquedaYCargaUsuarios();
  });
}

// Función para manejar la búsqueda y carga de usuarios
function manejarBusquedaYCargaUsuarios() {
  const searchQuery = $("#buscarSearchData").val().trim();
  const sedeId = $("#filtroSedes").val();
  if (searchQuery.length > 0) {
    cargarUsuarios(sedeId, page, limit, searchQuery);
  } else {
    cargarUsuarios(sedeId, page, limit);
  }
}

// Función para manejar el cambio en el filtro de sede
function handleSedeChange() {
  page = 1;
  manejarBusquedaYCargaUsuarios();
}

// Función para manejar la búsqueda de usuarios
function handleSearchInput() {
  const searchText = $(this).val().trim();
  if (searchText.length > 0) {
    $("#btnBuscarSearchData").show();
  } else {
    $("#btnBuscarSearchData").hide();
    manejarBusquedaYCargaUsuarios();
  }
}

// Función para manejar la acción del botón de búsqueda
function handleSearchButtonClick() {
  manejarBusquedaYCargaUsuarios();
}

// Llamar a las funciones dentro de document.ready
$(document).ready(function () {
  cargarUsuarios(sedeSeleccionada, page, limit);
  // Eventos de búsqueda y filtros
  $("#buscarSearchData").on("input", handleSearchInput);
  $("#btnBuscarSearchData").on("click", handleSearchButtonClick);
  $("#filtroSedes").on("change", handleSedeChange);
});
