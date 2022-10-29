<?

if (profile(3, $id_usuario) == 1) {


?>

    <style>
        .solicitud-rechazada {
            background-color: #d9534f !important;
        }

        .solicitud-aprobada {
            background-color: #28a745 !important;
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarSolicitudModal()">Nueva Solicitud</button>
                            <h4 class="card-title">Solicitudes</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableSolicitudes" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PROSPECTO</th>
                                            <th>PRODUCTO</th>
                                            <th>CONTACTO</th>
                                            <th>CORREO</th>
                                            <th>INICIAL</th>
                                            <th>ESTADO</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT solicitudes.id AS id_solicitud, productos_stock.id AS id_existencia, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar, prospectos.prospecto_cedula FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.del = 0 AND prospectos.id_plataforma = 3 ORDER BY solicitudes.id DESC";
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_solicitud = $row1['id_solicitud'];
                                            $id_existencia = $row1['id_existencia'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $prospecto_nombre = $row1['fullname'];
                                            $full_producto = $row1['full_producto'];
                                            $prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
                                            $contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
                                            $prospecto_email = $row1['prospecto_email'];

                                            $porcentaje = $row1['porcentaje'];
                                            $precio_producto = $row1['precio_producto'];

                                            $inicial = ($porcentaje * $precio_producto) / 100;

                                            $id_estado_solicitud = $row1['id_estado_solicitud'];

                                            $mostrar = $row1['mostrar'];

                                            $clase_color = '';

                                            $validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

                                            if ($validate_resultados_solicitud_cifin != 0) {
                                                $id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
                                                if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
                                                    $clase_color = "solicitud-aprobada";

                                                    $texto_cifin = "ELEGIBLE A";

                                                    if ($id_resultados_solicitud_cifin == 1) {
                                                        $texto_cifin = "ELEGIBLE AA";
                                                    }
                                                } else {
                                                    $clase_color = "solicitud-rechazada";

                                                    $texto_cifin = "RECHAZADO";
                                                }
                                            }

                                        ?>
                                            <tr class="row-<?= $id_solicitud . ' ' . $clase_color ?>">
                                                <td><?= $id_solicitud ?></td>
                                                <td><?= $prospecto_cedula.'-'.$prospecto_nombre ?>&nbsp;<? if (isset($texto_cifin)) { ?><span class="label label-info"><?= $texto_cifin ?></span><? } ?></td>
                                                <td><?= $full_producto ?></td>
                                                <td><?= $contacto ?></td>
                                                <td><?= $prospecto_email ?></td>
                                                <td><?= number_format($inicial, 0, '.', '.') ?></td>
                                                <td><span class="label label-info"><?= $mostrar ?></span></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <? if ($id_estado_solicitud == 4 && $id_existencia == 0) {
                                                    ?>
                                                        <a href="?page=solicitud&id_solicitud=<?= $id_solicitud ?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Detalles solicitud"><i class="mdi mdi-pencil"></i></a>

                                                    <? }
                                                    if ($id_estado_solicitud == 4 && $id_existencia == 0) { ?>
                                                        <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Resultado Cifin" onclick="cambiarResultado(<?= $id_solicitud ?>)"><i class="fas fa-credit-card"></i></a>

                                                    <? }
                                                    if ($id_estado_solicitud == 4) { ?>
                                                        <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Asegurar Equipo" onclick="enrolarEquipo(<?= $id_solicitud ?>)"><i class="fab fa-android"></i></a>

                                                    <? } 
                                                    if($id_estado_solicitud >= 4 && $id_existencia != 0){
                                                    ?>
                                                        <a href="?page=resumen_financiamiento&id=<?= $id_solicitud ?>" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resumen Solicitud"><i class="fas fa-file-contract"></i></a>
                                                    <? } 
                                                    if ($id_estado_solicitud == 1 || $id_estado_solicitud == 11) { ?>
                                                        <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="selecMedio(<?= $id_solicitud ?>)"><i class="far fa-paper-plane"></i></a>

                                                        <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Insertar Codigo" onclick="enterCode(<?= $id_solicitud ?>)"><i class="fas fa-code"></i></a>

                                                    <? }
                                                    if ($id_estado_solicitud == 2) { ?>
                                                        <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resultado Cifin" onclick="selecCifin(<?= $id_solicitud ?>)"><i class="fas fa-credit-card"></i></a>

                                                    <? }
                                                    if ($id_estado_solicitud == 12) { ?>
                                                        <a class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Firmar Contractos" onclick="firmarContractos(<?= $id_solicitud ?>, <?= $prospecto_numero_contacto ?>, 0)"><i class="fas fa-file-signature"></i></a>

                                                    <? }
                                                    if ($id_estado_solicitud == 13) { ?>

                                                        <a class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reenviar link de firma" onclick="firmarContractos(<?= $id_solicitud ?>, <?= $prospecto_numero_contacto ?>, 1)"><i class="fas fa-file-signature"></i></a>

                                                        <a class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Verificar firmado" onclick="VerificarFirmado(<?= $id_solicitud ?>)"><i class="fas fa-check-double"></i></a>

                                                    <? }
                                                    if ($id_estado_solicitud == 5 || $id_estado_solicitud == 6 || $id_estado_solicitud == 7 || $id_estado_solicitud == 8) { ?>

                                                        <a href="documents/solicitudes/<?= $id_solicitud ?>/contrato_minuta_firm_<?= $prospecto_cedula ?>.pdf" target="blank" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Contracto Firmado"><i class="fas fa-download"></i></a>

                                                        <a href="documents/solicitudes/<?= $id_solicitud ?>/pagare_firmado_<?= $prospecto_cedula ?>.pdf" target="blank" class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Pagaré"><i class="fas fa-download"></i></a>

                                                    <? } ?>

                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(<?= $id_solicitud ?>)"><i class="fas fa-trash"></i></a>

                                                </td>
                                            </tr>
                                        <?php $clase_color = '';
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


    <div id="crear-solicitud-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nueva Solicitud NOA10</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="crearSolicitudForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Busqueda por nombre o cedula:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="prospecto_solicitud" id="prospecto_solicitud" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled selected>Seleccionar Prospecto</option>
                                            <?php
                                            
                                            $condition = '';

                                            if(profile(14, $id_usuario)){

                                                $condition = " AND prospectos.id_responsable_interno = $id_usuario "; 

                                            }

                                            $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS nombre_prospecto FROM `prospectos` LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.del = 0 $condition AND prospectos.id_plataforma = 0";
                                            //echo $query;
                                            //die();
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {

                                                $id_prospecto = $row['id_prospecto'];
                                                $prospecto_cedula = $row['prospecto_cedula'];
                                                $nombre_prospecto = $row['nombre_prospecto'];

                                            ?>
                                                <option value="<?= $id_prospecto ?>"><?= $nombre_prospecto . ' - ' . $prospecto_cedula ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_solicitud" id="id_solicitud_crear" value="">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Nuevo Cliente modal -->
    <div id="confirmacion-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Enviar confirmación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="seleccionarConfirmForm" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Medio de envio:<span class="text-danger">&nbsp;*</span></label>
                            <div class="input-group">
                                <fieldset class="controls">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" value="1" name="medio_envio" id="medio_email" class="custom-control-input" required>
                                        <label class="custom-control-label" for="medio_email">Enviar por Email</label>
                                    </div>
                                </fieldset>
                                &nbsp; &nbsp;
                                <fieldset>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" value="2" name="medio_envio" id="medio_sms" class="custom-control-input">
                                        <label class="custom-control-label" for="medio_sms">Enviar por SMS</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_solicitud" id="id_solicitud" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <!-- Nuevo Cliente modal -->
    <div id="codigo-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar Codigo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="confirmCodigoForm" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center">
                                    <h2 id="time-expire"></h2>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <h4 id="explication"></h4>
                                </div>
                                <div class="code-container">
                                    <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                    <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                    <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                    <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                    <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                    <input type="number" class="code" name="codigoN[]" placeholder="0" min="0" max="9" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_solicitud_codigo" id="id_solicitud_codigo" value="">
                        <button type="button" class="btn btn-danger" style="float: left" onclick="cerrarModal();">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- resultado cifin modal -->

    <div id="resultadosc-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Resultado Estudio en cifin</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="resultadosCifinForm" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Estados Cifin:<span class="text-danger">&nbsp;*</span></label>
                            <div class="input-group">
                                <select class="form-control select2Class" name="id_resultado" id="resultados_cifin" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                    <option value="placeholder" disabled>Seleccionar Estado</option>
                                    <?php
                                    $query = "select id, estado from resultados_cifin order by estado";
                                    $result = qry($query);
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['estado'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_solicitud" id="id_solicitud_cifin" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <!-- enrolar equipo modal -->
    <div id="enrolar-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Asegurar Equipo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="seleccionarSistemaAseguradorForm" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Seleccionar Apliciacion:<span class="text-danger">&nbsp;*</span></label>
                            <div class="input-group">
                                <fieldset class="controls">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" value="1" name="plataforma_asegurar" id="app_trustonic" class="custom-control-input" required>
                                        <label class="custom-control-label" for="app_trustonic">TRUSTONIC</label>
                                    </div>
                                </fieldset>
                                &nbsp; &nbsp;
                                <fieldset>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" value="2" name="plataforma_asegurar" id="app_nuovo" class="custom-control-input">
                                        <label class="custom-control-label" for="app_nuovo">NUOVO</label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_solicitud" id="id_solicitud_enrolar" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->


    <!-- enrolar equipo modal -->

    <div id="enrolar-modal-nuovo" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Asegurar Equipo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="asegurarEquipoFormNuovo" method="post">
                    <div class="modal-body">
                        <div id="trustonic" class="form-group">

                        </div>
                        <div id="nuovo">
                            <div class="row">
                                <div class="col-md-12" style="text-align: center">
                                    <img src="documents/nuovo/qr-nuovo.png" alt="QR NUOVO">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Utilice los siguientes pasos para registrar un dispositivo nuevo o un restablecimiento de fábrica.</p>
                                    <h1>En dispositivos con Android 7.0 y superior.</h1>
                                    <ul>
                                        <li>Encienda el dispositivo y en la primera pantalla, toque en cualquier lugar de la pantalla 6 veces para ver un escáner de código QR.</li>
                                        <li>Escanee el código QR que se muestra aquí para descargar e instalar la aplicación Nuovo.</li>
                                        <li>La aplicación Nuovo completará automáticamente la configuración.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_solicitud" id="id_solicitud_enrolar_nuovo" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <script>

        function registrarSolicitudModal() {

            $("#prospecto_solicitud").val('placeholder');
            $("#prospecto_solicitud").trigger('change');
            $("#id_solicitud_crear").val('');
            $("#crear-solicitud-modal").modal("show");

        }


        $("#crearSolicitudForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = "crear_solicitud_p";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("crearSolicitudForm"));

            formData.append("action", action);

            crearSolicitudInicialDB(formData);

        });

        function crearSolicitudInicialDB(dates){

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                            title: 'Solicitud Creada Correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center">
                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="selecMedio(${respuesta.id_solicitud})"><i class="far fa-paper-plane"></i></a>
                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Insertar Codigo" onclick="enterCode(${respuesta.id_solicitud})"><i class="fas fa-code"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud);

                        $("#crear-solicitud-modal").modal("hide");

                    } else if (respuesta.response == "error_codigo") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Codigo Incorrecto',
                            showConfirmButton: false,
                            timer: 2500

                        });


                        $(".code").val('');


                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        $(".code").val('');


                    }



                }

            }

            // send dates
            xhr.send(dates)
        }

        //parametrización

        var modal1 = $("#codigo-modal"),
            idSolicitudCodigo = $("#id_solicitud_codigo"),
            modal2 = $("#enrolar-modal"),
            appTrustonic = $("#app_trustonic"),
            appNuovo = $("#app_nuovo");

        $(document).ready(function() {

            const codes = document.querySelectorAll('.code')

            codes[0].focus()
            console.log('se ejecuto');
            codes.forEach((code, idx) => {
                code.addEventListener('keydown', (e) => {
                    if (e.key >= 0 && e.key <= 9) {
                        codes[idx].value = ''
                        setTimeout(() => codes[idx + 1].focus(), 10)
                    } else if (e.key === 'Backspace') {
                        setTimeout(() => codes[idx - 1].focus(), 10)
                    }
                })
            })

        });


        $("#confirmCodigoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = "confirmar_codigo";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("confirmCodigoForm"));

            formData.append("action", action);

            confirmarCodigoDB(formData);

        });


        function confirmarCodigoDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                            title: 'Codigo Confirmado',
                            showConfirmButton: false,
                            timer: 2500

                        });


                        tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">Pendiente Verificación</span>`, `<div class="jsgrid-align-center">
                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resultado Cifin" onclick="selecCifin(${respuesta.id_solicitud})"><i class="fas fa-credit-card"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud);

                        modal1.modal("hide");


                    } else if (respuesta.response == "error_codigo") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Codigo Incorrecto',
                            showConfirmButton: false,
                            timer: 2500

                        });


                        $(".code").val('');


                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        $(".code").val('');


                    }



                }

            }

            // send dates
            xhr.send(dates)

        }

        function selecMedio(idSolicitud) {

            $("medio_email").prop('checked', false);
            $("medio_sms").prop('checked', false);
            $("#id_solicitud").val(idSolicitud);
            $("#confirmacion-modal").modal("show");

        }

        $("#seleccionarConfirmForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = "confirmacion";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("seleccionarConfirmForm"));
            formData.append("action", action);

            confirmacionDB(formData);

        });

        function confirmacionDB(dates) {
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                            title: 'Codigo enviado',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        $("#confirmacion-modal").modal("hide");

                        tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center">
                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación" onclick="selecMedio(${respuesta.id_solicitud})"><i class="far fa-paper-plane"></i></a>
                            <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Insertar Codigo" onclick="enterCode(${respuesta.id_solicitud})"><i class="fas fa-code"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud);

                        idSolicitudCodigo.val(respuesta.id_codigo);
                        modal1.modal("show");

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        $("#confirmacion-modal").modal("hide");

                    }



                }

            }

            // send dates
            xhr.send(dates)
        }

        function enterCode(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "validar_codigo"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        idSolicitudCodigo.val(respuesta.id_codigo);
                        modal1.modal("show");

                    } else if (respuesta.response == "fecha_vencida") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Codigo vencido',
                            text: 'Envia nuevamente el codigo',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 4000

                        });

                    } else if (respuesta.response == "codigo_error") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'El codigo no ha sido enviado',
                            text: 'Envia el codigo al cliente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 4000

                        });

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Opss...',
                            text: 'Error en el prcoeso',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                    }

                }

            });

        }

        function selecCifin(idSolicitud) {
            $("#resultados_cifin").val('placeholder');
            $("#resultados_cifin").trigger("change");
            $("#id_solicitud_cifin").val(idSolicitud);
            $("#resultadosc-modal").modal("show");
        }


        $("#resultadosCifinForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = "insertar_cifin";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("resultadosCifinForm"));
            formData.append("action", action);

            registrarCifinDB(formData);

        });

        function registrarCifinDB(dates) {
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                            title: 'resultado Actualizado',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre + `&nbsp;<span class="label label-info">${respuesta.texto_cifin}</span>`, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center"><a href="?page=solicitud&id_solicitud=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Detalles solicitud"><i class="mdi mdi-pencil"></i></a>
                                <a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar resutado Cifin" onclick="cambiarResultado(${respuesta.id_solicitud})"><i class="fas fa-credit-card"></i></a>
                                <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Asegurar Equipo" onclick="enrolarEquipo(${respuesta.id_solicitud})"><i class="fab fa-android"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + " " + respuesta.clase_color);

                        $("#resultadosc-modal").modal("hide");

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

            }

            // send dates
            xhr.send(dates)
        }

        function cerrarModal() {
            $("#codigo-modal").modal('hide');
            return 0;
        }

        function cambiarResultado(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "select_cifin"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {
                        $("#resultados_cifin").val(respuesta.cifin);
                        $("#resultados_cifin").trigger("change");
                        $("#id_solicitud_cifin").val(respuesta.id_solicitud);
                        $("#resultadosc-modal").modal("show");
                    } else {

                    }

                }
            });
        }

        function enrolarEquipo(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "validar_equipo"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.id_existencia != 0) {

                        appTrustonic.prop('checked', false);
                        appNuovo.prop('checked', false);
                        modal2.modal("show");
                        $("#id_solicitud_enrolar").val(idSolicitud);


                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Agrega un producto a la solicitud',
                            showConfirmButton: false,
                            timer: 4000

                        });

                    }

                }

            });

        }


        $("#seleccionarSistemaAseguradorForm").on("submit", function(e) {

            e.preventDefault();

            var idSolicitud = $("#id_solicitud_enrolar").val();

            if (appTrustonic[0].checked == true) {

                seleccionarImei(idSolicitud);

            } else if (appNuovo[0].checked == true) {

                $("#id_solicitud_enrolar_nuovo").val(idSolicitud);
                $("#enrolar-modal").modal("hide");
                $("#enrolar-modal-nuovo").modal("show");

            }

        });


        function seleccionarImei(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "select_imei"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    Swal.fire({
                        title: 'IMPORTANTE!',
                        html: `Por favor confirmar que el imei <span class="text-danger" style="font-size: 20px; font-weight: 700;">${respuesta.imei}</span> corresponde al equipo que se va a entregar en la solicitud.`,
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Estoy seguro!'
                    }).then((result) => {

                        if (result.value) {

                            const action = "enrolar_trustonic";
                            var formData = new FormData();
                            formData.append("action", action);
                            formData.append("imei", respuesta.imei);
                            formData.append("id_solicitud", respuesta.id_solicitud);

                            enrolarTrustonicDB(formData);


                        } else {

                            return 0;

                        }

                    });

                }
            });

        }

        function enrolarTrustonicDB(dates) {
            /** Call to Ajax **/
            // create the object
            //console.log(...dates);
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                            title: 'Equipo asegurado correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre + `&nbsp;<span class="label label-info">${respuesta.texto_cifin}</span>`, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center">
                                <a href="?page=resumen_financiamiento&id=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resumen Solicitud"><i class="fas fa-file-contract"></i></a>
                                <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Firmar Contractos" onclick="firmarContractos(${respuesta.id_solicitud}, ${respuesta.prospecto_numero_contacto}, 0)"><i class="fas fa-file-signature"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + " " + respuesta.clase_color);

                        $("#enrolar-modal").modal("hide");

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

        $("#asegurarEquipoFormNuovo").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            Swal.fire({
                title: 'IMPORTANTE!',
                text: "Para confirmar este proceso, se debe terminar la instalación de la app de NUOVO en el equipo a asegurar.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                if (result.value) {

                    const action = "validar_enrolada";
                    var formData = new FormData(document.getElementById("asegurarEquipoFormNuovo"));
                    formData.append("action", action);

                    verificarEnroladaDB(formData);


                } else {

                    $("#enrolar-modal-nuovo").modal("hide");

                }

            });

        });


        function verificarEnroladaDB(dates) {
            /** Call to Ajax **/
            // create the object
            //console.log(...dates);
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudesAjax.php', true);
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
                            title: 'Equipo asegurado correctamente',
                            showConfirmButton: false,
                            timer: 4000

                        });


                        tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre + `&nbsp;<span class="label label-info">${respuesta.texto_cifin}</span>`, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center">
                                <a href="?page=resumen_financiamiento&id=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resumen Solicitud"><i class="fas fa-file-contract"></i></a>
                                <a class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Firmar Contractos" onclick="firmarContractos(${respuesta.id_solicitud}, ${respuesta.prospecto_numero_contacto}, 0)"><i class="fas fa-file-signature"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + " " + respuesta.clase_color);

                    } else {

                        Swal.fire({
                            title: 'ERROR!',
                            text: "El imei de la solicitud no corresponde con el del equipo asegurado.",
                            type: 'error',
                            showCancelButton: false,
                            allowOutsideClick: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!',
                            footer: "Verifica los datos del equipo y intenta nuevamente."

                        }).then((result) => {

                            if (respuesta.permiso == 1) {

                                setTimeout(function() {
                                    location.href = "?page=nuovo"
                                }, 1000);

                            } else {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Comunicate con soporte tecnico para eliminar el equipo registrado en nuovo',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                            }

                        });

                    }

                    $("#enrolar-modal-nuovo").modal("hide");

                }

            }

            // send dates
            xhr.send(dates)
        }

        function firmarContractos(idSolicitud, celular, reEnvio) {

            var titulo, texto, confirmarButton, pie, accion;

            titulo = `Estas seguro?`;
            texto = `Por favor confirma que el numero celular <span class="text-danger" style="font-size: 28px;">${celular}</span> pertenece al prospecto de la solicitud.`;
            confirmarButton = `Si, Estoy seguro!`;
            pie = `<span class="text-danger">Se enviara el link para firmar mediante OTP (contracto, pagare, carta de instrucciones y minuta).</span>`;
            accion = "enviar_mensaje_firmar";

            Swal.fire({
                title: titulo,
                html: texto,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmarButton,
                footer: pie
            }).then((result) => {

                if (result.value) {

                    var parameters = {

                        "id_solicitud": idSolicitud,
                        "celular": celular,
                        "reenvio": reEnvio,
                        "action": accion

                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/solicitudesAjax.php',
                        type: 'post',
                        beforeSend: function() {
                            //$('#document-reminder-modal').modal('hide');
                            $('.response').show();
                        },
                        success: function(response) {
                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            $('.response').hide();

                            if (respuesta.response == "success") {


                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Link enviado correctamente',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                                tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                                tablaSolicitudes.row.add([
                                    respuesta.id_solicitud, respuesta.nombre + `&nbsp;<span class="label label-info">${respuesta.texto_cifin}</span>`, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center">
                                                <a href="?page=resumen_financiamiento&id=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resumen Solicitud"><i class="fas fa-file-contract"></i></a>
                                                <a class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reenviar link de firma" onclick="firmarContractos(${respuesta.id_solicitud}, ${respuesta.contacto}, 1)"><i class="fas fa-file-signature"></i></a>
                                                <a class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Verificar firmado" onclick="VerificarFirmado(${respuesta.id_solicitud})"><i class="fas fa-check-double"></i></a>
                                                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash-alt"></i></a></div>`
                                ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + " " + respuesta.clase_color);

                            } else if (respuesta.response == "contrato_firmado") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Error al reenviar',
                                    text: 'El cliente ya firmo el mensaje',
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

                    });

                }

            });

        }

        function VerificarFirmado(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "verificar_firmado"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                beforeSend: function() {
                    //$('#document-reminder-modal').modal('hide');
                    $('.response').show();
                },
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $('.response').hide();

                    if (respuesta.response == "success") {

                        tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        tablaSolicitudes.row.add([
                            respuesta.id_solicitud, respuesta.nombre + `&nbsp;<span class="label label-info">${respuesta.texto_cifin}</span>`, respuesta.modelo, respuesta.contacto, respuesta.email, respuesta.inicial, `<span class="label label-info">${respuesta.texto_estado}</span>`, `<div class="jsgrid-align-center">
                            <a href="?page=resumen_financiamiento&id=${respuesta.id_solicitud}" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Resumen Solicitud"><i class="fas fa-file-contract"></i></a>
                            <a href="documents/solicitudes/${respuesta.id_solicitud}/contrato_minuta_firm_${respuesta.prospecto_cedula}.pdf" target="blank" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Contracto Firmado"><i class="fas fa-download"></i></a>
                            <a href="documents/solicitudes/${respuesta.id_solicitud}/pagare_firmado_${respuesta.prospecto_cedula}.pdf" target="blank" class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Pagaré"><i class="fas fa-download"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(${respuesta.id_solicitud})"><i class="fas fa-trash"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_solicitud + " " + respuesta.clase_color);

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Contracto firmado correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "falta_firmar") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'El cliente no ha firmado el contracto',
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

            });
        }

        function SubiraGarantias(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "subir_garantias"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                }
            });
        }

        function eliminarSolicitud(idSolicitud) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para eliminar esta solicitud!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                console.log(result.value);

                if (result.value == true) {

                    var parameters = {
                        "id_solicitud": idSolicitud,
                        "action": "eliminar_solicitud"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/solicitudesAjax.php',
                        type: 'post',

                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                tablaSolicitudes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Solicitud Eliminada correctamente',
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

                    });

                } else {

                    return 0;

                }
            });

        }
    </script>

<?
} else {

    include '401error.php';
}

?>