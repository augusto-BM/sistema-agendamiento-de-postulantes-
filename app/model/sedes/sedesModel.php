<?php
class sedesModel
{
    private $PDO;

    public function __construct()
    {
        require_once '../../../config/conexion/conexion.php';
        $con = Database::getInstance();
        $this->PDO = $con->getConnection();
    }

    //LISTAR TODAS LAS SEDES
    /*public function sedes(){
            $stament = $this->PDO->prepare("SELECT idsede  , nombresede	, metaagenda
                                                FROM sede 
                                                WHERE estado=2 
                                                ORDER BY nombreusuario ASC");
            return ($stament->execute()) ? $stament->fetchAll(PDO::FETCH_OBJ) : false;
        }*/

    //LISTAR LAS SEDES SEGUN ROL (AUXILIAR = 1, MODERADOR = 2, USUARIO = 3, ADMINISTRADOR = 4)
    public function sedesSegunRol($idrol, $idsede)
    {
        // Inicializamos la variable $stament
        $stament = null;

        // Preparamos la base de la consulta
        $query = "SELECT 
                    empresa.idempresa AS idempresa,
                    empresa.nombreempresa AS nombreempresa,
                    empresa.estado AS estado,
                    sede.idsede AS idsede, 
                    sede.nombresede AS nombresede, 
                    sede.metaagenda AS metaagenda
                FROM empresa
                INNER JOIN sede ON empresa.idsede = sede.idsede
                WHERE empresa.estado = 'ACTIVO'";

        // Si el rol es MODERADOR o USUARIO, se agrega el filtro por sede
        if ($idrol == 2 || $idrol == 3) { // MODERADOR Y USUARIO
            $query .= " AND sede.idsede = :idsede";
        }

        // Ordenamos por nombre de sede
        $query .= " ORDER BY sede.nombresede ASC";

        // Preparamos la consulta
        $stament = $this->PDO->prepare($query);

        // Si el rol es MODERADOR o USUARIO, vinculamos el parámetro
        if ($idrol == 2 || $idrol == 3) {
            $stament->bindParam(':idsede', $idsede, PDO::PARAM_INT);
        }


        if ($stament !== null) {
            $stament->execute();
            return ($stament->rowCount() > 0) ? $stament->fetchAll(PDO::FETCH_OBJ) : [];
        } else {
            return [];
        }
    }
}
