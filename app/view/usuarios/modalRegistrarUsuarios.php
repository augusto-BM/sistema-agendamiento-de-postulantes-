
<!-- Modal -->
<div class="modal fade" id="registrar_usuarios" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registrar_articulosLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable registrar_usuarios ">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-start">
        <h4 class="fw-bold titulo-principal text-center">
            <span class="fw-light">Inventario/</span> Registar Articulo
        </h4>
        <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <!-- PRIMERA PAGINA -->
        <div class="container contenedor_registrarArticulo modal-page-registrarArticulo page-1">
            
            <form class="formulario_registrarArticulo" action="../../controlador/dashboard/principal/registrarArticulos_modalControlador.php" method="post">
                <div class="card mb-4">
                    <h6 class="mt-3 fw-bold titulo-principal text-start ml-4" style="color: #566a7f;" >DETALLE ARTICULO <span class="campo_obligatorio campo_obligatorio">(*) Campo obligatorio</span></h6>

                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="nombre_articulo" class="form-label">Nombre <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <input type="text" id="nombre_articulo" name="nombre_articulo" class="form-control" placeholder="Nombre articulo es obligatorio" required value="<?php echo isset($_SESSION['nombre_articulo']) ? htmlspecialchars($_SESSION['nombre_articulo'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                                <?php unset($_SESSION['nombre_articulo']);?>
                            </div>

                            <div class="col-md-4">
                                <label for="empresa_id" class="form-label">Empresa <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <select type="text" id="empresa_id" name="empresa_id" class="form-control" required>
                                    <option value="" disabled selected>SELECCIONE</option>
                                    <?php if (!empty($empresasModal)): ?>
                                        <?php foreach ($empresasModal as $empresa): ?>
                                            <option value="<?php echo htmlspecialchars($empresa['empresa_id']); ?>"
                                                <?php echo (isset($_SESSION['empresa_id']) && $_SESSION['empresa_id'] == $empresa['empresa_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($empresa['nombre_empresa']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <option value="" disabled selected>NO HAY EMPRESAS ACTIVAS</option> 
                                    <?php endif; ?>
                                </select>
                                <?php unset($_SESSION['empresa_id']);?>
                            </div>

                            <div class="col-md-4">
                                <label for="area_id" class="form-label">Area <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <select type="text" id="area_id" name="area_id" class="form-control" required>
                                    <option value="" disabled selected>SELECCIONE</option>
                                        <?php if (!empty($areasModal)): ?>
                                            <?php foreach ($areasModal as $area): ?>
                                            <option value="<?php echo htmlspecialchars($area['area_id']); ?>"
                                                <?php echo (isset($_SESSION['area_id']) && $_SESSION['area_id'] == $area['area_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($area['nombre_area']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <option value="" disabled selected>NO HAY AREAS ACTIVAS</option> 
                                        <?php endif; ?>
                                </select>
                                <?php unset($_SESSION['area_id']);?>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="responsable_id" class="form-label">Responsable <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <select type="text" id="responsable_id" name="responsable_id" class="form-control" required>
                                    <option value="" disabled selected>SELECCIONE</option>    
                                    <?php if (!empty($responsablesModal)): ?>
                                        <?php foreach ($responsablesModal as $responsable): ?>
                                            <option value="<?php echo htmlspecialchars($responsable['responsable_id']); ?>"
                                                <?php echo (isset($_SESSION['responsable_id']) && $_SESSION['responsable_id'] == $responsable['responsable_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($responsable['nombre_apellido']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <option value="" disabled selected>NO HAY RESPONSABLES ACTIVOS</option> 
                                    <?php endif; ?>
                                </select>
                                <?php unset($_SESSION['responsable_id']);?>
                            </div>
                            <div class="col-md-4">
                                <label for="codigo_articulo" class="form-label">Código 
                                    <span class="asterisco" title="Campo obligatorio">*</span>
                                    <i style="color: red; font-style: italic; font-size: 12px;">
                                        <?php echo isset($_SESSION['error_codigo_existente']) ? $_SESSION['error_codigo_existente'] : ''; ?>
                                    </i>
                                </label>
                                <input type="text" id="codigo_articulo" name="codigo_articulo" class="form-control <?php echo isset($_SESSION['error_codigo_existente']) ? 'is-invalid' : ''; ?>" placeholder="Codigo articulo es obligatorio" required value="<?php echo isset($_SESSION['codigo_articulo']) ? htmlspecialchars($_SESSION['codigo_articulo'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                                <?php unset($_SESSION['codigo_articulo']);?>
                                <?php unset($_SESSION['error_codigo_existente']);?>
                            </div>

                            <div class="col-md-4">
                                <label for="estado_articulo_id" class="form-label">Estado <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <select type="text" id="estado_articulo_id" name="estado_articulo_id" class="form-control" required>
                                    <option value="" disabled selected>SELECCIONE</option>
                                        <?php if (!empty($estadoArticulosModal)): ?>
                                            <?php foreach ($estadoArticulosModal as $estadoArticulos): ?>
                                            <option value="<?php echo htmlspecialchars($estadoArticulos['estado_articulo_id']); ?>"
                                                <?php echo (isset($_SESSION['estado_articulo_id']) && $_SESSION['estado_articulo_id'] == $estadoArticulos['estado_articulo_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($estadoArticulos['nombre_estado']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <option value="" disabled selected>NO HAY ESTADOS DE ARTICULOS REGISTRADOS</option> 
                                        <?php endif; ?>
                                </select>
                                <?php unset($_SESSION['estado_articulo_id']);?>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="categoria_id" class="form-label">Categoría <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <select type="text" id="categoria_id" name="categoria_id" class="form-control" required>
                                        <option value="" disabled selected>SELECCIONE</option>
                                        <?php if (!empty($categoriasModal)): ?>
                                            <?php foreach ($categoriasModal as $categoria): ?>
                                            <option value="<?php echo htmlspecialchars($categoria['categoria_id']); ?>"
                                                <?php echo (isset($_SESSION['categoria_id']) && $_SESSION['categoria_id'] == $categoria['categoria_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <option value="" disabled selected>NO HAY CATEGORIAS REGISTRADAS</option> 
                                        <?php endif; ?>
                                </select>
                                <?php unset($_SESSION['categoria_id']);?>
                            </div>
                            <div class="col-md-4">
                                <label for="marca_id" class="form-label">Marca <span class="asterisco" title="Campo obligatorio">*</span></label>
                                <select type="text" id="marca_id" name="marca_id" class="form-control" required>
                                    <option value="" disabled selected>SELECCIONE</option>
                                        <?php if (!empty($marcasAticuloModal)): ?>
                                            <?php foreach ($marcasAticuloModal as $marcaArticulo): ?>
                                            <option value="<?php echo htmlspecialchars($marcaArticulo['marca_id']); ?>"
                                                <?php echo (isset($_SESSION['marca_id']) && $_SESSION['marca_id'] == $marcaArticulo['marca_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($marcaArticulo['nombre_marca']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <option value="" disabled selected>NO HAY MARCAS DE ARTICULOS REGISTRADOS</option> 
                                        <?php endif; ?>
                                </select>
                                <?php unset($_SESSION['marca_id']);?>
                            </div>

                            <div class="col-md-4">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" id="modelo" name="modelo" class="form-control" placeholder="Modelo articulo" value="<?php echo isset($_SESSION['modelo']) ? htmlspecialchars($_SESSION['modelo'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                            </div>

                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="numero_serie" class="form-label">Número de Serie
                                    <i style="color: red; font-style: italic; font-size: 12px;">
                                        <?php echo isset($_SESSION['error_serie_existente']) ? $_SESSION['error_serie_existente'] : ''; ?>
                                    </i>
                                </label>
                                <input type="text" id="numero_serie" name="numero_serie" class="form-control <?php echo isset($_SESSION['error_serie_existente']) ? 'is-invalid' : ''; ?>" placeholder="Numero de serie" value="<?php echo isset($_SESSION['numero_serie']) ? htmlspecialchars($_SESSION['numero_serie'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                                <?php unset($_SESSION['numero_serie']);?>
                                <?php unset($_SESSION['error_serie_existente']);?>

                                <label for="color" class="form-label mt-2">Color</label>
                                <input type="text" id="color" name="color" class="form-control" placeholder="Color articulo" value="<?php echo isset($_SESSION['color']) ? htmlspecialchars($_SESSION['color'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                                <?php unset($_SESSION['color']);?>
                            </div>

                            <div class="col-md-8">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea id="output2" name="descripcion" class="form-control descripcion_textarea" rows="5" autocomplete="off" placeholder="• PRIMER COMENTARIO
• SEGUNDO COMENTARIO
• TERCER COMENTARIO
•  . . . " style="resize: none;" value="<?php echo isset($_SESSION['descripcion']) ? htmlspecialchars($_SESSION['descripcion'], ENT_QUOTES, 'UTF-8') : ''; ?>"></textarea>
                                <?php unset($_SESSION['descripcion']);?>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-retroceder" data-bs-dismiss="modal">Retroceder</button>
                        <button type="submit" class="btn btn-registrar" name="submit" >Registrar</button>
                    </div>
                </div>
            </form>
        </div>

      </div>
    </div>
  </div>
</div>
