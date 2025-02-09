<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php'
?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <form class="formulario_registrarUsuario">
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
                    <div class="col-md-6">
                        <label for="nombre_colaborador" class="form-label">NOMBRES Y APELLIDOS <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="nombre_colaborador" name="nombre_colaborador" class="form-control"
                            placeholder="Nombres y Apellidos">
                        <div class="invalid-feedback">Este campo es obligatorio y no puede contener números.</div>
                    </div>
                    <div class="col-md-3">
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
                </div>

                <div class="row mb-2">
                    <div class="col-md-3">
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

                    <div class="col-md-3">
                        <label for="numero_documento" class="form-label">NUMERO DE DOC. <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                            placeholder="12345678" maxlength="10">
                        <div class="invalid-feedback">Este campo es obligatorio y debe contener solo números.</div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="contacto" class="form-label">CONTACTO</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" style="color: #697a8d;">PE (+51)</span>
                            <input type="text" id="contacto" name="contacto" class="form-control" placeholder="Campo opcional"
                                maxlength="9">
                            <div class="invalid-feedback">El número de contacto debe tener 9 dígitos y solo contener
                                números.</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">

                    <div class="col-md-3">
                        <label for="cboedad" class="form-label">EDAD <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cboedad" name="cboedad" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

                    <div class="col-md-3">
                        <label for="cbodistrito" class="form-label">DISTRITO <span class="asterisco"
                                title="Campo obligatorio">*</span></label>
                        <select type="text" id="cbodistrito" name="cbodistrito" class="form-control" required>
                            <option value="" disabled selected>SELECCIONE</option>
                            <option value="ANCÓN">ANCÓN</option>
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
                            <option value="JESÚS MARÍA">JESÚS MARÍA</option>
                            <option value="LA MOLINA">LA MOLINA</option>
                            <option value="LA VICTORIA">LA VICTORIA</option>
                            <option value="LIMA CENTRO">LIMA CENTRO</option>
                            <option value="LINCE">LINCE</option>
                            <option value="LOS OLIVOS">LOS OLIVOS</option>
                            <option value="LURIGANCHO">LURIGANCHO</option>
                            <option value="LURÍN">LURÍN</option>
                            <option value="MAGDALENA DEL MAR">MAGDALENA DEL MAR</option>
                            <option value="MIRAFLORES">MIRAFLORES</option>
                            <option value="PACHACÁMAC">PACHACÁMAC</option>
                            <option value="PUCUSANA">PUCUSANA</option>
                            <option value="PUEBLO LIBRE">PUEBLO LIBRE</option>
                            <option value="PUENTE PIEDRA">PUENTE PIEDRA</option>
                            <option value="PUNTA HERMOSA">PUNTA HERMOSA</option>
                            <option value="PUNTA NEGRA">PUNTA NEGRA</option>
                            <option value="RÍMAC">RÍMAC</option>
                            <option value="SAN BARTOLO">SAN BARTOLO</option>
                            <option value="SAN BORJA">SAN BORJA</option>
                            <option value="SAN ISIDRO">SAN ISIDRO</option>
                            <option value="SAN JUAN DE LURIGANCHO">SAN JUAN DE LURIGANCHO</option>
                            <option value="SAN JUAN DE MIRAFLORES">SAN JUAN DE MIRAFLORES</option>
                            <option value="SAN LUIS">SAN LUIS</option>
                            <option value="SAN MARTÍN DE PORRES">SAN MARTÍN DE PORRES</option>
                            <option value="SAN MIGUEL">SAN MIGUEL</option>
                            <option value="SANTA ANITA">SANTA ANITA</option>
                            <option value="SANTA MARÍA DEL MAR">SANTA MARÍA DEL MAR</option>
                            <option value="SANTA ROSA">SANTA ROSA</option>
                            <option value="SANTIAGO DE SURCO">SANTIAGO DE SURCO</option>
                            <option value="SURQUILLO">SURQUILLO</option>
                            <option value="VILLA EL SALVADOR">VILLA EL SALVADOR</option>
                            <option value="VILLA MARÍA DEL TRIUNFO">VILLA MARÍA DEL TRIUNFO</option>
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
<div class="col-md-3">
    <label for="fecha_agenda" class="form-label">FECHA DE AGENDA <span class="asterisco" title="Campo obligatorio">*</span></label>
    <input type="date" id="fecha_agenda" name="fecha_agenda" class="form-control" value=""
           min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
    <div class="invalid-feedback">Este campo es obligatorio.</div>
</div>

<script>
    // Guardamos el valor inicial del campo de fecha
    const fechaInput = document.getElementById('fecha_agenda');
    let initialValue = fechaInput.value;

    // Escuchar cuando el valor del campo cambia
    fechaInput.addEventListener('change', function(event) {
        // Mostrar el valor seleccionado para verificar la entrada
        console.log("Fecha seleccionada: ", event.target.value);

        // Crear una fecha con la zona horaria UTC para evitar desplazamientos por zona horaria
        const selectedDate = new Date(event.target.value + "T00:00:00Z");
        
        // Obtener la fecha de hoy
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Aseguramos que solo compare la fecha sin la hora

        // Mostrar la fecha en formato de día de la semana
        console.log("Día de la semana (0=Domingo, 6=Sábado): ", selectedDate.getUTCDay());

        // Verificar si el día de la semana es domingo (0 es domingo)
        if (selectedDate.getUTCDay() === 0) {
            alert("No puedes seleccionar un domingo.");
            // Restauramos el valor original del input si es domingo
            event.target.value = initialValue;
        }
        // Verificar si la fecha seleccionada es anterior al día de hoy
        else if (selectedDate < today) {
            alert("No puedes seleccionar una fecha anterior al día de hoy.");
            // Restauramos el valor original del input si es fecha anterior a hoy
            event.target.value = initialValue;
        }
    });

    // Para manejar el caso de que el valor inicial se modifique fuera del evento (por ejemplo, con un valor predeterminado)
    fechaInput.addEventListener('focus', function() {
        initialValue = fechaInput.value;
    });
</script>

<!-- REGISTAR USUARIO MEDIANTE AJAX -->
<script src="../../../public/js/agenda/validarFechaAgenda.js"></script>
<!-- 
<script src="../../../public/js/usuarios/ajaxRegistrarUsuario.js"></script> -->