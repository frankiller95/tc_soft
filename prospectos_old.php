<?

if (profile(12, $id_usuario) == 1) {

    $filtro = '';

    if(isset($_GET['filtro'])){
        $filtro = $_GET['filtro'];
    }

    if($filtro == 'g'){
        $titulo = "Prospectos Gane";
    }else if($filtro == '' || $filtro == 'e'){
        $titulo = "Prospectos Agentes Externo";
    }else if($filtro == "t"){
        $titulo = "Prospectos Tropa";
    }

?>

    <div class="response" style="
    width: 100%;
    height: 100%;
    top: 0;
    position: fixed;
    z-index: 99999;
    background: #fff;
    display: none;
    ">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">POR FAVOR ESPERE...</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h4 class="text-themecolor"><?= ucwords($page) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item active"><?= ucwords($page) ?></li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row" id="prospectos-zone">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="addProspecto()">Nuevo Prospecto</button>
                            <a href="?page=prospectos&filtro=t" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;">Tropa</a>
                            <a href="?page=prospectos&filtro=g" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;">Gane</a>
                            <a href="?page=prospectos&filtro=e" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;">Externos</a>
                            <h4 class="card-title"><?=$titulo?></h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableProspectos" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PROSPECTO</th>
                                            <th>CONTACTO</th>
                                            <th>ASESOR</th>
                                            <th>VALIDADOR</th>
                                            <th>PLATAFORMA</th>
                                            <th>UBICACIÓN</th>
                                            <th>ESTADO PROSPECTO</th>
                                            <th>CALIFICACIÓN DATACREDITO</th>
                                            <th>FECHA REGISTRO</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $condition = "";

                                        $condition2 = '';

                                        if (profile(14, $id_usuario) != 1) {

                                            $condition = " AND prospectos.id_responsable_interno = $id_usuario";
                                        }

                                        if($filtro == 'g'){
                                            $condition2 = " AND usuarios.cliente_gane = 1";
                                        }else if($filtro == '' || $filtro == 'e'){
                                            $condition2 = " AND usuarios.cliente_gane = 0 AND usuarios.usuario_tropa = 0";
                                        }else if($filtro == "t"){
                                            $condition2 = " AND usuarios.usuario_tropa = 1";
                                        }

                                        $query1 = "SELECT prospectos.id AS id_prospecto, prospecto_detalles.id AS id_detalles, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno, prospectos.id_plataforma, plataformas_credito.nombre_plataforma, estados_prospectos.estado_prospecto, prospectos.id_estado_prospecto, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y') AS fecha_registro_prospecto, prospectos.id_usuario_validador, prospectos.resultado_dc_prospecto FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id WHERE prospectos.del = 0 $condition $condition2 ORDER BY prospectos.fecha_registro DESC";
                                        
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_prospecto = $row1['id_prospecto'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $prospecto_nombre = $row1['prospecto_nombre'];
                                            $prospecto_apellidos = $row1['prospecto_apellidos'];
                                            $prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
                                            $prospecto_numero_contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
                                            $contacto_w = $row1['contacto_w'];
                                            $ciudad_prospecto = $row1['ciudad'];
                                            $departamento_prospecto = $row1['departamento'];
                                            $marca_producto = $row1['marca_producto'];

                                            $id_usuario_responsable = $row1['id_usuario_responsable'];
                                            $id_responsable_interno = $row1['id_responsable_interno'];

                                            $nombre_plataforma = $row1['nombre_plataforma'];

                                            if($nombre_plataforma == ''){
                                                $nombre_plataforma = "N/A";
                                            }

                                            $estado_prospecto = $row1['estado_prospecto'];

                                            $id_estado_prospecto = $row1['id_estado_prospecto'];

                                            $id_detalles = $row1['id_detalles'];

                                            $fecha_registro_prospecto = $row1['fecha_registro_prospecto'];

                                            $id_usuario_validador = $row1['id_usuario_validador'];

                                            $resultado_dc_prospecto = $row1['resultado_dc_prospecto'];

                                            if($id_estado_prospecto == 0){
                                                $estado_prospecto = "pdte. por validar";
                                            }

                                            $validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");

                                            $clase_color = '';

                                            $validate_solicitud = '';

                                            if ($validate_gane == 1) {
                                                $id_punto_gane = execute_scalar("SELECT id_punto_gane FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario_responsable");
                                                $creado_en = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE id = $id_punto_gane");
                                            } else {
                                                $creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
                                            }

                                            if ($id_responsable_interno == 0) {
                                                $responsable = '<span class="label label-success" style="font-size: 16px;">EN COLA</span>';
                                            } else {
                                                $responsable = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) FROM usuarios WHERE id = $id_responsable_interno");

                                                $validate_solicitud = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE solicitudes.id_prospecto = $id_prospecto AND solicitudes.del = 0");

                                                if ($validate_solicitud == 0) {
                                                    $clase_color = "solicitud-aprobada";
                                                } else {
                                                    $clase_color = "solicitud-informacion";
                                                }

                                            }

                                            if($id_usuario_validador != 0){
                                                $validador_nombre = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS name_validador FROM usuarios WHERE usuarios.id = $id_usuario_validador");
                                            }else{
                                                $validador_nombre = "N/A";
                                            }

                                            $creditos_entregados = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE solicitudes.id_estado_solicitud = 7 AND solicitudes.id_prospecto = $id_prospecto AND solicitudes.del = 0");

                                            $id_plataforma = $row1['id_plataforma'];
                                            /*
                                            if($id_plataforma == 3){

                                                $id_solicitud_noa = execute_scalar("SELECT id FROM solicitudes WHERE id_prospecto = $id_prospecto AND del = 0");
                                                $id_estado_prospecto = execute_scalar("SELECT id_estado_solicitud FROM solicitudes WHERE id = $id_solicitud_noa");
                                                $estado_prospecto = execute_scalar("SELECT mostrar FROM estados_solicitudes WHERE id = $id_estado_prospecto");

                                            }
                                            */

                                            $resultado_dc_texto = "N/A";
                                            $resultado_dc_label = "info";

                                            if($resultado_dc_prospecto == 1){
                                                $resultado_dc_texto = "APROBADO";
                                                $resultado_dc_label = "success";
                                            }else if($resultado_dc_prospecto == 2){
                                                $resultado_dc_texto = "RECHAZADO";
                                                $resultado_dc_label = "danger";
                                            }

                                            if($id_detalles != ''){

                                        ?>
                                            <tr class="row-<?= $id_prospecto ?> <?= $clase_color ?>">
                                                <td><?= $id_prospecto ?></td>
                                                <td><?= $prospecto_cedula.'-'.$prospecto_nombre . ' ' . $prospecto_apellidos ?></td>
                                                <td><?= $prospecto_numero_contacto ?></td>
                                                <td><?= $responsable ?></td>
                                                <td><?= $validador_nombre?></td>
                                                <td><?= $nombre_plataforma ?></td>
                                                <td><?= $ciudad_prospecto.'-'.$departamento_prospecto?></td>
                                                <td><span class="label label-info"><?=$estado_prospecto?></span></td>
                                                <td><span class="label label-<?=$resultado_dc_label?>"><?=$resultado_dc_texto?></span></td>
                                                <td><?= $fecha_registro_prospecto?></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <? if ($id_responsable_interno != 0) { ?>
                                                        <a href="?page=prospecto&id=<?= $id_prospecto ?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto"><i class="mdi mdi-pencil"></i></a>
                                                    <? }
                                                    if (profile(20, $id_usuario)) {
                                                    ?>
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Prospecto" onClick="eliminarProspecto(<?= $id_prospecto ?>)"><i class="fas fa-trash-alt"></i></a>
                                                    <? } ?>
                                                </td>
                                            </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="imagenes-zone" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Subir Imagenes</h4>
                            <div id="zone-upload">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" id="boton_cargar" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; font-size: 25px;" onclick="uploadImagen()">Subir Frontal</button>
                                        <p id="estado" style="font-size: 20px; font-weight: 700;">Sube la imagen de la parta&nbsp;<span class="text-danger">FRONTAL</span>&nbsp;de la cedula del cliente.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="img-preview">

                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario ?>">
                                        <input type="hidden" name="id_confirmacion" id="id_confirmacion" value="0">
                                        <input type="hidden" name="tipo_img" id="tipo_img" value="0">
                                        <!--<input type="hidden" name="action" value="insertar_imagen">-->
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar()">Cancelar</button>
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;" onclick="continueNext()">SIGUIENTE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="prospecto-zone" style="display: none">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Información Prospecto</h4>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <form action="" method="post" class="smart-form" id="registrarNuevoProspectoForm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cedula:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="cedula_prospecto" id="cedula_prospecto" placeholder="Cedula del prospecto" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="16" onpaste="return false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nombre_prospecto" id="nombre_prospecto" placeholder="Nombre del prospecto" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="apellidos_prospecto" id="apellidos_prospecto" placeholder="Apellidos del prospecto" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input class="form-control phoneNumber" name="contacto_prospecto" id="contacto_prospecto" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" name="email_prospecto" id="email_prospecto" placeholder="Email del prospecto" autocomplete="ÑÖcompletes" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Dirección:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="direccion_prospecto" id="direccion_prospecto" placeholder="Dirección del prospecto" autocomplete="ÑÖcompletes" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ciudad de residencia:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="ciudad_prospecto" id="ciudad_prospecto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                    <option value="placeholder" selected disabled>Seleccionar Ciudad</option>
                                                    <?php
                                                    $query = "select ciudades.id as id_ciudad, ciudades.ciudad, departamentos.departamento from ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id order by ciudad";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_ciudad'] ?>"><?= $row['ciudad'] . '-' . $row['departamento'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fecha de Expedición:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="fecha_exp" id="fecha_exp" placeholder="Fecha de expedición" autocomplete="ÑÖcompletes" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ciudad de expedición:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="ciudad_exp" id="ciudad_exp" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                    <option value="placeholder" selected disabled>Seleccionar Ciudad</option>
                                                    <?php
                                                    $query = "select ciudades.id as id_ciudad, ciudades.ciudad, departamentos.departamento from ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id order by ciudad";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_ciudad'] ?>"><?= $row['ciudad'] . '-' . $row['departamento'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="dispositivo_referencia" id="dispositivo_referencia" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                    <option value="placeholder" selected disabled>Seleccionar Referencia</option>
                                                    <?php
                                                    $query = "SELECT modelos.id AS id_modelo, modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, modelos.precio_venta FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.del = 0 ORDER BY nombre_modelo ASC";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_modelo'] ?>"><?= $row['marca_producto'] . ' - ' . $row['nombre_modelo'] . ' ' . $row['capacidad'] . ' $' . number_format($row['precio_venta'], 0, '.', '.') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cuota Inicial:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="incial_prospecto" id="incial_prospecto" placeholder="Escribe la cuota incial del cliente" autocomplete="ÑÖcompletes" value="" required onkeypress="return filterFloat(event,this,id);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <fieldset>
                                                    <div class="custom-control custom-checkbox" style="margin-top: 35px; margin-left: 20px;">
                                                        <input type="checkbox" name="cliente_gane" id="cliente_gane" class="custom-control-input" value="2" onclick="viewTropaList()">
                                                        <label class="custom-control-label" for="cliente_gane">Cliente Gane</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="usuario-tropa" style="display: none">
                                        <div class="form-group">
                                            <label>Asesor:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="asesor_tropa" id="asesor_tropa" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                    <option value="placeholder" disabled>Seleccionar Asesor de tropa</option>
                                                    <?php
                                                    $query = "SELECT usuarios.id AS id_usuario, usuarios.nombre, usuarios.apellidos FROM usuarios WHERE usuarios.usuario_tropa = 1 AND usuarios.del = 0 ORDER BY usuarios.apellidos ASC";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?= $row['id_usuario'] ?>"><?= $row['apellidos'] . ' ' . $row['nombre'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <fieldset>
                                                    <div class="custom-control custom-checkbox" style="margin-top: 35px; margin-left: 20px;">
                                                        <input type="checkbox" name="cliente_interno" id="cliente_interno" class="custom-control-input" value="3" onchange="viewAsesorExterno()">
                                                        <label class="custom-control-label" for="cliente_interno">Asesor Externo</label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="usuario-externo" style="display: none">
                                        <div class="form-group">
                                            <label>Asesor:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="asesor_externo" id="asesor_externo" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                    <option value="placeholder" disabled>Seleccionar Asesor</option>
                                                    <?php
                                                    $query = "SELECT usuarios.id AS id_usuario, usuarios.nombre, usuarios.apellidos FROM usuarios WHERE usuarios.usuario_tropa = 0 AND usuarios.cliente_gane = 0 AND usuarios.del = 0 ORDER BY usuarios.apellidos ASC";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        ?>
                                                        <option value="<?= $row['id_usuario'] ?>"><?= $row['apellidos'] . ' ' . $row['nombre'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="observacion_create_prospecto">Observación:</label>
                                            <div class="input-group">
                                                <textarea class="form-control" name="observacion_create_prospecto" id="observacion_create_prospecto" rows="10" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="action" value="insertar_prospecto">
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar()">Cancelar</button>
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;">GUARDAR</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
    </div>

    <div id="add_imagen_modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Subir Imagen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="insertarImagenForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label>Imagen:&nbsp;<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cargar</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="imagen_prospecto" id="imagen_prospecto" class="form-control"  accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="cargar_imagen">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Cargar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        var zone1 = $("#prospectos-zone"),
            zone2 = $("#imagenes-zone"),
            zone3 = $("#img-preview"),
            zone4 = $("#prospecto-zone"),
            tipoImg = $("#tipo_img"),
            estado = $("#estado"),
            confirmacion = $("#id_confirmacion"),
            idUsuario = $("#id_usuario"),
            botonCargar = $("#boton_cargar");

        function addProspecto() {

            zone1.hide();
            zone2.show();
            zone3.empty();

        }

        function uploadImagen() {
            $("#imagen_prospecto").val('');
            $("#add_imagen_modal").modal("show");
        }

        $("#insertarImagenForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("insertarImagenForm"));
            formData.append('tipo_img', tipoImg.val());
            formData.append('id_confirmacion', confirmacion.val());

            cargarImgPreviewDB(formData);

        });

        function cargarImgPreviewDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos2Ajax.php', true);
            // pass Info
            xhr.onload = function() {
                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        zone3.empty();

                        zone3.html(`<img src="./documents/prospects/${respuesta.id_confirmacion}/${respuesta.id_confirmacion}-${respuesta.tipo_img}.jpg" alt="${respuesta.tipo_img_name}"></img>`);

                        confirmacion.val(respuesta.id_confirmacion);

                        $("#add_imagen_modal").modal('hide');

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    }

                } else {

                }
            }

            // send dates
            xhr.send(dates)

        }

        function continueNext() {

            var tipoImagen = tipoImg.val();
            var idConfirmacion = confirmacion.val();

            var parameters = {
                "tipo_imagen": tipoImagen,
                "id_confirmacion": idConfirmacion,
                "action": "siguente_img"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/prospectos2Ajax.php',
                type: 'post',

                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Imagen cargada correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        zone3.empty();

                        tipoImg.val(respuesta.tipo_imagen_next);

                        confirmacion.val(respuesta.id_confirmacion);

                        if (respuesta.tipo_imagen_next == 1) {

                            estado.html(`Sube la imagen de la parte&nbsp;<span class="text-danger">TRASERA</span>&nbsp;de la cedula del cliente.`);
                            botonCargar.html('Subir parte de atras');

                        } else if (respuesta.tipo_imagen_next == 2) {

                            estado.html(`Sube la imagen de la foto tipo&nbsp;<span class="text-danger">SELFIE</span>&nbsp; del cliente.`);
                            botonCargar.html('Subir Selfie');

                        } else if (respuesta.tipo_imagen_next == 3) {

                            zone4.show();
                            zone2.hide();

                            $("#cedula_prospecto").val('');
                            $("#nombre_prospecto").val('');
                            $("#apellidos_prospecto").val('');
                            $("#contacto_prospecto").val('');
                            $("#email_prospecto").val('');
                            $("#prospecto_m").prop('checked', false);
                            $("#prospecto_f").prop('checked', false);
                            $("#direccion_prospecto").val('');
                            $("#dob_prospecto").val('');
                            $("#ciudad_prospecto").val('placeholder');
                            $("#ciudad_prospecto").trigger('change');
                            $("#dispositivo_referencia").val('placeholder');
                            $("#dispositivo_referencia").trigger("change");
                            $("#incial_prospecto").val('');
                            $("#fecha_exp").val('');
                            $("#ciudad_exp").val('placeholder');
                            $("#ciudad_exp").trigger('change');
                            $("#observacion_create_prospecto").val('');
                            $("#cliente_interno").prop('checked', false);
                            $("#cliente_interno").prop('checked', false);
                            $("#asesor_externo").val('placeholder');
                            $("#asesor_externo").trigger('change');
                            $("#cliente_gane").prop('checked', false);
                            $("#asesor_tropa").val('placeholder');
                            $("#asesor_tropa").trigger('change');

                        }

                    } else if (respuesta.response == "falta_img") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Primero debes cargar la imagen',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });


                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                    }

                }

            });

        }

        $("#registrarNuevoProspectoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            var ciudadResidencia = $("#ciudad_prospecto").val(),
                ciudadExp = $("#ciudad_exp").val(),
                referenciaProspecto = $("#dispositivo_referencia").val();

            if (ciudadResidencia === null) {
                $.toast({
                    heading: 'Error!',
                    text: 'la ciudad no puede estar vacia.',
                    position: 'top-center',
                    loaderBg: '#e46a76',
                    icon: 'error',
                    hideAfter: 4500,
                    stack: 6
                });

                return 0;
            }

            console.log(ciudadExp);

            if (ciudadExp === null) {

                $.toast({
                    heading: 'Error!',
                    text: 'la ciudad de expedición no puede estar vacia.',
                    position: 'top-center',
                    loaderBg: '#e46a76',
                    icon: 'error',
                    hideAfter: 4500,
                    stack: 6
                });

                return 0;

            }

            if (referenciaProspecto === null) {

                $.toast({
                    heading: 'Error!',
                    text: 'La referencia no puede estar vacia.',
                    position: 'top-center',
                    loaderBg: '#e46a76',
                    icon: 'error',
                    hideAfter: 4500,
                    stack: 6
                });

                return 0;

            }

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarNuevoProspectoForm"));

            formData.append('id_confirmacion', confirmacion.val());
            formData.append('id_usuario', idUsuario.val());

            insertarProspectoDB(formData);

        });

        function insertarProspectoDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos2Ajax.php', true);
            // pass Info
            xhr.onload = function() {
                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        zone4.hide();
                        zone2.hide();
                        zone1.show();

                        confirmacion.val(0);
                        tipoImg.val(0);
                        estado.html(`Sube la imagen de la parta&nbsp;<span class="text-danger">FRONTAL</span>&nbsp;de la cedula del cliente.`);
                        botonCargar.html('Subir Frontal');

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Prospecto Registrado Correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        var botonEliminar = '';

                        if (respuesta.profile_20) {

                            botonEliminar = `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Prospecto" onClick="eliminarProspecto(${respuesta.id_prospecto})"><i class="fas fa-trash-alt"></i></a>`;

                        }

                        tablaProspectos.row.add([
                            respuesta.id_prospecto, respuesta.cedula_prospecto+'-'+respuesta.prospecto_nombre, respuesta.contacto_prospecto, respuesta.creador_nombre, respuesta.creador_nombre, respuesta.validador_nombre, `N/A`, `<span class="label label-info">PDTE. VALIDAR</span>`, `<span class="label label-${respuesta.resultado_dc_label}">${respuesta.resultado_dc_texto}</span>`, respuesta.fecha_registro, `<div class="jsgrid-align-center"><a href="?page=prospecto&id=${respuesta.id_prospecto}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Soltar Prospecto" onClick="soltarProspecto(${respuesta.id_prospecto}, ${respuesta.id_usuario})"><i class="fas fa-sync-alt"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Prospecto" onClick="eliminarProspecto(${respuesta.id_prospecto})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_prospecto + " solicitud-aprobada");

                    } else if (respuesta.response == "cedula_repetida") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Cedula ya registrada',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                    }else if(respuesta.response == "falta_dispositivo"){

                        $.toast({
                            heading: 'Error!',
                            text: 'La referencia no puede estar vacia.',
                            position: 'top-center',
                            loaderBg: '#e46a76',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });

                        return 0;

                    }else if(respuesta.response == "falta_ciudad"){

                        $.toast({
                            heading: 'Error!',
                            text: 'la ciudad no puede estar vacia.',
                            position: 'top-center',
                            loaderBg: '#e46a76',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });

                        return 0;

                    }else if(respuesta.response == "falta_ciudad_exp") {

                        $.toast({
                            heading: 'Error!',
                            text: 'la ciudad de expedición no puede estar vacia.',
                            position: 'top-center',
                            loaderBg: '#e46a76',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });

                        return 0;

                    }else if(respuesta.response == "selecciona_desde") {

                        $.toast({
                            heading: 'Error!',
                            text: 'Selecciona una procedencia.',
                            position: 'top-center',
                            loaderBg: '#e46a76',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });

                        return 0;

                    }else if(respuesta.response == "seleciona_asesor"){

                        $.toast({
                            heading: 'Error!',
                            text: 'Selecciona un asesor.',
                            position: 'top-center',
                            loaderBg: '#e46a76',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });

                        return 0;

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    }

                } else {
                    Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Error en el proceso',
                    showConfirmButton: false,
                    timer: 2500

                    });
                }
            }

            // send dates
            xhr.send(dates)

        }

        function crearProspectoDB(idConfirmacion) {

            var parameters = {
                "id_confirmacion": idConfirmacion,
                "id_usuario": idUsuario.val(),
                "action": "insert_prospecto"
            };

        }

        function eliminarProspecto(idProspecto) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para eliminar este prospecto!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                console.log(result.value);

                if (result.value == true) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "action": "eliminar_prospecto"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectosAjax.php',
                        type: 'post',

                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Prospecto Eliminado correctamente',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                            } else if (respuesta.response == "solicitues_activas") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Prospecto con solicitudes ACtivas.',
                                    text: 'Debes eliminar las solicitudes asociadas con este prospecto.',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 4000

                                });

                            } else {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Error en el proceso',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                            }

                        }

                    });

                } else {

                    return 0;

                }
            });

        }



        function soltarProspecto(idProspecto, idUsuario) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para enviar a la cola nuevamente!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                console.log(result.value);

                if (result.value == true) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "id_usuario": idUsuario,
                        "action": "soltar_prospecto"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectosAjax.php',
                        type: 'post',

                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            Swal.fire({

                                position: 'top-end',
                                type: 'success',
                                title: 'Prospecto Liberado Correctamente',
                                showConfirmButton: false,
                                timer: 3000

                            });

                            tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

                            botonEliminar = '';

                            if (respuesta.permiso_20 == 1) {

                                botonEliminar = `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Prospecto" onClick="eliminarProspecto(${respuesta.id_prospecto})"><i class="fas fa-trash-alt"></i></a>`;

                            }

                            tablaProspectos.row.add([
                                respuesta.id_prospecto, respuesta.prospecto_cedula, respuesta.prospecto_nombre + ' ' + respuesta.prospecto_apellidos, respuesta.prospecto_numero_contacto, respuesta.contacto_w, respuesta.ciudad + ', ' + respuesta.departamento, respuesta.creado_en, respuesta.responsable, respuesta.marca_producto, `<div class="jsgrid-align-center"><a href="javascript:void(0);" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Tomar Prospecto" onclick="tomarProspecto(${idProspecto}, ${idUsuario})"><i class="far fa-hand-point-up"></i></a>${botonEliminar}
                                    </div>`
                            ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_prospecto);

                        }

                    });

                } else {

                    return 0;

                }
            });

        }

        function tomarProspecto(idProspecto, idUsuario) {

            var parameters = {
                "id_prospecto": idProspecto,
                "id_usuario": idUsuario,
                "action": "tomar_prospecto"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/prospectosAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'FELICIDADES!',
                            text: 'prospecto asignado correctamente.',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 4000

                        });

                        botonEliminar = '';

                        if (respuesta.permiso_20 == 1) {

                            botonEliminar = `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Prospecto" onClick="eliminarProspecto(${respuesta.id_prospecto})"><i class="fas fa-trash-alt"></i></a>`;

                        }

                        var claseColor = "solicitud-aprobada";

                        tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);
                        tablaProspectos.row.add([
                            respuesta.id_prospecto, respuesta.prospecto_cedula, respuesta.prospecto_nombre + ' ' + respuesta.prospecto_apellidos, respuesta.prospecto_numero_contacto, respuesta.contacto_w, respuesta.ciudad + ', ' + respuesta.departamento, respuesta.creado_en, respuesta.responsable, respuesta.marca_producto, `<div class="jsgrid-align-center"><a href="?page=prospecto&id=${respuesta.id_prospecto}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto"><i class="mdi mdi-pencil"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Soltar Prospecto" onClick="soltarProspecto(${respuesta.id_prospecto}, ${respuesta.id_usuario})"><i class="fas fa-sync-alt"></i></a>${botonEliminar}</div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_prospecto + ' ' + claseColor);

                        //setTimeout(function(){location.href="?page=prospecto&id="+respuesta.id_prospecto} , 4500);

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }

                }
            });

        }



        function cancelar() {

            zone4.hide();
            zone2.hide();
            zone1.show();

            confirmacion.val(0);
            tipoImg.val(0);
            estado.html(`Sube la imagen de la parta&nbsp;<span class="text-danger">FRONTAL</span>&nbsp;de la cedula del cliente.`);

        }


        function viewTropaList(){

            var check, zone1, list, check2, list2, zone2;

            zone1 = $("#usuario-tropa");

            zone2 = $("#usuario-externo");

            list = $("#asesor_tropa");

            check = document.getElementById("cliente_gane");

            check2 = document.getElementById("cliente_interno");

            list2 = $("#asesor_externo");

            if (check.checked == true) // Si la checkbox de mostrar contraseña está activada
            {
                list.val('placeholder');
                list.trigger('change');
                $("#cliente_interno").prop('checked', false);
                list2.val('placeholder');
                list2.trigger('change');
                zone1.show();
                zone2.hide();

            } else // Si no está activada
            {
                list.val('placeholder');
                list.trigger('change');
                zone1.hide();
            }

        }


        function viewAsesorExterno(){

            var check, zone1, list, check2, list2, zone2;

            zone1 = $("#usuario-externo");

            zone2 = $("#usuario-tropa");

            list = $("#asesor_externo");

            list2 = $("#asesor_tropa");

            check = document.getElementById("cliente_interno");

            check2 = document.getElementById("cliente_gane");

            if (check.checked == true) // Si la checkbox de mostrar contraseña está activada
            {
                list.val('placeholder');
                list.trigger('change');
                $("#cliente_gane").prop('checked', false);
                list2.val('placeholder');
                list2.trigger('change');
                zone1.show();
                zone2.hide();

            } else // Si no está activada
            {
                list.val('placeholder');
                list.trigger('change');
                zone1.hide();
            }


        }

    </script>

<?
} else {

    include '401error.php';
}

?>