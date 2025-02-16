console.log("SCRIPT: Se cargó el script de AJAX para generar el calendario.");

/*** VARIABLES GLOBALES ***/
const fechaActualCalendario = new Date();
let diaSeleccionadoCalendario = null;
const diasCalendario = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
const mesesCalendario = [
  "Enero",
  "Febrero",
  "Marzo",
  "Abril",
  "Mayo",
  "Junio",
  "Julio",
  "Agosto",
  "Septiembre",
  "Octubre",
  "Noviembre",
  "Diciembre",
];

/*** FUNCIONES DE AYUDA ***/
// Devuelve la fecha de hoy con la hora normalizada a 00:00:00
function obtenerDiaHoyCalendario() {
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return today;
}

/*** MÓDULO DE ACCESO AL BACKEND ***/
// Función genérica para enviar datos al backend
function updateCalendarData(data) {
  //console.log("Enviando datos al servidor:", data); // PUNTO DE DEPURACIÓN
  return $.ajax({
    url: "storeCalendary.php",
    type: "POST",
    data: JSON.stringify(data),
    contentType: "application/json",
  });
}

// Función para obtener el JSON con las fechas guardadas
function fetchStoredDatesCalendario() {
  return $.ajax({
    url: "fechasCalendario.json",
    type: "GET",
    dataType: "json",
  });
}

/*** MÓDULO DE RENDERIZADO DEL CALENDARIO ***/
async function renderizarCalendario() {
  //console.log("Renderizando calendario...");

  const $yearMonth = $("#calendar-year-month");
  const $calendarDays = $("#calendar-days");

  const year = fechaActualCalendario.getFullYear();
  const month = fechaActualCalendario.getMonth();
  fechaActualCalendario.setFullYear(year, month, 1);

  const firstDayOfMonth = new Date(year, month, 1);
  const lastDayOfMonth = new Date(year, month + 1, 0);
  const daysInMonth = lastDayOfMonth.getDate();

  $yearMonth.html(`${mesesCalendario[month]} &nbsp;-&nbsp;&nbsp;${year}`);

  $calendarDays.empty();
  diasCalendario.forEach((day) => $calendarDays.append(`<div>${day}</div>`));

  const firstDayWeekday = firstDayOfMonth.getDay();
  for (let i = 0; i < firstDayWeekday; i++) {
    $calendarDays.append("<div></div>");
  }

  try {
    const response = await fetchStoredDatesCalendario();
    //console.log("Fechas guardadas:", response);
    const savedDatesCalendario = limpiarDomingosPasadosEsteMes(response);

    for (let i = 1; i <= daysInMonth; i++) {
      const date = new Date(year, month, i);
      const $dayElement = $(`<div class="calendar-day">${i}</div>`);

      if (date.toDateString() === obtenerDiaHoyCalendario().toDateString()) {
        $dayElement.addClass("today");
      }

      const dateExists = savedDatesCalendario.some(
        (savedDate) =>
          savedDate.year === year &&
          savedDate.month === month + 1 &&
          savedDate.day === i
      );
      if (dateExists) {
        $dayElement.addClass("red");
      }

      if (date < obtenerDiaHoyCalendario()) {
        $dayElement.addClass("disabled");
      } else if (date.getDay() === 0) {
        $dayElement.addClass("red disabled");
      } else {
        // Aquí está la lógica del clic para el día
        $dayElement.on("click", function () {
          manejarClicDia(i, date, $dayElement);
        });
      }

      $calendarDays.append($dayElement);
    }
  } catch (error) {
    console.error("Error al obtener las fechas guardadas:", error);
  }

  // Se deshabilita el botón de mes anterior si se está mostrando el mes actual
  const today = obtenerDiaHoyCalendario();
  const currentCalendar = new Date(year, month, 1);
  if (
    today.getFullYear() === currentCalendar.getFullYear() &&
    today.getMonth() === currentCalendar.getMonth()
  ) {
    $("#prev-month").prop("disabled", true).addClass("disabled-prev-month");
  } else {
    $("#prev-month").prop("disabled", false).removeClass("disabled-prev-month");
  }
}

/*** LÓGICA DEL CLIC EN EL DÍA SEPARADA ***/
function manejarClicDia(i, date, $dayElement) {
  //console.log("Clic en el día: ", i);
  diaSeleccionadoCalendario = $dayElement;
  const diaSeleccionadoInfo = {
    year: date.getFullYear(),
    month: date.getMonth() + 1,
    day: date.getDate(),
  };

  const isSelected = $dayElement.hasClass("red");
  //console.log("Estado del día seleccionado (es rojo): ", isSelected);

  updateCalendarData(diaSeleccionadoInfo)
    .done(function (response) {
      //console.log("Respuesta al guardar fecha:", response);
      if (isSelected) {
        $dayElement.removeClass("red");
      } else {
        $dayElement.addClass("red");
      }

      // Llamar a renderizarCalendario después de modificar el día
      renderizarCalendario();
    })
    .fail(function (xhr, status, error) {
      console.error("Error al guardar la fecha:", error);
    });
}

