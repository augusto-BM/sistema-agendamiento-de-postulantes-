<?php
session_start();
require_once '../../controller/usuarios/usuariosController.php';

if (isset($_POST['idusuario']) && isset($_POST['nombre_colaborador']) && isset($_POST['tipo_documento']) && isset($_POST['numero_documento']) && isset($_POST['fecha_ingreso']) && isset($_POST['id_empresa']) && isset($_POST['turno']) && isset($_POST['id_rol'])  && isset($_POST['correo_electronico']) && isset($_POST['pass']) && isset($_POST['celular'])) {

    // Asignamos las variables con los datos del formulario
    $idusuario = $_POST['idusuario'];
    $nombreusuario = $_POST['nombre_colaborador'];
    $tipodocumento = $_POST['tipo_documento'];
    $numerodocumento = $_POST['numero_documento'];
    $correoelectronico = $_POST['correo_electronico'];
    $pass = $_POST['pass'];
    $celular = $_POST['celular'];
    $empresa = $_POST['id_empresa'];
    $sede = $_POST['nombre_sede'];
    $turno = $_POST['turno'];
    $rol = $_POST['id_rol'];
    $fechaingreso = $_POST['fecha_ingreso'];

    try {
        // Crear una instancia del controlador y llamar a guardarUsuarios
        $obj = new usuariosController();
        $obj->actualizarUsuario($idusuario, $nombreusuario, $tipodocumento, $numerodocumento, $correoelectronico, $pass, $celular, $empresa, $sede, $turno, $rol, $fechaingreso);
    } catch (Exception $e) {
        // Capturamos cualquier excepción y la lanzamos
        echo 'error: ' . $e->getMessage();
    }
    
} else {
    //echo "Faltan datos necesarios para actualizar los registros.";
    $_SESSION['error'] = "Faltan datos necesarios para actualizar los registros.";
}