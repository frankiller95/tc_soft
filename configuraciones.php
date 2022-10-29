<?

if (profile(31, $id_usuario) == 1) {

?>
    <style>
        .custom-control-input {
            transform: scale(1.4);
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

            <div class="row" id="arandelas-zone">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Configuraciones.</h4>
                            <br>
                            <form action="" method="post" class="smart-form" id="arandelasCreditoForm">
                                <?
                                $query = "SELECT configuraciones.envios_sms_gane FROM configuraciones WHERE id = 1";
                                $result = qry($query);
                                while ($row = mysqli_fetch_array($result)) {

                                    $envio_sms_gane = $row['envios_sms_gane'];
                                }
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>SMS codigo GANE:</label>
                                            <div class="input-group">
                                                <!--
                                                <fieldset class="controls">
                                                    <div class="custom-control">
                                                        <input type="checkbox" name="sms_gane_altiria" id="sms_gane_altiria" class="js-switch" <? if ($envio_sms_gane == 0) { ?>checked<? } ?> value="0" data-color="#0fddd8" data-size="large" required onchange="cambiarSms(this.checked, this.value)"/>
                                                        <label for="sms_gane_altiria">ALTIRIA</label>
                                                    </div>
                                                </fieldset>
                                                &nbsp; &nbsp;
                                                <fieldset>
                                                    <div class="custom-control">
                                                        <input type="checkbox" name="sms_gane_aldeamo" id="sms_gane_aldeamo" class="js-switch" <? if ($envio_sms_gane == 1) { ?>checked<? } ?> value="1" data-color="#0fddd8" data-size="large" required onchange="cambiarSms(this.checked, this.value)"/>
                                                        <label for="sms_gane_aldeamo">ALDEAMO</label>
                                                    </div>
                                                </fieldset>
                                                -->
                                                <fieldset class="controls">
                                                    <div class="custom-control custom-switch custom-switch-xl">
                                                        <input type="checkbox" class="custom-control-input" value="0" id="customSwitch1" <? if ($envio_sms_gane == 0) { ?>checked<? } ?> onchange="cambiarSms(this.checked, this.value)">
                                                        <label class="custom-control-label" for="customSwitch1">ALTIRIA</label>
                                                    </div>
                                                </fieldset>
                                                &nbsp; &nbsp;
                                                <fieldset>
                                                    <div class="custom-control custom-switch custom-switch-xl">
                                                        <input type="checkbox" class="custom-control-input" value="1" id="customSwitch2" <? if ($envio_sms_gane == 1) { ?>checked<? } ?> onchange="cambiarSms(this.checked, this.value)">
                                                        <label class="custom-control-label" for="customSwitch2">ALDEAMO</label>
                                                    </div>
                                                </fieldset>
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
        'use strict';

        /*
        $(function() {
           $("#customSwitch1").prop('checked', true);
           $("#customSwitch2").prop('checked', true);
        });
        */

        function cambiarSms(checked, value) {

            var valueReal = 0;

            if (checked === true && value == 0) {
                $("#customSwitch2").prop('checked', false);
                valueReal = 0;
            } else if (checked === true && value == 1) {
                $("#customSwitch1").prop('checked', false);
                valueReal = 1;
            } else if (checked === false && value == 0) {
                $("#customSwitch2").prop('checked', true);
                valueReal = 1;
            } else if (checked === false && value == 1) {
                $("#customSwitch1").prop('checked', true);
                valueReal = 0;
            }

            var parameters = {
                "valor": valueReal,
                "action": "change_codigo"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/bibliotecasAjax.php',
                type: 'post',
                success: function(response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if (respuesta.response == "success") {

                        $.toast({
                            heading: 'Genial!',
                            text: 'Actualizado Correctamente.',
                            position: 'top-center',
                            loaderBg: '#00c292',
                            icon: 'success',
                            hideAfter: 3000,
                            stack: 6
                        });

                    } else {

                        $.toast({

                            heading: 'Error!',
                            text: 'Error en el proceso.',
                            position: 'top-center',
                            loaderBg: '#e46a76',
                            icon: 'error',
                            hideAfter: 3000,
                            stack: 6

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