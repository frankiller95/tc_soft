<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");

session_start();

include('includes/connection.php');

include('includes/functions.php');



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
    <!-- Select 2 -->
    <link href="./assets/node_modules/select2_4.1.0/css/select2.min.css" rel="stylesheet" />
    <link href="./assets/node_modules/signature-pad/assets/jquery.signaturepad.css" rel="stylesheet">

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
        <br>
      <div class="m-0 row justify-content-center ml-auto">
        <div class="col-auto p-6 text-center">   
            <img src="./assets/images/LogoNegrotucelular.png" alt="" width="300" height="200">
            <img src="./assets/images/logo_crediavales.jpg" alt="" width="300" height="200">
            <br><br>
            <br>     
            <h2>FIRMAR TRATAMIENTO DE DATOS.</h2>
            <p class="lead">Diligiencia el siguiente formulario con tu información para continuar con tu solicitud de credito.</p>       
        </div>
      </div>

    <div class="row" id="form-zone">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Información del cliente</h4>
                    <div class="row pt-3">
                        <div class="col-md-6">
                            <h6>Los campos marcados con<span class="text-danger">&nbsp;*&nbsp;</span>Son requeridos.</h6>
                        </div>
                    </div>
                    <form action="" method="post" class="smart-form"
                    id="tratamiento_datos_form" data-ajax="false">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cedula:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="cedula_cliente" id="cedula_cliente" class="form-control" placeholder="Ingrese su cedula" onkeypress="return validaNumerics(event)" maxlength="16">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha de expedición:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                    <input type="date" class="form-control" name="fecha_exp" id="fecha_exp" placeholder="Fecha de expedición" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nombre y apellidos:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="nombre_apellidos" id="nombre_apellidos" class="form-control" placeholder="nombre y apellidos completos" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>dirección y ciudad:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="direccion_ciudad" id="direccion_ciudad" class="form-control" placeholder="Exp: Ciudad country, jamundi" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Teléfono de Contacto:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="telefono_contacto" id="telefono_contacto" class="form-control" placeholder="numero de contacto" autocomplete="ÑÖcompletes" required onkeypress="return validaNumerics(event)" maxlength="16">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>dirección del trabajo y ciudad:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="trabajo_ciudad" id="trabajo_ciudad" class="form-control" placeholder="Exp: zona Franca, Yumbo" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Teléfono del trabajo:</label>
                                    <div class="input-group">
                                        <input type="text" name="telefono_trabajo" id="telefono_trabajo" class="form-control" placeholder="numero del trabajo (opcional)" autocomplete="ÑÖcompletes" required onkeypress="return validaNumerics(event)" maxlength="16">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cargo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="cargo_cliente" id="cargo_cliente" class="form-control" placeholder="Cargo en la empresa" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salario:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="salario_cliente" id="salario_cliente" class="form-control" placeholder="Ingrese su salario" autocomplete="ÑÖcompletes" required onkeypress="return filterFloat(event,this,id);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Antiguedad:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="antiguedad_trabajo" id="antiguedad_trabajo" class="form-control" placeholder="Ingrese antiguedad laboral" autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="2">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia 1:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia1" id="referencia1" class="form-control" placeholder="referencia familiar o personal" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia 2:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia2" id="referencia2" class="form-control" placeholder="referencia familiar o personal" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia 3:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia3" id="referencia3" class="form-control" placeholder="referencia familiar o personal" autocomplete="ÑÖcompletes" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Que compra:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                         <select  class="form-control select2Class" name="compra_modelo" id="compra_modelo" style="width: 100%; height:36px;" autocomplete="ÑÖcompletes" required>
                                                <option value="placeholder" selected disabled>Seleccione el articulo a adquirir?</option>
                                                <?php
                                                $query = "SELECT modelos.id AS id_modelo, marcas.marca_producto, modelos.nombre_modelo FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.del = 0 order by nombre_modelo";
                                                $result = qry($query);
                                                while($row = mysqli_fetch_array($result)) {
                                                ?>
                                                    <option value="<?= $row['id_modelo']?>"><?= $row['marca_producto'].'-'.$row['nombre_modelo']?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cuota inicial:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="cuota_inicial" id="cuota_inicial" class="form-control" placeholder="Ingrese la cuota inical" autocomplete="ÑÖcompletes" required onkeypress="return filterFloat(event,this,id);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label># Cuotas:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="numero_cuotas" id="numero_cuotas" class="form-control" placeholder="Ingrese cuotas del credito" autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="2">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor de cada cuota:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="valor_cuota" id="valor_cuota" class="form-control" placeholder="Ingrese valor en pesos de cada cuota" autocomplete="ÑÖcompletes" required onkeypress="return filterFloat(event,this,id);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor total con simulador:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="total_credito" id="total_credito" class="form-control" placeholder="Ingrese el total del credito" autocomplete="ÑÖcompletes" required onkeypress="return filterFloat(event,this,id);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sigPad" id="smoothed" style="width:404px;">
                                    <ul class="sigNav">
                                      <li class="drawIt"><a href="#draw-it" >Campo de firma</a></li>
                                      <li class="clearButton" style="font-size: 1.75em !important;"><a href="#clear">Limpiar</a></li>
                                    </ul>
                                    <div class="sig sigWrapper" style="height:auto;">
                                      <div class="typed"></div>
                                      <canvas class="pad" id="info-signature" width="400" height="250"></canvas>
                                      <input type="hidden" name="output" class="output">
                                    </div>
                                    <br>
                                    <p style="font-size: 1.75em; font-weight: bold;">Realice su firma.</p>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 align-items-center">
                                <button type="submit" class="btn waves-effect waves-light btn-lg btn-success" style="float: right; margin-right: 20px;">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="response-zone">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="complete">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">© <?=date("Y")?> TuCelular  Todos los derechos Reservados.</p>
        <p class="mb-1">visita <a href="https://tucelular.net.co">tucelular.net.co</a>&nbsp;para mas información.</p>
    </footer>

    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/node_modules/sweetalert2/sweet-alert.init.js"></script>
    <!-- select2 -->
    <script src="./assets/node_modules/select2_4.1.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <!--signature pad -->
    <script src="./assets/node_modules/signature-pad/assets/numeric.js"></script> 
    <script src="./assets/node_modules/signature-pad/assets/bezier.js"></script> 
    <script src="./assets/node_modules/signature-pad/jquery.signaturepad.js"></script>
    <script src="./assets/node_modules/signature-pad/assets/json2.min.js"></script>
    <script>
        'use strict';

        $(document).ready(function() {
            signatureSpace = $('#smoothed').signaturePad({
              drawOnly:true, 
              drawBezierCurves:true,
              variableStrokeWidth:true, 
              lineTop:200
            });
            $( ":text" ).prop('readonly', false);
        });

        $(document).on('keydown', '.select2', function(e) {
          if (e.originalEvent && e.which == 40) {
            e.preventDefault();
            $(this).siblings('select').select2('open');
          }
        });

        $(document).ready(function() {
            $('.select2Class').select2({selectOnClose: true});
        });

        function validaNumerics(event) {

        if(event.charCode >= 4 && event.charCode <= 57){
          return true;
         }
         return false;
                 
    }


        function filterFloat(evt,input, id){
        // Backspace = 8, Enter = 13, ‘0′ = 48, ‘9′ = 57, ‘.’ = 46, ‘-’ = 43
        var key = window.Event ? evt.which : evt.keyCode;    
        var chark = String.fromCharCode(key);
        var tempValue = input.value+chark;
        if(key >= 48 && key <= 57){
            if(filter(tempValue)=== false){
                return false;
            }else{       
                var fnf = document.getElementById(id);
                fnf.addEventListener('keyup', function(evt){
                    var n2 = this.value;
                    if(n2 != ''){
                        var n = parseInt(n2.replace(/\D/g,''),10);
                        fnf.value = n.toLocaleString();
                    }
                }, false);
            }
        }else{
              if(key == 8 || key == 13 || key == 0) {     
                    var fnf = document.getElementById(id);
                    fnf.addEventListener('keyup', function(evt){
                        var n2 = this.value;
                        if(n2 != ''){
                            var n = parseInt(n2.replace(/\D/g,''),10);
                            fnf.value = n.toLocaleString();
                        }
                    }, false);            
              }else if(key == 46){
                    if(filter(tempValue)=== false){
                        return false;
                    }else{       
                        var fnf = document.getElementById(id);
                        fnf.addEventListener('keyup', function(evt){
                            var n2 = this.value;
                            if(n2 != ''){
                                var n = parseInt(n2.replace(/\D/g,''),10);
                                fnf.value = n.toLocaleString();
                            }
                        }, false); 
                    }
              }else{
                  return false;
              }
        }
    }
    function filter(__val__){
        /*
        console.log(__val__);
        var preg = /^([0-9]+\.?[0-9]{0,2})$/; 
        if(console.log(preg.test(__val__)) === true){
            return true;
        }else{
           return false;
        }
        */
       return true;
    }
      
        $("#tratamiento_datos_form").on("submit", function(e){

            // evitamos que se envie por defecto
            e.preventDefault();

                               
        });


        function saveImages(){

            var mycanvas = document.getElementById('info-signature');

            var img = mycanvas.toDataURL("image/png");

            var formData = new FormData(document.getElementById("tratamiento_datos_form"));
            formData.append('data', img);
            
            $.ajax('ajax/uploadAjax.php',
            {
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                  console.log(data);
                  const respuesta = JSON.parse(data);
                  console.log(respuesta);
                    
                    $("#form-zone").hide();
                        $("#response-zone").show();
                        $("#complete").empty();

                    if(respuesta.response == "success"){

                        
                        $("#complete").html(`<h1 class="text-success">LOS DATOS FUERON ENVIADOS CON EXITO.</h1><br>
                            <p>INGRESA AL SIGUIENTE LINK PARA DESCARGAR EL ARCHIVO: <a href="http://3.137.219.107/tc_soft/generar_trata_datos_form.php?id=${respuesta.id_trata}" target="blank">TRATAMIENTO DE DATOS</a></p>
                        </br>`);

                    }else{


                        $("#complete").html(`<h1 class="text-danger">ERORR EN EL PROCESO</h1>`);

                    }

                },
                error: function(data){

                  console.log(data);

                   
                }
            });
        }

    </script>
</body>
</html>