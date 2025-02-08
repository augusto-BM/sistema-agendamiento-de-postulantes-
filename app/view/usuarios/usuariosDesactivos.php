<!-- usuarios_desactivados.php -->
<?php
session_start();
date_default_timezone_set('America/Lima');
require_once '../../../config/datossesion/datossesion.php'
?>
<div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
    <div class="formulario_registrarArticulo">
        <div class="card mb-4">
            <div class="card-body">

                <?php
                require_once '../../controller/sedes/sedesController.php';
                $obj = new SedesController();
                $sedes = $obj->listarSedes($idusuario, $idrol, $idempresa);
                ?>
                <div class="row mt-2 filtradoFecha">
                    <!-- SELECT PARA FILTRAR LAS SEDES -->
                    <div class="col-md-3">
                        <label class="form-label" for="filtroSedes">Sede</label>
                        <select class="form-control" id="filtroSedesInactivos">
                            <?php if ($sedes): ?>
                            <?php foreach ($sedes as $sede): ?>
                            <option value="<?= $sede->idsede; ?>"><?= $sede->nombresede; ?></option>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <option value="" disabled>No hay sedes disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Bootstrap Table with Header - Light -->
            <div class="row">
                <div class="col-md-7">
                    <h5 class="titulo-tabla">Lista de Colaboradores Inactivos</h5>
                </div>

                <div class="col-md-5 d-flex flex-column justify-content-center">
                    <div class="row m-2">
                        <div class="col-md-12 d-flex">
                            <button id="btnBuscarSearchDataInactivos" class="btn btn-primary"
                                style="margin-right: 3px; display: none;">Buscar</button>
                            <input class="form-control" type="search" id="buscarSearchDataInactivos"
                                placeholder="Buscar colaborador por Nombre, DNI">
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center">
                <i id="resultadoCountDesactivos">Se encontró 0 resultados <span
                        id="busquedaRealizadaUsuariosDesactivos"></span>
                </i>
            </p>

            <div class="table-responsive col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <table class="table table-borderless table-hover w-100 tabla-general" id="myTablaUsuarios">
                    <thead class="table-light">
                        <tr>
                            <th style="display: none;">id</th>
                            <th class="text-center">#</th>
                            <th>COLABORADOR</th>
                            <th>DNI</th>
                            <th style="display: none;">CORREO</th>
                            <th>CELULAR</th>
                            <th>SEDE</th>
                            <th>FECHA DE INGRESO</th>
                            <th>ESTADO</th>
                            <th style='display: none;'></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="data-bodyDesactivos">
                        <!-- Aquí van los datos de la tabla -->
                        <!-- Los datos serán llenados por AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="pagination m-2 d-flex justify-content-center" id="paginationDesactivos"></div>
        </div>
    </div>
</div>
<script src="../../../public/js/usuarios/ajaxListarUsuariosInactivos.js"></script>