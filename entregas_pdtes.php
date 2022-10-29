<?

if (profile(15, $id_usuario) == 1) {

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
                    <h4 class="text-themecolor"><?= ucwords(str_replace("_", " ", $page)) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item active"><?= ucwords(str_replace("_", " ", $page)) ?></li>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Solicitudes</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableReportesDomicilios" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PROSPECTO</th>
                                            <th>DISPOSITIVO</th>
                                            <th>FINANCIERA</th>
                                            <th>CONTACTO</th>
                                            <th>UBICACIÓN</th>
                                            <th>FECHA REGISTRO</th>
                                            <th>ESTADO</th>
                                            <th>DOMICILIARIO</th>
                                            <th>FECHA/HORA ENTREGA</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT DISTINCT solicitudes.id AS id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre,prospecto_detalles.prospecto_apellidos, marcas.marca_producto, modelos.nombre_modelo, productos_stock.imei_1, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento, estados_solicitudes.mostrar, solicitudes_domiciliarios.id_domiciliario, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS solicitud_delivery_date, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, solicitudes.id_estado_solicitud, solicitudes.del AS estado_eliminado, prospectos.id_plataforma, prospecto_detalles.id_referencia, capacidades_telefonos.capacidad, prospectos.imei_referencia, prospectos.id_medio_envio, plataformas_credito.nombre_plataforma, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y') AS fecha_registro_prospecto FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospectos.id = prospecto_detalles.id_prospecto LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN usuarios ON solicitudes_domiciliarios.id_domiciliario = usuarios.id WHERE solicitudes.id_estado_solicitud = 5 OR solicitudes.id_estado_solicitud = 6 AND prospectos.id_medio_envio <> 2 AND solicitudes.del = 0 AND solicitudes_domiciliarios.del = 0";
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_solicitud = $row1['id_solicitud'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $prospecto_nombre = $row1['prospecto_nombre'];
                                            $prospecto_apellidos = $row1['prospecto_apellidos'];
                                            $prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
                                            $prospecto_direccion = $row1['prospecto_direccion'];
                                            $prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
                                            $prospecto_direccion = $row1['prospecto_direccion'];
                                            $ciudad = $row1['ciudad'];
                                            $departamento = $row1['departamento'];
                                            $mostrar = $row1['mostrar'];
                                            $marca_producto = $row1['marca_producto'];
                                            $nombre_modelo = $row1['nombre_modelo'];
                                            $imei_1 = $row1['imei_1'];
                                            $id_domiciliario = $row1['id_domiciliario'];
                                            $id_estado_solicitud = $row1['id_estado_solicitud'];
                                            $estado_eliminado = $row1['estado_eliminado'];
                                            $id_plataforma = $row1['id_plataforma'];
                                            $id_referencia = $row1['id_referencia'];
                                            $capacidad = $row1['capacidad'];
                                            $id_medio_envio = $row1['id_medio_envio'];
                                            $nombre_plataforma = $row1['nombre_plataforma'];
                                            $fecha_registro_prospecto = $row1['fecha_registro_prospecto'];

                                            if ($id_plataforma == 1 || $id_plataforma == 2) {

                                                $marca_producto2 = execute_scalar("SELECT marcas.marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
                                                $nombre_modelo2 = execute_scalar("SELECT nombre_modelo FROM modelos WHERE modelos.id = $id_referencia");
                                                $capacidad2 = execute_scalar("SELECT capacidades_telefonos.capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");
                                                $imei_1_2 = $row1['imei_referencia'];
                                                //$dispositivo = $marca_producto2 . '-' . $nombre_modelo2 . '-' . $imei_1_2;
                                                $dispositivo = $marca_producto2 . '-' . $nombre_modelo2;
                                            } else {

                                                $dispositivo = $marca_producto . '-' . $nombre_modelo . '-' . $capacidad;

                                            }

                                            $clase_color = '';
                                            $estado_servientrega = 0;
                                            $entra = 1;
                                            $nombre_estado_s = '';

                                            if ($id_medio_envio != 0) {
                                                $clase_color = "solicitud-asignada";
                                            }

                                            if ($id_medio_envio == 1) {

                                                $usuario_nombre = $row1['nombre'];
                                                $usuario_apellidos = $row1['apellidos'];
                                                $usuario_full_name = $usuario_nombre . ' ' . $usuario_apellidos;
                                            } else if ($id_medio_envio == 2) {

                                                $estado_servientrega = execute_scalar("SELECT id_estado_solicitud FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");

                                                if ($estado_servientrega == 1) {
                                                    $nombre_estado_s = "EN RUTA";
                                                } else if ($estado_servientrega == 2) {
                                                    $nombre_estado_s = "PDTE. POR PAGAR";
                                                }

                                                $texto_estado_s = '<span class"label label-info">' . $nombre_estado_s . '</span>';

                                                $usuario_full_name = "SERVIENTREGA - ";

                                                $clase_color = "solicitud-asignada-servientrega";
                                            } else if ($id_medio_envio == 3) {

                                                $usuario_full_name = "RECOGE EN TIENDA";

                                                $clase_color = "solicitud-asignada-tienda";
                                            } else {

                                                $usuario_full_name = "N/A";
                                            }

                                            if (is_null($id_domiciliario)) {

                                                $solicitud_fecha_confirmada = 'N/A';
                                            } else {

                                                $solicitud_fecha_entrega = $row1['solicitud_delivery_date'];
                                                $solicitud_inicio_tiempo = $row1['solicitud_inicio_tiempo'];
                                                $solicitud_final_tiempo = $row1['solicitud_final_tiempo'];
                                                $solicitud_fecha_confirmada = $solicitud_fecha_entrega . ' DESDE ' . $solicitud_inicio_tiempo . ' HASTA ' . $solicitud_final_tiempo;
                                            }

                                            if ($estado_eliminado == 0 && $estado_servientrega != 4) {
                                        ?>
                                                <tr class="row-<?= $id_solicitud ?> <?= $clase_color ?>">
                                                    <td><?= $id_solicitud ?></td>
                                                    <td><?= $prospecto_cedula . '-' . $prospecto_nombre ?>&nbsp;<?= $prospecto_apellidos ?></td>
                                                    <td><?= $dispositivo ?></td>
                                                    <td><?= str_replace('_', ' ', $nombre_plataforma)?></td>
                                                    <td><?= $prospecto_numero_contacto ?></td>
                                                    <td><?= $prospecto_direccion . '-' . $ciudad . '/' . $departamento ?></td>
                                                    <td><?= $fecha_registro_prospecto?></td>
                                                    <td><span class="label label-info"><?= $mostrar ?></span></td>
                                                    <td><? echo $usuario_full_name;
                                                        if ($id_medio_envio == 2) { ?><span class="label label-info"><?= $nombre_estado_s ?></span><? } ?></td>
                                                    <td><?= $solicitud_fecha_confirmada ?></td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                        <? if ($id_estado_solicitud == 5) { ?>
                                                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Asignar Domiciliario" onclick="AsignarDomiciliario(<?= $id_solicitud ?>)"><i class="fas fa-motorcycle"></i></a>
                                                        <? } else if ($id_estado_solicitud == 6) { ?>
                                                            <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Entrega" onclick="EditarEntrega(<?= $id_solicitud ?>)"><i class="fas fa-motorcycle"></i></a>
                                                            <?
                                                            if ($id_medio_envio == 2) {
                                                                if ($estado_servientrega == 1) { ?>
                                                                    <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Entregado Servientrega" onclick="entregadoServientrega(<?= $id_solicitud ?>)"><i class="fas fa-truck-loading"></i></a>
                                                                    <a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Devolución Servientrega" onclick="devolucionServientrega(<?= $id_solicitud ?>)"><i class="fas fa-backspace"></i></a>
                                                                <? }
                                                                if ($estado_servientrega == 2) { ?>
                                                                    <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pagar Servientrega" onclick="pagarServientrega(<?= $id_solicitud ?>)"><i class="fas fa-dollar-sign"></i></a>
                                                                <? }
                                                            }
                                                            if ($id_medio_envio == 3) { ?>
                                                                <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Confirmar Entrega" onclick="ConfirmarEntrega(<?= $id_solicitud ?>)"><i class="fas fa-check"></i></a>
                                                            <? }
                                                        } else if ($id_estado_solicitud == 7) {
                                                            if ($id_medio_envio == 2) {
                                                            ?>
                                                                <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pagar Servientrega" onclick="pagarServientrega(<?= $id_solicitud ?>)"><i class="fas fa-dollar-sign"></i></a>
                                                        <? }
                                                        } ?>
                                                    </td>
                                                </tr>
                                        <? }
                                        } ?>
                                    </tbody>
                                </table>
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


    <!-- section modal for pick up date -->

    <div id="asignar_domi_modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="asignar_domi_titulo"></h4>
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
                                    <label>Cliente:</label>
                                    <div class="input-group">
                                        <input type="text" id="cliente_nombre" name="cliente_nombre" class="form-control" placeholder="Nombre del cliente" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dispositivo:</label>
                                    <div class="input-group">
                                        <input type="text" id="dispositivo_nombre" name="dispositivo_nombre" class="form-control" placeholder="Product Name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="zone-imei">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imei_referencia">Imei del dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="imei_referencia" name="imei_referencia" class="form-control" placeholder="Imei de la referencia a entregar" autocomplete="ÑÖcompletes" maxlength="20" onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="pickup_date" name="pickup_date" class="form-control max-date" placeholder="Seleccionar fecha de entrega" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora Inicial:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>
                                        </div>
                                        <input id="pickup_start" name="pickup_start" class="form-control date-start" placeholder="Selec. Hora Inicial Entrega" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora Final:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-hourglass-end"></i></span>
                                        </div>
                                        <input id="pickup_end" name="pickup_end" class="form-control date-end" placeholder="Selec. Hora Final Entrega" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="guia_servientrega">Guia Servientrega:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" id="guia_servientrega" name="guia_servientrega" class="form-control" placeholder="Guia de servientrega" autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sobreflete_servientrega">Sobreflete:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" id="sobreflete_servientrega" name="sobreflete_servientrega" class="form-control" placeholder="costo del flete" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Destino:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <select class="form-control select2Class" name="destino_servientrega" id="destino_servientrega" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" selected disabled>Seleccionar Destino</option>
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
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bolsa de seguridad:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <fieldset class="controls">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" value="1" name="bolsa_servientrega" id="si_bolsa" class="custom-control-input" checked>
                                                    <label class="custom-control-label" for="si_bolsa">SI</label>
                                                </div>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" value="2" name="bolsa_servientrega" id="no_bolsa" class="custom-control-input">
                                                    <label class="custom-control-label" for="no_bolsa">NO</label>
                                                </div>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                        </div>
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
                        <input type="hidden" name="id_solicitud" id="id_solicitud" value="">
                        <input type="hidden" name="id_domiciliario" id="id_domiciliario" value="">
                        <input type="hidden" name="action" id="action_domi" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--end section modal for pick up date -->

    <script>
        'use strict';

        var zoneServientrega = $("#zone-servientrega");
        var zoneImei = $("#zone-imei");
        var zoneDomiciliario = $("#zone-domiciliario");
        var zoneTienda = $("#zone-tienda");


        function AsignarDomiciliario(idSolicitud, from) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "from": from,
                "action": "select_entrega"
            };

            $.ajax({
                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "success") {



                        $("#cliente_nombre").val(respuesta.prospecto);
                        $("#dispositivo_nombre").val(respuesta.dispositivo);
                        $("#pickup_date").val(respuesta.pick_up_date);
                        $("#pickup_start").val(respuesta.pick_up_start);
                        $("#pickup_end").val(respuesta.pick_up_end);
                        zoneDomiciliario.hide();
                        zoneServientrega.hide();
                        zoneTienda.hide();
                        $("#medio_domicilio").prop('checked', false);
                        $("#medio_servientrega").prop('checked', false);
                        $("#medio_en_tienda").prop('checked', false);

                        $("#domiciliario_solicitud").prop('required', true);
                        $("#domiciliario_solicitud").val('placeholder');
                        $("#domiciliario_solicitud").trigger('change');

                        $("#guia_servientrega").prop('required', true);
                        $("#guia_servientrega").val('');
                        $("#sobreflete_servientrega").prop('required', true);
                        $("#sobreflete_servientrega").val('');
                        $("#destino_servientrega").prop('required', true);
                        $("#destino_servientrega").val(respuesta.id_destino);
                        $("#destino_servientrega").trigger('change');
                        $("#si_bolsa").prop('required', true);
                        $("#si_bolsa").prop("checked", true);
                        $("#no_bolsa").prop('checked', false);

                        $("#responsable_entrega").prop('required', true);
                        $("#responsable_entrega").val('placeholder');
                        $("#responsable_entrega").trigger('change');

                        $("#imei_referencia").val('');
                        if (respuesta.id_plataforma == 1 || respuesta.id_plataforma == 2) {
                            zoneImei.show();
                            $("#imei_referencia").prop('required', true);
                            if (respuesta.imei_1 != 0) {
                                $("#imei_referencia").val(respuesta.imei_1);
                            }
                        } else {
                            zoneImei.hide();
                            $("#imei_referencia").prop('required', false);
                            $("#imei_referencia").val('');
                        }

                        $("#id_solicitud").val(idSolicitud);
                        $("#action_domi").val('insertar_domi');
                        $("#asignar_domi_titulo").html('Asignar Entrega');
                        $("#asignar_domi_modal").modal("show");

                    } else if (respuesta.response == "pdte_validar") {

                        tablaReportesDomicilios.row(".row-" + idSolicitud).remove().draw(false);

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

            });

        }

        $("#asignar_domi_form").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("asignar_domi_form"));

            const action = document.querySelector("#action_domi").value;

            if (action == "insertar_domi") {

                insertDomiDB(formData);

            } else {

                updateDomiDB(formData);

            }

        });

        function insertDomiDB(dates) {
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/reportesAjax.php', true);
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
                            title: 'Domiciliario asignado satisfactoriamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        var botonesDomicilios = '';

                        if (respuesta.id_medio_envio != 2) {

                            if (respuesta.id_medio_envio != 3) {

                                botonesDomicilios = `<a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Confirmar Entrega" onclick="ConfirmarEntrega(${respuesta.id_solicitud})"><i class="fas fa-check"></i></a>`;

                            }

                            tablaReportesDomicilios.row.add([
                                respuesta.id_solicitud, respuesta.prospecto, respuesta.dispositivo, respuesta.plataforma, respuesta.contacto, respuesta.ubicacion, `<span class="label label-info">${respuesta.estado}</span>`, respuesta.domiciliario, respuesta.fecha_entrega, `<div class="jsgrid-align-center"><a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Entrega" onclick="EditarEntrega(${respuesta.id_solicitud})"><i class="fas fa-motorcycle"></i></a>${botonesDomicilios}</div>`
                            ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + ' ' + respuesta.clase_color);

                        }

                        $("#asignar_domi_modal").modal("hide");

                    } else if (respuesta.response == "pdte_validar") {

                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);


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
            }

            // send dates
            xhr.send(dates)
        }

        function EditarEntrega(idSolicitud, from) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "from": from,
                "action": "select_entrega"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        $("#cliente_nombre").val(respuesta.prospecto);
                        $("#dispositivo_nombre").val(respuesta.dispositivo);
                        $("#pickup_date").val(respuesta.pick_up_date);
                        $("#pickup_start").val(respuesta.pick_up_start);
                        $("#pickup_end").val(respuesta.pick_up_end);
                        zoneDomiciliario.hide();
                        zoneServientrega.hide();
                        zoneTienda.hide();

                        $("#medio_domicilio").prop('checked', false);
                        $("#medio_servientrega").prop('checked', false);
                        $("#medio_en_tienda").prop('checked', false);

                        $("#domiciliario_solicitud").prop('required', false);
                        $("#domiciliario_solicitud").val('placeholder');
                        $("#domiciliario_solicitud").trigger('change');

                        $("#guia_servientrega").prop('required', false);
                        $("#guia_servientrega").val('');
                        $("#sobreflete_servientrega").prop('required', false);
                        $("#sobreflete_servientrega").val('');
                        $("#destino_servientrega").prop('required', false);
                        $("#destino_servientrega").val('');
                        $("#si_bolsa").prop('required', false);
                        $("#si_bolsa").prop('checked', false);
                        $("#no_bolsa").prop('checked', false);

                        $("#responsable_entrega").prop('required', false);
                        $("#responsable_entrega").val('placeholder');
                        $("#responsable_entrega").trigger('change');

                        $("#imei_referencia").prop('required', false);

                        if (respuesta.id_medio_envio == 1) {
                            $("#medio_domicilio").prop('checked', true);
                            zoneDomiciliario.show();
                            $("#domiciliario_solicitud").prop('required', true);
                            $("#domiciliario_solicitud").val(respuesta.id_domiciliario);
                            $("#domiciliario_solicitud").trigger('change');

                        } else if (respuesta.id_medio_envio == 2) {

                            zoneServientrega.show();
                            $("#medio_servientrega").prop('checked', true);
                            $("#guia_servientrega").prop('required', true);
                            $("#guia_servientrega").val(respuesta.guia_servientrega);
                            $("#sobreflete_servientrega").prop('required', true);
                            $("#sobreflete_servientrega").val('');
                            $("#destino_servientrega").prop('required', true);
                            $("#destino_servientrega").val('');
                            $("#si_bolsa").prop('required', true);
                            $("#si_bolsa").prop('checked', false);
                            $("#no_bolsa").prop('checked', false);


                        } else if (respuesta.id_medio_envio == 3) {

                            zoneTienda.show();
                            $("#medio_en_tienda").prop('checked', true);
                            $("#responsable_entrega").prop('required', true);
                            $("#responsable_entrega").val(respuesta.id_responsable_entrega);
                            $("#responsable_entrega").trigger("change");

                        }

                        $("#imei_referencia").val('');
                        if (respuesta.id_plataforma == 1 || respuesta.id_plataforma == 2) {
                            //zoneImei.show();
                            $("#imei_referencia").prop('required', true);
                            if (respuesta.imei_1 == 0) {
                                $("#imei_referencia").val('');
                            } else {
                                $("#imei_referencia").val(respuesta.imei_1);
                            }
                        } else {
                            zoneImei.hide();
                            $("#imei_referencia").prop('required', false);
                            $("#imei_referencia").val('');
                        }

                        $("#id_solicitud").val(idSolicitud);
                        $("#action_domi").val('editar_domi');
                        $("#asignar_domi_titulo").html('Editar Entrega');
                        $("#asignar_domi_modal").modal("show");

                    } else if (respuesta.response == "pdte_validar") {

                        tablaReportesDomicilios.row(".row-" + idSolicitud).remove().draw(false);

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

            });

        }

        function updateDomiDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/reportesAjax.php', true);
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
                            title: 'Entrega actualizada satisfactoriamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });


                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        if (respuesta.id_medio_envio != 2) {

                            var botonesDomicilios = '';

                            if (respuesta.id_medio_envio == 2) {

                                if (respuesta.id_estado_servientrega == 1) {

                                    botonesDomicilios = `<a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Entregado Servientrega" onclick="entregadoServientrega(${respuesta.id_solicitud})"><i class="fas fa-truck-loading"></i></a>
        <a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Devolución Servientrega" onclick="devolucionServientrega(${respuesta.id_solicitud})"><i class="fas fa-backspace"></i></a>`;

                                } else {

                                    botonesDomicilios = `<a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pagar Servientrega" onclick="pagarServientrega(${respuesta.id_solicitud})"><i class="fas fa-dollar-sign"></i></a>`;


                                }

                            } else {

                                botonesDomicilios = `<a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Confirmar Entrega" onclick="ConfirmarEntrega(${respuesta.id_solicitud})"><i class="fas fa-check"></i></a>`;

                            }


                            tablaReportesDomicilios.row.add([
                                respuesta.id_solicitud, respuesta.prospecto, respuesta.dispositivo, respuesta.plataforma, respuesta.contacto, respuesta.ubicacion, `<span class="label label-info">${respuesta.estado}</span>`, respuesta.domiciliario, respuesta.fecha_entrega, `<div class="jsgrid-align-center"><a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Entrega" onclick="EditarEntrega(${respuesta.id_solicitud})"><i class="fas fa-motorcycle"></i></a>${botonesDomicilios}</div>`
                            ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + ' ' + respuesta.clase_color);

                        }

                        $("#asignar_domi_modal").modal("hide");

                    } else if (respuesta.response == "pdte_validar") {

                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

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
            }

            // send dates
            xhr.send(dates)
        }

        function changeMedioEnvio(idMedio) {

            zoneServientrega.hide();
            zoneDomiciliario.hide();
            zoneTienda.hide();


            $("#domiciliario_solicitud").prop('required', false);
            $("#domiciliario_solicitud").val('placeholder');
            $("#domiciliario_solicitud").trigger('change');

            $("#guia_servientrega").prop('required', false);
            $("#guia_servientrega").val('');
            $("#sobreflete_servientrega").prop('required', false);
            $("#sobreflete_servientrega").val('');
            $("#destino_servientrega").prop('required', false);
            $("#destino_servientrega").val('');
            $("#si_bolsa").prop('required', false);
            $("#si_bolsa").prop('checked', false);
            $("#no_bolsa").prop('checked', false);


            $("#responsable_entrega").prop('required', false);
            $("#responsable_entrega").val('placeholder');
            $("#responsable_entrega").trigger('change');

            if (idMedio == 1) {

                $("#medio_domicilio").prop('checked', true);
                $("#medio_servientrega").prop('checked', false);
                $("#medio_en_tienda").prop('checked', false);
                zoneDomiciliario.show();
                $("#domiciliario_solicitud").val('placeholder');
                $("#domiciliario_solicitud").trigger("change");
                $("#domiciliario_solicitud").prop('required', true);

            } else if (idMedio == 2) {

                $("#medio_domicilio").prop('checked', false);
                $("#medio_servientrega").prop('checked', true);
                $("#medio_en_tienda").prop('checked', false);
                zoneServientrega.show();
                $("#guia_servientrega").prop('required', true);
                $("#guia_servientrega").val('');
                $("#sobreflete_servientrega").prop('required', true);
                $("#sobreflete_servientrega").val('');
                $("#destino_servientrega").prop('required', true);
                $("#destino_servientrega").val('');
                $("#si_bolsa").prop('required', true);
                $("#si_bolsa").prop('checked', false);
                $("#no_bolsa").prop('checked', false);



            } else if (idMedio == 3) {

                zoneTienda.show();
                $("#medio_domicilio").prop('checked', false);
                $("#medio_servientrega").prop('checked', false);
                $("#medio_en_tienda").prop('checked', true);
                $("#domiciliario_solicitud").val('placeholder');
                $("#domiciliario_solicitud").trigger("change");
                $("#domiciliario_solicitud").prop('required', true);
            }

        }

        function ConfirmarEntrega(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "confirmar_recogida_tienda"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Entrega confirmada satisfactoriamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

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

        function entregadoServientrega(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "confirmar_entregado_servientrega"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Entrega confirmada satisfactoriamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });


                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaReportesDomicilios.row.add([
                            respuesta.id_solicitud, respuesta.prospecto, respuesta.dispositivo, respuesta.plataforma, respuesta.contacto, respuesta.ubicacion, `<span class="label label-info">${respuesta.estado}</span>`, respuesta.domiciliario, respuesta.fecha_entrega, `<div class="jsgrid-align-center"><a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pagar Servientrega" onclick="pagarServientrega(${respuesta.id_solicitud})"><i class="fas fa-dollar-sign"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + ' ' + respuesta.clase_color);

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

        function devolucionServientrega(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "confirmar_devolucion_servientrega"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Servientrega Actualizado Correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });


                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

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

        function pagarServientrega(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "pagar_servientrega"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Servientrega Actualizado Correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });


                        tablaReportesDomicilios.row(".row-" + respuesta.id_solicitud).remove().draw(false);

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

<?
} else {

    include '401error.php';
}

?>