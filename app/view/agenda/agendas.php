<?php
/* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
//titulo de la pagina
define('TITULO', 'Agenda');

//nombre de carpeta de la SECCION que pertence
define('SECCION', 'agenda');

//Nombre de los archivos CSS a importar en esta vista
define('ARCHIVOS_CSS', ['tablaAgendas']);

//Nombre de los archivos JS a importar en esta vista
define('ARCHIVOS_JS', ['ajaxListarAgenda']);

//Incluir las rutas dinamicos
require_once '../../../config/rutas/rutas.php';
/* ******************************************************************** */
session_start();
date_default_timezone_set('America/Lima');

header("Content-Type: text/html;charset=utf-8");

/* echo '<pre>'; print_r($_SESSION); echo '</pre>'; */
/* session_destroy(); */

if (isset($_SESSION['activo'])) {
    require_once '../../../config/datossesion/datossesion.php'

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <!-- RUTAS DE LA CABECERA -->
        <?php file_exists(RUTA_HEAD) ? require_once RUTA_HEAD : print "Error: No se pudo incluir el archivo de cabecera."; ?>
    </head>

    <body>
        <!-- EMPIEZA sidebar -->
        <?php file_exists(RUTA_SIDEBAR) ? require_once RUTA_SIDEBAR : print "Error: No se pudo incluir el archivo de sidebar."; ?>
        <!-- FINALIZA sidebar -->

        <!-- CONTENEDOR -->
        <div class="contenedor-general">
            <!-- EMPIEZA navbar -->
            <?php file_exists(RUTA_NAVBAR) ? require_once RUTA_NAVBAR : print "Error: No se pudo incluir el archivo de encabezado."; ?>
            <!-- FINALIZA navbar -->
            <main>
                <section>
                    <div class="container contenedor-principal">
                        <div class="content-wrapper">
                            <div class="container-xxl flex-grow-1 container-p-y contenedor-secundario">
                                <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Agenda /</span>
                                    Agendas <!-- Botón para añadir usuario -->
                                    <button type="button" class="btn btn-primary abrirModal"
                                        style="background-color: #19727A; color: white; border: 0px; margin-left: 20px;"
                                        data-id="registrarAgendas" data-prefix="Agenda/"
                                        data-titulo="Agendar Postulante">
                                        Registar <i class="fa-solid fa-plus"></i>
                                    </button>
                                </h4>

                                <?php
                                require_once '../../controller/sedes/sedesController.php';
                                require_once '../../controller/agenda/agendaController.php';

                                $obj = new SedesController();
                                $obj2 = new agendaController();

                                $sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);
                                $reclutadores = $obj2->listarReclutadoress($idusuario, $idrol, $idempresa);


                                $soloRolPermitido = in_array($idrol, [2, 4]); //MODERADOR y ADMIN 
                                $displayCompleto = $soloRolPermitido ? '' : 'display: none;';
                                ?>

                                <input type="hidden" id="idrolSesion" value="<?= $idrol; ?>" name="idrolSesion">
                                <input type="hidden" id="nombreUsuarioSesion" value="<?= $nombreusuario; ?>" name="nombreUsuarioSesion">
                                <input type="hidden" id="nombreSedeSesion" value="<?= $nombresede; ?>" name="nombreUsuarioSesion">

                                <div class="row filtradoAdmin">
                                    <div class="col-md-2">
                                        <label class="form-label" for="filtroFecha">Selecciona:</label>
                                        <input type="hidden" value="<?= date('Y-m-d'); ?>" id="fechaActual">
                                        <select class="form-control" id="filtroFecha">
                                            <option style="display: none;" value="" disabled>Fecha Personalizada</option>
                                            <option value="<?= date('Y-m-d'); ?>">Hoy</option>
                                            <option value="<?= date('Y-m-d', strtotime('-1 day')); ?>">Ayer</option>
                                            <option value="<?= date('Y-m-d', strtotime('-7 days')); ?>">Ultimos 7 dias</option>
                                            <option value="<?= date('Y-m-d', strtotime('-1 month')); ?>" selected>Ultimo mes</option>
                                            <option value="<?= date('Y-m-d', strtotime('-3 months')); ?>">Ultimos 3 meses</option>
                                            <option value="<?= date('Y-m-d', strtotime('-6 months')); ?>">Ultimos 6 meses</option>
                                            <option value="<?= date('Y-m-d', strtotime('-12 months')); ?>">Ultimos 12 meses</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="fecha_col">
                                            <label class="form-label" for="fechaInicio">Desde:</label>
                                            <input class="form-control" type="date" id="fechaInicio">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="fecha_col">
                                            <label class="form-label" for="fechaFin">Hasta:</label>
                                            <input class="form-control" type="date" id="fechaFin">
                                        </div>
                                    </div>

                                    <!-- SELECT PARA FILTRAR LAS SEDES -->
                                    <div class="col-md-2" style="<?= $displayCompleto; ?>">
                                        <label class="form-label" for="filtroSedes">Sede</label>
                                        <select class="form-control" id="filtroSedes">
                                            <option value="TODOS" selected>TODOS</option>
                                            <?php if ($sedes): ?>
                                                <?php foreach ($sedes as $sede): ?>
                                                    <option value="<?= $sede->nombresede; ?>"><?= $sede->nombresede; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay sedes disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2" style="<?= $displayCompleto; ?>">
                                        <label class="form-label" for="filtroEstado">Estado</label>
                                        <select class="form-control" id="filtroEstado">
                                            <option value="TODOS" selected>TODOS</option>
                                            <option value="AGENDADO">AGENDADO</option>
                                            <option value="NO RESPONDE">NO RESPONDE</option>
                                            <option value="NO INTERESADO">NO INTERESADO</option>
                                            <option value="ASISTIERON">ASISTIERON</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2" style="<?= $displayCompleto; ?>">
                                        <label class="form-label" for="filtroRecultador">Reclutador</label>
                                        <select class="form-control" id="filtroRecultador">
                                            <option value="TODOS" selected>TODOS</option>
                                            <?php if ($reclutadores): ?>
                                                <?php foreach ($reclutadores as $reclutador): ?>
                                                    <option value="<?= $reclutador->nombreusuario; ?>"><?= $reclutador->nombreusuario; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay reclutadores disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Bootstrap Table with Header - Light -->
                            <div class="card">
                                <div class="row">
                                    <div class="col-md-7">
                                        <h5 class="titulo-tabla mb-0">Lista de Agendas</h5>
                                    </div>

                                    <div class="col-md-5 d-flex flex-column justify-content-center">
                                        <div class="row m-2">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <button id="btn_buscarAgendaFiltros" class="btn btn-primary btn_buscar"
                                                    style="margin-right: 3px; display: none;">Buscar</button>
                                                <input class="form-control" type="search" id="buscarSearchData"
                                                    placeholder="Buscar Postulante, N° Doc o Telefono">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <p class="text-center">
                                    <i id="resultadoCountAgenda">Se encontró 0 resultados <span id="busquedaRealizadaAgendas"></span>
                                    </i>
                                </p>

                                <div class="table-responsive col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <table class="table table-borderless table-hover w-100 tabla-general" id="myTablaAgendas">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="display: none;">id</th>
                                                <th class="text-center">#</th>
                                                <th>FECHA REGISTRO</th>
                                                <th>POSTULANTE</th>
                                                <th>N° DOC.</th>
                                                <th>TELEFONO</th>
                                                <th>RECLUTADOR</th>
                                                <th>ESTADO</th>
                                                <!-- <th>DETALLE</th><td>${agenda.detalle ? agenda.detalle : "NO DEFINIDO"}</td> -->
                                                <th>FECHA AGENDA</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-body">
                                            <!-- Aquí van los datos de la tabla -->
                                            <!-- Los datos serán llenados por AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination m-2 d-flex justify-content-center" id="pagination"></div>
                            </div>

                            <!-- FOOTER -->
                            <?php file_exists(RUTA_FOOTER) ? require_once RUTA_FOOTER : print "Error: No se pudo incluir el archivo de encabezado."; ?>
                            <!-- FINALIZA FOOTER -->
                        </div>
                    </div>
                </section>
            </main>
        </div>
        <?php require_once '../components/alertaSesion.php' ?>

        <!-- RUTAS DE LOS SCRIPTS NECESARIOS -->
        <?php file_exists(RUTA_SCRIPTS) ? require_once RUTA_SCRIPTS : print "Error: No se pudo incluir el archivo de scripts."; ?>

    </body>

    </html>

<?php
} else {
    echo '  <script type="text/javascript">
                    alert("Debes Iniciar Sesion");
                    window.location.assign("../../../index.php");
                </script>';
}
?>