<? if (isset($_GET['id'])) {


    $id_prospecto = $_GET['id'];

    $query = "SELECT prospectos.prospecto_cedula, prospectos.id_usuario_responsable, prospectos.id_responsable_interno, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, prospecto_detalles.prospecto_email, prospecto_detalles.prospecto_sexo, prospecto_detalles.prospecto_dob, prospecto_detalles.prospecto_direccion, prospecto_detalles.ciudad_id, prospecto_detalles.fecha_exp, prospecto_detalles.id_ciudad_exp, prospectos.id_responsable_interno, prospectos.id_confirmacion, prospectos.id_estado_prospecto, prospectos.id_plataforma, prospecto_detalles.id_referencia, prospecto_detalles.inicial_referencia, prospecto_detalles.observacion_prospecto, confirmar_aprobado, confirmar_rechazado, prospectos.resultado_dc_prospecto FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.id = $id_prospecto";

    $result = qry($query);

    while ($row = mysqli_fetch_array($result)) {

        $prospecto_cedula = $row['prospecto_cedula'];
        $id_usuario_responsable = $row['id_usuario_responsable'];
        $id_responsable_interno = $row['id_responsable_interno'];
        $prospecto_nombre = $row['prospecto_nombre'];
        $prospecto_apellidos = $row['prospecto_apellidos'];
        $prospecto_numero_contacto = $row['prospecto_numero_contacto'];
        $prospecto_numero_contacto = substr($prospecto_numero_contacto, 0, 3) . '-' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
        $contacto_w = $row['contacto_w'];
        $prospecto_email = $row['prospecto_email'];
        $prospecto_sexo = $row['prospecto_sexo'];
        $prospecto_dob = $row['prospecto_dob'];
        $prospecto_direccion = $row['prospecto_direccion'];
        $ciudad_id = $row['ciudad_id'];
        $fecha_exp = $row['fecha_exp'];
        $id_ciudad_exp = $row['id_ciudad_exp'];

        $id_responsable_interno = $row['id_responsable_interno'];

        $id_confirmacion = $row['id_confirmacion'];

        $id_estado_prospecto = $row['id_estado_prospecto'];

        $cliente_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");

        $id_plataforma = $row['id_plataforma'];

        $id_referencia = $row['id_referencia'];

        $inicial_referencia = $row['inicial_referencia'];

        $observacion_prospecto = $row['observacion_prospecto'];

        $confirmar_aprobado = $row['confirmar_aprobado'];

        $confirmar_rechazado = $row['confirmar_rechazado'];

        $resultado_dc_prospecto = $row['resultado_dc_prospecto'];

        $disabled = 'disabled';

        if ($id_estado_prospecto == 0 || $id_estado_prospecto == 1 || $id_estado_prospecto == 2) {
            $disabled = '';
        }
    }

    $permiso_25 = profile(25, $id_usuario);


?>
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
                    <h4 class="text-themecolor"><?= ucwords($prospecto_nombre . '' . $prospecto_apellidos) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="?page=prospectos">Prospectos</a></li>
                            <li class="breadcrumb-item active"><?= ucwords(str_replace('_', ' ', $page)) ?></li>
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
            <div class="row" id="prospecto-zone">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Información Prospecto <?= $id_estado_prospecto ?></h4>
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
                                                <input type="text" class="form-control" name="cedula_prospecto" id="cedula_prospecto" placeholder="Cedula del prospecto" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="16" <? if ($prospecto_cedula != '') { ?>value="<?= $prospecto_cedula ?>" <? } ?> onchange="actualizarProspectoDetalles(this.value, 'prospecto_cedula')">
                                            </div>h
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nombre_prospecto" id="nombre_prospecto" placeholder="Nombre del prospecto" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" <? if ($prospecto_nombre != '') { ?>value="<?= $prospecto_nombre ?>" <? } ?> onchange="actualizarProspectoDetalles(this.value, 'prospecto_nombre')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="apellidos_prospecto" id="apellidos_prospecto" placeholder="Apellidos del prospecto" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" <? if ($prospecto_apellidos != '') { ?>value="<?= $prospecto_apellidos ?>" <? } ?> onchange="actualizarProspectoDetalles(this.value, 'prospecto_apellidos')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input class="form-control phoneNumber" name="contacto_prospecto" id="contacto_prospecto" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16" <? if ($prospecto_numero_contacto != '') { ?>value="<?= $prospecto_numero_contacto ?>" <? } ?> onchange="actualizarProspectoDetalles(this.value, 'prospecto_numero_contacto')" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" name="email_prospecto" id="email_prospecto" placeholder="Email del prospecto" <? if ($prospecto_email != '') { ?>value="<?= $prospecto_email ?>" <? } ?> autocomplete="ÑÖcompletes" onchange="actualizarProspectoDetalles(this.value, 'prospecto_email')" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Dirección:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="direccion_prospecto" id="direccion_prospecto" placeholder="Dirección del prospecto" autocomplete="ÑÖcompletes" <? if ($prospecto_direccion != '') { ?>value="<?= $prospecto_direccion ?>" <? } ?> onchange="actualizarProspectoDetalles(this.value, 'prospecto_direccion')" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ciudad de residencia:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="ciudad_prospecto" id="ciudad_prospecto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" onchange="actualizarProspectoDetalles(this.value, 'ciudad_id')" required>
                                                    <option value="placeholder" selected disabled>Seleccionar Ciudad</option>
                                                    <?php
                                                    $query = "select ciudades.id as id_ciudad, ciudades.ciudad, departamentos.departamento from ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id order by ciudad";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_ciudad'] ?>" <? if ($row['id_ciudad'] == $ciudad_id) { ?>selected<? } ?>><?= $row['ciudad'] . '-' . $row['departamento'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fecha de Expedición:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="fecha_exp" id="fecha_exp" placeholder="Fecha de expedición" autocomplete="ÑÖcompletes" <? if ($fecha_exp != '') { ?>value="<?= $fecha_exp ?>" <? } ?> onchange="actualizarProspectoDetalles(this.value, 'fecha_exp')" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ciudad de expedición:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="ciudad_exp" id="ciudad_exp" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" onchange="actualizarProspectoDetalles(this.value, 'id_ciudad_exp')" required>
                                                    <option value="placeholder" selected disabled>Seleccionar Ciudad</option>
                                                    <?php
                                                    $query = "select ciudades.id as id_ciudad, ciudades.ciudad, departamentos.departamento from ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id order by ciudad";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_ciudad'] ?>" <? if ($row['id_ciudad'] == $id_ciudad_exp) { ?>selected<? } ?>><?= $row['ciudad'] . '-' . $row['departamento'] ?></option>
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
                                                <select class="form-control select2Class" name="dispositivo_referencia" id="dispositivo_referencia" style="width: 100%; height:36px;" onchange="actualizarProspectoDetalles(this.value, 'id_referencia')" autocomplete="ÑÖcompletes" required>
                                                    <option value="placeholder" selected disabled>Seleccionar Referencia</option>
                                                    <?php
                                                    $query = "SELECT modelos.id AS id_modelo, modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, modelos.precio_venta FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.del = 0 ORDER BY nombre_modelo ASC";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                    ?>
                                                        <option value="<?= $row['id_modelo'] ?>" <? if ($row['id_modelo'] == $id_referencia) { ?>selected<? } ?>><?= $row['marca_producto'] . ' - ' . $row['nombre_modelo'] . ' ' . $row['capacidad'] . ' $' . number_format($row['precio_venta'], 0, '.', '.') ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cuota Inicial:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="incial_prospecto" id="incial_prospecto" placeholder="Escribe la cuota incial del cliente" autocomplete="ÑÖcompletes" <? if ($inicial_referencia != '') { ?>value="<?= number_format($inicial_referencia, 0, '.', '.') ?>" <? } ?> required onkeypress="return filterFloat(event,this,id);" onchange="actualizarProspectoDetalles(this.value, 'inicial_referencia')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="observacion_create_prospecto">Observación:</label>
                                            <div class="input-group">
                                                <textarea class="form-control" name="observacion_create_prospecto" id="observacion_create_prospecto" rows="10" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;" onchange="actualizarProspectoDetalles(this.value, 'observacion_prospecto')"><? if ($observacion_prospecto != '') { ?><?= $observacion_prospecto ?><? } ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- imagenes prospectos -->
            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <h4 class="card-title">Imagenes Prospectos.</h4>

                            <div class="table-responsive m-t-40">

                                <table id="dataTableImagenesProspectos" class="table display table-bordered table-striped no-wrap">

                                    <thead>

                                        <tr>

                                            <th>ID</th>

                                            <th>IMAGEN TIPO</th>

                                            <th>ACCIONES</th>

                                        </tr>

                                    </thead>

                                    <tbody>
                                        <?
                                        $id_imagen_1 = execute_scalar("SELECT id FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = 'FRONTAL' AND del = 0");
                                        $imagen_extension_1 = execute_scalar("SELECT imagen_extension FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = 'FRONTAL' AND del = 0");
                                        ?>
                                        <tr class="row-<?= $id_imagen_1 ?>">
                                            <td>0</td>
                                            <td>FRONTAL</td>
                                            <td><a href="./documents/prospects/<?= $id_confirmacion ?>/<?= $id_confirmacion . '-0' ?>.<?= $imagen_extension_1 ?>" class="btn btn-outline-info waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="FRONTAL"><i class="fas fa-eye"></i></a>
                                                <a href="./documents/prospects/<?= $id_confirmacion ?>/<?= $id_confirmacion . '-0' ?>.<?= $imagen_extension_1 ?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Descargar" download="FRONTAL"><i class="fas fa-download"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Img" onclick="cambiarImagenP(<?= $id_imagen_1 ?>)"><i class="fas fa-sync-alt"></i></a>
                                            </td>
                                        </tr>
                                        <?
                                        $id_imagen_2 = execute_scalar("SELECT id FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = 'ATRAS' AND del = 0");
                                        $imagen_extension_2 = execute_scalar("SELECT imagen_extension FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = 'ATRAS' AND del = 0");
                                        ?>
                                        <tr class="row-<?= $id_imagen_2 ?>">
                                            <td>1</td>
                                            <td>ATRAS</td>
                                            <td><a href="./documents/prospects/<?= $id_confirmacion ?>/<?= $id_confirmacion . '-1' ?>.<?= $imagen_extension_2 ?>" class="btn btn-outline-info waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="FRONTAL"><i class="fas fa-eye"></i></a>
                                                <a href="./documents/prospects/<?= $id_confirmacion ?>/<?= $id_confirmacion . '-1' ?>.<?= $imagen_extension_2 ?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Descargar" download="FRONTAL"><i class="fas fa-download"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Img" onclick="cambiarImagenP(<?= $id_imagen_2 ?>)"><i class="fas fa-sync-alt"></i></a>
                                            </td>
                                        </tr>
                                        <?
                                        $id_imagen_3 = execute_scalar("SELECT id FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = 'SELFIE' AND del = 0");
                                        $imagen_extension_3 = execute_scalar("SELECT imagen_extension FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = 'SELFIE' AND del = 0");
                                        ?>
                                        <tr class="row-<?= $id_imagen_3 ?>">
                                            <td>2</td>
                                            <td>SELFIE</td>
                                            <td><a href="./documents/prospects/<?= $id_confirmacion ?>/<?= $id_confirmacion . '-2' ?>.<?= $imagen_extension_3 ?>" class="btn btn-outline-info waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="SELFIE"><i class="fas fa-eye"></i></a>
                                                <a href="./documents/prospects/<?= $id_confirmacion ?>/<?= $id_confirmacion . '-2' ?>.<?= $imagen_extension_3 ?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Descargar" download="SELFIE"><i class="fas fa-download"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Img" onclick="cambiarImagenP(<?= $id_imagen_3 ?>)"><i class="fas fa-sync-alt"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>

                            </div>

                            <br>
                            <br>
                            <!-- botones -->
                            <?
                            if ($permiso_25 == 0) {
                            ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id_usuario_responsable" id="<?= $id_usuario_responsable ?>">
                                        <input type="hidden" name="id_prospecto" id="id_prospecto" value="<?= $id_prospecto ?>">
                                        <input type="hidden" name="id_responsable_interno" id="id_responsable_interno" value="<?= $id_responsable_interno ?>">
                                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario ?>">
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar()">Salir</button>
                                        <?
                                        //si ya tiene responsable interno y si el usuario login es el responsable interno
                                        if ($id_responsable_interno != 0 && $id_responsable_interno == $id_usuario) {
                                        ?>
                                            <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: right;" onClick="soltarProspecto(<?= $id_prospecto ?>)" id="boton-soltar">Soltar Prospecto</button>
                                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;" onClick="descargarTodas(<?= $id_prospecto ?>)" id="boton-descargar">DESCARGAR TODAS</button>
                                            <button type="button" class="btn waves-effect waves-light btn-lg btn-warning" style="float: right; margin-right: 5px;" onClick="ventaNoRealizada(<?= $id_prospecto ?>)" id="boton-soltar">Venta no realizada</button>
                                        <? } ?>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="row">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;" onClick="descargarTodas(<?= $id_prospecto ?>)" id="boton-descargar">DESCARGAR TODAS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <? if ($permiso_25 == 1) { ?>

                <div class="row" id="validaciones-zone">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Validación</h4>
                                <br>
                                <form action="" method="post" class="smart-form" id="validacionForm">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Plataforma de credito:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <select class="form-control select2Class" name="plataforma_prospecto" id="plataforma_prospecto" style="width: 100%; height:36px;" onchange="actualizarProspectoDetalles(this.value, 'id_plataforma')" autocomplete="ÑÖcompletes" <?= $disabled ?> required>
                                                        <option value="placeholder" disabled <? if ($id_plataforma == 0) { ?> selected <? } ?>>Seleccionar Plataforma</option>
                                                        <?php
                                                        $query = "SELECT plataformas_credito.id AS id_plataforma, plataformas_credito.nombre_plataforma FROM plataformas_credito WHERE plataformas_credito.del = 0 ORDER BY nombre_plataforma ASC";
                                                        $result = qry($query);
                                                        while ($row = mysqli_fetch_array($result)) {
                                                        ?>
                                                            <option value="<?= $row['id_plataforma'] ?>" <? if ($row['id_plataforma'] == $id_plataforma) { ?>selected<? } ?>><?= $row['nombre_plataforma'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="resultado_prospecto" <? if ($id_plataforma == 0 || $id_plataforma == 3) { ?>style="display: none;" <? } ?>>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Resultado Validación:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <fieldset class="controls">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="1" name="prospecto_validacion" id="prospecto_aprobado" class="custom-control-input" required <? if ($resultado_dc_prospecto == 1 || $confirmar_aprobado == 1) { ?>checked<? } ?> onchange="actualizarProspectoDetalles(this.value, 'id_estado_prospecto')" <?= $disabled ?>>
                                                            <label class="custom-control-label" for="prospecto_aprobado">Aprobado</label>
                                                        </div>
                                                    </fieldset>
                                                    &nbsp; &nbsp;
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" value="2" name="prospecto_validacion" id="prospecto_rechazado" class="custom-control-input" <? if ($resultado_dc_prospecto == 2 || $confirmar_rechazado == 1) { ?>checked<? } ?> onchange="actualizarProspectoDetalles(this.value, 'id_estado_prospecto')" <?= $disabled ?>>
                                                            <label class="custom-control-label" for="prospecto_rechazado">Rechazado</label>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="hidden" name="id_usuario_responsable" id="<?= $id_usuario_responsable ?>">
                                            <input type="hidden" name="id_prospecto" id="id_prospecto" value="<?= $id_prospecto ?>">
                                            <input type="hidden" name="id_responsable_interno" id="id_responsable_interno" value="<?= $id_responsable_interno ?>">
                                            <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario ?>">
                                            <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar()">Salir</button>
                                            <div id="zone-despachar">
                                                <? if ($id_plataforma != 3) {
                                                    if ($resultado_dc_prospecto == 1 && $id_estado_prospecto == 0) { ?>
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left : 5px;" onClick="despacharEquipo(<?= $id_prospecto ?>, <?= $id_usuario ?>)" id="boton-despachar">PDTE. POR ENTREGAR</button>
                                                    <? } else if ($resultado_dc_prospecto == 2 && $id_estado_prospecto == 0) { ?>
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-warning" style="float: right; margin-left : 5px;" onClick="confirmarRechazado(<?= $id_prospecto ?>, <?= $id_usuario ?>)" id="boton-rechazar">CONFIRMAR RECHAZADO</button>
                                                    <? } else if ($id_estado_prospecto == 3) { ?>
                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: right; margin-left : 5px;" onClick="modificarValidacion(<?= $id_prospecto ?>, <?= $id_usuario ?>)" id="boton-modificar">MODIFICAR VALIDACIÓN</button>
                                                    <? } ?>
                                                <? } ?>
                                            </div>
                                            <div id="zone-crear">
                                                <? if ($id_plataforma == 3) {

                                                    if ($id_estado_prospecto == 0) { ?>

                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left : 5px;" onClick="CrearSolicitud(<?= $id_prospecto ?>, <?= $id_usuario ?>)" id="boton-crear">Crear Solicitud</button>

                                                    <?
                                                    } else { ?>

                                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: right; margin-left : 5px;" onClick="modificarValidacion(<?= $id_prospecto ?>, <?= $id_usuario ?>)" id="boton-modificar">MODIFICAR VALIDACION</button>

                                                <? }
                                                } ?>
                                            </div>
                                            <? //si ya tiene responsable interno y si el usuario login es el responsable interno
                                            if ($id_responsable_interno != 0 && $id_responsable_interno == $id_usuario) {
                                            ?>
                                                <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: left;" onClick="soltarProspecto(<?= $id_prospecto ?>)" id="boton-soltar">Soltar Prospecto</button>
                                            <? }
                                            if ($id_estado_prospecto <> 5) {
                                            ?>
                                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: left; margin-left: 5px;" onClick="ventaNoRealizada(<?= $id_prospecto ?>)" id="boton-soltar">Venta no realizada</button>
                                            <? } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <? } ?>

            <!-- final imagenes prospectos -->
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
    </div>


    <!-- cargar imagen prospecto -->
    <div id="cargarimagenp-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cargar imagen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="cargarImagenForm" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div id="zone-subir">
                                <div class="form-group">
                                    <label>Seleccionar Imagen<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Cargar</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="file" id="file" class="form-control" accept="jpg, png, jpeg">
                                        </div>
                                    </div>
                                    <br>
                                    <!--<p class="help-block">Solo acpeta formatos<span class="text-danger">JPG, PNG, JPEG</span>.</p>-->
                                    <p class="help-block"><span class="text-danger">IMPORTANTE</span>:&nbsp;Subir parte frontal de la cedula.</p>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto_img" id="id_prospecto_img">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Cargar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <? if (profile(23, $id_usuario) == 1) { ?>
        <!-- plataformas modal -->
        <div id="plataformas-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Plataformas de credito.</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="plataformasForm" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Plataformas:<span class="text-danger">&nbsp;*</span></label>
                                <div class="input-group">
                                    <select class="form-control select2Class" name="plataformas_credito" id="plataformas_credito" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                        <option value="placeholder" disabled>Seleccionar Plataforma</option>
                                        <?php
                                        $query = "SELECT plataformas_credito.id AS id_plataforma, nombre_plataforma FROM plataformas_credito WHERE plataformas_credito.del = 0";
                                        $result = qry($query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $id_plataforma = $row['id_plataforma'];
                                            $nombre_plataforma = $row['nombre_plataforma'];
                                        ?>
                                            <option value="<?= $id_plataforma ?>"><?= $nombre_plataforma ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_prospecto" value="<?= $id_prospecto ?>">
                            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                            <input type="hidden" name="action" id="action_plataforma" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary">Cargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal -->

    <? } ?>


    <script>

        'use strict';

        var idProspecto = document.getElementById('id_prospecto').value;
        var action = "actualizar_prospecto";
        var resultadoProspecto = $("#resultado_prospecto");
        var zoneDespachar = $("#zone-despachar");
        var zoneCrear = $("#zone-crear");
        var idUsuario = $("#id_usuario").val();

        function cancelar() {

            setTimeout(function() {
                location.href = "?page=prospectos"
            }, 1000);

        }


        function soltarProspecto(idProspecto) {

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

                            if (respuesta.response == "sucess") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Solicitud Creada Correctamente',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                                //setTimeout(function(){location.href="?page=dashboard"} , 3500);

                            }



                        }

                    });

                } else {

                    return 0;

                }
            });

        }

        function cambiarImagenP(idProspecto) {

            $("#file").val('');
            $("#id_prospecto_img").val(idProspecto);
            $("#cargarimagenp-modal").modal("show");

        }

        $("#cargarImagenForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = "cargar_imagenp";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("cargarImagenForm"));
            formData.append("action", action);

            cargarImagenPDB(formData);

        });


        function cargarImagenPDB(dates) {

            //console.log(...dates);
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectosAjax.php', true);
            // pass Info
            xhr.onload = function() {

                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Imagen cargada correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        $("#cargarimagenp-modal").modal("hide");

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }

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

            // send dates
            xhr.send(dates)
        }

        function cambiarResultado(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "quitar_resultado"
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

                        $("#prospecto_rechazado").prop('checked', false);

                        $("#prospecto_aprobado").prop('checked', false);

                        zoneDespachar.empty();
                        zoneCrear.empty();
                        zoneDespachar.hide();
                        zoneCrear.hide();

                    } else {

                        return 0;

                    }
                }

            });

        }


        function actualizarProspectoDetalles(date, type) {

            var parameters = {
                "date": date,
                "type": type,
                "id_prospecto": idProspecto,
                "id_usuario": idUsuario,
                "action": action
            };

            console.log(parameters);

            $.ajax({
                data: parameters,
                url: 'ajax/prospectosAjax.php',
                type: 'post',
                success: function(response) {
                    //const ToJson = JSON.parse(response);
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        if (respuesta.type == "id_plataforma") {

                            if (respuesta.date != 3) {

                                resultadoProspecto.show();
                                zoneCrear.hide();


                                cambiarResultado(idProspecto);

                            } else if (respuesta.date == 3) {

                                resultadoProspecto.hide();
                                zoneDespachar.hide();
                                zoneCrear.show();
                                zoneCrear.html(`<button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left : 5px;" onClick="CrearSolicitud(${respuesta.id_prospecto}, ${respuesta.id_usuario})" id="boton-crear">Crear Solicitud</button>`);

                            }

                        } else if (respuesta.type == "id_estado_prospecto") {

                            zoneDespachar.empty();

                            if (respuesta.date == 1) {

                                zoneDespachar.html(`<button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left : 5px;" onClick="despacharEquipo(${respuesta.id_prospecto}, ${respuesta.id_usuario})" id="boton-despachar">Pdte. Por Entregar</button>`);

                            } else if (respuesta.date == 2) {

                                zoneDespachar.html(`<button type="button" class="btn waves-effect waves-light btn-lg btn-warning" style="float: right; margin-left : 5px;" onClick="confirmarRechazado(${respuesta.id_prospecto}, ${respuesta.id_usuario})" id="boton-rechazar">CONFIRMAR RECHAZADO</button>`);

                            }

                            zoneDespachar.show();

                        }

                    }

                }
            });
        }


        function despacharEquipo(idProspecto, idUusuario) {

            var parameters = {
                "id_prospecto": idProspecto,
                "id_usuario": idUusuario,
                "action": "despachar_equipo"
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
                            title: 'Equipo validado correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        setTimeout(function() {
                            location.href = "?page=prospectos"
                        }, 3500);

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

        function CrearSolicitud(idProspecto, idUusuario) {

            var parameters = {
                "id_prospecto": idProspecto,
                "id_usuario": idUusuario,
                "action": "crear_solicitud"
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
                            title: 'Equipo validado correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        setTimeout(function() {
                            location.href = "?page=solicitudes"
                        }, 3500);

                    } else if (respuesta.response == "solicitudes_activas") {


                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Prospecto con solicitud Activa',
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

        function confirmarRechazado(idProspecto, idUsuario) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Si confirmas el rechazado, no podras validar nuevamente el cliente!",
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
                        "id_isuario": idUsuario,
                        "action": "confirmar_rechazado"
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
                                    title: 'Prospecto Actualizado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                                setTimeout(function() {
                                    location.href = "?page=prospectos"
                                }, 3500);

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

                } else {

                    return 0;

                }
            });

        }

        function ventaNoRealizada(id_prospecto) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "El prospecto perdera todas las solicitudes activas, recuerda indicar en la observación el motivo!",
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
                        "action": "venta_no_realizada"
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
                                    title: 'Realizado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                                setTimeout(function() {
                                    location.href = "?page=prospectos"
                                }, 3500);

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

                } else {

                    return 0;

                }
            });

        }

        function modificarValidacion(idProspecto, idUsuario) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Si el Cliente esta pdte por entregar se reiniciara!",
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
                        "action": "downgrade_validar"
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
                                    title: 'Prospecto actualizado correctamente',
                                    showConfirmButton: false,
                                    timer: 2500

                                });

                                setTimeout("location.reload()", 3000);

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

            });

        }


        function descargarTodas(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "descargar_imagenes"
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
                            title: 'Prospecto actualizado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        setTimeout("location.reload()", 3000);

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
    </script>

<? } else { ?>

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
                    <h4 class="text-themecolor"><?= ucwords($prospecto_full_name) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="?page=prospectos">Prospectos</a></li>
                            <li class="breadcrumb-item active"><?= ucwords(str_replace('_', ' ', $page)) ?></li>
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
            <div class="row" id="prospecto-zone">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h1>FALTA LA INFORMACIÓN DEL PROSPECTO</h1>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

<? } ?>