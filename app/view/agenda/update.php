<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../controller/agenda/agendaController.php';

if (isset($_POST['idagenda']) && isset($_POST['postulante']) && isset($_POST['celular']) && isset($_POST['tipo_documento']) && isset($_POST['numero_documento']) && isset($_POST['cboedad']) && isset($_POST['cbodistrito']) && isset($_POST['cbofuente']) && isset($_POST['fecha_agenda']) && isset($_POST['fecha_agenda_original']) && isset($_POST['fechaagendamodificacion']) && isset($_POST['nombre_sede']) && isset($_POST['turno']) && isset($_POST['estado_agenda']) && isset($_POST['estado_actual']) && isset($_POST['reclutador']) && isset($_POST['observaciones'])) {

    // Asignamos las variables con los datos del formulario
    $idagenda = $_POST['idagenda'];
    $postulante = $_POST['postulante'];
    $celular = $_POST['celular'];
    $tipodocumento = $_POST['tipo_documento'];
    $numerodocumento = $_POST['numero_documento'];
    $edad = $_POST['cboedad'];

    $distrito = $_POST['cbodistrito'];
    $fuente = $_POST['cbofuente'];
    $fechaagenda = $_POST['fecha_agenda'];
    $fecha_agenda_original = $_POST['fecha_agenda_original'];
    $fechaagendamodificacion = $_POST['fechaagendamodificacion'];
    $sede = $_POST['nombre_sede'];
    $sedeprincipal = $_POST['nombre_sede'];

    $turno = $_POST['turno'];
    $agenda = $_POST['estado_agenda'];
    $estado = $_POST['estado_actual'];
    $idusuario = $_POST['reclutador'];
    $contacto = $_POST['reclutador'];
    $observaciones = $_POST['observaciones'];

    // Crear una instancia del controlador y llamar a guardarUsuarios
    $obj = new agendaController();
    $obj->actualizarAgenda($idagenda, $postulante, $tipodocumento, $numerodocumento, $edad, $celular,  $distrito, $fuente, $contacto, $observaciones, $agenda, $estado, $fechaagenda, $fecha_agenda_original, $fechaagendamodificacion, $turno, $sede, $sedeprincipal, $idusuario);
} else {
    //echo "Faltan datos necesarios para el registro.";
    $_SESSION['error'] = "Faltan datos necesarios para actualizar los registro.";
}
