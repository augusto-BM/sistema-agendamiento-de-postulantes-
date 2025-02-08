<?php
session_start();

require_once '../../model/login/loginModel.php';


if (isset($_POST['submit'])) {
    // Obtener el valor del campo de correo electrónico
    $email = htmlspecialchars($_POST['email_user'], ENT_QUOTES, 'UTF-8');
    $_SESSION['email_user'] = $_POST['email_user'];

    // Obtener el valor del campo de contraseña
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    // Validar los campos de entrada
    if (empty($email) && empty($password)) {
        $_SESSION['error'] = 'No puede haber campos vacíos!';
        header('location:../../../index.php');
    } elseif (empty($email)) {
        $_SESSION['error'] = 'Email no puede estar vacío!';
        header('location:../../../index.php');
    }/*  elseif (!preg_match("/^[a-zA-Z0-9]{3,50}$/", $email)) {
        $_SESSION['error'] = 'Ha introducido un email no válido!';
        header('location:../../../index.php');
    } */ elseif (empty($password)) {
        $_SESSION['error'] = 'Contraseña no puede estar vacía!';
        header('location:../../../index.php');
    } elseif (!preg_match("/^.{3,15}$/", $password)) {
        $_SESSION['error'] = 'Contraseña no válida!';
        header('location:../../../index.php');
    } else {
        // Controlador 
        autenticarUsuario($conn, $email, $password);
    }
}

function autenticarUsuario($conn, $email, $password) {
    // Llamar al modelo para buscar el usuario
    $result = buscarUsuarioPorEmail($conn, $email);
    
    // Verificar si se encontró un usuario con el correo electrónico proporcionado
    if (count($result) > 0) {
        $row = $result[0];
    
        // Obtener la contraseña y el estado almacenados
        $stored_password = $row['pass'];  
        $stored_estado = $row['estado'];  

        // Verificar si el usuario está activo
        if ($stored_estado != 2) {
            $_SESSION['error'] = 'Tu cuenta está inactiva. Contacta con el administrador.';
            header('location:../../../index.php');
            exit();
        }
    
        // Verificar la contraseña ingresada
        if ($password === $stored_password) {

            // Regenerar el ID de sesión para prevenir fijación de sesión
            session_regenerate_id(true);  // Regenera el ID de sesión
            
            $rol = htmlspecialchars($row['nombrerol'], ENT_QUOTES, 'UTF-8'); 
    
            // Eliminar el email almacenado en la sesión
            unset($_SESSION['email_user']);
    
            // Verificar si el rol es válido y redirigir
            if (!empty($rol)) {
                // Agrupar todos los valores en un array asociativo
                $_SESSION = array_merge($_SESSION, [
                    'exitoso' => 'BIENVENIDO, ' . htmlspecialchars($row['nombreusuario'], ENT_QUOTES, 'UTF-8'),

                    'idusuario' => htmlspecialchars($row['idusuario'], ENT_QUOTES, 'UTF-8'),
                    'nombreusuario' => htmlspecialchars($row['nombreusuario'], ENT_QUOTES, 'UTF-8'),
                    'tipodocumento' => htmlspecialchars($row['tipodocumento'], ENT_QUOTES, 'UTF-8'),
                    'dni' => htmlspecialchars($row['dni'], ENT_QUOTES, 'UTF-8'),
                    'activo' => htmlspecialchars($row['correo'], ENT_QUOTES, 'UTF-8'),
                    'celular' => htmlspecialchars($row['celular'], ENT_QUOTES, 'UTF-8'),
                    'turno' => htmlspecialchars($row['turno'], ENT_QUOTES, 'UTF-8'),
                    'estadousuario' => htmlspecialchars("ACTIVO", ENT_QUOTES, 'UTF-8'),
                    'fechaingreso' => htmlspecialchars($row['fechaingreso'], ENT_QUOTES, 'UTF-8'),

                    'idempresa' => htmlspecialchars($row['idempresa'], ENT_QUOTES, 'UTF-8'),
                    'nombreempresa' => htmlspecialchars($row['nombreempresa'], ENT_QUOTES, 'UTF-8'),
                    'empresaestado' => htmlspecialchars($row['empresaestado'], ENT_QUOTES, 'UTF-8'),

                    'idrol' => htmlspecialchars($row['idrol'], ENT_QUOTES, 'UTF-8'),
                    'nombrerol' => htmlspecialchars($row['nombrerol'], ENT_QUOTES, 'UTF-8'),

                    'idsede' => htmlspecialchars($row['idsede'], ENT_QUOTES, 'UTF-8'),
                    'nombresede' => htmlspecialchars($row['nombresede'], ENT_QUOTES, 'UTF-8'),
                    'metaagenda' => htmlspecialchars($row['metaagenda'], ENT_QUOTES, 'UTF-8'),
                ]);
            
                // Redirigir a la página de inicio
                header('Location: ../../view/inicio/inicio.php');
                exit();
            } else {
                // Si el rol no es válido, redirigir al login
                $_SESSION['error'] = 'Rol no reconocido';
                header('Location: ../../../index.php');
                exit();
            }
            
        } else {
            // Contraseña incorrecta
            $_SESSION['error'] = 'Contraseña incorrecta!';
            header('location:../../../index.php');
            exit();
        }
    } else {
        // Correo no registrado
        $_SESSION['error'] = 'Correo incorrecto o no registrado';
        header('location:../../../index.php');
        exit();
    }
    
}
?>
