<? 

if (profile(17,$id_usuario)==1){ 

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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarMarcaModal()">Nueva Marca</button>
                                    <h4 class="card-title">Marcas</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableMarcas" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>MARCA</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT id, marca_producto FROM marcas WHERE marcas.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_marca = $row1['id'];
                                                            $marca_producto = $row1['marca_producto'];

                                                    ?>
                                                               <tr class="row-<?=$id_marca?>">
                                                               <td><?=$id_marca?></td>
                                                               <td><?=$marca_producto?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Marca" onClick="editarMarca(<?= $id_marca ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Marca" onClick="eliminarMarca(<?= $id_marca ?>)"><i class="fas fa-trash-alt"></i></a>
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
   

        <!-- Add marca modal -->
        <div id="registrar-marca-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_marcas"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarMarcaForm" method="post">
                        <div class="modal-body">
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Marca:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nueva_marca" id="nueva_marca" placeholder="Escribir nueva marca" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_marca" id="id_marca" value="">
                            <input type="hidden" name="action" id="action_marca" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<script>


function registrarMarcaModal(){

    $("#titulo_marcas").html("Ingresar Marca");
    $("#nueva_marca").val('');
    $("#id_marca").val('');
    $("#action_marca").val('insertar');
    $("#registrar-marca-modal").modal("show");
    
}

$("#registrarMarcaForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

     // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("registrarMarcaForm"));

    const action = document.querySelector("#action_marca").value;

    if(action == "insertar"){

        insertMarcaDB(formData);

    }else{

        updateMarcaDB(formData);

    }

});

function insertMarcaDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/marcasAjax.php', true);
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
                    title: 'Nueva marca registrada correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaMarcas.row.add([
                    respuesta.id_marca, respuesta.marca, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarMarca(${respuesta.id_marca})"><i class="mdi mdi-pencil"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarMarca(${respuesta.id_marca})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_marca);

                $("#registrar-marca-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Marca ya existe',
                        showConfirmButton: false,
                        timer: 3500

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


function editarMarca(idMarca){

    var parameters = {
        "id_marca": idMarca,
        "action": "select_marcas"
    };

    $.ajax({

        data: parameters,
        url: 'ajax/marcasAjax.php',
        type: 'post',
        success: function (response) {
            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#titulo_marcas").html("Editar Marca");
            $("#nueva_marca").val(respuesta[0].marca);
            $("#id_marca").val(respuesta[0].id_marca);
            $("#action_marca").val('editar');
            $("#registrar-marca-modal").modal("show");

        }

    });

}


function updateMarcaDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/marcasAjax.php', true);
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
                    title: 'Marca Actualizada Correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaMarcas.row(".row-"+respuesta.id_marca).remove().draw(false);

                tablaMarcas.row.add([
                    respuesta.id_marca, respuesta.marca, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Producto" onClick="editarMarca(${respuesta.id_marca})"><i class="mdi mdi-pencil"></i></a>
                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Producto" onClick="eliminarMarca(${respuesta.id_marca})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_marca);

                $("#registrar-marca-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Marca ya existe',
                        showConfirmButton: false,
                        timer: 3500

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


function eliminarMarca(idMarca){

    Swal.fire({
        title: 'Estas seguro?',
        text: "Por favor confirmar para eliminar esta marca!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {
        if (result.value) {
            var parameters = {
                "id_marca": idMarca,
                "action": "eliminar_marca"
            };

            $.ajax({
data: parameters,
url: 'ajax/marcasAjax.php',
type: 'post',
success: function (response) {

                console.log(response);
                const respuesta = JSON.parse(response);

                console.log(respuesta);

                if (respuesta.response == "success") {

                    tablaMarcas.row(".row-"+respuesta.id_marca).remove().draw(false);

                    $.toast({
                            heading: 'Genial!',
                            text: 'Marca Eliminada Correctamente.',
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