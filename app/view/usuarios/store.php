<?php
session_start();
require_once '../../controller/usuarios/usuariosController.php';

if (isset($_POST['nombre_colaborador']) && isset($_POST['apellido_paterno']) && isset($_POST['apellido_materno']) && isset($_POST['tipo_documento']) && isset($_POST['numero_documento']) && isset($_POST['fecha_ingreso']) && isset($_POST['id_empresa']) && isset($_POST['nombre_sede']) && isset($_POST['turno']) && isset($_POST['id_rol']) && isset($_POST['correo_electronico']) && isset($_POST['celular'])) {

    // Asignamos las variables con los datos del formulario
    $nombrecolaborador = $_POST['nombre_colaborador'];
    $apellidopaterno = $_POST['apellido_paterno'];
    $apellidomaterno = $_POST['apellido_materno'];

    $nombreusuario = $nombrecolaborador . ' ' . $apellidopaterno . ' ' . $apellidomaterno;
    $tipodocumento = $_POST['tipo_documento'];
    $numerodocumento = $_POST['numero_documento'];
    $correoelectronico = $_POST['correo_electronico'];
    $contraseña = $numerodocumento . "0"; // contraseña, concatenando un "0" al final del DNI
    $celular = $_POST['celular'];
    $empresa = $_POST['id_empresa'];
    $sede = $_POST['nombre_sede'];
    $turno = $_POST['turno'];
    $estado = 2;
    $rol = $_POST['id_rol'];
    $fechaingreso = $_POST['fecha_ingreso'];

    // Crear una instancia del controlador y llamar a guardarUsuarios
    $obj = new usuariosController();
    $obj->guardarUsuarios($nombreusuario, $tipodocumento, $numerodocumento, $correoelectronico, $contraseña, $celular, $sede, $empresa, $turno, $estado, $rol, $fechaingreso);
    
} else {
    //echo "Faltan datos necesarios para el registro.";
    $_SESSION['error'] = "Faltan datos necesarios para el registro.";
}