<?

if (profile(11, $id_usuario) == 1) {
    
    $nombre_digitador = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS usuario_full FROM usuarios WHERE id = $id_usuario");

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
                    <h4 class="text-themecolor"><?= str_replace('_', ' & ', ucwords($page)) ?></h4>
                </div>
                <div class="col-md-7 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                            <li class="breadcrumb-item active"><?= str_replace('_', ' & ', ucwords($page)) ?></li>
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
                            <h4 class="card-title" style="font-weight: bold">Hola <span class="text-info"><?=$nombre_digitador?></span> Estas a un paso de poder Ganar!!!</h4>
                            <div class="row pt-3">
                                <div class="col-md-6">
                                    <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                                </div>
                            </div>
                            <br>
                            <form action="" method="post" class="smart-form" id="registrarNuevoProspectoForm">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nombre:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="nombre_prospecto" id="nombre_prospecto" placeholder="<?=ucwords('Nombres del referido')?>" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>apellidos:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="apellidos_prospecto" id="apellidos_prospecto" placeholder="<?=ucwords('Ahora los apellidos')?>" required autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>contacto:<span class="text-danger">&nbsp;*</span></label>
                                            <div class="input-group">
                                                <input class="form-control phoneNumber" name="contacto_prospecto" id="contacto_prospecto" placeholder="<?=ucwords('ahora el celular')?>" value="" autocomplete="ÑÖcompletes" maxlength="16" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="action" id="action_registrar" value="insertar_prospecto">
                                        <input type="hidden" name="id_usuario" value="<?=$id_usuario?>">
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="float: right; margin-left: 5px;" onclick="cancelar()">Cancelar</button>
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right;">ENVIAR</button>
                                    </div>
                                </div>
                            </form>
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
        
        $("#registrarNuevoProspectoForm").on("submit", function(e) {

            // evitamos que se envie por defecto
            e.preventDefault();

            $("#action_registrar").val('insertar_prospecto');

            // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("registrarNuevoProspectoForm"));

            insertReferidoDB(formData);

        });


        function insertReferidoDB(dates){
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/ganeAjax.php', true);
            // pass Info
            xhr.onload = function() {
                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        Swal.fire({

                            position: 'center',
                            type: 'success',
                            title: 'Referido registrado correctamente!!',
                            text: 'Continua registrando para mayores oportunidas de ganar comisiones',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            closeOnClickOutside: false,
                            timer: 5000

                        });

                        $("#nombre_prospecto").val('');
                        $("#apellidos_prospecto").val('');
                        $("#contacto_prospecto").val('');

                    } else {

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }



                }
            }

            // send dates
            xhr.send(dates)

        }


    </script>
 

<?
} else {

    include '401error.php';
}

?>