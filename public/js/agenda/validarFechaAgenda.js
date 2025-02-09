console.log("Cargando el script validarFechaAgenda.js");
$(document).ready(function () {
    // Realizar una solicitud AJAX para cargar el archivo fechasCalendario.json
    $.ajax({
      url: "../calendario/fechasCalendario.json",  // Ruta al archivo JSON
      type: "GET",
      dataType: "json",
      success: function (fechasNoLaborables) {
        //console.log("Fechas no laborables cargadas:", fechasNoLaborables);
  
        // Obtener la fecha de Lima
        var fechaLima = obtenerFechaLima();
        //console.log("Fecha actual de Lima:", fechaLima);
  
        var manana = new Date(fechaLima);
        manana.setDate(fechaLima.getDate() + 1);
        var mananaStr = formatearFecha(manana); // Fecha de mañana en formato 'YYYY-MM-DD'
        //console.log("Fecha de mañana:", mananaStr);
  
        // Crear un Set para las fechas no laborables (almacenamos como fechas)
        var fechasNoLaborablesSet = new Set(
          fechasNoLaborables.map(function (fecha) {
            return formatearFecha(new Date(fecha.year, fecha.month - 1, fecha.day));
          })
        );
  
        //console.log("Fechas no laborables en Set:", fechasNoLaborablesSet);
  
        // Verificar si la fecha de mañana está en el Set de fechas no laborables
        if (!fechasNoLaborablesSet.has(mananaStr)) {
          $("#fecha_agenda").val(mananaStr);
          //console.log("La fecha de mañana no está en el Set. Se seleccionó:", mananaStr);
        } else {
          // Si la fecha de mañana está en el Set, buscamos la siguiente fecha laborable
          var siguienteLaborable = getSiguienteLaborable(manana, fechasNoLaborablesSet);
          var siguienteLaborableStr = formatearFecha(siguienteLaborable);
          $("#fecha_agenda").val(siguienteLaborableStr);
          //console.log("La fecha de mañana está en el Set. Se seleccionó el siguiente día laborable:", siguienteLaborableStr);
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al cargar el archivo JSON:", error);
      },
    });
  
    // Función para obtener el siguiente día laborable
    function getSiguienteLaborable(fechaInicio, fechasNoLaborablesSet) {
      var siguienteFecha = new Date(fechaInicio);
      while (true) {
        siguienteFecha.setDate(siguienteFecha.getDate() + 1);
        var siguienteFechaStr = formatearFecha(siguienteFecha);
        //console.log("Comprobando siguiente fecha laborable:", siguienteFechaStr);
  
        // Comprobar si la siguiente fecha no está en el Set
        if (!fechasNoLaborablesSet.has(siguienteFechaStr)) {
          return siguienteFecha;  // Si no es una fecha no laborable, devolver la fecha
        }
      }
    }
  });
  