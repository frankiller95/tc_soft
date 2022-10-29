<? if (profile(16, $id_usuario) == 1) {  ?>

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

                                                <label>Seleccione punto Gane:</label>

                                                <div class="input-group">

                                                    <select class="form-control select2Class" name="puntos_gane" id="puntos_gane" style="width: 100%; height:36px;" onchange="selectDigitadores(this.value)" autocomplete="ÑÖcompletes">

                                                        <option value="placeholder" disabled selected>Seleccionar Punto Gane</option>

                                                        <option value="0">Todos los puntos</option>

                                                        <?

                                                        $query = "SELECT ID AS id_punto_gane, COD, AGENCIA, DIRECCION, BARRIO FROM puntos_gane WHERE del = 0";

                                                        $result = qry($query);

                                                        while ($row = mysqli_fetch_array($result)) {

                                                        ?>

                                                            <option value="<?= $row['id_punto_gane'] ?>"><? echo $row['COD'].'-'.$row['AGENCIA'] . '-' . $row['DIRECCION'] ?></option>

                                                        <? } ?>

                                                    </select>

                                                </div>

                                            </div>

                                        </div>



                                    </div>

                                    <div class="row" id="zone-digitadores" style="display: none;">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>Seleccione Digitador:</label>

                                                <div class="input-group">

                                                    <select class="form-control select2Class" name="digitadores_gane" id="digitadores_gane" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">

                                                        <option value="placeholder" disabled selected>Seleccionar Digitador</option>

                                                        <option value="0">Todos los Digitadores</option>


                                                    </select>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">

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
            <!-- END update orders section -->

            <!-- Row -->
            <div class="row" id="table-space-report1" style="display: none;">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" id="titulo_reporte">PROSPECTOS</h4>
                            <div class="table-responsive m-t-40 table_puntos_gane">

                            </div>
                            <br>
                            <br>
                            <button type="button" class="btn btn-info" style="float: right; margin-left: 5px;" onclick="cerrarReporte()">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>

    <script>
        'use strict';

        var table1 = $(".table_puntos_gane");


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

            const action = "reporte_puntos_gane";

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

                    table1.empty();
                    space1.hide();
                    space2.show();

                    titulo.html(`TODOS LOS PROSPECTOS.`);

                    if(respuesta.puntos_gane != 0 && respuesta.digitador_gane == 0){
                        titulo.html(`PROSPECTOS DEL PUNTO ${respuesta.nombre_punto}`);
                    }else if(respuesta.puntos_gane == 0 && respuesta.digitador_gane != 0){
                        titulo.html(`PROSPECTOS DEL DIGITADOR ${respuesta.nombre_digitador} ${respuesta.apellidos_digitador} - ${respuesta.cedula_digitador}`);
                    }else if(respuesta.puntos_gane != 0 && respuesta.digitador_gane != 0){
                        titulo.html(`PROSPECTOS DEL PUNTO ${respuesta.nombre_punto} Y DIGITADOR ${respuesta.nombre_digitador} ${respuesta.apellidos_digitador} - ${respuesta.cedula_digitador}`);
                    }

                    var theTable = `<table id="dataTablePuntosProspectos" class="table-patient table display table-bordered table-striped no-wrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>CEDULA #</th>
                                        <th>PROSPECTO</th>
                                        <th>CIUDAD</th>
                                        <th>CC DIGITADOR</th>
                                        <th>PUNTO</th>
                                        <th>FECHA</th>
                                        <th>ESTADO</th>
                                        <th>ESTADO DC</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    var tableTr = '';
                    //for (i = 0; i < ToJson.length; i++) { 
                    respuesta[0].forEach(function(prospectos, index) {

                        tableTr += `<tr>
                                                    <td>${prospectos.id_prospecto}</td>
                                                    <td>${prospectos.prospecto_cedula}</td>
                                                    <td>${prospectos.prospecto_nombre+' '+prospectos.prospecto_apellidos}</td>
                                                    <td>${prospectos.ciudad+'/ '+prospectos.departamento}</td>
                                                    <td>${prospectos.digitador_cedula}</td>
                                                    <td>${prospectos.codigo}-${prospectos.DIRECCION}</td>
                                                    <td>${prospectos.fecha_creado}</td>
                                                    <td>${prospectos.estado}</td>
                                                    <td>${prospectos.estado_prospecto_dc}</td>
                                                    </tr>`;



                    });


                    theTable += tableTr;
                    theTable += `</tbody> 
                                                </table>`;

                    // Añadimos el option al select 
                    table1.append(theTable);


                    tablePuntosGane = $('#dataTablePuntosProspectos').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy',
                            {
                                extend: 'excelHtml5',
                                title: 'Reporte_puntos_gane '
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Reporte_puntos_gane'
                            },
                            'print',
                            {
                                extend: 'csvHtml5',
                                title: 'Reporte_puntos_gane',
                                fieldSeparator: "|",
                                escapeChar: '"',
                                fieldBoundary: '',
                                text: "PLAIN TEXT"
                            }
                        ],
                        "order": [
                            [0, "asc"]
                        ]
                    }); 
                    tablePuntosGane.column(0).visible(false);

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
            $("#puntos_gane").val('placeholder');
            $("#puntos_gane").trigger("change");
            var listDigitadores = $("#digitadores_gane");
            var zoneDigitadores = $("#zone-digitadores");
            listDigitadores.empty();
            table1.empty();
            space2.hide();
            space1.show();
            zoneDigitadores.hide();

        }

        function selectDigitadores(idPunto) {

            console.log(idPunto);
            var parameters = {
                "id_punto": idPunto,
                "action": "select_digitadores"
            };

            if (idPunto == "placeholder") {
                return 0;
            }

            $.ajax({

                data: parameters,
                url: 'ajax/reportesAjax.php',
                type: 'post',

                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    var listDigitadores = $("#digitadores_gane");

                    var zoneDigitadores = $("#zone-digitadores");

                    listDigitadores.empty();

                    var newOption1 = new Option('Todos los digitadores', 0, false, false);

                    var newOption = '';

                    listDigitadores.append(newOption1).trigger('change');

                    respuesta.forEach(function(digitadores, index) {

                        //console.log(producto);
                        newOption = new Option(digitadores.cedula + '-' + digitadores.nombre + ' ' + digitadores.apellidos, digitadores.id_usuario, false, false);
                        listDigitadores.append(newOption);

                    });

                    zoneDigitadores.show();

                }

            });

        }
        
    </script>

<?

} else {

    include '401error.php';
}

?>