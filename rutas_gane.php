<?

if (profile(1, $id_usuario) == 1) {

?>
    <style>
        .titulos-comm {
            font-size: 15px;
            font-weight: 700;
        }

        .texto-comm {
            font-size: 13px;
        }
    </style>
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
                    <h4 class="text-themecolor"><?= ucwords(str_replace('_', ' ', $page)) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item active"><?= ucwords(str_replace('_', ' ', $page)) ?></li>
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
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarRutaModal()">Nueva Ruta</button>
                            <h4 class="card-title">Rutas Gane</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableRutasGane" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>PUNTO GANE</th>
                                            <th>RESPONSABLE</th>
                                            <th>FECHA VISITA</th>
                                            <th>COMENTARIOS</th>
                                            <th>ESTADO</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT rutas_gane.id AS id_ruta, puntos_gane.COD, puntos_gane.AGENCIA, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(rutas_gane.fecha_visita, '%m-%d-%Y') AS fecha_programada, puntos_gane.confirmado_capacitacion FROM rutas_gane LEFT JOIN puntos_gane ON rutas_gane.id_punto_gane = puntos_gane.ID LEFT JOIN usuarios ON rutas_gane.id_usuario = usuarios.id WHERE rutas_gane.del = 0";
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_ruta = $row1['id_ruta'];
                                            $COD = $row1['COD'];
                                            $AGENCIA = $row1['AGENCIA'];
                                            $nombre = $row1['nombre'];
                                            $apellidos = $row1['apellidos'];
                                            $fecha_programada = $row1['fecha_programada'];
                                            $confirmado_capacitacion = $row1['confirmado_capacitacion'];

                                            if ($confirmado_capacitacion == 1) {
                                                $estado = "CAPACITADO";
                                            } else {
                                                $estado = "SIN CAPACITAR";
                                            }

                                            $validate_comentarios = execute_scalar("SELECT COUNT(id) AS total FROM comentarios_rutas_gane WHERE id_ruta_gane = $id_ruta AND del = 0");

                                        ?>
                                            <tr class="row-<?= $id_ruta ?>" <? if ($confirmado_capacitacion == 1) { ?>style="background-color: #00c292;" <? } ?>>
                                                <td><?= $id_ruta ?></td>
                                                <td><?= $COD . ' ' . $AGENCIA ?></td>
                                                <td><?= $nombre . ' ' . $apellidos ?></td>
                                                <td><?= $fecha_programada ?></td>
                                                <td><? if ($validate_comentarios != 0) {
                                                        $query2 = "SELECT comentarios_rutas_gane.id AS id_comentario, comentario_texto, comentarios_rutas_gane.fecha_registro, usuarios.nombre, usuarios.apellidos FROM comentarios_rutas_gane LEFT JOIN usuarios ON comentarios_rutas_gane.realizado_por = usuarios.id WHERE id_ruta_gane = $id_ruta AND comentarios_rutas_gane.del = 0 ORDER BY comentarios_rutas_gane.fecha_registro ASC";
                                                        $result2 = qry($query2);
                                                        while ($row2 = mysqli_fetch_array($result2)) {
                                                            $id_comentario = $row2['id_comentario'];
                                                            $comentario_texto = $row2['comentario_texto'];
                                                            $fecha_registro = $row2['fecha_registro'];
                                                            $nombre_comentario = $row2['nombre'] . ' ' . $row2['apellidos'];
                                                    ?>
                                                            <div id="comentarios_ruta<?=$id_ruta?>">
                                                                <p class="titulos-comm"><?= $fecha_registro . ' Por: ' . $nombre_comentario ?></p>
                                                                <p class="texto-comm"><?= $comentario_texto ?></p>
                                                            </div>
                                                    <? }
                                                    } ?>
                                                </td>
                                                <td><span class="label label-info"><?= $estado ?></span></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ruta" onClick="editarRuta(<?= $id_ruta ?>)"><i class="mdi mdi-pencil"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Evidencias" onclick="verEvidencias(<?= $id_ruta ?>)"><i class="fas fa-camera"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Comentario" onclick="verComentarios(<?= $id_ruta ?>)"><i class="fas fa-comment"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar ruta" onClick="eliminarRuta(<?= $id_ruta ?>)"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="galeries" class="hidden">

                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
    </div>


    <!-- Add rutamodal -->
    <div id="registrar-ruta-gane-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_rutas_gane"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="registrarRutasGaneForm" method="post">
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
                                    <label>Responsable:</label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="ruta_responsable" id="ruta_responsable" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Responsable</option>
                                            <?php
                                            $query = "SELECT usuarios.id AS id_responsable, usuarios.cedula, usuarios.nombre, usuarios.apellidos FROM usuarios WHERE usuarios.del = 0 ORDER BY usuarios.nombre ASC";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id_responsable'] ?>"><?= $row['cedula'] . ' ' . $row['nombre'] . ' ' . $row['apellidos'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Punto GANE:</label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="punto_gane_ruta" id="punto_gane_ruta" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                            <option value="placeholder" disabled>Seleccionar Punto Gane</option>
                                            <?php
                                            $query = "SELECT puntos_gane.ID AS id_punto_gane, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, puntos_gane.BARRIO FROM puntos_gane WHERE puntos_gane.del = 0 AND puntos_gane.confirmado_capacitacion = 0 ORDER BY puntos_gane.COD ASC";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id_punto_gane'] ?>"><?= $row['COD'] . '-' . $row['AGENCIA'] . '-' . $row['DIRECCION'] . '-' . $row['BARRIO'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha de visita:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="fecha_visita" name="fecha_visita" class="form-control min-date" placeholder="Seleccionar fecha de visita" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_ruta" id="id_ruta" value="">
                        <input type="hidden" name="action" id="action_ruta" value="">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

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
                        <input type="hidden" name="id_usuario" value="<?=$id_usuario?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <script>
        'use strict';

        function registrarRutaModal() {

            $("#titulo_rutas_gane").html('Nueva Ruta');
            $("#ruta_responsable").val('placeholder');
            $("#ruta_responsable").trigger('change');
            $("#punto_gane_ruta").val('placeholder');
            $("#punto_gane_ruta").trigger('change');
            $("#fecha_visita").val('');
            $("#action_ruta").val('insertar_ruta');
            $("#id_ruta").val('');
            $("#registrar-ruta-gane-modal").modal("show");

        }

        $("#registrarRutasGaneForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarRutasGaneForm"));

            const action = document.querySelector("#action_ruta").value;

            if (action == "insertar_ruta") {

                insertRutaGaneDB(formData);

            } else {

                updateRutaGaneDB(formData);

            }

        });

        function insertRutaGaneDB(dates) {

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
                            title: 'Ruta registrada correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaRutasGane.row.add([
                            respuesta.id_ruta, respuesta.punto_gane, respuesta.responsable, respuesta.fecha_visita, `<div id="comentarios_ruta${respuesta.id_ruta}"></div>`, `<span class="label label-info">SIN CAPACITAR</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ruta" onClick="editarRuta(${respuesta.id_ruta})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Evidencias" onclick="verEvidencias(${respuesta.id_ruta})"><i class="fas fa-camera"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Comentario" onclick="verComentarios(${respuesta.id_ruta})"><i class="fas fa-comment"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar ruta" onClick="eliminarRuta(${respuesta.id_ruta})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_ruta);

                        $("#registrar-ruta-gane-modal").modal("hide");

                    } else if (respuesta.response == "existe") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Esta Punto ya tiene ruta',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    } else if (respuesta.response == "capacitado") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Este punto ya esta capacitado',
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

        function editarRuta(idRuta) {

            var parameters = {
                "id_ruta": idRuta,
                "action": "select_ruta_info"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/rutasGaneAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $("#titulo_rutas_gane").html('Editar Ruta');
                    $("#ruta_responsable").val(respuesta[0].id_usuario);
                    $("#ruta_responsable").trigger('change');
                    $("#punto_gane_ruta").val(respuesta[0].id_punto_gane);
                    $("#punto_gane_ruta").trigger('change');
                    $("#fecha_visita").val(respuesta[0].fecha_programada);
                    $("#action_ruta").val('editar_ruta');
                    $("#id_ruta").val(respuesta[0].id_ruta);
                    $("#registrar-ruta-gane-modal").modal("show");
                }

            });

        }


        function updateRutaGaneDB(dates) {

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
                            title: 'Ruta registrada correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        var zonaComentarios = '';

                        tablaRutasGane.row(".row-" + respuesta.id_ruta).remove().draw(false);

                        if (respuesta.validate_comentarios != 0) {
                            respuesta[0].forEach(function(comentarios, index) {
                                zonaComentarios += (`<p class="titulos-comm">${respuesta.fecha_registro} Por: ${respuesta.realizado_por}</p><p class="texto-comm">${respuesta.comentario_texto}</p>`);
                            });
                        }

                        tablaRutasGane.row.add([
                            respuesta.id_ruta, respuesta.punto_gane, respuesta.responsable, respuesta.fecha_visita, `<div id="comentarios_ruta${respuesta.id_ruta}">${zonaComentarios}</div>`, `<span class="label label-info">SIN CAPACITAR</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ruta" onClick="editarRuta(${respuesta.id_ruta})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Evidencias" onclick="verEvidencias(${respuesta.id_ruta})"><i class="fas fa-camera"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Comentario" onclick="verComentarios(${respuesta.id_ruta})"><i class="fas fa-comment"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar ruta" onClick="eliminarRuta(${respuesta.id_ruta})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_ruta);

                        $("#registrar-ruta-gane-modal").modal("hide");

                    } else if (respuesta.response == "existe") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Esta Punto ya tiene ruta',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    } else if (respuesta.response == "capacitado") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Este punto ya esta capacitado',
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

        function verEvidencias(idRuta) {

            var parameters = {
                "id_ruta": idRuta,
                "action": "select_evidencias"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/rutasGaneAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        var zonaEvidencias = $("#galeries");
                        zonaEvidencias.empty();

                        respuesta[0].forEach(function(evidencias, index) {
                            zonaEvidencias.append(`<a href="./documents/rutas/${evidencias.id_ruta_gane}/${evidencias.img_nombre_archivo}.${evidencias.ext}" data-source="http://500px.com/photo/32736307" title="Subida por ${evidencias.usuario_responsable}">${evidencias.img_nombre_personalizado}</a>`);
                        });

                        zonaEvidencias.magnificPopup({
                            delegate: 'a',
                            type: 'image',
                            gallery: {
                                enabled: true
                            }
                        }).magnificPopup('open');

                    } else if (respuesta.response == "sin_evidencias") {

                        Swal.fire({

                            position: 'top-center',
                            type: 'error',
                            title: 'Aun sin eviencias',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }


                }

            });

        }

        function verComentarios(idRuta){
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

        function insertComentarioRutaDB(dates){

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
                            title: 'Ruta registrada correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        var zonaComentarios = '';
                        

                        if (respuesta.validate_comentarios != 0) {
                            respuesta[0].forEach(function(comentarios, index) {
                                zonaComentarios += (`<p class="lead">${comentarios.fecha_registro}</p>
                                                        <p>${comentarios.comentario_texto}</p>`);
                            });
                        }

                        tablaRutasGane.row.add([
                            respuesta.id_ruta, respuesta.punto_gane, respuesta.responsable, respuesta.fecha_visita, zonaComentarios, `<span class="label label-info">SIN CAPACITAR</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ruta" onClick="editarRuta(${respuesta.id_ruta})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Evidencias" onclick="verEvidencias(${respuesta.id_ruta})"><i class="fas fa-camera"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Comentario" onclick="verComentarios(${respuesta.id_ruta})"><i class="fas fa-comment"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar ruta" onClick="eliminarRuta(${respuesta.id_ruta})"><i class="fas fa-trash-alt"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_ruta);

                        $("#registrar-ruta-gane-modal").modal("hide");

                    } else if (respuesta.response == "existe") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Esta Punto ya tiene ruta',
                            showConfirmButton: false,
                            timer: 2500

                        });

                    } else if (respuesta.response == "capacitado") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Este punto ya esta capacitado',
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

    </script>

<?
} else {

    include '401error.php';
}

?>