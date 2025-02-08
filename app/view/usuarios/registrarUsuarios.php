<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php'
?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_registrarUsuario">
        <div class="card mb-4">
            <h6 class="mt-3 fw-bold titulo-principal text-start ml-4" style="color: #566a7f; margin-left: 17px;">
                Registrar Colaborador <span class="campo_obligatorio campo_obligatorio">(*) Campo obligatorio</span>
            </h6>

            <?php
            require_once '../../controller/sedes/sedesController.php';
            require_once '../../controller/roles/rolesController.php';

            $obj = new SedesController();
            $obj2 = new RolesController();

            $sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);
            $roles = $obj2->listarRoles();
            ?>

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="nombre_colaborador" class="form-label">NOMBRES <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control"
                            placeholder="Nombres">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="apellido_paterno" class="form-label">APELLIDO PATERNO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control"
                            placeholder="Apellido Paterno">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="apellido_materno" class="form-label">APELLIDO MATERNO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="apellido_materno" name="apellido_materno" class="form-control"
                            placeholder="Apellido Materno">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="tipo_documento" class="form-label">TIPO DE DOC. <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="tipo_documento" name="tipo_documento" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="DNI">DNI</option>
                            <option value="CARNET DE EXTR.">CARNET DE EXTR.</option>
                            <option value="PASAPORTE">PASAPORTE</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="numero_documento" class="form-label">NUMERO DE DOC. <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                            placeholder="12345678" maxlength="10">
                        <div class="invalid-feedback">Este campo es obligatorio y debe contener solo números.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_ingreso" class="form-label">FECHA DE INGRESO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>

                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control"
                            value="<?= date('Y-m-d'); ?>">
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="id_empresa" class="form-label">EMPRESA <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="id_empresa" name="id_empresa" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <?php if ($sedes): ?>
                            <?php foreach ($sedes as $sede): ?>
                            <option value="<?= $sede->idempresa; ?>" data-nombresede="<?= $sede->nombresede; ?>"
                                data-nombreempresa="<?= $sede->nombreempresa; ?>"><?= $sede->nombreempresa; ?></option>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <option value="" disabled>No hay empresas activas</option>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <input type="hidden" id="nombre_sede" name="nombre_sede">

                    <div class="col-md-4">
                        <label for="turno" class="form-label">TURNO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="turno" name="turno" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="MANANA">MANANA</option>
                            <option value="TARDE">TARDE</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="id_rol" class="form-label">ROL <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="id_rol" name="id_rol" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <?php if ($roles): ?>
                            <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol->idrol; ?>"><?= $rol->nombrerol; ?></option>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <option value="" disabled>No hay roles</option>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="correo_electronico" class="form-label">CORREO ELECTRONICO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="email" id="correo_electronico" name="correo_electronico" class="form-control"
                            placeholder="john.doe@example.com">
                        <div class="invalid-feedback">Por favor, ingrese un correo electrónico válido.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="celular" class="form-label">CELULAR <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control" placeholder="999666999"
                                maxlength="9">
                            <div class="invalid-feedback">El número de celular debe tener 9 dígitos y solo contener
                                números.</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-start">
                <button type="button" class="btn btn-retroceder" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-registrar" name="submit">Registrar</button>
            </div>
        </div>
    </form>
</div>

<!-- SCRIPT PARA ASIGNARLE AL INPUT EL NOMBRE DE LA SEDE SELECCIONADO -->
<script>
document.getElementById('id_empresa').addEventListener('change', function() {
    // Obtener la opción seleccionada
    var selectedOption = this.options[this.selectedIndex];

    // Obtener el nombre de la sede (y opcionalmente el nombre de la empresa)
    var nombreSede = selectedOption.getAttribute('data-nombresede');

    // Asignar el valor al input invisible
    document.getElementById('nombre_sede').value = nombreSede;
});
</script>

<!-- REGISTAR USUARIO MEDIANTE AJAX -->


<script src="../../../public/js/usuarios/ajaxRegistrarUsuario.js"></script>