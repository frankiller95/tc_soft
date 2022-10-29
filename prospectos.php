<?

if (profile(12, $id_usuario) == 1) {

    $query_filtro = "";
    $subtitulo = "Todos";

    if (isset($_GET['filtro'])) {

        $filtro = $_GET['filtro'];

        if ($filtro == "c") {

            $id_estado_prospecto = 11;
            $subtitulo = "Creación";
        } else if ($filtro == "pl") {

            $id_estado_prospecto = 7;
            $subtitulo = "Pdte. Llamar";
        } else if ($filtro == "pv") {

            $id_estado_prospecto = 12;
            $subtitulo = "Pdte. Validar";
        } else if ($filtro == "pc") {

            $id_estado_prospecto = 4;
            $subtitulo = "Pdte. Comprobante";
        }

        $query_filtro = " AND prospectos.id_estado_prospecto = $id_estado_prospecto";
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
                            <h4 class="card-title">Prospectos&nbsp;<?= ucwords($subtitulo) ?></h4>
                            <a href="?page=prospectos" class="btn waves-effect waves-light btn-sm btn-info" style="float: left; margin-right: 5px;">Todos</a>
                            <a href="?page=prospectos&filtro=c" class="btn waves-effect waves-light btn-sm btn-info" style="float: left; margin-right: 5px;">Creación</a>
                            <a href="?page=prospectos&filtro=pl" class="btn waves-effect waves-light btn-sm btn-info" style="float: left; margin-right: 5px;">Pdte. Llamar</a>
                            <a href="?page=prospectos&filtro=pv" class="btn waves-effect waves-light btn-sm btn-info" style="float: left; margin-right: 5px;">Pdte. Validar</a>
                            <a href="?page=prospectos&filtro=pc" class="btn waves-effect waves-light btn-sm btn-info" style="float: left; margin-right: 5px;">Pdte. Comprobante</a>
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="addProspecto()"><?= ucwords('Crear Prospecto') ?></button>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableProspectos" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Cedula</th>
                                            <th>Prospecto</th>
                                            <th>Contacto</th>
                                            <th>Ubicación</th>
                                            <th>Asesor</th>
                                            <th>Estado</th>
                                            <th>Fecha y Hora</th>
                                            <th>Resultados</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $all_prospectos = profile(14, $id_usuario);
                                        $filter = "";

                                        if ($all_prospectos == 0) {
                                            $filter = " AND prospectos.id_usuario_responsable = '$id_usuario'";
                                        }

                                        $query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, ciudades.ciudad, departamentos.departamento, estados_prospectos.estado_prospecto, prospectos.resultado_dc_prospecto, prospectos.id_estado_prospecto, CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS nombre_responsable, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y %H:%i:%s') AS fecha_hora_registro FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.del = 0" . $filter . $query_filtro . " ORDER BY prospectos.id DESC";

                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_prospecto =  $row1['id_prospecto'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $prospecto_info = $row1['prospecto_nombre'] . ' ' . $row1['prospecto_apellidos'];
                                            $contacto = $row1['prospecto_numero_contacto'];
                                            if ($contacto == 0 || $contacto == '') {
                                                $contacto = 'N/A';
                                            }
                                            $ciudad =  $row1['ciudad'];
                                            if ($ciudad == 0) {
                                                $ubicacion = "N/A";
                                            } else {
                                                $ubicacion = $ciudad . ' / ' . $row1['departamento'];
                                            }
                                            $estado = $row1['estado_prospecto'];
                                            $id_estado_prospecto = $row1['id_estado_prospecto'];
                                            $color = '';
                                            if ($id_estado_prospecto == 11 || $id_estado_prospecto == 4) {
                                                $color = "info";
                                            } else if ($id_estado_prospecto == 12) {
                                                $color = "warning";
                                            } else if ($id_estado_prospecto == 7 || $id_estado_prospecto == 3) {
                                                $color = "success";
                                            }

                                            $nombre_responsable = $row1['nombre_responsable'];

                                            $fecha_hora_registro = $row1['fecha_hora_registro'];

                                            $resultados_plataformas_total = execute_scalar("SELECT COUNT(*) AS total FROM resultados_prospectos WHERE resultados_prospectos.id_prospecto = $id_prospecto AND resultados_prospectos.del = 0");

                                            $resultados_plataformas = '<span class="label label-info">N/A</span>';

                                            if ($resultados_plataformas_total != 0) {

                                                //funcion que trae las plataformas
                                                $resultados_plataformas = traer_plataformas($id_prospecto);
                                            }

                                            $validate_record_pdte_validar = execute_scalar("SELECT COUNT(*) AS total FROM prospectos_pendiente_validar WHERE id_prospecto = $id_prospecto AND del = 0");

                                            $estado_mostrar2 = '';

                                            if ($validate_record_pdte_validar != 0 and $id_estado_prospecto == 12) {
                                                $id_estado_pdte_validar = execute_scalar("SELECT id_estado FROM prospectos_pendiente_validar WHERE id_prospecto = $id_prospecto AND del = 0");
                                                if ($id_estado_pdte_validar == 1) {
                                                    $estado_nombre = "CUENTA CON LA INICIAL";
                                                } else {
                                                    $estado_nombre = "CLIENTE DISPONIBLE PARA LLAMAR";
                                                }
                                                $estado_mostrar2 = '<p style="font-size: 12px"><span class="label label-info">-' . $estado_nombre . '</span></p>';
                                            }

                                        ?>
                                            <tr class="row-<?= $id_prospecto ?>">
                                                <td><?= $id_prospecto ?></td>
                                                <td><?= $prospecto_cedula ?></td>
                                                <td><?= ucwords($prospecto_info) ?></td>
                                                <td><?= $contacto ?></td>
                                                <td><?= ucwords($ubicacion) ?></td>
                                                <td><?= ucwords($nombre_responsable) ?></td>
                                                <td id="estado_prospecto_<?= $id_prospecto ?>"><span class="label label-<?= $color ?>"><?= ucwords($estado) ?></span><?= $estado_mostrar2 ?></td>
                                                <td><?= $fecha_hora_registro ?></td>
                                                <td><?= $resultados_plataformas ?></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <div id="row-<?= $id_prospecto ?>">
                                                        <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto" onclick="crearPlantilla(<?= $id_prospecto ?>)"><i class="mdi mdi-pencil"></i></a>
                                                        <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagenes" onclick="subirImagenes(<?= $id_prospecto ?>)"><i class="fas fa-images"></i></a>
                                                        <? if ($id_estado_prospecto == 11 || $id_estado_prospecto == 7) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reprogramar LLamada" onclick="reproLlamada(<?= $id_prospecto ?>)"><i class="fas fa-phone-volume"></i></a>
                                                        <? }
                                                        if ($id_estado_prospecto == 11) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="<?= ucwords('Pdte por validar') ?>" onclick="validacionPdteValidar(<?= $id_prospecto ?>, 1)"><i class="fas fa-check"></i></a>
                                                        <? }
                                                        if ($id_estado_prospecto == 7) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte validar" onclick="validacionPdteValidar(<?= $id_prospecto ?>, 2)"><i class="fas fa-check"></i></a>
                                                        <? }
                                                        if ($id_estado_prospecto == 4) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Comprobante" onclick="cargarComprobante(<?= $id_prospecto ?>)"><i class="fas fa-file-image"></i></a>
                                                        <? }
                                                        if ($all_prospectos == 1) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar Solicitud" onClick="eliminarProspecto(<?= $id_prospecto ?>)"><i class="fas fa-times"></i></a>
                                                        <? }  ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <input type="hidden" id="id_usuario_prospecto" value="<?= $id_usuario ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
    </div>

    <!-- Add prospecto modal -->
    <div id="registrar-prospecto-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_prospectos"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="registrarProspectoForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>cedula:<span class="text-danger">&nbsp;*</span></label></label>
                                    <div class="input-group">
                                        <input class="form-control" name="cedula_prospecto" id="cedula_prospecto" placeholder="Ingrese la cedula" autocomplete="ÑÖcompletes" maxlength="16" required onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="prospecto_nombre" id="prospecto_nombre" placeholder="Escribe el nombre completo" style="text-transform:uppercase" oninput="validar(this)" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Apellidos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="prospecto_apellidos" id="prospecto_apellidos" placeholder="Escribe los apellidos" style="text-transform:uppercase" oninput="validar(this)" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto" value="">
                        <input type="hidden" name="action" id="action_prospecto" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- update prospecto modal -->
    <div id="update-prospecto-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Completar Prospecto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="completarProspectoForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>cedula:<span class="text-danger">&nbsp;*</span></label></label>
                                    <div class="input-group">
                                        <input class="form-control" name="cedula_prospecto" id="cedula_prospecto_editar" placeholder="Ingrese la cedula" autocomplete="ÑÖcompletes" maxlength="16" required onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="prospecto_nombre" id="prospecto_nombre_editar" placeholder="Escribe el nombre completo" style="text-transform:uppercase" oninput="validar(this)" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Apellidos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="prospecto_apellidos" id="prospecto_apellidos_editar" placeholder="Escribe los apellidos" style="text-transform:uppercase" oninput="validar(this)" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="id_referencia_equipo" id="id_referencia_equipo_editar" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" onchange="selectInicial(this.value)" required>
                                            <option value="placeholder" disabled>Seleccionar Referencia</option>
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
                                    <label>Inicial:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="inicial_referencia" id="inicial_referencia_editar" placeholder="Escribe el precio de la inicial" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Plazo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <fieldset class="controls">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="4" name="plazo_credito" id="termino_4_meses" class="custom-control-input all-check" required>
                                                <label class="custom-control-label" for="termino_4_meses">4 Meses</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
                                        <fieldset class="controls">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="6" name="plazo_credito" id="termino_6_meses" class="custom-control-input all-check">
                                                <label class="custom-control-label" for="termino_6_meses">6 Meses</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
                                        <fieldset class="controls">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="8" name="plazo_credito" id="termino_8_meses" class="custom-control-input all-check">
                                                <label class="custom-control-label" for="termino_8_meses">8 Meses</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
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
                                    <label>Fecha de Nacimiento:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="fecha_nacimiento_prospecto" id="fecha_nacimiento_prospecto" placeholder="Fecha de Nacimiento" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sexo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <fieldset class="controls">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="M" name="sexo_prospecto" id="sexo_prospecto_m" class="custom-control-input all-check">
                                                <label class="custom-control-label" for="sexo_prospecto_m">Masculino</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
                                        <fieldset class="controls">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="F" name="sexo_prospecto" id="sexo_prospecto_f" class="custom-control-input all-check">
                                                <label class="custom-control-label" for="sexo_prospecto_f">Femenino</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!---<div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="plantilla_area">Observación:</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="plantilla_area" id="plantilla_area" rows="10" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_editar" value="">
                        <input type="hidden" name="action" id="action_prospecto_editar" value="">
                        <input type="hidden" name="from" value="prospectos">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- subir imagenes modal -->
    <div id="subir-imgenes-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cargar Imagenes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="subirImagenesForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40">
                                    <table id="dataTableImagenes" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th><?= ucwords('IMAGEN') ?></th>
                                                <th><?= ucwords('ESTADO') ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>CEDULA</td>
                                                <td id="estado_1"></td>
                                                <td id="botones_imagenes_1"></td>
                                            </tr>
                                            <tr>
                                                <td>ATRAS</td>
                                                <td id="estado_2"></td>
                                                <td id="botones_imagenes_2"></td>
                                            </tr>
                                            <tr>
                                                <td>SELFIE</td>
                                                <td id="estado_3"></td>
                                                <td id="botones_imagenes_3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_imagenes" value="">
                        <input type="hidden" name="action" id="action_prospecto_imagenes" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- cargar archivos modal -->
    <div id="cargar-imagen-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Subir Imagen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="subirImagenForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h4 class="card-title" id="imagen_titulo"></h4>
                                <label for="cargar_imagen"></label>
                                <input type="file" name="imagen_cc" id="cargar_imagen" class="dropify" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_imagen" value="">
                        <input type="hidden" name="tipo_img" id="tipo_img">
                        <input type="hidden" name="action" id="action_cargar_imagen" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="submit" class="btn btn-success" style="float: left;">Cargar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- pdte por llamar modal -->
    <div id="pdte-llamar-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pdte Por Llamar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="pdtePorLlamarForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fecha de llamada:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="pickup_date" name="pickup_date" class="form-control min-date" placeholder="Seleccionar fecha de llamada" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Hora Llamada:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>
                                        </div>
                                        <input id="pickup_start" name="pickup_start" class="form-control date-start" placeholder="Selec. Hora Inicial Entrega" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Resultado:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="estado_pendiente_por_llamar" id="estado_pendiente_por_llamar" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" selected disabled>Seleccionar Estado</option>
                                            <?php
                                            $query = "SELECT estados_pendiente_llamar.id AS estado_id, estados_pendiente_llamar.estado_mostrar FROM estados_pendiente_llamar WHERE estados_pendiente_llamar.del = 0";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                                $estado_id = $row['estado_id'];
                                                $estado_mostrar = $row['estado_mostrar'];
                                            ?>
                                                <option value="<?= $estado_id ?>"><?= ucwords($estado_mostrar) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_pdte" value="">
                        <input type="hidden" name="action" id="action_prospecto_pdte" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <!-- pdte por validar modal -->
    <div id="pdte-validar-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pdte Por Llamar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="pdtePorvalidarForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Motivo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="estado_pendiente_validar" id="estado_pendiente_validar" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" selected disabled>Seleccionar Estado</option>
                                            <option value="1"><?= ucwords('Cliente ya cuenta con la inicial') ?></option>
                                            <option value="2"><?= ucwords('Cliente disponible para llamada') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_validar" value="">
                        <input type="hidden" name="action" id="action_prospecto_validar" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <!-- detalles cancelacion modal -->
    <div id="detalles-cancelar-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Observación al cancelar.</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="detallesCancelarForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observación:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="observacion_cancelacion" id="observacion_cancelacion" rows="10" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_cancelacion" value="">
                        <input type="hidden" name="action" id="action_prospecto_cancelacion" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        //var idUsuario = $("#id_usuario_prospecto").val();
        var idUsuario = document.getElementById("id_usuario_prospecto").value;
        //console.log(idUsuario);

        function addProspecto() {
            $("#titulo_prospectos").html('Crear Prospecto');
            $("#cedula_prospecto").val('');
            $("#prospecto_nombre").val('');
            $("#prospecto_apellidos").val('');
            $("#id_prospecto").val('');
            $("#action_prospecto").val('insertar_prospecto');
            $("#registrar-prospecto-modal").modal("show");
        }

        $("#registrarProspectoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarProspectoForm"));

            const action = document.querySelector("#action_prospecto").value;

            if (action == "insertar_prospecto") {

                insertProspectoDB(formData);

            } else {

                updateUsuarioDB(formData);

            }

        });

        function insertProspectoDB(dates) {
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos3Ajax.php', true);
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
                            title: 'prospecto registrado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        traerPlataformas(respuesta.id_prospecto);

                        $("#registrar-prospecto-modal").modal("hide");


                    } else if (respuesta.response == "cedula_repetida") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Cedula ya registrada',
                            showConfirmButton: false,
                            timer: 3000

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
            }

            // send dates
            xhr.send(dates)

        }


        function crearPlantilla(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_info"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/prospectos3Ajax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $("#id_prospecto_editar").val(idProspecto);
                    $("#cedula_prospecto_editar").val(respuesta[0].prospecto_cedula);
                    $("#prospecto_nombre_editar").val(respuesta[0].prospecto_nombre);
                    $("#prospecto_apellidos_editar").val(respuesta[0].prospecto_apellidos);
                    $("#inicial_referencia_editar").val('');
                    $("#termino_4_meses").prop('checked', false);
                    $("#termino_6_meses").prop('checked', false);
                    $("#termino_8_meses").prop('checked', false);
                    $("#id_referencia_equipo_editar").val('placeholder');
                    $("#id_referencia_equipo_editar").trigger('change');
                    $("#contacto_prospecto").val('');
                    $("#email_prospecto").val('');
                    $("#direccion_prospecto").val('');
                    $("#ciudad_prospecto").val('placeholder');
                    $("#ciudad_prospecto").trigger('change');
                    $("#fecha_nacimiento_prospecto").val('');
                    $("#sexo_prospecto_m").prop('checked', false);
                    $("#sexo_prospecto_f").prop('checked', false);

                    if (respuesta[0].inicial_confirmada !== null) {
                        $("#inicial_referencia_editar").val(respuesta[0].inicial_confirmada);
                    }
                    if (respuesta[0].id_referencia !== null) {
                        $("#id_referencia_equipo_editar").val(respuesta[0].id_referencia);
                        $("#id_referencia_equipo_editar").trigger('change');
                    }
                    if (respuesta[0].plazo_meses !== null) {
                        if (respuesta[0].plazo_meses == 4) {
                            $("#termino_4_meses").prop('checked', true);
                        } else if (respuesta[0].plazo_meses == 6) {
                            $("#termino_6_meses").prop('checked', true);
                        } else if (respuesta[0].plazo_meses == 8) {
                            $("#termino_8_meses").prop('checked', true);
                        }
                    }
                    if (respuesta[0].prospecto_numero_contacto !== null) {
                        $("#contacto_prospecto").val(respuesta[0].prospecto_numero_contacto);
                    }
                    if (respuesta[0].prospecto_email !== null) {
                        $("#email_prospecto").val(respuesta[0].prospecto_email);
                    }
                    if (respuesta[0].prospecto_direccion !== null) {
                        $("#direccion_prospecto").val(respuesta[0].prospecto_direccion);
                    }
                    if (respuesta[0].ciudad_id !== 0) {
                        $("#ciudad_prospecto").val(respuesta[0].ciudad_id);
                        $("#ciudad_prospecto").trigger("change");
                    }
                    if (respuesta[0].prospecto_dob !== null) {
                        $("#fecha_nacimiento_prospecto").val(respuesta[0].prospecto_dob);
                    }
                    if (respuesta[0].prospecto_sexo == "M") {
                        $("#sexo_prospecto_m").prop('checked', true);
                    } else if (respuesta[0].prospecto_sexo == "F") {
                        $("#sexo_prospecto_f").prop('checked', true);
                    }
                    $("#action_prospecto_editar").val('completar_prospecto');
                    $("#update-prospecto-modal").modal("show");

                }

            });

        }

        function selectInicial(idReferencia) {

            if (idReferencia !== "placeholder") {

                idProspecto = $("#id_prospecto_editar").val();

                var parameters = {
                    "id_referencia": idReferencia,
                    "id_prospecto": idProspecto,
                    "action": "select_inicial"
                };

                $.ajax({

                    data: parameters,
                    url: 'ajax/prospectos3Ajax.php',
                    type: 'post',
                    success: function(response) {
                        console.log(response);
                        const respuesta = JSON.parse(response);
                        console.log(respuesta);

                        if (respuesta.response == "success") {
                            $("#inicial_referencia_editar").val(respuesta.inicial);
                        }

                    }
                });

            } else {

                return 0;

            }

        }


        $("#completarProspectoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("completarProspectoForm"));

            const action = document.querySelector("#action_prospecto_editar").value;

            updateProspectoDB(formData);

        });

        function updateProspectoDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos3Ajax.php', true);
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
                            title: 'prospecto registrado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        traerPlataformas(respuesta.id_prospecto);

                        $("#update-prospecto-modal").modal("hide");

                    } else if (respuesta.response == "telefono_invalido") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Revisa el numero de contacto',
                            showConfirmButton: false,
                            timer: 4000

                        });


                    } else if (respuesta.response == "cedula_repetida") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Cedula ya registrada',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        //queda pendiente hacer un proceso con estos clientes

                    } else if (respuesta.response == "falta_sexo") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Indica el Sexo del Prospecto',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "falta_ciudad") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Indica la Ciudad del Prospecto',
                            showConfirmButton: false,
                            timer: 3000

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
            }

            // send dates
            xhr.send(dates)

        }


        function subirImagenes(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_imagenes"
            }

            $.ajax({

                data: parameters,
                url: 'ajax/prospectos3Ajax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var estado1 = $("#estado_1"),
                        estado2 = $("#estado_2"),
                        estado3 = $("#estado_3"),
                        botones1 = $("#botones_imagenes_1"),
                        botones2 = $("#botones_imagenes_2"),
                        botones3 = $("#botones_imagenes_3");

                    estado1.empty();
                    estado2.empty();
                    estado3.empty();
                    botones1.empty();
                    botones2.empty();
                    botones3.empty();

                    if (respuesta.imagen_1 == 0) {

                        estado1.html('<span class="label label-info">FALTA</span>');

                        botones1.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagen" onclick="subirImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-upload"></i></a>`);

                    } else {

                        estado1.html('<span class="label label-success">COMPLETA</span>');

                        botones1.html(`<a href="./documents/prospects/${respuesta.id_prospecto}/frontal.jpg" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-trash-alt"></i></a>`);

                    }

                    if (respuesta.imagen_2 == 0) {

                        estado2.html('<span class="label label-info">FALTA</span>');

                        botones2.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagen" onclick="subirImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-upload"></i></a>`);

                    } else {

                        estado2.html('<span class="label label-success">COMPLETA</span>');

                        botones2.html(`<a href="./documents/prospects/${respuesta.id_prospecto}/atras.jpg" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-trash-alt"></i></a>`);

                    }

                    if (respuesta.imagen_3 == 0) {

                        estado3.html('<span class="label label-info">FALTA</span>');

                        botones3.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagen" onclick="subirImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-upload"></i></a>`);

                    } else {

                        estado3.html('<span class="label label-success">COMPLETA</span>');

                        botones3.html(`<a href="./documents/prospects/${respuesta.id_prospecto}/selfie.jpg" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-trash-alt"></i></a>`);

                    }

                    var config = {
                        type: 'image',
                        callbacks: {}
                    };

                    var cssHeight = '800px'; // Add some conditions here

                    config.callbacks.open = function() {
                        $(this.container).find('.mfp-content').css('height', cssHeight);
                    };

                    $('.image-complete').magnificPopup(config);

                    $("#id_prospecto_imagenes").val(idProspecto);
                    $("#subir-imgenes-modal").modal("show");

                }

            });

        }


        function subirImagen(idProspecto, tipoImg) {

            //console.log(tipoImg);
            if (tipoImg == 'frontal') {

                $("#imagen_titulo").html('Imagen Parte de adelante de la cedula');

            } else if (tipoImg == 'atras') {

                $("#imagen_titulo").html('Imagen Parte de atras de la cedula');

            } else if (tipoImg == 'selfie') {

                $("#imagen_titulo").html('Imagen Tipo selfie del cliente con la cedula');

            }

            $('.dropify-clear').click();

            $("#id_prospecto_imagen").val(idProspecto);
            $("#tipo_img").val(tipoImg);
            $("#action_cargar_imagen").val('cargar_imagen');
            $("#cargar-imagen-modal").modal("show");

        }


        function cambiarImagen(idProspecto, tipoImg) {

            if (tipoImg == 'frontal') {

                $("#imagen_titulo").html('Imagen Parte de adelante de la cedula');

            } else if (tipoImg == 'atras') {

                $("#imagen_titulo").html('Imagen Parte de atras de la cedula');

            } else if (tipoImg == 'selfie') {

                $("#imagen_titulo").html('Imagen Tipo selfie del cliente con la cedula');

            }

            $('.dropify-clear').click();

            $("#id_prospecto_imagen").val(idProspecto);
            $("#tipo_img").val(tipoImg);
            $("#action_cargar_imagen").val('actualizar_imagen');
            $("#cargar-imagen-modal").modal("show");
        }


        $("#subirImagenForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("subirImagenForm"));

            const action = document.querySelector("#action_cargar_imagen").value;

            if (action == "cargar_imagen") {

                insertImagenDB(formData);

            } else if (action == "cargar_comprobante") {

                insertBaucherDB(formData);

            } else {

                updateImagenDB(formData);

            }

        });


        function insertImagenDB(dates) {
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos3Ajax.php', true);
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
                            title: 'CORRECTO!',
                            text: 'Imagen cargada satisfactoriamente',
                            showConfirmButton: false,
                            timer: 4000

                        });

                        var estado1 = $("#estado_1"),
                            estado2 = $("#estado_2"),
                            estado3 = $("#estado_3"),
                            botones1 = $("#botones_imagenes_1"),
                            botones2 = $("#botones_imagenes_2"),
                            botones3 = $("#botones_imagenes_3");

                        if (respuesta.tipo_img == "frontal") {

                            estado1.empty();
                            botones1.empty();
                            estado1.html(`<span class="label label-success">COMPLETA</span>`);
                            botones1.html(`<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-trash-alt"></i></a>`);

                        } else if (respuesta.tipo_img == "atras") {


                            estado2.empty();
                            botones2.empty();
                            estado2.html(`<span class="label label-success">COMPLETA</span>`);
                            botones2.html(`<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-trash-alt"></i></a>`);

                        } else if (respuesta.tipo_img == "selfie") {

                            estado3.empty();
                            botones3.empty();
                            estado3.html(`<span class="label label-success">COMPLETA</span>`);
                            botones3.html(`<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-trash-alt"></i></a>`);

                        }

                        var config = {
                            type: 'image',
                            callbacks: {}
                        };

                        var cssHeight = '800px'; // Add some conditions here

                        config.callbacks.open = function() {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                        $("#cargar-imagen-modal").modal("hide");


                    } else if (respuesta.response == "tipo_incorrecto") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Formato de imagen incorrecto',
                            text: 'selecciona la imagen en formato jpg o png',
                            showConfirmButton: false,
                            timer: 3000

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
            }

            // send dates
            xhr.send(dates)

        }

        function updateImagenDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos3Ajax.php', true);
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
                            title: 'CORRECTO!',
                            text: 'Imagen actualizada satisfactoriamente',
                            showConfirmButton: false,
                            timer: 4000

                        });

                        var estado1 = $("#estado_1"),
                            estado2 = $("#estado_2"),
                            estado3 = $("#estado_3"),
                            botones1 = $("#botones_imagenes_1"),
                            botones2 = $("#botones_imagenes_2"),
                            botones3 = $("#botones_imagenes_3");

                        if (respuesta.tipo_img == "frontal") {

                            estado1.empty();
                            botones1.empty();
                            estado1.html(`<span class="label label-success">COMPLETA</span>`);
                            botones1.html(`<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'frontal')"><i class="fas fa-trash-alt"></i></a>`);

                        } else if (respuesta.tipo_img == "atras") {


                            estado2.empty();
                            botones2.empty();
                            estado2.html(`<span class="label label-success">COMPLETA</span>`);
                            botones2.html(`<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'atras')"><i class="fas fa-trash-alt"></i></a>`);

                        } else if (respuesta.tipo_img == "selfie") {

                            estado3.empty();
                            botones3.empty();
                            estado3.html(`<span class="label label-success">COMPLETA</span>`);
                            botones3.html(`<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-redo"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="eliminarImagen(${respuesta.id_prospecto}, 'selfie')"><i class="fas fa-trash-alt"></i></a>`);

                        }

                        var config = {
                            type: 'image',
                            callbacks: {}
                        };

                        var cssHeight = '800px'; // Add some conditions here

                        config.callbacks.open = function() {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                        $("#cargar-imagen-modal").modal("hide");


                    } else if (respuesta.response == "tipo_incorrecto") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3000

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
            }

            // send dates
            xhr.send(dates)
        }

        function insertBaucherDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/validacionesAjax.php', true);
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
                            title: 'CORRECTO!',
                            text: 'Baucher cargado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });


                        traerPlataformas(respuesta.id_prospecto);

                        var config = {
                            type: 'image',
                            callbacks: {}
                        };

                        var cssHeight = '800px'; // Add some conditions here

                        config.callbacks.open = function() {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                        $("#cargar-imagen-modal").modal("hide");


                    } else if (respuesta.response == "tipo_incorrecto") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3000

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
            }

            // send dates
            xhr.send(dates)
        }

        function eliminarImagen(idProspecto, tipoImg) {
            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para eliminar la imagen!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "tipo_img": tipoImg,
                        "action": "eliminar_imagen"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectos3Ajax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);

                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                var estado1 = $("#estado_1"),
                                    estado2 = $("#estado_2"),
                                    estado3 = $("#estado_3"),
                                    botones1 = $("#botones_imagenes_1"),
                                    botones2 = $("#botones_imagenes_2"),
                                    botones3 = $("#botones_imagenes_3");

                                if (tipoImg == "frontal") {

                                    estado1.empty();
                                    botones1.empty();
                                    estado1.html('<span class="label label-info">FALTA</span>');
                                    botones1.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagen" onclick="subirImagen(${idProspecto}, 'frontal')"><i class="fas fa-upload"></i></a>`);

                                } else if (tipoImg == "atras") {


                                    estado2.empty();
                                    botones2.empty();
                                    estado2.html('<span class="label label-info">FALTA</span>');
                                    botones2.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagen" onclick="subirImagen(${idProspecto}, 'atras')"><i class="fas fa-upload"></i></a>`);

                                } else if (tipoImg == "selfie") {

                                    estado3.empty();
                                    botones3.empty();
                                    estado3.html('<span class="label label-info">FALTA</span>');
                                    botones3.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagen" onclick="subirImagen(${idProspecto}, 'selfie')"><i class="fas fa-upload"></i></a>`);

                                }


                            } else {

                                $.toast({

                                    heading: 'Error!',
                                    text: 'Error en el proceso.',
                                    position: 'top-center',
                                    loaderBg: '#e46a76',
                                    icon: 'error',
                                    hideAfter: 4500,
                                    stack: 6
                                });

                            }
                        }

                    });
                }
            });

        }

        function validacionPdteValidar(idProspecto, from) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Recuerda que debes tener toda la información del prospecto (Información, plantilla, imagenes) cargadas!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "action": "validate_pdte_validar",
                        "from": from
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectos3Ajax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);

                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                if (from == 1) {

                                    Swal.fire({

                                        position: 'top-end',
                                        type: 'success',
                                        title: 'Estado actualizado correctamente',
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        timer: 4000

                                    });


                                    traerPlataformas(respuesta.id_prospecto);

                                } else if (from == 2) {

                                    $("#estado_pendiente_validar").val('placeholder');
                                    $("#estado_pendiente_validar").trigger('change');
                                    $("#id_prospecto_validar").val(respuesta.id_prospecto);
                                    $("#action_prospecto_validar").val('pdte_validar_update');
                                    $("#pdte-validar-modal").modal("show");

                                }

                            } else if (respuesta.response == "falta_informacion") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Debes cargar toda la información',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 4000

                                });


                            } else if (respuesta.response == "falta_imagenes") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Debes cargar todas las imagenes',
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
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                            }

                        }

                    });
                }
            });

        }

        /*
        function eliminarProspecto(idProspecto) {

            Swal.fire({

                title: 'Estas seguro?',
                text: "La información del prospecto se perdera y deberas crearla nuevamente!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "action": "delete_prospecto"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectos3Ajax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);

                            console.log(respuesta);


                            if (respuesta.response == "success") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Prospecto eliminado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 4000

                                });

                                tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

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
        */

        function traerPlataformas(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_plataformas",
                "id_usuario": idUsuario
            };

            $.ajax({

                data: parameters,
                url: 'ajax/validacionesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.status == "success") {

                        tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

                        var colorProspecto = '',
                            botonesTabla = '';

                        if (respuesta.id_estado_prospecto == 3) {
                            colorProspecto = 'success';
                        } else if (respuesta.id_estado_prospecto == 12) {
                            colorProspecto = 'warning';
                        } else {
                            colorProspecto = 'info';
                        }

                        botonesTabla = `<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto" onclick="crearPlantilla(${respuesta.id_prospecto})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Subir Imagenes" onclick="subirImagenes(${respuesta.id_prospecto})"><i class="fas fa-images"></i></a>`;

                        if (respuesta.id_estado_prospecto != 12 && respuesta.id_estado_prospecto != 3 && respuesta.id_estado_prospecto != 5) {
                            botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reprogramar LLamada" onclick="reproLlamada(${respuesta.id_prospecto})"><i class="fas fa-phone-volume"></i></a>`;
                            if (respuesta.total_plataformas == 0) {
                                botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte Por Validar" onclick="validacionPdteValidar(${respuesta.id_prospecto}, 1)"><i class="fas fa-check"></i></a>`;
                            } else {
                                botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte Por Validar" onclick="validacionPdteValidar(${respuesta.id_prospecto}, 2)"><i class="fas fa-check"></i></a>`;
                            }
                        }

                        if (respuesta.id_estado_prospecto == 4) {
                            botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Comprobante" onclick="cargarComprobante(${respuesta.id_prospecto})"><i class="fas fa-file-image"></i></a>`;
                        }

                        botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar Solicitud" onClick="eliminarProspecto(${respuesta.id_prospecto})"><i class="fas fa-times"></i></a>`;

                        tablaProspectos.row.add([
                            respuesta.id_prospecto, respuesta.prospecto_cedula, respuesta.prospecto_nombre, respuesta.prospecto_numero_contacto, respuesta.ubicacion, respuesta.asesor_nombre, `<span class="label label-${colorProspecto}">${respuesta.estado_texto}</span>`, respuesta.fecha_registro, `${respuesta.lista_plataformas}`, `<div class="jsgrid-align-center">${botonesTabla}</div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_prospecto);

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


        function reproLlamada(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_pro_llamada"
            };

            $.ajax({
                data: parameters,
                url: 'ajax/validacionesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        $("#pickup_date").val(respuesta.fecha_llamada);
                        $("#pickup_start").val(respuesta.hora_llamada);
                        $("#estado_pendiente_por_llamar").val(respuesta.id_estado_recordatorio);
                        $("#estado_pendiente_por_llamar").trigger('change');
                        $("#id_prospecto_pdte").val(idProspecto);
                        $("#action_prospecto_pdte").val('pdte_llamar_update');
                        $("#pdte-llamar-modal").modal("show");

                    } else if (respuesta.response == "error" || respuesta.num_rows == 0) {

                        $("#pickup_date").val('');
                        $("#pickup_start").val('');
                        $("#estado_pendiente_por_llamar").val('placeholder');
                        $("#estado_pendiente_por_llamar").trigger('change');
                        $("#id_prospecto_pdte").val(idProspecto);
                        $("#action_prospecto_pdte").val('pdte_llamar_insert');
                        $("#pdte-llamar-modal").modal("show");



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

        $("#pdtePorLlamarForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("pdtePorLlamarForm"));

            const action = document.querySelector("#action_prospecto_pdte").value;

            updatePdteLLamarDB(formData);

        });

        function updatePdteLLamarDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/validacionesAjax.php', true);
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
                            title: 'Estado actualizado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });
                        traerPlataformas(respuesta.id_prospecto);
                        $("#pdte-llamar-modal").modal("hide");

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
            }

            // send dates
            xhr.send(dates)

        }

        $("#pdtePorvalidarForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("pdtePorvalidarForm"));

            const action = document.querySelector("#action_prospecto_pdte").value;

            updatePdteValidarDB(formData);

        });

        function updatePdteValidarDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos3Ajax.php', true);
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
                            title: 'Estado actualizado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });

                        traerPlataformas(respuesta.id_prospecto);

                        $("#pdte-validar-modal").modal("hide");

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
            }

            // send dates
            xhr.send(dates)

        }

        function cargarComprobante(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_pro_comprobante"
            };

            $.ajax({
                data: parameters,
                url: 'ajax/prospectos3Ajax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    if (respuesta.response == "success") {

                        $("#subir-imgenes2-modal").modal("hide");

                        $("#imagen_titulo").html('Comprobante de pago para el despacho');

                        $('.dropify-clear').click();

                        $("#id_prospecto_imagen").val(idProspecto);
                        $("#tipo_img").val('comprobante');
                        $("#action_cargar_imagen").val('cargar_comprobante');
                        $("#cargar-imagen-modal").modal("show");

                    } else if (respuesta.response == "cargado") {

                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Imagen ya cargada',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 4000
                        });

                        traerPlataformas(respuesta.id_prospecto);

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

        function eliminarProspecto(idProspecto) {

            Swal.fire({

                title: 'Estas seguro?',
                text: "La información del prospecto se perdera y deberas crearla nuevamente!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    $("#observacion_cancelacion").val('');
                    $("#id_prospecto_cancelacion").val(idProspecto);
                    $("#action_prospecto_cancelacion").val('delete_prospecto');
                    $("#detalles-cancelar-modal").modal("show");

                } else {

                    return null;

                }

            });

        }

        $("#detallesCancelarForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("detallesCancelarForm"));

            deleteProspectoDB(formData);

        });

        function deleteProspectoDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/prospectos3Ajax.php', true);
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
                            title: 'Estado actualizado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });

                        tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

                        $("#detalles-cancelar-modal").modal("hide");

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
            }

            // send dates
            xhr.send(dates)
        }
    </script>

<?
} else {

    include '401error.php';
}

?>