/*** RENDERIZADO DEL CALENDARIO AL CAMBIAR DE MES ***/
function cambiarMes(increment) {
  let newMonth = fechaActualCalendario.getMonth() + increment;
  let newYear = fechaActualCalendario.getFullYear();

  if (newMonth < 0) {
    newMonth = 11;
    newYear--;
  } else if (newMonth > 11) {
    newMonth = 0;
    newYear++;
  }

  fechaActualCalendario.setFullYear(newYear);
  fechaActualCalendario.setMonth(newMonth);
  fechaActualCalendario.setDate(1);

  // Aquí solo se llama a renderizarCalendario
  renderizarCalendario();
}

/*** MÓDULO PARA MANEJAR LOS DOMINGOS ***/
// Función que obtiene los domingos de un mes
function obtenerDomingos(year, month) {
  const sundays = [];
  const lastDay = new Date(year, month + 1, 0).getDate();
  for (let i = 1; i <= lastDay; i++) {
    const date = new Date(year, month, i);
    if (date.getDay() === 0) {
      sundays.push({
        year: date.getFullYear(),
        month: date.getMonth() + 1,
        day: date.getDate(),
      });
    }
  }
  return sundays;
}

// Se filtran los domingos que corresponden al mes actual y se actualiza el backend
function limpiarDomingosPasadosEsteMes(savedDatesCalendario) {
  const currentYear = fechaActualCalendario.getFullYear();
  const currentMonth = fechaActualCalendario.getMonth();
  const validSundays = savedDatesCalendario.filter(
    (date) => date.year === currentYear && date.month === currentMonth + 1
  );

  updateCalendarData({
    sundays: validSundays,
  })
    .done(function (response) {
      // Actualización exitosa en backend.
    })
    .fail(function (xhr, status, error) {
      console.error("Error al actualizar las fechas:", error);
    });

  return validSundays;
}

// Actualiza los domingos del mes actual
function actualizarDomingosEsteMes() {
  const year = fechaActualCalendario.getFullYear();
  const month = fechaActualCalendario.getMonth();
  let sundays = obtenerDomingos(year, month);

  // Se filtran para descartar domingos anteriores a hoy
  sundays = sundays.filter((sunday) => {
    const sundayDate = new Date(sunday.year, sunday.month - 1, sunday.day);
    return sundayDate >= obtenerDiaHoyCalendario();
  });

  updateCalendarData({
    sundays: sundays,
  })
    .done(function (response) {
      // Domingos actualizados correctamente.
    })
    .fail(function (xhr, status, error) {
      console.error("Error al guardar los domingos:", error);
    });
}

// Actualiza los domingos del mes siguiente
function actualizarDomingosSiguienteMes() {
  let nextMonth = fechaActualCalendario.getMonth() + 1;
  let nextYear = fechaActualCalendario.getFullYear();
  if (nextMonth > 11) {
    nextMonth = 0;
    nextYear++;
  }

  const sundaysNext = obtenerDomingos(nextYear, nextMonth);
  updateCalendarData({
    sundaysNext: sundaysNext,
  })
    .done(function (response) {
      // Domingos del mes siguiente actualizados correctamente.
    })
    .fail(function (xhr, status, error) {
      console.error("Error al guardar los domingos del mes siguiente:", error);
    });
}

/*** MÓDULO DE LIMPIEZA ***/
// Remueve del JSON las fechas que son anteriores a hoy
function removerDiasPasadosDeHoy() {
  fetchStoredDatesCalendario()
    .done(function (response) {
      const filteredDates = response.filter((date) => {
        const dateObj = new Date(date.year, date.month - 1, date.day);
        return dateObj >= obtenerDiaHoyCalendario();
      });

      if (filteredDates.length !== response.length) {
        updateCalendarData({
          cleanDates: true,
          dates: filteredDates,
        })
          .done(function (response) {
            renderizarCalendario();
          })
          .fail(function (xhr, status, error) {
            console.error("Error al eliminar las fechas antiguas:", error);
          });
      }
    })
    .fail(function (xhr, status, error) {
      console.error("Error al obtener las fechas guardadas:", error);
    });
}

/*** VALIDACIÓN INICIAL DEL CALENDARIO ***/
function validarCalendario() {
  return new Promise((resolve, reject) => {
    fetchStoredDatesCalendario()
      .done(function (response) {
        if (response && Array.isArray(response)) {
          resolve(true);
        } else {
          resolve(false);
        }
      })
      .fail(function (xhr, status, error) {
        console.error("Error al obtener las fechas:", error);
        reject(false);
      });
  });
}

$(document).ready(function () {
  renderizarCalendario();renderizarCalendario();renderizarCalendario();
  
  validarCalendario()
    .then((validacionExitosa) => {
      if (validacionExitosa) {
        removerDiasPasadosDeHoy();
        actualizarDomingosEsteMes();
        actualizarDomingosSiguienteMes();

        $("#prev-month").on("click", function () {
          cambiarMes(-1);
        });
        $("#next-month").on("click", function () {
          cambiarMes(1);
        });

        // Asegúrate de esperar a que fetchStoredDatesCalendario se complete
        fetchStoredDatesCalendario()
          .done(function () {
            // Solo después de que los datos se hayan cargado, renderizamos el calendario
            renderizarCalendario();
          })
          .fail(function (xhr, status, error) {
            console.error("Error al obtener las fechas guardadas:", error);
            location.reload();
          });
      } else {
        console.log(
          "No se pueden ejecutar las acciones. Las validaciones no han sido completadas."
        );
      }
    })
    .catch((error) => {
      console.log("Error en la validación:", error);
    });
});
