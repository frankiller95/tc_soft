<? 

if (profile(5,$id_usuario)==1){ 

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
                                    <h4 class="card-title">Dispositivos Nuovo</h4>
                                    <div class="table-responsive m-t-40" id="table-nuovo">
                                                
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

var tableSpace, theTable, theRow;

$(document).ready(function() {

   var parameters = {
        "action": "select_dispositivos"
    };

    $.ajax({

        data:parameters,
        url:'ajax/nuovoAjax.php',
        type:'post',
        success:function (response) {

            console.log(response);
            const respuesta = JSON.parse(response);
            console.log(respuesta);

            tableSpace = $("#table-nuovo");

            tableSpace.empty();

            theTable = `<table id="dataTableNuovo" class="table display table-bordered table-striped no-wrap">
            <thead>
            <tr>
            <th>ID DISPOSITIVO</th>
            <th>IMEI No</th>
            <th>SERIAL No</th>
            <th>MODELO</th>
            <th>ESTADO</th>
            <th>ULTIMA CONEXIÓN</th>
            <th>LOCALIZACIÓN</th>
            <th>ACCIONES</th>
            </tr>
            </thead>
            <tbody>`;

            var lockIcon, actionLocked;

            respuesta.forEach(function(dispositivos, index) {

                if(dispositivos.locked === true){
                    lockIcon = `<i class="fas fa-lock text-danger"></i>`;

                    actionLocked = `<a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Bloquear equipo" onClick="bloquearEquipo(${dispositivos.id})"><i class="fas fa-lock-open"></i></a>`;

                }else{

                    lockIcon = `<i class="fas fa-lock-open text-success"></i>`;

                     actionLocked = `<a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Desbloquear equipo" onClick="desbloquearEquipo(${dispositivos.id})"><i class="fas fa-lock"></i></a>`;


                }

                theRow += `<tr class="row-${dispositivos.id}">
                                <td>${dispositivos.id}&nbsp;${lockIcon}</td>
                                <td>${dispositivos.imei_no}-${dispositivos.imei_no2}</td>
                                <td>${dispositivos.serial_no}</td>
                                <td>${dispositivos.model}</td>
                                <td><span class="label label-info">${dispositivos.status}</span></td>
                                <td>${dispositivos.last_connected_at}</td>
                                <td>${dispositivos.location.address}</td>
                                <td>
                                    <div class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                        ${actionLocked}
                                        <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar Recordatorio" onClick="enviarRecordatorio(${dispositivos.id})"><i class="fas fa-bell"></i></a> 
                                        <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="eliminar dispositivo" onClick="eliminarDispositivo(${dispositivos.id})"><i class="fas fa-trash"></i></a>                              
                                    </div>
                                </td>
                                </td>
                            </tr>`;

            });

            theTable += theRow;

            theTable += `</tbody> 
                        </table>`;

            tableSpace.append(theTable);

            tablaNuovo =  $('#dataTableNuovo').DataTable({
                dom: 'Bfrtip',
                buttons: [
                'copy', 'excel', 'pdf', 'print'
                ],
                "order": [[ 0, "desc" ]],
                responsive: true
            });

        }
    });

});

</script>

<?
}else{

    include '401error.php';

}

?>