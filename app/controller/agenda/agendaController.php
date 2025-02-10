<?php
//solicitud para mostrar usuarios
if (isset($_GET['accion']) && $_GET['accion'] === 'AgendasAjax') {
    $agendaController = new agendaController();
    $agendaController->mostrarAgendasAjax();
} else if (isset($_GET['accion']) && $_GET['accion'] === 'buscarAgendasAjax') {
    $agendaController = new agendaController();
    $agendaController->buscarAgendasAjax();
}
?>

<?php
class agendaController
{
    private $model;

    public function __construct()
    {
        require_once '../../model/agenda/agendaModel.php';
        $this->model = new usuariosModel();
    }

    public function mostrarAgendasAjax()
    {
        try {
            // Recibir los parámetros del GET
            $idrol = isset($_GET['idrolSesion']) ? $_GET['idrolSesion'] : null;
            $nombreSede = isset($_GET['filtroSedes']) ? $_GET['filtroSedes'] : null;
            $estado = isset($_GET['filtroEstado']) ? $_GET['filtroEstado'] : null;
            $nombreReclutador = isset($_GET['filtroRecultador']) ? $_GET['filtroRecultador'] : null;
            $nombreUsuario = isset($_GET['filtroNombreUsuarioSesion']) ? $_GET['filtroNombreUsuarioSesion'] : null;
            $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
            $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

            // Verificar si el idrol fue proporcionado
            if ($idrol === null) {
                echo json_encode(['error' => 'El parámetro idrol es obligatorio.']);
                return;
            }

            // Validar los parámetros según el rol
            if ($idrol == 2 || $idrol == 4) {

                $nombreUsuario = null;

                // Si el rol es 2 o 4, nombreUsuario debe ser null
                if ($nombreUsuario !== null) {
                    echo json_encode(['error' => 'El nombre de usuario debe ser null para el rol 2 o 4.']);
                    return;
                }
            }

            if ($idrol == 1 || $idrol == 3) {

                $nombreSede = null;
                $estado = null;
                $nombreReclutador = null;

                // Si el rol es 1 o 3, nombreSede, estado, y nombreReclutador deben ser null
                if ($nombreSede !== null || $estado !== null || $nombreReclutador !== null) {
                    echo json_encode(['error' => 'nombreSede, estado y nombreReclutador deben ser null para el rol 1 o 3.']);
                    return;
                }
            }

            // Obtener los resultados llamando al modelo
            $result = $this->model->agendas($idrol, $nombreSede, $estado, $nombreReclutador, $nombreUsuario, $fechaInicio, $fechaFin, $page, $limit);

            if ($result !== false) {
                // Responder en formato JSON con los datos
                header('Content-Type: application/json');
                echo json_encode([
                    'users' => $result['users'],
                    'total' => $result['total']
                ]);
            } else {
                // Si no se encontraron resultados
                echo json_encode(['users' => [], 'total' => 0]);
            }
        } catch (Exception $e) {
            // Capturar errores y devolver mensaje en formato JSON
            echo json_encode(['error' => 'Hubo un problema al obtener las agendas: ' . $e->getMessage()]);
        }
    }


    public function buscarAgendasAjax()
    {
        try {
            if (isset($_GET['query'])) {
                // Recibir los parámetros del GET
                $query = $_GET['query'];
                $nombreempresa = isset($_GET['nombreempresa']) ? $_GET['nombreempresa'] : null;
                $nombreusuario = isset($_GET['nombreusuario']) ? $_GET['nombreusuario'] : null;
                $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
                $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
                $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $idrol = isset($_GET['idrol']) ? (int)$_GET['idrol'] : null;  // Recibir el idrol desde GET

                // Verificar si el idrol fue proporcionado
                if ($idrol === null) {
                    echo json_encode(['error' => 'El parámetro idrol es obligatorio.']);
                    return;
                }

                // Obtener los resultados llamando al modelo
                $result = $this->model->buscarAgendas($query, $idrol, $nombreempresa, $estado, $nombreusuario, $fechaInicio, $fechaFin, $page, $limit);

                if ($result !== false) {
                    // Responder en formato JSON con los datos
                    header('Content-Type: application/json');
                    echo json_encode([
                        'users' => $result['users'],
                        'total' => $result['total']
                    ]);
                } else {
                    // Si no se encontraron resultados
                    echo json_encode(['users' => [], 'total' => 0]);
                }
            } else {
                echo json_encode(['error' => 'El parámetro query es obligatorio.']);
            }
        } catch (Exception $e) {
            // Capturar errores y devolver mensaje en formato JSON
            echo json_encode(['error' => 'Hubo un problema al obtener los usuarios: ' . $e->getMessage()]);
        }
    }
}
?>