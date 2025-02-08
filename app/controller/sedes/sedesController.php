<?php
    class SedesController {
        private $model;

        public function __construct() {
            require_once '../../model/sedes/sedesModel.php';
            $this->model = new sedesModel();
        }

        public function listarSedes($idusuario, $idrol, $idempresa) {

            if (isset($idusuario) && isset($idrol) && isset($idempresa)) {
        
                $sedes = $this->model->sedesSegunRol($idrol, $idempresa);
                return $sedes ? $sedes : false;
        
            } else {
                return 'No hay sedes'; 
            }
        }
    }
?>
