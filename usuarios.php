<?

if (profile(6, $id_usuario) == 1) {

    $id_usuario_index = $id_usuario;

    if(isset($_GET['filtro'])){
        $filtro = $_GET['filtro'];
        
    }else{
        $filtro = 0;
        
    }

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
                        <? if(profile(41, $id_usuario_index)){?>
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarUsuarioModal()">Nuevo Usuario</button>
                        <? } ?>
                            <a href="?page=usuarios&filtro=g" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;">Ver Digitadores</a>
                            <a href="?page=usuarios&filtro=t" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;">Todos</a>
                            <!--<button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 5px;" onclick="verDigitadoresGane()">Digitadores Gane</button>-->
                            <h4 class="card-title">Usuarios<?=$filtro?></h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableUsuarios" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>NOMBRE</th>
                                            <th>CEDULA</th>
                                            <th>EMAIL</th>
                                            <th>CONTACTO</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        //$filtro = "g";

                                        if($filtro == 0){
                                            $query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0 AND usuarios.cliente_gane = 0";
                                        }

                                        if($filtro == "g"){
                                            $query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0 AND usuarios.cliente_gane = 1";
                                        }

                                        if($filtro == "t"){
                                            $query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0";
                                        }

                                        /*
                                        if($filtro == 0){

                                            /*$query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0 AND usuarios.cliente_gane = 0";

                                            $query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0 AND usuarios.cliente_gane = 1";

                                        }else if($filtro == "t"){

                                            $query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0";

                                        }else if($filtro == "g"){

                                            $query1 = "SELECT usuarios.id, nombre, cedula, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0 AND usuarios.cliente_gane = 1";

                                        }

                                        */
                                        
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_usuario = $row1['id'];
                                            $nombre_completo = $row1['nombre'] . ' ' . $row1['apellidos'];
                                            $correo = $row1['email'];
                                            $contacto = $row1['numero_contacto'];
                                            //$contacto = '(' . substr($contacto, 0, 3) . ')' . substr($contacto, 3, 3) . '-' . substr($contacto, 6, 4);
                                            $cargo = $row1['descripcion_cargo'];
                                            $del = $row1['del'];
                                            $ciudad_usuario = $row1['ciudad'] . ' (' . $row1['departamento'] . ')';
                                            $cedula = $row1['cedula'];

                                        ?>
                                            <tr class="row-<?= $id_usuario ?>">
                                                <td><?= $id_usuario ?></td>
                                                <td><?= $nombre_completo ?></td>
                                                <th><?= $cedula?></th>
                                                <td><?= $correo ?></td>
                                                <td><?= $contacto ?></td>
                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                    <? if(profile(41, $id_usuario_index)){?>
                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Usuario" onClick="editarUsuario(<?= $id_usuario ?>)"><i class="mdi mdi-pencil"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Change Password" onClick="editarContra(<?= $id_usuario ?>)"><i class="fas fa-key"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Inactivate User" onClick="eliminarUsuario(<?= $id_usuario ?>)"><i class="fas fa-user-times fa-sm"></i></a>
                                                    <? } ?>
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


    <!-- Add user modal -->
    <div id="registrar-usuario-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none; overflow-y: auto !important; overflow-x: hidden !important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo_usuarios">Agregar Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" enctype="multipart/form-data" id="registrarUsuarioForm" method="post">
                    <div class="modal-body">
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" placeholder="nombre del usuario" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Apellidos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="apellidos_usuario" id="apellidos_usuario" placeholder="Apellidos del usuario" required autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>cedula:<span class="text-danger">&nbsp;*</span></label></label>
                                    <div class="input-group">
                                        <input class="form-control" name="cedula_usuario" id="cedula_usuario" placeholder="Ingrese la cedula" autocomplete="ÑÖcompletes" maxlength="16" required onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email:</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" name="correo_usuario" id="correo_usuario" placeholder="Correo del usuario" autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contacto:</label>
                                    <div class="input-group">
                                        <input class="form-control phoneNumber" name="contacto_usuario" id="contacto_usuario" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cargo:</label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="cargo_usuario" id="cargo_usuario" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" requiered>
                                            <option value="placeholder" disabled>Seleccionar Cargo</option>
                                            <?php
                                            $query = "select id, descripcion_cargo from cargos where del = 0 order by descripcion_cargo";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['descripcion_cargo'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ciudad de residencia:</label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="ciudad_usuario" id="ciudad_usuario" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                            <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                            <?php
                                            $query = "select ciudades.id, ciudades.ciudad, departamento from ciudades left join departamentos on ciudades.id_departamento = departamentos.id order by ciudad";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id'] ?>"><?= $row['ciudad'] . ' - ' . $row['departamento'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <fieldset>
                                            <div class="custom-control custom-checkbox" style="margin-top: 35px; margin-left: 20px;">
                                                <input type="checkbox" name="usuario_domi" id="usuario_domi" class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="usuario_domi">Domiciliario</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <fieldset>
                                            <div class="custom-control custom-checkbox" style="margin-top: 35px; margin-left: 20px;">
                                                <input type="checkbox" name="usuario_tropa" id="usuario_tropa" class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="usuario_tropa">Tropa</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <fieldset>
                                            <div class="custom-control custom-checkbox" style="margin-top: 35px; margin-left: 20px;">
                                                <input type="checkbox" name="usuario_gane" id="usuario_gane" class="custom-control-input" value="1" onclick="showPuntosGane()">
                                                <label class="custom-control-label" for="usuario_gane">PUNTO GANE</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="zone-puntos">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Puntos gane:</label>
                                    <div class="input-group">
                                        <select class="form-control select2Class" name="punto_gane" id="punto_gane" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                            <option value="placeholder" disabled>Seleccionar Punto</option>
                                            <?php
                                            $query = "SELECT puntos_gane.ID AS id_punto_gane, COD, AGENCIA, DIRECCION FROM puntos_gane WHERE del = 0 ORDER BY COD ASC";
                                            $result = qry($query);
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>
                                                <option value="<?= $row['id_punto_gane'] ?>"><?= $row['COD'] . ' - ' . $row['AGENCIA'] . ' - ' . $row['DIRECCION'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label>No existe?</label>&nbsp;
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: left" onclick="addPuntoGane()">Agregar Punto</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Permisos:</label>
                                    <?
                                    $query = "SELECT id, permiso, mostrar FROM permisos WHERE extend = 0";
                                    $result = qry($query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id_permiso = $row['id'];
                                        $permiso = $row['permiso'];
                                        $mostrar = $row['mostrar'];
                                    ?>
                                        <div class="input-group">
                                            <fieldset>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="permisos_usuario[]" class="custom-control-input checkBoxClass" id="cb<?= $id_permiso ?>" value="<?= $id_permiso ?>" onClick="selectExtends(<?=$id_permiso?>)">
                                                    <label class="custom-control-label" for="cb<?= $id_permiso ?>"><?= $mostrar ?></label>
                                                </div>
                                                <?
                                                $validate = execute_scalar("SELECT COUNT(id) AS total FROM permisos WHERE extend = $id_permiso");
                                                if ($validate != 0) {
                                                    $query2 = "SELECT id, permiso, mostrar FROM permisos WHERE extend = $id_permiso";
                                                    $result2 = qry($query2);
                                                    while ($row2 = mysqli_fetch_array($result2)) {
                                                        $id_permiso2 = $row2['id'];
                                                        $permiso2 = $row2['permiso'];
                                                        $mostrar2 = $row2['mostrar'];
                                                ?>
                                                        <div class="custom-control custom-checkbox">
                                                            <label style="margin: 0 auto; margin-right:22px;">-</label>
                                                            <input type="checkbox" name="permisos_usuario[]" class="custom-control-input checkBoxClass" id="cb<?= $id_permiso2 ?>" value="<?= $id_permiso2 ?>">
                                                            <label class="custom-control-label" for="cb<?= $id_permiso2 ?>"><?= $mostrar2 ?></label>
                                                        </div>
                                                <? }
                                                }
                                                ?>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                        </div>
                                    <? } ?>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <fieldset>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="ckbCheckAll">
                                                <label class="custom-control-label" for="ckbCheckAll">Todos</label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="">
                        <input type="hidden" name="action" id="action_usuario" value="">
                        <input type="hidden" name="id_usuario_index" value="<?=$id_usuario_index?>">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- /.modal -->

    <!--add punto Modal-->
    <div id="add-punto-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Punto Gane</h4>
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
                                    <label>Nombre del punto:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="nombre_punto" id="nombre_punto" placeholder="Nombre del punto GANE" autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Dirección del punto:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="direccion_punto" id="direccion_punto" placeholder="Dirección del punto GANE" autocomplete="ÑÖcompletes">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Contacto del punto:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="contacto_punto" id="contacto_punto" placeholder="Fijo o Celular" autocomplete="ÑÖcompletes" maxlength="10" onkeypress="return validaNumerics(event)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" value="add_punto_gane" />
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-info" style="float: right">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- cambiar password Modal -->
    <div id="cambiar-password-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cambiar Contraseña</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="smart-form" id="cambiarPasswordForm" method="post" action="" data-ajax="false">
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
                                    <label>Contraseña:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="contra_usuario" id="contra_usuario" placeholder="Ingrese la nueva contraseña" autocomplete="ÑÖcompletes" required>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <div class="controls">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="verContraUsuario" onChange="ocultarOmostrarContra()">
                                                <label class="custom-control-label" for="verContraUsuario">Ver Contraseña</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id_usuario_contra" name="id_usuario" value="" />
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">Cancelar</button>
                        <button type="submit" class="btn btn-info" style="float: right">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal -->


    <script>
        function registrarUsuarioModal() {
            $("#nombre_usuario").val('');
            $("#apellidos_usuario").val('');
            $("#correo_usuario").val('');
            $("#cedula_usuario").val('');
            $("#contacto_usuario").val('');
            $("#dob_usuario").val('');
            $("#cargo_usuario").val('placeholder');
            $("#cargo_usuario").trigger('change');
            $("#ciudad_usuario").val('placeholder');
            $("#ciudad_usuario").trigger('change');
            $("#action_usuario").val('insert');
            $("#id_usuario").val('');
            $("#usuario_domi").prop('checked', false);
            $("#usuario_tropa").prop('checked', false);
            $("#usuario_gane").prop('checked', false);
            $("#punto_gane").val('placeholder');
            $("#punto_gane").trigger("change");
            $("#zone-puntos").hide();
            $("#titulo_usuarios").html("Agregar Usuario");
            $("#registrar-usuario-modal").modal("show");
            var totalCheckbox = $(".checkBoxClass").length;
            for (i = 1; i <= totalCheckbox; i++) {
                $("#cb" + i).prop('checked', false);
            }
            $("#ckbCheckAll").prop('checked', false);
        }

        $("#registrarUsuarioForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarUsuarioForm"));

            const action = document.querySelector("#action_usuario").value;

            if (action == "insert") {

                insertUsuarioDB(formData);

            } else {

                updateUsuarioDB(formData);

            }
        });

        function insertUsuarioDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/usuariosAjax.php', true);
            // pass Info
            xhr.onload = function() {
                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        var fullName = respuesta.nombre + ' ' + respuesta.apellidos;
                        var ciudadEstado = respuesta.ciudad + ' (' + respuesta.departamento + ')';

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Usuario registrado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaUsuarios.row.add([
                            respuesta.id_insert, fullName, respuesta.cedula, respuesta.email, respuesta.contacto, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Usuario" onClick="editarUsuario(${respuesta.id_insert})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Change Password" onClick="editarContra(${respuesta.id_insert})"><i class="fas fa-key"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Inactivate User" onClick="eliminarUsuario(${respuesta.id_insert})"><i class="fas fa-user-times fa-sm"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_insert);

                        $("#registrar-usuario-modal").modal("hide");

                        setTimeout(function() {
                            location.reload
                        }, 3000);

                    } else if (respuesta.response == "correo_repetido") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'El correo ya existe',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    } else if (respuesta.response == "cedula_repetida") {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Cedula ya registrada',
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



                }
            }

            // send dates
            xhr.send(dates)

        }

        function editarUsuario(idUsuario) {
            var parameters = {
                "id_usuario": idUsuario,
                "action": "select_usuarios"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/usuariosAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    $("#nombre_usuario").val(respuesta[0].nombre);
                    $("#apellidos_usuario").val(respuesta[0].apellidos);
                    $("#correo_usuario").val(respuesta[0].email);
                    $("#cedula_usuario").val(respuesta[0].cedula);
                    $("#contacto_usuario").val(respuesta[0].numero_contacto);
                    $("#dob_usuario").val(respuesta[0].fecha_nacimiento);
                    $("#cargo_usuario").val(respuesta[0].id_cargo);
                    $("#cargo_usuario").trigger('change');
                    $("#departamento_usuario").val(respuesta[0].id_departamento);
                    $("#departamento_usuario").trigger('change');
                    $("#ciudad_usuario").val(respuesta[0].id_ciudad);
                    $("#ciudad_usuario").trigger('change');
                    $("#usuario_domi").prop('checked', false);
                    if (respuesta[0].domiciliario == 1) {
                        $("#usuario_domi").prop('checked', true);
                    }
                    $("#usuario_tropa").prop('checked', false);
                    if (respuesta[0].usuario_tropa == 1) {
                        $("#usuario_tropa").prop('checked', true);
                    }
                    if (respuesta[0].cliente_gane == 1) {
                        $("#usuario_gane").prop('checked', true);
                        $("#punto_gane").val(respuesta[0].id_punto_gane);
                        $("#punto_gane").trigger("change");
                        $("#zone-puntos").show();
                    } else {
                        $("#usuario_gane").prop('checked', false);
                        $("#zone-puntos").hide();
                    }
                    $("#action_usuario").val('update');
                    $("#id_usuario").val(respuesta[0].id_usuario);
                    var totalCheckbox = $(".checkBoxClass").length;
                    for (i = 1; i <= totalCheckbox; i++) {
                        $("#cb" + i).prop('checked', false);
                    }
                    respuesta[1].forEach(function(permisos, index) {

                        $("#cb" + permisos.id_permiso).prop('checked', true);

                    });
                    var totalCheckbox2 = respuesta[1].length;
                    if (totalCheckbox2 == totalCheckbox) {
                        $("#ckbCheckAll").prop('checked', true);
                    } else {
                        $("#ckbCheckAll").prop('checked', false);
                    }
                    $("#titulo_usuarios").html("Editar Usuario");
                    $("#registrar-usuario-modal").modal("show");

                }

            });
        }

        function updateUsuarioDB(dates) {

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/usuariosAjax.php', true);
            // pass Info
            xhr.onload = function() {
                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        var fullName = respuesta.nombre + ' ' + respuesta.apellidos;
                        var ciudadEstado = respuesta.ciudad + ' (' + respuesta.departamento + ')';

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Usuario actualizado correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        tablaUsuarios.row(".row-" + respuesta.id_insert).remove().draw(false);

                        tablaUsuarios.row.add([
                            respuesta.id_insert, fullName, respuesta.cedula, respuesta.email, respuesta.contacto, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Usuario" onClick="editarUsuario(${respuesta.id_insert})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Change Password" onClick="editarContra(${respuesta.id_insert})"><i class="fas fa-key"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Inactivate User" onClick="eliminarUsuario(${respuesta.id_insert})"><i class="fas fa-user-times fa-sm"></i></a></div>`
                        ]).draw(false).nodes().to$().addClass("row-" + respuesta.id_insert);

                        $("#registrar-usuario-modal").modal("hide");
                        
                        if(respuesta.id_insert == respuesta.id_usuario_index){
                            setTimeout("location.reload()", 3000);
                        }

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


        function eliminarUsuario(idUsuario) {
            Swal.fire({
                title: 'Estas seguro?',
                text: "Por favor confirmar para eliminar este usuario!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Estoy seguro!'
            }).then((result) => {
                if (result.value) {
                    var parameters = {
                        "id_usuario": idUsuario,
                        "action": "eliminar"
                    };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/usuariosAjax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);

                            console.log(respuesta);

                            if (respuesta.response == "success") {

                                tablaUsuarios.row(".row-" + respuesta.id_usuario).remove().draw(false);

                                $.toast({
                                    heading: 'Genial!',
                                    text: 'Usuario Eliminado Correctamente.',
                                    position: 'top-center',
                                    loaderBg: '#00c292',
                                    icon: 'success',
                                    hideAfter: 4500,
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


        function editarContra(idUsuario) {
            $("#contra_usuario").val('');
            $("#verContraUsuario").prop('checked', false);
            $("#id_usuario_contra").val(idUsuario);
            $("#cambiar-password-modal").modal("show");
        }

        function ocultarOmostrarContra() {

            var password, check;

            password = document.getElementById("contra_usuario");
            check = document.getElementById("verContraUsuario");

            if (check.checked == true) // Si la checkbox de mostrar contraseña está activada
            {
                password.type = "text";
            } else // Si no está activada
            {
                password.type = "password";
            }

        }

        $("#cambiarPasswordForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("cambiarPasswordForm"));

            const action = "edit_password";

            formData.append('action', action);

            editPasswordDB(formData);

        });


        function editPasswordDB(dates) {
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/usuariosAjax.php', true);
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
                            title: 'Contraseña modificada correctamente',
                            showConfirmButton: false,
                            timer: 2500

                        });

                        $("#cambiar-password-modal").modal("hide");

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

        $("#ckbCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });

        $(".checkBoxClass").change(function() {
            if (!$(this).prop("checked")) {
                $("#ckbCheckAll").prop("checked", false);
            }
        });

        function showPuntosGane() {

            var check, zone1;

            zone1 = $("#zone-puntos");

            check = document.getElementById("usuario_gane");

            if (check.checked == true) // Si la checkbox de mostrar contraseña está activada
            {
                $("#punto_gane").val('placeholder');
                $("#punto_gane").trigger('change');
                zone1.show();

            } else // Si no está activada
            {
                $("#punto_gane").val('placeholder');
                $("#punto_gane").trigger('change');
                zone1.hide();
            }

        }

        function addPuntoGane() {

            $("#nombre_punto").val('');
            $("#direccion_punto").val('');
            $("#contacto_punto").val('');
            $("#add-punto-modal").modal("show");

        }

        $("#addPuntoGaneForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("addPuntoGaneForm"));

            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/usuariosAjax.php', true);
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
                            title: 'Punto Agregado Correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                        var data = {
                            id: respuesta.id_punto_gane,
                            text: respuesta.nombre_punto + '-' + respuesta.direccion_punto
                        };

                        var newOption = new Option(data.text, data.id, false, false);

                        $("#add-punto-modal").modal("hide");

                        $('#punto_gane').append(newOption).trigger('change');
                        $('#punto_gane').val(respuesta.id_punto_gane);
                        $('#punto_gane').trigger('change');

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
            }

            // send dates
            xhr.send(formData)

        });


        function selectExtends(idPermiso){

            var parameters = {

                "id_permiso": idPermiso,
                "action": "select_extends"

            };

            $.ajax({

                data: parameters,
                url: 'ajax/usuariosAjax.php',
                type: 'post',
                success: function(response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);


                }

            });

        }

    </script>

<?
} else {

    include '401error.php';
}

?>