<?php

    session_start();
    $IdUserSession = $_REQUEST['idusuario'];
    
    //Eliminando las  cookies  en session
    setcookie ($_SESSION['idusuario'], "", 1);
    setcookie ($_SESSION['idusuario'], false);
    unset($_COOKIE[$_SESSION['idusuario']]);
    
    
    unset ($_SESSION['idusuario']); // Eliminar el IdUser de usuario
    session_unset(); //Eliminar todas las sesiones
    
    // Terminar la sesión:
    session_destroy();
    $parametros_cookies = session_get_cookie_params();
    setcookie(session_name(),0,1,$parametros_cookies["path"]);
        
    
    date_default_timezone_set("America/Lima" ) ;
    $hora = date('h:i a',time() - 3600*date('I'));
    $fecha = date("d/m/Y");
    $UltimaSession = $fecha." ".$hora;

    header('location:../../index.php');

?>