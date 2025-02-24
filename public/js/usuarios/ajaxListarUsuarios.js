// VARIABLES GLOBALES
var sedeSeleccionada = $("#filtroSedes").val();
var page = 1;
var limit = 50;
var totalRecords = 0;
var searchQuery = $("#buscarSearchData").val();

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
  cargarUsuarios();
});

// Delegación de evento para paginación
$("#pagination").on("click", ".page-item button", function () {
  page = $(this).data("page");
  //console.log("Página seleccionada:", page);
  cargarUsuarios();
});

let ajaxRequest;
async function cargarUsuarios() {
  sedeSeleccionada = $("#filtroSedes").val();
  searchQuery = $("#buscarSearchData").val();

  const dataToSend = {
    sedeSeleccionada,
    searchQuery,
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
      url: "../../controller/usuarios/usuariosController.php?accion=mostrarUsuariosAjax",
      method: "GET",
      data: dataToSend,
    });

    const response = await ajaxRequest;
    //console.log("Respuesta del servidor:", response);

    if (response && response.users) {
      const tbody = $("#data-body");
      tbody.empty(); // Limpiar datos previos
      let resultadoCount = $("#resultadoCount");
      let counter = (page - 1) * limit + 1;

      response.users.forEach((user) => {
        tbody.append(generarFilaUsuario(user, counter));
        counter++;
      });

      totalRecords = response.total;

      let textoBusqueda = searchQuery? ` para "${searchQuery}"` : "";
      resultadoCount.text(
        `Se encontró ${totalRecords} resultados${textoBusqueda}`
      );
      actualizarPaginacion();
    } else {
      $("#resultadoCount").text("Se encontró 0 resultados");
    }
  } catch (error) {
    console.error("Error al cargar los usuarios:", error);
  }
}

 // Modificar el código donde se genera el botón de estado en la fila
function generarFilaUsuario(usuario, counter) {
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
        <td>${formatearFechaVista(usuario.fechaingreso)}</td>
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

// Llamar a las funciones dentro de document.ready
$(document).ready(function () {
  let hasTextInputSearchUsuario= false;

  $("#filtroSedes").on("change", function () {
    page = 1; // Resetea la página a 1
    cargarUsuarios();
  });

  // Mostrar u ocultar el botón de búsqueda de colaboradores en función del texto
  $("#buscarSearchData").on("input", function () {
    const inputValue = $(this).val().trim();

    if (inputValue !== "") {
      $("#btnBuscarSearchData").show();
      hasTextInputSearchUsuario = true;
    } else {
      $("#btnBuscarSearchData").hide();
      if (hasTextInputSearchUsuario) {
        page = 1;
        cargarUsuarios();
      }
    }
  });

    // Detectar clic en el botón de búsqueda por fecha
    $("#btnBuscarSearchData").on("click", function () {
      page = 1;
      cargarUsuarios();
      $("#btnBuscarSearchData").hide();
    });
  cargarUsuarios();

});
