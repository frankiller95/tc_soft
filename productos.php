<?

if (profile(1, $id_usuario) == 1) {

    $creador_existencias = profile(45, $id_usuario);

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
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarProductoModal()">Nuevo Producto</button>
                            <h4 class="card-title">Productos</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableProductos" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>MARCA</th>
                                            <th>MODELO</th>
                                            <th>PRECIO VENTA</th>
                                            <? if ($creador_existencias == 1) { ?>
                                                <th>STOCK</th>
                                            <? } ?>
                                            <th>DISPONIBLES</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT productos.id as producto_id, marcas.marca_producto, productos.id_modelo, modelos.nombre_modelo, capacidades_telefonos.capacidad, (SELECT COUNT(productos_stock.id) AS total FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE inventario.id_producto = producto_id AND productos_stock.del = 0) AS stock,  (SELECT COUNT(productos_stock.id) AS total2 FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE inventario.id_producto = producto_id AND productos_stock.id_estado_producto = 1 AND productos_stock.del = 0) AS disponibles, modelos.id_capacidad, modelos.precio_venta FROM inventario LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE productos.del = 0";
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_producto = $row1['producto_id'];
                                            $marca = $row1['marca_producto'];
                                            $modelo_producto = $row1['nombre_modelo'];
                                            $precio_venta = $row1['precio_venta'];
                                            $stock = $row1['stock'];
                                            $capacidad = $row1['capacidad'];

                                            $disponibles = $row1['disponibles'];

                                        ?>
                                            <tr class="row-<?= $id_producto ?>">
                                                <td><?= $id_producto ?></td>
                                                <td><?= $marca ?></td>
                                                <td><?= $modelo_producto . ' ' . $capacidad ?></td>
                                                <td><?= number_format($precio_venta, 0, '.', '.') ?></td>
                                                <? if ($creador_existencias == 1) { ?>
                                                    <td><?= $stock ?></td>
                                                <? } ?>
                                                <td><?= $disponibles ?></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <? if ($creador_existencias == 1) {
                                                    ?>
                                                        <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarProducto(<?= $id_producto ?>)"><i class="mdi mdi-pencil"></i></a>
                                                        <a href="?page=producto&id_producto=<?= $id_producto ?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver productos"><i class="fas fa-mobile"></i></a>
                                                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarProducto(<?= $id_producto ?>)"><i class="fas fa-trash-alt"></i></a>
                                                    <? } ?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Plantilla" onClick="crearPlantilla(<?= $id_producto ?>)"><i class="fas fa-clipboard"></i></a>
                                                </td>
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


    <!-- Add producto modal -->
    <div id="registrar-producto-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_productos"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="registrarProdutoForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-6">
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
                                    <label>Terminos del producto:</label>
                                    <div class="input-group">
                                        <?
                                        $query = "SELECT id AS id_termino, numero_meses FROM terminos_prestamos WHERE del = 0 ORDER BY numero_meses";
                                        $result = qry($query);
                                        while ($row = mysqli_fetch_array($result)) {

                                            $id_termino = $row['id_termino'];
                                            $numero_meses = $row['numero_meses'];

                                        ?>
                                            <fieldset class="controls">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="<?= $id_termino ?>" name="terminos_p[]" id="termino_<?= $id_termino ?>" class="custom-control-input all-check">
                                                    <label class="custom-control-label" for="termino_<?= $id_termino ?>"><?= $numero_meses . '&nbsp;MESES' ?></label>
                                                </div>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Frecuencias de pago:</label>
                                    <div class="input-group">
                                        <?
                                        $query = "SELECT id AS id_frecuencia, frecuencia FROM frecuencias_pagos WHERE del = 0 ORDER BY frecuencia";
                                        $result = qry($query);
                                        while ($row = mysqli_fetch_array($result)) {

                                            $id_frecuencia = $row['id_frecuencia'];
                                            $frecuencia = $row['frecuencia'];

                                        ?>
                                            <fieldset class="controls">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="<?= $id_frecuencia ?>" name="frecuencias_p[]" id="frecuencia_<?= $id_frecuencia ?>" class="custom-control-input all-check">
                                                    <label class="custom-control-label" for="frecuencia_<?= $id_frecuencia ?>"><?= $frecuencia ?></label>
                                                </div>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Resultados Cifin:</label>
                                    <div class="input-group">
                                        <?
                                        $query = "SELECT id AS id_cifin, estado FROM resultados_cifin WHERE del = 0 ORDER BY id ASC";
                                        $result = qry($query);
                                        while ($row = mysqli_fetch_array($result)) {

                                            $id_cifin = $row['id_cifin'];
                                            $estado = $row['estado'];

                                        ?>
                                            <fieldset class="controls">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" value="<?= $id_cifin ?>" name="resultado_c" id="cifin_<?= $id_cifin ?>" class="custom-control-input" onclick="onlyOne(this)">
                                                    <label class="custom-control-label" for="cifin_<?= $id_cifin ?>"><?= $estado ?></label>
                                                </div>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                        <? } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_producto" id="id_producto" value="">
                        <input type="hidden" name="action" id="action_producto" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <!-- Add plantilla modal -->
    <div id="insertar-plantilla-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_plantilla_producto"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="plantillaProductoForm" method="post">
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
                                    <label>Plantilla:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="plantilla_producto" id="plantilla_producto" rows="10" style="text-transform:uppercase;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_producto" id="id_producto_plantilla" value="">
                        <input type="hidden" name="action" id="action_producto_plantilla" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="inputCopy" onclick="copyClipboard()" onmouseout="outFunc()"><i class="fas fa-clipboard"></i>&nbsp;<span class="tooltiptext" id="myTooltip">Copiar al portapeles!</span></button>
                        <?if($creador_existencias == 1){?>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <?}?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <script>
        function registrarProductoModal() {
            $("#titulo_productos").html("Ingresar Producto");
            $("#dispositivos").val("placeholder");
            $("#dispositivos").trigger("change");
            $("#id_producto").val('');
            $(".all-check").prop('checked', true);
            $("#cifin_" + 1).prop('checked', false);
            $("#cifin_" + 2).prop('checked', false);
            $("#cifin_" + 3).prop('checked', true);
            $("#action_producto").val('insertar');
            $("#registrar-producto-modal").modal("show");
        }

        function onlyOne(checkbox) {

            var checkboxes = document.getElementsByName('resultado_c')
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false
            })

        }


        $("#registrarProdutoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarProdutoForm"));

            const action = document.querySelector("#action_producto").value;

            if (action == "insertar") {

                insertProductosDB(formData);

            } else {

                updateProductosDB(formData);

            }

        });

        function insertProductosDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/productosAjax.php', true);
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
                            title: 'Producto registrado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        modeloCapacidadCifin = `${respuesta.modelo_producto+' '+respuesta.capacidad}&nbsp;<span class="label label-info">${respuesta.estado_cifin}</span>`;

                        tablaProductos.row.add([
                            respuesta.id_producto, respuesta.marca_nombre, modeloCapacidadCifin, respuesta.precio_venta, respuesta.stock, respuesta.disponibles, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarProducto(${respuesta.id_producto})"><i class="mdi mdi-pencil"></i></a>
                            <a href="?page=producto&id_producto=${respuesta.id_producto}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver productos"><i class="fas fa-mobile"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarProducto(${respuesta.id_producto})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_producto);

                        $("#registrar-producto-modal").modal("hide");

                    } else if (respuesta.response == "existe") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Este Inventario Ya Existe',
                            showConfirmButton: false,
                            timer: 2500

                        });


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


        function editarProducto(idProducto) {

            var parameters = {
                "id_producto": idProducto,
                "action": "select_productos"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/productosAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $("#titulo_productos").html("Editar Producto");
                    $("#dispositivos").val(respuesta[0].id_modelo);
                    $("#dispositivos").trigger("change");
                    $("#id_producto").val(idProducto);
                    $(".all-check").prop('checked', false);
                    respuesta[1].forEach(function(frecuencias, index) {

                        $("#frecuencia_" + frecuencias.id_frecuencia).prop('checked', true);

                    });
                    respuesta[2].forEach(function(terminos, index) {

                        $("#termino_" + terminos.id_termino).prop('checked', true);

                    });
                    $("#cifin_1").prop("checked", false);
                    $("#cifin_2").prop("checked", false);
                    $("#cifin_3").prop("checked", false);
                    $("#cifin_" + respuesta[0].id_resultado_cifin).prop('checked', true);
                    $("#action_producto").val('editar');
                    $("#registrar-producto-modal").modal("show");

                }

            });

        }




        function updateProductosDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/productosAjax.php', true);
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
                            title: 'Inventario Actualizado Correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaProductos.row(".row-" + respuesta.id_producto).remove().draw(false);

                        modeloCapacidadCifin = `${respuesta.modelo_producto+' '+respuesta.capacidad}&nbsp;<span class="label label-info">${respuesta.estado_cifin}</span>`;

                        tablaProductos.row.add([
                            respuesta.id_producto, respuesta.marca_nombre, modeloCapacidadCifin, respuesta.precio_venta, respuesta.stock, respuesta.disponibles, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarProducto(${respuesta.id_producto})"><i class="mdi mdi-pencil"></i></a>
                            <a href="?page=producto&id_producto=${respuesta.id_producto}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver productos"><i class="fas fa-mobile"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarProducto(${respuesta.id_producto})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_producto);

                        $("#registrar-producto-modal").modal("hide");

                    } else if (respuesta.response == "existe") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Este Inventario Ya Existe',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    } else if (respuesta.response == "entregados") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Producto con dispositivos entregados',
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
                            timer: 2500

                        });
                    }
                }

            }

            // send dates
            xhr.send(dates)
        }


        function eliminarProducto(idProducto) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para eliminar este producto!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {
                if (result.value) {
                    var parameters = {
                        "id_producto": idProducto,
                        "action": "eliminar"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/productosAjax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                tablaProductos.row(".row-" + respuesta.id_producto).remove().draw(false);

                                $.toast({
                                    heading: 'Genial!',
                                    text: 'Producto Eliminado Correctamente.',
                                    position: 'top-center',
                                    loaderBg: '#00c292',
                                    icon: 'success',
                                    hideAfter: 4500,
                                    stack: 6
                                });

                            } else if (respuesta.response == "existe") {

                                $.toast({

                                    heading: 'Error!',
                                    text: 'Equipos Asignados.',
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

        function crearPlantilla(idProducto) {

            var parameters = {
                "id_producto": idProducto,
                "action": "select_plantilla"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/productosAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        $("#plantilla_producto").val(respuesta.plantilla_producto);
                        $("#id_producto_plantilla").val(idProducto);
                        $("#action_producto_plantilla").val('crear_plantilla');
                        $("#titulo_plantilla_producto").text('Plantilla ' + respuesta.referencia_completa);
                        $("#insertar-plantilla-modal").modal("show");

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

        $("#plantillaProductoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("plantillaProductoForm"));

            insertPlantillaDB(formData);

        });

        function insertPlantillaDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/productosAjax.php', true);
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
                            title: 'Plantilla Actualizada Correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        $("#insertar-plantilla-modal").modal("hide");

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

        function copyClipboard() {
            var copyText = document.getElementById("plantilla_producto");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            var tooltip = document.getElementById("myTooltip");
            //tooltip.innerHTML = "Copied: " + copyText.value;
            tooltip.innerHTML = "Ready!";

        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copiar al portapeles!";
        }

    </script>

<?
} else {

    include '401error.php';
}

?>