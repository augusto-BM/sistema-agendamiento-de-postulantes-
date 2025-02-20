<?php
/* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
//titulo de la pagina
define('TITULO', 'Inicio');

//nombre de carpeta de la SECCION que pertence
define('SECCION', 'inicio');

//Nombre de los archivos CSS a importar 
define('ARCHIVOS_CSS', ['card']);

//Nombre de los archivos JS a importar 
define('ARCHIVOS_JS', [/* 'principal' */]);

//Incluir las rutas dinamicos
require_once '../../../config/rutas/rutas.php';
/* ******************************************************************** */
session_start();

header("Content-Type: text/html;charset=utf-8");
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
                                    Estadisticas</h4>
                                <?php
                                require_once '../../controller/sedes/sedesController.php';
                                $obj = new SedesController();
                                $sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);
                                ?>
                                <div class="row mt-2 filtradoFecha">
                                    <!-- SELECT PARA FILTRAR LAS SEDES -->
                                    <div class="col-md-3">
                                        <label class="form-label" for="filtroSedes">Sede</label>
                                        <select class="form-control" id="filtroSedes">
                                            <?php if ($sedes): ?>
                                                <?php foreach ($sedes as $sede): ?>
                                                    <option value="<?= $sede->idsede; ?>"><?= $sede->nombresede; ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay sedes disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="row">
                                <div class="col-6">
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
                                                <tbody class="table-border-bottom-0" id="data-body">
                                                    <!-- Aquí van los datos de la tabla -->
                                                    <!-- Los datos serán llenados por AJAX -->
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="pagination m-2 d-flex justify-content-center" id="pagination"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h5 class="titulo-tabla">TOP Asistencia Diaria</h5>
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
                                                        <th>ASISTIERON</th>
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
                                </div>
                            </div>

                            <div class="row mt-4 filtradoTurno">
                                <!-- SELECT PARA FILTRAR LAS SEDES -->
                                <div class="col-md-3">
                                    <label class="form-label" for="turno">Turno</label>
                                    <select type="text" id="turno" name="turno" class="form-control" required>
                                        <option value="MANANA">MANANA</option>
                                        <option value="TARDE">TARDE</option>
                                    </select>
                                </div>

                                <div class="estados_card">
                                    <div class="row mt-3 mb-5">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card" style="background-color: #ACCCF2; color: #515151;">
                                                <i class="fa-solid fa-people-group"></i>
                                                <span><b>2</b></span>
                                                <span>VOY</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card" style="background-color: #ACCCF2; color: #515151;">
                                                <i class="fa-solid fa-bullseye"></i>
                                                <span><b>15</b></span>
                                                <span>OBJETIVO</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card" style="background-color: #BCE69F; color: #515151;">
                                                <i class="fa-solid fa-people-group"></i>
                                                <span><b>33</b></span>
                                                <span>VAMOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card" style="background-color: #BCE69F; color: #515151;">
                                                <i class="fa-solid fa-bullseye"></i>
                                                <span><b>100</b></span>
                                                <span >OBJETIVO</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row estado_final_card mt-4">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa fa-user" style="color: #ff9800;"></i>
                                                <span><b>33</b></span>
                                                <span>AGENDA</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-users" style="color: #2196f3;"></i>
                                                <span><b>33</b></span>
                                                <span>ENTREVISTA</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-circle-check" style="color: #2196f3;"></i>
                                                <span><b>33</b></span>
                                                <span>CONFIRMADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-calendar-check" style="color: #009688;"></i>
                                                <span><b>33</b></span>
                                                <span>CONFIRMADOS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row estado_final_card mt-3">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-phone-slash" style="color: #e91e63;"></i>
                                                <span><b>33</b></span>
                                                <span>NO RESPONDE</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-comment-slash" style="color: #e91e63;"></i>
                                                <span><b>33</b></span>
                                                <span>NO INTERESADO</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-ban" style="color: #1f1f1f;"></i>
                                                <span><b>33</b></span>
                                                <span>LISTA NEGRA</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                        </div>
                                    </div>
                                </div>

                                <div class="fuente_card mt-5">
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-brands fa-facebook" style="background-color: #3b5998;"> FACEBOOK</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa fa-briefcase" style="background-color: #0077B5;"> COMPUTRABAJO</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-brands fa-instagram" style="background-color: #E1306C;"> INSTAGRAM</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-brands fa-tiktok" style="background-color: #000000;"> TIKTOK</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-receipt" style="background-color: rgba(255, 87, 51, 1);"> VOLANTES</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-people-arrows" style="background-color: rgba(255, 159, 0, 1);"> REFERIDO</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-chevron-right" style="background-color: rgba(255, 205, 0, 1);"> BUMERAN</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-note-sticky" style="background-color: rgba(76, 175, 80, 1);"> VOLANTEO</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-brands fa-facebook" style="background-color: #00A0D7;"> OTROS</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-3">
                                            <div class="card">
                                                <i class="fa-solid fa-calendar" style="background-color: #3D61F7;"> RE-AGENDADOS</i>
                                                <span><b>33</b></span>
                                                <span>AGENDADOS</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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