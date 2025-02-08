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
        public function sedes(){
            $stament = $this->PDO->prepare("SELECT idsede  , nombresede	, metaagenda
                                                FROM sede 
                                                WHERE estado=2 
                                                ORDER BY nombreusuario ASC");
            return ($stament->execute()) ? $stament->fetchAll(PDO::FETCH_OBJ) : false;
        }

        //LISTAR LAS SEDES SEGUN ROL (AUXILIAR = 1, MODERADOR = 2, USUARIO = 3, ADMINISTRADOR = 4)
        public function sedesSegunRol($idrol, $idsede) {
            // Inicializamos la variable $stament
            $stament = null;
        
            // Revisamos el rol y preparamos la consulta correspondiente
            if ($idrol == 4) { // ADMINISTRADOR
                $stament = $this->PDO->prepare("SELECT 
                                                    empresa.idempresa AS idempresa,
                                                    empresa.nombreempresa AS nombreempresa,
                                                    empresa.estado AS estado,
                                                    sede.idsede AS idsede, 
                                                    sede.nombresede AS nombresede, 
                                                    sede.metaagenda AS metaagenda
                                                FROM empresa
                                                INNER JOIN sede ON empresa.idsede = sede.idsede
                                                WHERE empresa.estado = 'ACTIVO'
                                                ORDER BY sede.nombresede ASC");
        
            } else if ($idrol == 1) { // AUXILIAR
                $stament = $this->PDO->prepare("SELECT 
                                                    empresa.idempresa AS idempresa,
                                                    empresa.nombreempresa AS nombreempresa,
                                                    empresa.estado AS estado,
                                                    sede.idsede AS idsede, 
                                                    sede.nombresede AS nombresede, 
                                                    sede.metaagenda AS metaagenda
                                                FROM empresa
                                                 INNER JOIN sede ON empresa.idsede = sede.idsede
                                                 WHERE sede.idsede = :idsede AND empresa.estado = 'ACTIVO'
                                                 ORDER BY sede.nombresede ASC");

                $stament->bindParam(':idsede', $idsede, PDO::PARAM_INT);
        
            } else if ($idrol == 2) { // MODERADOR
                $stament = $this->PDO->prepare("SELECT 
                                                    empresa.idempresa AS idempresa,
                                                    empresa.nombreempresa AS nombreempresa,
                                                    empresa.estado AS estado,
                                                    sede.idsede AS idsede, 
                                                    sede.nombresede AS nombresede, 
                                                    sede.metaagenda AS metaagenda
                                                FROM empresa
                                                 INNER JOIN sede ON empresa.idsede = sede.idsede
                                                 WHERE sede.idsede = :idsede AND empresa.estado = 'ACTIVO'
                                                 ORDER BY sede.nombresede ASC");
                $stament->bindParam(':idsede', $idsede, PDO::PARAM_INT);

            } else if ($idrol == 3) { // USUARIO
                $stament = $this->PDO->prepare("SELECT
                                                    empresa.idempresa AS idempresa,
                                                    empresa.nombreempresa AS nombreempresa, 
                                                    empresa.estado AS estado,
                                                    sede.idsede AS idsede, 
                                                    sede.nombresede AS nombresede, 
                                                    sede.metaagenda AS metaagenda
                                                FROM empresa
                                                 INNER JOIN sede ON empresa.idsede = sede.idsede
                                                 WHERE sede.idsede = :idsede AND empresa.estado = 'ACTIVO'
                                                 ORDER BY sede.nombresede ASC");
                $stament->bindParam(':idsede', $idsede, PDO::PARAM_INT);
        
            }
        
            // Comprobamos si $stament se definió correctamente antes de ejecutarlo
            if ($stament !== null) {
                $stament->execute();
                return ($stament->rowCount() > 0) ? $stament->fetchAll(PDO::FETCH_OBJ) : [];
            } else {
                // Si no se definió correctamente, retornamos un array vacío o manejamos el error de alguna otra manera
                return [];
            }
        }
        
    }
?>
