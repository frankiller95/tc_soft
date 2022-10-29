<?

if (profile(28, $id_usuario) == 1) {

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
      
            <div class="row" id="arandelas-zone">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Arandelas De Crédito.</h4>
                            <br>
                            <form action="" method="post" class="smart-form" id="arandelasCreditoForm">
                            <?
                            $query = "SELECT estudio_credito, fianza, interaccion_tecnologica, beriblock, seguro_pantalla, domicilio, iva_arandelas, tasa_interes_usura FROM arandelas_creditos WHERE id = 1";
                            $result = qry($query);
                            while($row = mysqli_fetch_array($result)){

                                $estudio_credito = $row['estudio_credito'];
                                $fianza = $row['fianza'];
                                $interaccion_tecnologica = $row['interaccion_tecnologica'];
                                $beriblock = $row['beriblock'];
                                $seguro_pantalla = $row['seguro_pantalla'];
                                $domicilio = $row['domicilio'];
                                $iva_arandelas = $row['iva_arandelas'];
                                $tasa_interes_usura = $row['tasa_interes_usura'];

                            }
                            ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Estudio de Crédito:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="valor_estudio_c" id="valor_estudio_c" placeholder="" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" value="<?= number_format($estudio_credito, 0, '.', '.') ?>" onchange="actualizarArandelas(this.value, 'estudio_credito')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fianza:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="percentage" id="fianza_c" placeholder="" autocomplete="ÑÖcompletes" maxlength="4" value="<?= $fianza?>%" onchange="actualizarArandelas(this.value, 'fianza')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Interacción tecnologica:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="interaccion_tecnologica_c" id="interaccion_tecnologica_c" placeholder="" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" value="<?= number_format($interaccion_tecnologica, 0, '.', '.')?>" onchange="actualizarArandelas(this.value, 'interaccion_tecnologica')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Beriblock:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="beriblock_c" id="beriblock_c" placeholder="" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" value="<?= number_format($beriblock, 0, '.', '.') ?>" onchange="actualizarArandelas(this.value, 'beriblock')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Domicilio:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="domicilio_c" id="domicilio_c" placeholder="" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" value="<?= number_format($domicilio, 0, '.', '.') ?>" onchange="actualizarArandelas(this.value, 'domicilio')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Seguro de pantalla:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="percentage" id="seguro_pantalla_c" placeholder="" autocomplete="ÑÖcompletes" maxlength="4" value="<?= $seguro_pantalla?>%" onchange="actualizarArandelas(this.value, 'seguro_pantalla')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Iva Arandelas:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="percentage" id="iva_arandelas_c" placeholder="" autocomplete="ÑÖcompletes" maxlength="4" value="<?= $iva_arandelas?>%" onchange="actualizarArandelas(this.value, 'iva_arandelas')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tasa Interes Usura:</label>
                                            <div class="input-group">
                                                <!--<input type="text" class="form-control" name="tasa_interes_usura_c" id="tasa_interes_usura_c" placeholder="" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" value="<?= $tasa_interes_usura ?>" onchange="actualizarArandelas(this.value, 'tasa_interes_usura')">-->
                                                <input class="percent form-control" type="text" name="percentage" id="tasa_interes_usura_c" value="<?=$tasa_interes_usura?>%" maxlength="6" onchange="actualizarArandelas(this.value, 'tasa_interes_usura')">
                                            </div>
                                        </div>
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

        $(document).ready(function(){
            $("input[name='percentage']").on('input', function() {
                $(this).val(function(i, v) {
                return v.replace('%','') + '%';  });
            });
        });

        function actualizarArandelas(date, type) { 

            var action = "actualizar_arandela";

            var parameters = {
                "date": date,
                "type": type,
                "action": action
            };
                
            $.ajax({
                data: parameters,
                url: 'ajax/bibliotecasAjax.php',
                type: 'post',
                success: function (response) {
                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);
                    if(respuesta.response == "success"){

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Actualizado correctamente.',
                            showConfirmButton: false,
                            timer: 2000

                        });

                    }else{

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 2000

                        });

                    }

                }
            });
        }
        
    </script>

<?
} else {

    include '401error.php';
}

?>