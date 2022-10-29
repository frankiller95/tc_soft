<?

session_name("tc_shop");

session_start();

include('includes/connection.php');

include('includes/functions.php');

if(isset($_GET['id'])){

  $id_solicitud = $_GET['id'];

  $celular_prospecto = execute_scalar("SELECT prospecto_detalles.prospecto_numero_contacto FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE solicitudes.id = $id_solicitud");

  $enviado_codigo = execute_scalar("SELECT enviado_codigo FROM firmas_solicitudes WHERE id_solicitud = $id_solicitud");

  $terminado = execute_scalar("SELECT terminado FROM firmas_solicitudes WHERE id_solicitud = $id_solicitud"); 

  if($enviado_codigo == 0 && $terminado == 0){
    $texto_proceso = "Por favor pulsa el siguiente BOTON si el telefono que indica corresponde a tu numero de contacto.";
  }else if($enviado_codigo == 1 && $terminado == 0){
     $texto_proceso = "Digita el mensaje recibido por medio de SMS en el cuadro de ABAJO.";
  }else if($enviado_codigo == 1 && $terminado == 1){
    $texto_proceso = "ESTE DOCUMENTO YA FUE FIRMADO CON EXITO.";
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TC-SHOP firma de contractos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!--alerts CSS -->
    <link href="./assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <style>
    .embed-responsive-3by4 {
        padding-bottom: 133%;
    }
    .embed-responsive {
        position: relative;
        display: block;
        height: 0;
        padding: 0;
        overflow: hidden;
    }
    .img-rounded {
        border-radius: 6px;
    }
    </style>
</head>
<body class="bg-light">

    <div class="container">
      <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="./assets/images/LogoNegrotucelular.png" alt="" width="200" height="200">
        <h2>FIRMA DE DOCUMENTOS</h2>
        <p class="lead">A continuación se muestra el documento que ha recibido. Reviselo y desplácese al final de la página para su firma.</p>
      </div>
      <div class="row">
          <div class="col-md-12">
                <iframe src="./documents/pdf/Contrato TcShop.pdf" frameborder="0" width="100%" height="570px"></iframe>
              <br>
              <button class="btn waves-effect waves-light btn-lg btn-info" style="float: right;" type="submit">Abrir PDF En una nueva pagína</button>    
          </div>         
      </div>
      <br>
      <br>
      <div class="row">
          <div class="col-md-12">
                <iframe src="./documents/pdf/Minuta Garantias.pdf" frameborder="0" width="100%" height="570px"></iframe>
              <br>
              <button class="btn waves-effect waves-light btn-lg btn-info" style="float: right;" type="submit">Abrir PDF En una nueva pagína</button>    
          </div>         
      </div>
      <br>
      <br>
      <div class="row">
          <div class="col-md-12">
            <h1>FIRMA DE DOCUMENTO MEDIANTE MENSAJERIA OTP<?=$enviado_codigo?></h1>
            <p class="lead"><span class="text-danger" id="texto-proceso"><?=$texto_proceso?></span></p>
            <div id="zone-numero" style="display: <?if($enviado_codigo == 0 && $terminado == 0){?>block<?}else{?>none<?}?>">
              <button class="btn btn-success btn-lg btn-block" onclick="enviarCodigo(<?=$id_solicitud?>)">ENVIAR CODIGO AL NUMERO +57 <?=$celular_prospecto?></button>
            </div>
            <div id="zone-mensaje" style="display: <?if($enviado_codigo == 1 && $terminado == 0){?>block<?}else{?>none<?}?>">
                <input type="text" name="codigo_firma" id="codigo_firma" class="form-control">
                <br>
                <button class="btn btn-success btn-lg btn-block" onclick="firmarDocumentos(<?=$id_solicitud?>)">FIRMAR DOCUMENTOS</button>
            </div>
            <div id="zone-exito" style="display: <?if($enviado_codigo==1&&$terminado == 1){?>block<?}else{?>none<?}?>">
                <p class="lead label label-success" id="mensaje-exito">ESTE DOCUMENTO YA FUE FIRMADO CON EXITO</p>
            </div>
          </div> 
      </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">© 2021 TuCelular  Todos los derechos Reservados.</p>
        <p class="mb-1">visita <a href="https://tucelular.net.co">tucelular.net.co</a>&nbsp;para mas información.</p>
    </footer>

    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <script>
        'use strict';
        function enviarCodigo(idSolicitud){
            var parameters = {
                "id_solicitud": idSolicitud,
                "action": "enviar_codigo"
            };

            $.ajax({

                data: parameters,
                url: 'ajax/solicitudesAjax.php',
                type: 'post',
                success: function (response) {

                    console.log(response);
                    const respuesta = JSON.parse(response);
                    console.log(respuesta);

                    if(respuesta.response == "success"){

                        $("#zone-numero").hide();
                        $("#zone-mensaje").show();
                        $("#zone-exito").hide();
                        $("#codigo_firma").val('');

                        $("#texto-proceso").empty();
                        $("#texto-proceso").html('Coloca el codigo recibido por SMS en el cuadro de abajo.');

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Codigo enviado correctamente',
                            showConfirmButton: false,
                            timer: 3000

                        });

                    }else{

                        $("#zone-numero").show();
                        $("#zone-mensaje").hide();
                        $("#zone-exito").hide();
                        $("#codigo_firma").val('');

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
        }
        
        function firmarDocumentos(idSolicitud){

            var codigo = $("#codigo_firma").val();

            if(codigo != ''){

                var parameters = {

                    "id_solicitud": idSolicitud,
                    "codigo1": codigo,
                    "action": "firmar_documento"
                };

                $.ajax({

                    data: parameters,
                    url: 'ajax/solicitudesAjax.php',
                    type: 'post',
                    success: function (response) {

                        console.log(response);
                        const respuesta = JSON.parse(response);
                        console.log(respuesta);

                        if(respuesta.response == "success"){

                            $("#zone-numero").hide();
                            $("#zone-mensaje").hide();
                            $("#zone-exito").show();
                            $("#codigo_firma").val('');
                            $("#mensaje-exito").html('EL DOCUMENTO FUE FIRMADO CON EXITO');

                        }else if(respuesta.response == "codigo_incorrecto"){

                            $("#zone-numero").hide();
                            $("#zone-mensaje").show();
                            $("#zone-exito").hide();
                            $("#codigo_firma").val('');

                            Swal.fire({

                                position: 'top-end',
                                type: 'error',
                                title: 'Codigo incorrecto',
                                showConfirmButton: false,
                                timer: 3000

                            });

                        }else{

                            $("#zone-numero").hide();
                            $("#zone-mensaje").show();
                            $("#zone-exito").hide();
                            $("#codigo_firma").val('');

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
                    title: 'El codigo no puede estar vacio',
                    showConfirmButton: false,
                    timer: 3000

                });

            }
        }        

    </script>
<? }else{ ?>
    <h1>ERROR EN EL PROCESO.</h1>
<? } ?>
</body>
</html>