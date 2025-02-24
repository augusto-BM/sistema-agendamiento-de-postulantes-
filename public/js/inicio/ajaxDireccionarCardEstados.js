console.log("Ejecutando ajaxDireccionarCardEstados.js...");

async function direccionarCardEstados(archivo) {
  let sedeSeleccionadaCard = $("#filtroSedes").val();
  let turnoSeleccionadoCard = $("#turno").val();
  let data;

  if (archivo === "agenda") {
    await obtenerFechaAsignada();
    //console.log(fechaAsignadaNext);
    data = {
      sedeSeleccionadaCard: sedeSeleccionadaCard,
      turnoSeleccionadoCard: turnoSeleccionadoCard,
      fechaAsignadaNextCard: fechaAsignadaNext,
    };
  } else {
    data = {
      sedeSeleccionadaCard: sedeSeleccionadaCard,
      turnoSeleccionadoCard: turnoSeleccionadoCard,
    };
  }

  const parametrosUrl = new URLSearchParams(data).toString();
  const url = archivo + ".php?" + parametrosUrl;

  try {
    const response = await fetch(url, { method: "GET" });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.statusText);
    }
    const result = await response.text();
    window.location.href = url;
  } catch (error) {
    console.error("Hubo un error al realizar la solicitud:", error);
  }
}

$(document).ready(function () {
  $("#filtroSedes, #turno").on("change", function () {
    $("#filtroSedes").val();
    $("#turno").val();
  });

  $(".cardEstados").on("click", function (event) {
    var archivo = $(this).data("nombrearchivo");
    event.preventDefault();
    direccionarCardEstados(archivo);
  });
});
