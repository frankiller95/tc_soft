<? 

if (profile(19,$id_usuario)==1){ 

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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarProveedorModal()">Nuevo Proveedor</button>
                                    <h4 class="card-title">Proveedores</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableProveedores" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>PROVEEDOR</th>
                                                               <th>NIT</th>
                                                               <th>CONTACTO</th>
                                                               <th>UBICACIÓN</th>
                                                               <th>EMAIL</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT proveedores.id, proveedor_nombre, proveedor_nit, proveedor_direccion, ciudades.ciudad, proveedor_contacto, proveedor_email FROM proveedores LEFT JOIN ciudades ON proveedores.proveedor_ciudad_id  = ciudades.id WHERE proveedores.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_proveedor = $row1['id'];
                                                            $nombre = $row1['proveedor_nombre'];
                                                            $proveedor_nit = $row1['proveedor_nit'];
                                                            $proveedor_direccion = $row1['proveedor_direccion'];
                                                            $ciudad = $row1['ciudad'];
                                                            $proveedor_contacto = $row1['proveedor_contacto'];
                                                            $proveedor_contacto = '('.substr($proveedor_contacto, 0,3).') '.substr($proveedor_contacto, 3,3).'-'.substr($proveedor_contacto, 6,4);
                                                            $proveedor_email = $row1['proveedor_email'];

                                                    ?>
                                                               <tr class="row-<?=$id_proveedor?>">
                                                               <td><?=$id_proveedor?></td>
                                                               <td><?=$nombre?></td>
                                                               <td><?=$proveedor_nit?></td>
                                                               <td><?=$proveedor_contacto?></td>
                                                               <td><?=$proveedor_direccion.' ('.$ciudad.')'?></td>
                                                               <td><?=$proveedor_email?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Proveedor" onClick="editarProveedor(<?= $id_proveedor ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Proveedor" onClick="eliminarProveedor(<?= $id_proveedor ?>)"><i class="fas fa-trash-alt"></i></a>
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
   

        <!-- Add producto modal -->
        <div id="registrar-proveedor" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="registro_titulo"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarProveedorForm" method="post">
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
                                        <label>Nombre Completo:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nombre_proveedor" id="nombre_proveedor" placeholder="Nobmre del proveedor o empresa" required autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>NIT:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nit_proveedor" id="nit_proveedor" placeholder="Nit del proveedor" required autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>DIRECCIÓN:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="direccion_proveedor" id="direccion_proveedor" placeholder="Dirección del proveedor" required autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Departamento de residencia:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="departamento_proveedor" id="departamento" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
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
                                            <select  class="form-control select2Class" name="ciudad_proveedor" id="ciudad" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">
                                                <option value="placeholder" disabled>Seleccionar Ciudad</option>
                                                <option disabled>Selecciona un departamento</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contacto:</label>
                                        <div class="input-group">
                                            <input class="form-control phoneNumber" name="contacto_proveedor" id="contacto_proveedor" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="email_proveedor" id="email_proveedor" placeholder="Email del Proveedor" autocomplete="ÑÖcompletes">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_proveedor" id="id_proveedor" value="">
                            <input type="hidden" name="action" id="action_proveedor" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->

<script>

    function registrarProveedorModal(){
        $("#nombre_proveedor").val('');
        $("#nit_proveedor").val('');
        $("#direccion_proveedor").val('');
        $("#departamento").val('placeholder');
        $("#departamento").trigger('change');
        $("#ciudad").val('placeholder');
        $("#ciudad").trigger('change');
        $("#contacto_proveedor").val('');
        $("#email_proveedor").val('');
        $("#id_proveedor").val('');
        $("#action_proveedor").val('insertar');
        $("#registro_titulo").html('Registrar Proveedor');
        $("#registrar-proveedor").modal("show");
    }

    $("#registrarProveedorForm").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        const action = document.querySelector("#action_proveedor").value;

         // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("registrarProveedorForm"));

        if(action == "insertar"){

            registrarProveedorDB(formData);

        }else{

            editarProveedorDB(formData);

        }
        
    });


    function registrarProveedorDB(dates){
        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/proveedoresAjax.php', true);
        // pass Info
        xhr.onload = function(){

            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    var ubicacion = respuesta.direccion_proveedor+' ('+respuesta.ciudad_nombre+')';

                    tablaProveedores.row.add([
                        respuesta.id_proveedor, respuesta.proveedor_nombre, respuesta.proveedor_nit, respuesta.contacto_proveedor, ubicacion, respuesta.email_proveedor, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Proveedor" onClick="editarProveedor(${respuesta.id_proveedor})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Proveedor" onClick="eliminarProveedor(${respuesta.id_proveedor})"><i class="fas fa-trash-alt"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_proveedor);

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Proveedor registrado correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    $("#registrar-proveedor").modal("hide");

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


    function editarProveedor(idProveedor){

        var parameters = {

            "id_proveedor": idProveedor,
            "action": "select_proveedor"

        };

        $.ajax({

            data:  parameters,
            url:   'ajax/proveedoresAjax.php',
            type:  'post',
           success:  function (response) {

                console.log(response);
                const respuesta = JSON.parse(response);
                console.log(respuesta);

                $("#nombre_proveedor").val(respuesta[0].proveedor_nombre);
                $("#nit_proveedor").val(respuesta[0].proveedor_nit);
                $("#direccion_proveedor").val(respuesta[0].proveedor_direccion);
                if (respuesta[0].id_departamento == null) {
                $("#departamento").val('placeholder');
                }else{
                $("#departamento").val(respuesta[0].id_departamento);    
                }
                $("#departamento").trigger('change');
                if(respuesta[0].proveedor_ciudad_id == ""){
                $("#ciudad").val('placeholder');    
                }else{
                $("#ciudad").val(respuesta[0].proveedor_ciudad_id);
                }
                $("#ciudad").trigger('change');
                $("#contacto_proveedor").val(respuesta[0].proveedor_contacto);
                $("#email_proveedor").val(respuesta[0].proveedor_email);
                $("#id_proveedor").val(respuesta[0].id);
                $("#action_proveedor").val('editar');
                $("#registro_titulo").html('Editar Proveedor');
                $("#registrar-proveedor").modal("show");

            }

        });

    };


    function editarProveedorDB(dates){
        /** Call to Ajax **/
        // create the object
        const xhr = new XMLHttpRequest();
        // open conection
        xhr.open('POST', 'ajax/proveedoresAjax.php', true);
        // pass Info
        xhr.onload = function(){

            //the conection is success
            if (this.status === 200) {

                console.log(xhr.responseText);
                const respuesta = JSON.parse(xhr.responseText);
                console.log(respuesta);

                if(respuesta.response == "success"){

                    var ubicacion = respuesta.direccion_proveedor+' ('+respuesta.ciudad_nombre+')';

                    tablaProveedores.row(".row-"+respuesta.id_proveedor).remove().draw(false);

                    tablaProveedores.row.add([
                        respuesta.id_proveedor, respuesta.proveedor_nombre, respuesta.proveedor_nit, respuesta.contacto_proveedor, ubicacion, respuesta.email_proveedor, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Proveedor" onClick="editarProveedor(${respuesta.id_proveedor})"><i class="mdi mdi-pencil"></i></a>
                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Proveedor" onClick="eliminarProveedor(${respuesta.id_proveedor})"><i class="fas fa-trash-alt"></i></a></div>`
                    ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_proveedor);

                    Swal.fire({

                        position: 'top-end',
                        type: 'success',
                        title: 'Proveedor registrado correctamente',
                        showConfirmButton: false,
                        timer: 2500

                    });

                    $("#registrar-proveedor").modal("hide");

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


    function eliminarProveedor(idProveedor){
        Swal.fire({
            title: 'Estas seguro?',
            text: "Por favor confirmar para eliminar este proveedor!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Estoy seguro!'
        }).then((result) => {
            if (result.value) {
                var parameters = {
                    "id_proveedor": idProveedor,
                    "action": "eliminar"
                };

                $.ajax({
                    data:  parameters,
                    url:   'ajax/proveedoresAjax.php',
                    type:  'post',
                    success:  function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);

                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        tablaProveedores.row(".row-"+respuesta.id_proveedor).remove().draw(false);

                        $.toast({
                                heading: 'Genial!',
                                text: 'Proveedor Eliminado Correctamente.',
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

<?
}else{

    include '401error.php';

}

?>