<?

if (isset($_GET['id_solicitud']) && profile(3, $id_usuario) == 1) {

    $id_solicitud = $_GET['id_solicitud'];

    $id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

    $prospecto_nombre = execute_scalar("SELECT CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE solicitudes.id = $id_solicitud");

    $cifin = execute_scalar("SELECT estado FROM resultados_cifin WHERE id = $id_resultados_solicitud_cifin");

    $id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

    $id_responsable = execute_scalar("SELECT id_usuario FROM solicitudes WHERE id = $id_solicitud");

    $query = "SELECT * FROM solicitudes WHERE id = $id_solicitud";

    $result = qry($query);

    while ($row = mysqli_fetch_array($result)) {

        $id_existencia = $row['id_existencia'];
        $id_terminos_prestamo = $row['id_terminos_prestamo'];
        $id_frecuencia_pago = $row['id_frecuencia_pago'];
        $id_porcentaje_inicial = $row['id_porcentaje_inicial'];
        $precio_producto = $row['precio_producto'];

        if ($id_existencia != 0) {

            $id_inventario = execute_scalar("SELECT id_inventario FROM productos_stock WHERE id = $id_existencia");

            $id_producto = execute_scalar("SELECT id_producto FROM inventario WHERE id = $id_inventario");

            $imei1 = execute_scalar("SELECT imei_1 FROM productos_stock WHERE id_inventario = $id_inventario");
        }
    }

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
                    <h4 class="text-themecolor"><?= ucwords($page) . '&nbsp;<span class="text-danger">#' . $id_solicitud . '</span>' ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="?page=solicitudes">Solicitudes</a></li>
                            <li class="breadcrumb-item active"><?= ucwords($page) . '&nbsp;<span class="text-danger">#' . $id_solicitud . '</span>' ?></li>
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
                            <h4 class="card-title">Solicitud de credito&nbsp;<a href="?page=prospecto&id=<?= $id_prospecto ?>"><?= $prospecto_nombre ?></a>&nbsp;<span class="label label-info"><?= $cifin ?></span></h4>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <form class="smart-form" id="solicitudForm" method="post" action="">
                                <div class="row">
                                    <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Producto:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <select class="form-control select2Class" name="solicitud_producto" id="producto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" onchange="selectProducto(this.value, <?= $id_solicitud ?>)" required>
                                                    <option value="placeholder" disabled <? if ($id_existencia == 0) { ?>selected<? } ?>>Seleccionar Dispositivo</option>
                                                    <?

                                                    $join = '';
                                                    $condition = '';

                                                    if ($id_resultados_solicitud_cifin == 2) {
                                                        $join = " LEFT JOIN cifin_productos ON cifin_productos.id_producto = productos.id ";
                                                        $condition = " AND cifin_productos.id_resultado_cifin = 2 OR cifin_productos.id_resultado_cifin = 3 ";
                                                    } else if ($id_resultados_solicitud_cifin == 3) {
                                                        $join = " LEFT JOIN cifin_productos ON cifin_productos.id_producto = productos.id ";
                                                        $condition = " AND cifin_productos.id_resultado_cifin = 3 ";
                                                    }

                                                    $query = "SELECT productos_stock.id AS id_existencia2, productos.id AS id_producto, modelos.nombre_modelo, productos_stock.id_color, capacidades_telefonos.capacidad,
                                                             marcas.marca_producto, productos_stock.id_estado_producto, productos_stock.imei_1
                                                             FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id 
                                                             LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id
                                                             LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id" . $join . "WHERE productos_stock.del = 0" . $condition . "ORDER BY modelos.nombre_modelo";

                                                    if ($id_resultados_solicitud_cifin == 1) {

                                                        $query = "SELECT productos_stock.id AS id_existencia2, productos.id AS id_producto, modelos.nombre_modelo, productos_stock.id_color, capacidades_telefonos.capacidad,
                                                             marcas.marca_producto, productos_stock.id_estado_producto, productos_stock.imei_1
                                                             FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id 
                                                             LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id
                                                             LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id 
                                                             WHERE productos_stock.del = 0 ORDER BY modelos.nombre_modelo";
                                                    }
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {

                                                        $id_existencia2 = $row['id_existencia2'];
                                                        $nombre_modelo = $row['nombre_modelo'];
                                                        $id_color = $row['id_color'];
                                                        $capacidad = $row['capacidad'];
                                                        $marca_producto = $row['marca_producto'];
                                                        $imei_1 = $row['imei_1'];

                                                        $color_nombre = execute_scalar("SELECT color_desc FROM colores_productos WHERE id = $id_color");

                                                        $full_producto = $marca_producto . '-' . $nombre_modelo . '-' . $capacidad . '-' . $color_nombre . '-' . $imei_1;

                                                        $id_estado_producto = $row['id_estado_producto'];

                                                        if ($id_estado_producto == 1 || $id_existencia2 == $id_existencia) {

                                                    ?>
                                                            <option value="<?= $id_existencia2 ?>" <? if ($id_existencia == $id_existencia2) { ?>selected<? } ?>><?= $full_producto ?></option>
                                                    <? }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label>No existe?</label>&nbsp;
                                                <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left" onclick="addCelular()">Agregar Dispositivo</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Precio del modelo:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="precio_producto" id="precio_producto" placeholder="Precio del producto" autocomplete="ÑÖcompletes" <? if ($id_existencia != 0) { ?>value="<?= number_format($precio_producto, 0, '.', '.') ?>" <? } ?> required onkeypress="return filterFloat(event,this,id);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Terminos del prestamo:<span class="text-danger">*</span></h5>
                                            <div id="zone-terminos">
                                                <? if ($id_existencia != 0) {

                                                    $x = 0;
                                                    $first = '';
                                                    $query = "SELECT id_termino, numero_meses FROM terminos_productos LEFT JOIN terminos_prestamos ON terminos_productos.id_termino = terminos_prestamos.id WHERE id_producto = $id_producto";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($x == 0) {
                                                            $first = 'class="controls"';
                                                        }
                                                ?>
                                                        <fieldset <?= $first ?>>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="<?= $row['id_termino'] ?>" name="termino_prestamo" required id="termino<?= $row['numero_meses'] ?>" class="custom-control-input" <? if ($row['id_termino'] == $id_terminos_prestamo) { ?> checked <? } ?>>
                                                                <label class="custom-control-label" for="termino<?= $row['numero_meses'] ?>"><?= $row['numero_meses'] ?>&nbsp;Meses</label>
                                                            </div>
                                                        </fieldset>
                                                <? $x++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Frecuencia:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <select class="form-control select2Class" name="frecuencia_pago" id="frecuencia_pago" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" disabled <? if ($id_existencia == 0) { ?>selected<? } ?>>Seleccionar Frecuencia de pago</option>
                                                <? if ($id_existencia != 0) {
                                                    $query = "SELECT id_frecuencia_pago, frecuencia FROM frecuencias_productos LEFT JOIN frecuencias_pagos ON frecuencias_productos.id_frecuencia_pago = frecuencias_pagos.id WHERE id_producto = $id_producto";
                                                    $result = qry($query);
                                                    while ($row = mysqli_fetch_array($result)) {
                                                ?>
                                                        <option value="<?= $row['id_frecuencia_pago'] ?>" <? if ($row['id_frecuencia_pago'] == $id_frecuencia_pago) { ?> selected <? } ?>><?= $row['frecuencia'] ?></option>
                                                <? }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h5>Pago Inicial:<span class="text-danger">*</span></h5>
                                            <div id="zone-pagos">
                                                <? if ($id_existencia != 0) {
                                                    $y = 0;
                                                    $first = '';
                                                    $query = "SELECT id AS id_porcentaje, porcentaje FROM porcentajes_iniciales WHERE porcentajes_iniciales.del = 0";

                                                    $result = qry($query);

                                                    while ($row = mysqli_fetch_array($result)) {
                                                        if ($y == 0) {
                                                            $first = 'class="controls"';
                                                        }
                                                        $id_porcentaje = $row['id_porcentaje'];
                                                        $porcentaje = $row['porcentaje'];
                                                        $valor_porcentaje = ($porcentaje * $precio_producto) / 100;
                                                ?>
                                                        <fieldset <?= $first ?>>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" value="<?= $row['porcentaje'] ?>" name="porcentaje_inicial" required id="inicial<?= $row['porcentaje'] ?>" class="custom-control-input" <? if ($row['id_porcentaje'] == $id_porcentaje_inicial) { ?> checked <? } ?>>
                                                                <label class="custom-control-label" for="inicial<?= $row['porcentaje'] ?>"><?= number_format($valor_porcentaje, 0, '.', '.') . '&nbsp;(' . $row['porcentaje'] ?>%)</label>
                                                            </div>
                                                        </fieldset>
                                                <? $y++;
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="id_solicitud" id="id_solicitud" value="<?= $id_solicitud ?>">
                                        <input type="hidden" name="id_usuario" value="<?= $id_responsable ?>">
                                        <input type="hidden" name="id_prospecto" value="<?= $id_prospecto ?>">
                                        <input type="hidden" name="resultado_cifin" id="resultado_cifin" value="<?= $id_resultados_solicitud_cifin ?>">
                                        <a href="?page=solicitudes" class="btn waves-effect waves-light btn-lg btn-danger" style="float: left;">volver</a>
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;">Guardar Cambios</button>
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

    <!-- Add existencia modal -->
    <div id="registrar-existencia" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_existencia"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="registrarExistenciaForm" method="post">
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
                                    <label>Dispositivo:</label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="dispositivos" id="dispositivos" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Dispositivo</option>
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
                                    <label>IMEI1:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="imei_1" id="imei_1" placeholder="IMEI1 Del dispositivo" required autocomplete="ÑÖcompletes" maxlength="20" onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>IMEI2:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="imei_2" id="imei_2" placeholder="IMEI2 (Opcional)" autocomplete="ÑÖcompletes" maxlength="20" onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Color:<span class="text-danger">&nbsp;*</span></label></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="color_producto" id="color_producto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Color</option>
                                            <?php
                                            $query = "select id, color_desc from colores_productos where del = 0 order by color_desc";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['color_desc'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Proveedor:<span class="text-danger">&nbsp;*</span></label></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="proveedor_producto" id="proveedor_producto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Proveedor</option>
                                            <?php
                                            $query = "select id, proveedor_nombre from proveedores where del = 0 order by proveedor_nombre";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['proveedor_nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_existencia" id="id_existencia" value="">
                        <input type="hidden" name="action" id="action_existencia" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <script>
        function selectProducto(idExistencia, idSolicitud) {

            var parameters = {
                "id_existencia": idExistencia,
                "id_solicitud": idSolicitud,
                "action": "select_precio_producto"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    var respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "stock") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'No hay stock disponible',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        $("#producto").val('placeholder');
                        $("#producto").trigger("change");
                        $("#precio_producto").val('');
                        $("#zone2").hide();

                    } else {

                        $("#precio_producto").val(respuesta.precio_producto);

                        $("#zone2").show();

                        var zoneTerminos = $("#zone-terminos");

                        zoneTerminos.empty();

                        var md = '';

                        var first = '';

                        var newOption = '';

                        //$("#ciudad_usuario").val(null).trigger('change');
                        var zoneFrecuencias = $('#frecuencia_pago');

                        zoneFrecuencias.empty();

                        var zoneIniciales = $("#zone-pagos");

                        zoneIniciales.empty();

                        respuesta[0].forEach(function(terminos, index) {

                            console.log(terminos);

                            if (index == 0) {

                                first = `class="controls"`;
                            }


                            md += `<fieldset ${first}>
                                        <div class="custom-control custom-radio">
                                                <input type="radio" value="${terminos.id_termino}" name="termino_prestamo" required id="termino${terminos.numero_meses}" class="custom-control-input">
                                                <label class="custom-control-label" for="termino${terminos.numero_meses}">${terminos.numero_meses}&nbsp;Meses</label>
                                            </div>
                                    </fieldset>`;

                        });

                        zoneTerminos.append(md);

                        respuesta[1].forEach(function(frecuencias, index) {
                            //console.log(producto);

                            newOption = new Option(frecuencias.frecuencia, frecuencias.id_frecuencia_pago, false, false);
                            zoneFrecuencias.append(newOption).trigger('change');

                        });

                        md = '';

                        respuesta[2].forEach(function(iniciales, index) {

                            console.log(iniciales);

                            if (index == 0) {

                                first = `class="controls"`;
                            }


                            md += `<fieldset ${first}>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" value="${iniciales.porcentaje}" name="porcentaje_inicial" required id="inicial${iniciales.porcentaje}" class="custom-control-input ${index}">
                                            <label class="custom-control-label label-${index}" for="inicial${iniciales.porcentaje}"><span class="text-danger">$</span>${iniciales.valor_porcentaje}&nbsp;(${iniciales.porcentaje}%)</label>
                                        </div>
                                    </fieldset>`;

                        });

                        zoneIniciales.append(md);

                    }

                }

            });

        }

        $("#precio_producto").on("change", function(e) {

            const PrecioProducto = $("#precio_producto").val().replace(/\./g, '');

            //console.log(precioProducto);

            var totalIniciales = $('input:radio[name="porcentaje_inicial"]').length;

            console.log(totalIniciales);

            arrayIniciales = [];

            var ini = '';

            var valor = '';

            var camp = '';

            var label = '';

            for (var i = 0; i < totalIniciales; i++) {

                camp = $("." + i);
                label = $(".label-" + i);
                ini = camp.val();
                arrayIniciales.push(ini);

                valor = (ini * PrecioProducto) / 100;

                label.empty();
                label.html(`<span class="text-danger">$</span>${number_format(valor)}&nbsp;(${ini}%)`);

                ini = '';

                valor = '';

                camp = '';

                label = '';
            }

        });

        $("#solicitudForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("solicitudForm"));

            const action = "actualizar";

            formData.append('action', action);

            actualizarSolicitudDB(formData);

        });

        function actualizarSolicitudDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudAjax.php', true);
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
                            title: 'Solicitud Actualizada Correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        setTimeout(function() {
                            location.href = "?page=solicitudes"
                        }, 0);

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

        function addCelular() {

            $("#dispositivos").val('placeholder');
            $("#dispositivos").trigger("change");
            $("#imei_1").val('');
            $("#imei_2").val('');
            $("#color_producto").val('placeholder');
            $("#color_producto").trigger('change');
            $("#proveedor_producto").val('placeholder');
            $("#proveedor_producto").trigger('change');
            $("#id_existencia").val('');
            $("#action_existencia").val('insert_existencia');
            $("#titulo_existencia").val('Nueva Existencia');
            $("#registrar-existencia").modal("show");

        }


        $("#registrarExistenciaForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarExistenciaForm"));

            nuevaExistenciaDB(formData);

        });

        function nuevaExistenciaDB(dates){

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/solicitudAjax.php', true);
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
                            title: 'Dispositivo creado correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        var data = {
                            id: respuesta.id_existencia,
                            text: respuesta.marca + '-' + respuesta.modelo + '-' + respuesta.color + '-' + respuesta.imei1
                        };

                        var newOption = new Option(data.text, data.id, false, false);

                        $('#producto').append(newOption).trigger('change');
                        $('#producto').val(respuesta.id_existencia);
                        $('#producto').trigger('change');

                        $("#registrar-existencia").modal("hide");


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

    </script>

<?

} else {

    include '401error.php';
}

?>