// Función para convertir los valores a mayúsculas
function convertirAMayusculas(selector) {
  $(selector).on("input", function () {
    $(this).val($(this).val().toUpperCase());
  });
}

// Función para prevenir la entrada de números en los campos de texto
function prevenirNumeros(selector) {
  $(selector).on("keypress", function (event) {
    const charCode = event.which || event.keyCode;
    if (charCode >= 48 && charCode <= 57) {
      event.preventDefault(); // Bloquear números
    }
  });
}

// Función para prevenir letras en campos numéricos
function prevenirLetras(selector) {
  $(selector).on("keypress", function (event) {
    const charCode = event.which || event.keyCode;
    if (charCode < 48 || charCode > 57) {
      event.preventDefault(); // Bloquear letras
    }
  });
}

// Función de validación genérica
function validarCampo(selector, regex, errorMessage) {
  const value = $(selector).val().trim();
  if (!value || (regex && !regex.test(value))) {
    return {
      isValid: false,
      selector: selector,
      errorMessage: errorMessage,
    };
  }
  return { isValid: true };
}

// Función para obtener la fecha actual de Lima
function obtenerFechaLima() {
  const fechaLima = new Date(new Date().toLocaleString("en-US", { timeZone: "America/Lima" }));

  if (!(fechaLima instanceof Date) || isNaN(fechaLima)) {
    console.error("Error: fechaLima no es un objeto Date válido");
    return null;  
  }
  
  return fechaLima;  
}

/* function obtenerHoraLima() {
  return new Date(new Date().toLocaleString("en-US", { timeZone: "America/Lima" }));
} */

// Función para formatear la fecha en formato YYYY-MM-DD
function formatearFecha(fecha) {
  return fecha.toISOString().split("T")[0]; // Formato: YYYY-MM-DD
}

function formatearFechaVista(fecha) {
  var partes = fecha.split("-");
  return partes[2] + "-" + partes[1] + "-" + partes[0];
}

