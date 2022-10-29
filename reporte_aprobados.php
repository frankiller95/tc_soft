z<? if (profile(39, $id_usuario) == 1) {

$profile_14 = profile(14, $id_usuario);

?>

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
                <h4 class="text-themecolor"><?= ucwords(str_replace("_", " ", $page)) ?></h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active"><?= ucwords(str_replace("_", " ", $page)) ?></li>
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
        

        <!-- Row -->
        <div class="row" id="tabla-prospectos-aprobados">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title" id="titulo_reporte">APROBADOS</h4>
                        <div class="table-responsive m-t-40 table_prospectos_aprobados">
                        <table id="dataTableAprobados" class="table display table-bordered table-striped no-wrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>CLIENTE</th>
                                            <th>CONTACTO</th>
                                            <th>ENTREGADO</th>
                                            <th>PORCENTAJE INICIAL</th>
                                            <th>PLAZO</th>
                                            <th>PLATAFORMA</th>
                                            <th>MODELO</th>
                                            <th>MEDIO DE ENVIO</th>
                                            <th>FECHA CREACIÓN</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $query1 = "SELECT prospectos.id AS id_prospecto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos.prospecto_cedula, prospecto_detalles.prospecto_numero_contacto, estados_prospectos.estado_prospecto, solicitudes.id_porcentaje_inicial, solicitudes.id_terminos_prestamo, prospectos.resultado_dc_prospecto, prospectos.fecha_registro, prospectos.id_medio_envio, plataformas_credito.nombre_plataforma, modelos.nombre_modelo FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id LEFT JOIN solicitudes ON solicitudes.id_prospecto = prospectos.id LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN modelos ON prospecto_detalles.id_referencia = modelos.id WHERE prospectos.resultado_dc_prospecto = 1 AND prospectos.del = 0";
                                        
                                        $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_prospecto = $row1['id_prospecto'];
                                            $prospecto_cedula = $row1['prospecto_cedula'];
                                            $prospecto_nombre = $row1['prospecto_nombre'];
                                            $prospecto_apellidos = $row1['prospecto_apellidos'];
                                            $prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
                                            $estado_prospecto = $row1['estado_prospecto'];
                                            $id_porcentaje_inicial = $row1['id_porcentaje_inicial'];
                                            $id_terminos_prestamo = $row1['id_terminos_prestamo'];
                                            $fecha_registro = $row1['fecha_registro'];

                                            $id_medio_envio = $row1['id_medio_envio'];

                                            $envio_texto = "N/A";
                                           
                                           if($id_medio_envio == 1){
                                            $envio_texto = "DOMICILIO";
                                           }else if($id_medio_envio == 2){
                                            $envio_texto = "SERVIENTREGA";
                                           }else if($id_medio_envio == 3){
                                            $envio_texto = "RECOGE EN TIENDA";
                                           }

                                           $nombre_plataforma = $row1['nombre_plataforma'];

                                           $nombre_modelo = $row1['nombre_modelo'];

                                        ?>
                                            <tr class="row-<?= $id_prospecto ?>">
                                                <td><?= $id_prospecto ?></td>
                                                <td><?= $prospecto_cedula.'-'.$prospecto_nombre . ' ' . $prospecto_apellidos?></td>
                                                <td><?= $prospecto_numero_contacto ?></td>
                                                <td><?= $estado_prospecto?></td>
                                                <td><?= $id_porcentaje_inicial?></td>
                                                <td><?= $id_terminos_prestamo ?></td>
                                                <td><?=$nombre_plataforma?></td>
                                                <td><?=$nombre_modelo?></td>
                                                <td><?= $envio_texto?></td>
                                                <td><?= $fecha_registro ?></td>
                                                <td></td>
                                            </tr>
                                        <?php } ?>
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
 
</script>

<?

} else {

include '401error.php';
}

?>