<?php
class rolesModel
{
    private $PDO;

    public function __construct()
    {
        require_once '../../../config/conexion/conexion.php';
        $con = Database::getInstance();
        $this->PDO = $con->getConnection();
    }

    //LISTAR TODAS LAS SEDES
    public function roles()
    {
        $stament = $this->PDO->prepare("SELECT idrol, nombrerol
                                        FROM rol 
                                        ORDER BY nombrerol ASC");
        return ($stament->execute()) ? $stament->fetchAll(PDO::FETCH_OBJ) : false;
    }
}
