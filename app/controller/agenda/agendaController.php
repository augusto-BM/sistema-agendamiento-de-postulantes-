<?php
    //solicitud para mostrar usuarios
    if (isset($_GET['accion']) && $_GET['accion'] === 'AgendasAjax') {
        $agendaController = new agendaController();
        $agendaController->mostrarAgendasAjax();
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
                $query = isset($_GET['filtroInput']) ? $_GET['filtroInput'] : null;
                $nombreUsuario = isset($_GET['filtroNombreUsuarioSesion']) ? $_GET['filtroNombreUsuarioSesion'] : null;
                $fechaInicio = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : null;
                $fechaFin = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : null;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

                //PARA DEPURAR EN CONSOLA QUE RECIBE EL CONTROLADOR HACIA EL MODELO
                /* $parametrosRecibidos = [
                    'idrolSesion' => $idrol,
                    'filtroSedes' => $nombreSede,
                    'filtroEstado' => $estado,
                    'filtroRecultador' => $nombreReclutador,
                    'filtroInput' => $query,
                    'filtroNombreUsuarioSesion' => $nombreUsuario,
                    'fechaInicio' => $fechaInicio,
                    'fechaFin' => $fechaFin,
                    'page' => $page,
                    'limit' => $limit
                ]; */

                // Verificar si el idrol fue proporcionado
                if ($idrol === null) {
                    echo json_encode(['error' => 'El parámetro idrol es obligatorio.']);
                    return;
                }

                // Validar los parámetros según el rol // Para roles 2 y 4 (ADMINISTRADOR)
                if ($idrol == 2 || $idrol == 4) {
                    if ($nombreSede == "TODOS") $nombreSede = null;
                    if ($estado == "TODOS") $estado = null;
                    if ($nombreReclutador == "TODOS") $nombreReclutador = null;
                    if ($query == "") $query = null;
                }

                // Validar los parámetros según el rol // Para roles 1 y 3 (RECLUTADOR)
                if ($idrol == 1 || $idrol == 3) {
                    $nombreSede = null;
                    $estado = null;
                    $nombreReclutador = null;
                    if ($query == "") $query = null;

                    // Si el rol es 1 o 3, nombreSede, estado, y nombreReclutador deben ser null
                    if ($nombreSede !== null || $estado !== null || $nombreReclutador !== null) {
                        echo json_encode(['error' => 'nombreSede, estado y nombreReclutador deben ser null para el rol 1 o 3.']);
                        return;
                    }
                }

                // Obtener los resultados llamando al modelo
                $result = $this->model->agendas($idrol, $nombreSede, $estado, $nombreReclutador, $query, $nombreUsuario, $fechaInicio, $fechaFin, $page, $limit);

                if ($result !== false) {
                    // Responder en formato JSON con los datos
                    header('Content-Type: application/json');
                    echo json_encode([
                        //'params_recibidos' => $parametrosRecibidos,
                        'agendas' => $result['agendas'],
                        'total' => $result['total']
                    ]);
                } else {
                    // Si no se encontraron resultados
                    echo json_encode(['agendas' => [], 'total' => 0/* , 'params_recibidos' => $parametrosRecibidos, */]);
                }
            } catch (Exception $e) {
                // Capturar errores y devolver mensaje en formato JSON
                echo json_encode(['error' => 'Hubo un problema al obtener las agendas: ' . $e->getMessage()]);
            }
        }
    }
?>