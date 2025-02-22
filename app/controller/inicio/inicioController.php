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

if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarCardEstadosAgendas') {
    $inicioController = new inicioController();
    $inicioController->listarCardEstadosAgendas();
}

if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarCardAgendadosRedes') {
    $inicioController = new inicioController();
    $inicioController->listarCardAgendadosRedes();
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
}
?>