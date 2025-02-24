<?php
/* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
//titulo de la pagina
define('TITULO', 'Inicio');

//nombre de carpeta de la SECCION que pertence
define('SECCION', 'inicio');

//Nombre de los archivos CSS a importar en esta vista
define('ARCHIVOS_CSS', [/* 'tablaUsuarios' */]);

//Nombre de los archivos JS a importar en esta vista
define('ARCHIVOS_JS', ['ajaxListarAgenda']);

//Incluir las rutas dinamicos
require_once '../../../config/rutas/rutas.php';
/* ******************************************************************** */
session_start();
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('America/Lima');

/* echo '<pre>'; print_r($_SESSION); echo '</pre>'; */
/* session_destroy(); */

//RECIBIR LOS DATOS DE LA SEDE Y EL TURNO SELECCIONADO DE LOS CARDS DE INICIO
if (isset($_GET['sedeSeleccionadaCard']) && isset($_GET['turnoSeleccionadoCard']) && isset($_GET['fechaAsignadaNextCard'])) {
    $sedeSeleccionadaCard = $_GET['sedeSeleccionadaCard'];
    $turnoSeleccionadoCard = $_GET['turnoSeleccionadoCard'];
    $fechaAsignadaNextCard = $_GET['fechaAsignadaNextCard'];
} else {
    echo '  <script type="text/javascript">window.location.assign("inicio.php");</script>';
    exit();
}


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
                                <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Inicio /</span>
                                    Agendados <?= $sedeSeleccionadaCard ?> </h4>
                            </div>
                            <br>

                            <!-- Bootstrap Table with Header - Light -->
                            <div class="card">
                                <input type="hidden" id="idrolSesion" value="<?= $idrol; ?>" name="idrolSesion">
                                <input type="hidden" id="idUsuarioSesion" value="<?= $idusuario; ?>" name="nombreUsuarioSesion">
                                <input type="hidden" id="sedeSeleccionadaCard" value="<?= $sedeSeleccionadaCard; ?>" name="sedeSeleccionadaCard">
                                <input type="hidden" id="turnoSeleccionadoCard" value="<?= $turnoSeleccionadoCard; ?>" name="turnoSeleccionadoCard">
                                <input type="hidden" id="fechaAsignadaNextCard" value="<?= $fechaAsignadaNextCard; ?>" name="turnoSeleccionadoCard">

                                <div class="row">
                                    <div class="col-md-7">
                                        <h5 class="titulo-tabla">Agenda para el <?= date('d-m-Y', strtotime($fechaAsignadaNextCard)) ?> turno <?= $turnoSeleccionadoCard ?> </h5>


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
        <!-- FINALIZA CONTENEDOR -->
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