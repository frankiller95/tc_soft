<? 

if (profile(10,$id_usuario)==1){ 

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
                                    <h4 class="card-title">Clientes Crediavales</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableCrediavales" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>CEDULA</th>
                                                               <th>NOMBRE CLIENTE</th>
                                                               <th>TELEFONO</th>
                                                               <th>DIRECCION</th>
                                                               <th>PRODUCTO</th>
                                                               <th>INICIAL</th>
                                                               <th>CUOTAS</th>
                                                               <th>VALOR TOTAL</th>
                                                               <th></th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT form_tratamiento_datos.id AS id_trata, cedula, fecha_exp, nombre_apellidos, direccion_ciudad, telefono_contacto, trabajo_ciudad, telefono_trabajo, cargo, salario, antiguedad, referencia1, referencia2, referencia3, id_modelo_compra, nombre_modelo, cuota_inicial, cuotas_numero, valor_cuota, valor_total, codigo, clave FROM form_tratamiento_datos LEFT JOIN modelos ON form_tratamiento_datos.id_modelo_compra = modelos.id WHERE form_tratamiento_datos.del = 0";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_trata = $row1['id_trata'];
                                                            $cedula = $row1['cedula'];
                                                            $nombre_apellidos = $row1['nombre_apellidos'];
                                                            $contacto = $row1['telefono_contacto'];
                                                            $direccion_ciudad = $row1['direccion_ciudad'];
                                                            $nombre_modelo = $row1['nombre_modelo'];
                                                            $cuota_inicial = number_format($row1['cuota_inicial'], 0, '.', '.');
                                                            $cuotas_numero = $row1['cuotas_numero'];
                                                            $valor_total = number_format($row1['valor_total'], 0, '.', '.');

                                                    ?>
                                                               <tr class="row-<?=$id_trata?>">
                                                                <td><<?=$id_trata?>/td>
                                                               <td><?=$cedula?></td>
                                                               <td><?=$nombre_apellidos?></td>
                                                               <td><?=$contacto?></td>
                                                               <td><?=$direccion_ciudad?></td>
                                                               <td><?=$nombre_modelo?></td>
                                                               <td><?=$cuota_inicial?></td>
                                                               <td><?=$cuotas_numero?></td>
                                                               <td><?=$valor_total?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="generar_trata_datos_form.php?id=<?=$id_trata?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Documento" target="_blank"><i class="fas fa-eye"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Registro" onClick="eliminarCrediavales(<?= $id_trata ?>)"><i class="fas fa-trash-alt"></i></a>
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
   


<script>

function eliminarCrediavales(idTrata){

    Swal.fire({
        title: 'Estas seguro?',
        text: "si eliminas este registro se perdera la información de este cliente.!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Estoy seguro!'
    }).then((result) => {
        if (result.value) {
            var parameters = {
                "id_trata": idTrata,
                "action": "eliminar"
            };

            $.ajax({
data: parameters,
url: 'ajax/trataAjax.php',
type: 'post',
success: function (response) {

                console.log(response);
                const respuesta = JSON.parse(response);

                console.log(respuesta);

                if (respuesta.response == "success") {

                    tablaCrediavales.row(".row-"+respuesta.id_trata).remove().draw(false);

                    $.toast({
                            heading: 'Genial!',
                            text: 'Registro Eliminado Correctamente.',
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