<?

if (profile(43, $id_usuario) == 1) {

    $logistico = profile(44, $id_usuario);

    $query_filtro = "";
    $subtitulo = "Todos";

    if (isset($_GET['filtro'])) {

        $filtro = $_GET['filtro'];

        if ($filtro == "pl") {

            $id_estado_prospecto = 7;
            $subtitulo = "Pdte. Llamar";
        } else if ($filtro == "pv") {

            $id_estado_prospecto = 12;
            $subtitulo = "Pdte. Validar";
        } else if ($filtro == "pc") {

            $id_estado_prospecto = 4;
            $subtitulo = "Pdte. Comprobante";
        } else if ($filtro == "pe") {

            $id_estado_prospecto = 3;
            $subtitulo = "Pdte. Entrega";
        } else if ($filtro == "d") {

            $id_estado_prospecto = 5;
            $subtitulo = "Despachado";
        }

        $query_filtro = " AND prospectos.id_estado_prospecto = $id_estado_prospecto";
    }

?>

    <style>
        #textarea {
            -moz-appearance: textfield-multiline;
            -webkit-appearance: textarea;
            border: 1px solid gray;
            font: medium -moz-fixed;
            font: -webkit-small-control;
            height: 400px;
            overflow: auto;
            padding: 2px;
            resize: both;
            width: 400px;
            text-transform: uppercase;
        }
    </style>

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
                            <!--<button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="addProspecto()"><?= ucwords('Crear Prospecto') ?></button>-->
                            <a href="?page=validaciones" class="btn waves-effect waves-light btn-sm btn-info" style="float: right; margin-right: 5px;">Todos</a>
                            <a href="?page=validaciones&filtro=pl" class="btn waves-effect waves-light btn-sm btn-info" style="float: right; margin-right: 5px;">Pdte. Llamar</a>
                            <a href="?page=validaciones&filtro=pv" class="btn waves-effect waves-light btn-sm btn-info" style="float: right; margin-right: 5px;">Pdte. Validar</a>
                            <a href="?page=validaciones&filtro=pc" class="btn waves-effect waves-light btn-sm btn-info" style="float: right; margin-right: 5px;">Pdte. Comprobante</a>
                            <a href="?page=validaciones&filtro=pe" class="btn waves-effect waves-light btn-sm btn-info" style="float: right; margin-right: 5px;">Pdte. Entregar</a>
                            <a href="?page=validaciones&filtro=d" class="btn waves-effect waves-light btn-sm btn-info" style="float: right; margin-right: 5px;">Despachado</a>
                            <h4 class="card-title">Validaciones&nbsp;<?= ucwords($subtitulo) ?></h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableProspectos" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th><?= ucwords('Id') ?></th>
                                            <th><?= ucwords('Cedula') ?></th>
                                            <th><?= ucwords('Prospecto') ?></th>
                                            <th><?= ucwords('Contacto') ?></th>
                                            <th><?= ucwords('Ubicación') ?></th>
                                            <th><?= ucwords('Asesor') ?></th>
                                            <th><?= ucwords('Validador') ?></th>
                                            <th><?= ucwords('Estado') ?></th>
                                            <th><?= ucwords('Fecha y Hora') ?></th>
                                            <th><?= ucwords('Resultados') ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, ciudades.ciudad, departamentos.departamento, estados_prospectos.estado_prospecto, prospectos.resultado_dc_prospecto, prospectos.id_estado_prospecto, CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS nombre_responsable, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y %H:%i:%s') AS fecha_hora_registro, prospectos.id_usuario_validador FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id_usuario_validador <> 0 AND (prospectos.id_estado_prospecto = 12 OR prospectos.id_estado_prospecto = 7 OR prospectos.id_estado_prospecto = 3 OR prospectos.id_estado_prospecto = 4) AND prospectos.del = 0 $query_filtro";

                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_prospecto =  $row1['id_prospecto'];
                                            $prospecto_cedula =  $row1['prospecto_cedula'];
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
                                            $comprobante = 0;
                                            if ($id_estado_prospecto == 11 || $id_estado_prospecto == 7 || $id_estado_prospecto == 4) {
                                                $color = "info";
                                            } else if ($id_estado_prospecto == 12) {
                                                $color = "warning";
                                            } else if ($id_estado_prospecto == 3) {
                                                $color = "success";
                                                $validate_comprobante = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagen_nombre_archivo = 'comprobante' AND del = 0");
                                                if ($validate_comprobante > 0) {
                                                    $comprobante = 1;
                                                }
                                            }
                                            $resultado_dc = $row1['resultado_dc_prospecto'];
                                            $resultado = "N/A";
                                            if ($resultado_dc == 1) {
                                                $resultado = "APROBADO";
                                            } else if ($resultado_dc == 2) {
                                                $resultado = "RECHAZADO";
                                            }

                                            $nombre_responsable = $row1['nombre_responsable'];

                                            $fecha_hora_registro = $row1['fecha_hora_registro'];

                                            $id_usuario_validador = $row1['id_usuario_validador'];

                                            $nombre_validador = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS nombre_validador FROM usuarios WHERE id = $id_usuario_validador");

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
                                                <td id="nombre_prospecto_<?= $id_prospecto ?>"><?= ucwords($prospecto_info) ?></td>
                                                <td id="contacto_prospecto_<?= $id_prospecto ?>"><?= $contacto ?></td>
                                                <td id="ubicacion_prospecto_<?= $id_prospecto ?>"><?= ucwords($ubicacion) ?></td>
                                                <td><?= ucwords($nombre_responsable) ?></td>
                                                <td><?= ucwords($nombre_validador) ?></td>
                                                <td id="estado_<?= $id_prospecto ?>"><span class="label label-<?= $color ?>"><?= ucwords($estado) ?></span><?= $estado_mostrar2 ?></td>
                                                <td><?= $fecha_hora_registro ?></td>
                                                <td>
                                                    <div id="lista-plataformas-<?= $id_prospecto ?>"><?= $resultados_plataformas ?></div>
                                                </td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <div id="botones_solicitudes_<?= $id_prospecto ?>">
                                                        <? if ($id_estado_prospecto == 12 || $id_estado_prospecto == 7 || $id_estado_prospecto == 4) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Información" onclick="verInformacion(<?= $id_prospecto ?>)"><i class="mdi mdi-pencil"></i></a>
                                                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Colocar Resultado" onclick="validarCliente(<?= $id_prospecto ?>)"><i class="fas fa-money-check"></i></a>
                                                            <? if ($id_estado_prospecto != 7) {
                                                            ?>
                                                                <a href="javascript:void(0)" class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte. por llamar" onclick="PdteLlamar(<?= $id_prospecto ?>)"><i class="fas fa-phone-volume"></i></a>
                                                            <? }
                                                            if ($id_estado_prospecto == 7) {
                                                            ?>
                                                                <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reprogramar LLamada" onclick="reproLlamada(<?= $id_prospecto ?>)"><i class="fas fa-phone-volume"></i></a>
                                                            <? }
                                                            if ($id_estado_prospecto == 7 || $id_estado_prospecto == 12) { ?>
                                                                <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte. por Entregar" onclick="PdteEntregar(<?= $id_prospecto ?>)"><i class="fas fa-truck"></i></a>

                                                            <? }

                                                            if ($id_estado_prospecto == 12) { ?>
                                                                <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Transferir Val." onclick="transferVal(<?= $id_prospecto ?>)"><i class="fas fa-arrow-right"></i></a>
                                                            <? }
                                                        }
                                                        if ($id_estado_prospecto == 3) {
                                                            ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Imagenes" onclick="cargarImagenes2(<?= $id_prospecto ?>)"><i class="fas fa-file-signature"></i></a>
                                                            <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Imprimir Baucher" onclick="ImprimirBaucher(<?= $id_prospecto ?>)"><i class="fas fa-print"></i></a>
                                                            <? if ($logistico == 1) { ?>
                                                                <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Completar Entrega" onclick="completarEntrega(<?= $id_prospecto ?>)"><i class="fas fa-route"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pasar a Despachado" onclick="despachado(<?= $id_prospecto ?>)"><i class="fas fa-truck"></i></a>
                                                            <? } ?>
                                                        <? }
                                                        if ($id_estado_prospecto == 4) { ?>
                                                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Comprobante" onclick="cargarComprobante(<?= $id_prospecto ?>)"><i class="fas fa-file-image"></i></a>
                                                        <? }
                                                        ?>
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar Solicitud" onClick="eliminarProspecto(<?= $id_prospecto ?>)"><i class="fas fa-times"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <input type="hidden" id="id_usuario_validaciones" value="<?= $id_usuario ?>">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Color del dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="color_dispositivo" id="color_dispositivo" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" selected disabled>Seleccionar Color</option>
                                            <?php
                                            $query = "SELECT DISTINCT colores_productos.color_desc, colores_productos.id AS id_color from colores_productos order by color_desc";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                                $id_color = $row['id_color'];
                                                $color_desc = $row['color_desc'];
                                            ?>
                                                <option value="<?= $id_color ?>"><?= $color_desc ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Asesor:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="asesor_nombre" id="asesor_nombre" placeholder="Escribe el nombre del asesor" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" required readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40">
                                    <table id="dataTableImagenes" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th><?= ucwords('IMAGENES') ?></th>
                                                <th><?= ucwords('ACCIONES') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>CEDULA</td>
                                                <td id="botones_imagenes_1"></td>
                                            </tr>
                                            <tr>
                                                <td>ATRAS</td>
                                                <td id="botones_imagenes_2"></td>
                                            </tr>
                                            <tr>
                                                <td>SELFIE</td>
                                                <td id="botones_imagenes_3"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12" id="boton-descargar">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_editar" value="">
                        <input type="hidden" name="action" id="action_prospecto_editar" value="">
                        <input type="hidden" name="from" value="validaciones">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- colocar validacion modal -->
    <div id="colocar-validacion-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Colocar Resultado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="validarClienteForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h4>RESULTADOS PLATAFORMA</h4>
                                <div class="table-responsive m-t-40">
                                    <table id="dataTablePlataformas" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th><?= ucwords('PLATAFORMA') ?></th>
                                                <th><?= ucwords('RESULTADO') ?></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?
                                            $query = "SELECT id AS id_plataforma, nombre_plataforma FROM plataformas_credito WHERE plataformas_credito.del = 0";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                                $id_plataforma = $row['id_plataforma'];
                                                $nombre_plataforma = $row['nombre_plataforma'];
                                            ?>
                                                <tr>
                                                    <td><?= strtoupper($nombre_plataforma) ?></td>
                                                    <td id="plataforma_<?= $id_plataforma ?>"></td>
                                                    <td id="botones_plataforma_<?= $id_plataforma ?>"></td>
                                                </tr>
                                            <? } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_plataforma" value="">
                        <input type="hidden" name="action" id="action_prospecto_resultados" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- colocar resultado modal -->
    <div id="colocar-resultado-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Colocar Resultado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="colocarResultadoForm" method="post">
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
                                    <label>Resultado:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="resultado_plataforma" id="resultado_plataforma" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" onchange="showComprobante(this.value)" required>
                                            <option value="placeholder" selected disabled>Seleccionar Resultado</option>
                                            <?php
                                            $query = "SELECT estados_validaciones.id AS id_estado_validacion, estados_validaciones.estado_validacion_nombre FROM estados_validaciones WHERE estados_validaciones.del = 0";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                                $id_estado_validacion = $row['id_estado_validacion'];
                                                $estado_validacion_nombre = $row['estado_validacion_nombre'];
                                            ?>
                                                <option value="<?= $id_estado_validacion ?>"><?= ucwords(str_replace('_', '', $estado_validacion_nombre)) ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="cargar_comprobante_resultado">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="cargar_comprobante">Comprobante de pago</label>
                                        <input type="file" name="cargar_comprobante" id="cargar_comprobante" class="dropify" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" id="observacion_resultado_otro">
                                <div class="form-group">
                                    <label for="plantilla_area">Observación:</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="observacion_otro" id="observacion_otro" rows="10" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_resultado" value="">
                        <input type="hidden" name="id_plataforma" id="id_plataforma_resultado" value="">
                        <input type="hidden" name="action" id="action_prospecto_resultado" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- observaciones -->
    <div id="ver-observaciones-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ver Observaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_comentario">Observaciones:</label>
                                    <div class="input-group">
                                        <div id="mitarjetacontenido" class="tarjetacontenido">
                                            <div id="mitextotarjeta" class="tarjeta-texto kalamtexto"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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


    <!-- subir imagenes modal -->
    <div id="subir-imgenes2-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cargar Imagenes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="subirImagenes2Form" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" id="seccion_tabla_imagenes2">

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


    <div id="asignar_domi_modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="asignar_domi_titulo">Completar Entrega</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="asignar_domi_form" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="imei_referencia">Imei del dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="imei_referencia" name="imei_referencia" class="form-control" placeholder="Imei de la referencia a entregar" autocomplete="ÑÖcompletes" maxlength="20" required onkeypress="return validaNumerics(event)">
                                    </div>
                                    <!--<select class="form-control select2Class" name="imei_referencia" id="imei_referencia" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                        <option value="placeholder" disabled>Seleccionar Imei</option>
                                    </select>-->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Medio de envio:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <fieldset class="controls">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="1" name="medio_envio" id="medio_domicilio" class="custom-control-input" required onchange="changeMedioEnvio(this.value)">
                                                <label class="custom-control-label" for="medio_domicilio">Domicilio</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
                                        <fieldset>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="2" name="medio_envio" id="medio_servientrega" class="custom-control-input" onchange="changeMedioEnvio(this.value)">
                                                <label class="custom-control-label" for="medio_servientrega">Servientrega</label>
                                            </div>
                                        </fieldset>
                                        &nbsp; &nbsp;
                                        <fieldset>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" value="3" name="medio_envio" id="medio_en_tienda" class="custom-control-input" onchange="changeMedioEnvio(this.value)">
                                                <label class="custom-control-label" for="medio_en_tienda">Recoger En tienda</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="zone-domiciliario">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Domiciliario:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="domiciliario_solicitud" id="domiciliario_solicitud" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Domiciliario</option>
                                            <?php
                                            $query = "select id, CONCAT(nombre, ' ', apellidos) AS domiciliario from usuarios WHERE usuarios.del = 0 AND usuarios.domiciliario = 1 order by domiciliario";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['domiciliario'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="zone-servientrega">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="guia_servientrega">Guia Servientrega:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="guia_servientrega" name="guia_servientrega" class="form-control" placeholder="Guia de servientrega" autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="zone-tienda">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Responsable de la entrega:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="responsable_entrega" id="responsable_entrega" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Responsable</option>
                                            <?php
                                            $query = "select id, CONCAT(nombre, ' ', apellidos) AS reponsable from usuarios WHERE usuarios.del = 0 AND usuarios.cliente_gane = 0 order by reponsable";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['reponsable'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_despachar" value="">
                        <input type="hidden" name="action" id="action_domi" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal_seleccionar_plataforma" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Seleccionar plataforma</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="seleccionar_plataforma_form" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select class="form-control select2Class" name="plataforma_despacho" id="plataforma_despacho" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                        <option value="placeholder" disabled>Seleccionar Plataforma</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_plataforma_despacho" value="">
                        <input type="hidden" name="action" value="select_plataforma_despacho">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <!-- plantilla modal -->
    <div id="plantilla-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ver plantilla.</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="plantillaForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Plantilla:</label>
                                    <div class="input-group">
                                        <div id="textarea" onkeyup="javascript:this.value=this.value.toUpperCase();" contenteditable></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="inputCopy" onclick="copyClipboard()" onmouseout="outFunc()"><i class="fas fa-clipboard"></i>&nbsp;<span class="tooltiptext" id="myTooltip">Copiar plantilla</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- validadores modal -->
    <div id="validadores-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Transferir Validacion.</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="transferValForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Validadores:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="validadores" id="validadores" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" selected disabled>Seleccionar Validador</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <input type="hidden" name="id_prospecto" id="prospecto_transferir">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Transferir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            var config = {
                type: 'image',
                callbacks: {}
            };

            var cssHeight = '800px'; // Add some conditions here

            config.callbacks.open = function() {
                $(this.container).find('.mfp-content').css('height', cssHeight);
            };

            $('.image-complete').magnificPopup(config);

        });

        function verInformacion(idProspecto) {

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

                    botones1 = $("#botones_imagenes_1");
                    botones2 = $("#botones_imagenes_2");
                    botones3 = $("#botones_imagenes_3");
                    botones1.empty();
                    botones2.empty();
                    botones3.empty();

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
                    $("#asesor_nombre").val(respuesta[0].nombre_usuario + ' ' + respuesta[0].apellido_usuario);
                    $("#color_dispositivo").val('placeholder');
                    if (respuesta[0].id_color !== null || respuesta[0].id_color !== 0) {
                        $("#color_dispositivo").val(respuesta[0].id_color);
                    }
                    $("#color_dispositivo").trigger("change");

                    botones1.html(`<a href="./documents/prospects/${idProspecto}/frontal.jpg" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${idProspecto}, 'frontal')"><i class="fas fa-redo"></i></a>`);
                    botones2.html(`<a href="./documents/prospects/${idProspecto}/atras.jpg" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${idProspecto}, 'atras')"><i class="fas fa-redo"></i></a>`);
                    botones3.html(`<a href="./documents/prospects/${idProspecto}/selfie.jpg" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${idProspecto}, 'selfie')"><i class="fas fa-redo"></i></a>`);

                    var config = {
                        type: 'image',
                        callbacks: {}
                    };

                    var cssHeight = '800px'; // Add some conditions here

                    config.callbacks.open = function() {
                        $(this.container).find('.mfp-content').css('height', cssHeight);
                    };

                    $('.image-complete').magnificPopup(config);

                    $("#boton-descargar").html(`<button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left; margin-top: 20px; margin-left: 20px;" onclick="descargarImagenes(${idProspecto})">Descargar Todas</button><button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: left; margin-top: 20px; margin-left: 20px;" onclick="verPlantilla(${idProspecto})">Ver Plantilla</button>`);

                    $("#action_prospecto_editar").val('completar_prospecto');
                    $("#update-prospecto-modal").modal("show");

                }

            });

        }

        function cargarImagenes2(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "validate_imagenes"
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

                        var botones_comprobante = '',
                            botones_contrato = '',
                            comprobante_space = '';

                        $("#seccion_tabla_imagenes2").html(``);

                        botones_comprobante = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Comprobante" onclick="cargarComprobante(${respuesta.id_prospecto})"><i class="fas fa-file-image"></i></a>`;

                        botones_contrato = `<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Contrato" onclick="cargarContrato(${respuesta.id_prospecto})"><i class="fas fa-file-signature"></i></i></a>`;

                        if (respuesta.validate_comprobante == 1) {

                            botones_comprobante = `<a href="${respuesta.route_comprobante}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'comprobante')"><i class="fas fa-redo"></i></a>`;
                        }

                        if (respuesta.validate_contrato == 1) {

                            botones_contrato = `<a href="${respuesta.route_contrato}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Imagen" onclick="cambiarImagen(${respuesta.id_prospecto}, 'contrato')"><i class="fas fa-redo"></i></a>`;
                        }

                        if (respuesta.id_ciudad_prospecto != 1) {

                            comprobante_space = `<div class="col-md-12">
                                                <div class="form-group">
                                                        <label for="">Baucher Despacho</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            ${botones_comprobante}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
                        }

                        $("#seccion_tabla_imagenes2").html(`<div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            ${comprobante_space}
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Contrato</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                        ${botones_contrato}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`);

                        var config = {
                            type: 'image',
                            callbacks: {}
                        };

                        var cssHeight = '800px'; // Add some conditions here

                        config.callbacks.open = function() {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                        $("#subir-imgenes2-modal").modal("show");

                    }
                }
            });

        }

        function cambiarImagen(idProspecto, tipoImg) {

            $("#subir-imgenes2-modal").modal("hide");

            if (tipoImg == 'frontal') {

                $("#imagen_titulo").html('Imagen Parte de adelante de la cedula');

            } else if (tipoImg == 'atras') {

                $("#imagen_titulo").html('Imagen Parte de atras de la cedula');

            } else if (tipoImg == 'selfie') {

                $("#imagen_titulo").html('Imagen Tipo selfie del cliente con la cedula');

            } else if (tipoImg == 'comprobante') {

                $("#imagen_titulo").html('Comprobante de Despacho');

            } else if (tipoImg == 'contrato') {

                $("#imagen_titulo").html('Contrato');

            }

            $('.dropify-clear').click();

            $("#id_prospecto_imagen").val(idProspecto);
            $("#tipo_img").val(tipoImg);
            $("#action_cargar_imagen").val('actualizar_imagen');
            $("#cargar-imagen-modal").modal("show");

        }

        function cargarComprobante(idProspecto) {

            $("#subir-imgenes2-modal").modal("hide");

            $("#imagen_titulo").html('Comprobante de pago para el despacho');

            $('.dropify-clear').click();

            $("#id_prospecto_imagen").val(idProspecto);
            $("#tipo_img").val('comprobante');
            $("#action_cargar_imagen").val('cargar_comprobante');
            $("#cargar-imagen-modal").modal("show");

        }

        function cargarContrato(idProspecto) {

            $("#subir-imgenes2-modal").modal("hide");

            $("#imagen_titulo").html('Contrato de credito');

            $('.dropify-clear').click();

            $("#id_prospecto_imagen").val(idProspecto);
            $("#tipo_img").val('contrato');
            $("#action_cargar_imagen").val('cargar_contrato');
            $("#cargar-imagen-modal").modal("show");

        }

        $("#subirImagenForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("subirImagenForm"));

            const action = document.querySelector("#action_cargar_imagen").value;

            if (action == "actualizar_imagen") {

                updateImagenDB(formData);

            } else if (action == "cargar_comprobante") {

                insertBaucherDB(formData);

            } else if (action == "cargar_contrato") {

                insertContratoDB(formData);

            }

        });

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

        function insertContratoDB(dates) {

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
                            text: 'Contrato cargado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });


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

        function selectInicial(idReferencia) {

            if (idReferencia !== "placeholder") {

                var idProspecto = $("#id_prospecto_editar").val();

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
                            title: 'Prospecto Actualizado Correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        traerPlataformas(respuesta.id_prospecto);

                        $("#update-prospecto-modal").modal("hide");

                    } else if (respuesta.response == "numero_incorrecto") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Numero de contacto incorrecto',
                            showConfirmButton: false,
                            timer: 3000

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

                    } else if (respuesta.response == "falta_color") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Elige el color del dispositivo',
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

        function validarCliente(idProspecto) {
            var parameters = {
                'id_prospecto': idProspecto,
                'action': 'select_resultados'
            };

            $.ajax({

                data: parameters,
                url: 'ajax/validacionesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    var respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var adelantos = $("#plataforma_1"),
                        creditek = $("#plataforma_2"),
                        crediminuto = $("#plataforma_3"),
                        botonesAdelantos = $("#botones_plataforma_1"),
                        botonesCreditek = $("#botones_plataforma_2"),
                        botonesCrediminuto = $("#botones_plataforma_3"),
                        botonesAdicionales = '';
                    /*botonesAdicionales = '<a href="${respuesta.route}" class="btn btn-outline-warning waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>'*/

                    const total_plataformas = respuesta[0].length,
                        respuesta_default = '<span class="label label-info">N/A</span>',
                        respuesta_aprobado = '<span class="label label-success">APROBADO</span>',
                        respuesta_rechazado = '<span class="label label-danger">RECHAZADO</span>';
                    respuesta_cred_activo = '<span class="label label-info">CREDITO ACTIVO</span>';
                    respuesta_otro = '<span class="label label-warning">OTRO</span>';

                    adelantos.html(respuesta_default);
                    creditek.html(respuesta_default);
                    crediminuto.html(respuesta_default);
                    botonesAdelantos.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Colocar Resultado" onclick="colocarResultado(${idProspecto}, 1)"><i class="fas fa-check"></i></a>`);
                    botonesCreditek.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Colocar Resultado" onclick="colocarResultado(${idProspecto}, 2)"><i class="fas fa-check"></i></a>`);
                    botonesCrediminuto.html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Colocar Resultado" onclick="colocarResultado(${idProspecto}, 3)"><i class="fas fa-check"></i></a>`);

                    if (total_plataformas != 0) {

                        respuesta[0].forEach(function(plataformas, index) {
                            botonesAdicionales = `<a href="./documents/prospects/${idProspecto}/resultado/${plataformas.nombre_plataforma}.jpg" class="btn btn-outline-success waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>`;
                            //console.log(plataformas);
                            if (plataformas.resultado_dc == 1) {
                                $("#plataforma_" + plataformas.id_plataforma).html(respuesta_aprobado);
                            } else if (plataformas.resultado_dc == 2) {
                                $("#plataforma_" + plataformas.id_plataforma).html(respuesta_rechazado);
                            } else if (plataformas.resultado_dc == 3) {
                                $("#plataforma_" + plataformas.id_plataforma).html(respuesta_cred_activo);
                            } else if (plataformas.resultado_dc == 4) {
                                $("#plataforma_" + plataformas.id_plataforma).html(respuesta_otro);
                                botonesAdicionales = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Observación" onclick="verObservacion(${idProspecto}, ${plataformas.id_plataforma})"><i class="fas fa-comment"></i></a>`;
                            }

                            $("#botones_plataforma_" + plataformas.id_plataforma).html(`<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Remover validación" onclick="cambiarResultado(${idProspecto}, ${plataformas.id_plataforma})"><i class="fas fa-arrow-left"></i></a>${botonesAdicionales}`);

                            botonesAdicionales = '';
                        });

                        var config = {
                            type: 'image',
                            callbacks: {}
                        };

                        var cssHeight = '800px'; // Add some conditions here

                        config.callbacks.open = function() {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                    }

                    $("#colocar-validacion-modal").modal("show");

                }

            });

        }


        function verObservacion(idProspecto, idPlataforma) {

            var parameters = {
                "id_prospecto": idProspecto,
                "id_plataforma": idPlataforma,
                "action": "select_observacion"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/validacionesAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var observacionProspecto = '';

                    if (respuesta.observacion !== null) {
                        observacionProspecto = respuesta.observacion;
                    }

                    $("#mitextotarjeta").html(observacionProspecto.replace(/\n/g, "<br>"));
                    //$("#boton-add-observacion").attr('onClick', `editarObservacion(${respuesta.id_prospecto});`);
                    //$("#observacion_create_prospecto").value(respuesta.observacion);
                    $("#ver-observaciones-modal").modal("show");

                }
            });

        }

        function colocarResultado(idProspecto, idPlataforma) {

            $("#resultado_plataforma").val('placeholder');
            $("#resultado_plataforma").trigger('change');
            $("#id_prospecto_resultado").val(idProspecto);
            $("#id_plataforma_resultado").val(idPlataforma);
            $("#cargar_comprobante_resultado").hide();
            $("#observacion_resultado_otro").hide();
            $("#action_prospecto_resultado").val('cargar_resultado');
            $("#colocar-resultado-modal").modal("show");

        }


        function showComprobante(idResultado) {
            if (idResultado != "placeholder") {
                if (idResultado == 1 || idResultado == 2 || idResultado == 3) {
                    $('.dropify-clear').click();
                    $("#observacion_resultado_otro").hide();
                    $("#cargar_comprobante_resultado").show();
                } else if (idResultado == 4) {
                    $("#observacion_otro").val('');
                    $("#cargar_comprobante_resultado").hide();
                    $("#observacion_resultado_otro").show();
                }
            }
        }


        $("#colocarResultadoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("colocarResultadoForm"));

            const action = document.querySelector("#action_prospecto_resultado").value;

            insertResultadoDB(formData);

        });

        function traerPlataformas(idProspecto) {

            var idUsuario = $("#id_usuario_validaciones").val();

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

                    console.log(respuesta.lista_plataformas);

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

                    botonesTabla = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Información" onclick="verInformacion(${respuesta.id_prospecto})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Colocar Resultado" onclick="validarCliente(${respuesta.id_prospecto})"><i class="fas fa-money-check"></i></a>`;

                    if (respuesta.id_estado_prospecto == 7) {
                        botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reprogramar LLamada" onclick="reproLlamada(${respuesta.id_prospecto})"><i class="fas fa-phone-volume"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte. por Entregar" onclick="PdteEntregar(${respuesta.id_prospecto})"><i class="fas fa-truck"></i></a>`;
                    } else if (respuesta.id_estado_prospecto == 12) {
                        botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte. por llamar" onclick="PdteLlamar(${respuesta.id_prospecto})"><i class="fas fa-phone-volume"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte. por Entregar" onclick="PdteEntregar(${respuesta.id_prospecto})"><i class="fas fa-truck"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Transferir Val." onclick="transferVal(${respuesta.id_prospecto})"><i class="fas fa-arrow-right"></i></a>`;
                    } else if (respuesta.id_estado_prospecto == 3) {

                        botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Completar Entrega" onclick="completarEntrega(${respuesta.id_prospecto})"><i class="fas fa-route"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Imprimir Baucher" onclick="ImprimirBaucher(${respuesta.id_prospecto})"><i class="fas fa-print"></i></a>`;

                        if (respuesta.logisitico == 1) {
                            botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Imagenes" onclick="cargarImagenes2(<?= $id_prospecto ?>)"><i class="fas fa-file-signature"></i></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pasar a Despachado" onclick="despachado(${respuesta.id_prospecto})"><i class="fas fa-truck"></i></a>`;
                        }

                    } else if (respuesta.id_estado_prospecto == 4) {
                        botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="cargar Comprobante" onclick="cargarComprobante(${respuesta.id_prospecto})"><i class="fas fa-file-image"></i></a>`;
                    }

                    botonesTabla += `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar Solicitud" onClick="eliminarProspecto(${respuesta.id_prospecto})"><i class="fas fa-times"></i></a>`;

                    tablaProspectos.row.add([
                        respuesta.id_prospecto, respuesta.prospecto_cedula, respuesta.prospecto_nombre, respuesta.prospecto_numero_contacto, respuesta.ubicacion, respuesta.asesor_nombre, respuesta.validador_nombre, `<span class="label label-${colorProspecto}">${respuesta.estado_texto}</span>`, respuesta.fecha_registro, `${respuesta.lista_plataformas}`, `<div class="jsgrid-align-center">${botonesTabla}</div>`
                    ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_prospecto);


                }

            });

        }

        function insertResultadoDB(dates) {

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
                            title: 'Resultado actualizado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });

                        var resultadoPla = '',
                            respuesta_default = '<span class="label label-info">N/A</span>',
                            respuesta_aprobado = '<span class="label label-success">APROBADO</span>',
                            respuesta_rechazado = '<span class="label label-danger">RECHAZADO</span>',
                            botonesAdicionales = `<a href="./documents/prospects/${respuesta.id_prospecto}/resultado/${respuesta.nombre_plataforma}.jpg" class="btn btn-outline-success waves-effect waves-light image-complete" data-toggle="tooltip" data-placement="top" title="Ver Imagen"><i class="far fa-eye"></i></a>`;



                        if (respuesta.resultado == 1) {

                            resultadoPla = `<span class="label label-success">APROBADO</span>`;

                        } else if (respuesta.resultado == 2) {

                            resultadoPla = `<span class="label label-danger">RECHAZADO</span>`;

                        } else if (respuesta.resultado == 3) {

                            resultadoPla = `<span class="label label-info">CONTRATO ACTIVO</span>`;

                        } else if (respuesta.resultado == 4) {

                            resultadoPla = `<span class="label label-info">OTRO</span>`;
                            botonesAdicionales = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Observación" onclick="verObservacion(${respuesta.id_prospecto}, ${respuesta.id_plataforma})"><i class="fas fa-comment"></i></a>`;

                        }


                        $("#plataforma_" + respuesta.id_plataforma).empty();
                        $("#plataforma_" + respuesta.id_plataforma).html(resultadoPla);

                        $("#botones_plataforma_" + respuesta.id_plataforma).empty();
                        $("#botones_plataforma_" + respuesta.id_plataforma).html(`<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Remover validación" onclick="cambiarResultado(${respuesta.id_prospecto}, ${respuesta.id_plataforma})"><i class="fas fa-arrow-left"></i></a>${botonesAdicionales}`);

                        var config = {
                            type: 'image',
                            callbacks: {}
                        };

                        var cssHeight = '800px'; // Add some conditions here

                        config.callbacks.open = function() {
                            $(this.container).find('.mfp-content').css('height', cssHeight);
                        };

                        $('.image-complete').magnificPopup(config);

                        botonesAdicionales = '';

                        traerPlataformas(respuesta.id_prospecto);

                        $("#colocar-resultado-modal").modal("hide");

                    } else if (respuesta.response == "tipo_incorrecto") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Formato de imagen incorrecto',
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

        function cambiarResultado(idProspecto, idPlataforma) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Se eliminara el resultado, comprobante o la observación!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "id_plataforma": idPlataforma,
                        "action": "eliminar_resultado"
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

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Resultado eliminado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 4000

                                });

                                $("#plataforma_" + respuesta.id_plataforma).empty();
                                $("#plataforma_" + respuesta.id_plataforma).html(`<span class="label label-info">N/A</span>`);

                                $("#botones_plataforma_" + respuesta.id_plataforma).empty();
                                $("#botones_plataforma_" + respuesta.id_plataforma).html(`<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Colocar Resultado" onclick="colocarResultado(${idProspecto}, ${idPlataforma})"><i class="fas fa-check"></i></a>`);

                                traerPlataformas(idProspecto);

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

        function PdteLlamar(idProspecto) {
            $("#pickup_date").val('');
            $("#pickup_start").val('');
            $("#estado_pendiente_por_llamar").val('placeholder');
            $("#estado_pendiente_por_llamar").trigger('change');
            $("#id_prospecto_pdte").val(idProspecto);
            $("#action_prospecto_pdte").val('pdte_llamar_insert');
            $("#pdte-llamar-modal").modal("show");
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

            if (action == "pdte_llamar_insert") {

                insertPdteLLamarDB(formData);

            } else {

                updatePdteLLamarDB(formData);

            }

        });

        function insertPdteLLamarDB(dates) {
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
                            title: 'Validación actualizada correctamente',
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

        function PdteEntregar(idProspecto) {

            Swal.fire({

                title: 'Estas seguro?',
                text: "Recuerda que la solicitud debe tener almenos un aprobado confirmado en una de las financieras!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "action": "validate_pdte_por_entregar"
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

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Estado actualizado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 4000

                                });

                                traerPlataformas(respuesta.id_prospecto);

                            } else if (respuesta.response == "varios_aprobados") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Tienes Varios aprobados',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                                $("#id_prospecto_plataforma_despacho").val(respuesta.id_prospecto);
                                $("#plataforma_despacho").val('placeholder');
                                $("#plataforma_despacho").trigger('change');

                                $("modal_seleccionar_plataforma").modal("show");

                            } else if (respuesta.response == "falta_aprobado") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Debes tener almenos un aprobado',
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

        function downloadZip(filename, ccCliente) {

            var element = document.createElement('a');
            //element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('href', filename);
            element.setAttribute('download', `imagenes-${ccCliente}.zip`);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);

        }


        function descargarImagenes(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "generar_zip"
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

                        downloadZip(respuesta.ruta, respuesta.cc_cliente);

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Descarga realizada correctamente',
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

        function verPlantilla(idProspecto) {
            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_plantilla"
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

                        $("#textarea").html(`<p>Asesor: ${respuesta.nombre_responsable}<br>
                        NOMBRE COMPLETO: ${respuesta.prospecto_nombre+' '+respuesta.prospecto_apellidos}<br>
                        CEDULA: ${respuesta.prospecto_cedula}<br>
                        REFERENCIA: ${respuesta.marca_producto + ' ' + respuesta.nombre_modelo + ' ' + respuesta.capacidad}<br>
                        INICIAL: ${respuesta.inicial_confirmada}<br>
                        PLAZO: ${respuesta.plazo_meses} meses<br>
                        COLOR: ${respuesta.color_desc}<br>
                        CORREO ELECTRONICO: ${respuesta.prospecto_email}<br>
                        DIRECCION EXACTA: ${respuesta.prospecto_direccion}<br>
                        BARRIO:<br>
                        CIUDAD: ${respuesta.ciudad}<br>
                        TELEFONO: ${respuesta.prospecto_numero_contacto}<br>
                        TELEFONO 2:</p>`);
                        $("#myTooltip").html('Copiar Plantilla');
                        $("#plantilla-modal").modal("show");

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

        function copyClipboard() {
            /*
            var content = document.getElementById('textarea').innerHTML;
            var tooltip = document.getElementById("myTooltip");
            navigator.clipboard.writeText(content)
                .then(() => {
                tooltip.innerHTML = "Ready!";
            })
                .catch(err => {
                console.log('Something went wrong', err);
            })
            */
            var aux = document.createElement("div");
            aux.setAttribute("contentEditable", true);
            aux.innerHTML = document.getElementById('textarea').innerHTML;
            aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)");
            document.body.appendChild(aux);
            aux.focus();
            document.execCommand("copy");
            document.body.removeChild(aux);
        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Texto copiado, adelante!";
        }

        function completarEntrega(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "completar_entrega"
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

                        $("#cliente_nombre").val('');
                        $("#dispositivo_nombre").val('');
                        $("#imei_referencia").val(respuesta.imei_dispositivo);
                        $("#medio_domicilio").prop('checked', false);
                        $("#medio_servientrega").prop('checked', false);
                        $("#medio_en_tienda").prop('checked', false);
                        $("#zone-domiciliario").hide();
                        $("#zone-servientrega").hide();
                        $("#zone-tienda").hide();
                        $("#domiciliario_solicitud").prop('required', false);
                        $("#domiciliario_solicitud").val('placeholder');
                        $("#domiciliario_solicitud").trigger('change');
                        $("#guia_servientrega").prop('required', false);
                        $("#responsable_entrega").prop('required', false);
                        $("#responsable_entrega").val('placeholder');
                        $("#responsable_entrega").trigger('change');
                        if (respuesta.id_medio_envio == 1) {
                            $("#medio_domicilio").prop('checked', true);
                            $("#zone-domiciliario").show();
                            $("#domiciliario_solicitud").prop('required', true);
                            $("#domiciliario_solicitud").val(respuesta.id_domiciliario);
                            $("#domiciliario_solicitud").trigger('change');
                        } else if (respuesta.id_medio_envio == 2) {
                            $("#medio_servientrega").prop('checked', true);
                            $("#zone-servientrega").show();
                            $("#guia_servientrega").prop('required', true);
                            $("#guia_servientrega").val(respuesta.guia_servientrega);
                        } else if (respuesta.id_medio_envio == 3) {
                            $("#medio_en_tienda").prop('checked', true);
                            $("#zone-tienda").show();
                            $("#responsable_entrega").prop('required', false);
                            $("#responsable_entrega").val(respuesta.id_responsable_tienda);
                            $("#responsable_entrega").trigger('change');
                        }
                        $("#id_prospecto_despachar").val(respuesta.id_prospecto);
                        $("#action_domi").val('insert_despacho');
                        $("#asignar_domi_modal").modal("show");

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

        function changeMedioEnvio(idMedio) {

            $("#zone-domiciliario").hide();
            $("#zone-servientrega").hide();
            $("#zone-tienda").hide();
            $("#domiciliario_solicitud").prop('required', false);
            $("#domiciliario_solicitud").val('placeholder');
            $("#domiciliario_solicitud").trigger('change');
            $("#guia_servientrega").prop('required', false);
            $("#responsable_entrega").prop('required', false);
            $("#responsable_entrega").val('placeholder');
            $("#responsable_entrega").trigger('change');

            if (idMedio == 1) {

                $("#zone-domiciliario").show();
                $("#domiciliario_solicitud").val('placeholder');
                $("#domiciliario_solicitud").trigger("change");
                $("#domiciliario_solicitud").prop('required', true);

            } else if (idMedio == 2) {

                $("#zone-servientrega").show();
                $("#guia_servientrega").prop('required', true);
                $("#guia_servientrega").val('');

            } else if (idMedio == 3) {

                $("#zone-tienda").show();
                $("#responsable_entrega").val('placeholder');
                $("#responsable_entrega").trigger("change");
                $("#responsable_entrega").prop('required', true);
            }

        }

        $("#asignar_domi_form").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("asignar_domi_form"));

            const action = document.querySelector("#action_domi").value;

            if (action == "insert_despacho") {

                insertMedioEnvioDB(formData);

            }

        });

        function insertMedioEnvioDB(dates) {

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
                            title: 'Información Actualizada Correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });

                        $("#asignar_domi_modal").modal("hide");


                    } else if (respuesta.response == "falta_domiciliario") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Selecciona un domiciliario',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "falta_responsable_tienda") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Selecciona el asesor que entrego el equipo',
                            showConfirmButton: false,
                            timer: 4000

                        });

                    } else if (respuesta.response == "falta_guia_servientrega") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Ingresa la guia de servicio de entrega',
                            showConfirmButton: false,
                            timer: 4000

                        });

                    } else if (respuesta.response == "falta_imei") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Ingresa el IMEI del equipo',
                            showConfirmButton: false,
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
            }

            // send dates
            xhr.send(dates)
        }

        function despachado(idProspecto) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Recuerda que debes tener toda la información del despacho (contrato, comprobante si se requiere) cargadas!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'

            }).then((result) => {

                if (result.value) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "action": "validate_despachado"
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
                                    title: 'Estado actualizado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 4000

                                });

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

        function transferVal(idProspecto) {

            let idUsuario = $("#id_usuario_validaciones").val();

            var parameters = {
                "id_prospecto": idProspecto,
                "id_usuario": idUsuario,
                "action": "select_validadores"
            };

            $.ajax({
                data: parameters,
                url: 'ajax/validacionesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var newOption = '';
                    //$("#ciudad_usuario").val(null).trigger('change');
                    $('#validadores').empty();

                    var placeholderOption = new Option('Seleccionar Validador', 'placeholder', true, true);
                    $('#validadores').append(placeholderOption)
                    //$('#validadores').val('placeholder').prop("disabled", true);
                    respuesta[0].forEach(function(validadores, index) {
                        newOption = new Option(validadores.nombre, validadores.id_usuario, false, false);
                        $('#validadores').append(newOption);
                    });
                    $("#validadores").val('placeholder').trigger('change');

                    $("#prospecto_transferir").val(idProspecto);
                    $("#validadores-modal").modal("show");

                }
            });

        }

        $("#transferValForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("transferValForm"));

            var action = "transfer_prospecto";

            formData.append("action", action);

            transferProspectoDB(formData);

        });

        function transferProspectoDB(dates){
                
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
                                title: 'Transferencia Realizada correctamente',
                                showConfirmButton: false,
                                timer: 4000
    
                            });
    
                            tablaProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);
    
                            $("#validadores-modal").modal("hide");
    
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

        function ImprimirBaucher(idProspecto){

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "print_remision"
            };

            $.ajax({
                data: parameters,
                url: 'http://localhost/tc_soft/ajax/validacionesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

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

    </script>

<?
} else {

    include '401error.php';
}

?>