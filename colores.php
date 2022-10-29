<?

if (profile(29,$id_usuario)==1){ 

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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarColorModal()">Nuevo Color</button>
                                    <h4 class="card-title">Colores Dispositivos</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableColores" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>COLOR</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT colores_productos.id AS id_color, color_desc FROM colores_productos WHERE colores_productos.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_color = $row1['id_color'];
                                                            $color_desc = $row1['color_desc'];

                                                    ?>
                                                        <tr class="row-<?=$id_color?>">
                                                               <td><?=$id_color?></td>
                                                               <td><?=$color_desc?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Color" onClick="editarColor(<?= $id_color ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Color" onClick="eliminarColor(<?= $id_color ?>)"><i class="fas fa-trash-alt"></i></a>
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
   

        <!-- Add color modal -->
        <div id="color-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_colores"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarColoresForm" method="post">
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
                                        <label>Color:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nuevo_color" id="nuevo_color" placeholder="Nombre del color" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_color" id="id_color" value="">
                            <input type="hidden" name="action" id="action_color" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<script>


function registrarColorModal(){

    $("#titulo_colores").html("Agregar Color");
    $("#nuevo_color").val('');
    $("#id_color").val('');
    $("#action_color").val('insertar_color');
    $("#color-modal").modal("show");

}


$("#registrarColoresForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

     // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("registrarColoresForm"));

    const action = document.querySelector("#action_color").value;

    if(action == "insertar_color"){

        insertColorDB(formData);

    }else{

        updateColorDB(formData);

    }

});

function insertColorDB(dates){

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
                    title: 'Color Ingresado Correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaColores.row.add([
                    respuesta.id_color, respuesta.color_desc, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Color" onClick="editarColor(${respuesta.id_color})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Color" onClick="eliminarColor(${respuesta.id_color})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_color);

                $("#color-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Color ya existe',
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


function editarColor(idColor){

    var parameters = {
        "id_color": idColor,
        "action": "select_color"
    };

    $.ajax({

        data: parameters,
        url: 'ajax/bibliotecasAjax.php',
        type: 'post',
        success: function (response) {
            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#titulo_colores").html("Actualizar Color");
            $("#nuevo_color").val(respuesta[0].color_desc);
            $("#id_color").val(respuesta[0].id_color);
            $("#action_color").val('editar_color');
            $("#color-modal").modal("show");

        }

    });

}


function updateColorDB(dates){

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
                    title: 'Color Actualizado correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaColores.row(".row-"+respuesta.id_color).remove().draw(false);

                tablaColores.row.add([
                    respuesta.id_color, respuesta.color_desc, `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Color" onClick="editarColor(${respuesta.id_color})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Color" onClick="eliminarColor(${respuesta.id_color})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_color);

                $("#color-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Color ya existe',
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


function eliminarColor(idColor){

    Swal.fire({
        title: 'Estas Seguro?',
        text: "Por favor confirmar para eliminar este Color!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {
        if (result.value) {
            var parameters = {
                "id_color": idColor,
                "action": "eliminar_color"
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

                    tablaColores.row(".row-"+respuesta.id_color).remove().draw(false);

                    $.toast({
                            heading: 'Genial!',
                            text: 'Color Eliminado Correctamente.',
                            position: 'top-center',
                            loaderBg:'#00c292',
                            icon: 'success',
                            hideAfter: 3000, 
                            stack: 6
                    });

                }else{

                    $.toast({

                            heading: 'Error!',
                            text: 'Error en el proceso.',
                            position: 'top-center',
                            loaderBg:'#e46a76',
                            icon: 'error',
                            hideAfter: 3000, 
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