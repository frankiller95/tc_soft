
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
                            <h4 class="text-themecolor"><?=ucwords($page)?></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                                    <li class="breadcrumb-item active"><?=ucwords($page)?></li>
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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarUsuarioModal()">Nuevo Usuario</button>
                                    <h4 class="card-title">Usuarios</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableUsuarios" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>NOMBRE</th>
                                                               <th>EMAIL</th>
                                                               <th>CONTACTO</th>
                                                               <th>CARGO</th>
                                                               <th>CIUDAD</th>
                                                               <th>ESTADO</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT usuarios.id, nombre, apellidos, email, numero_contacto, cargos.descripcion_cargo, usuarios.del, ciudad, departamento FROM usuarios LEFT JOIN cargos ON usuarios.id_cargo = cargos.id LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE usuarios.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_usuario = $row1['id'];
                                                            $nombre_completo = $row1['nombre'].' '.$row1['apellidos'];
                                                            $correo = $row1['email'];
                                                            $contacto = $row1['numero_contacto'];
                                                            $contacto = '('.substr($contacto, 0,3).') '.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
                                                            $cargo = $row1['descripcion_cargo'];
                                                            $del = $row1['del'];
                                                            $ciudad_usuario = $row1['ciudad'].' ('.$row1['departamento'].')';

                                                    ?>
                                                               <tr class="row-<?=$id_usuario?>">
                                                               <td><?=$id_usuario?></td>
                                                               <td><?=$nombre_completo?></td>
                                                               <td><?=$correo?></td>
                                                               <td><?=$contacto?></td>
                                                               <td><?=$cargo?></td>
                                                               <td><?=$ciudad_usuario?></td>
                                                                <?php if ($del == 0) {?>
                                                                <td><span class="label label-success">Activo</span></td>
                                                                <?php }else{ ?>
                                                                <td><span class="label label-danger">Inactivo</span></td>    
                                                                <?php } ?>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Usuario" onClick="editarUsuario(<?= $id_usuario ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Change Password" onClick="editarContra(<?=$id_usuario?>)"><i class="fas fa-key"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Inactivate User" onClick="eliminarUsuario(<?= $id_usuario ?>)"><i class="fas fa-user-times fa-sm"></i></a>
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
        <div id="registrar-usuario-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
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
                                        <label>Email:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="correo_usuario" id="correo_usuario" placeholder="Correo del usuario" autocomplete="ÑÖcompletes" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>cedula:<span class="text-danger">&nbsp;*</span></label></label>
                                        <div class="input-group">
                                            <input class="form-control" name="cedula_usuario" id="cedula_usuario" placeholder="Ingrese la cedula" autocomplete="ÑÖcompletes" maxlength="16" required onkeypress="return validaNumerics(event)">
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
                                        <label>Fecha de nacimiento:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control todayColor" name="dob_usuario" id="dob_usuario" placeholder="fecha de nacimiento" autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Cargo:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="cargo_usuario" id="cargo_usuario" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" requiered>
                                                <option value="placeholder" disabled>Seleccionar Cargo</option>
                                                <?php
                                                $query = "select id, descripcion_cargo from cargos where del = 0 order by descripcion_cargo";
                                                $result = qry($query);
                                                while($row = mysqli_fetch_array($result)) {
                                                ?>
                                                    <option value="<?= $row['id']?>"><?= $row['descripcion_cargo']?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Departamento de residencia:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="departamento_usuario" id="departamento_usuario" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                <option value="placeholder" disabled>Seleccionar Departamento</option>
                                                <?php
                                                $query = "select id, departamento from departamentos order by departamento";
                                                $result = qry($query);
                                                while($row = mysqli_fetch_array($result)) {
                                                ?>
                                                    <option value="<?= $row['id']?>"><?= $row['departamento']?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Ciudad de residencia:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="ciudad_usuario" id="ciudad_usuario" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                                <option disabled>Selecciona un departamento</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_usuario" id="id_usuario" value="">
                            <input type="hidden" name="action" id="action_usuario" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<script>

    function registrarUsuarioModal(){
        $("#nombre_usuario").val('');
        $("#apellidos_usuario").val('');
        $("#correo_usuario").val('');
        $("#cedula_usuario").val('');
        $("#contacto_usuario").val('');
        $("#dob_usuario").val('');
        $("#cargo_usuario").val('placeholder');
        $("#cargo_usuario").trigger('change');
        $("#departamento_usuario").val('placeholder');
        $("#departamento_usuario").trigger('change');
        $("#ciudad_usuario").val('placeholder');
        $("#ciudad_usuario").trigger('change');
        $("#action_usuario").val('insert');
        $("#id_usuario").val('');
        $("#titulo_usuarios").html("Agregar Usuario");
        $("#registrar-usuario-modal").modal("show");
    }

    $('#departamento_usuario').change(function() {
        let idDepartamento = $("#departamento_usuario").val();
        console.log(idDepartamento);
        var parameters = {
            "id_departamento": idDepartamento,
            "action": "select_ciudades_departamento"
        };

        $.ajax({

           data:  parameters,
           url:   'ajax/usuariosAjax.php',
           type:  'post',
           success:  function (response) {
                console.log(response);
                const respuesta = JSON.parse(response);
                console.log(respuesta);

                var newOption = '';

                //$("#ciudad_usuario").val(null).trigger('change');
                $('#ciudad_usuario').empty();


                //var placeholderOption = new Option('Seleccionar Ciudad', 'placeholder', true, true);
                //$('#ciudad_usuario').append(placeholderOption).trigger('change');
                //$('#ciudad_usuario').val('placeholder').prop("disabled", true);

                respuesta.forEach(function(ciudades, index) {
                    console.log(ciudades);

                    newOption = new Option(ciudades.ciudad, ciudades.id, false, false);
                    $('#ciudad_usuario').append(newOption).trigger('change');
                });
           }

        });
    });

    $("#registrarUsuarioForm").on("submit", function(e){

                // evitamos que se envie por defecto
                e.preventDefault();
    
                 // create FormData with dates of formulary       
                var formData = new FormData(document.getElementById("registrarUsuarioForm"));

                const action = document.querySelector("#action_usuario").value;

                if(action == "insert"){

                    insertUsuarioDB(formData);

                }else{

                    updateUsuarioDB(formData);

                }
    });

    function insertUsuarioDB(dates){

        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/usuariosAjax.php', true);
        // pass Info
        xhr.onload = function(){
            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    var fullName = respuesta.nombre+' '+respuesta.apellidos;
                    var ciudadEstado = respuesta.ciudad+' ('+respuesta.departamento+')';

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Usuario registrado correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    tablaUsuarios.row.add([
                        respuesta.id_insert, fullName, respuesta.email, respuesta.contacto, respuesta.cargo, ciudadEstado, `<span class="label label-success">Activated</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Usuario" onClick="editarUsuario(${respuesta.id_insert})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Change Password" onClick="editarContra(${respuesta.id_insert})"><i class="fas fa-key"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Inactivate User" onClick="eliminarUsuario(${respuesta.id_insert})"><i class="fas fa-user-times fa-sm"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_insert);

                    $("#registrar-usuario-modal").modal("hide");

                }else{

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

    function editarUsuario(idUsuario){
        var parameters = {
            "id_usuario": idUsuario,
            "action": "select_usuarios"
        };

        $.ajax({

           data:  parameters,
           url:   'ajax/usuariosAjax.php',
           type:  'post',
           success:  function (response) {
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
                $("#action_usuario").val('update');
                $("#id_usuario").val(respuesta[0].id_usuario);
                $("#titulo_usuarios").html("Editar Usuario");
                $("#registrar-usuario-modal").modal("show");

            }

        });
    }

    function updateUsuarioDB(dates){

        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/usuariosAjax.php', true);
        // pass Info
        xhr.onload = function(){
            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    var fullName = respuesta.nombre+' '+respuesta.apellidos;
                    var ciudadEstado = respuesta.ciudad+' ('+respuesta.departamento+')';

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Usuario actualizado correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    tablaUsuarios.row(".row-"+respuesta.id_insert).remove().draw(false);

                    tablaUsuarios.row.add([
                        respuesta.id_insert, fullName, respuesta.email, respuesta.contacto, respuesta.cargo, ciudadEstado, `<span class="label label-success">Activated</span>`, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Usuario" onClick="editarUsuario(${respuesta.id_insert})"><i class="mdi mdi-pencil"></i></a><a href="javascript:void(0)" class="btn btn-outline-warning waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Change Password" onClick="editarContra(${respuesta.id_insert})"><i class="fas fa-key"></i></a><a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Inactivate User" onClick="eliminarUsuario(${respuesta.id_insert})"><i class="fas fa-user-times fa-sm"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_insert);

                    $("#registrar-usuario-modal").modal("hide");

                }else{

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


    function eliminarUsuario(idUsuario){
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
                    data:  parameters,
                    url:   'ajax/usuariosAjax.php',
                    type:  'post',
                    success:  function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        tablaUsuarios.row(".row-"+respuesta.id_usuario).remove().draw(false);

                        $.toast({
                                heading: 'Genial!',
                                text: 'Usuario Eliminado Correctamente.',
                                position: 'top-center',
                                loaderBg:'#00c292',
                                icon: 'success',
                                hideAfter: 4500, 
                                stack: 6
                        });

                    }else{

                        $.toast({

                                heading: 'Error!',
                                text: 'Error en el proceso.',
                                position: 'top-center',
                                loaderBg:'#e46a76',
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