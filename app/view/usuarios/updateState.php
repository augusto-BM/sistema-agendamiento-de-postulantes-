<?php
session_start();
require_once '../../controller/usuarios/usuariosController.php';

if (isset($_POST['usuario_id']) && isset($_POST['estado'])) {

    // Asignar las variables con los datos del formulario
    $usuario_id = $_POST['usuario_id'];
    $estado = $_POST['estado'];


    try {
        // Crear una instancia del controlador y llamar a guardarUsuarios
        $obj = new usuariosController();
        $obj->actualizarEstadoUsuario($usuario_id, $estado);
    } catch (Exception $e) {
        // Capturamos cualquier excepción y la lanzamos
        echo 'error: ' . $e->getMessage();
    }
    
} else {
    echo "Faltan datos necesarios para la actualizacion.";
    $_SESSION['error'] = "Faltan datos necesarios para la actualizacion.";
}
