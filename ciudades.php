<? 

if (profile(27,$id_usuario)==1){ 

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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarDepartamentoModal()">Nuevo Departamento</button>
                                    <h4 class="card-title">Departamentos</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableDepartamentos" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>DEPARTAMENTO</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT departamentos.id AS id_departamento, departamento FROM departamentos WHERE departamentos.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_departamento = $row1['id_departamento'];
                                                            $departamento = $row1['departamento'];

                                                    ?>
                                                        <tr class="row-<?=$id_departamento?>">
                                                               <td><?=$id_departamento?></td>
                                                               <td><?=$departamento?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Departamento" onClick="editarDepartamento(<?= $id_departamento ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Departamento" onClick="eliminarDepartamento(<?= $id_departamento ?>)"><i class="fas fa-trash-alt"></i></a>
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

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarCiudadModal()">Nueva Ciudad</button>
                                    <h4 class="card-title">Ciudades.</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableCiudades" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>CIUDAD</th>
                                                               <th>DEPARTAMENTO</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query2 = "SELECT ciudades.id AS id_ciudad, ciudad, departamentos.departamento FROM ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE ciudades.del = 0";
                                                        $result2 = qry($query2);
                                                        while($row2 = mysqli_fetch_array($result2)) {

                                                            $id_ciudad = $row2['id_ciudad'];
                                                            $ciudad = $row2['ciudad'];
                                                            $departamento = $row2['departamento'];

                                                    ?>
                                                        <tr class="row-<?=$id_ciudad?>">
                                                               <td><?=$id_ciudad?></td>
                                                               <td><?=$ciudad?></td>
                                                               <td><?=$departamento?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ciudad" onClick="editarCiudad(<?= $id_ciudad ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Ciudad" onClick="eliminarCiudad(<?= $id_ciudad ?>)"><i class="fas fa-trash-alt"></i></a>
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
   

        <!-- Add Departamento modal -->
        <div id="departamentos-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_departamentos"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarDepartamentosForm" method="post">
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
                                        <label>Departamento:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nuevo_departamento" id="nuevo_departamento" placeholder="Nombre del departamento" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_departamento" id="id_departamento" value="">
                            <input type="hidden" name="action" id="action_departamento" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="ciudades-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_ciudades"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarCiudadesForm" method="post">
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
                                        <label>Departamento:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="departamento_ciudad" id="departamento_ciudad" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" disabled>Seleccionar Departamneto</option>
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Ciudad:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nueva_ciudad" id="nueva_ciudad" placeholder="Nombre de la ciudad" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_ciudad" id="id_ciudad" value="">
                            <input type="hidden" name="action" id="action_ciudad" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<script>


function registrarDepartamentoModal(){

    $("#titulo_departamentos").html("Agregar Departamento");
    $("#nuevo_departamento").val('');
    $("#id_departamento").val('');
    $("#action_departamento").val('insertar_departamento');
    $("#departamentos-modal").modal("show");

}

function registrarCiudadModal(){

    $("#titulo_ciudades").html("Agregar Ciudad");
    $("#departamento_ciudad").val('placeholder');
    $("#departamento_ciudad").trigger('change');
    $("#nueva_ciudad").val('');
    $("#id_ciudad").val('');
    $("#action_ciudad").val('insertar_ciudad');
    $("#ciudades-modal").modal("show");

}

$("#registrarDepartamentosForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

     // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("registrarDepartamentosForm"));

    const action = document.querySelector("#action_departamento").value;

    if(action == "insertar_departamento"){

        insertDepartamentoDB(formData);

    }else{

        updateDepartamentoDB(formData);

    }

});

$("#registrarCiudadesForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

    // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("registrarCiudadesForm"));

    const action = document.querySelector("#action_ciudad").value;

    if(action == "insertar_ciudad"){

        insertCiudadDB(formData);

    }else{

        updateCiudadDB(formData);

    }

});


function insertDepartamentoDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/bibliotecasAjax.php', true);
    // pass Info
    xhr.onload = function(){
        //the conection is success
        if (this.status === 200) {

            console.log(xhr.responseText);
            const respuesta = JSON.parse(xhr.responseText);
            console.log(respuesta);

            if(respuesta.response == "success"){

                Swal.fire({

                    position: 'top-end',
                    type: 'success',
                    title: 'Departamento Ingresado Correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaDepartamentos.row.add([
                    respuesta.id_departamento, respuesta.departamento, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Departamento" onClick="editarDepartamento(${respuesta.id_departamento})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Departamento" onClick="eliminarDepartamento(${respuesta.id_departamento})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_departamento);

                $("#departamentos-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Departamento ya existe',
                        showConfirmButton: false,
                        timer: 2500

                });


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

function insertCiudadDB(dates){

/** Call to Ajax **/
// create the object
const xhr = new XMLHttpRequest();
// open conection
xhr.open('POST', 'ajax/bibliotecasAjax.php', true);
// pass Info
xhr.onload = function(){
    //the conection is success
    if (this.status === 200) {

        console.log(xhr.responseText);
        const respuesta = JSON.parse(xhr.responseText);
        console.log(respuesta);

        if(respuesta.response == "success"){

            Swal.fire({

                position: 'top-end',
                type: 'success',
                title: 'Ciudad Ingresada Correctamente',
                showConfirmButton: false,
                timer: 2500

            });

            tablaCiudades.row.add([
                respuesta.id_ciudad, respuesta.ciudad, respuesta.departamento, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ciudad" onClick="editarCiudad(${respuesta.id_ciudad})"><i class="mdi mdi-pencil"></i></a>
                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Ciudad" onClick="eliminarCiudad(${respuesta.id_ciudad})"><i class="fas fa-trash-alt"></i></a></div>`
            ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_ciudad);

            $("#ciudades-modal").modal("hide");

        }else if(respuesta.response == "existe"){

            Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Esta ciudad ya existe',
                    showConfirmButton: false,
                    timer: 2500
            });

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


function editarDepartamento(idDepartamento){

    var parameters = {
        "id_departamento": idDepartamento,
        "action": "select_departamento"
    };

    $.ajax({

        data: parameters,
        url: 'ajax/bibliotecasAjax.php',
        type: 'post',
        success: function (response) {
            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#titulo_departamentos").html("Editar Departamento");
            $("#nuevo_departamento").val(respuesta[0].departamento);
            $("#id_departamento").val(respuesta[0].id_departamento);
            $("#action_departamento").val('editar_departamento');
            $("#departamentos-modal").modal("show");

        }

    });

}

function editarCiudad(idCiudad){

    var parameters = {
        "id_ciudad": idCiudad,
        "action": "select_ciudad"
    };

    $.ajax({

        data: parameters,
        url: 'ajax/bibliotecasAjax.php',
        type: 'post',
        success: function (response) {
            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#titulo_ciudades").html("Agregar Ciudad");
            $("#departamento_ciudad").val(respuesta[0].id_departamento);
            $("#departamento_ciudad").trigger('change');
            $("#nueva_ciudad").val(respuesta[0].ciudad);
            $("#id_ciudad").val(respuesta[0].id_ciudad);
            $("#action_ciudad").val('editar_ciudad');
            $("#ciudades-modal").modal("show");

        }

    });

}


function updateDepartamentoDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/bibliotecasAjax.php', true);
    // pass Info
    xhr.onload = function(){
        //the conection is success
        if (this.status === 200) {

            console.log(xhr.responseText);
            const respuesta = JSON.parse(xhr.responseText);
            console.log(respuesta);

            if(respuesta.response == "success"){

                Swal.fire({

                    position: 'top-end',
                    type: 'success',
                    title: 'Departamento Ingresado Correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaDepartamentos.row(".row-"+respuesta.id_departamento).remove().draw(false);

                tablaDepartamentos.row.add([
                    respuesta.id_departamento, respuesta.departamento, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Departamento" onClick="editarDepartamento(${respuesta.id_departamento})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Departamento" onClick="eliminarDepartamento(${respuesta.id_departamento})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_departamento);

                $("#departamentos-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Departamento ya existe',
                        showConfirmButton: false,
                        timer: 2500

                });


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

function updateCiudadDB(dates){

/** Call to Ajax **/
// create the object
const xhr = new XMLHttpRequest();
// open conection
xhr.open('POST', 'ajax/bibliotecasAjax.php', true);
// pass Info
xhr.onload = function(){
    //the conection is success
    if (this.status === 200) {

        console.log(xhr.responseText);
        const respuesta = JSON.parse(xhr.responseText);
        console.log(respuesta);

        if(respuesta.response == "success"){

            Swal.fire({

                position: 'top-end',
                type: 'success',
                title: 'Departamento Ingresado Correctamente',
                showConfirmButton: false,
                timer: 2500

            });

            tablaCiudades.row(".row-"+respuesta.id_ciudad).remove().draw(false);

            tablaCiudades.row.add([
                respuesta.id_ciudad, respuesta.ciudad, respuesta.departamento, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Ciudad" onClick="editarCiudad(${respuesta.id_ciudad})"><i class="mdi mdi-pencil"></i></a>
                <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Ciudad" onClick="eliminarCiudad(${respuesta.id_ciudad})"><i class="fas fa-trash-alt"></i></a></div>`
            ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_ciudad);

            $("#ciudades-modal").modal("hide");

        }else if(respuesta.response == "existe"){

            Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Esta ciudad ya existe',
                    showConfirmButton: false,
                    timer: 2500

            });


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

function eliminarDepartamento(idDepartamento){

    Swal.fire({
        title: 'Por favor confirmar para eliminar este Departamento!',
        text: "Se eliminaran todas las ciudades asociadas.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {
        if (result.value) {
            var parameters = {
                "id_departamento": idDepartamento,
                "action": "eliminar_departamento"
            };

            $.ajax({
            data: parameters,
            url: 'ajax/bibliotecasAjax.php',
            type: 'post',
            success: function (response) {

                console.log(response);
                const respuesta = JSON.parse(response);

                console.log(respuesta);

                if (respuesta.response == "success") {

                    tablaDepartamentos.row(".row-"+respuesta.id_departamento).remove().draw(false);

                    $.toast({
                            heading: 'Genial!',
                            text: 'Departamento Eliminado Correctamente.',
                            position: 'top-center',
                            loaderBg:'#00c292',
                            icon: 'success',
                            hideAfter: 3000, 
                            stack: 6
                    });

                }else if(respuesta.response == "solicitudes_activas"){

                    $.toast({

                            heading: 'Error!',
                            text: 'Existen creditos activos en este departamento.',
                            position: 'top-center',
                            loaderBg:'#e46a76',
                            icon: 'error',
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

function eliminarCiudad(idCiudad){

Swal.fire({
    title: 'Estas seguro?',
    text: "Confirma para eliminar esta ciudad!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Si, Estoy seguro!'
}).then((result) => {
    if (result.value) {
        var parameters = {
            "id_ciudad": idCiudad,
            "action": "eliminar_ciudad"
        };

        $.ajax({
        data: parameters,
        url: 'ajax/bibliotecasAjax.php',
        type: 'post',
        success: function (response) {

            console.log(response);
            const respuesta = JSON.parse(response);

            console.log(respuesta);

            if (respuesta.response == "success") {

                tablaCiudades.row(".row-"+respuesta.id_ciudad).remove().draw(false);

                $.toast({
                        heading: 'Genial!',
                        text: 'Ciudad Eliminada Correctamente.',
                        position: 'top-center',
                        loaderBg:'#00c292',
                        icon: 'success',
                        hideAfter: 3000, 
                        stack: 6
                });

            }else if(respuesta.response == "solicitudes_activas"){

                $.toast({

                        heading: 'Error!',
                        text: 'Existen creditos activos en esta ciudad.',
                        position: 'top-center',
                        loaderBg:'#e46a76',
                        icon: 'error',
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