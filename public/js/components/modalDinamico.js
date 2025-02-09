console.log("modal dinámico cargado");
$(document).on('click', '.abrirModal', function(event) {
    event.preventDefault();
    console.log("Botón de modal clickeado");

    // Obtener los datos del botón
    var modalId = $(this).data('id');   // Este es el nombre del archivo donde está el contenido del modal
    var prefix = $(this).data('prefix');
    var modalTitle = $(this).data('titulo');
    var identificador = $(this).data('identificador');  // Datos adicionales si son necesarios

    // Actualizar el título del modal dinámicamente
    $('#modalDinamicoLabel').html('<span class="fw-light">' + prefix + '</span> ' + modalTitle);

    // Condicionar el estilo del modal según el tipo de contenido
    if (modalId === "calendario") {
        // Para el calendario, podemos ajustar la altura y los estilos
        $('#modalDinamico .modal-dialog').removeClass('modal-xl')/* .addClass('modal-lg') */;
        $('#modalDinamico .modal-body').css({
            'display': 'flex',
            'justify-content': 'center',
            'align-items': 'flex-start',
            'height': '100%',
            'overflow-y': 'auto',
            'padding': '0'
        });
        $('#modalDinamico .modal-content').css({
            'height': '500px',
        });
        /* $('#modalDinamicoLabel').text('Calendario'); */
    } else {
        // Para otros modales, asegurarse de usar la clase de tamaño grande
        $('#modalDinamico .modal-dialog').removeClass('modal-lg').addClass('modal-xl');
        $('#modalDinamico .modal-body').removeAttr('style');
    }

    // Definir la URL según el tipo de contenido a cargar
    var url;
    if (modalId === "calendario") {
        url = "../calendario/" + modalId + ".php";  // Ruta para el calendario
    } else {
        url = modalId + ".php";  // Ruta genérica para otros modales
    }

    // Datos adicionales que se enviarán si el identificador existe
    var dataToSend = identificador ? { identificador: identificador } : {};

    // Hacer la petición Ajax para cargar el contenido en el modal dinámico
    $.ajax({
        url: url,  // Usar la URL definida arriba
        type: 'GET',
        data: dataToSend,
        success: function(response) {
            // Cargar el contenido recibido en el cuerpo del modal
            $('#modalDinamico .modal-body').html(response);

            // Mostrar el modal dinámicamente
            var myModal = new bootstrap.Modal(document.getElementById('modalDinamico'));
            myModal.show();
        },
        error: function() {
            alert('Hubo un error al cargar el contenido.');
        }
    });
});

// Limpiar el contenido del modal cuando se cierre
$('#modalDinamico').on('hidden.bs.modal', function() {
    $('#modalDinamico .modal-body').empty();  // Vaciar el contenido para liberar recursos

    // Restaurar los estilos predeterminados del modal después de cerrarlo
    $('#modalDinamico .modal-dialog')/* .removeClass('modal-lg') */.addClass('modal-xl');
    $('#modalDinamico .modal-body').removeAttr('style');
    $('#modalDinamico .modal-content').removeAttr('style');
    $('#modalDinamicoLabel').text('Título del Modal');
});
