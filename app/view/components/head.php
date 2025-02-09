    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>Agendamiento - <?php echo TITULO; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../../public/img/logo/favicon.ico"/>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../public/css/components/sidebar.css">
    <link rel="stylesheet" href="../../../public/css/components/navbar.css">
    <link rel="stylesheet" href="../../../public/css/components/plantilla.css">
    <link rel="stylesheet" href="../../../public/css/calendario/calendario.css">

    <!-- ICONOS FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- ***************************************** CSS **************************************** -->
     <?php
        // Función para cargar los archivos CSS
        function cargarArchivosCss($archivosCss, $basePath) {
            foreach ($archivosCss as $rutaCss) {
                $cssPath = $basePath . "/{$rutaCss}.css";
                
                // Verificar si el archivo existe
                if (file_exists($cssPath)) {
                    echo '<link rel="stylesheet" href="' . $cssPath . '">';
                }
            }
        }

        // Bucle de archivos JS de componentes
        cargarArchivosCss(ARCHIVOS_CSS, "../../../public/css/components");

        // Bucle de archivos JS según sección(Nombre de la carpeta)
        cargarArchivosCss(ARCHIVOS_CSS, "../../../public/css/" . SECCION);
     ?>
    <!-- ************************************************************************************** -->



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ***************************************** JS **************************************** -->
     <!-- <script src="../../../public/js/componente/sidebar.js"></script> -->
    <!-- ************************************************************************************** -->

