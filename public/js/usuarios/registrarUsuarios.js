$(document).ready(function () {
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

  // Llamar a las funciones para los campos correspondientes
  convertirAMayusculas("#nombre_colaborador, #apellido_paterno, #apellido_materno, #correo_electronico");
  prevenirNumeros("#nombre_colaborador, #apellido_paterno, #apellido_materno");
  prevenirLetras("#numero_documento, #celular");

  // Validación de formulario
  $(".formulario_registrarArticulo").submit(function (event) {
    event.preventDefault(); // Prevenir el envío tradicional del formulario

    let isValid = true;
    $(".form-control").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    // Función de validación genérica
    function validarCampo(selector, regex, errorMessage) {
      const value = $(selector).val().trim();
      if (!value || (regex && !regex.test(value))) {
        isValid = false;
        $(selector)
          .addClass("is-invalid")
          .next(".invalid-feedback")
          .text(errorMessage)
          .show();
      }
    }

    // Validación de campos específicos
    validarCampo("#nombre_colaborador", /^[a-zA-Z\s]+$/, "El nombre solo puede contener letras y espacios.");
    validarCampo("#apellido_paterno", /^[a-zA-Z\s]+$/, "El apellido paterno solo puede contener letras y espacios.");
    validarCampo("#apellido_materno", /^[a-zA-Z\s]+$/, "El apellido materno solo puede contener letras y espacios.");

    validarCampo("#numero_documento", null,"El numero de documento es obligatorio y debe ser numérico.");
    validarCampo("#correo_electronico", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, "El correo electrónico es inválido.");
    validarCampo("#celular", /^[0-9]{9}$/, "El celular debe tener 9 dígitos.");

    // Validaciones de campos seleccionados
    const camposRequeridos = ["#tipo_documento", "#empresa_id", "#turno", "#rol", "#fecha_ingreso"];
    camposRequeridos.forEach((selector) => {
      if ($(selector).val() === "") {
        isValid = false;
        $(selector).addClass("is-invalid").next(".invalid-feedback").show();
      }
    });

    // Si todo es válido, enviar el formulario
    if (isValid) {
      const formData = $(".formulario_registrarArticulo").serialize(); // Serializar los datos del formulario

      $.ajax({
        url: "store.php", // Tu archivo PHP
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            mostrarAlerta("success", "¡Éxito!", response.message);
          } else {
            mostrarAlerta("error", "Error", response.message);
          }
        },
        error: function () {
          console.log("Error en la solicitud AJAX.");
          alert("Hubo un error al registrar los datos.");
        },
      });
    }
  });

  // Función para mostrar alertas con SweetAlert2 o alert tradicional
  function mostrarAlerta(tipo, titulo, mensaje) {
    if (typeof Swal !== "undefined") {
      Swal.fire({
        icon: tipo,
        title: titulo,
        text: mensaje,
        confirmButtonText: "Aceptar",
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => Swal.showLoading(),
        willClose: () => {
          $(".formulario_registrarArticulo")[0].reset();
          $("#modalDinamico").modal("hide");
        },
      });
    } else {
      alert(mensaje);
    }
  }
});
