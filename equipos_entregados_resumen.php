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

    </script>

<?

} else {

    include '401error.php';
}

?>