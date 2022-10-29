<?

if (profile(40, $id_usuario)) {

    /*

      id_estado_solicitud = 1 // en ruta
      id_estado_solicitud = 2 // entregado pdte.por pagar
      id_estado_solicitud = 3 // devolucion
      id_estado_solicitud = 4 // pagado

    */

    $en_ruta = execute_scalar("SELECT COUNT(entregas_servientrega.id) AS total FROM entregas_servientrega LEFT JOIN solicitudes ON entregas_servientrega.id_solicitud = solicitudes.id WHERE entregas_servientrega.id_estado_solicitud = 1 AND entregas_servientrega.del = 0 AND solicitudes.del = 0");

    $pdte_por_pagar = execute_scalar("SELECT COUNT(entregas_servientrega.id) AS total FROM entregas_servientrega LEFT JOIN solicitudes ON entregas_servientrega.id_solicitud = solicitudes.id WHERE entregas_servientrega.id_estado_solicitud = 2 AND entregas_servientrega.del = 0 AND solicitudes.del = 0");

    $devolucion = execute_scalar("SELECT COUNT(entregas_servientrega.id) AS total FROM entregas_servientrega LEFT JOIN solicitudes ON entregas_servientrega.id_solicitud = solicitudes.id WHERE entregas_servientrega.id_estado_solicitud = 3 AND entregas_servientrega.del = 0 AND solicitudes.del = 0");

    $pagado = execute_scalar("SELECT COUNT(entregas_servientrega.id) AS total FROM entregas_servientrega LEFT JOIN solicitudes ON entregas_servientrega.id_solicitud = solicitudes.id WHERE entregas_servientrega.id_estado_solicitud = 4 AND entregas_servientrega.del = 0 AND solicitudes.del = 0");


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

            <!-- update orders section -->
            <div class="row filters-space">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body">

                            <div class="tab-content">

                                <form class="smart-form" name="filtrosReporte1" id="filtrosReporte1" method="post" action="" data-ajax="false">

                                    <div class="row pt-3">

                                        <div class="col-md-6">

                                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>

                                        </div>

                                    </div>

                                    <br>


                                    <hr class="m-t-0 m-b-40">

                                    <div class="row pt-3">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Fecha Inicio:</label>

                                                <div class="input-group">

                                                    <div class="input-group-append">

                                                        <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>

                                                    </div>

                                                    <input id="fecha_inicio" name="fecha_inicio" class="form-control date_start_filter" placeholder="Seleccione fecha de inicio">

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Fecha Final:</label>

                                                <div class="input-group">

                                                    <div class="input-group-append">

                                                        <span class="input-group-text"><i class="fas fa-hourglass-end"></i></span>

                                                    </div>

                                                    <input id="fecha_final" name="fecha_final" class="form-control date_end_filter" placeholder="Seleccione fecha final">


                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                                            <button type="submit" style="float: left; margin-left: 0px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-success">Generar</button>
                                            <button type="button" style="float: rigth; margin-left: 5px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-danger" onclick="limpiarCampos()">Limpiar</button>
                                            <!--<button type="button" style="float: left; margin-left: 20px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-success" onclick="queryAllFacilities()">All Facilities</button>-->

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body" id="zona-impresion">
                            <div id="botones">
                                <a href="javascript:void(0);" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;" onclick="imprimirResumen()">Imprimir Resumen</a>
                            </div>
                            <h4 class="card-title">Resumen de entregas Servientrega.</h4>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">EN RUTA:</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content" style="font-weight: bold;"><?=$en_ruta?></P>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">PDTE POR PAGAR:</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content" id="cifin_texto" style="font-weight: bold;"><?=$pdte_por_pagar?></P>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">PAGADOS:</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content" style="font-weight: bold;"><?=$pagado?></P>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col p-6">
                                    <h4 class="card-subtitle text-center font-weight-bold">DEVOLUCIONES:</h6>
                                </div>
                                <div class="col order-12 p-6">
                                    <P class="text-left card-content" style="font-weight: bold;"><?=$devolucion?></P>
                                </div>
                            </div>
                            <hr>
                            <br>
                            <br>
                            <div class="row" id="boton-actualizar">
                                <div class="col-md-12">
                                    <a href="javascript:void(0);" class="btn waves-effect waves-light btn-lg btn-info" style="float: right;" onclick="actualizarReporte()">ACTUALIZAR</a>
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



    <script>
        'use strict';

        var space1 = $(".filters-space");

        var space2 = $("#table-space-report1");

        function waitMoment() {

            $('.response').show();
        }

        $("#filtrosReporte1").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("filtrosReporte1"));

            const action = "reporte_servientrega_resumen";

            formData.append('action', action);

            SelectReport1BD(formData);

        });

        function SelectReport1BD(dates) {

            /** Call to Ajax **/

            // create the object
            const xhr = new XMLHttpRequest();

            xhr.addEventListener("load", waitMoment());
            // open conection
            xhr.open('POST', 'ajax/reportesAjax.php', true);
            // pass Info
            xhr.onload = function() {
                //the conection is success
                //console.log(this.status);
                if (this.status === 200) {

                    $('.response').hide();
                    /* in case of problem of json object, use this code console.log(xhr.responseText); */
                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        //space1.hide();
                        space2.show();

                        $("#pdte_validar_texto").html('');
                        $("#pdte_validar_texto").html(respuesta.pdte_validar);
                        $("#entregado_texto").html('');
                        $("#entregado_texto").html(respuesta.entregados);
                        $("#pdte_comprobante_texto").html('');
                        $("#pdte_comprobante_texto").html(respuesta.pdte_comprobante);
                        $("#pdte_entregar_texto").html('');
                        $("#pdte_entregar_texto").html(respuesta.pdte_entregar);
                        $("#rechazados_texto").html('');
                        $("#rechazados_texto").html(respuesta.rechazados);
                        $("#venta_no_realizada_texto").html('');
                        $("#venta_no_realizada_texto").html(respuesta.ventas_no_realizadas);

                    }else if(respuesta.response == "falta_fecha"){

                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Selecciona las dos fechas',
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

                } else {

                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'There is an error in the operation',
                        showConfirmButton: false,
                        timer: 2000
                    })

                    //setTimeout("location.reload()", 2000);
                }
            }
            // send dates
            xhr.send(dates)

        }

        function imprimirResumen() {

            var zone = $("#zona-impresion");
            $("#botones").prop('style', 'display: none');
            $("#boton-actualizar").prop('style', 'display: none');

            zone.printThis({
                debug: false,
                importCSS: true,
                printContainer: true,
                loadCSS: "assets/boostrap/dist/css/boostrap.min.css",
                pageTitle: "Resumen Servientrega",
                removeInline: false,
                "afterPrint": function (){
                    $("#botones").prop('style', 'display: block');
                    $("#boton-actualizar").prop('style', 'display: block');
                }
            });

        }

        function actualizarReporte(){
            setTimeout("location.reload()", 500);
        }

        function limpiarCampos(){

            $("#fecha_inicio").val('');
            $("#fecha_final").val('');

        }

    </script>

<?

} else {

    include '401error.php';
}

?>