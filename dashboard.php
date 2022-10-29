<?

if (profile(13, $id_usuario) == 1) {


    $total_prospectos = 0;
    $total_mis_prospectos = 0;
    $total_validaciones_pdtes = 0;
    $total_mis_validaciones = 0;
    $total_pdtes_entregas = 0;
    $total_rutas_gane = 0;


?>
    <style>
        #audio {
            display: none;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper form">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <? if (validateScreen($id_usuario) == 0) { ?>
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Inicio</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="index.php">Inicio</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            <? } else { ?>
                <br>
                <br>
            <? } ?>
            <div class="row">
                <!-- Column -->
                <? if (profile(24, $id_usuario)) { ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <a href="javascript:void(0);" onclick="verProspectos('boton')" id="boton-prospectos">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-success"><i class="fas fa-users"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <? $total_prospectos = execute_scalar("SELECT COUNT(id) FROM prospectos WHERE prospectos.del = 0 AND prospectos.id_responsable_interno = 0"); ?>
                                            <h3 class="m-b-0" id="contador_prospectos"><?= $total_prospectos ?></h3>
                                            <h5 class="text-muted m-b-0">PROSPECTOS</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!--<audio id="audio" controls>
                            <source type="audio/wav" src="./assets/notifications/videoplayback.wav">
                        </audio>-->
                        <div id="sound"></div>
                        <!--<input type="hidden" id="total_prospectos" value="<?= $total_prospectos ?>">-->
                    </div>
                <? } 
                if (profile(25, $id_usuario)) { ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <a href="javascript:void(0);" onclick="verValidacionesPdtes('boton')" id="boton-validaciones-pendientes">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-primary"><i class="fas fa-bell"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <? $total_validaciones_pdtes = execute_scalar("SELECT COUNT(id) AS total FROM `prospectos` WHERE prospectos.id_estado_prospecto = 12 AND prospectos.del = 0"); ?>
                                            <h3 class="m-b-0" id="contador_validaciones_pdtes"><?= $total_validaciones_pdtes ?></h3>
                                            <h5 class="text-muted m-b-0">PDTES. VALIDAR</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!--<input type="hidden" name="total_validaciones_pdtes" id="total_validaciones_pdtes" value="<?= $total_validaciones_pdtes ?>">-->
                    </div>
                <? }
                if (profile(26, $id_usuario)) { ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <a href="javascript:void(0);" onclick="verEntregasPdtes('boton')" id="boton-entregas-pendientes">
                                    <div class="d-flex flex-row">
                                        <div class="round align-self-center round-primary"><i class="fas fa-motorcycle"></i></div>
                                        <div class="m-l-10 align-self-center">
                                            <? if(profile(36, $id_usuario)){

                                                $total_pdtes_entregas = execute_scalar("SELECT COUNT(solicitudes_domiciliarios.id) AS total FROM solicitudes_domiciliarios LEFT JOIN solicitudes ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id WHERE prospectos.id_medio_envio = 1 AND solicitudes.id_estado_solicitud = 6 AND solicitudes.del = 0 AND solicitudes_domiciliarios.del = 0");

                                            }else{

                                                $total_pdtes_entregas = execute_scalar("SELECT COUNT(solicitudes_domiciliarios.id) AS total FROM solicitudes_domiciliarios LEFT JOIN solicitudes ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id WHERE prospectos.id_medio_envio = 1 AND solicitudes.id_estado_solicitud = 6 AND solicitudes.del = 0 AND solicitudes_domiciliarios.del = 0 AND solicitudes_domiciliarios.id_domiciliario = $id_usuario");

                                            }
                                            
                                            ?>
                                            <h3 class="m-b-0" id="contador_entregas_pdtes"><?= $total_pdtes_entregas ?></h3>
                                            <h5 class="text-muted m-b-0">ENTREGAS PDTES.</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!--<input type="hidden" name="total_entregas_pdtes" id="total_entregas_pdtes" value="<?= $total_pdtes_entregas ?>">-->
                    </div>
                <? } ?>
            </div>

            <div class="row" id="zone-row-tabla" style="display: none;">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title" id="titulo_tabla"></h4>
                            <div class="table-responsive m-t-40" id="zone-tab">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->
            <input type="hidden" id="id_usuario" value="<?= $id_usuario ?>">
            <input type="hidden" id="total_prospectos" value="<?= $total_prospectos ?>">
            <input type="hidden" name="total_mis_prospectos" id="total_mis_prospectos" value="<?= $total_mis_prospectos ?>">
            <input type="hidden" name="total_validaciones_pdtes" id="total_validaciones_pdtes" value="<?= $total_validaciones_pdtes ?>">
            <input type="hidden" name="total_mis_validaciones" id="total_mis_validaciones" value="<?= $total_mis_validaciones ?>">
            <input type="hidden" name="total_entregas_pdtes" id="total_entregas_pdtes" value="<?= $total_pdtes_entregas ?>">
            <input type="hidden" name="total_rutas_gane" id="total_rutas_gane" value="<?= $total_rutas_gane ?>">
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->


    <!-- section modal for pick up date -->

    <div id="info_equipo" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">INFORMACIÓN DISPOSITIVO.</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="info_equipo_form" method="post">
                    <div class="modal-body">
                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Dispositivo:</label>
                                    <div class="input-group">
                                        <input type="text" id="dispositivo" name="dispositivo" class="form-control" placeholder="Product Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Capacidad:</label>
                                    <div class="input-group">
                                        <input type="text" id="capacidad" name="capacidad" class="form-control" placeholder="Product Name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Color:</label>
                                    <div class="input-group">
                                        <input type="text" id="color" name="color" class="form-control" placeholder="Product Name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <div id="confirmar_entrega_modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmar Entrega.</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="imagenes_entrega_form" method="post">
                    <div class="modal-body">
                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                        <div class="row">
                            <div id="preview-imagenes">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_solicitud_entrega" name="id_solicitud">
                        <button type="button" class="btn btn-success" id="boton-confirmar" style="float: left">Confirmar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

    </div>


    <!-- Add comentario ruta -->
    <div id="registrar-comentario-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_comentarios">Agregar Comentario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="registrarComentariosForm" method="post">
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
                                    <label for="add_comentario">Nuevo Comentario:</label>
                                    <div class="input-group">
                                        <textarea class="form-control" name="add_comentario" id="add_comentario" rows="5" placeholder="Escribe tu comentario"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_ruta" id="id_ruta_comentario" value="">
                        <input type="hidden" name="action" id="action_ruta_comentario" value="">
                        <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <script>

        /* VARIABLES ZONE */

        var pause = 0;

        var filename = "videoplayback";

        var titulo = $("#titulo_tabla");

        var totalProspectos = $("#total_prospectos").val();

        var misProspectos = $("#total_mis_prospectos").val();

        var entregasPdtes = $("#total_entregas_pdtes").val();

        var validacionesPdtes = $("#total_validaciones_pdtes").val();

        var misValidaciones = $("#total_mis_validaciones").val();

        var totalRutasGane = $("#total_rutas_gane").val();

        var idUsuario = $("#id_usuario").val();

        var zone1 = $("#zone-row-tabla");

        var zone2 = $("#zone-tab");

        var imagesArray = [];

        function contador() {

            if (pause == 0) {

                window.setInterval(function() {

                    contadorAll();

                }, 1000);

            } else {
                return 0;
            }


        }

        function playSound(filename) {
            var mp3Source = '<source src="./assets/notifications/' + filename + '.mp3" type="audio/mpeg">';
            var oggSource = '<source src="./assets/notifications/' + filename + '.ogg" type="audio/ogg">';
            var embedSource = '<embed hidden="true" autostart="true" loop="false" src="./assets/notifications/' + filename + '.mp3">';
            document.getElementById("sound").innerHTML = '<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
        }

        function contadorAll() {

            var audio = document.getElementById("audio");

            var parameters = {
                "id_usuario": idUsuario,
                "action": "count_all"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',

                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.permiso_validaciones_pdtes == 1) {

                        $("#contador_validaciones_pdtes").html('');
                        $("#contador_validaciones_pdtes").html(respuesta.total_mis_validaciones);
                        $("#total_validaciones_pdtes").val(respuesta.total_mis_validaciones);

                        verValidacionesPdtes('boton');

                    }

                }

            });

        }

        contador();

        function verProspectos(from) {

            var parameters = {
                "action": "select_prospectos"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    var zone1 = $("#zone-row-tabla");

                    var zone2 = $("#zone-tab");

                    if (from == "boton") {
                        zone1.show();
                    }

                    zone2.empty();

                    titulo.html(`PROSPECTOS`);

                    var theTable1 = `<table id="dataTableDashProspectos" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>CC</th>
                                                <th>NOMBRE</th>
                                                <th>CONTACTO</th>
                                                <th>CREADO EN</th>
                                                <th>FECHA</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    var tableTr1 = '';

                    respuesta.forEach(function(prospectos, index) {

                        tableTr1 += `<tr class="row-${prospectos.id_prospecto}">
                                                    <td>${prospectos.id_prospecto}</td>
                                                    <td>${prospectos.prospecto_cedula}</td>
                                                    <td>${prospectos.prospecto_nombre_full}</td>
                                                    <td>${prospectos.contacto}</td>
                                                    <td>${prospectos.creado_en}</td>
                                                    <td>${prospectos.fecha_registro}</td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                       <a href="javascript:void(0);" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Tomar Prospecto" onclick="tomarProspecto(${prospectos.id_prospecto})"><i class="far fa-hand-point-up"></i></a>
                                                    </td>
                                                </tr>`;

                    });

                    theTable1 += tableTr1;
                    theTable1 += `</tbody> 
                                </table>`;

                    zone2.append(theTable1);

                    tablaProspectosDash = $('#dataTableDashProspectos').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ],
                        responsive: true,
                        "paging": false
                    });
                    tablaProspectosDash.column(0).visible(false);

                    if (from == "boton") {
                        $('#boton-prospectos').removeAttr('onclick').attr('onclick', 'esconderTabla(1);');
                    }
                }

            });

        }

        function verMisProspectos(from) {

            var parameters = {
                "id_usuario": idUsuario,
                "action": "select_mis_prospectos"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    var zone1 = $("#zone-row-tabla");

                    var zone2 = $("#zone-tab");

                    if (from == "boton") {
                        zone1.show();
                    }

                    zone2.empty();

                    titulo.html(`MIS PROSPECTOS`);

                    var theTable1 = `<table id="dataTableDashProspectos" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>CC</th>
                                                <th>NOMBRE</th>
                                                <th>CREADO POR</th>
                                                <th>PLATAFORMA</th>
                                                <th>ESTADO</th>
                                                <th>FECHA</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    var tableTr1 = '';

                    var opcionDelete = '';

                    var acciones = '';

                    var opcionCargar = '';

                    respuesta[0].forEach(function(prospectos, index) {

                        if (respuesta.permiso20) {

                            opcionDelete = `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Prospecto" onClick="eliminarProspecto(${prospectos.id_prospecto})"><i class="fas fa-trash-alt"></i></a>`;

                        }

                        var colorLabel = "info";

                        if (prospectos.estado_prospecto == "APROBADO") {

                            colorLabel = "success";

                        } else if (prospectos.estado_prospecto == "RECHAZADO") {

                            colorLabel = "danger";

                        }

                        if (prospectos.id_plataforma == "3") {
                            acciones = `<a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Soltar Prospecto" onClick="soltarProspecto(${prospectos.id_prospecto})"><i class="fas fa-sync-alt"></i></a>`;
                        }


                        if (prospectos.nombre_plataforma === null) {
                            prospectos.nombre_plataforma = "N/A";
                        }

                        tableTr1 += `<tr class="row-${prospectos.id_prospecto}">
                                                    <td>${prospectos.id_prospecto}</td>
                                                    <td>${prospectos.prospecto_cedula}</td>
                                                    <td>${prospectos.prospecto_nombre_full}</td>
                                                    <td>${prospectos.creado_en}</td>
                                                    <td><span class="label label-info">${prospectos.nombre_plataforma}</span></td>
                                                    <td><span class="label label-${colorLabel}">${prospectos.estado_prospecto}</span></td>
                                                    <td>${prospectos.fecha_registro}</td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                       <a href="?page=prospecto&id=${prospectos.id_prospecto}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto"><i class="mdi mdi-pencil"></i></a>
                                                        ${acciones}
                                                        ${opcionDelete}
                                                    </td>
                                                </tr>`;

                    });

                    theTable1 += tableTr1;
                    theTable1 += `</tbody> 
                                </table>`;

                    zone2.append(theTable1);

                    tablaProspectosDash = $('#dataTableDashProspectos').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ],
                        responsive: false,
                        "paging": false
                    });
                    tablaProspectosDash.column(0).visible(false);

                    if (from == "boton") {
                        $('#boton-mis-prospectos').removeAttr('onclick').attr('onclick', 'esconderTabla(2);');
                    }

                }

            });

        }

        function inputFile(input, idSolicitudInput) {

            console.log(idSolicitudInput);

            $("#id_solicitud_entrega").val(idSolicitudInput); //colocamos el id de la solicitud en la modal

            //Get count of selected files
            var countFiles = $(input)[0].files.length;

            var imgPath = $(input)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#preview-imagenes");
            image_holder.empty();

            console.log(extn);

            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "jfif") {
                if (typeof(FileReader) != "undefined") {

                    imagesArray = [];
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {

                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $("<img />", {
                                "src": e.target.result,
                                "class": "img-thumbnail img-responsive"
                            }).appendTo(image_holder);

                            imagesArray.push(e.target.result);
                        }

                        image_holder.show();
                        reader.readAsDataURL($(input)[0].files[i]);
                    }

                } else {

                    alert("This browser does not support FileReader.");

                }
            } else {

                alert("Pls select only images");

            }

            $("#confirmar_entrega_modal").modal("show");

            //});
        }

        function verEntregasPdtes(from) {

            var parameters = {
                "id_usuario": idUsuario,
                "action": "select_entregas_pdtes"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    var zone1 = $("#zone-row-tabla");

                    var zone2 = $("#zone-tab");

                    if (from == "boton") {
                        zone1.show();
                    }

                    zone2.empty();

                    titulo.html(`ENTREGAS PDTES.`);

                    var theTable1 = `<table id="dataTableDashEntregasPdtes" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>CLIENTE</th>
                                                <th>CELULAR</th>
                                                <th>DOMICILIARIO</th>
                                                <th>FECHA ENTEGA</th>
                                                <th>UBICACIÓN</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    var tableTr1 = '';

                    var opcionDelete = '';

                    respuesta.forEach(function(solicitudes, index) {

                        tableTr1 += `<tr class="row-${solicitudes.id_solicitud}">
                                                    <td>${solicitudes.id_solicitud}</td>
                                                    <td>${solicitudes.prospecto_nombre_full}-${solicitudes.prospecto_cedula}</td>
                                                    <td><a href="tel:+57${solicitudes.prospecto_numero_contacto}" style="font-weight: 700;">${solicitudes.prospecto_numero_contacto}</a></td>
                                                    <td>${solicitudes.domiciliario}</td>
                                                    <td>${solicitudes.fecha_entrega_format}&nbsp;${solicitudes.solicitud_inicio_tiempo}&nbsp;-&nbsp;${solicitudes.solicitud_final_tiempo}</td>
                                                    <td>${solicitudes.prospecto_direccion}-${solicitudes.ciudad}-${solicitudes.departamento}</td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                       <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Dispositivo" onclick="verDispositivo(${solicitudes.id_solicitud})"><i class="fas fa-info"></i></a>
                                                       <!--<a href="javascript:void(0)" onClick="confirmarEntrega(${solicitudes.id_solicitud})"><i class="fas fa-check"></i></a>-->
                                                        <form method="post" action="" enctype="multipart/form-data" id="uploadForm">
                                                            <label for="files_${solicitudes.id_solicitud}" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Confirmar Entrega" class="custom-file-upload"><i class="fas fa-check"></i></label>
                                                            <input id="files_${solicitudes.id_solicitud}" name="file" type="file" multiple accept="image/png,image/jpeg" style="display: none" onchange="inputFile(this, ${solicitudes.id_solicitud})"/>
                                                            
                                                        </form>
                                                    </td>
                                                </tr>`;

                    });

                    theTable1 += tableTr1;
                    theTable1 += `</tbody> 
                                </table>`;

                    zone2.append(theTable1);

                    tablaEntregasPdtes = $('#dataTableDashEntregasPdtes').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ],
                        responsive: false,
                        "paging": false
                    });
                    tablaEntregasPdtes.column(0).visible(false);

                    if (from == "boton") {

                        $('#boton-entregas-pendientes').removeAttr('onclick').attr('onclick', 'esconderTabla(3);');

                    }

                }

            });

        }

        function verValidacionesPdtes(from) {

            var parameters = {
                "id_usuario": idUsuario,
                "action": "select_validaciones_pdtes"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    var zone1 = $("#zone-row-tabla");

                    var zone2 = $("#zone-tab");

                    if (from == "boton") {
                        zone1.show();
                    }

                    zone2.empty();

                    titulo.html(`VALIDACIONES PDTES.`);

                    var theTable1 = `<table id="dataTableDashValidacionesPdtes" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>C.C</th>
                                                <th>CLIENTE</th>
                                                <th>CONTACTO</th>
                                                <th>UBICACIÓN</th>
                                                <th>ASESOR</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    var tableTr1 = '';

                    var opcionDelete = '';

                    var iconoBoton = '';

                    respuesta.forEach(function(validaciones, index) {

                        if (validaciones.id_usuario_validador == 0) {

                            iconoBoton = '<i class="far fa-hand-point-up"></i>';

                        } else {

                            iconoBoton = '<i class="fas fa-info"></i>';

                        }

                        tableTr1 += `<tr class="row-${validaciones.id_prospecto}">
                                                    <td>${validaciones.id_prospecto}</td>
                                                    <td>${validaciones.prospecto_cedula}</td>
                                                    <td>${validaciones.prospecto}</td>
                                                    <td><a href="tel:+57${validaciones.prospecto_numero_contacto}" style="font-weight: 700;">${validaciones.prospecto_numero_contacto}</a></td>
                                                    <td>${validaciones.ubicacion}</td>
                                                    <td>${validaciones.asesor}</td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                       <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Tomar Validación" onclick="TomarValidacion(${validaciones.id_prospecto})">${iconoBoton}</a>
                                                    </td>
                                                </tr>`;

                    });

                    theTable1 += tableTr1;
                    theTable1 += `</tbody> 
                                </table>`;

                    zone2.append(theTable1);

                    tablaValidacionesPdtes = $('#dataTableDashValidacionesPdtes').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ],
                        responsive: false,
                        "paging": false
                    });
                    tablaValidacionesPdtes.column(0).visible(false);

                    if (from == "boton") {
                        $('#boton-validaciones-pendientes').removeAttr('onclick').attr('onclick', 'esconderTabla(4);');
                    }

                }

            });

        }

        function verMisValidaciones(from) {

            var parameters = {
                "id_usuario": idUsuario,
                "action": "select_mis_validaciones"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    var zone1 = $("#zone-row-tabla");

                    var zone2 = $("#zone-tab");

                    if (from == "boton") {
                        zone1.show();
                    }

                    zone2.empty();

                    titulo.html(`VALIDACIONES PDTES.`);

                    var theTable1 = `<table id="dataTableDashMisValidaciones" class="table display table-bordered table-striped no-wrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>C.C</th>
                                                <th>CLIENTE</th>
                                                <th>CONTACTO</th>
                                                <th>ASESOR</th>
                                                <th>PLATAFORMA</th>
                                                <th>ESTADO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                    var tableTr1 = '';

                    var opcionDelete = '';

                    var iconoBoton = '';

                    var plataforma = '';

                    var estadoProspecto = '';

                    respuesta.forEach(function(validaciones, index) {

                        if (validaciones.id_usuario_validador == 0) {

                            iconoBoton = '<i class="far fa-hand-point-up"></i>';

                        } else {

                            iconoBoton = '<i class="fas fa-info"></i>';

                        }

                        if (validaciones.id_plataforma == 0) {
                            plataforma = "N/A";
                        } else {
                            plataforma = validaciones.nombre_plataforma;
                        }

                        if (validaciones.id_estado_prospecto == 0) {
                            estadoProspecto = "PDTE.VALIDAR";
                        } else {
                            estadoProspecto = validaciones.estado_prospecto;
                        }

                        tableTr1 += `<tr class="row-${validaciones.id_prospecto}">
                                                    <td>${validaciones.id_prospecto}</td>
                                                    <td>${validaciones.prospecto_cedula}</td>
                                                    <td>${validaciones.prospecto}</td>
                                                    <td><a href="tel:+57${validaciones.prospecto_numero_contacto}" style="font-weight: 700;">${validaciones.prospecto_numero_contacto}</a></td>
                                                    <td>${validaciones.asesor}</td>
                                                    <td><span class="label label-info">${plataforma}</span></td>
                                                    <td><span class="label label-info">${estadoProspecto}</span></td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                       <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Tomar Validación" onclick="TomarValidacion(${validaciones.id_prospecto})">${iconoBoton}</a>
                                                    </td>
                                                </tr>`;

                    });

                    theTable1 += tableTr1;
                    theTable1 += `</tbody> 
                                </table>`;

                    zone2.append(theTable1);

                    tablaMisValidaciones = $('#dataTableDashMisValidaciones').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ],
                        responsive: false,
                        "paging": false
                    });
                    tablaMisValidaciones.column(0).visible(false);

                    if (from == "boton") {
                        $('#boton-mis-validaciones').removeAttr('onclick').attr('onclick', 'esconderTabla(5);');
                    }

                }

            });

        }

        function verDispositivo(idSolicitud) {

            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "select_dispositivo"
            };


            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $("#dispositivo").val(respuesta[0].marca_producto + '-' + respuesta[0].nombre_modelo);
                    $("#capacidad").val(respuesta[0].capacidad);
                    $("#color").val(respuesta[0].color_desc);
                    $("#info_equipo").modal("show");
                }

            });

        }


        function esconderTabla(from) {

            zone1.hide();
            zone2.empty();
            if (from == 1) {
                $('#boton-prospectos').removeAttr('onclick').attr('onclick', `verProspectos('boton');`);
            } else if (from == 2) {
                $('#boton-mis-prospectos').removeAttr('onclick').attr('onclick', `verMisProspectos('boton');`);
            } else if (from == 3) {
                $('#boton-entregas-pendientes').removeAttr('onclick').attr('onclick', `verEntregasPdtes('boton');`);
            } else if (from == 4) {
                $('#boton-validaciones-pendientes').removeAttr('onclick').attr('onclick', `verValidacionesPdtes('boton');`);
            } else if (from == 5) {
                $('#boton-mis-validaciones').removeAttr('onclick').attr('onclick', `verMisValidaciones('boton');`);
            } else if (from == 6) {
                $('#boton-rutas-gane').removeAttr('onclick').attr('onclick', `verRutasGane('boton');`);
            }

        }

        function tomarProspecto(idProspecto) {

            const idUsuario = $("#id_usuario").val();

            var parameters = {
                "id_prospecto": idProspecto,
                "id_usuario": idUsuario,
                "action": "tomar_prospecto"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'FELICIDADES!',
                            text: 'prospecto asignado correctamente.',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 4000

                        });


                        tablaProspectosDash.row(".row-" + respuesta.id_prospecto).remove().draw(false);

                        //setTimeout(function(){location.href="?page=prospecto&id="+respuesta.id_prospecto} , 4500);

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


        function soltarProspecto(idProspecto) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para enviar a la cola nuevamente!",
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
                        "action": "soltar_prospecto"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectosAjax.php',
                        type: 'post',

                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            Swal.fire({

                                position: 'top-end',
                                type: 'success',
                                title: 'Prospecto Liberado Correctamente',
                                showConfirmButton: false,
                                timer: 3000

                            });

                        }

                    });

                } else {

                    return 0;

                }
            });

        }

        function TomarValidacion(idProspecto) {

            const idUsuario = $("#id_usuario").val();

            var parameters = {
                "id_prospecto": idProspecto,
                "id_usuario": idUsuario,
                "action": "validar_prospecto"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        var tiempoAlerta = 1000;

                        if (respuesta.insert == 1) {

                            tiempoAlerta = 4500;

                            Swal.fire({

                                position: 'top-end',
                                type: 'success',
                                title: 'FELICIDADES!',
                                text: 'Validación asignada correctamente.',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timer: tiempoAlerta

                            });

                        }

                    } else if (respuesta.response == "validacion_proceso") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Prospecto en proceso de validación',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        tablaValidacionesPdtes.row(".row-" + respuesta.id_prospecto).remove().draw(false);

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

        $("#boton-confirmar").on('click', function() {

            console.log(imagesArray);

            idSolicitud = $("#id_solicitud_entrega").val();

            var parameters = {
                "images": imagesArray,
                "id_solicitud": idSolicitud,
                "action": "cargar_images_entrega"
            };

            $.ajax({
                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {
                    //
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'FELICIDADES!',
                            text: 'Solicitud entregada correctamente',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3000

                        });

                        tablaEntregasPdtes.row(".row-" + respuesta.id_solicitud).remove().draw(false);

                        $("#confirmar_entrega_modal").modal("hide");

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

        });

        function verRutasGane(from) {
            var parameters = {
                "id_usuario": idUsuario,
                "action": "select_rutas_gane"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/dashboardAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    var zone1 = $("#zone-row-tabla");

                    var zone2 = $("#zone-tab");

                    if (from == "boton") {
                        zone1.show();
                    }

                    zone2.empty();

                    titulo.html(`RUTAS PDTES.`);

                    var theTable1 = `<table id="dataTableRutasGaneDash" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PUNTO GANE</th>
                                            <th>DIRECCION</th>
                                            <th>FECHA VISITA</th>
                                            <th>COMENTARIOS</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                    var tableTr1 = '';

                    var opcionDelete = '',
                        opcionConfirmarButton = '',
                        zonaComentarios = '';


                    respuesta[0].forEach(function(rutas, index) {

                        if (rutas.validate_comentarios != 0) {
                            rutas[0].forEach(function(comentarios, index) {
                                zonaComentarios += `<p class="lead">${comentarios.fecha_registro}</p>
                                                        <p>${comentarios.comentario_texto}</p>`;
                            });
                        }

                        if (rutas.id_usuario_ruta == idUsuario) {
                            opcionConfirmarButton = `<a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Confirmar Capacitado" onclick="confirmarCapacitado(${rutas.id_ruta}, 1)"><i class="fas fa-check"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Confirmado pendiente" onclick="confirmarCapacitado(${rutas.id_ruta}, 2)"><i class="fas fa-check"></i></a>`;
                        } else {
                            opcionConfirmarButton = '';
                        }

                        tableTr1 += `<tr class="row-${rutas.id_ruta}">
                                                    <td>${rutas.id_ruta}</td>
                                                    <td>${rutas.cod}-${rutas.agencia}</td>
                                                    <td>${rutas.direccion}</td>
                                                    <td>${rutas.fecha}</td>
                                                    <td><div id="comentarios-${rutas.id_ruta}">${zonaComentarios}</div></td>
                                                    <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                        ${opcionConfirmarButton}
                                                        <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Evidencias" onclick="verEvidencias(${rutas.id_ruta})"><i class="fas fa-camera"></i></a>
                                                        <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Comentario" onclick="hacerComentario(${rutas.id_ruta})"><i class="fas fa-comment"></i></a>
                                                    </td>
                                                </tr>`;

                        zonaComentarios = '';

                    });

                    theTable1 += tableTr1;
                    theTable1 += `</tbody> 
                                </table>`;

                    zone2.append(theTable1);

                    tablaRutasPdtes = $('#dataTableRutasGaneDash').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ],
                        responsive: true,
                        "paging": false,
                        "order": [
                            [3, "asc"]
                        ]
                    });
                    tablaRutasPdtes.column(0).visible(false);

                    if (from == "boton") {

                        $('#boton-rutas-gane').removeAttr('onclick').attr('onclick', 'esconderTabla(6);');

                    }

                }

            });

        }

        function confirmarCapacitado(idRuta, desde) {

            Swal.fire({
                title: 'Estas seguro?',
                text: "Si aceptas la ruta sera cerrada, recuerda realizar el comentario!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {

                console.log(result.value);

                if (result.value == true) {

                    var parameters = {
                        "id_usuario": idUsuario,
                        "id_ruta": idRuta,
                        "confirmacion_capacitado": desde,
                        "action": "update_capacitado"
                    };

                    $.ajax({

                        data: parameters,
                        url: 'ajax/dashboardAjax.php',
                        type: 'post',
                        success: function(response) {
                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'FELICIDADES!',
                                    text: 'Punto capacitado correctamente',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                                tablaRutasPdtes.row(".row-" + idRuta).remove().draw(false);

                            } else {

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'ERROR EN EL PROCESO!',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });

                            }

                        }

                    });

                } else {

                    return 0;

                }
            });

        }

        function hacerComentario(idRuta) {

            $("#add_comentario").val('');
            $("#action_ruta_comentario").val('new_comentario');
            $("#id_ruta_comentario").val(idRuta);
            $("#registrar-comentario-modal").modal("show");

        }

        $("#registrarComentariosForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarComentariosForm"));

            insertComentarioRutaDB(formData);
            

        });

        function insertComentarioRutaDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/rutasGaneAjax.php', true);
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
                            title: 'Comentario realizado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        var zonaComentarios = '';

                        var comentariosDiv = $("#comentarios-"+respuesta.id_ruta);

                        comentariosDiv.empty();

                        if (respuesta.validate_comentarios != 0) {
                            respuesta[0].forEach(function(comentarios, index) {
                                zonaComentarios += (`<p class="lead">${comentarios.fecha_registro}</p>
                                            <p>${comentarios.comentario_texto}</p>`);
                            });
                        }

                        comentariosDiv.append(zonaComentarios);

                        $("#registrar-comentario-modal").modal("hide");

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
    </script>

<? } else {

    include_once '401error.php';
} ?>