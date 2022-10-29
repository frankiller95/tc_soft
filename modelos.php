<? 

if (profile(18,$id_usuario)==1){ 

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
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarModeloModal()">Nuevo Modelo</button>
                                    <h4 class="card-title">Modelos</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableModelos" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>MODELO</th>
                                                               <th>PRECIO</th>
                                                                <th>INICIAL 30%</th>
                                                                <th>4 MESES</th>
                                                                <th>6 MESES</th>
                                                                <th>8 MESES</th>
                                                                <th>INICIAL 40%</th>
                                                                <th>4 MESES</th>
                                                                <th>6 MESES</th>
                                                                <th>8 MESES</th>
                                                                <th>INICIAL 50%</th>
                                                                <th>4 MESES</th>
                                                                <th>6 MESES</th>
                                                                <th>8 MESES</th>
                                                               <th>ACCIONES</th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT modelos.id AS id_modelo, marca_producto, nombre_modelo, precio_venta, capacidades_telefonos.capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_modelo = $row1['id_modelo'];
                                                            $marca_producto = $row1['marca_producto'];
                                                            $nombre_modelo = $row1['nombre_modelo'];
                                                            $precio_venta = $row1['precio_venta'];
                                                            $capacidad = $row1['capacidad'];

                                                            $query2 = "SELECT * FROM arandelas_creditos WHERE del = 0";
                                                            $result2 = qry($query2);
                                                            while($row2 = mysqli_fetch_array($result2)){

                                                                $estudio_credito = $row2['estudio_credito'];
                                                                $fianza = $row2['fianza'];
                                                                $interaccion_tecnologica = $row2['interaccion_tecnologica'];
                                                                $beriblock = $row2['beriblock'];
                                                                $seguro_pantalla = $row2['seguro_pantalla'];
                                                                $domicilio = $row2['domicilio'];
                                                                $iva_arandelas = $row2['iva_arandelas'];
                                                                $tasa_interes_usura = $row2['tasa_interes_usura'];

                                                            }

                                                            $arandelas_precio_normal = $estudio_credito + $interaccion_tecnologica + $beriblock + $domicilio;
                                                            $precio_seguro_pantalla = ($seguro_pantalla * $precio_venta)/100;

                                                            //30% inicial
                                                            $porcentaje_inicial = 30;
                                                            $inicial_30 = ($porcentaje_inicial * $precio_venta)/100;
                                                            $valor_credito_30 = $precio_venta - $inicial_30;
                                                            $precio_fianza_30 = ($fianza * $valor_credito_30)/100; 
                                                            $arandelas_total_30 = $arandelas_precio_normal + $precio_seguro_pantalla + $precio_fianza_30;
                                                            $precio_iva_arandelas = ($iva_arandelas * $arandelas_total_30)/100;
                                                            $arandelas_total_30 = $arandelas_total_30 + $precio_iva_arandelas;
                                                            $valor_credito_30 = $valor_credito_30 + $arandelas_total_30;
                                                            
                                                            //40% inicial
                                                            $porcentaje_inicial = 40;
                                                            $inicial_40 = ($porcentaje_inicial * $precio_venta)/100;
                                                            $valor_credito_40 = $precio_venta - $inicial_40;
                                                            $precio_fianza_40 = ($fianza * $valor_credito_40)/100; 
                                                            $arandelas_total_40 = $arandelas_precio_normal + $precio_seguro_pantalla + $precio_fianza_40;
                                                            $precio_iva_arandelas = ($iva_arandelas * $arandelas_total_40)/100;
                                                            $arandelas_total_40 = $arandelas_total_40 + $precio_iva_arandelas;
                                                            $valor_credito_30 = $valor_credito_40 + $arandelas_total_40;

                                                            //50% inicial
                                                            $porcentaje_inicial = 50;
                                                            $inicial_50 = ($porcentaje_inicial * $precio_venta)/100;
                                                            $valor_credito_50 = $precio_venta - $inicial_50;
                                                            $precio_fianza_50 = ($fianza * $valor_credito_50)/100; 
                                                            $arandelas_total_50 = $arandelas_precio_normal + $precio_seguro_pantalla + $precio_fianza_50;
                                                            $precio_iva_arandelas = ($iva_arandelas * $arandelas_total_50)/100;
                                                            $arandelas_total_50 = $arandelas_total_50 + $precio_iva_arandelas;
                                                            $valor_credito_50 = $valor_credito_50 + $arandelas_total_50;

                                                            //cuotas section
                                                            $total_cuotas = 8;
                                                            $cuota_4_meses_30 = $valor_credito_30 / $total_cuotas;
                                                            $cuota_4_meses_40 = $valor_credito_40 / $total_cuotas;
                                                            $cuota_4_meses_50 = $valor_credito_50 / $total_cuotas;

                                                            $total_cuotas = 12;
                                                            $cuota_6_meses_30 = $valor_credito_30 / $total_cuotas;
                                                            $cuota_6_meses_40 = $valor_credito_40 / $total_cuotas;
                                                            $cuota_6_meses_50 = $valor_credito_50 / $total_cuotas;

                                                            $total_cuotas = 16;
                                                            $cuota_8_meses_30 = $valor_credito_30 / $total_cuotas;
                                                            $cuota_8_meses_40 = $valor_credito_40 / $total_cuotas;
                                                            $cuota_8_meses_50 = $valor_credito_50 / $total_cuotas;

                                                            $solicitudes_activas = execute_scalar("SELECT COUNT(solicitudes.id) AS total FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id WHERE solicitudes.del = 0 AND productos_stock.del = 0 AND inventario.del = 0 AND productos.del = 0 AND modelos.id = $id_modelo");
                                                            //echo $solicitudes_activas;

                                                    ?>
                                                        <tr class="row-<?=$id_modelo?>">
                                                               <td><?=$id_modelo?></td>
                                                               <td><?=$marca_producto.' '.$nombre_modelo.' '.$capacidad?></td>
                                                               <td>$<?=number_format($precio_venta, 0, '.', '.')?></td>
                                                               <td><span class="text-danger bold" style="font-weight: bold;">$<?=number_format($inicial_30, 0, '.', '.')?></span></td>
                                                               <td>$<?=number_format($cuota_4_meses_30, 0, '.', '.')?></td>
                                                               <td>$<?=number_format($cuota_6_meses_30, 0, '.', '.')?></td>
                                                               <td>$<?=number_format($cuota_8_meses_30, 0, '.', '.')?></td>
                                                               <td><span class="text-danger" style="font-weight: bold;">$<?=number_format($inicial_40, 0, '.', '.')?></span></td>
                                                               <td>$<?=number_format($cuota_4_meses_40, 0, '.', '.')?></td>
                                                               <td>$<?=number_format($cuota_6_meses_40, 0, '.', '.')?></td>
                                                               <td>$<?=number_format($cuota_8_meses_40, 0, '.', '.')?></td>
                                                               <td><span class="text-danger" style="font-weight: bold;">$<?=number_format($inicial_50, 0, '.', '.')?></span></td>
                                                               <td>$<?=number_format($cuota_4_meses_50, 0, '.', '.')?></td>
                                                               <td>$<?=number_format($cuota_6_meses_50, 0, '.', '.')?></td>
                                                               <td>$<?=number_format($cuota_8_meses_50, 0, '.', '.')?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Modelo" onClick="editarModelo(<?= $id_modelo ?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Modelo" onClick="eliminarModelo(<?= $id_modelo ?>)"><i class="fas fa-trash-alt"></i></a>
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
        <div id="registrar-modelo-modal" class="modal bs-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="titulo_modelos"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <form class="smart-form" enctype="multipart/form-data" id="registrarModeloForm" method="post">
                        <div class="modal-body">
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Marca:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="marca_producto" id="marca_producto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" disabled>Seleccionar Marca</option>
                                                <?php
                                                $query = "select id, marca_producto from marcas where del = 0 order by marca_producto";
                                                $result = qry($query);
                                                while($row = mysqli_fetch_array($result)) {
                                                ?>
                                                    <option value="<?= $row['id']?>"><?= $row['marca_producto']?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Modelo:<span class="text-danger">&nbsp;*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nuevo_modelo" id="nuevo_modelo" placeholder="Escribir nuevo modelo" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Capacidad:</label>
                                        <div class="input-group">
                                            <select  class="form-control select2Class" name="capacidad_modelo" id="capacidad_modelo" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" disabled>Seleccionar Capacidad</option>
                                                <?php
                                                $query = "select id, capacidad from capacidades_telefonos where del = 0 order by capacidad";
                                                $result = qry($query);
                                                while($row = mysqli_fetch_array($result)) {
                                                ?>
                                                    <option value="<?= $row['id']?>"><?= $row['capacidad']?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Precio:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="precio_modelo" id="precio_modelo" placeholder="Precio del modelo" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_modelo" id="id_modelo" value="">
                            <input type="hidden" name="action" id="action_modelo" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="float: left">cancelar</button>
                            <button type="submit" class="btn btn-primary" >Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

<!-- /.modal -->


<script>


function registrarModeloModal(){
    $("#titulo_modelos").html("Ingresar Modelo");
    $("#marca_producto").val('placeholder');
    $("#marca_producto").trigger("change");
    $("#nuevo_modelo").val('');
    $("#capacidad_modelo").val('placeholder');
    $("#capacidad_modelo").trigger('change');
    $("#precio_modelo").val('');
    $("#id_modelo").val('');
    $("#action_modelo").val('insertar');
    $("#registrar-modelo-modal").modal("show");
}

$("#registrarModeloForm").on("submit", function(e){

    // evitamos que se envie por defecto
    e.preventDefault();

     // create FormData with dates of formulary       
    var formData = new FormData(document.getElementById("registrarModeloForm"));

    const action = document.querySelector("#action_modelo").value;

    if(action == "insertar"){

        insertModeloDB(formData);

    }else{

        updateModeloDB(formData);

    }

});

function insertModeloDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/modelosAjax.php', true);
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
                    title: 'Modelo registrado correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaModelos.row.add([
                    respuesta.id_modelo, respuesta.marca+' '+respuesta.modelo+' '+respuesta.capacidad_modelo, respuesta.precio_modelo, respuesta.inicial_30, respuesta.inicial_40, respuesta.inicial_50, respuesta.cuota_4_meses, respuesta.cuota_6_meses, respuesta.cuota_8_meses,  `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Modelo" onClick="editarModelo(${respuesta.id_modelo})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Modelo" onClick="eliminarModelo(${respuesta.id_modelo})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_modelo);

                $("#registrar-modelo-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Modelo ya existe',
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


function editarModelo(idModelo){

    var parameters = {
        "id_modelo": idModelo,
        "action": "select_modelos"
    };

    $.ajax({

        data: parameters,
        url: 'ajax/modelosAjax.php',
        type: 'post',
        success: function (response) {
            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            $("#titulo_modelos").html("Editar Modelo");
            $("#marca_producto").val(respuesta[0].id_marca);
            $("#marca_producto").trigger("change");
            $("#nuevo_modelo").val(respuesta[0].modelo);
            $("#capacidad_modelo").val(respuesta[0].id_capacidad);
            $("#capacidad_modelo").trigger('change');
            $("#precio_modelo").val(respuesta[0].precio_venta);
            $("#id_modelo").val(idModelo);
            $("#action_modelo").val('editar');
            $("#registrar-modelo-modal").modal("show");

        }

    });

}


function updateModeloDB(dates){

    /** Call to Ajax **/
    // create the object
    const xhr = new XMLHttpRequest();
    // open conection
    xhr.open('POST', 'ajax/modelosAjax.php', true);
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
                    title: 'Modelo Actualizado correctamente',
                    showConfirmButton: false,
                    timer: 2500

                });

                tablaModelos.row(".row-"+respuesta.id_modelo).remove().draw(false);

                tablaModelos.row.add([
                    respuesta.id_modelo, respuesta.marca+' '+respuesta.modelo+' '+respuesta.capacidad_modelo, respuesta.precio_modelo, respuesta.inicial_30, respuesta.inicial_40, respuesta.inicial_50, respuesta.cuota_4_meses, respuesta.cuota_6_meses, respuesta.cuota_8_meses,  `<div class="jsgrid-align-center"><a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Modelo" onClick="editarModelo(${respuesta.id_modelo})"><i class="mdi mdi-pencil"></i></a>
                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Modelo" onClick="eliminarModelo(${respuesta.id_modelo})"><i class="fas fa-trash-alt"></i></a></div>`
                ]).draw(false).nodes().to$().addClass("row-"+respuesta.id_modelo);

                $("#registrar-modelo-modal").modal("hide");

            }else if(respuesta.response == "existe"){

                Swal.fire({

                        position: 'top-end',
                        type: 'error',
                        title: 'Modelo ya existe',
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


function eliminarModelo(idModelo){

    Swal.fire({
        title: 'Estas seguro?',
        text: "Por favor confirmar para eliminar esta Modelo!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {
        if (result.value) {
            var parameters = {
                "id_modelo": idModelo,
                "action": "eliminar_modelo"
            };

            $.ajax({
            data: parameters,
            url: 'ajax/modelosAjax.php',
            type: 'post',
            success: function (response) {

                console.log(response);
                const respuesta = JSON.parse(response);

                console.log(respuesta);

                if (respuesta.response == "success") {

                    tablaModelos.row(".row-"+respuesta.id_modelo).remove().draw(false);

                    $.toast({
                            heading: 'Genial!',
                            text: 'Modelo Eliminado Correctamente.',
                            position: 'top-center',
                            loaderBg:'#00c292',
                            icon: 'success',
                            hideAfter: 4500, 
                            stack: 6
                    });

                }else if(respuesta.response == "solicitudes_activas"){

                    $.toast({

                            heading: 'Error!',
                            text: 'Modelo con credito activo.',
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