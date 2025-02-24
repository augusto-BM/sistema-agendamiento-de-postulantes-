<?php 
    session_start();
    date_default_timezone_set('America/Lima'); 
    require_once '../../../config/datossesion/datossesion.php';
    
    if (isset($_GET['identificador'])) {
        $identificador = $_GET['identificador'];
    }
    require_once '../../controller/usuarios/usuariosController.php';

    $obj = new usuariosController();
    $date = $obj->mostrarUsuario($identificador);
?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_registrarArticulo">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-2">
                    <input type="hidden" id="idusuario" value="<?= $identificador ?>">
                    <div class="col-md-8">
                        <label for="nombre_colaborador" class="form-label ver">NOMBRES Y APELLIDOS</label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control ver" value="<?= $date->nombreusuario; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label">ESTADO </label>
                        <input type="text" id="estado" name="estado" class="form-control ver" value="<?php echo ($date->estado == 2) ? 'ACTIVO' : (($date->estado == 3) ? 'INACTIVO' : 'SIN VALOR'); ?>" placeholder="Sin dato" readonly>

                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="tipo_documento" class="form-label">TIPO DE DOC. </label>
                        <input type="text" id="tipo_documento" name="tipo_documento" class="form-control ver" value="<?= $date->tipodocumento; ?>" placeholder="Sin dato" readonly>

                    </div>

                    <div class="col-md-4">
                        <label for="numero_documento" class="form-label">NUMERO DE DOC. </label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control ver" value="<?= $date->dni; ?>" placeholder="Sin dato" readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_ingreso" class="form-label">FECHA DE INGRESO </label>
                        <input type="text" id="fecha_ingreso" name="fecha_ingreso" class="form-control ver" value="<?= !empty($date->fechaingreso) ? date('d-m-Y', strtotime($date->fechaingreso)) : ''; ?>" placeholder="Sin dato" readonly>

                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="id_empresa" class="form-label">EMPRESA </label>
                        <input type="text" id="id_empresa" name="id_empresa" class="form-control ver" value="<?= $date->nombreempresa; ?>" placeholder="Sin dato" readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="turno" class="form-label">TURNO </label>
                        <input type="text" id="turno" name="turno" class="form-control ver" value="<?= $date->turno; ?>" placeholder="Sin dato" readonly>

                    </div>



                    <div class="col-md-4">
                        <label for="id_rol" class="form-label">ROL </label>
                        <input type="text" id="id_rol" name="id_rol" class="form-control ver" value="<?= $date->nombrerol; ?>" placeholder="Sin dato" readonly>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="correo_electronico" class="form-label">CORREO ELECTRONICO </label>
                        <input type="text" id="correo_electronico" name="correo_electronico" class="form-control ver" value="<?= $date->correo; ?>" placeholder="Sin dato" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="celular" class="form-label">CELULAR </label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control ver" value="<?= $date->celular; ?>" placeholder="Sin dato" maxlength="9" readonly>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
