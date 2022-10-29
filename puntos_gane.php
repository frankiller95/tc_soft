<?

if (profile(30, $id_usuario) == 1) {

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
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarPuntoGaneModal()">Nuevo Punto Gane</button>
                            <h4 class="card-title">Puntos Gane</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTablePuntosGane" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>CODIGO</th>
                                            <th>NOMBRE PUNTO</th>
                                            <th>DIRECCIÓN PUNTO</th>
                                            <th>BARRIO</th>
                                            <th># DIGITADORES</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT puntos_gane.ID AS a, AGENCIA, DIRECCION, COD, BARRIO, (SELECT COUNT(usuarios_puntos_gane.id) AS digitadores FROM usuarios_puntos_gane LEFT JOIN usuarios ON usuarios_puntos_gane.id_usuario = usuarios.id WHERE usuarios_puntos_gane.id_punto_gane = a AND usuarios_puntos_gane.del = 0 AND usuarios.del = 0) AS total_digitadores FROM puntos_gane WHERE puntos_gane.del = 0";
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_punto_gane = $row1['a'];
                                            $nombre_punto = $row1['AGENCIA'];
                                            $direccion_punto = $row1['DIRECCION'];
                                            $codigo = $row1['COD'];
                                            $barrio = $row1['BARRIO'];
                                            $digitadores = $row1['total_digitadores'];

                                        ?>
                                            <tr class="row-<?= $id_punto_gane ?>">
                                                <td><?= $id_punto_gane ?></td>
                                                <td><?= $codigo ?></td>
                                                <td><?= $nombre_punto ?></td>
                                                <td><?= $direccion_punto ?></td>
                                                <td><?= $barrio ?></td>
                                                <td><?= $digitadores ?></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Punto Gane" onClick="editarPuntoGane(<?= $id_punto_gane ?>)"><i class="mdi mdi-pencil"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Punto Gane" onClick="eliminarPuntoGane(<?= $id_punto_gane ?>, <?= $digitadores ?>)"><i class="fas fa-trash-alt"></i></a>
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




    <!--add punto Modal-->
    <div id="add-punto-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_puntos_gane">Add Punto Gane</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" id="addPuntoGaneForm" method="post" action="" data-ajax="false">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Codigo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="codigo_punto" id="codigo_punto" placeholder="Codigo del punto" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="12">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre del punto:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nombre_punto" id="nombre_punto" placeholder="Nombre del punto GANE" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Dirección del punto:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="direccion_punto" id="direccion_punto" placeholder="Dirección del punto GANE" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Barrio:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="barrio_punto" id="barrio_punto" placeholder="Barrio del punto GANE" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action_punto_gane" value="" />
                        <input type="hidden" name="id_punto_gane" id="id_punto_gane">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-info" style="float: right">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->


    <script>
        function registrarPuntoGaneModal() {

            $("#titulo_puntos_gane").html("Agregar Punto Gane");
            $("#codigo_punto").val('');
            $("#nombre_punto").val('');
            $("#direccion_punto").val('');
            $("#barrio_punto").val('');
            $("#id_punto_gane").val('');
            $("#action_punto_gane").val('insertar_punto_gane');
            $("#add-punto-modal").modal("show");

        }


        $("#addPuntoGaneForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("addPuntoGaneForm"));

            const action = document.querySelector("#action_punto_gane").value;

            if (action == "insertar_punto_gane") {

                insertPuntoGaneDB(formData);

            } else {

                updatePuntoGaneDB(formData);

            }

        });

        function insertPuntoGaneDB(dates) {

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