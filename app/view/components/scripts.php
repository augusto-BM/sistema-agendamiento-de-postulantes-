<!-- BUCLE DE IMPORTANCION DE TODOS LOS SCRIPTS NECESARIOS -->
<script src="../../../public/js/components/sidebar.js"></script>
<script src="../../../public/js/components/funcionesGenerales.js"></script>
<?php
    // Función para generar los scripts JS
    function cargarArchivosJs($archivosJs, $basePath) {
        foreach ($archivosJs as $rutaJs) {
            // Construir el path
            $jsPath = $basePath . "/{$rutaJs}.js";
            
            // Verificar si el archivo existe
            if (file_exists($jsPath)) {
                echo '<script src="' . $jsPath . '"></script>';
            }
        }
    }

/*     // Bucle de archivos JS de componentes
    cargarArchivosJs(ARCHIVOS_JS, "../../../public/js/components"); */

    // Bucle de archivos JS según sección(Nombre de la carpeta)
    cargarArchivosJs(ARCHIVOS_JS, "../../../public/js/" . SECCION);

    

?>
<script src="../../../public/js/components/modalDinamico.js"></script>