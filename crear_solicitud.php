<? 

if (profile(3,$id_usuario)==1){ 

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
                            <h4 class="text-themecolor"><?=str_replace('_', ' ', ucwords($page))?></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                                    <li class="breadcrumb-item active"><?=str_replace('_', ' ', ucwords($page))?></li>
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
                    <div class="row" id="solicitud-zone">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Buscar Prospecto</h4>
                                    <div class="row pt-3">
                                        <div class="col-md-6">
                                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <input style="display:none" type="text" name="falsocodigo" autocomplete="off" />
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Busqueda por nombre o cedula:<span class="text-danger">&nbsp;*</span></label>
                                                <div class="input-group">
                                                    <select  class="form-control select2Class" name="prospecto_solicitud" id="prospecto_solicitud" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                        <option value="placeholder" disabled selected>Seleccionar Prospecto</option>
                                                        <?php
                                                        $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS nombre_prospecto FROM `prospectos` LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.del = 0 AND prospectos.id_responsable_interno = <?=$id_usuario?>";
                                                        $result = qry($query);
                                                        while($row = mysqli_fetch_array($result)) {

                                                            $validar_solicitudes_activas = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE solicitudes.id_prospecto = <?=$id_prospecto?> AND solicitudes.id_estado_solicitud <> 8 AND solicitudes.del = 0");

                                                        
                                                        ?>
                                                            <option value="<?= $row['id_prospecto']?>"><?= $row['nombre_prospecto'].' - '.$row['prospecto_cedula']?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$id_usuario?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="col-md-4">
                                            <div class="form-group">     
                                                <div class="input-group" style="top: 25px;">
                                                    <label>No Existe?</label>&nbsp;
                                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-info" style="float: left" onclick="addCliente()">Agregar Cliente</button>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;" onclick="iniciarSolicitud()">Crear solicitud</button>
                                        </div>
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
    
    var idProspecto = $("#prospecto_solicitud"),
         idUsuario = $("#id_usuario");

    function iniciarSolicitud(){

        if(idProspecto != null){

            var parameters = {
                "id_prospecto": idProspecto.val(),
                "id_usuario": idUsuario.val(),
                "action": "crear_solicitud"
            };

            $.ajax({

            data: parameters,
             url: 'ajax/solicitudAjax.php',
            type: 'post',
            success: function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if(respuesta.response == "success"){

                        setTimeout(function(){location.href="?page=solicitudes"} , 0);

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

            });

        }else{

            Swal.fire({

                position: 'top-end',
                type: 'error',
                title: 'Debes seleccionar un Prospecto',
                showConfirmButton: false,
                timer: 2500

            });

        }

        

    }


</script>
<?
}else{

    include '401error.php';

}

?>