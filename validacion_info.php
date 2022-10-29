<?

if (isset($_GET['id'])) {

    $id_prospecto = $_GET['id'];

    $validate_validador = execute_scalar("SELECT id_usuario_validador FROM prospectos WHERE id = $id_prospecto");

    if($id_usuario == $validate_validador){

        $prospecto_nombre = execute_scalar("SELECT CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.id = $id_prospecto");

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
                    <h4 class="text-themecolor"><?= ucwords(str_replace("_", " ", $page)) . '&nbsp;<span class="text-danger"> ' . $prospecto_nombre . '</span>' ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="?page=solicitudes">Solicitudes</a></li>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Información del prospecto.</h4>
                            <br>
                            <br>
                            <?

                            $query = "SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, usuarios.nombre, usuarios.apellidos, prospecto_detalles.prospecto_email, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento, prospecto_detalles.prospecto_sexo, prospecto_detalles.prospecto_dob, prospecto_detalles.fecha_exp, prospecto_detalles.id_ciudad_exp, prospectos.id_estado_prospecto FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE prospectos.id = $id_prospecto";

                            $result = qry($query);
                            while ($row = mysqli_fetch_array($result)) {

                                $prospecto_cedula = $row['prospecto_cedula'];
                                $prospecto_nombre = $row['prospecto_nombre'] . ' ' . $row['prospecto_apellidos'];
                                $prospecto_numero_contacto = $row['prospecto_numero_contacto'];
                                $asesor = $row['nombre'] . ' ' . $row['apellidos'];
                                $prospecto_email = $row['prospecto_email'];
                                $prospecto_direccion = $row['prospecto_direccion'] . ' ' . $row['ciudad'] . '-' . $row['departamento'];
                                $prospecto_sexo = $row['prospecto_sexo'];
                                if ($prospecto_sexo == "M") {
                                    $prospecto_sexo = "MASCULINO";
                                } else {
                                    $prospecto_sexo = "FEMENINO";
                                }
                                $prospecto_dob = $row['prospecto_dob'];
                                $fecha_exp = $row['fecha_exp'];
                                $id_ciudad_exp = $row['id_ciudad_exp'];
                                $ciudad_exp = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $id_ciudad_exp");
                                $id_departamento_exp = execute_scalar("SELECT id_departamento FROM ciudades WHERE id = $id_ciudad_exp");
                                $departamento_exp = execute_scalar("SELECT departamento FROM departamentos WHERE id = $id_departamento_exp");
                                $id_estado_prospecto = $row['id_estado_prospecto'];

                            ?>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">NOMBRE COMPLETO:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_nombre ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold"># DOCUMENTO:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_cedula ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold"># CONTACTO:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_numero_contacto ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">CORREO ELECTRONICO:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_email ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">DIRECCIÓN:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_direccion ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">SEXO:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_sexo ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">FECHA DE NACIMIENTO:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $prospecto_dob ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">CIUDAD DE EXPEDICIÓN:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $fecha_exp . ' ' . $ciudad_exp . ' ' . $departamento_exp ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">ASESOR RESPONSABLE:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <P class="text-left card-content"><?= $asesor ?></P>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col p-6">
                                        <h4 class="card-subtitle text-center font-weight-bold">RESULTADO VALIDACIÓN:</h6>
                                    </div>
                                    <div class="col order-12 p-6">
                                        <div class="input-group">
                                            <fieldset class="controls">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" value="1" name="prospecto_validacion" id="prospecto_aprobado" class="custom-control-input" required <?if($id_estado_prospecto == 1){?> checked <?}?>>
                                                    <label class="custom-control-label" for="prospecto_aprobado">APROBADO</label>
                                                </div>
                                            </fieldset>
                                            &nbsp; &nbsp;
                                            <fieldset>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" value="2" name="prospecto_validacion" id="prospecto_rechazado" class="custom-control-input" <?if($id_estado_prospecto == 2){?> checked <?}?> >
                                                    <label class="custom-control-label" for="prospecto_rechazado">RECHAZADO</label>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            <? } ?>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-left: 5px;" id="submitBtnProspecto" onclick="guardarCambios(<?=$id_prospecto?>, <?=$id_usuario?>)">Guardar Cambios</button>
                                    <a href="?page=dashboard" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right;">Salir</a>
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
        
        function guardarCambios(idProspecto, idUsuario){

            var aprobado = document.getElementById("prospecto_aprobado");
            var rechazado = document.getElementById("prospecto_rechazado");

            var validacionResultado = '';

            if(aprobado.checked == true){

                validacionResultado = 1;

            }else if(rechazado.checked == true){

                validacionResultado = 2;

            }else{

                Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Debe seleccionar un resultado',
                    showConfirmButton: false,
                    timer: 3000

                });

            }

            if(validacionResultado != ''){

                var parameters = {
                    "id_prospecto": idProspecto,
                    "id_usuario": idUsuario,
                    "validacion_resultado": validacionResultado,
                    "action": "update_validacion"
                };

                    $.ajax({
                        data: parameters,
                        url: 'ajax/prospectosAjax.php',
                        type: 'post',
                        success: function(response) {

                            console.log(response);
                            const respuesta = JSON.parse(response);
                            console.log(respuesta);

                            if(respuesta.response == "success"){

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'success',
                                    title: 'FELICIDADES!',
                                    text: 'Resultado asignado correctamente.',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timer: 3000

                                });


                                setTimeout(function(){location.href="?page=dashboard"} , 4000);

                            }else{

                                Swal.fire({

                                    position: 'top-end',
                                    type: 'error',
                                    title: 'Error en el proceso',
                                    showConfirmButton: false,
                                    timer: 3000

                                });

                            }

                        }
                    });

            }else{

                Swal.fire({

                    position: 'top-end',
                    type: 'error',
                    title: 'Debe seleccionar un resultado',
                    showConfirmButton: false,
                    timer: 3000

                });

                return 0;
            }

        }
    </script>


<?
    }else{
        include '401error.php';    
    }
} else {

    include '401error.php';
}

?>