let fechaAsignadaNext; // Variable para almacenar la fecha asignada next
let ajaxRequestTop5Diario;
let ajaxRequestTop5AsistenciaDiario;
let idRolSesion = $("#idRolSesion").val();


// Función asíncrona para obtener las fechas no laborables
async function obtenerFechasNoLaborables() {
  try {
    const response = await $.ajax({
      url: "../calendario/fechasCalendario.json", // Ruta al archivo JSON
      type: "GET",
      dataType: "json",
    });
    return response; // Devuelve las fechas no laborables
  } catch (error) {
    console.error("Error al obtener las fechas no laborables: ", error);
    throw new Error("No se pudo cargar el archivo de fechas no laborables");
  }
}

// Función para obtener el siguiente día laborable
function getSiguienteLaborable(fechaInicio, fechasNoLaborablesSet) {
  let siguienteFecha = new Date(fechaInicio);

  while (true) {
    siguienteFecha.setDate(siguienteFecha.getDate() + 1); // Avanzar al siguiente día
    const siguienteFechaStr = formatearFecha(siguienteFecha);

    if (
      !fechasNoLaborablesSet.has(siguienteFechaStr) &&
      siguienteFecha.getDay() !== 0
    ) {
      return siguienteFecha;
    }
  }
}

// Función para obtener la fecha asignada y hacerla accesible
async function obtenerFechaAsignada() {
  try {
    const fechasNoLaborables = await obtenerFechasNoLaborables();
    const fechaLima = obtenerFechaLima();

    let manana = new Date(fechaLima);
    manana.setHours(0, 0, 0, 0);
    manana.setDate(manana.getDate() + 1); // Sumar un día
    const mananaStr = formatearFecha(manana);

    const fechasNoLaborablesSet = new Set(
      fechasNoLaborables.map((fecha) =>
        formatearFecha(new Date(fecha.year, fecha.month - 1, fecha.day))
      )
    );

    if (!fechasNoLaborablesSet.has(mananaStr)) {
      fechaAsignadaNext = mananaStr;
    } else {
      const siguienteLaborable = getSiguienteLaborable(
        manana,
        fechasNoLaborablesSet
      );
      const siguienteLaborableStr = formatearFecha(siguienteLaborable);
      fechaAsignadaNext = siguienteLaborableStr;
    }
  } catch (error) {
    console.error("Error al cargar las fechas no laborables:", error);
  }
}

//Función para cargar los usuarios top 5 diarios
async function cargarUsuariosTop5Diario() {
  sedeSeleccionada = $("#filtroSedes").val();

  await obtenerFechaAsignada();
  //console.log(fechaAsignadaNext);

  const dataToSend = {
    sedeSeleccionada,
    fechaAsignadaNext,
  };

  //console.log("Datos a enviar:", dataToSend);

  // Cancelar la petición AJAX anterior si existe
  if (ajaxRequestTop5Diario) {
    ajaxRequestTop5Diario.abort();
  }

  try {
    ajaxRequestTop5Diario = $.ajax({
      url: "../../controller/inicio/inicioController.php?accion=mostrarTop5DiariosAjax",
      method: "GET",
      data: dataToSend,
    });

    const response = await ajaxRequestTop5Diario;
    //console.log("Respuesta del servidor:", response);

    const tbody = $("#data-bodyAgendados");
    tbody.empty();

    if (
      response &&
      response.usersTop5Diario &&
      response.usersTop5Diario.length > 0
    ) {
      response.usersTop5Diario.forEach((user, contador) => {
        tbody.append(generarFilaUsuarioTop5Diario(user, contador));
      });
    } else {
      //console.log("No se encontraron usuarios.");
      tbody.append(`
        <tr class='bg-white align-middle text-center'>
          <td colspan='3'>${response.mensaje || "0 resultados"}</td>
        </tr>
      `);
    }
  } catch (error) {
    console.error("Error al cargar los usuarios:", error);
  }
}

// Función para generar la fila de los usuario en el top 5 diario
function generarFilaUsuarioTop5Diario(usuario, contador) {
  return `
      <tr class='bg-white align-middle text-center'>
        <td class='usuario_id' style='display: none;'>${usuario.idusuario}</td>
        <td><i class='fab fa-angular fa-lg me-1'></i>${contador + 1}</td>
        <td>${usuario.nombreusuario}</td>
        <td>${usuario.contar}</td>
      </tr>
    `;
}

//Función para cargar los usuarios top 5 de asistencias diarios
async function cargarUsuariosTop5AsistenciaDiario() {
  sedeSeleccionada = $("#filtroSedes").val();
  fechaHoy = $("#fecha-hoy").val();

  const dataToSend = {
    sedeSeleccionada,
    fechaHoy,
  };

  //console.log("Datos a enviar:", dataToSend);

  // Cancelar la petición AJAX anterior si existe
  if (ajaxRequestTop5AsistenciaDiario) {
    ajaxRequestTop5AsistenciaDiario.abort();
  }

  try {
    ajaxRequestTop5AsistenciaDiario = $.ajax({
      url: "../../controller/inicio/inicioController.php?accion=mostrarTop5AsistenciaDiarioAjax",
      method: "GET",
      data: dataToSend,
    });

    const response = await ajaxRequestTop5AsistenciaDiario;
    //console.log("Respuesta del servidor:", response);

    const tbody = $("#data-bodyAsistencia");
    tbody.empty();

    if (
      response &&
      response.usersTop5AsistenciaDiario &&
      response.usersTop5AsistenciaDiario.length > 0
    ) {
      response.usersTop5AsistenciaDiario.forEach((user, contador) => {
        tbody.append(generarFilaUsuarioTop5AsistenciaDiario(user, contador));
      });
    } else {
      //console.log("No se encontraron usuarios.");
      tbody.append(`
        <tr class='bg-white align-middle text-center'>
          <td colspan='3'>${response.mensaje || "0 resultados"}</td>
        </tr>
      `);
    }
  } catch (error) {
    console.error("Error al cargar los usuarios:", error);
  }
}

// Función para generar la fila de los usuario en el top 5 de asistencias diario
function generarFilaUsuarioTop5AsistenciaDiario(usuario, contador) {
  return `
      <tr class='bg-white align-middle text-center'>
        <td class='usuario_id' style='display: none;'>${usuario.idusuario}</td>
        <td><i class='fab fa-angular fa-lg me-1'></i>${contador + 1}</td>
        <td>${usuario.nombreusuario}</td>
        <td>${usuario.contara}</td>
      </tr>
    `;
}


