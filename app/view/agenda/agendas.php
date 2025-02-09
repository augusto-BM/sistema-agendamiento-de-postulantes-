<?php
/* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
//titulo de la pagina
define('TITULO', 'Agenda');

//nombre de carpeta de la SECCION que pertence
define('SECCION', 'agenda');

//Nombre de los archivos CSS a importar en esta vista
define('ARCHIVOS_CSS', ['tablaUsuarios']);

//Nombre de los archivos JS a importar en esta vista
define('ARCHIVOS_JS', ['agendas']);

//Incluir las rutas dinamicos
require_once '../../../config/rutas/rutas.php';
/* ******************************************************************** */
session_start();

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
                                    Agendas                                     <!-- Botón para añadir usuario -->
                                    <button type="button" class="btn btn-primary abrirModal"
                                        style="background-color: #19727A; color: white; border: 0px; margin-left: 20px;"
                                        data-id="registrarAgenda" data-prefix="Agenda/"
                                        data-titulo="Agendar Postulante">
                                        Registar <i class="fa-solid fa-plus"></i>
                                    </button></h4>
                                    
                                <?php
                                require_once '../components/modalDinamico.php'; 
                                require_once '../../controller/sedes/sedesController.php';
                                require_once '../../controller/usuarios/usuariosController.php';

                                $obj = new SedesController();
                                $sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);

                                $obj2 = new usuariosController();
                                $reclutadores = $obj2->listarReclutadores();
                                ?>
                                <div class="row filtradoFecha">
                                    <!-- SELECT PARA FILTRAR LAS SEDES -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="filtroSedes">Sede</label>
                                        <select class="form-control" id="filtroSedes">
                                            <option value="TODOS" selected>TODOS</option>
                                            <?php if ($sedes): ?>
                                                <?php foreach ($sedes as $sede): ?>
                                                    <option value="<?= $sede->idsede; ?>"><?= $sede->nombresede; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay sedes disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="filtroSedes">Estado</label>
                                        <select class="form-control" id="filtroSedes">
                                            <option value="TODOS" selected>TODOS</option>
                                            <option value="AGENDADO">AGENDADO</option>
                                            <option value="NO RESPONDE">NO RESPONDE</option>
                                            <option value="NO INTERESADO">NO INTERESADO</option>
                                            <option value="ASISTIERON">ASISTIERON</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label" for="filtroSedes">Reclutador</label>
                                        <select class="form-control" id="filtroSedes">
                                            <option value="TODOS" selected>TODOS</option>
                                            <?php if ($reclutadores): ?>
                                                <?php foreach ($reclutadores as $reclutador): ?>
                                                    <option value="<?= $reclutador->idusuario; ?>"><?= $reclutador->nombreusuario; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay sedes disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="row mt-2 filtradoFecha">
                                    <div class="col-md-3">
                                        <label class="form-label" for="filtroFecha">Selecciona filtro:</label>
                                        <select class="form-control" id="filtroFecha">
                                            <option style="display: none;" value="" disabled>Fecha Personalizada</option>
                                            <option value="hoy">Hoy</option>
                                            <option value="ayer">Ayer</option>
                                            <option value="semana">Ultimos 7 dias</option>
                                            <option value="mes" selected>Ultimo mes</option>
                                            <option value="tresMeses">Ultimos 3 meses</option>
                                            <option value="seisMeses">Ultimos 6 meses</option>
                                            <option value="doceMeses">Ultimos 12 meses</option>
                                        </select>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fecha_col">
                                                    <label class="form-label" for="fechaInicio">Desde:</label>
                                                    <input class="form-control" type="date" id="fechaInicio">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fecha_col">
                                                    <label class="form-label" for="fechaFin">Hasta:</label>
                                                    <input class="form-control" type="date" id="fechaFin">
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex flex-column justify-content-end">
                                                <div class="fecha_col">
                                                    <button id="btnBuscarAgenda" class="btn btn-primary btn_buscar">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <!-- Bootstrap Table with Header - Light -->
                        <div class="card">
                            <div class="row">
                                <div class="col-md-7">
                                    <h5 class="titulo-tabla">Lista de Agendas</h5>
                                </div>

                                <div class="col-md-5 d-flex flex-column justify-content-center">
                                    <div class="row m-2">
                                        <div class="col-md-12 d-flex">
                                            <button id="btnBuscarSearchData" class="btn btn-primary btn_buscar"
                                                style="margin-right: 3px; display: none;">Buscar</button>
                                            <input class="form-control" type="search" id="buscarSearchData"
                                                placeholder="Buscar Postulante, DNI o Celular">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-center">
                                <i id="resultadoCount">Se encontró 0 resultados <span id="busquedaRealizadaUsuarios"></span>
                                </i>
                            </p>

                            <div class="table-responsive col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <table class="table table-borderless table-hover w-100 tabla-general" id="myTablaUsuarios">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="display: none;">id</th>
                                            <th class="text-center">#</th>
                                            <th>COLABORADOR</th>
                                            <th>DNI</th>
                                            <th style="display: none;">CORREO</th>
                                            <th>CELULAR</th>
                                            <th>SEDE</th>
                                            <th>FECHA DE INGRESO</th>
                                            <th>ESTADO</th>
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