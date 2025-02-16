<?php
/* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
//titulo de la pagina
define('TITULO', 'Calendario');

//nombre de carpeta de la SECCION que pertence
define('SECCION', 'calendario');

//Nombre de los archivos CSS a importar 
define('ARCHIVOS_CSS', ['calendario']);

//Nombre de los archivos JS a importar 
define('ARCHIVOS_JS', ['ajaxCalendario']);

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
                        <!-- Content wrapper -->
                        <div class="content-wrapper">
                            <!-- Content -->
                            <div class="container-xxl flex-grow-1 container-p-y contenedor-secundario">
                                <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Calendario /</span> Dias no laborables</h4>


                                <!-- Bootstrap Table with Header - Light -->
                                <div class="card" style="height: 450px; ">
                                    <div class="card calendario" style="max-width: 90%; margin: 0 auto; margin-top: 200px;">
                                        <div class="calendar-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                            <div class="calendar">
                                                <div class="calendar-header">
                                                    <button class="button" id="prev-month" title="Mes Anterior"><i class="fa-solid fa-arrow-left"></i></button>
                                                    <span id="calendar-year-month"></span>
                                                    <button class="button" id="next-month" title="Siguente Mes"><i class="fa-solid fa-arrow-right"></i></button>
                                                </div>
                                                <div class="calendar-days" id="calendar-days"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- / Content -->

                            <!-- FOOTER -->
                            <?php file_exists(RUTA_FOOTER) ? require_once RUTA_FOOTER : print "Error: No se pudo incluir el archivo de encabezado."; ?>
                            <!-- FINALIZA FOOTER -->

                            <div class="content-backdrop fade"></div>
                        </div>
                        <!-- Content wrapper -->
                </section>
            </main>
        </div>

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