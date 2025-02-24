<?php
/* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
//titulo de la pagina
define('TITULO', 'Inicio');

//nombre de carpeta de la SECCION que pertence
define('SECCION', 'inicio');

//Nombre de los archivos CSS a importar 
define('ARCHIVOS_CSS', ['card']);

//Nombre de los archivos JS a importar 
define('ARCHIVOS_JS', ['ajaxListarTop', 'ajaxObjetivosCard', 'ajaxEstadosCard', 'ajaxDireccionarCardEstados']);

//Incluir las rutas dinamicos
require_once '../../../config/rutas/rutas.php';
/* ******************************************************************** */
session_start();

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('America/Lima');

/* echo "El ID de sesión es: " . session_id();  */ // Muestra el ID aleadtorio de la sesión actual
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
                                <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Inicio /</span>
                                    Estadistica de hoy</h4>
                                <?php
                                require_once '../../controller/sedes/sedesController.php';
                                $obj = new SedesController();
                                $sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);

                                $soloRolPermitido = in_array($idrol, [1, 2, 4]); //AUXILIAR, MODERADOR y ADMIN 
                                $displayCompleto = $soloRolPermitido ? '' : 'display: none;';
                                $displayMargenTurno = $soloRolPermitido ? 'mt-4' : '';
                                ?>
                                <input type="hidden" id="fecha-hoy" value="<?= date("Y-m-d");  ?>">
                                <input type="hidden" id="idRolSesion" value="<?= $idrol; ?>">
                                <input type="hidden" id="idUsuarioSesion" value="<?= $idusuario; ?>">

                                <!-- SELECT PARA FILTRAR LAS SEDES -->
                                <div class="row mt-2 filtradoFecha">
                                    <div class="col-md-3" style="<?= $displayCompleto; ?>">
                                        <label class="form-label" for="filtroSedes">Sede</label>
                                        <select class="form-control" id="filtroSedes">
                                            <?php if ($sedes): ?>
                                                <?php foreach ($sedes as $sede): ?>
                                                    <option value="<?= $sede->nombresede; ?>"><?= $sede->nombresede; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay sedes disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- FINALIZA SELECT PARA FILTRAR LAS SEDES -->

                            </div>

                            <!-- TABLAS TOP 5  -->
                            <div class="row" style="<?= $displayCompleto; ?>">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 mt-3">
                                    <div class="card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="titulo-tabla">Top #5 Agendados Diario</h5>
                                            </div>
                                        </div>
                                        <div class="table-responsive col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <table class="table table-borderless table-hover w-100 tabla-general"
                                                id="myTablaUsuarios">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="display: none;">id</th>
                                                        <th class="text-center">#</th>
                                                        <th>COLABORADOR</th>
                                                        <th>AGENDADOS</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0" id="data-bodyAgendados">
                                                    <!-- Aquí van los datos de la tabla -->
                                                    <!-- Los datos serán llenados por AJAX -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 mt-3">
                                    <div class="card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="titulo-tabla">TOP Asistencia Diaria</h5>
                                            </div>
                                        </div>
                                        <div class="table-responsive col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <table class="table table-borderless table-hover w-100 tabla-general"
                                                id="myTablaColaboradoresAsistencias">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="display: none;">id</th>
                                                        <th class="text-center">#</th>
                                                        <th>COLABORADOR</th>
                                                        <th>ASISTIERON</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0" id="data-bodyAsistencia">
                                                    <!-- Aquí van los datos de la tabla -->
                                                    <!-- Los datos serán llenados por AJAX -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FINALIZA TABLAS TOP 5  -->

                            <div class="row <?= $displayMargenTurno ?> filtradoTurno">

                                <!-- TABLAS TURNO  -->
                                <div class="col-md-3">
                                    <label class="form-label" for="turno">Turno</label>
                                    <select type="text" id="turno" name="turno" class="form-control" required>
                                        <option value="MANANA">MANANA</option>
                                        <option value="TARDE">TARDE</option>
                                    </select>
                                </div>
                                <!-- FINALIZA TABLAS TURNO  -->

                                <!-- CARD DE ESTADOS DE AGENDADOS  -->
                                <div class="estados_card">
                                    <!-- CARD DE OBJETIVOS -->
                                    <div class="row mt-3 mb-4">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" style="<?= ($idrol != 3 ? 'display: none;' : '') ?>">
                                            <div class="card mt-2 mb-2" style="background-color: #ACCCF2; color: #515151;">
                                                <i class="fa-solid fa-person"></i>
                                                <span><b id="countVoy"></b></span>
                                                <span>VOY</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" style="<?= ($idrol != 3 ? 'display: none;' : '') ?>">
                                            <div class="card mt-2 mb-2" style="background-color: #ACCCF2; color: #515151;">
                                                <i class="fa-solid fa-bullseye"></i>
                                                <span><b>15</b></span>
                                                <span>OBJETIVO</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2" style="background-color: #BCE69F; color: #515151;">
                                                <i class="fa-solid fa-people-group"></i>
                                                <span><b id="countVamos"></b></span>
                                                <span>VAMOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2" style="background-color: #BCE69F; color: #515151;">
                                                <i class="fa-solid fa-bullseye"></i>
                                                <span><b>100</b></span>
                                                <span>OBJETIVO</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FINALIZA CARD DE OBJETIVOS  -->

                                    <!-- CARD DE ESTADOS -->
                                    <div class="row estado_final_card">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver agendados">
                                            <div class="card mt-2 mb-2 cardEstados" id="agendaCard" data-nombrearchivo="agenda">
                                                <i class="fa fa-user" style="color: #ff9800;"></i>
                                                <span><b id="countAgenda"></b></span>
                                                <span>AGENDA</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver entrevistados">
                                            <div class="card mt-2 mb-2 cardEstados" id="entrevistaCard" data-nombrearchivo="entrevista">
                                                <i class="fa-solid fa-users" style="color: #2196f3;"></i>
                                                <span><b id="countEntrevista"></b></span>
                                                <span>ENTREVISTA</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver confirmados">
                                            <div class="card mt-2 mb-2 cardEstados" id="confirmadosCard" data-nombrearchivo="confirmados">
                                                <i class="fa-solid fa-circle-check" style="color: #2196f3;"></i>
                                                <span><b id="countConfirmados"></b></span>
                                                <span>CONFIRMADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver asistieron">
                                            <div class="card mt-2 mb-2 cardEstados" id="asistieronCard" data-nombrearchivo="asistieron">
                                                <i class="fa-solid fa-calendar-check" style="color: #009688;"></i>
                                                <span><b id="countAsistieron"></b></span>
                                                <span>ASISTIERON</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row estado_final_card">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver no responden">
                                            <div class="card mt-2 mb-2 cardEstados" id="norespondeCard" data-nombrearchivo="noResponde">
                                                <i class="fa-solid fa-phone-slash" style="color: #e91e63;"></i>
                                                <span><b id="countNoresponde"></b></span>
                                                <span>NO RESPONDE</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver no interesados">
                                            <div class="card mt-2 mb-2 cardEstados" id="nointeresadoCard" data-nombrearchivo="noInteresados">
                                                <i class="fa-solid fa-comment-slash" style="color: #e91e63;"></i>
                                                <span><b id="countNointeresado"></b></span>
                                                <span>NO INTERESADO</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3" title="Ver lista negra">
                                            <div class="card mt-2 mb-2 cardEstados" id="listanegraCard" data-nombrearchivo="listaNegra">
                                                <i class="fa-solid fa-ban" style="color: #1f1f1f;"></i>
                                                <span><b id="countListanegra"></b></span>
                                                <span>LISTA NEGRA</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FINALIZA CARD DE ESTADOS -->
                                </div>
                                <!-- FINALIZA CARD DE ESTADOS DE AGENDADOS  -->

                                <!-- CARD DE REDES SOCIALES -->
                                <div class="fuente_card mt-3">
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-brands fa-facebook" style="background-color: #3b5998;"> FACEBOOK</i>
                                                <span><b id="countFacebook"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa fa-briefcase" style="background-color: #0077B5;"> COMPUTRABAJO</i>
                                                <span><b id="countComputrabajo"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-brands fa-instagram" style="background-color: #E1306C;"> INSTAGRAM</i>
                                                <span><b id="countInstagram"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-brands fa-tiktok" style="background-color: #000000;"> TIKTOK</i>
                                                <span><b id="countTiktok"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-solid fa-people-arrows" style="background-color: rgba(255, 159, 0, 1);"> REFERIDO</i>
                                                <span><b id="countReferido"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-solid fa-chevron-right" style="background-color: rgba(255, 205, 0, 1);"> BUMERAN</i>
                                                <span><b id="countBumeran"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-solid fa-arrows-spin" style="background-color: rgba(76, 175, 80, 1);"> OTROS</i>
                                                <span><b id="countOtros"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                            <div class="card mt-2 mb-2">
                                                <i class="fa-solid fa-calendar" style="background-color: #3D61F7;"> RE-AGENDADOS</i>
                                                <span><b id="countReagendados"></b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- FINALIZA CARD DE REDES SOCIALES -->

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