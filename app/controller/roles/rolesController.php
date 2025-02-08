<?php
    class RolesController {
        private $model;

        public function __construct() {
            require_once '../../model/roles/rolesModel.php';
            $this->model = new rolesModel();
        }

        public function listarRoles() {
                $roles = $this->model->roles();
                return $roles ? $roles : false;
        }
    }
?>
