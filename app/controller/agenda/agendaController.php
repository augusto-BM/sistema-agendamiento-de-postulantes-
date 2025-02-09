<?php
//solicitud para mostrar usuarios
if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarUsuariosAjax') {
    $usuariosController = new usuariosController();
    $usuariosController->mostrarUsuariosAjax();
} else if (isset($_GET['accion']) && $_GET['accion'] === 'buscarUsuariosAjax') {
    $usuariosController = new usuariosController();
    $usuariosController->buscarUsuariosAjax();
} else if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarUsuariosInactivosAjax') {
    $usuariosController = new usuariosController();
    $usuariosController->mostrarUsuariosInactivosAjax();
} else if (isset($_GET['accion']) && $_GET['accion'] === 'buscarUsuariosInactivosAjax') {
    $usuariosController = new usuariosController();
    $usuariosController->buscarUsuariosInactivosAjax();
}
?>

<?php
class usuariosController
{
    private $model;

    public function __construct()
    {
        require_once '../../model/agenda/agendaModel.php';
        $this->model = new usuariosModel();
    }

    public function buscarUsuariosAjax()
    {
        try {
            if (isset($_GET['query'])) {
                $query = $_GET['query'];
                $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : null;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

                // Obtener los usuarios y el total de resultados
                $result = $this->model->buscarUsuarios($query, $idempresa, $page, $limit);

                if ($result !== false) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'users' => $result['users'],
                        'total' => $result['total']
                    ]);
                } else {
                    echo json_encode(['users' => [], 'total' => 0]);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Hubo un problema al obtener los usuarios: ' . $e->getMessage()]);
        }
    }
}
?>