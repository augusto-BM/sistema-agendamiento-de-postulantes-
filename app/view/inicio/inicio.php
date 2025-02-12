<?php
    /* **** CONFIGURAR CONSTANTES OBLIGATORIAS EN TODAS LAS PAGINAS DINAMICO **** */
        //titulo de la pagina
        define('TITULO', 'Inicio');   

        //nombre de carpeta de la SECCION que pertence
        define('SECCION', 'inicio');  

        //Nombre de los archivos CSS a importar 
        define('ARCHIVOS_CSS', [/* 'sidebar','navbar', 'plantilla' */]);           
        
        //Nombre de los archivos JS a importar 
        define('ARCHIVOS_JS', ['principal']);  

        //Incluir las rutas dinamicos
        require_once '../../../config/rutas/rutas.php';
    /* ******************************************************************** */
    session_start();

    header("Content-Type: text/html;charset=utf-8");

    /* echo '<pre>'; print_r($_SESSION); echo '</pre>'; */
    /* session_destroy(); */

    if(isset($_SESSION['activo'])){
        require_once '../../../config/datossesion/datossesion.php'

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- RUTAS DE LA CABECERA -->
    <?php file_exists(RUTA_HEAD) ? require_once RUTA_HEAD : print "Error: No se pudo incluir el archivo de cabecera.";?>
</head>

<body>
    <!-- EMPIEZA sidebar -->
    <?php file_exists(RUTA_SIDEBAR) ? require_once RUTA_SIDEBAR : print "Error: No se pudo incluir el archivo de sidebar."; ?>
    <!-- FINALIZA sidebar -->

    <!-- CONTENEDOR -->
    <div class="contenedor-general">
            <!-- EMPIEZA navbar -->
            <?php file_exists(RUTA_NAVBAR) ? require_once RUTA_NAVBAR : print "Error: No se pudo incluir el archivo de encabezado.";?>
            <!-- FINALIZA navbar -->
        <main>
            <section>
                <div class="container contenedor-principal">
                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y contenedor-secundario">
                            <h4 class="fw-bold py-3 mb-1"><span class="text-muted fw-light">Inicio /</span> Estadistica</h4>

                            <div class="row mt-2 filtradoFecha">
                                    <div class="col-md-3">
                                        <label class="form-label" for="filtroFecha">Selecciona filtro:</label>
                                        <select class="form-control" id="filtroFecha">
                                            <option style="display: none;" value="" disabled>Fecha Personalizada</option>
                                            <option value="hoy" selected>Hoy</option>
                                            <option value="ayer">Ayer</option>
                                            <option value="semana">Ultimos 7 dias</option>
                                            <option value="mes">Ultimo mes</option>
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
                                                    <button id="btnBuscarFecha" class="btn btn-primary" style="display: none;">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <br>
                            
                            <!-- Bootstrap Table with Header - Light -->
                            <div class="card">
                                
                                <div class="row">
                                    <div class="col-md-5">
                                        <h5 class="card-header">Lista de Asistencias</h5>
                                        <?php     echo "El ID de sesión es: " . session_id();  // Muestra el ID de la sesión actual ?>
                                    </div>


                                    <div class="col-md-2 d-flex flex-column justify-content-center">
                                        <div class="fecha_col">
                                            <button class="btn" id="btnExportarExcel" title="Exportar a Excel">
                                                <i class="fa-solid fa-file-excel" style="color: #f5f5f9 !important; font-size: 24px;"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-5 d-flex flex-column justify-content-center">
                                        <div class="row m-2">
                                            <div class="col-md-12 d-flex">
                                                <button id="btnBuscarSearchData" class="btn btn-primary" style="margin-right: 3px; display: none;">Buscar</button>
                                                <input class="form-control" type="search" id="buscarSearchData" placeholder="Buscar asistencias por Colaborador, DNI, Empresa o Area.">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-center">
                                    <i id="resultadoCount">Se encontró 0 resultados del <span id="fechaInicioTexto"></span> al <span id="fechaFinTexto"></span></i>
                                </p>

                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover w-100" id="myTablaReporteAsistencia">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="display: none;">id</th> <!-- Columna oculta -->
                                                <th class="text-center">#</th>
                                                <th>Colaborador</th>
                                                <th>DNI</th>
                                                <th>Fecha</th>
                                                <th>Hora inicio</th>
                                                <th>Hora fin</th>
                                                <th>Empresa</th>
                                                <th>Area</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-body">
                                            <!-- Aquí van los datos de la tabla -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="pagination m-2 d-flex justify-content-center" id="pagination"></div>
                            </div>
                        </div>
                        <!-- / Content -->

                            <!-- FOOTER -->
                                <?php file_exists(RUTA_FOOTER) ? require_once RUTA_FOOTER : print "Error: No se pudo incluir el archivo de encabezado.";?>
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