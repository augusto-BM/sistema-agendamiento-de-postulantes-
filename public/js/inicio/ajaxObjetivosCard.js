let ajaxRequestCardObjetivosAgendas = null;

async function cargarCardObjetivosAgendas() {
    await obtenerFechaAsignada();
    //console.log(fechaAsignadaNext)
    sedeSeleccionada = $("#filtroSedes").val();
    turnoSeleccionado = $("#turno").val();
    idUsuarioSesion = $("#idUsuarioSesion").val();
  
    // Asegúrate de que esta función obtenga la fecha correctamente
    /* console.log(fechaAsignadaNext);
    console.log(fechaHoy);
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
  
    if (ajaxRequestCardObjetivosAgendas) {
        ajaxRequestCardObjetivosAgendas.abort();
    }
  
    try {
        ajaxRequestCardObjetivosAgendas = $.ajax({
        url: "../../controller/inicio/inicioController.php?accion=mostrarCardObjetivosAgendas",
        method: "GET",
        data: dataToSend,
      });
  
      const response = await ajaxRequestCardObjetivosAgendas;
      //console.log("Respuesta del servidor:", response);
  
      // Asegúrate de que la respuesta contenga las claves que necesitas
      $("#countVoy").text(response.voy || 0);
      $("#countVamos").text(response.vamos || 0);

    } catch (error) {
      console.error("Error al cargar los estados card:", error);
    }
  }