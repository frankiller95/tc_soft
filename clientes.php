<? 

if (profile(2,$id_usuario)==1){ 

?>
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
                                    <h4 class="card-title">Clientes</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableClientes" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>C.C</th>
                                                               <th>NOMBRE</th>
                                                               <th>CONTACTO</th>
                                                               <th>EMAIL</th>
                                                               <th>UBICACIÓN</th>
                                                               <th></th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, CONCAT(prospecto_nombre, ' ', prospecto_apellidos) AS full_prospecto, prospecto_numero_contacto, prospecto_email, CONCAT(prospecto_direccion, ', ',ciudades.ciudad) AS ubicacion FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN solicitudes ON solicitudes.id_prospecto = prospectos.id WHERE prospectos.del = 0 AND solicitudes.del = 0 AND solicitudes.id_estado_solicitud = 8";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $id_prospecto = $row1['id_prospecto'];
                                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                                            $full_prospecto = $row1['full_prospecto'];
                                                            $contacto = $row1['prospecto_numero_contacto'];
                                                            $contacto = '('.substr($contacto, 0,3).')'.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
                                                            $email = $row1['prospecto_email'];
                                                            $ubicacion = $row1['ubicacion'];

                                                    ?>
                                                               <tr class="row-<?=$id_prospecto?>">
                                                               <td><?=$id_prospecto?></td>
                                                               <td><?=$prospecto_cedula?></td>
                                                               <td><?=$full_prospecto?></td>
                                                               <td><?=$contacto?></td>
                                                               <td><?=$email?></td>
                                                               <td><?=$ubicacion?></td>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="javascript:void(0)" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Editar Cliente" onClick="editarCliente(<?=$id_prospecto?>)"><i class="mdi mdi-pencil"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Ver Imagenes" onClick="verImagenesC(<?= $id_prospecto ?>)"><i class="fas fa-eye"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar Cliente" onClick="eliminarCliente(<?= $id_prospecto ?>)"><i class="fas fa-trash-alt"></i></a>
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
                </div>
            </div>
         


<script>

 
</script>

<?
}else{

    include '401error.php';

}

?>