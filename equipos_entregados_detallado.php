<? if (profile(38, $id_usuario) == 1) {  ?>

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

                                                <label>Seleccione Responsable:</label>

                                                <div class="input-group">

                                                    <select class="form-control select2Class" name="id_responsable_directo" id="id_responsable_directo" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">

                                                        <option value="placeholder" disabled selected>Seleccionar Responsable de la venta</option>

                                                        <option value="0">Todos los Asesores</option>

                                                        <?

                                                        $query = "SELECT usuarios.id AS id_responsable, usuarios.nombre, usuarios.apellidos, usuarios.cedula FROM usuarios WHERE usuarios.cliente_gane = 0 AND usuarios.email <> 'pantalla@noa10.com' AND usuarios.email <> 'admin@gane.com' AND usuarios.del = 0";

                                                        $result = qry($query);

                                                        while ($row = mysqli_fetch_array($result)) {

                                                        ?>

                                                            <option value="<?= $row['id_responsable'] ?>"><? echo $row['cedula'] . '-' . $row['nombre'] . '-' . $row['apellidos'] ?></option>

                                                        <? } ?>

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
                            <h4 class="card-title" id="titulo_reporte">CLIENTES</h4>
                            <div class="table-responsive m-t-40 table_ventas_dia">

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

    <div id="change_comprobantes_modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cambiar Comprobantes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="insertarComprobantesForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label>Imagen:&nbsp;<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cargar</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="comprobantes_entrega[]" id="comprobantes_entrega" class="form-control" accept="image/*" multiple="multiple">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="cambiar_comprobantes">
                        <input type="hidden" name="id_solicitud" id="id_solicitud_comprobante">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Cargar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        'use strict';

        var table1 = $(".table_ventas_dia");


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

            const action = "reporte_ventas_dia";

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
                console.log(this.status);
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

                        titulo.html(`Equipos Entregados.`);

                        var theTable = `<table id="dataTableVentasDia" class="table-patient table display table-bordered table-striped no-wrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CLIENTE</th>
                                    <th>DISPOSITIVO</th>
                                    <th>RESPONSABLE</th>
                                    <th>FINANCIERA</th>
                                    <th>FECHA ENTREGA</th>
                                    <th>MEDIO</th>
                                    <th>ESTADO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>`;

                        var tableTr = '';
                        var botonComprobante = '';
                        //for (i = 0; i < ToJson.length; i++) { 
                        respuesta[0].forEach(function(solicitudes, index) {

                            if (solicitudes.id_medio_envio == 1) {
                                botonComprobante = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Comprobantes" onclick="verComprobantes(${solicitudes.id_solicitud})"><i class="fas fa-camera"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cambiar Comprobantes" onclick="cambiarImgs(${solicitudes.id_solicitud})"><i class="fas fa-sync"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Regresar a entregas pdtes" onclick="regresarEnPdtes(${solicitudes.id_solicitud})"><i class="fas fa-arrow-left"></i></a>`;
                            }

                            tableTr += `<tr class="row-${solicitudes.id_solicitud}">
                                                <td>${solicitudes.id_solicitud}</td>
                                                <td>${solicitudes.prospecto_cedula+'-'+solicitudes.prospecto_nombre+' '+solicitudes.prospecto_apellidos}</td>
                                                <td>${solicitudes.marca_producto+' '+solicitudes.nombre_modelo+' '+solicitudes.capacidad}</td>
                                                <td>${solicitudes.responsable_nombre+' '+solicitudes.responsable_apellidos}</td>
                                                <td>${solicitudes.nombre_plataforma}</td>
                                                <td>${solicitudes.fecha_entrega}</td>
                                                <td>${solicitudes.medio_envio}</td>
                                                <td><span class="label label-info">${solicitudes.mostrar}</span></td>
                                                <td><div class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                ${botonComprobante}
                                                </div></td>
                                                </tr>`;

                            botonComprobante = '';

                        });


                        theTable += tableTr;
                        theTable += `</tbody> 
                                            </table>`;

                        // Añadimos el option al select 
                        table1.append(theTable);


                        tableVentasDia = $('#dataTableVentasDia').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'copy',
                                {
                                    extend: 'excelHtml5',
                                    title: 'Reporte_ventas_dia '
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Reporte_ventas_dia'
                                },
                                'print',
                                {
                                    extend: 'csvHtml5',
                                    title: 'Reporte_ventas_dia',
                                    text: "CSV"
                                }
                            ],
                            "order": [
                                [0, "asc"]
                            ]
                        });
                        tableVentasDia.column(0).visible(false);

                    } else if (respuesta.response == "falta_fecha") {

                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Ingresa las dos fechas o ninguna',
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

            $("#fecha_inicio").val('');
            $("#fecha_final").val('');
            $("#id_responsable_directo").val('placeholder');
            $("#id_responsable_directo").trigger("change");
            table1.empty();
            space2.hide();
            space1.show();

        }


        function verComprobantes(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "select_comprobantes_entrega"
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

                        var zonaEvidencias = $("#galeries");
                        zonaEvidencias.empty();

                        respuesta[0].forEach(function(comprobantes, index) {
                            zonaEvidencias.append(`<a href="${respuesta.filepath}${comprobantes.img}" title="comprobante # ${index}">${index}</a>`);
                        });

                        zonaEvidencias.magnificPopup({
                            delegate: 'a',
                            type: 'image',
                            gallery: {
                                enabled: true
                            }
                        }).magnificPopup('open');

                    } else if (respuesta.response == "vacio") {

                        Swal.fire({

                            position: 'top-center',
                            type: 'error',
                            title: 'No tiene comprobantes',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else {

                        Swal.fire({

                            position: 'top-center',
                            type: 'error',
                            title: 'Erorr en el proceso',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }


                }

            });

        }

        function cambiarImgs(idSolicitd) {
            $("#comprobantes_entrega").val('');
            $("#id_solicitud_comprobante").val(idSolicitd);
            $("#change_comprobantes_modal").modal("show");
        }


        $("#insertarComprobantesForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("insertarComprobantesForm"));
            actualizarComprobanteswDB(formData);

        });


        function actualizarComprobanteswDB(dates) {
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
                            title: 'Comprobantes Actualizados',
                            showConfirmButton: false,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        setTimeout("location.reload()", 3000);

                        $("#change_comprobantes_modal").modal('hide');



                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    }

                } else {

                }
            }

            // send dates
            xhr.send(dates)

        }

        function regresarEnPdtes(idSolicitud) {
            Swal.fire({

                position: 'top-end',
                type: 'error',
                title: 'Función en proceso de desarrollo',
                showConfirmButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 4000

            });
        }
        
    </script>

<?

} else {

    include '401error.php';
}

?>