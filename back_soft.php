<?

if (profile(3, $id_usuario) == 1) {


?>

    <style>
        .solicitud-rechazada {
            background-color: #d9534f !important;
        }

        .solicitud-aprobada {
            background-color: #28a745 !important;
        }
    </style>

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
                    <h4 class="text-themecolor"><?= ucwords($page) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item active"><?= ucwords($page) ?></li>
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
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right" onclick="registrarSolicitudModal()">Nueva Solicitud</button>
                            <h4 class="card-title">Solicitudes</h4>
                            <div class="table-responsive m-t-40">
                                <table id="dataTableSolicitudes" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>CC</th>
                                            <th>NOMBRE</th>
                                            <th>NUMERO</th>
                                            <th>REFERENCIA</th>
                                            <th>VALIDADOR</th>
                                            <th>CIUDAD</th>
                                            <th>FECHA REGISTRO</th>
                                            <th>MEDIO</th>
                                            <th>ESTADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $fecha_inicio = '2021-12-07';

                                        $query1 = "SELECT prospectos.id AS id_prospecto, prospecto_cedula, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos.id_estado_prospecto, prospectos.fecha_registro, prospectos.id_usuario_responsable, prospectos.id_medio_envio, modelos.nombre_modelo, ciudades.ciudad FROM `prospectos` LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN modelos ON prospecto_detalles.id_referencia = modelos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id WHERE (prospectos.fecha_registro >= '2021-12-01' AND prospectos.del = 0) AND (prospectos.id_medio_envio = 0 AND prospectos.resultado_dc_prospecto = 1)";

                                        //echo $query1;
                                        //die();
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id = $row1['id_prospecto'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $numero_contacto_confirmacion = $row1['prospecto_numero_contacto'];
                                            $id_estado_prospecto = $row1['id_estado_prospecto'];
                                            $fecha_registro = $row1['fecha_registro'];
                                            $prospecto_nombre = $row1['prospecto_nombre'].' '.$row1['prospecto_apellidos'];
                                            $id_usuario_validador = $row1['id_usuario_responsable'];
                                            
                                            $nom_val = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS nombre_total FROM usuarios WHERE id = $id_usuario_validador");

                                            $id_medio_envio = $row1['id_medio_envio'];

                                            $nombre_modelo = $row1['nombre_modelo'];

                                            $ciudad = $row1['ciudad'];

                                            //$id_estado_prospecto = $row1['id_estado_prospecto'];

                                            if($id_estado_prospecto != 6 && $id_estado_prospecto != 2){

                                        ?>
                                            <tr class="row-<?= $id?>">
                                                <td><?=$id?></td>
                                                <td><?=$prospecto_cedula?></td>
                                                <td><?=$prospecto_nombre?></td>
                                                <td><?= $numero_contacto_confirmacion ?></td>
                                                <td><?= $nombre_modelo?>
                                                <td><?= $id_usuario_validador.' '.$nom_val?></td>
                                                <td><?= $ciudad?></td>
                                                <td><?= $fecha_registro?></td>
                                                <td><?= $id_medio_envio?></td>
                                               <td><?= $id_estado_prospecto?></td>
                                            </tr>
                                        <?
                                        } } ?>
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


 

<?
} else {

    include '401error.php';
}

?>