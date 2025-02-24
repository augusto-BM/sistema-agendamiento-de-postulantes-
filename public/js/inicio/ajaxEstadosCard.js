let ajaxRequestCardEstadosAgendas = null;
let ajaxRequestCardAgendadosRedes = null;

async function cargarCardEstadosAgendas() {
  fechaHoy = $("#fecha-hoy").val();
  sedeSeleccionada = $("#filtroSedes").val();
  turnoSeleccionado = $("#turno").val();
  idUsuarioSesion = $("#idUsuarioSesion").val();

  await obtenerFechaAsignada(); // Asegúrate de que esta función obtenga la fecha correctamente
  /* console.log(fechaAsignadaNext);
  console.log(fechaHoy);
  console.log(sedeSeleccionada);
  console.log(turnoSeleccionado);
  console.log(idRolSesion);
  console.log(idUsuarioSesion); */

  const dataToSend = {
    fechaAsignadaNext,
    fechaHoy,
    sedeSeleccionada,
    turnoSeleccionado,
    idRolSesion,
    idUsuarioSesion,
  };

  if (ajaxRequestCardEstadosAgendas) {
    ajaxRequestCardEstadosAgendas.abort();
  }

  try {
    ajaxRequestCardEstadosAgendas = $.ajax({
      url: "../../controller/inicio/inicioController.php?accion=mostrarCardEstadosAgendas",
      method: "GET",
      data: dataToSend,
    });

    const response = await ajaxRequestCardEstadosAgendas;
    //console.log("Respuesta del servidor:", response);

    // Asegúrate de que la respuesta contenga las claves que necesitas
    $("#countAgenda").text(response.agenda || 0);
    $("#countEntrevista").text(response.entrevista || 0);
    $("#countConfirmados").text(response.confirmados || 0);
    $("#countAsistieron").text(response.asistieron || 0);
    $("#countNoresponde").text(response.no_responde || 0);
    $("#countNointeresado").text(response.no_interesado || 0);
    $("#countListanegra").text(response.lista_negra || 0);
  } catch (error) {
    console.error("Error al cargar los estados card:", error);
  }
}

async function cargarCardAgendadosRedes() {
  sedeSeleccionada = $("#filtroSedes").val();
  turnoSeleccionado = $("#turno").val();
  idUsuarioSesion = $("#idUsuarioSesion").val();

  await obtenerFechaAsignada(); // Asegúrate de que esta función obtenga la fecha correctamente
  /* console.log(fechaAsignadaNext);
  console.log(sedeSeleccionada);
  console.log(turnoSeleccionado);
  console.log(idRolSesion);
  console.log(idUsuarioSesion); */

  const dataToSend = {
    fechaAsignadaNext,
    sedeSeleccionada,
    turnoSeleccionado,
    idRolSesion,
    idUsuarioSesion,
  };

  if (ajaxRequestCardAgendadosRedes) {
    ajaxRequestCardAgendadosRedes.abort();
  }

  try {
    ajaxRequestCardAgendadosRedes = $.ajax({
      url: "../../controller/inicio/inicioController.php?accion=mostrarCardAgendadosRedes",
      method: "GET",
      data: dataToSend,
    });

    const response = await ajaxRequestCardAgendadosRedes;
    //console.log("Respuesta del servidor:", response);

    // Asegúrate de que la respuesta contenga las claves que necesitas
    $("#countFacebook").text(response.facebook || 0);
    $("#countComputrabajo").text(response.computrabajo || 0);
    $("#countInstagram").text(response.instagram || 0);
    $("#countTiktok").text(response.tiktok || 0);
    $("#countReferido").text(response.referido || 0);
    $("#countBumeran").text(response.bumeran || 0);
    $("#countOtros").text(response.otros || 0);
    $("#countReagendados").text(response.reagendados || 0);
  } catch (error) {
    console.error("Error al cargar las redes:", error);
  }
}

// Ejecutar la función al cargar el documento
$(document).ready(async function () {
  $("#filtroSedes").on("change", function () {
    if (idRolSesion == 1 | idRolSesion == 2 || idRolSesion == 4) {
      cargarUsuariosTop5Diario();
      cargarUsuariosTop5AsistenciaDiario();
    }
    cargarCardObjetivosAgendas();
    cargarCardEstadosAgendas();
    cargarCardAgendadosRedes();
  });

  $("#turno").on("change", function () {
    cargarCardObjetivosAgendas();
    cargarCardEstadosAgendas();
    cargarCardAgendadosRedes();
  });

  if (idRolSesion == 1 | idRolSesion == 2 || idRolSesion == 4) {
    cargarUsuariosTop5Diario();
    cargarUsuariosTop5AsistenciaDiario();
  }
  cargarCardObjetivosAgendas();
  cargarCardEstadosAgendas();
  cargarCardAgendadosRedes();
});
