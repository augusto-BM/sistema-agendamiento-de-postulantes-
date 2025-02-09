<!--  -->
<div class="sidebar-menu-btn" id="sidebar-menu-btn">
    <i class="bx bx-menu"></i>
    <i class="bx bx-x"></i>
</div>

<aside class="sidebar-plantilla" id="sidebar-plantilla">
    <div class="header-sidebar">
        <div class="menu-btn-arrow-sidebar d-none" id="menu-btn-arrow-sidebar">
            <i class="bx bx-chevron-left"></i>
        </div>
        <div class="brand-sidebar">
            <!-- <img src="../../../public/img/login/jbgoperator.png" alt="logo"> -->
        </div>
    </div>

    <div class="menu-container-sidebar">
        <!--         <div class="search-sidebar">
            <i class="bx bx-search"></i>
            <input type="search" placeholder="Buscar...">
        </div> -->
        <img src="../../../public/img/login/jbgoperator.png" style="width: 200px;" alt="logo">


        <ul class="menu-sidebar">
            <li class="menu-item menu-item-static active">
                <a href="../inicio/inicio.php" class="menu-link">
                    <i class="fa-solid fa-house"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <li class="menu-item menu-item-static">
                <a href="../agenda/agendas.php" class="menu-link">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Agenda</span>
                </a>
            </li>
<!--             <li class="menu-item menu-item-dropdown">
                <a href="" class="menu-link subMenuBtn">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Agenda</span>
                    <i class="bx bx-chevron-down "></i>
                </a>
                <ul class="sub-menu">
                    <li><a href="#" class="sub-menu-link">Registrar</a></li>
                    <li><a href="../agenda/agendas.php" class="sub-menu-link">Agenda</a></li>
                    <li><a href="#" class="sub-menu-link">Buscar</a></li>
                </ul>
            </li> -->
            <li class="menu-item menu-item-static">
                <a href="../usuarios/usuarios.php" class="menu-link">
                    <i class="fa-solid fa-user"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="menu-item menu-item-dropdown">
                <a href="" class="menu-link subMenuBtn">
                    <i class="fa-solid fa-file"></i>
                    <span>Reportes</span>
                    <i class="bx bx-chevron-down "></i>
                </a>
                <ul class="sub-menu">
                    <li><a href="#" class="sub-menu-link">Eficiencia</a></li>
                    <li><a href="#" class="sub-menu-link">Top Diario</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- <div class="footer-sidebar"> -->
    <!-- <ul class="menu-sidebar">
            <li class="menu-item menu-item-static">
                <a href="#" class="menu-link">
                    <i class="bx bx-bell"></i>
                    <span>Notificaciones</span>
                </a>
            </li>
            <li class="menu-item menu-item-static">
                <a href="#" class="menu-link">
                    <i class="bx bx-cog"></i>
                    <span>Configuraciones</span>
                </a>
            </li>

        </ul> -->
    <!-- <div class="user-sidebar"> -->
    <!-- <div class="user-img">
                <img src="../../../public/img/login/jbgoperator.png" alt="user">
            </div> -->
    <!-- <div class="user-info">
            <span class="user-name"> -->
    <?php
    /* function obtenerIniciales($nombreusuario) {
                        $palabras = explode(' ', trim($nombreusuario));
                        $primerNombre = $palabras[0];
                        $nombresMedios = array_slice($palabras, 1, -2);
                        $ultimosApellidos = array_slice($palabras, -2);
                        
                        $nuevoNombre = $primerNombre . ' ' . implode(' ', $nombresMedios) . ' ';
                        foreach ($ultimosApellidos as $apellido) {
                            $nuevoNombre .= strtoupper($apellido[0]);
                        }
                        return trim($nuevoNombre);
                    }
                    
                    echo obtenerIniciales($nombreusuario); */
    ?>
    <!-- </span>
                <span class="user-email"></span>
            </div> -->
    <!-- <div class="user-icon" title="Salir">
                <li class="h7" style="list-style: none;">
                    <a class="nav-link" href="../../../config/cerrarsesion/cerrarsesion.php?idusuario=">
                        <i class="fa-solid fa-power-off"></i>
                    </a>
                </li>
            </div>
        </div>
    </div> -->
</aside>