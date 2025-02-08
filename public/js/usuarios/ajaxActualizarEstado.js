// Función para mostrar las alertas (SweetAlert o alert estándar)
function mostrarAlertaEstado(titulo, texto, tipo) {
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: titulo,
      text: texto,
      icon: tipo,
    });
  } else {
    alert(`${titulo}: ${texto}`);
  }
}

// Función para manejar el cambio de estado de un usuario
async function handleEstadoChange(usuario_id, estadoActual) {
  const nuevoEstado = estadoActual === 2 ? 3 : 2; // Cambiar de ACTIVO a INACTIVO o viceversa
  
  // Verificar si SweetAlert2 está disponible
  if (typeof Swal !== 'undefined') {
    // Mostrar SweetAlert2 para confirmar el cambio de estado
    const result = await Swal.fire({
      title: '¿Estás seguro?',
      text: `Vas a cambiar el estado del usuario a ${nuevoEstado === 2 ? 'ACTIVO' : 'INACTIVO'}.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, cambiarlo',
      cancelButtonText: 'No, cancelar',
      confirmButtonColor: '#19727A',  
      cancelButtonColor: '#A72307',  
    });

    if (result.isConfirmed) {
      // Si confirma el cambio, realizar la solicitud AJAX
      cambiarEstadoUsuario(usuario_id, nuevoEstado);
    }
  } else {
    // Si SweetAlert2 no está disponible, usar confirm() de JS
    const confirmacion = confirm(`¿Estás seguro de cambiar el estado del usuario a ${nuevoEstado === 2 ? 'ACTIVO' : 'INACTIVO'}?`);
    if (confirmacion) {
      cambiarEstadoUsuario(usuario_id, nuevoEstado);
    }
  }
}

// Función para cambiar el estado del usuario
function cambiarEstadoUsuario(usuario_id, nuevoEstado) {
  $.ajax({
    url: 'updateState.php',
    type: 'POST',
    data: {
      usuario_id: usuario_id,
      estado: nuevoEstado
    },
    success: function(response) {
      const data = JSON.parse(response);
      
      // Mostrar la alerta de éxito o error
      if (data.success) {
        mostrarAlertaEstado('Estado actualizado', data.message, 'success');
      } else {
        mostrarAlertaEstado('Error', data.message, 'error');
      }

      
      if (typeof cargarUsuariosInactivos === 'function') {
        // Si la función existe, se ejecuta
        cargarUsuariosInactivos(sedeSeleccionadaInactivos, page, limitinactivos);
      }
      
      if (typeof cargarUsuarios === 'function') {
        // Si la función existe, se ejecuta
        cargarUsuarios($("#filtroSedes").val(), page, limit);
      }
    
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error('Error AJAX:', textStatus, errorThrown);
      mostrarAlertaEstado('Error', 'Hubo un problema con la solicitud AJAX.', 'error');
    }
  });
}
