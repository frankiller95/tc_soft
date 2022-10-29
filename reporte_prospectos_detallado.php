<? if (profile(39, $id_usuario) == 1) {

    $profile_14 = profile(14, $id_usuario);

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

                                        <? if (profile(14, $id_usuario)) { ?>

                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label>Seleccione Asesor:</label>

                                                    <div class="input-group">

                                                        <select class="form-control select2Class" name="id_asesor" id="id_asesor" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">

                                                            <option value="placeholder" disabled selected>Seleccionar Asesor</option>

                                                            <option value="0">Todos los Asesores</option>

                                                            <?

                                                            $query = "SELECT usuarios.id AS id_asesor, usuarios.nombre, usuarios.apellidos, usuarios.cedula FROM usuarios WHERE usuarios.cliente_gane = 0 AND usuarios.email <> 'pantalla@noa10.com' AND usuarios.email <> 'admin@gane.com' AND usuarios.del = 0";

                                                            $result = qry($query);

                                                            while ($row = mysqli_fetch_array($result)) {

                                                            ?>

                                                                <option value="<?= $row['id_asesor'] ?>"><? echo $row['cedula'] . '-' . $row['nombre'] . '-' . $row['apellidos'] ?></option>

                                                            <? } ?>

                                                        </select>

                                                    </div>

                                                </div>

                                            </div>

                                        <? } ?>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Seleccione Estado:</label>

                                                <div class="input-group">

                                                    <select class="form-control select2Class" name="id_estado_prospecto" id="id_estado_prospecto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">

                                                        <option value="placeholder" disabled selected>Seleccionar Estado</option>

                                                        <option value="all">Todos los Estados</option>

                                                        <option value="0">PDTE. VALIDAR</option>

                                                        <?

                                                        $query = "SELECT estados_prospectos.id AS id_estado, estados_prospectos.estado_prospecto FROM estados_prospectos WHERE estados_prospectos.id <> 1 AND estados_prospectos.del = 0 ORDER BY estado_prospecto ASC";

                                                        $result = qry($query);

                                                        while ($row = mysqli_fetch_array($result)) {

                                                        ?>

                                                            <option value="<?= $row['id_estado'] ?>"><? echo $row['estado_prospecto'] ?></option>

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

            <!-- Row -->
            <div class="row" id="table-space-report1" style="display: none;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" id="titulo_reporte">PROSPECTOS</h4>
                            <div class="table-responsive m-t-40 table_reporte_prospectos">

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

    <!-- observaciones -->
    <div id="ver-observaciones-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ver Observaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" method="post">
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
                                    <label for="add_comentario">Observaciones:</label>
                                    <div class="input-group">
                                        <div id="mitarjetacontenido" class="tarjetacontenido">
                                            <div id="mitextotarjeta" class="tarjeta-texto kalamtexto"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="editar-observacion-zone">
                            <button type="button" class="btn btn-success" style="float: right; margin-left: 5px;" id="boton-add-observacion">Add. Observación</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_ruta" id="id_ruta_comentario" value="">
                        <input type="hidden" name="action" id="action_ruta_comentario" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- add observaciones -->
    <div id="add-observacion-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pdte por llamar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" id="pdte-llamar-form" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacion_create_prospecto">Fecha y hora de llamada:</label>
                                    <div class="input-group">
                                        <input class="form-control" name="fecha_nueva_llamada" id="fecha_nueva_llamada" rows="10" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_prospecto" id="id_prospecto_comentario" value="">
                        <input type="hidden" name="action" value="add_comentario">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="submit" class="btn btn-success">Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        'use strict';

        var table1 = $(".table_reporte_prospectos");

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

            const action = "reporte_prospectos";

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
                        //space1.hide();
                        space2.show();

                        titulo.html(`Prospectos Creados.`);

                        var theTable = `<table id="dataTableProspectosReport" class="table-patient table display table-bordered table-striped no-wrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>PROSPECTO</th>
                                <th>DISPOSITIVO</th>
                                <th>CONTACTO</th>
                                <th>RESPONSABLE</th>
                                <th>ESTADO</th>
                                <th>FECHA CREADO</th>
                                <th></th>                                
                            </tr>
                        </thead>
                        <tbody>`;

                        var tableTr = '';
                        var botonRestaurar = '';
                        var botonesVentaNoRealizada = '';

                        respuesta[0].forEach(function(prospectos, index) {

                            if (prospectos.id_estado_prospecto == 2 || prospectos.id_estado_prospecto == 6) {
                                botonRestaurar = `<a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Restaurar Prospecto" onclick="restaurarProspecto(${prospectos.id_prospecto})"><i class="fas fa-sync"></i></a>`;
                            }

                            if (prospectos.id_estado_prospecto == 3) {
                                botonesVentaNoRealizada = `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Venta no realizada" onclick="definirVentaNoRealizada(${prospectos.id_prospecto})"><i class="fas fa-exclamation-circle"></i></a>
                                <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Pdte por llamar" onclick="pdtePorLlamar(${prospectos.id_prospecto})"><i class="fas fa-phone-volume"></i></a>`;
                            }

                            tableTr += `<tr class="row-${prospectos.id_prospecto}">
                                            <td>${prospectos.id_prospecto}</td>
                                            <td>${prospectos.prospecto_cedula+'-'+prospectos.prospecto_nombre+' '+prospectos.prospecto_apellidos}</td>
                                            <td>${prospectos.dispositivo}-${prospectos.id_referencia}</td>
                                            <td>${prospectos.prospecto_numero_contacto}</td>
                                            <td>${prospectos.responsable_nombre+' '+prospectos.responsable_apellidos}</td>    
                                            <td><span class="label label-info">${prospectos.estado_prospecto}</span></td>
                                            <td>${prospectos.fecha_creado}</td>
                                            <td><div class="class="jsgrid-cell jsgrid-control-field jsgrid-align-center"">
                                            <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Observaciones" onclick="verObservaciones(${prospectos.id_prospecto})"><i class="fas fa-comment"></i></a>
                                            ${botonesVentaNoRealizada}
                                            ${botonRestaurar}
                                            </div></td>
                                            </tr>`;

                            botonRestaurar = '';

                            botonesVentaNoRealizada = '';


                        });


                        theTable += tableTr;
                        theTable += `</tbody> 
                                        </table>`;

                        // Añadimos el option al select 
                        table1.append(theTable);


                        tableReporteProspectos = $('#dataTableProspectosReport').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'copy',
                                {
                                    extend: 'excelHtml5',
                                    title: 'Reporte_prospectos'
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Reporte_prospectos'
                                },
                                'print',
                                {
                                    extend: 'csvHtml5',
                                    title: 'Reporte_prospectos',
                                    text: "CSV"
                                }
                            ],
                            "order": [
                                [7, "asc"]
                            ],
                            responsive: true
                        });
                        tableReporteProspectos.column(0).visible(false);

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

            $("#id_asesor").val('placeholder');
            $("#id_asesor").trigger("change");
            $("#id_estado_prospecto").val('placeholder');
            $("#id_estado_prospecto").trigger("change");
            table1.empty();
            space2.hide();
            space1.show();

        }

        function verObservaciones(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "select_observacion"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var observacionProspecto = '';

                    if (respuesta.observacion !== null) {
                        observacionProspecto = respuesta.observacion;
                    }

                    $("#mitextotarjeta").html(observacionProspecto.replace(/\n/g, "<br>"));
                    $("#boton-add-observacion").attr('onClick', `editarObservacion(${respuesta.id_prospecto});`);
                    //$("#observacion_create_prospecto").value(respuesta.observacion);
                    $("#ver-observaciones-modal").modal("show");

                }
            });

        }

        function restaurarProspecto(idProspecto) {

            var parameters = {
                "id_prospecto": idProspecto,
                "action": "restaurar_prospecto"
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
                            title: 'Prospecto Restaurado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tableReporteProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

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

        }

        function limpiarCampos() {

            $("#fecha_inicio").val('');
            $("#fecha_final").val('');
            $("#id_asesor").val('placeholder');
            $("#id_asesor").trigger("change");
            $("#id_estado_prospecto").val('placeholder');
            $("#id_estado_prospecto").trigger("change");

        }


        function definirVentaNoRealizada(idProspecto) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "RECUERDA COLOCAR EN LA OBSERVACIÓN, EL MOTIVO DE LA VENTA NO REALIZADA.!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                console.log(result.value);

                if (result.value == true) {

                    var parameters = {
                        "id_prospecto": idProspecto,
                        "action": "definir_venta_no_realizada"
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

                                tableReporteProspectos.row(".row-" + respuesta.id_prospecto).remove().draw(false);

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


        }


        function editarObservacion(idProspecto) {

            $("#observacion_create_prospecto").val('');
            $("#id_prospecto_comentario").val(idProspecto);
            $("#add-observacion-modal").modal("show");

        }


        $("#add-observacion-form").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            console.log('esta llegando aqui');

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("add-observacion-form"));

            updateObservacionBD(formData);

        });

        function updateObservacionBD(dates){
            /** Call to Ajax **/
            console.log(...dates);
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

                        $("#observacion_create_prospecto").val('');
                        $("#add-observacion-modal").modal('hide');
                        $("#mitextotarjeta").html(respuesta.nuevo_texto.replace(/\n/g, "<br>"));

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

    </script>

<?

} else {

    include '401error.php';
}

?>