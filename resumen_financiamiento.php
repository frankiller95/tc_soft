<?

if (isset($_GET['id']) && profile(3, $id_usuario) == 1) {

    $id_solicitud = $_GET['id'];

    $prospecto_nombre = execute_scalar("SELECT CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE solicitudes.id = $id_solicitud");

    $id_estado_solicitud = execute_scalar("SELECT id_estado_solicitud FROM solicitudes WHERE id = $id_solicitud");

    $id_estado_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

    $id_cifin_producto = execute_scalar("SELECT resultados_cifin.id AS id_cifin_producto FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN cifin_productos ON productos.id = cifin_productos.id_producto LEFT JOIN resultados_cifin ON cifin_productos.id_resultado_cifin = resultados_cifin.id WHERE solicitudes.id = $id_solicitud");

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
                    <h4 class="text-themecolor"><?= ucwords(str_replace("_", " ", $page)) . '&nbsp;<span class="text-danger">#' . $id_solicitud . '</span>' ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="?page=solicitudes">Solicitudes</a></li>
                            <li class="breadcrumb-item active"><?= ucwords(str_replace("_", " ", $page)) . '&nbsp;<span class="text-danger">#' . $id_solicitud . '</span>' ?></li>
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
                        <div class="card-body" id="zona-impresion">
                            <div id="botones">
                            <?
                            if ($id_estado_solicitud <> 7 || $id_estado_solicitud <> 8) { ?>
                                <a href="?page=solicitud&id_solicitud=<?= $id_solicitud ?>" class="btn waves-effect waves-light btn-lg btn-success" style="float: right">Editar Solicitud</a>
                                <a href="javascript:void(0);" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;" onclick="cambiarResultadoCifin(<?= $id_estado_cifin ?>, <?= $id_solicitud ?>, <?= $id_cifin_producto ?>)">Cambiar Cifin</a>
                                <a href="javascript:void(0);" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;" onclick="imprimirResumen()">Imprimir Resumen</a>
                            <? } ?>
                            </div>
                            <h4 class="card-title">Resumen de Financiamiento.</h4>
                            <br>
                            <br>
                            <?
                            $query = "SELECT modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, solicitudes.precio_producto, porcentajes_iniciales.porcentaje, terminos_prestamos.numero_meses, frecuencias_pagos.frecuencia, solicitudes.id_frecuencia_pago, resultados_cifin.estado AS estado_cifin, resultados_solicitud_cifin.id_estado_cifin FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN terminos_prestamos ON solicitudes.id_terminos_prestamo = terminos_prestamos.id LEFT JOIN frecuencias_pagos ON solicitudes.id_frecuencia_pago = frecuencias_pagos.id LEFT JOIN resultados_solicitud_cifin ON resultados_solicitud_cifin.id_solicitud = solicitudes.id LEFT JOIN resultados_cifin ON resultados_solicitud_cifin.id_estado_cifin = resultados_cifin.id WHERE solicitudes.id = $id_solicitud";
                            $result = qry($query);
                            while ($row = mysqli_fetch_array($result)) {

                                $nombre_modelo = $row['nombre_modelo'];
                                $marca_producto = $row['marca_producto'];
                                $capacidad = $row['capacidad'];
                                $precio_producto = $row['precio_producto'];
                                $porcentaje = $row['porcentaje'];

                                $id_frecuencia_pago = $row['id_frecuencia_pago'];

                                $valor_inicial = ($porcentaje * $precio_producto) / 100;

                                $valor_credito = $precio_producto - $valor_inicial;

                                $numero_cuotas = $row['numero_meses'];

                                $frecuencia = $row['frecuencia'];

                                $estado_cifin = $row['estado_cifin'];

                                $id_estado_cifin = $row['id_estado_cifin'];

                                if ($id_frecuencia_pago == 2) {

                                    $numero_cuotas = $numero_cuotas * 2;
                                } else if ($frecuencia == 3) {

                                    $numero_cuotas = $numero_cuotas * 4;
                                }

                                $query2 = "SELECT * FROM arandelas_creditos WHERE del = 0";
                                $result2 = qry($query2);
                                while ($row2 = mysqli_fetch_array($result2)) {

                                    $estudio_credito = $row2['estudio_credito'];
                                    $fianza = $row2['fianza'];
                                    $interaccion_tecnologica = $row2['interaccion_tecnologica'];
                                    $beriblock = $row2['beriblock'];
                                    $seguro_pantalla = $row2['seguro_pantalla'];
                                    $domicilio = $row2['domicilio'];
                                    $iva_arandelas = $row2['iva_arandelas'];
                                    $tasa_interes_usura = $row2['tasa_interes_usura'];
                                }

                                $arandelas_precio_normal = $estudio_credito + $interaccion_tecnologica + $beriblock + $domicilio;
                                $precio_seguro_pantalla = ($seguro_pantalla * $precio_producto) / 100;


                                $precio_fianza = ($fianza * $valor_credito) / 100;
                                $arandelas_total = $arandelas_precio_normal + $precio_seguro_pantalla + $precio_fianza;
                                $precio_iva_arandelas = ($iva_arandelas * $arandelas_total) / 100;
                                $arandelas_total = $arandelas_total + $precio_iva_arandelas;
                                $valor_credito_interes = $valor_credito + $arandelas_total;

                                $valor_cuota = $valor_credito_interes / $numero_cuotas;


                            ?>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Nombre Completo:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><?= $prospecto_nombre ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Resultado Cifin:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content <? if ($id_estado_cifin == 1 || $id_estado_cifin == 2) { ?>text-success<? } else { ?>text-danger<? } ?>" id="cifin_texto" style="font-weight: bold;"><?= $estado_cifin ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">IdNumber:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><?= $id_solicitud ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Celular:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><?= $marca_producto . ' ' . $nombre_modelo . ' ' . $capacidad ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Precio de Celular:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><span class="text-danger" style="font-weight: bold;">$</span>&nbsp;<?= number_format($precio_producto, 0, '.', '.') ?></P>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Abono inicial (<?= $porcentaje ?>%):</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><span class="text-danger" style="font-weight: bold;">$</span>&nbsp;<?= number_format($valor_inicial, 0, '.', '.') ?></P>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Valor de Crédito:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><span class="text-danger" style="font-weight: bold;">$</span>&nbsp;<?= number_format($valor_credito, 0, '.', '.') ?></P>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Monto total a pagar en cuotas, capital, intereses y fianza:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><span class="text-danger" style="font-weight: bold;">$</span>&nbsp;<?= number_format($valor_credito_interes, 0, '.', '.') ?></P>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Cuota&nbsp;<?= $frecuencia ?>&nbsp;(incluye capital, intereses y fianza):</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><span class="text-danger" style="font-weight: bold;">$</span>&nbsp;<?= number_format($valor_cuota, 0, '.', '.') ?></P>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <!--<div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">Pago mensual principal e intereses al 25% E.A:</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content"><?= number_format($valor_cuota_25, 0, '.', '.') ?></P>
                                </div>
                            </div>
                            <hr>
                            <hr>
                            <div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">Pago de Fianza mensual *:</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content"><?= number_format($valor_intereses, 0, '.', '.') ?></P>
                                </div>
                            </div>
                            <hr>
                            <hr>
                            <div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">Iva Fianza (19%):</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content"><?= number_format($valor_iva, 0, '.', '.') ?></P>
                                </div>
                            </div>
                            <hr>
                            <hr>-->
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Cantidad de Cuotas:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><?= $numero_cuotas ?></P>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">Ultima Fecha de pago:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content" style="font-weight: bold;"><?= 'N/A' ?></P>
                                    </div>
                                </div>
                                <hr>
                            <? } ?>
                            <br>
                            <br>
                            <div class="row" id="boton-salir">
                                <div class="col-md-12">
                                    <a href="?page=solicitudes" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right;">Salir</a>
                                </div>
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
                        <input type="hidden" name="id_cifin_producto" id="id_cifin_producto">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <script>
        'use strict';

        function cambiarResultadoCifin(idResultadoC, idSolicitud, idCifinProducto) {

            $("#resultados_cifin").val(idResultadoC);
            $("#resultados_cifin").trigger("change");
            $("#id_solicitud_cifin").val(idSolicitud);
            $("#id_cifin_producto").val(idCifinProducto);
            $("#resultadosc-modal").modal("show");

        }

        $("#resultadosCifinForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            const action = "cambiar_cifin";

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("resultadosCifinForm"));
            formData.append("action", action);

            cambiarCifinDB(formData);

        });

        function cambiarCifinDB(dates) {
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
                            title: 'Resultado Cambiado',
                            showConfirmButton: false,
                            timer: 2500

                        });


                        if (respuesta.id_cifin != respuesta.id_cifin_old) {


                            $("#cifin_texto").removeClass('danger');
                            $("#cifin_texto").removeClass('success');

                            if (respuesta.id_cifin == 1 || respuesta.id_cifin == 2) {
                                $("#cifin_texto").addClass('success');
                            } else {
                                $("#cifin_texto").addClass('danger');
                            }

                        }

                        $("#cifin_texto").html(respuesta.estado);

                        $("#resultadosc-modal").modal("hide");

                    } else if (respuesta.response == "afecta_cel") {

                        Swal.fire({
                            title: 'Estas seguro?',
                            text: "Si cambias por este resultado debes seleccionar nuevamente el dispositivo!",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Estoy seguro!'
                        }).then((result) => {

                            console.log(result.value);

                            if (result.value == true) {

                                var parameters = {
                                    "id_solicitud": respuesta.id_solicitud,
                                    "id_cifin": respuesta.id_cifin,
                                    "action": "confir_cambiar_cifin"
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

                                            Swal.fire({

                                                position: 'top-end',
                                                type: 'success',
                                                title: 'Resultado Cambiado',
                                                showConfirmButton: false,
                                                timer: 2500

                                            });

                                                $("#cifin_texto").removeClass('danger');
                                                $("#cifin_texto").removeClass('success');

                                                if (respuesta.id_cifin == 1 || respuesta.id_cifin == 2) {
                                                    $("#cifin_texto").addClass('success');
                                                } else {
                                                    $("#cifin_texto").addClass('danger');
                                                }

                                            $("#cifin_texto").html(respuesta.estado);

                                            $("#resultadosc-modal").modal("hide");

                                            setTimeout(function(){location.href="?page=solicitudes"} , 3500);

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

                            } else {

                                return 0;

                            }
                        });


                        $("#resultadosc-modal").modal("hide");

                    } else if (respuesta.response == "el_mismo") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Resultado Cambiado',
                            showConfirmButton: false,
                            timer: 2500

                        });

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

        function imprimirResumen(){

            var zone = $("#zona-impresion");
            $("#botones").prop('style', 'display: none');
            $("#boton-salir").prop('style', 'display: none');

            zone.printThis({
                debug: false,              
                importCSS: true,           
                printContainer: true,      
                loadCSS: "assets/boostrap/dist/css/boostrap.min.css", 
                pageTitle: "Resumen Financiamiento",             
                removeInline: false,
                "afterPrint": function (){
                    $("#botones").prop('style', 'display: block');
                    $("#boton-salir").prop('style', 'display: block');
                }        
            });

        }

    </script>

<?

} else {

    include '401error.php';
}

?>