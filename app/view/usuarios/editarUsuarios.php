<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php';

if (isset($_GET['identificador'])) {
    $identificador = $_GET['identificador'];
}

require_once '../../controller/usuarios/usuariosController.php';

$obj3 = new usuariosController();
$date = $obj3->mostrarUsuario($identificador);
?>
<div class="container contenedor_editarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_editarUsuario">
        <div class="card mb-4">
            <h6 class="mt-3 fw-bold titulo-principal text-start ml-4" style="color: #566a7f; margin-left: 17px;">Editar
                Colaborador <span class="campo_obligatorio campo_obligatorio">(*) Campo obligatorio</span></h6>

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
                    <input type="hidden" id="idusuario" name="idusuario" value="<?= $identificador ?>">
                    <div class="col-md-8">
                        <label for="nombre_colaborador" class="form-label">NOMBRES Y APELLIDOS <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control"
                            placeholder="Nombres" value="<?= $date->nombreusuario; ?>">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="tipo_documento" class="form-label">TIPO DE DOC. <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="tipo_documento" name="tipo_documento" class="form-control" required>
                            <option value="" disabled <?= ($date->tipodocumento == "") ? 'selected' : ''; ?>>SELECCIONE
                            </option>
                            <option value="DNI" <?= ($date->tipodocumento == "DNI") ? 'selected' : ''; ?>>DNI</option>
                            <option value="CARNET DE EXTR."
                                <?= ($date->tipodocumento == "CARNET DE EXTR.") ? 'selected' : ''; ?>>CARNET DE EXTR.
                            </option>
                            <option value="PASAPORTE" <?= ($date->tipodocumento == "PASAPORTE") ? 'selected' : ''; ?>>
                                PASAPORTE</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>


                    <div class="col-md-4">
                        <label for="numero_documento" class="form-label">NUMERO DE DOC. <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                            placeholder="12345678" value="<?= $date->dni; ?>">
                        <div class="invalid-feedback">Este campo es obligatorio y debe contener solo números.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_ingreso" class="form-label">FECHA DE INGRESO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>

                        <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control"
                            value="<?= $date->fechaingreso; ?>">
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
                                    <option value="<?= $sede->idempresa; ?>"
                                        <?= ($sede->idempresa == $date->idempresa) ? 'selected' : ''; ?>>
                                        <?= $sede->nombreempresa; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay empresas activas</option>
                            <?php endif; ?>
                        </select>

                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="turno" class="form-label">TURNO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="turno" name="turno" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="MANANA" <?= ($date->turno == 'MANANA') ? 'selected' : ''; ?>>MANANA</option>
                            <option value="TARDE" <?= ($date->turno == 'TARDE') ? 'selected' : ''; ?>>TARDE</option>
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
                                    <option value="<?= $rol->idrol; ?>" <?= ($rol->idrol == $date->idrol) ? 'selected' : ''; ?>>
                                        <?= $rol->nombrerol; ?>
                                    </option>
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
                            placeholder="john.doe@example.com" value="<?= $date->correo; ?>">
                        <div class="invalid-feedback">Por favor, ingrese un correo electrónico válido.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="pass" class="form-label">CONTRASEÑA<span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="password" id="pass" name="pass" class="form-control" placeholder="Contraseña"
                            value="<?= $date->pass; ?>">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="celular" class="form-label">CELULAR <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control" placeholder="999666999"
                                maxlength="9" value="<?= $date->celular; ?>">
                            <div class="invalid-feedback">El número de celular debe tener 9 dígitos y solo contener
                                números.</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-start">
                <button type="button" class="btn btn-retroceder" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-registrar" name="submit">Actualizar</button>
            </div>
        </div>
    </form>
</div>

<!-- AJAX EDITAR USUARIOS -->
<script>
    console.log("Script archivo editar usuario ...");

    // Función para mostrar alertas con SweetAlert2 o alert tradicional
    function mostrarAlertaEditar(tipo, titulo, mensaje) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: tipo,
                title: titulo,
                text: mensaje,
                confirmButtonText: "Aceptar",
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => Swal.showLoading(),
                willClose: () => {
                    $(".formulario_editarUsuario")[0].reset();
                    $("#modalDinamico").modal("hide");
                },
            });
        } else {
            alert(mensaje);
        }
    }

    // Llamar a las funciones dentro de document.ready
    $(document).ready(function() {

        // Llamar a las funciones para los campos correspondientes
        convertirAMayusculas("#nombre_colaborador, #correo_electronico");
        prevenirNumeros("#nombre_colaborador");
        prevenirLetras("#numero_documento, #celular");

        // Validación de formulario
        $(".formulario_editarUsuario").submit(async function(event) {
            event.preventDefault(); // Prevenir el envío tradicional del formulario

            let isValid = true;
            $(".form-control").removeClass("is-invalid");
            $(".invalid-feedback").hide();

            // Validación de campos específicos
            const validaciones = [
                validarCampo("#nombre_colaborador", /^[a-zA-Z\s]+$/, "El nombre solo puede contener letras y espacios."),
                validarCampo("#numero_documento", null, "El numero de documento es obligatorio y debe ser numérico."),
                validarCampo("#correo_electronico", /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, "El correo electrónico es inválido."),
                validarCampo("#pass", /^.{4,}$/, "La contraseña es inválida, debe ser mayor a tres caracteres."),
                validarCampo("#celular", /^[0-9]{9}$/, "El celular debe tener 9 dígitos."),
            ];

            validaciones.forEach((validation) => {
                if (!validation.isValid) {
                    isValid = false;
                    $(validation.selector)
                        .addClass("is-invalid")
                        .next(".invalid-feedback")
                        .text(validation.errorMessage)
                        .show();
                }
            });

            // Validaciones de campos seleccionados
            const camposRequeridos = ["#tipo_documento", "#empresa_id", "#turno", "#rol", "#fecha_ingreso"];
            camposRequeridos.forEach((selector) => {
                if ($(selector).val() === "") {
                    isValid = false;
                    $(selector).addClass("is-invalid").next(".invalid-feedback").show();
                }
            });

            // Si todo es válido, enviar el formulario
            if (isValid) {
                const formData = $(".formulario_editarUsuario").serialize(); // Serializar los datos del formulario

                try {
                    const response = await fetch("update.php", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded", // Tipo de contenido de la solicitud
                        },
                    });

                    const data = await response.json(); // Parseamos la respuesta como JSON

                    console.log(data);
                    if (data.success) {
                        mostrarAlertaEditar("success", "¡Éxito!", data.message);
                    } else {
                        let errorMessages = data.message; // Array de mensajes de error

                        // Verificar si hay errores específicos y asignarles la clase is-invalid
                        if (errorMessages && errorMessages.length > 0) {
                            errorMessages.forEach((msg) => {
                                if (msg.includes("DNI")) {
                                    $("#numero_documento").addClass("is-invalid");
                                    $("#numero_documento").next(".invalid-feedback").text(msg).show();
                                }
                                if (msg.includes("celular")) {
                                    $("#celular").addClass("is-invalid");
                                    $("#celular").next(".invalid-feedback").text(msg).show();
                                }
                            });
                        } else {
                            // Si no hay errores de campo, mostrar el mensaje de SweetAlert
                            mostrarAlertaEditar("error", "Error", data.message);
                        }
                    }

                    // Cargar usuarios después de la edición
                    cargarUsuarios();

                } catch (error) {
                    console.error("Error en la solicitud:", error);
                    alert("Hubo un error al editar los datos.");
                }
            }
        });
    });
</script>