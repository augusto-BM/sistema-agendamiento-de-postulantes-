<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php';

if (isset($_GET['identificador'])) {
    $identificador = $_GET['identificador'];
}
require_once '../../controller/agenda/agendaController.php';

$obj = new agendaController();
$date = $obj->mostrarAgenda($identificador);

?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_verAgenda">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-2">
                    <input type="hidden" id="idagenda" value="<?= $identificador ?>">
                    <div class="col-md-4">
                        <label for="postulante" class="form-label ver">POSTULANTE</label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control ver" value="<?= $date->postulante; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="celular" class="form-label">CELULAR <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control ver" value="<?= $date->celular; ?>" placeholder="Sin dato"
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">TIPO DE DOC. </label>
                        <input type="text" id="estado" name="estado" class="form-control ver" value="<?= $date->tipodocumento; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">N° DOC </label>
                        <input type="text" id="estado" name="estado" class="form-control ver" value="<?= $date->numerodocumento; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-1">
                        <label for="edad" class="form-label">EDAD </label>
                        <input type="text" id="edad" name="edad" class="form-control ver" value="<?= $date->edad; ?>" placeholder="Sin dato" readonly>
                    </div>

                </div>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="postulante" class="form-label ver">DISTRITO</label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control ver" value="<?= $date->distrito; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">FUENTE</label>
                        <input type="text" id="estado" name="estado" class="form-control ver" value="<?= $date->fuente; ?>" placeholder="Sin dato" readonly>
                    </div>

                    <div class="col-md-2">
                        <label for="estado" class="form-label">FECHA DE AGENDA</label>
                        <input type="date" id="estado" name="estado" class="form-control ver" value="<?= $date->fechaagenda; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="estado" class="form-label">SEDE</label>
                        <input type="text" id="estado" name="estado" class="form-control ver" value="<?= $date->sede; ?>" placeholder="Sin dato" readonly>
                    </div>

                    <div class="col-md-3">
                        <label for="celular" class="form-label">CONTACTO </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control ver" value="<?= $date->celular2; ?>" placeholder="Sin dato"
                                readonly>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="postulante" class="form-label ver">TURNO</label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control ver" value="<?= $date->turno; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="edad" class="form-label">FECHA DE REGISTRO </label>
                        <input type="date" id="edad" name="edad" class="form-control ver" value="<?= $date->fecharegistro; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="edad" class="form-label">HORA DE REGISTRO </label>
                        <input type="time" id="edad" name="edad" class="form-control ver" value="<?= $date->horaregistro; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="edad" class="form-label">FECHA DE PROGRAMACION </label>
                        <input type="" id="edad" name="edad" class="form-control ver" value="<?= $date->fechareprogramacion; ?>" placeholder="Sin fecha" readonly>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="estado" class="form-label">RECLUTADOR</label>
                        <input type="text" id="estado" name="estado" class="form-control ver" value="<?= $date->nombreusuario; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="postulante" class="form-label ver">ESTADO AGENDA</label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control ver" value="<?= $date->agenda; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="edad" class="form-label">ESTADO ACTUAL </label>
                        <input type="text" id="edad" name="edad" class="form-control ver" value="<?= empty($date->estado) ? 'NO DEFINIDO' : $date->estado; ?>" placeholder="Sin dato" readonly>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <label for="estado" class="form-label">OBSERVACIONES</label>
                        <textarea class="form-control ver" style="resize: none;" id="observaciones" name="observaciones" rows="3" placeholder="Sin observaciones" readonly><?= $date->observaciones; ?></textarea>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>