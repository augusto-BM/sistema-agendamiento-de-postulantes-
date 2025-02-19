<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php'
?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_registrarAgenda">
        <div class="card mb-4">
            <h6 class="mt-3 fw-bold titulo-principal text-start ml-4" style="color: #566a7f; margin-left: 17px;">
                Datos Personales del Postulante <span class="campo_obligatorio campo_obligatorio">(*) Campo obligatorio</span>
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
                    <input type="hidden" id="idusuario" name="idusuario" value="<?php echo $idusuario; ?>">
                    <div class="col-md-2">
                        <label for="celular" class="form-label">CELULAR <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d; font-size: 10px; padding: 0px 3px;">PE (+51)</span>
                            <input type="text" id="celular" name="celular" class="form-control" placeholder="999666999"
                                maxlength="9">
                            <div class="invalid-feedback">El número de celular debe tener 9 dígitos y solo contener
                                números.</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="contacto" class="form-label">CONTACTO</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d; font-size: 10px; padding: 0px 3px;">PE (+51)</span>
                            <input type="text" id="contacto" name="contacto" class="form-control" placeholder="Opcional"
                                maxlength="9">
                            <div class="invalid-feedback">El número de contacto debe tener 9 dígitos y solo contener
                                números.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="postulante" class="form-label">NOMBRES Y APELLIDOS <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="postulante" name="postulante" class="form-control"
                            placeholder="Nombres y Apellidos">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>

                    <div class="col-md-2">
                        <label for="tipo_documento" class="form-label">DOC. IDENTIDAD <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="tipo_documento" name="tipo_documento" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="DNI">DNI</option>
                            <option value="CARNET DE EXTR.">CARNET DE EXTR.</option>
                            <option value="PASAPORTE">PASAPORTE</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-2">
                        <label for="numero_documento" class="form-label">DNI/C.E/PASAPORTE <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                            placeholder="12345678" maxlength="10">
                        <div class="invalid-feedback">Este campo es obligatorio y debe contener solo números.</div>
                    </div>
                </div>



                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="cboedad" class="form-label">EDAD <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cboedad" name="cboedad" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <?php
                            for ($edad = 18; $edad <= 50; $edad++) {
                                echo "<option value=\"$edad\">$edad</option>";
                            }
                            ?>
                        </select>

                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-3">
                        <label for="cbodistrito" class="form-label">DISTRITO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cbodistrito" name="cbodistrito" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="ANCON">ANCON</option>
                            <option value="ATE">ATE</option>
                            <option value="BARRANCO">BARRANCO</option>
                            <option value="BREÑA">BREÑA</option>
                            <option value="CARABAYLLO">CARABAYLLO</option>
                            <option value="CHACLACAYO">CHACLACAYO</option>
                            <option value="CHORRILLOS">CHORRILLOS</option>
                            <option value="CIENEGUILLA">CIENEGUILLA</option>
                            <option value="COMAS">COMAS</option>
                            <option value="EL AGUSTINO">EL AGUSTINO</option>
                            <option value="INDEPENDENCIA">INDEPENDENCIA</option>
                            <option value="JESUS MARIA">JESUS MARIA</option>
                            <option value="LA MOLINA">LA MOLINA</option>
                            <option value="LA VICTORIA">LA VICTORIA</option>
                            <option value="LIMA CENTRO">LIMA CENTRO</option>
                            <option value="LINCE">LINCE</option>
                            <option value="LOS OLIVOS">LOS OLIVOS</option>
                            <option value="LURIGANCHO">LURIGANCHO</option>
                            <option value="LURIN">LURIN</option>
                            <option value="MAGDALENA DEL MAR">MAGDALENA DEL MAR</option>
                            <option value="MIRAFLORES">MIRAFLORES</option>
                            <option value="PACHACAMAC">PACHACAMAC</option>
                            <option value="PUCUSANA">PUCUSANA</option>
                            <option value="PUEBLO LIBRE">PUEBLO LIBRE</option>
                            <option value="PUENTE PIEDRA">PUENTE PIEDRA</option>
                            <option value="PUNTA HERMOSA">PUNTA HERMOSA</option>
                            <option value="PUNTA NEGRA">PUNTA NEGRA</option>
                            <option value="RIMAC">RIMAC</option>
                            <option value="SAN BARTOLO">SAN BARTOLO</option>
                            <option value="SAN BORJA">SAN BORJA</option>
                            <option value="SAN ISIDRO">SAN ISIDRO</option>
                            <option value="SAN JUAN DE LURIGANCHO">SAN JUAN DE LURIGANCHO</option>
                            <option value="SAN JUAN DE MIRAFLORES">SAN JUAN DE MIRAFLORES</option>
                            <option value="SAN LUIS">SAN LUIS</option>
                            <option value="SAN MARTIN DE PORRES">SAN MARTIN DE PORRES</option>
                            <option value="SAN MIGUEL">SAN MIGUEL</option>
                            <option value="SANTA ANITA">SANTA ANITA</option>
                            <option value="SANTA MARIA DEL MAR">SANTA MARIA DEL MAR</option>
                            <option value="SANTA ROSA">SANTA ROSA</option>
                            <option value="SANTIAGO DE SURCO">SANTIAGO DE SURCO</option>
                            <option value="SURQUILLO">SURQUILLO</option>
                            <option value="VILLA EL SALVADOR">VILLA EL SALVADOR</option>
                            <option value="VILLA MARIA DEL TRIUNFO">VILLA MARIA DEL TRIUNFO</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-3">
                        <label for="cbofuente" class="form-label">FUENTE <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cbofuente" name="cbofuente" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="FACEBOOK">FACEBOOK</option>
                            <option value="COMPUTRABAJO">COMPUTRABAJO</option>
                            <option value="INSTAGRAM">INSTAGRAM</option>
                            <option value="TIKTOK">TIKTOK</option>
                            <option value="VOLANTES">VOLANTES</option>
                            <option value="REFERIDO">REFERIDO</option>
                            <option value="BUMERAN">BUMERAN</option>
                            <option value="VOLANTEO">VOLANTEO</option>
                            <option value="OTROS">OTROS</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-3">
                        <label for="fecha_agenda" class="form-label">FECHA DE AGENDA <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="date" id="fecha_agenda" name="fecha_agenda" class="form-control" value="" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
                        <label for="turno" class="form-label">TURNO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="turno" name="turno" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="MANANA">MANANA</option>
                            <option value="TARDE">TARDE</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-3">
                        <label for="id_empresa" class="form-label">EMPRESA <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="id_empresa" name="id_empresa" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <?php if ($sedes): ?>
                                <?php foreach ($sedes as $sede): ?>
                                    <option value="<?= $sede->idempresa; ?>"
                                        data-nombresede="<?= $sede->nombresede; ?>"
                                        data-nombreempresa="<?= $sede->nombreempresa; ?>"
                                        <?= ($sede->nombreempresa == $nombreempresa) ? 'selected' : ''; ?>>
                                        <?= $sede->nombreempresa; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No hay empresas activas</option>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <input type="hidden" id="nombre_sede" name="nombre_sede">
                </div>

                <div class="row mb-2">
                    <div class="col-md-12">
                        <label for="observaciones" class="form-label">OBSERVACIONES</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="5" placeholder="Campo Opcional" style="resize: none;"></textarea>
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

