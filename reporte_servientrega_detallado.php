<? if (profile(40, $id_usuario) == 1) {

    /*

      id_estado_solicitud = 1 // en ruta
      id_estado_solicitud = 2 // entregado pdte.por pagar
      id_estado_solicitud = 3 // devolucion
      id_estado_solicitud = 4 // pagado

    */

?>

    <!-- ============================================================== -->

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



            <!-- row details -->

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

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Seleccione Estado Servientrega:</label>

                                                <div class="input-group">

                                                    <select class="form-control select2Class" name="id_estado_servientrega" id="id_estado_servientrega" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                        <option value="placeholder" disabled selected>Seleccionar Estado</option>
                                                        <option value="all">Todos los Estados</option>
                                                        <option value="1">EN RUTA</option>
                                                        <option value="2">ENTREGADO-PDTE POR PAGAR</option>
                                                        <option value="3">DEVOLUCIÓN</option>
                                                        <option value="4">PAGADO</option>
                                                    </select>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

                                            <button type="submit" style="float: left; margin-left: 0px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-success">Generar</button>

                                            <!--<button type="button" style="float: left; margin-left: 20px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-success" onclick="queryAllFacilities()">All Facilities</button>-->

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Row -->
            <div class="row" id="table-space-report1" style="display: none;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" id="titulo_reporte">SOLICITUDES</h4>
                            <div class="table-responsive m-t-40 table_reporte_servientrega">

                            </div>
                            <br>
                            <br>
                            <button type="button" class="btn btn-info" style="float: right; margin-left: 5px;" onclick="cerrarReporte()">Cerrar</button>
                        </div>
                    </div>
                </div>
                <div id="galeries" class="hidden">

                </div>
            </div>

        </div>
    </div>

    <script>
        'use strict';

        var table1 = $(".table_reporte_servientrega");

        var space1 = $(".filters-space");

        var space2 = $("#table-space-report1");

        var titulo = $("#titulo_reporte");

        function waitMoment() {

            $('.response').show();
        }

        $("#filtrosReporte1").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("filtrosReporte1"));

            //const action = "reporte_prospectos";
            const action = "reporte_servientrega_detallado";

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

                        table1.empty();
                        space1.hide();
                        space2.show();

                        titulo.html(`Entregas Servientrega.`);

                        var theTable = `<table id="dataTableServientregasReport" class="table-patient table display table-bordered table-striped no-wrap">
                        <thead>
                            <tr>
                                <th># GUIA</th>
                                <th>FECHA ENVIO</th>
                                <th>CLIENTE</th>
                                <th>CEDULA</th>
                                <th>TELEFONO</th>
                                <th>REF. EQUIPO</th>
                                <th>INCIAL</th>
                                <th>FINANCIERA</th>
                                <th>SOBREFLETE</th>
                                <th>BOLSA</th>
                                <th>TOTAL GASTO TCSHOP</th>
                                <th>DESTINO</th>
                                <th>FECHA ENTREGA SERVIENTREGA<th>
                                <th>ESTADO CLIENTE</th>
                                <th>ESTADO TCSHOP</th>
                                <th>MES</th>
                                <th>ASESOR</th>
                                <th>CONSIG</th>
                                <th>MEDIO</th>
                                <th>DIF</th>
                                <th></th>                                
                            </tr>
                        </thead>
                        <tbody>`;

                            var tableTr = '';
                            var botonesServientrega = '';

                            respuesta[0].forEach(function(servientregas, index) {

                                if (servientregas.id_estado_solicitud == 1) {

                                    botonesServientrega = `<a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Entregado Servientrega" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'confirmar_entregado_servientrega')"><i class="fas fa-truck-loading"></i></a>
                                    <a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Devolución Servientrega" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'confirmar_devolucion_servientrega')"><i class="fas fa-backspace"></i></a>`;

                                } else if (servientregas.id_estado_solicitud == 2) {

                                    botonesServientrega = `<a class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pagar Servientrega" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'pagar_servientrega')"><i class="fas fa-dollar-sign"></i></a>
                                    <a class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Volver En ruta" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'retornar_en_ruta')"><i class="fas fa-arrow-left"></i></a>`;

                                } else if (servientregas.id_estado_solicitud == 3) {

                                    botonesServientrega = `<a class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Asignar entrega nuevamente" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'volver_nuevamente_entrega')"><i class="fas fa-arrow-left"></i></a>
                                    <a class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Volver En ruta" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'retornar_en_ruta')"><i class="fas fa-arrow-left"></i></a>`;

                                } else if (servientregas.id_estado_solicitud == 4) {

                                    botonesServientrega = `<a class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Volver pdte. por pagar" onclick="procesosReporteServientrega(${servientregas.id_solicitud}, 'volver_pdte_por_pagar')"><i class="fas fa-arrow-left"></i></a>`;
                                }

                                tableTr += `<tr class="row-${servientregas.id_servientrega}">
                                            <td>${servientregas.id_servientrega}</td>
                                            <td>${servientregas.prospecto_cedula+'-'+servientregas.prospecto_nombre+' '+servientregas.prospecto_apellidos}</td>
                                            <td>${servientregas.dispositivo}</td>
                                            <td>${servientregas.numero_guia}</td>  
                                            <td><span class="label label-info">${servientregas.estado_servientrega}</span></td>
                                            <td>${servientregas.fecha_envio}</td>
                                            <td><div class="class="jsgrid-cell jsgrid-control-field jsgrid-align-center"">
                                            ${botonesServientrega}
                                            </div></td>
                                            </tr>`;

                                botonesServientrega = '';

                            });

                            theTable += tableTr;
                            theTable += `</tbody> 
                                        </table>`;

                            // Añadimos el option al select 
                            table1.append(theTable);


                            tableReporteServientregas = $('#dataTableServientregasReport').DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    'copy',
                                    {
                                        extend: 'excelHtml5',
                                        title: 'Reporte_servientregas'
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        title: 'Reporte_servientregas'
                                    },
                                    'print',
                                    {
                                        extend: 'csvHtml5',
                                        title: 'Reporte_servientregas',
                                        text: "CSV"
                                    }
                                ],
                                "order": [
                                    [0, "asc"]
                                ]
                            });
                            tableReporteServientregas.column(0).visible(false);

                    }else if(respuesta.response == "falta_fecha"){

                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Selecciona ambas Fechas',
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

        function cerrarReporte() {

            $("#id_estado_servientrega").val('placeholder');
            $("#id_estado_servientrega").trigger("change");
            $("#fecha_inicio").val('');
            $("#fecha_final").val('');
            table1.empty();
            space2.hide();
            space1.show();

        }


        function procesosReporteServientrega(idSolicitud, action) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": action
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

                        table1.empty();

                        var formData = new FormData(document.getElementById("filtrosReporte1"));

                        //const action = "reporte_prospectos";
                        const action = "reporte_servientrega_detallado";

                        formData.append('action', action);

                        SelectReport1BD(formData);


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