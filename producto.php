<?

if (isset($_GET['id_producto'])) {


    $id_producto = $_GET['id_producto'];
    $nombre_producto = execute_scalar("SELECT nombre_modelo FROM productos LEFT JOIN modelos ON productos.id_modelo = modelos.id WHERE productos.id = $id_producto");

    $capacidad = execute_scalar("SELECT capacidad FROM productos LEFT JOIN capacidades_telefonos ON productos.id_capacidad = capacidades_telefonos.id WHERE productos.id = $id_producto");

    $id_inventario = execute_scalar("SELECT id AS id_inventario FROM inventario WHERE id_producto = $id_producto");
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
                <h4 class="text-themecolor"><?= ucwords($nombre_producto . ' ' . $capacidad) ?></h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="?page=productos">Productos</a></li>
                        <li class="breadcrumb-item active"><?= ucwords($nombre_producto . ' ' . $capacidad) ?></li>
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
                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarExistenciaModal()">Agregar Existencia</button>
                        <h4 class="card-title"><?= $nombre_producto . ' ' . $capacidad ?></h4>
                        <div class="table-responsive m-t-40">
                            <table id="dataTableProducto" class="table display table-bordered table-striped no-wrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>IMEI 1</th>
                                        <th>IMEI 2</th>
                                        <th>PROVEEDOR</th>
                                        <th>COLOR</th>
                                        <th>ESTADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $query1 = "SELECT productos_stock.id AS id_existencia, imei_1, imei_2, proveedores.proveedor_nombre, color_desc, id_estado_producto, estados_productos.estado_desc FROM productos_stock LEFT JOIN estados_productos ON productos_stock.id_estado_producto = estados_productos.id LEFT JOIN proveedores ON productos_stock.id_proveedor = proveedores.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE productos_stock.del = 0 AND inventario.id_producto = $id_producto";
                                    $result1 = qry($query1);
                                    while ($row1 = mysqli_fetch_array($result1)) {

                                        $id_existencia = $row1['id_existencia'];
                                        $imei1 = $row1['imei_1'];
                                        $imei2 = $row1['imei_2'];
                                        $proveedor_nombre = $row1['proveedor_nombre'];
                                        $producto_color = $row1['color_desc'];
                                        $id_estado_producto = $row1['id_estado_producto'];
                                        $estado_desc = $row1['estado_desc'];

                                    ?>
                                        <tr class="row-<?= $id_existencia ?>">
                                            <td><?= $id_existencia ?></td>
                                            <td><?= $imei1 ?></td>
                                            <td><?= $imei2 ?></td>
                                            <td><?= $proveedor_nombre ?></td>
                                            <td><?= $producto_color ?></td>
                                            <? if ($id_estado_producto == 1) { ?>
                                                <td><span class="label label-success"><?= $estado_desc ?></span></td>
                                            <? } else if ($id_estado_producto == 4 || $id_estado_producto == 5) { ?>
                                                <td><span class="label label-danger"><?= $estado_desc ?></span></td>
                                            <? } else if ($id_estado_producto == 2) { ?>
                                                <td><span class="label label-info"><?= $estado_desc ?></span></td>
                                            <? } else if ($id_estado_producto == 3) { ?>
                                                <td><span class="label label-primary"><?= $estado_desc ?></span></td>
                                            <? } ?>
                                            <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                <? if ($id_estado_producto == 1 || $id_estado_producto == 2) { ?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Existencia" onClick="editarExistencia(<?= $id_existencia ?>)"><i class="mdi mdi-pencil"></i></a>
                                                    <!--<a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Equipo dañado" onclick="estadoDanado(<?= $id_existencia ?>)"><i class="fas fa-hammer"></i></a>-->
                                                <? } else if ($id_estado_producto == 4) { ?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Equipo Reparado" onclick="estadoReparado(<?= $id_existencia ?>)"><i class="fas fa-hammer"></i></a>
                                                <? }
                                                if ($id_estado_producto == 3) { ?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Reportar Garantia" onclick="reportarGarantia(<?= $id_existencia ?>)"><i class="fas fa-hammer"></i></a>
                                                <? }
                                                if ($id_estado_producto == 2 || $id_estado_producto == 3 || $id_estado_producto == 5) {
                                                ?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Información<?= $id_estado_producto ?>" onClick="verHistorial(<?= $id_existencia ?>)"><i class="fas fa-history"></i></a>
                                                <? }
                                                if ($id_estado_producto == 1 || $id_estado_producto == 4) {
                                                ?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Existencia" onClick="EliminarExistencia(<?= $id_existencia ?>)"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        <? } ?>
                                        </tr>
                                    <?php } ?>
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
                    </div>
                    <div class="row">
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
                    <input type="hidden" name="id_producto" value="<?= $id_producto ?>">
                    <input type="hidden" name="id_inventario" value="<?= $id_inventario ?>">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- /.modal -->

<div id="historial-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row pt-3">
                    <div class="col-md-12" id="zone-historial">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function registrarExistenciaModal() {

        $("#imei_1").val('');
        $("#imei_2").val('');
        $("#color_producto").val('placeholder');
        $("#color_producto").trigger('change');
        $("#proveedor_producto").val('placeholder');
        $("#proveedor_producto").trigger('change');
        $("#id_existencia").val('');
        $("#action_existencia").val('insertar');
        $("#titulo_existencia").html('Agregar Dispositivo');
        $("#registrar-existencia").modal("show");

    }

    $("#registrarExistenciaForm").on("submit", function(e) {

        // evitamos que se envie por defecto
        e.preventDefault();

        // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("registrarExistenciaForm"));

        const action = document.querySelector("#action_existencia").value;

        if (action == "insertar") {

            insertExistenciaDB(formData);

        } else {

            updateExistenciaDB(formData);

        }

    });


    function insertExistenciaDB(dates) {

        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/productoAjax.php', true);
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
                        title: 'Dispositivo registrado correctamente',
                        showConfirmButton: false,
                        timer: 3500

                    });


                    tablaProducto.row.add([
                        respuesta.id_existencia, respuesta.imei1, respuesta.imei2, respuesta.proveedor, respuesta.color, `<span class="label label-success">${respuesta.estado}</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Existencia" onClick="editarExistencia(${respuesta.id_existencia})"><i class="mdi mdi-pencil"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Existencia" onClick="eliminarProducto(${respuesta.id_existencia})"><i class="fas fa-trash-alt"></i></a>
                        </div>`
                    ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_existencia);

                    $("#registrar-existencia").modal("hide");

                } else if (respuesta.response == "imei_existe") {

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'El IMEI ya existe',
                        showConfirmButton: false,
                        timer: 3500

                    });

                } else {

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Error en el proceso',
                        showConfirmButton: false,
                        timer: 3500

                    });
                }
            }

        }

        // send dates
        xhr.send(dates)

    }


    function editarExistencia(idExistencia) {

        var parameters = {
            "id_existencia": idExistencia,
            "action": "select_existencia"
        };

        $.ajax({

            data: parameters,
            url: 'ajax/productoAjax.php',
            type: 'post',
            success: function(response) {
                console.log(response);
                const respuesta = JSON.parse(response);
                console.log(respuesta);

                $("#imei_1").val(respuesta[0].imei_1);
                $("#imei_2").val(respuesta[0].imei_2);
                $("#color_producto").val(respuesta[0].id_color);
                $("#color_producto").trigger('change');
                $("#proveedor_producto").val(respuesta[0].id_proveedor);
                $("#proveedor_producto").trigger('change');
                $("#id_existencia").val(respuesta[0].id);
                $("#action_existencia").val('editar');
                $("#titulo_existencia").html('Editar Dispositivo');
                $("#registrar-existencia").modal("show");
            }

        });
    }

    function updateExistenciaDB(dates) {

        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/productoAjax.php', true);
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
                        title: 'Dispositivo Editado correctamente',
                        showConfirmButton: false,
                        timer: 2500
                    });

                    tablaProducto.row(".row-" + respuesta.id_existencia).remove().draw(false);

                    tablaProducto.row.add([
                        respuesta.id_existencia, respuesta.imei1, respuesta.imei2, respuesta.proveedor, respuesta.color, `<span class="label label-success">${respuesta.estado}</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Existencia" onClick="editarExistencia(${respuesta.id_existencia})"><i class="mdi mdi-pencil"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Existencia" onClick="eliminarProducto(${respuesta.id_existencia})"><i class="fas fa-trash-alt"></i></a>
                        </div>`
                    ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_existencia);

                    $("#registrar-existencia").modal("hide");

                } else if (respuesta.response == "imei_existe") {

                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'El IMEI ya existe',
                        showConfirmButton: false,
                        timer: 3500
                    });

                } else {

                    Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Error en el proceso',
                        showConfirmButton: false,
                        timer: 3500

                    });
                }
            }

        }

        // send dates
        xhr.send(dates)

    }


    function EliminarExistencia(idExistencia) {

        Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para eliminar este Dispositvo!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
        }).then((result) => {
            if (result.value) {
                var parameters = {
                    "id_existencia": idExistencia,
                    "action": "eliminar"
                };

                $.ajax({
                    data: parameters,
                    url: 'ajax/productoAjax.php',
                    type: 'post',
                    success: function(response) {

                        console.log(response);
                        const respuesta = JSON.parse(response);

                        console.log(respuesta);

                        if (respuesta.response == "success") {

                            tablaProducto.row(".row-" + respuesta.id_existencia).remove().draw(false);

                            $.toast({
                                heading: 'Genial!',
                                text: 'Dispositivo Eliminado Correctamente.',
                                position: 'top-center',
                                loaderBg: '#00c292',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

                        } else if (respuesta.response == "asignado") {

                            $.toast({

                                heading: 'Error!',
                                text: 'Equipo asignado a una solicitud.',
                                position: 'top-center',
                                loaderBg: '#e46a76',
                                icon: 'error',
                                hideAfter: 4500,
                                stack: 6
                            });

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

    function estadoDanado(idExistencia) {

        Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para cambiar el estado a dañado!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
        }).then((result) => {
            if (result.value) {
                var parameters = {
                    "id_existencia": idExistencia,
                    "action": "danado"
                };

                $.ajax({
                    data: parameters,
                    url: 'ajax/productoAjax.php',
                    type: 'post',
                    success: function(response) {

                        console.log(response);
                        const respuesta = JSON.parse(response);

                        console.log(respuesta);

                        if (respuesta.response == "success") {

                            tablaProducto.row(".row-" + respuesta.id_existencia).remove().draw(false);

                            tablaProducto.row.add([
                                respuesta.id_existencia, respuesta.imei_1, respuesta.imei_2, respuesta.proveedor_nombre, respuesta.color_desc, `<span class="label label-danger">${respuesta.estado_desc}</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Existencia" onClick="editarExistencia(${respuesta.id_existencia})"><i class="mdi mdi-pencil"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Equipo Reparado" onclick="estadoReparado(${respuesta.id_existencia})"><i class="fas fa-hammer"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Existencia" onClick="eliminarProducto(${respuesta.id_existencia})"><i class="fas fa-trash-alt"></i></a>
                            </div>`
                            ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_existencia);

                            $.toast({
                                heading: 'Genial!',
                                text: 'Estado cambio Correctamente.',
                                position: 'top-center',
                                loaderBg: '#00c292',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

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

    function estadoReparado(idExistencia) {


        Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para cambiar el estado a Disponible!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
        }).then((result) => {
            if (result.value) {
                var parameters = {
                    "id_existencia": idExistencia,
                    "action": "reparado"
                };

                $.ajax({
                    data: parameters,
                    url: 'ajax/productoAjax.php',
                    type: 'post',
                    success: function(response) {

                        console.log(response);
                        const respuesta = JSON.parse(response);

                        console.log(respuesta);

                        if (respuesta.response == "success") {

                            tablaProducto.row(".row-" + respuesta.id_existencia).remove().draw(false);

                            tablaProducto.row.add([
                                respuesta.id_existencia, respuesta.imei_1, respuesta.imei_2, respuesta.proveedor_nombre, respuesta.color_desc, `<span class="label label-success">${respuesta.estado_desc}</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Existencia" onClick="editarExistencia(${respuesta.id_existencia})"><i class="mdi mdi-pencil"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Equipo Reparado" onclick="estadoDanado(${respuesta.id_existencia})"><i class="fas fa-hammer"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Existencia" onClick="eliminarProducto(${respuesta.id_existencia})"><i class="fas fa-trash-alt"></i></a>
                            </div>`
                            ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_existencia);

                            $.toast({
                                heading: 'Genial!',
                                text: 'Estado cambio Correctamente.',
                                position: 'top-center',
                                loaderBg: '#00c292',
                                icon: 'success',
                                hideAfter: 4500,
                                stack: 6
                            });

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


    function verHistorial(idExistencia) {
        var parameters = {
            "id_existencia": idExistencia,
            "action": "ver_productos"
        }

        $.ajax({
            data: parameters,
            url: 'ajax/productoAjax.php',
            type: 'post',
            success: function(response) {

                console.log(response);
                const respuesta = JSON.parse(response);

                console.log(respuesta);

                var table1 = $("#zone-historial");
                table1.empty();

                var theTable1 = `<table id="dataTableHistory" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>SOLICITUD</th>
                                                    <th>CLIENTE</th>
                                                    <th>ESTADO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="row-${respuesta[0].id_solicitud}">
                                                    <td>${respuesta[0].id_solicitud}</td>
                                                    <td><a href="?page=solicitud&id_solicitud=${respuesta[0].id_solicitud}">#${respuesta[0].id_solicitud}</a></td>
                                                    <td><a href="?page=prospecto&id=${respuesta[0].id_prospecto}">${respuesta[0].prospecto_cedula}-${respuesta[0].prospecto_nombre}&nbsp;${respuesta[0].prospecto_apellidos}</a></td>
                                                    <td><span class="label label-info">${respuesta[0].mostrar}</span></td>
                                                </tr>
                                            </tbody> 
                                    </table>`;

                table1.append(theTable1);

                tableHistory = $('#dataTableHistory').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'print'
                    ],
                    responsive: true,
                    "paging": false,
                    "order": [
                        [0, "desc"]
                    ]
                });
                tableHistory.column(0).visible(false);

                $("#historial-modal").modal("show");

            }
        });
    }

    function reportarGarantia(idExistencia) {
        console.log('hola tarolas');
    }
</script>