<!-- SCRIPT AJAX PARA ASIGNARLE AL INPUT EL NOMBRE DE LA SEDE SELECCIONADO -->
<script>
    $(document).ready(function() {
        var nombreSede = $('#id_empresa option:selected').data('nombresede');
        $('#nombre_sede').val(nombreSede);

        $('#id_empresa').change(function() {
            var nombreSede = $('#id_empresa option:selected').data('nombresede');
            $('#nombre_sede').val(nombreSede);
        });
    });
</script>

<!-- SCRIPT AJAX VALIDAR INPUT FECHA DE AGENDA LABORABLE -->
<script>
    console.log("Cargando el script validarFechaAgenda.js");

    $(document).ready(async function() {
        let fechaAsignada; // Variable para almacenar la fecha asignada inicialmente

        try {
            // Cargar las fechas no laborables desde el archivo JSON
            const fechasNoLaborables = await obtenerFechasNoLaborables();
            //console.log("Fechas no laborables cargadas:", fechasNoLaborables);

            // Obtener la fecha actual en Lima
            const fechaLima = obtenerFechaLima();

            //console.log("Fecha actual en Lima:", fechaLima);

            // Crear una nueva fecha de mañana tomando solo el día
            let manana = new Date(fechaLima);
            manana.setHours(0, 0, 0, 0); // Asegurarse de que no tenga hora, minuto, etc.
            manana.setDate(manana.getDate() + 1); // Sumar un día
            const mananaStr = formatearFecha(manana); // Fecha de mañana en formato 'YYYY-MM-DD'
            //console.log("Fecha de mañana:", mananaStr);

            // Crear un Set con las fechas no laborables
            const fechasNoLaborablesSet = new Set(
                fechasNoLaborables.map(fecha => formatearFecha(new Date(fecha.year, fecha.month - 1, fecha.day)))
            );
            //console.log("Conjunto de fechas no laborables:", [...fechasNoLaborablesSet]);

            // Verificar si la fecha de mañana está en el Set de fechas no laborables
            if (!fechasNoLaborablesSet.has(mananaStr)) {
                //console.log("La fecha de mañana no es laborable.");
                fechaAsignada = mananaStr;
                $("#fecha_agenda").val(mananaStr);
            } else {
                //console.log("La fecha de mañana es no laborable. Buscando siguiente día laborable.");
                // Buscar la siguiente fecha laborable
                const siguienteLaborable = getSiguienteLaborable(manana, fechasNoLaborablesSet);
                const siguienteLaborableStr = formatearFecha(siguienteLaborable);
                //console.log("Siguiente fecha laborable:", siguienteLaborableStr);
                fechaAsignada = siguienteLaborableStr;
                $("#fecha_agenda").val(siguienteLaborableStr);
            }

            // Establecer la fecha mínima para el input de fecha
            //console.log("Estableciendo la fecha mínima para el input de fecha:", mananaStr);
            $("#fecha_agenda").attr("min", mananaStr);

            // Validación al cambiar la fecha
            $("#fecha_agenda").on('change', function() {
                const selectedDate = new Date($(this).val());
                const selectedDateStr = formatearFecha(selectedDate);
                //console.log("Fecha seleccionada por el usuario:", selectedDateStr);

                // Validar si el día seleccionado es domingo o no laborable
                if (selectedDate.getDay() === 6) { // 6 = Domingo
                    //console.log("El día seleccionado es domingo.");
                    alert("No se puede seleccionar un domingo. Por favor, elige otro día.");
                    $(this).val(fechaAsignada); // Restaurar la fecha previamente asignada
                } else if (fechasNoLaborablesSet.has(selectedDateStr)) {
                    //console.log("El día seleccionado es no laborable.");
                    alert("El día seleccionado es no laborable. Por favor, elige otro día.");
                    $(this).val(fechaAsignada); // Restaurar la fecha previamente asignada
                }
            });

        } catch (error) {
            console.error("Error al cargar las fechas no laborables:", error);
        }

        // Función asíncrona para obtener las fechas no laborables
        async function obtenerFechasNoLaborables() {
            try {
                //console.log("Realizando la solicitud AJAX para obtener las fechas no laborables...");
                const response = await $.ajax({
                    url: "../calendario/fechasCalendario.json", // Ruta al archivo JSON
                    type: "GET",
                    dataType: "json"
                });
                //console.log("Fechas no laborables obtenidas:", response);
                return response; // Devuelve las fechas no laborables
            } catch (error) {
                console.error("Error al obtener las fechas no laborables: ", error);
                throw new Error("No se pudo cargar el archivo de fechas no laborables");
            }
        }

        // Función para obtener el siguiente día laborable
        function getSiguienteLaborable(fechaInicio, fechasNoLaborablesSet) {
            let siguienteFecha = new Date(fechaInicio);
            //console.log("Buscando el siguiente día laborable a partir de:", formatearFecha(siguienteFecha));

            // Avanzar al siguiente día y verificar si es laborable (si no es domingo y no es una fecha no laborable)
            while (true) {
                siguienteFecha.setDate(siguienteFecha.getDate() + 1); // Avanzar al siguiente día
                const siguienteFechaStr = formatearFecha(siguienteFecha);
                //console.log("Comprobando fecha:", siguienteFechaStr);

                // Verificar si la fecha no es no laborable y no es domingo
                if (!fechasNoLaborablesSet.has(siguienteFechaStr) && siguienteFecha.getDay() !== 0) {
                    //console.log("Encontrada siguiente fecha laborable:", siguienteFechaStr);
                    return siguienteFecha;
                }
            }
        }
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
                    $(".formulario_registrarAgenda")[0].reset();
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
                url: "store.php", // Tu archivo PHP
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
        prevenirLetras("#celular, #contacto, #numero_documento");

        // Validación de formulario
        $(".formulario_registrarAgenda").submit(async function(event) {
            event.preventDefault(); // Prevenir el envío tradicional del formulario

            let isValid = true;
            $(".form-control").removeClass("is-invalid");
            $(".invalid-feedback").hide();

            // Validación de campos específicos
            const validaciones = [
                validarCampo("#postulante", /^[a-zA-Z\s]+$/, "El nombre solo puede contener letras y espacios."),
                validarCampo("#numero_documento", null, "El numero de documento es obligatorio y debe ser numérico."),
                validarCampo("#celular", /^[0-9]{9}$/, "El celular debe tener 9 dígitos."),
                validarCampo("#contacto", /^([0-9]{9})?$/, "El contacto debe tener 9 dígitos."), // Tiene que tener 9 dígitos o ninguno
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
            const camposRequeridos = ["#tipo_documento", "#empresa_id", "#turno", "#fecha_agenda"];
            camposRequeridos.forEach((selector) => {
                if ($(selector).val() === "") {
                    isValid = false;
                    $(selector).addClass("is-invalid").next(".invalid-feedback").show();
                }
            });

            // Si todo es válido, enviar el formulario
            if (isValid) {
                const formData = $(".formulario_registrarAgenda").serialize(); // Serializar los datos del formulario

                const response = await enviarFormulario(formData);

                if (response && response.success) {
                    mostrarAlertaAgendar("success", "¡Éxito!", response.message);
                } else {
                    // Aquí se maneja el error de duplicado
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