<? if (profile(21,$id_usuario)==1){  ?>

<!-- ============================================================== -->

<div class="response" style="
    width: 100%;
    height: 100%;
    top: 0;
    position: fixed;
    z-index: 99999;
    background: #fff;
    display: none;
    ">
    <div class="loader">
       <div class="loader__figure"></div>
        <p class="loader__label">POR FAVOR ESPERE...</p> 
    </div> 
</div>

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
                <h4 class="text-themecolor"><?=ucwords(str_replace("_", " ",$page))?></h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active"><?=ucwords(str_replace("_", " ",$page))?></li>
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



        <!-- row details -->

<!-- update orders section -->
        <div class="row filters-space">

            <div class="col-12">

                <div class="card">

                    <div class="card-body">

                        <div class="tab-content">

                            <form class="smart-form" name="filtrosReporte1" id="filtrosReporte1" method="post" action="" data-ajax="false">

                                <div class="row pt-3">

                                    <div class="col-md-12">

                                        <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos, para ver todos los clientes deja los filtros vacios.</h6>

                                    </div>

                                </div>

                                <br>


                                <hr class="m-t-0 m-b-40">

                                <div class="row pt-3">

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Fecha Inicio:</label>

                                            <div class="input-group">

                                                <div class="input-group-append">

                                                    <span class="input-group-text"><i class="fas fa-hourglass-start"></i></span>

                                                </div>

                                                <input id="fecha_inicio" name="fecha_inicio" class="form-control date_start_filter"  placeholder="Seleccione fecha de inicio">

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>Fecha Final:</label>

                                            <div class="input-group">

                                                <div class="input-group-append">

                                                    <span class="input-group-text"><i class="fas fa-hourglass-end"></i></span>

                                                </div>
                                                
                                                <input id="fecha_final" name="fecha_final" class="form-control date_end_filter" placeholder="Seleccione fecha final" >


                                            </div>

                                        </div>

                                    </div>  


                                    <div class="col-md-4">

                                        <div class="form-group">

                                        <label>Seleccione Cedula Cliente:</label>

                                            <div class="input-group">

                                               <select  class="form-control select2Class" name="cedula_prospecto" id="cedula_prospecto" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes">

                                                        <option value="placeholder" disabled selected>Seleccionar Cedula</option>

                                                        <option value="0" >Todos los Clientes</option>

                                                        <?

                                                        $query = "SELECT prospectos.id AS id_prospecto, prospecto_cedula, prospecto_nombre, prospecto_apellidos FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.del = 0 AND prospectos.id_plataforma = 1 ORDER BY prospecto_detalles.prospecto_nombre";

                                                        $result = qry($query);

                                                        while($row = mysqli_fetch_array($result)) {

                                                            $id_prospecto = $row['id_prospecto'];
                                                            $prospecto_cedula = $row['prospecto_cedula'];
                                                            $prospecto_nombre = $row['prospecto_nombre'];
                                                            $prospecto_apellidos = $row['prospecto_apellidos'];

                                                        ?>

                                                            <option value="<?=$id_prospecto?>"><?=$prospecto_cedula.'-'.$prospecto_nombre.', '.$prospecto_apellidos?></option>

                                                        <? } ?>

                                                    </select>

                                            </div>

                                        </div>

                                    </div>

                                    

                                </div>


                                <div class="row">

                                    <div class="col-md-4">

                                        <button type="submit" style="float: left; margin-left: 0px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-success" >Generar</button>

                                        <!--<button type="button" style="float: left; margin-left: 20px; margin-top: 20px;" class="btn waves-effect waves-light btn-lg btn-success" onclick="queryAllFacilities()">All Facilities</button>-->

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- END update orders section -->

        <!-- Row -->
        <div class="row" id="table-space-report1">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Clientes Adelantos</h4>
                        <div class="table-responsive m-t-40 table_clientes_adelantos">
                            <table id="dataTableClientesAdelantos" class="table-patient table display table-bordered table-striped no-wrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>CEDULA #</th>
                                        <th>PROSPECTO</th>
                                        <th>CIUDAD</th>
                                        <th>CREADO EN</th>
                                        <th>RESPONSABLE</th>
                                        <th>FECHA</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?

                                    $condition = "";

                                    if (profile(14, $id_usuario)!=1) {
                                                            
                                        $condition = " AND prospectos.id_responsable_interno = $id_usuario";

                                    }

                                    $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y %H:%i:%s') AS fecha_registro_prospecto FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id WHERE prospectos.del = 0 AND prospectos.id_plataforma = 1 $condition";
                                    $result = qry($query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id_prospecto = $row['id_prospecto'];
                                        $prospecto_cedula = $row['prospecto_cedula'];
                                        $prospecto_nombre = $row['prospecto_nombre'];
                                        $prospecto_apellidos = $row['prospecto_apellidos'];
                                        $ciudad = $row['ciudad'];
                                        $departamento = $row['departamento'];
                                        $id_usuario_responsable = $row['id_usuario_responsable'];
                                        $id_responsable_interno = $row['id_responsable_interno'];

                                        $fecha_registro_prospecto = $row['fecha_registro_prospecto'];

                                        $validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");
                                        if ($validate_gane == 1) {
                                            $creado_en = execute_scalar("SELECT nombre_punto FROM puntos_gane WHERE id_usuario = $id_usuario_responsable");
                                        }else{
                                            $creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
                                        }

                                        if($id_responsable_interno == 0){
                                            $responsable = '<span class="label label-success" style="font-size: 16px;">EN COLA</span>';
                                        }else{
                                            $responsable = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) FROM usuarios WHERE id = $id_responsable_interno");
                                        }

                                    ?>
                                    <tr class="row-<?=$id_prospecto?>">
                                        <td><?=$id_prospecto?></td>
                                        <td><?=$prospecto_cedula?></td>
                                        <td><?=$prospecto_nombre.', '.$prospecto_apellidos?></td>
                                        <td><?=$ciudad.', '.$departamento?></td>
                                        <td><?=$creado_en?></td>
                                        <td><?=$responsable?></td>
                                        <td><?=$fecha_registro_prospecto?></td>
                                        <td>
                                            <a href="?page=prospecto&id=<?=$id_prospecto?>" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Prospecto"><i class="mdi mdi-pencil"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Venta no realizada" onClick="VentaNoRealizada(<?= $id_prospecto ?>)"><i class="fas fa-exclamation-circle"></i></a>
                                        </td>
                                    </tr>
                                    <?
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>


    </div>


</div>

<script>

    'use strict';

    var table1 = $(".table_puntos_gane");
    

    var space1 = $(".filters-space");
    

    var space2 = $("#table-space-report1");
    

    function waitMoment(){
        $('.response').show();
    }

    $("#filtrosReporte1").on("submit", function(e){

        // evitamos que se envie por defecto
        e.preventDefault();

        // create FormData with dates of formulary       
        var formData = new FormData(document.getElementById("filtrosReporte1"));

        const action = "reporte_puntos_gane";

        formData.append('action', action);

        SelectReport1BD(formData);

    });

    function SelectReport1BD(dates){

        /** Call to Ajax **/
        
        // create the object
            const xhr = new XMLHttpRequest();

            xhr.addEventListener("load", waitMoment());
        // open conection
            xhr.open('POST', 'Ajax/reportesAjax.php', true);
        // pass Info
            xhr.onload = function(){
                //the conection is success
                console.log(this.status);
                if (this.status === 200) {

                    $('.response').hide();
                    /* in case of problem of json object, use this code console.log(xhr.responseText); */
                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    table1.empty();
                    space1.hide();
                    space2.show();
 

                            var theTable = `<table id="dataTablePuntosProspectos" class="table-patient table display table-bordered table-striped no-wrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>CEDULA #</th>
                                        <th>PROSPECTO</th>
                                        <th>CIUDAD</th>
                                        <th>CREADO EN</th>
                                        <th>DIRECCION PUNTO</th>
                                        <th>FECHA</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                                var tableTr = '';
                                //for (i = 0; i < ToJson.length; i++) { 
                                respuesta[0].forEach(function(prospectos, index) {

                                    tableTr += `<tr>
                                                    <td>${prospectos.id_prospecto}</td>
                                                    <td>${prospectos.prospecto_cedula}</td>
                                                    <td>${prospectos.prospecto_nombre+' '+prospectos.prospecto_apellidos}</td>
                                                    <td>${prospectos.ciudad+'/ '+prospectos.departamento}</td>
                                                    <td>${prospectos.nombre_punto}</td>
                                                    <td>${prospectos.direccion_punto}</td>
                                                    <td>${prospectos.fecha_creado}</td>
                                                    </tr>`;
                                                    


                                });


                                theTable += tableTr;
                                theTable += `</tbody> 
                                                </table>`;

                                // Añadimos el option al select 
                                table1.append(theTable);

                                
                                tablePuntosGane = $('#dataTablePuntosProspectos').DataTable({
                                    dom: 'Bfrtip',
                                    buttons: [
                                    'copy', 
                                    {
                                        extend: 'excelHtml5',
                                        title: 'Reporte_puntos_gane '
                                    }, 
                                    {
                                        extend: 'pdfHtml5',
                                        title: 'Reporte_puntos_gane'
                                    },
                                    'print'
                                    ],
                                    "order": [[ 0, "asc" ]]
                                });
                                tablePuntosGane.column(0).visible(false);
                    
                }else{

                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'There is an error in the operation',
                        showConfirmButton: false,
                        timer: 2000
                        })

                    //setTimeout("location.reload()", 2000);
                }   
            }
            // send dates
            xhr.send(dates) 

    }

    function cerrarReporte(){

        $("#fecha_inicio").val('');
        $("#fecha_final").val('');
        $("#puntos_gane").val('placeholder');
        $("#puntos_gane").trigger("change");
        table1.empty();
        space2.hide();
        space1.show();

    }


</script>

<?

}else{

    include '401error.php';

}

?>