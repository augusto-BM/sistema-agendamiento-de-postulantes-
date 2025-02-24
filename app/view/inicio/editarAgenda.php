<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php';

if (isset($_GET['identificador'])) {
    $identificador = $_GET['identificador'];
}

require_once '../../controller/sedes/sedesController.php';
require_once '../../controller/agenda/agendaController.php';


$obj = new SedesController();
$sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);

$obj2 = new agendaController();
$date = $obj2->mostrarAgenda($identificador);
$reclutadores = $obj2->listarReclutadoress();

?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_editarAgenda">
        <div class="card mb-4">
            <h6 class="mt-3 fw-bold titulo-principal text-start ml-4" style="color: #566a7f; margin-left: 17px;">Editar
                Postulante <span class="campo_obligatorio campo_obligatorio">(*) Campo obligatorio</span>
                <?php echo ($idrol == 1) ? '<span style="position: absolute; right: 0; margin-right: 30px; margin-top: 5px; font-size: 13px; font-weight: bold; color:rgb(199, 72, 47)">Advertencia: <span style="color: #19727A;">Solo puedes reprogramar al postulante 3 veces.</span></span>': ''; ?>

            </h6>
            <div class="card-body">
                <div class="row mb-2">
                    <input type="hidden" id="idagenda" name="idagenda" value="<?= $identificador ?>">
                    <div class="col-md-4">
                        <label for="postulante" class="form-label ">POSTULANTE <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="postulante" name="postulante" class="form-control " value="<?= $date->postulante; ?>" placeholder="Sin dato">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-3">
                        <label for="celular" class="form-label">CELULAR <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control " value="<?= $date->celular; ?>" placeholder="Sin dato">
                            <div class="invalid-feedback">El número de celular debe tener 9 dígitos y solo contener
                                números.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="tipo_documento" class="form-label">DOC. IDENTIDAD <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="tipo_documento" name="tipo_documento" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="DNI" <?= ($date->tipodocumento == 'DNI') ? 'selected' : ''; ?>>DNI</option>
                            <option value="CARNET DE EXTR." <?= ($date->tipodocumento == 'CARNET DE EXTR.') ? 'selected' : ''; ?>>CARNET DE EXTR.</option>
                            <option value="PASAPORTE" <?= ($date->tipodocumento == 'PASAPORTE') ? 'selected' : ''; ?>>PASAPORTE</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="numero_documento" class="form-label">DNI/C.E/PASAPORTE <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control " value="<?= $date->numerodocumento; ?>" placeholder="Sin dato">
                        <div class="invalid-feedback">Este campo es obligatorio y debe contener solo números.</div>
                    </div>
                    <div class="col-md-1">
                        <label for="cboedad" class="form-label">EDAD <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cboedad" name="cboedad" class="form-control" required>
                            <?php
                            for ($edad = 18; $edad <= 50; $edad++) {
                                $selected = ($edad == $date->edad) ? 'selected' : '';
                                echo "<option value=\"$edad\" $selected>$edad</option>";
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="cbodistrito" class="form-label ">DISTRITO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cbodistrito" name="cbodistrito" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="ANCON" <?= ($date->distrito == 'ANCON') ? 'selected' : ''; ?>>ANCON</option>
                            <option value="ATE" <?= ($date->distrito == 'ATE') ? 'selected' : ''; ?>>ATE</option>
                            <option value="BARRANCO" <?= ($date->distrito == 'BARRANCO') ? 'selected' : ''; ?>>BARRANCO</option>
                            <option value="BREÑA" <?= ($date->distrito == 'BREÑA') ? 'selected' : ''; ?>>BREÑA</option>
                            <option value="CARABAYLLO" <?= ($date->distrito == 'CARABAYLLO') ? 'selected' : ''; ?>>CARABAYLLO</option>
                            <option value="CHACLACAYO" <?= ($date->distrito == 'CHACLACAYO') ? 'selected' : ''; ?>>CHACLACAYO</option>
                            <option value="CHORRILLOS" <?= ($date->distrito == 'CHORRILLOS') ? 'selected' : ''; ?>>CHORRILLOS</option>
                            <option value="CIENEGUILLA" <?= ($date->distrito == 'CIENEGUILLA') ? 'selected' : ''; ?>>CIENEGUILLA</option>
                            <option value="COMAS" <?= ($date->distrito == 'COMAS') ? 'selected' : ''; ?>>COMAS</option>
                            <option value="EL AGUSTINO" <?= ($date->distrito == 'EL AGUSTINO') ? 'selected' : ''; ?>>EL AGUSTINO</option>
                            <option value="INDEPENDENCIA" <?= ($date->distrito == 'INDEPENDENCIA') ? 'selected' : ''; ?>>INDEPENDENCIA</option>
                            <option value="JESUS MARIA" <?= ($date->distrito == 'JESUS MARIA') ? 'selected' : ''; ?>>JESUS MARIA</option>
                            <option value="LA MOLINA" <?= ($date->distrito == 'LA MOLINA') ? 'selected' : ''; ?>>LA MOLINA</option>
                            <option value="LA VICTORIA" <?= ($date->distrito == 'LA VICTORIA') ? 'selected' : ''; ?>>LA VICTORIA</option>
                            <option value="LIMA CENTRO" <?= ($date->distrito == 'LIMA CENTRO') ? 'selected' : ''; ?>>LIMA CENTRO</option>
                            <option value="LINCE" <?= ($date->distrito == 'LINCE') ? 'selected' : ''; ?>>LINCE</option>
                            <option value="LOS OLIVOS" <?= ($date->distrito == 'LOS OLIVOS') ? 'selected' : ''; ?>>LOS OLIVOS</option>
                            <option value="LURIGANCHO" <?= ($date->distrito == 'LURIGANCHO') ? 'selected' : ''; ?>>LURIGANCHO</option>
                            <option value="LURIN" <?= ($date->distrito == 'LURIN') ? 'selected' : ''; ?>>LURIN</option>
                            <option value="MAGDALENA DEL MAR" <?= ($date->distrito == 'MAGDALENA DEL MAR') ? 'selected' : ''; ?>>MAGDALENA DEL MAR</option>
                            <option value="MIRAFLORES" <?= ($date->distrito == 'MIRAFLORES') ? 'selected' : ''; ?>>MIRAFLORES</option>
                            <option value="PACHACAMAC" <?= ($date->distrito == 'PACHACAMAC') ? 'selected' : ''; ?>>PACHACAMAC</option>
                            <option value="PUCUSANA" <?= ($date->distrito == 'PUCUSANA') ? 'selected' : ''; ?>>PUCUSANA</option>
                            <option value="PUEBLO LIBRE" <?= ($date->distrito == 'PUEBLO LIBRE') ? 'selected' : ''; ?>>PUEBLO LIBRE</option>
                            <option value="PUENTE PIEDRA" <?= ($date->distrito == 'PUENTE PIEDRA') ? 'selected' : ''; ?>>PUENTE PIEDRA</option>
                            <option value="PUNTA HERMOSA" <?= ($date->distrito == 'PUNTA HERMOSA') ? 'selected' : ''; ?>>PUNTA HERMOSA</option>
                            <option value="PUNTA NEGRA" <?= ($date->distrito == 'PUNTA NEGRA') ? 'selected' : ''; ?>>PUNTA NEGRA</option>
                            <option value="RIMAC" <?= ($date->distrito == 'RIMAC') ? 'selected' : ''; ?>>RIMAC</option>
                            <option value="SAN BARTOLO" <?= ($date->distrito == 'SAN BARTOLO') ? 'selected' : ''; ?>>SAN BARTOLO</option>
                            <option value="SAN BORJA" <?= ($date->distrito == 'SAN BORJA') ? 'selected' : ''; ?>>SAN BORJA</option>
                            <option value="SAN ISIDRO" <?= ($date->distrito == 'SAN ISIDRO') ? 'selected' : ''; ?>>SAN ISIDRO</option>
                            <option value="SAN JUAN DE LURIGANCHO" <?= ($date->distrito == 'SAN JUAN DE LURIGANCHO') ? 'selected' : ''; ?>>SAN JUAN DE LURIGANCHO</option>
                            <option value="SAN JUAN DE MIRAFLORES" <?= ($date->distrito == 'SAN JUAN DE MIRAFLORES') ? 'selected' : ''; ?>>SAN JUAN DE MIRAFLORES</option>
                            <option value="SAN LUIS" <?= ($date->distrito == 'SAN LUIS') ? 'selected' : ''; ?>>SAN LUIS</option>
                            <option value="SAN MARTIN DE PORRES" <?= ($date->distrito == 'SAN MARTIN DE PORRES') ? 'selected' : ''; ?>>SAN MARTIN DE PORRES</option>
                            <option value="SAN MIGUEL" <?= ($date->distrito == 'SAN MIGUEL') ? 'selected' : ''; ?>>SAN MIGUEL</option>
                            <option value="SANTA ANITA" <?= ($date->distrito == 'SANTA ANITA') ? 'selected' : ''; ?>>SANTA ANITA</option>
                            <option value="SANTA MARIA DEL MAR" <?= ($date->distrito == 'SANTA MARI DEL MAR') ? 'selected' : ''; ?>>SANTA MARIA DEL MAR</option>
                            <option value="SANTA ROSA" <?= ($date->distrito == 'SANTA ROSA') ? 'selected' : ''; ?>>SANTA ROSA</option>
                            <option value="SANTIAGO DE SURCO" <?= ($date->distrito == 'SANTIAGO DE SURCO') ? 'selected' : ''; ?>>SANTIAGO DE SURCO</option>
                            <option value="SURQUILLO" <?= ($date->distrito == 'SURQUILLO') ? 'selected' : ''; ?>>SURQUILLO</option>
                            <option value="VILLA EL SALVADOR" <?= ($date->distrito == 'VILLA EL SALVADOR') ? 'selected' : ''; ?>>VILLA EL SALVADOR</option>
                            <option value="VILLA MARIA DEL TRIUNFO" <?= ($date->distrito == 'VILLA MARIA DEL TRIUNFO') ? 'selected' : ''; ?>>VILLA MARIA DEL TRIUNFO</option>
                        </select>

                    </div>
                    <div class="col-md-3">
                        <label for="cbofuente" class="form-label">FUENTE <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cbofuente" name="cbofuente" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="FACEBOOK" <?= ($date->fuente == 'FACEBOOK') ? 'selected' : ''; ?>>FACEBOOK</option>
                            <option value="COMPUTRABAJO" <?= ($date->fuente == 'COMPUTRABAJO') ? 'selected' : ''; ?>>COMPUTRABAJO</option>
                            <option value="INSTAGRAM" <?= ($date->fuente == 'INSTAGRAM') ? 'selected' : ''; ?>>INSTAGRAM</option>
                            <option value="TIKTOK" <?= ($date->fuente == 'TIKTOK') ? 'selected' : ''; ?>>TIKTOK</option>
                            <option value="VOLANTES" <?= ($date->fuente == 'VOLANTES') ? 'selected' : ''; ?>>VOLANTES</option>
                            <option value="REFERIDO" <?= ($date->fuente == 'REFERIDO') ? 'selected' : ''; ?>>REFERIDO</option>
                            <option value="BUMERAN" <?= ($date->fuente == 'BUMERAN') ? 'selected' : ''; ?>>BUMERAN</option>
                            <option value="VOLANTEO" <?= ($date->fuente == 'VOLANTEO') ? 'selected' : ''; ?>>VOLANTEO</option>
                            <option value="OTROS" <?= ($date->fuente == 'OTROS') ? 'selected' : ''; ?>>OTROS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_agenda" class="form-label">FECHA DE AGENDA <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="date" id="fecha_agenda" name="fecha_agenda" class="form-control " value="<?= $date->fechaagenda; ?>" placeholder="Sin fecha de agenda">
                        <input type="date" id="fecha_agenda_original" name="fecha_agenda_original" class="form-control " value="<?= $date->fechaagenda; ?>" placeholder="Sin fecha de agenda" style="display: none;">
                        <input type="hidden" value="<?= $date->fechaagendamodificacion; ?>" name="fechaagendamodificacion">
                    </div>
                    <div class="col-md-3">
                        <label for="nombre_sede" class="form-label">SEDE <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="nombre_sede" name="nombre_sede" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <?php if ($sedes): ?>
                                <?php foreach ($sedes as $sede): ?>
                                    <option value="<?= $sede->nombresede; ?>"
                                        <?= ($sede->nombresede == $date->sede) ? 'selected' : ''; ?>>
                                        <?= $sede->nombresede; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay empresas activas</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">

                    <div class="col-md-2">
                        <label for="turno" class="form-label ">TURNO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="turno" name="turno" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="MANANA" <?= ($date->turno == 'MANANA') ? 'selected' : ''; ?>>MANANA</option>
                            <option value="TARDE" <?= ($date->turno == 'TARDE') ? 'selected' : ''; ?>>TARDE</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="estado_agenda" class="form-label ">ESTADO AGENDA <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="estado_agenda" name="estado_agenda" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="AGENDADO" <?= ($date->agenda == 'AGENDADO') ? 'selected' : ''; ?>>AGENDADO</option>
                            <option value="REPROGRAMADO" <?= ($date->agenda == 'REPROGRAMADO') ? 'selected' : ''; ?>>REPROGRAMADO</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="estado_actual" class="form-label">ESTADO ACTUAL <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="estado_actual" name="estado_actual" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="" <?= ($date->estado == '') ? 'selected' : ''; ?>>NO DEFINIDO</option>
                            <option value="CONFIRMADO" <?= ($date->estado == 'CONFIRMADO') ? 'selected' : ''; ?>>CONFIRMADO</option>
                            <option value="NO RESPONDE" <?= ($date->estado == 'NO RESPONDE') ? 'selected' : ''; ?>>NO RESPONDE</option>
                            <option value="NO INTERESADO" <?= ($date->estado == 'NO INTERESADO') ? 'selected' : ''; ?>>NO INTERESADO</option>
                            <option value="LISTA NEGRA" <?= ($date->estado == 'LISTA NEGRA') ? 'selected' : ''; ?>>LISTA NEGRA</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="reclutador" class="form-label">RECLUTADOR <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="reclutador" name="reclutador" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <?php if ($reclutadores): ?>
                                <?php foreach ($reclutadores as $reclutador): ?>
                                    <option value="<?= $reclutador->idusuario; ?>"
                                        <?= ($reclutador->idusuario == $date->idusuario) ? 'selected' : ''; ?>>
                                        <?= $reclutador->nombreusuario; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No se encontraron reclutadores activos</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <label for="observaciones" class="form-label">OBSERVACIONES</label>
                        <textarea class="form-control " style="resize: none;" id="observaciones" name="observaciones" rows="3" placeholder="Sin observaciones"><?= $date->observaciones; ?></textarea>
                    </div>
                </div>

            </div>

            <!-- SI ES ROL USUARIO Y MODIFICO LA FECHA DE AGENDA TRES VECES YA NO VA PODER MODIFICAR SOLO LOS DEMAS ROLES VAN A PODER SEGUIR MODIFICANDO  -->
            <?php $condicionReagendado = ($idrol == 1 && $date->fechaagendamodificacion > 3); ?>

            <div class="d-flex justify-content-start">
                <button type="button" class="btn btn-retroceder" data-bs-dismiss="modal">Cancelar</button>

                <button type="submit" class="btn btn-registrar" name="submit" <?php echo $condicionReagendado ? 'disabled' : ''; ?>>Actualizar
                </button>
                <?php echo $condicionReagendado ? '<span class="">El Postulante ya sido reagendado tres veces</span>' : ''; ?>
            </div>

        </div>
    </form>
</div>

<!-- AJAX  -->
<script>
    $(document).ready(function() {
        $('#fecha_agenda').on('change', function() {
            $('#estado_agenda').val('REPROGRAMADO');
            $('#estado_agenda').css('pointer-events', 'none');
        });
    });
</script>

<script>
    //console.log("Script registrar usuarios cargado...");

    // Función para mostrar alertas con SweetAlert2 o alert tradicional
    function mostrarAlertaAgendar(tipo, titulo, mensaje) {
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
                    $(".formulario_editarAgenda")[0].reset();
                    $("#modalDinamico").modal("hide");
                },
            });
        } else {
            alert(mensaje);
        }
    }

    // Función para la validación de campo
    function validarCampo(selector, regex, errorMessage) {
        const value = $(selector).val().trim();
        return {
            isValid: value && (!regex || regex.test(value)),
            selector: selector,
            errorMessage: errorMessage,
        };
    }

    // Función para enviar los datos del formulario de forma asíncrona
    async function enviarFormulario(formData) {
        try {
            const response = await $.ajax({
                url: "update.php", // Tu archivo PHP
                type: "POST",
                data: formData,
                dataType: "json",
            });
            return response;

        } catch (error) {
            console.log("Error en la solicitud AJAX.");
            console.log("Error:", error);
            alert("Hubo un error al registrar los datos.");
        }
    }

    // Llamar a las funciones dentro de document.ready
    $(document).ready(function() {
        // Función para convertir los valores a mayúsculas
        convertirAMayusculas("#postulante, #observaciones, #numero_documento");
        prevenirNumeros("#postulante");
        prevenirLetras("#celular, #numero_documento");

        // Validación de formulario
        $(".formulario_editarAgenda").submit(async function(event) {
            event.preventDefault(); // Prevenir el envío tradicional del formulario

            let isValid = true;
            $(".form-control").removeClass("is-invalid");
            $(".invalid-feedback").hide();

            // Validación de campos específicos
            const validaciones = [
                validarCampo("#postulante", /^[a-zA-Z\s]+$/, "El nombre solo puede contener letras y espacios."),
                validarCampo("#numero_documento", null, "El numero de documento es obligatorio y debe ser numérico."),
                validarCampo("#celular", /^[0-9]{9}$/, "El celular debe tener 9 dígitos."),
            ];

            validaciones.forEach(validation => {
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
            const camposRequeridos = ["#tipo_documento", "#edad", "#distrito", "#fuente", "#nombre_sede", "#turno", "#fecha_agenda", "#estado_agenda", /* "#estado_actual", */ "#reclutador"];
            camposRequeridos.forEach((selector) => {
                if ($(selector).val() === "") {
                    isValid = false;
                    $(selector).addClass("is-invalid").next(".invalid-feedback").show();
                }
            });

            // Si todo es válido, enviar el formulario
            if (isValid) {
                const formData = $(".formulario_editarAgenda").serialize(); // Serializar los datos del formulario
                console.log("Datos del formulario:", formData);

                const response = await enviarFormulario(formData);


                if (response && response.success) {
                    mostrarAlertaAgendar("success", "¡Éxito!", response.message);
                } else {
                    // Aquí se maneja el error de duplicado de datos en la bbdd de dni y celular
                    if (response.message) {
                        response.message.forEach((msg) => {
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
                        mostrarAlertaAgendar("error", "Error", response.message);
                    }
                }

                //console.log("Cargando agendas...");
                cargarAgendas();
            }
        });
    });
</script>