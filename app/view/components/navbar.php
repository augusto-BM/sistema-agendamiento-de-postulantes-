<!-- Barra de navegación -->
<nav class="mi-navbar">
    <!-- Iconos a la izquierda -->
    <div class="mi-navbar-izquierda">
        <a href="" class="abrirModalCalendario" title="Calendario" data-id="calendario" data-titulo="Dias no laborables"
            data-prefix="Calendario/"><i class="fa-solid fa-calendar-days"></i></a>
        <!-- <a href="#"><i class="fa-solid fa-cake-candles"></i></a> -->
        <?php require_once '../calendario/modalCalendario.php'; ?>

    </div>

    <!-- Espacio vacío en el centro -->
    <div class="mi-navbar-centro"></div>

    <!-- Menú desplegable a la derecha -->
    <div class="mi-navbar-derecha">

        <!-- <a href="#"><i class="fa-solid fa-clock"></i></a> -->

        <!-- Menú desplegable con icono de usuario -->
        <div class="mi-menu-desplegable">

            <div class="mi-usuario">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="mi-opciones-menu">
                <div class="mi-usuario-info">
                    <div class="mi-usuario-datoSesion">
                        <div class="mi-usuario-datoSesion-icono">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="mi-usuario-datoSesion-info">
                            <span><?= $nombreusuario ?></span><br>
                            <small><?= $nombrerol ?></small>
                        </div>
                    </div>
                    <hr>
                    <ul>
                        <a href="#">
                            <li><i class="fa-regular fa-user"></i><span>Mis datos</span></li>
                        </a>
                        <a href="#">
                            <li><i class="fa-solid fa-gear"></i><span>Cambiar contraseña</span></li>
                        </a>
                        <hr>
                        <a class="nav-link"
                            href="../../../config/cerrarsesion/cerrarsesion.php?idusuario=<?= $idusuario; ?>">
                            <li><i class="fa-solid fa-power-off"></i><span>Salir</span></li>
                        </a>
                    </ul>
                </div>
            </div>
        </div>
</nav>