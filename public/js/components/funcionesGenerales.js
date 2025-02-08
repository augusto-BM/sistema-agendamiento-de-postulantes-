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
  const options = {
    timeZone: "America/Lima",
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  };
  const fechaLima = new Intl.DateTimeFormat("es-PE", options).format(
    new Date()
  );
  const [day, month, year] = fechaLima.split("/");
  return new Date(`${year}-${month}-${day}`); // Devolver como un objeto Date
}

// Función para formatear la fecha en formato YYYY-MM-DD
function formatearFecha(fecha) {
  return fecha.toISOString().split("T")[0]; // Formato: YYYY-MM-DD
}
