$(document).on('click', '.abrirModal', function(event) {
    event.preventDefault();
    
    var modalId = $(this).data('id');   //Este es el nombre del archivo donde está el contenido del modal
    var prefix = $(this).data('prefix'); 
    var modalTitle = $(this).data('titulo');
    
    var identificador = $(this).data('identificador'); 

    $('#modalDinamicoLabel').html('<span class="fw-light">' + prefix + '</span> ' + modalTitle);

    var dataToSend = identificador ? { identificador: identificador } : {};
    
    $.ajax({
        url: modalId + '.php',
        type: 'GET',
        data: dataToSend,
        success: function(response) {
            $('#modalDinamico .modal-body').html(response);
            var myModal = new bootstrap.Modal(document.getElementById('modalDinamico'));
            myModal.show();
        },
        error: function() {
            alert('Hubo un error al cargar el contenido.');
        }
    });
});

// Añadir un listener para el cierre del modal
$('#modalDinamico').on('hidden.bs.modal', function() {
    // Limpiar el contenido del modal al cerrarlo
    $('#modalDinamico .modal-body').html('');
    // Si deseas destruir el modal completamente, puedes hacer lo siguiente (opcional)
    var myModal = bootstrap.Modal.getInstance(document.getElementById('modalDinamico'));
    myModal.dispose();
});


/* $(document).ready(function() {
    // Cuando el usuario hace clic en cualquier botón con la clase 'abrirModal'
    $('.abrirModal').on('click', function() {
        // Obtener el ID, el título y el prefijo desde los atributos 'data-id', 'data-titulo' y 'data-prefix' del botón
        var modalId = $(this).data('id');
        var prefix = $(this).data('prefix');
        var modalTitle = $(this).data('titulo');

        // Actualizar el título del modal con el prefijo dinámico y el título
        $('#modalDinamicoLabel').html('<span class="fw-light">' + prefix + '</span> ' + modalTitle);

        // Hacer la petición Ajax con el modalId para cargar el contenido correspondiente
        $.ajax({
            url: modalId + '.php',
            type: 'GET',
            success: function(response) {
                // Cargar el contenido recibido en el cuerpo del modal
                $('#modalDinamico .modal-body').html(response);

                // Mostrar el modal automáticamente
                $('#modalDinamico').modal('show');
            },
            error: function() {
                alert('Hubo un error al cargar el contenido.');
            }
        });
    });
}); 

==========================================
$(document).on('click', '.abrirModal', function(event) {
    event.preventDefault();
    //console.log("Botón de modal clickeado");

    var modalId = $(this).data('id');   //Este es el nombre del archivo donde esta el contenido del modal
    var prefix = $(this).data('prefix'); 
    var modalTitle = $(this).data('titulo');
    
    //SOLO LOS QUE TIENE EL VALOR DEL ID EN EL BOTON
    var identificador = $(this).data('identificador'); 
    //console.log(identificador);

    $('#modalDinamicoLabel').html('<span class="fw-light">' + prefix + '</span> ' + modalTitle);

    //SOLO SI EXISTE EL ID DINAMICO SE ENVIA EL VALOR SINO SOLO QUEDA VACIO
    var dataToSend = identificador ? { identificador: identificador } : {};
    $.ajax({
        url: modalId + '.php',
        type: 'GET',
        data: dataToSend,
        success: function(response) {
            $('#modalDinamico .modal-body').html(response);
            var myModal = new bootstrap.Modal(document.getElementById('modalDinamico'));
            myModal.show();
        },
        error: function() {
            alert('Hubo un error al cargar el contenido.');
        }
    });
});

*/