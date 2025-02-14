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
        require_once '../../model/usuarios/usuariosModel.php';
        $this->model = new usuariosModel();
    }

    public function listarReclutadores()
    {
        $reclutadores = $this->model->reclutadores();
        return $reclutadores ? $reclutadores : false;
    }
    
    public function mostrarUsuariosAjax()
    {
        try {
            $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

            $result = $this->model->usuarios($idempresa, $page, $limit);

            if ($result !== false) {
                header('Content-Type: application/json');
                echo json_encode([
                    'users' => $result['users'],
                    'total' => $result['total']
                ]);
            } else {
                echo json_encode(['users' => [], 'total' => 0]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Hubo un problema al obtener los usuarios: ' . $e->getMessage()]);
        }
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

    public function actualizarEstadoUsuario($idusuario, $estado)
    {
        $id = $this->model->cambiarEstadoUsuario($idusuario, $estado);
        if ($id != false) {
            echo json_encode(["success" => true, "message" => "Se actualizo el estado del usuario"]);
        } else {
            echo json_encode(["success" => false, "message" => "Hubo un error al modificar el estado del usuario"]);
        }
        exit();
    }

    public function guardarUsuarios($nombreusuario, $tipodocumento, $dni, $correo, $pass, $celular, $sede, $idempresa, $turno, $estado, $idrol, $fechaingreso)
    {
        $id = $this->model->insertarUsuarios($nombreusuario, $tipodocumento, $dni, $correo, $pass, $celular, $sede, $idempresa, $turno, $estado, $idrol, $fechaingreso);

        if ($id != false) {
            echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Hubo un error al registrar el usuario"]);
        }
        exit();
    }

    public function mostrarUsuario($idusuario)
    {
        return ($this->model->verUsuario($idusuario)) ? $this->model->verUsuario($idusuario) : header("Location:usuarios.php");
    }

    public function actualizarUsuario($idusuario, $nombreusuario, $tipodocumento, $dni, $correo, $pass, $celular, $idempresa, $turno, $idrol, $fechaingreso)
    {
        $id = $this->model->editarUsuario($idusuario, $nombreusuario, $tipodocumento, $dni, $correo, $pass, $celular, $idempresa, $turno, $idrol, $fechaingreso);

        if ($id != false) {
            echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Hubo un error al actualizar el usuario"]);
        }
        exit();
    }

    public function mostrarUsuariosInactivosAjax()
    {
        try {
            $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

            $result = $this->model->usuariosInactivos($idempresa, $page, $limit);

            if ($result !== false) {
                header('Content-Type: application/json');
                echo json_encode([
                    'users' => $result['users'],
                    'total' => $result['total']
                ]);
            } else {
                echo json_encode(['users' => [], 'total' => 0]);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'Hubo un problema al obtener los usuarios: ' . $e->getMessage()]);
        }
    }

    public function buscarUsuariosInactivosAjax()
    {
        try {
            if (isset($_GET['query'])) {
                $query = $_GET['query'];
                $idempresa = isset($_GET['idempresa']) ? $_GET['idempresa'] : null;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

                // Obtener los usuarios y el total de resultados
                $result = $this->model->buscarUsuariosInactivos($query, $idempresa, $page, $limit);

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

    /* public function eliminar($id){
            return ($this->model->delete($id)) ? header("Location:index.php") :  header("Location:show.php?id=" . $id);
        } */
}
?>