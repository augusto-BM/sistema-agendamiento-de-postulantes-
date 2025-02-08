// Declaración de variables globales
/* const  */ fechaActual = new Date();
/* let  */ diaSeleccionado = null;
/* const  */ dias = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
/* const  */ meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
  
  // Función auxiliar para obtener la fecha de hoy (con hora normalizada a 00:00:00)
  function obtenerDiaHoy() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return today;
  }
  
  function limpiarDomingosPasadosEsteMes(savedDates) {
    const currentYear = fechaActual.getFullYear();
    const currentMonth = fechaActual.getMonth(); // 0 a 11
  
    const validSundays = savedDates.filter(
      (date) => date.year === currentYear && date.month === currentMonth + 1
    );
  
    // Actualiza el JSON en el backend con solo los domingos del mes actual
    $.ajax({
      url: "../calendario/storeCalendary.php",
      type: "POST",
      data: JSON.stringify({ sundays: validSundays }),
      contentType: "application/json",
      success: function (response) {
        //console.log("Fechas actualizadas correctamente:", response);
      },
      error: function (xhr, status, error) {
        console.error("Hubo un error al actualizar las fechas:", error);
      },
    });
  
    return validSundays;
  }
  
  function renderizarCalendario() {
    const yearMonth = $("#calendar-year-month");
    const calendarDays = $("#calendar-days");
  
    const year = fechaActual.getFullYear();
    const month = fechaActual.getMonth();
  
    // Asegurarse de que fechaActual esté en el primer día del mes
    fechaActual.setFullYear(year, month, 1);
  
    const firstDayOfMonth = new Date(year, month, 1);
    const lastDayOfMonth = new Date(year, month + 1, 0);
    const daysInMonth = lastDayOfMonth.getDate();
  
    // Establece el título del calendario (mes y año)
    yearMonth.text(`${meses[month]} ${year}`);
  
    // Limpia el contenedor de días
    calendarDays.empty();
  
    // Agrega la cabecera con los días de la semana
    dias.forEach((day) => calendarDays.append(`<div>${day}</div>`));
  
    // Agrega divs vacíos para alinear el primer día del mes
    const firstDayWeekday = firstDayOfMonth.getDay();
    for (let i = 0; i < firstDayWeekday; i++) {
      calendarDays.append("<div></div>");
    }
  
    // Solicita el JSON con las fechas guardadas
    $.ajax({
      url: "../calendario/fechas.json",
      type: "GET",
      dataType: "json",
      success: function (response) {
        // Filtra para obtener solo los domingos del mes actual
        const savedDates = limpiarDomingosPasadosEsteMes(response);
  
        // Recorre todos los días del mes
        for (let i = 1; i <= daysInMonth; i++) {
          const date = new Date(year, month, i);
          const dayElement = $(`<div class="calendar-day">${i}</div>`);
  
          // Si es el día actual, se le añade la clase "today"
          if (date.toDateString() === obtenerDiaHoy().toDateString()) {
            dayElement.addClass("today");
          }
  
          // Comprueba si la fecha está guardada (en el JSON)
          const dateExists = savedDates.some(
            (savedDate) =>
              savedDate.year === year &&
              savedDate.month === month + 1 &&
              savedDate.day === i
          );
          if (dateExists) {
            dayElement.addClass("red");
          }
  
          // Se deshabilitan los días anteriores a hoy
          if (date < obtenerDiaHoy()) {
            dayElement.addClass("disabled");
          }
          // Se deshabilitan (y marcan) los domingos (por ejemplo, para evitar selección)
          else if (date.getDay() === 0) {
            dayElement.addClass("red disabled");
          }
          // Para días habilitados, se asigna el evento click
          else {
            dayElement.on("click", function () {
              diaSeleccionado = dayElement;
              const isSelected = dayElement.hasClass("red");
              const diaSeleccionadoInfo = {
                year: date.getFullYear(),
                month: date.getMonth() + 1,
                day: date.getDate(),
              };
  
              // Alterna la clase "red" y envía la actualización al backend
              if (isSelected) {
                dayElement.removeClass("red");
              } else {
                dayElement.addClass("red");
              }
  
              $.ajax({
                url: "../calendario/storeCalendary.php",
                type: "POST",
                data: JSON.stringify(diaSeleccionadoInfo),
                contentType: "application/json",
                success: function (response) {
                  //console.log("Fecha actualizada:", response);
                },
                error: function (xhr, status, error) {
                  console.error("Hubo un error al guardar la fecha:", error);
                },
              });
            });
          }
  
          // Agrega el día al contenedor del calendario
          calendarDays.append(dayElement);
        }
      },
      error: function (xhr, status, error) {
        console.error("Hubo un error al obtener las fechas guardadas:", error);
      },
    });
  
    // Deshabilita el botón "prev-month" si el calendario está mostrando el mes actual
    const today = obtenerDiaHoy();
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
  
  function cambiarMes(increment) {
    let newMonth = fechaActual.getMonth() + increment;
    let newYear = fechaActual.getFullYear();
  
    if (newMonth < 0) {
      newMonth = 11;
      newYear--;
    } else if (newMonth > 11) {
      newMonth = 0;
      newYear++;
    }
  
    fechaActual.setFullYear(newYear);
    fechaActual.setMonth(newMonth);
    fechaActual.setDate(1);
    renderizarCalendario();
  }
  
  function obtenerDomingos(year, month) {
    const sundays = [];
    const lastDayOfMonth = new Date(year, month + 1, 0).getDate();
  
    for (let i = 1; i <= lastDayOfMonth; i++) {
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
  
  function domingosEsteMes() {
    const year = fechaActual.getFullYear();
    const month = fechaActual.getMonth();
    let sundays = obtenerDomingos(year, month);
  
    // Filtra para descartar domingos anteriores a hoy
    sundays = sundays.filter((sunday) => {
      const sundayDate = new Date(sunday.year, sunday.month - 1, sunday.day);
      return sundayDate >= obtenerDiaHoy();
    });
  
    $.ajax({
      url: "../calendario/storeCalendary.php",
      type: "POST",
      data: JSON.stringify({ sundays: sundays }),
      contentType: "application/json",
      success: function (response) {
        //console.log("Domingos guardados:", response);
      },
      error: function (xhr, status, error) {
        console.error("Hubo un error al guardar los domingos:", error);
      },
    });
  }
  
  function domingosSiguenteMes() {
    let nextMonth = fechaActual.getMonth() + 1;
    let nextYear = fechaActual.getFullYear();
    if (nextMonth > 11) {
      nextMonth = 0;
      nextYear++;
    }
  
    const sundaysNextMonth = obtenerDomingos(nextYear, nextMonth);
  
    $.ajax({
      url: "../calendario/storeCalendary.php",
      type: "POST",
      data: JSON.stringify({ sundaysNext: sundaysNextMonth }),
      contentType: "application/json",
      success: function (response) {
        //console.log("Domingos del mes siguiente guardados:", response);
      },
      error: function (xhr, status, error) {
        console.error(
          "Hubo un error al guardar los domingos del mes siguiente:",
          error
        );
      },
    });
  }
  
  function removerDiasPasadosDeHoy() {
    $.ajax({
      url: "../calendario/fechas.json",
      type: "GET",
      dataType: "json",
      success: function (response) {
        const filteredDates = response.filter((date) => {
          const dateObj = new Date(date.year, date.month - 1, date.day);
          return dateObj >= obtenerDiaHoy();
        });
  
        if (filteredDates.length !== response.length) {
          $.ajax({
            url: "../calendario/storeCalendary.php",
            type: "POST",
            data: JSON.stringify({
              cleanDates: true,
              dates: filteredDates,
            }),
            contentType: "application/json",
            success: function (response) {
              //console.log("Fechas antiguas eliminadas:", response);
              renderizarCalendario();
            },
            error: function (xhr, status, error) {
              console.error("Error al eliminar las fechas antiguas:", error);
            },
          });
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al obtener las fechas guardadas:", error);
      },
    });
  }
  
  function validarCalendario() {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: "../calendario/fechas.json",
        type: "GET",
        dataType: "json",
        success: function (response) {
          //console.log("Fechas obtenidas:", response);
          if (response && Array.isArray(response)) {
            resolve(true); 
          } else {
            resolve(false); 
          }
        },
        error: function (xhr, status, error) {
          console.error("Error al obtener las fechas:", error);
          reject(false); 
        }
      });
    });
  }
  
  $(document).ready(function () {
    validarCalendario()
      .then((validacionExitosa) => {
        if (validacionExitosa) {
          //console.log("Las validaciones han sido completadas con éxito.");
          removerDiasPasadosDeHoy();
          renderizarCalendario();
          domingosEsteMes();
          domingosSiguenteMes();
  
          $("#prev-month").on("click", function () {
            cambiarMes(-1);
          });
          $("#next-month").on("click", function () {
            cambiarMes(1);
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
  