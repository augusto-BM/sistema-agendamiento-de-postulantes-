<?php
// Solicitud para mostrar usuarios
if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarTop5DiariosAjax') {
    $inicioController = new inicioController();
    $inicioController->listarTop5Diarios();
}
if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarTop5AsistenciaDiarioAjax') {
    $inicioController = new inicioController();
    $inicioController->listarTop5AsistenciaDiario();
}

if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarCardObjetivosAgendas') {
    $inicioController = new inicioController();
    $inicioController->listarCardObjetivosAgendas();
}

if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarCardEstadosAgendas') {
    $inicioController = new inicioController();
    $inicioController->listarCardEstadosAgendas();
}

if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarCardAgendadosRedes') {
    $inicioController = new inicioController();
    $inicioController->listarCardAgendadosRedes();
}

if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarInicioAgendaAjax') {
    $inicioController = new inicioController();
    $inicioController->mostrarAgendaAjax();
}
?>


<?php
class inicioController
{
    private $model;

    public function __construct()
    {
        require_once '../../model/inicio/inicioModel.php';
        $this->model = new inicioModel();
    }

    public function listarTop5Diarios()
    {

        try {
            $next = isset($_GET['fechaAsignadaNext']) ? $_GET['fechaAsignadaNext'] : null;
            $nombresede = isset($_GET['sedeSeleccionada']) ? $_GET['sedeSeleccionada'] : null;

            /* $parametrosRecibidos = [
                'fechaAsignadaNext' => $next,
                'sedeSeleccionada' => $nombresede
            ]; */

            if ($next === null) {
                echo json_encode(['error' => 'El parámetro "fechaAsignadaNext" es obligatorio.']);
                return;
            }
            if ($nombresede === null) {
                echo json_encode(['error' => 'El parámetro "fechaAsignadaNext" es obligatorio.']);
                return;
            }

            $result = $this->model->top5Diario($next, $nombresede);

            if ($result !== false && !empty($result['usersTop5Diario'])) {
                header('Content-Type: application/json');
                echo json_encode([
                    /* 'params_recibidos' => $parametrosRecibidos, */
                    'usersTop5Diario' => $result['usersTop5Diario']
                ]);
            } else {
                echo json_encode([
                    'usersTop5Diario' => [],
                    'mensaje' => 'No se encontraron resultados.'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Hubo un problema al obtener los usuarios: ' . $e->getMessage()]);
        }
    }

    public function listarTop5AsistenciaDiario()
    {
        try {
            $now = isset($_GET['fechaHoy']) ? $_GET['fechaHoy'] : null;
            $nombresede = isset($_GET['sedeSeleccionada']) ? $_GET['sedeSeleccionada'] : null;

            /* $parametrosRecibidos = [
                'fechaHoy' => $now,
                'sedeSeleccionada' => $nombresede
            ]; */

            if ($now === null) {
                echo json_encode(['error' => 'El parámetro "now" es obligatorio.']);
                return;
            }
            if ($nombresede === null) {
                echo json_encode(['error' => 'El parámetro "nombresede" es obligatorio.']);
                return;
            }

            $result = $this->model->topAsistenciaDiario($now, $nombresede);

            if ($result !== false && !empty($result['usersTop5AsistenciaDiario'])) {
                // Si hay resultados, retornamos los datos
                header('Content-Type: application/json');
                echo json_encode([
                    /* 'params_recibidos' => $parametrosRecibidos, */
                    'usersTop5AsistenciaDiario' => $result['usersTop5AsistenciaDiario']
                ]);
            } else {
                // Si no hay resultados, devolvemos un array vacío o un mensaje personalizado
                header('Content-Type: application/json');
                echo json_encode([
                    'usersTop5AsistenciaDiario' => [],
                    'mensaje' => 'No se encontraron resultados.'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error de base de datos: ' . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Hubo un problema al obtener los usuarios: ' . $e->getMessage()]);
        }
    }

    public function listarCardObjetivosAgendas()
    {
        // Recoger parámetros (puedes utilizar GET, POST u otro método según tu necesidad)
        $next       = isset($_GET['fechaAsignadaNext']) ? $_GET['fechaAsignadaNext'] : null;
        $nombresede = isset($_GET['sedeSeleccionada']) ? $_GET['sedeSeleccionada'] : null;
        $turno      = isset($_GET['turnoSeleccionado']) ? $_GET['turnoSeleccionado'] : null;
        $idrol      = isset($_GET['idRolSesion']) ? $_GET['idRolSesion'] : null;
        $idusuario  = isset($_GET['idUsuarioSesion']) ? $_GET['idUsuarioSesion'] : null;

        // Llamar al método del modelo
        $data = $this->model->cardObjetivosAgendas($next, $nombresede, $turno, $idrol, $idusuario);

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function listarCardEstadosAgendas()
    {
        // Recoger parámetros (puedes utilizar GET, POST u otro método según tu necesidad)
        $next       = isset($_GET['fechaAsignadaNext']) ? $_GET['fechaAsignadaNext'] : null;
        $now        = isset($_GET['fechaHoy']) ? $_GET['fechaHoy'] : null;
        $nombresede = isset($_GET['sedeSeleccionada']) ? $_GET['sedeSeleccionada'] : null;
        $turno      = isset($_GET['turnoSeleccionado']) ? $_GET['turnoSeleccionado'] : null;
        $idrol      = isset($_GET['idRolSesion']) ? $_GET['idRolSesion'] : null;
        $idusuario  = isset($_GET['idUsuarioSesion']) ? $_GET['idUsuarioSesion'] : null;

        // Llamar al método del modelo
        $data = $this->model->cardEstadosAgendas($next, $now, $nombresede, $turno, $idrol, $idusuario);

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function listarCardAgendadosRedes()
    {
        // Recoger parámetros (puedes utilizar GET, POST u otro método según tu necesidad)
        $next       = isset($_GET['fechaAsignadaNext']) ? $_GET['fechaAsignadaNext'] : null;
        $nombresede = isset($_GET['sedeSeleccionada']) ? $_GET['sedeSeleccionada'] : null;
        $turno      = isset($_GET['turnoSeleccionado']) ? $_GET['turnoSeleccionado'] : null;
        $idrol      = isset($_GET['idRolSesion']) ? $_GET['idRolSesion'] : null;
        $idusuario  = isset($_GET['idUsuarioSesion']) ? $_GET['idUsuarioSesion'] : null;

        // Llamar al método del modelo
        $data = $this->model->cardAgendadosRedes($next, $nombresede, $turno, $idrol, $idusuario);

        // Enviar la respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function mostrarAgendaAjax()
    {
        try {
            // Recibir los parámetros del GET
            $idrol = isset($_GET['idrolSesion']) ? $_GET['idrolSesion'] : null;
            $nombreSede = isset($_GET['sedeSeleccionadaCard']) ? $_GET['sedeSeleccionadaCard'] : null;
            $turno = isset($_GET['turnoSeleccionadoCard']) ? $_GET['turnoSeleccionadoCard'] : null;
            $next = isset($_GET['fechaAsignadaNextCard']) ? $_GET['fechaAsignadaNextCard'] : null;
            $query = isset($_GET['filtroInput']) ? $_GET['filtroInput'] : null;
            $idUsuario = isset($_GET['filtroIdUsuarioSesion']) ? $_GET['filtroIdUsuarioSesion'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

            //PARA DEPURAR EN CONSOLA QUE RECIBE EL CONTROLADOR HACIA EL MODELO
            /* $parametrosRecibidos = [
                'idrolSesion' => $idrol,
                'sedeSeleccionadaCard' => $nombreSede,
                'turnoSeleccionadoCard' => $turno,
                'fechaAsignadaNextCard' => $next,
                'filtroInput' => $query,
                'filtroIdUsuarioSesion' => $idUsuario,
                'page' => $page,
                'limit' => $limit
            ]; */

            // Verificar si el idrol fue proporcionado
            if ($idrol === null) {
                echo json_encode(['error' => 'El parámetro idrol es obligatorio.']);
                return;
            }

            // Para roles 1(AUXILIAR), 2(MODERADOR) y 4(ADMINISTRADOR)
            if ($idrol == 1 || $idrol == 2 || $idrol == 4) {
                $idUsuario = null;
            }

            if ($query == "") $query = null;

            // Obtener los resultados llamando al modelo
            $result = $this->model->agenda($idrol, $nombreSede, $turno, $next, $query, $idUsuario, $page, $limit);

            if ($result !== false) {
                // Responder en formato JSON con los datos
                header('Content-Type: application/json');
                echo json_encode([
                    //'params_recibidos' => $parametrosRecibidos,
                    'agenda' => $result['agenda'],
                    'total' => $result['total']
                ]);
            } else {
                // Si no se encontraron resultados
                echo json_encode(['agenda' => [], 'total' => 0/* , 'params_recibidos' => $parametrosRecibidos, */]);
            }
        } catch (Exception $e) {
            // Capturar errores y devolver mensaje en formato JSON
            echo json_encode(['error' => 'Hubo un problema al obtener las agendas: ' . $e->getMessage()]);
        }
    }
}
?>