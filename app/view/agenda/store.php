<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../controller/agenda/agendaController.php';

if (isset($_POST['idusuario']) && isset($_POST['celular']) && isset($_POST['contacto']) && isset($_POST['postulante']) && isset($_POST['tipo_documento']) && isset($_POST['numero_documento']) && isset($_POST['cboedad']) && isset($_POST['cbodistrito']) && isset($_POST['cbofuente']) && isset($_POST['fecha_agenda']) && isset($_POST['turno']) && isset($_POST['nombre_sede']) && isset($_POST['observaciones'])) {

    // Asignamos las variables con los datos del formulario
    $idusuario = $_POST['idusuario'];
    $celular = $_POST['celular'];
    $celular2 = $_POST['contacto'];
    $postulante = $_POST['postulante'];
    $tipodocumento = $_POST['tipo_documento'];
    $numerodocumento = $_POST['numero_documento'];

    $edad = $_POST['cboedad'];
    $distrito = $_POST['cbodistrito'];
    $fuente = $_POST['cbofuente'];
    $fechaagenda = $_POST['fecha_agenda'];


    $turno = $_POST['turno'];
    $sede = $_POST['nombre_sede'];
    $sedeprincipal = $_POST['nombre_sede'];
    $observaciones = $_POST['observaciones'];


    $contacto = $_POST['idusuario'];
    $agenda = "AGENDADO";
    $asistencia = "NO";
    $fecharegistro = date("Y-m-d");
    $horaregistro = date("H:i:s");


    // Crear una instancia del controlador y llamar a guardarUsuarios
    $obj = new agendaController();
    $obj->guardarAgendas($postulante, $tipodocumento, $numerodocumento, $edad, $celular, $celular2, $distrito, $fuente, $contacto, $observaciones, $agenda, $asistencia, $fecharegistro, $horaregistro, $fechaagenda, $turno, $sede, $sedeprincipal, $idusuario);
} else {
    //echo "Faltan datos necesarios para el registro.";
    $_SESSION['error'] = "Faltan datos necesarios para el registro.";
}
