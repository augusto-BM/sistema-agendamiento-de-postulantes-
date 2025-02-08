// Función para mostrar alertas con SweetAlert2 o alert tradicional
function mostrarAlertaEditar(tipo, titulo, mensaje) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
            confirmButtonText: "Aceptar",
            timer: 1000,
            timerProgressBar: true,
            didOpen: () => Swal.showLoading(),
            willClose: () => {
                $(".formulario_editarUsuario")[0].reset();
                $("#modalDinamico").modal("hide");
            },
        });
    } else {
        alert(mensaje);
    }
}

// Llamar a las funciones dentro de document.ready
$(document).ready(function() {
    console.log("Prueba script archivo editar usuario");

    // Llamar a las funciones para los campos correspondientes
    convertirAMayusculas("#nombre_colaborador, #correo_electronico");
    prevenirNumeros("#nombre_colaborador");
    prevenirLetras("#numero_documento, #celular");

    // Validación de formulario
    $(".formulario_editarUsuario").submit(function(event) {
        event.preventDefault(); // Prevenir el envío tradicional del formulario

        let isValid = true;
        $(".form-control").removeClass("is-invalid");
        $(".invalid-feedback").hide();

        // Validación de campos específicos
        const validaciones = [
            validarCampo("#nombre_colaborador", /^[a-zA-Z\s]+$/,
                "El nombre solo puede contener letras y espacios."),
            validarCampo("#numero_documento", null,
                "El numero de documento es obligatorio y debe ser numérico."),
            validarCampo("#correo_electronico", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/,
                "El correo electrónico es inválido."),
            validarCampo("#pass", /^.{4,}$/,
                "La contraseña es inválida, debe ser mayor a tres caracteres."),
            validarCampo("#celular", /^[0-9]{9}$/, "El celular debe tener 9 dígitos."),
        ];

        validaciones.forEach(validation => {
            if (!validation.isValid) {
                isValid = false;
                $(validation.selector)
                    .addClass("is-invalid")
                    .next(".invalid-feedback")
                    .text(validation.errorMessage)
                    .show();
            }
        });

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
            const formData = $(".formulario_editarUsuario")
                .serialize(); // Serializar los datos del formulario

            $.ajax({
                url: "update.php", // Tu archivo PHP
                type: "POST",
                data: formData,
                dataType: "json", // Esperamos una respuesta JSON
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        mostrarAlertaEditar("success", "¡Éxito!", response.message);
                    } else {
                        mostrarAlertaEditar("error", "Error", response.message);
                    }
                    //console.log("Cargando usuarios...");
                    cargarUsuarios($("#filtroSedes").val(), page, limit);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error en la solicitud AJAX.");
                    console.log("Estado de la solicitud:", textStatus);
                    console.log("Error:", errorThrown);
                    console.log("Respuesta del servidor:", jqXHR.responseText);
                    alert("Hubo un error al editar los datos.");
                },
            });
        }
    });
});