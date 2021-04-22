
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
                                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Solicitudes</h4>
                                    <div class="table-responsive m-t-40">
                                                <table id="dataTableUsuarios" class="table display table-bordered table-striped no-wrap">
                                                        <thead>
                                                            <tr>
                                                               <th>ID</th>
                                                               <th>CLIENTE</th>
                                                               <th>PRODUCTO</th>
                                                               <th>CONTACTO</th>
                                                               <th>CORREO</th>
                                                               <th>INICIAL</th>
                                                               <th>ESTADO</th>
                                                               <th></th>
                                                           </tr>
                                                       </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        $query1 = "SELECT solicitudes.id AS id_solicitud, cliente_detalles.cliente_nombre, cliente_detalles.cliente_apellidos, productos.modelo_producto, cliente_detalles.cliente_numero_contacto, cliente_detalles.cliente_email, solicitudes.inicial, solicitudes.id_estado_solicitud,estados_solicitudes.texto_estado FROM solicitudes LEFT JOIN clientes ON solicitudes.id_cliente = clientes.id LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN productos ON solicitudes.id_producto = productos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.del = 0 ORDER BY solicitudes.id DESC";
                                                        $result1 = qry($query1);
                                                        while($row1 = mysqli_fetch_array($result1)) {

                                                            $texto_estado = $row1['texto_estado'];

                                                            $id_solicitud = $row1['id_solicitud'];
                                                            $cliente_nombre = $row1['cliente_nombre'].' '.$row1['cliente_apellidos'];
                                                            $modelo_producto = $row1['modelo_producto'];
                                                            $cliente_numero_contacto = $row1['cliente_numero_contacto'];
                                                            $contacto = '('.substr($cliente_numero_contacto, 0,3).') '.substr($cliente_numero_contacto, 3,3).'-'.substr($cliente_numero_contacto, 6,4);
                                                            $cliente_email = $row1['cliente_email'];
                                                            $inicial = $row1['inicial'];
                                                            if($texto_estado == 9){
                                                                $modelo_producto = 'N/A';
                                                                $inicial = 'N/A';
                                                            }
                                                            $id_estado_solicitud = $row1['id_estado_solicitud'];
                                                    ?>
                                                               <tr class="row-<?=$id_solicitud?>">
                                                               <td><?=$id_solicitud?></td>
                                                               <td><?=$cliente_nombre?></td>
                                                               <td><?=$modelo_producto?></td>
                                                               <td><?=$contacto?></td>
                                                               <td><?=$cliente_email?></td>
                                                               <td><?=$inicial?></td>
                                                                <?php if($id_estado_solicitud == 9){?>
                                                                <td><span class="label label-info">Sin producto</span></td>
                                                                <?php }else if($id_estado_solicitud == 1){ ?>
                                                                <td><span class="label label-info">Inicial</span></td>
                                                                <?php } ?>
                                                                <td class="jsgrid-cell jsgrid-control-field jsgrid-align-center">
                                                                    <a href="?page=solicitud&id_solicitud=<?=$id_solicitud?>" class="btn btn-outline-info waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Detalles solicitud"><i class="mdi mdi-pencil"></i></a>
                                                                <?php if($id_estado_solicitud == 1){?>
                                                                    <a href="sms:<?=$cliente_numero_contacto?>?body=Estimado%20Sr%20<?=$cliente_nombre?>%20su%20codigo%20para%20autorizar%20el%20tratamiento%20de%20datos%20es%20347602%20" class="btn btn-outline-dark waves-effect waves-light button-sms" class="btn btn-outline-success waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Enviar codigo confirmación"><i class="fas fa-comment-alt"></i></a>
                                                                <?php } ?>
                                                                    <a href="javascript:void(0)" class="btn btn-outline-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Eliminar solicitud" onClick="eliminarSolicitud(<?= $id_solicitud ?>)"><i class="fas fa-trash-alt"></i></a>
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