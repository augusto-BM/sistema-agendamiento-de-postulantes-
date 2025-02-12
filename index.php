<?php
session_start();
header("Content-Type: text/html;charset=utf-8");
if (!empty($_SESSION['activo'])) {
    header("Location: ./app/view/inicio/inicio.php");
    exit();
}
/* echo '<pre>'; print_r($_SESSION); echo '</pre>'; */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamiento de Postulantes</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./public/img/logo/favicon.ico"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./public/css/login/login.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./public/js/login/login.js"></script>
</head>

<body>
    <?php include './app/view/components/alertaSesion.php' ?>
    <div class="container d-flex align-items-center min-vh-100">
        <div class="authentication-wrapper authentication-basic w-100">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card p-3" style="max-width: 400px; margin: auto;">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand text-center">
                            <a href="index.php" class="app-brand-link gap-2">
                                <img src="./public/img/login/jbgoperator.png" alt="inventario_logo" width="300px" class="mx-auto d-block">
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h5 class="mb-2 mt-4 ">Bienvenidos al Agendamiento de Postulantes! 👋</h5>
                        <p class="mb-4">Ingresa a tu cuenta e inicia la Aventura!!!!</p>

                        <form id="formAuthentication" class="mb-3" action="./app/controller/login/loginController.php" method="POST">
                            <div class="mb-3">
                                <label for="user" class="form-label">CORREO O DNI</label>
                                <input type="text" class="form-control" name="email_user" id="user" placeholder="Ingresa tu correo electronico o DNI" autofocus required value="<?php echo isset($_SESSION['email_user']) ? htmlspecialchars($_SESSION['email_user'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
                            </div>
                            
                            <div class="mb-4 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">CONTRASEÑA</label>
                                </div>
                                <div class="input-group" style="position: relative;">
                                    <input type="password" class="form-control" name="password" id="pass" placeholder="Ingresa tu contraseña" aria-describedby="password" required />
                                    <button class="btn" type="button" id="togglePassword" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); border: none; background: none; z-index: 1000;">
                                        <i class="fa-regular fa-eye-slash ver_pass" title="Ver contraseña"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3 mt-4">
                                <button class="btn d-grid w-100 p-2" style="background-color: #19727A; color: white; font-size: 14px; font-weight: 500;" type="submit" id="enviar" name="submit" >Iniciar Sesi&oacute;n</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>