<?

if (profile(35, $id_usuario) == 1) {

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
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarCotizacionModal()">Nueva Cotización</button>
                            <h4 class="card-title">Cotizaciones</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableCotizaciones" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>CLIENTE</th>
                                            <th>CONVENIO</th>
                                            <th>VENDEDOR</th>
                                            <th>DISPOSITIVO</th>
                                            <th>VALIDA HASTA</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT cotizaciones.id AS id_cotizacion, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, convenios.nombre_empresa, convenios.nit_empresa, usuarios.nombre, usuarios.apellidos, modelos.nombre_modelo, capacidades_telefonos.capacidad, marcas.marca_producto, DATE_FORMAT(cotizaciones.valido_hasta, '%m-%d-%Y') AS fecha_valida FROM `cotizaciones` LEFT JOIN prospectos ON cotizaciones.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN convenios ON cotizaciones.id_convenio = convenios.id LEFT JOIN usuarios ON cotizaciones.id_vendador = usuarios.id LEFT JOIN modelos ON cotizaciones.id_dispositivo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE cotizaciones.del = 0";

                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_cotizacion = $row1['id_cotizacion'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $prospecto_nombre = $row1['prospecto_nombre'];
                                            $prospecto_apellidos = $row1['prospecto_apellidos'];
                                            $nombre_empresa = $row1['nombre_empresa'];
                                            $nit_empresa = $row1['nit_empresa'];
                                            $vendedor = $row1['nombre'] . ' ' . $row1['apellidos'];
                                            $nombre_modelo = $row1['nombre_modelo'];
                                            $capacidad = $row1['capacidad'];
                                            $marca_producto = $row1['marca_producto'];
                                            $fecha_valida = $row1['fecha_valida'];

                                        ?>

                                            <tr class="row-<?= $id_cotizacion ?>">
                                                <td><?= $prospecto_cedula . ' ' . $prospecto_nombre . ' ' . $prospecto_apellidos ?></td>
                                                <td><?= $nit_empresa . ' - ' . $nombre_empresa ?></td>
                                                <td><?= $vendedor ?></td>
                                                <td><?= $marca_producto . ' ' . $nombre_modelo . ' ' . $capacidad ?></td>
                                                <td><?= $fecha_valida ?></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Cotización" onClick="editarCotizacion(<?= $id_cotizacion ?>)"><i class="mdi mdi-pencil"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Imprimir Cotización" onClick="imprimirCotizacion(<?= $id_cotizacion ?>)"><i class="fas fa-print"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Cotizacion" onClick="eliminarCotizacion(<?= $id_cotizacion ?>)"><i class="fas fa-trash-alt"></i></a>
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




    <!--add cotizacion Modal-->
    <div id="add-cotizacion-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_cotizacion_modal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" id="addCotizacionesForm" method="post" action="" data-ajax="false">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prospectos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="prospecto_coti" id="prospecto_coti" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled selected>Seleccionar Prospecto</option>
                                            <?php

                                            $condition = '';

                                            if (profile(14, $id_usuario) == 0) {

                                                $condition = " AND prospectos.id_responsable_interno = $id_usuario ";
                                            }

                                            $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS nombre_prospecto FROM `prospectos` LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.del = 0 $condition";
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
                                        <br>
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left: 10px; margin-top: 5px;" onclick="nuevoProspectoModal()">Crear Nuevo</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CONVENIO:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="convenio_coti" id="convenio_coti" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled selected>Seleccionar Convenio</option>
                                            <?php

                                            $query = "SELECT convenios.id AS id_convenio, convenios.nit_empresa, convenios.nombre_empresa FROM `convenios` WHERE convenios.del = 0";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {

                                                $id_convenio = $row['id_convenio'];
                                                $nit_empresa = $row['nit_empresa'];
                                                $nombre_empresa = $row['nombre_empresa'];

                                            ?>
                                                <option value="<?= $id_convenio ?>"><?= $nit_empresa . ' - ' . $nombre_empresa ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Dispositivo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="dispositivo_coti" id="dispositivo_coti" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
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
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha valida:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="fecha_valida" name="fecha_valida" class="form-control min-date" placeholder="Seleccionar fecha Valida" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Descuento:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="descuento_coti" id="descuento_coti" placeholder="Ingresa Descuento" autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="texto_adicional_coti">Texto adicional:</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="texto_adicional_coti" id="texto_adicional_coti" rows="5" placeholder="Escribe texto adicional"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action_coti" value="" />
                        <input type="hidden" name="id_cotizacion" id="id_cotizacion" >
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-info" style="float: right">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->


    <script>

        function registrarCotizacionModal() {

            $("#titulo_cotizacion_modal").html("Nueva Cotización");
            $("#prospecto_coti").val('placeholder');
            $("#prospecto_coti").trigger('change');
            $("#convenio_coti").val('placeholder');
            $("#convenio_coti").trigger('change');
            $("#dispositivo_coti").val('placeholder');
            $("#dispositivo_coti").trigger('change');
            $("#fecha_valida").val('');
            $("#descuento_coti").val('');
            $("#texto_adicional_coti").val('');
            $("#id_cotizacion").val('');
            $("#action_coti").val('insertar_cotizacion');
            $("#add-cotizacion-modal").modal("show");

        }


        $("#addCotizacionesForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("addCotizacionesForm"));

            const action = document.querySelector("#action_coti").value;

            if (action == "insertar_cotizacion") {

                insertCotiDB(formData);

            } else {

                updateCotiDB(formData);

            }

        });

        function insertCotiDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/cotizacionesAjax.php', true);
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
                            title: 'Cotizacion creada Correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaPuntosGane.row.add([
                            respuesta.id_punto_gane, respuesta.codigo_punto, respuesta.nombre_punto, respuesta.direccion_punto, respuesta.barrio_punto, 0, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Punto Gane" onClick="editarPuntoGane(${respuesta.id_punto_gane})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Punto Gane" onClick="eliminarPuntoGane(${respuesta.id_punto_gane}, 0)"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_punto_gane);

                        $("#add-punto-modal").modal("hide");

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


        function editarPuntoGane(idPuntoGane) {

            var parameters = {
                "id_punto_gane": idPuntoGane,
                "action": "select_punto_gane"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/bibliotecasAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $("#titulo_puntos_gane").html("Editar Punto Gane");
                    $("#codigo_punto").val(respuesta[0].COD);
                    $("#nombre_punto").val(respuesta[0].AGENCIA);
                    $("#direccion_punto").val(respuesta[0].DIRECCION);
                    $("#barrio_punto").val(respuesta[0].BARRIO);
                    $("#id_punto_gane").val(respuesta[0].id_punto_gane);
                    $("#action_punto_gane").val('editar_punto_gane');
                    $("#add-punto-modal").modal("show");

                }

            });

        }

        function updatePuntoGaneDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/bibliotecasAjax.php', true);
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
                            title: 'Punto Ingresado Correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaPuntosGane.row(".row-" + respuesta.id_punto_gane).remove().draw(false);

                        tablaPuntosGane.row.add([
                            respuesta.id_punto_gane, respuesta.codigo_punto, respuesta.nombre_punto, respuesta.direccion_punto, respuesta.barrio_punto, respuesta.total_digitadores, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Punto Gane" onClick="editarPuntoGane(${respuesta.id_punto_gane})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Punto Gane" onClick="eliminarPuntoGane(${respuesta.id_punto_gane}, ${respuesta.total_digitadores})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_punto_gane);

                        $("#add-punto-modal").modal("hide");

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

        function eliminarPuntoGane(idPuntoGane, totalDigitadores) {

            Swal.fire({
                title: 'Por favor confirmar para eliminar este Punto Gane!',
                text: "Si eliminas el punto los usuarios asociados seran desactivados.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {
                if (result.value) {
                    var parameters = {
                        "id_punto_gane": idPuntoGane,
                        "total_digitadores": totalDigitadores,
                        "action": "eliminar_punto_gane"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/bibliotecasAjax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);

                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                tablaPuntosGane.row(".row-" + respuesta.id_punto_gane).remove().draw(false);

                                $.toast({
                                    heading: 'Genial!',
                                    text: 'Punto eliminado Correctamente.',
                                    position: 'top-center',
                                    loaderBg: '#00c292',
                                    icon: 'success',
                                    hideAfter: 3000,
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
    </script>

<?
} else {

    include '401error.php';
}

?>