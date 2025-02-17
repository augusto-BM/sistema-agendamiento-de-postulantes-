<?php
//solicitud para mostrar usuarios
if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarUsuariosAjax') {
    $usuariosController = new usuariosController();
    $usuariosController->mostrarUsuariosAjax();
}else if (isset($_GET['accion']) && $_GET['accion'] === 'mostrarUsuariosAjaxDesactivos') {
    $usuariosController = new usuariosController();
    $usuariosController->mostrarUsuariosDesactivosAjax();
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

    public function mostrarUsuariosAjax()
    {
        try {
            $idempresa = isset($_GET['sedeSeleccionada']) ? $_GET['sedeSeleccionada'] : null;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $query = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : null;

            //PARA DEPURAR EN CONSOLA QUE RECIBE EL CONTROLADOR HACIA EL MODELO
            /* $parametrosRecibidos = [
                'sedeSeleccionada' => $idempresa,
                'searchQuery' => $query,
                'page' => $page,
                'limit' => $limit
            ]; */

            if ($idempresa === null) {
                echo json_encode(['error' => 'El parámetro idempresa es obligatorio.']);
                return;
            }

            if ($query == "") $query = null;
    
            // Llamar a la función del modelo con el query (vacío o no)
            $result = $this->model->usuarios($idempresa, $page, $limit, $query);
    
            if ($result !== false) {
                header('Content-Type: application/json');
                echo json_encode([
                    /* 'params_recibidos' => $parametrosRecibidos, */
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

    public function mostrarUsuariosDesactivosAjax()
    {
        try {
            $idempresa = isset($_GET['sedeSeleccionadaDesactivos']) ? $_GET['sedeSeleccionadaDesactivos'] : null;
            $page = isset($_GET['pageDesactivos']) ? (int)$_GET['pageDesactivos'] : 1;
            $limit = isset($_GET['limitDesactivos']) ? (int)$_GET['limitDesactivos'] : 50;
            $query = isset($_GET['searchQueryDesactivos']) ? $_GET['searchQueryDesactivos'] : null;

            //PARA DEPURAR EN CONSOLA QUE RECIBE EL CONTROLADOR HACIA EL MODELO
            /* $parametrosRecibidos = [
                'sedeSeleccionadaDesactivos' => $idempresa,
                'searchQueryDesactivos' => $query,
                'pageDesactivos' => $page,
                'limitDesactivos' => $limit
            ]; */

            if ($idempresa === null) {
                echo json_encode(['error' => 'El parámetro idempresa es obligatorio.']);
                return;
            }

            if ($query == "") $query = null;
    
            // Llamar a la función del modelo con el query (vacío o no)
            $result = $this->model->usuariosInactivos($idempresa, $page, $limit, $query);
    
            if ($result !== false) {
                header('Content-Type: application/json');
                echo json_encode([
                    /* 'params_recibidos' => $parametrosRecibidos, */
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



    /* public function eliminar($id){
            return ($this->model->delete($id)) ? header("Location:index.php") :  header("Location:show.php?id=" . $id);
        } */
}
?>