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

    /* 
  ##Device = Most of the Smartphones Mobiles (Portrait)
  ##Screen = B/w 320px to 479px
    */
   
   .img-size-logos{
    width: 300px;
    height: 200px;
   }

   .size-smoothed{
    width: 404px;
   }

   .size-canvas{
    width: 400px;
    height: 250px;
   }

    @media (min-width: 320px) and (max-width: 480px) {
      
      .img-size-logos{
        width: 150px;
        height: 150px;
       }

       .size-smoothed{
        width: 298px;
       }

       .size-canvas{
        width: 295px;
        height: 250px;
       }
      
    }

    </style>
</head>
<body class="bg-light">
    <br>
    <br>
    <br>
    <div class="container">
      <div class="m-0 row justify-content-center ml-auto">
        <div class="col-auto p-6 text-center">   
            <img class="img-size-logos" src="./assets/images/LogoNegrotucelular.png" alt="">
            <img class="img-size-logos" src="./assets/images/logo_crediavales_v2.jpg" alt="">
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
                                        <input type="text" name="cedula_cliente" id="cedula_cliente" class="form-control" placeholder="Ingrese su cedula" onkeypress="return validaNumerics(event)" maxlength="16" required>
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
                                        <input type="text" name="nombre_apellidos" id="nombre_apellidos" class="form-control" placeholder="nombre y apellidos completos" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
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
                                        <input type="text" name="telefono_contacto" id="telefono_contacto" class="form-control" placeholder="numero de contacto" autocomplete="ÑÖcompletes" required onkeypress="return validaNumerics(event)" maxlength="10">
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
                                        <input type="text" name="telefono_trabajo" id="telefono_trabajo" class="form-control" placeholder="numero del trabajo (opcional)" autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cargo:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="cargo_cliente" id="cargo_cliente" class="form-control" placeholder="Cargo en la empresa" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Salario:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="salario_cliente" id="salario_cliente" class="form-control" placeholder="Ingrese su salario" autocomplete="ÑÖcompletes" onkeypress="return filterFloat(event,this,id);" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Antiguedad:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="antiguedad_trabajo" id="antiguedad_trabajo" class="form-control" placeholder="Exp: 6 MESES" autocomplete="ÑÖcompletes" required onkeyup="javascript:this.value=this.value.toUpperCase();" style="text-transform:uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia Familiar:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia1" id="referencia1" class="form-control" placeholder="Referencia Familiar" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefono 1:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="telefono1" id="telefono1" class="form-control" placeholder="Teléfono Referencia Familiar" autocomplete="ÑÖcompletes"  onkeypress="return validaNumerics(event)" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia Familiar 2:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia2" id="referencia2" class="form-control" placeholder="Referencia Familiar" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefono 2:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="telefono2" id="telefono2" class="form-control" placeholder="Teléfono Referencia Familiar 2" autocomplete="ÑÖcompletes"  onkeypress="return validaNumerics(event)" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia Personal:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia3" id="referencia3" class="form-control" placeholder="Referencia Personal" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefono 1:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="telefono3" id="telefono3" class="form-control" placeholder="Teléfono Referencia Personal 1" autocomplete="ÑÖcompletes"  onkeypress="return validaNumerics(event)" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Referencia Personal 2:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="referencia4" id="referencia4" class="form-control" placeholder="Referencia Personal" autocomplete="ÑÖcompletes" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return validLetters(event)" style="text-transform:uppercase;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefono 2:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="telefono4" id="telefono4" class="form-control" placeholder="Teléfono Referencia Personal 2" autocomplete="ÑÖcompletes"  onkeypress="return validaNumerics(event)" maxlength="10" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label># Cuotas:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="numero_cuotas" id="numero_cuotas" class="form-control" placeholder="Ingrese cuotas del credito" autocomplete="ÑÖcompletes" onkeypress="return validaNumerics(event)" maxlength="2" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor de cada cuota:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="valor_cuota" id="valor_cuota" class="form-control" placeholder="Ingrese valor en pesos de cada cuota" autocomplete="ÑÖcompletes" required onkeypress="return filterFloat(event,this,id);" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor total con simulador:<span class="text-danger">&nbsp;*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="total_credito" id="total_credito" class="form-control" placeholder="Ingrese el total del credito" autocomplete="ÑÖcompletes" required onkeypress="return filterFloat(event,this,id);" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row justify-content ml-auto">
                            <div class="col-md-12">
                                <p class="lead" style="font-weight: 700; font-size: 14px; text-align: justify;">Autorización, consulta y Reporte de información
El(los) abajo firmante(s) actuando en nombre propio y/o en representación de la persona juridica, autorizo (amos) a
CREDIAVALES SAS o a quien represente sus derechos. de manera irrevocable, escrita, expresa, concreta,
suficiente, voluntaria e informada para que toda la información personal comercial, financiera actual y la que genere
en el futuro. fruto de las relaciones comerciales y/o contractuales establecidas con CREDIAVALES o con sus
afiliados, referentes a mi (nuestro) comportamiento(s) financiero crediticio, origen de fondos, comercial y de servicios
que exista o pueda existir en bases de datos, centrales de riesgo de información nacionales o extranjeras,
especialmente aquella referida al nacimiento, ejecución y extinción del obligaciones que directa o indirectamente
tengan carácter o nerarias, independientemente de la naturaleza del contracto que los de origen, sea administrada,
capturada, procesada, operada, verificada, transmitida, usada o puesta en circulación y consultada igualmente
autorizamos a CREDIAVALES a entregar esa información de forma verbal o escrita, poner a disposición de terceras
personas, autoridades administrativas y judiciales que lo requieran organos de control y demás dependencias de
investigación disciplinaria, siempre que medie orden de autoridad competente ( ley de habeas data 1266/2008).</p>
<p class="lead" style="font-weight: 700; font-size: 14px; text-align: justify;">Bajo la gravedad de juramento cerifico (amos) que los datos personales por mi (nosotros) suministrados son veraces,
completos y exactos, actualizados y comprobables. por tanto, cualquier error en la información suministrada por mi
sera de mi (nuestra) unica y exclusiva responsabilidad y que saldre a responder ante la por o frente a cualquier
inconsistencia aqui consignada.</p>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="sigPad size-smoothed" id="smoothed">
                                    <ul class="sigNav">
                                      <li class="drawIt"><a href="#draw-it" >Campo de firma</a></li>
                                      <li class="clearButton" style="font-size: 1.75em !important;"><a href="#clear">Limpiar</a></li>
                                    </ul>
                                    <div class="sig sigWrapper" style="height:auto;">
                                      <div class="typed"></div>
                                      <canvas class="pad size-canvas" id="info-signature" height="250"></canvas>
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

    <div class="row" id="response-zone" style="display: none;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="complete" style="display: none;">
                        
                    </div>
                    <div class="row" id="envio" style="display: none;">
                        <p>¿Deseas recibir el documento en tu correo o SMS?</p>
                        <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label>Seleccionar Envio:</label>
                                            <div class="form-check">
                                              <input class="form-check-input" type="radio" name="send_type" id="email_send" value="email">
                                              <label class="form-check-label" for="email_send" onclick="selectMedio('email')">
                                                Enviar por Correo
                                              </label>
                                            </div>
                                            <div class="form-check">
                                              <input class="form-check-input" type="radio" name="send_type" id="sms_send" value="sms">
                                              <label class="form-check-label" for="sms_send" onclick="selectMedio('sms')">
                                                Enviar por SMS
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="row" id="metodo" style="display: none;">
                        <div class="col-md-12">
                            <form action="" method="post" class="smart-form" id="envio_pdf_form">
                                <div class="row">
                                    <div class="col-md-6" id="zone-input">
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <input type="hidden" id="id_trata" name="id_trata">
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-success">Enviar</button>
                                        <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="margin-right: 20px;" onclick="location.reload;">Salir</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
            const signatureSpace = $('#smoothed').signaturePad({
              drawOnly:true, 
              drawBezierCurves:true,
              variableStrokeWidth:true, 
              lineTop:200
            });
            $( ":text" ).prop('readonly', false);

            const mobileStyles = matchMedia("(min-width: 320px) and (max-width: 480px)");
            
            const changeSize = mql => {

                if (mql.matches) {

                    $("#info-signature").attr("width", "298");

                }else{

                    $("#info-signature").attr("width", "400");

                }

            }

            mobileStyles.addListener(changeSize);  
            changeSize(mobileStyles);

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

        function validLetters(event){
            //console.log(event.charCode);
            
            if (event.charCode >= 97 && event.charCode <= 122) {
                return true;
            }else{
                if (event.charCode == 32) {
                    return true;
                }else{
                    return false;
                }
            }
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
                        $("#complete").show();
                        $("#complete").empty();

                        $("#envio").show();

                    if(respuesta.response == "success"){

                        
                        $("#complete").html(`<h1 class="text-success">LOS DATOS FUERON ENVIADOS CON EXITO.</h1><br>
                            <p>INGRESA AL SIGUIENTE LINK PARA DESCARGAR EL ARCHIVO: <a href="http://3.137.219.107/tc_soft/generar_trata_datos_form.php?id=${respuesta.id_trata}" target="blank">DESCARGAR PDF</a></p>
                        </br>`);

                        $("#id_trata").val(respuesta.id_trata);

                    }else if(respuesta.response == "firma"){

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Por favor realiza la firma del documento',
                            showConfirmButton: false,
                            timer: 3500

                        });

                    }else if(respuesta.response == "falta"){

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Verifica la información del formulario',
                            showConfirmButton: false,
                            timer: 3500

                        });

                    }else{

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'ERROR EN EL PROCESO',
                            showConfirmButton: false,
                            timer: 3500

                        });

                        //$("#complete").html(`<h1 class="text-danger">ERORR EN EL PROCESO</h1>`);

                    }

                },
                error: function(data){

                  console.log(data);

                   
                }
            });
        }


        function selectMedio(from) {

            $("#zone-input").empty();

            if(from == "sms"){
                $("#zone-input").html(`<div class="form-group">
                                        <label>Digita tu numero celular:</label>
                                            <div class="input-group">
                                                <input class="form-control phoneNumber" name="celular_cliente" id="celular_cliente" placeholder="(123)-456-7890" autocomplete="ÑÖcompletes" maxlength="16" required>
                                            </div>
                                            </div>
                                        </div>`);

                
            }else{

                $("#zone-input").html(`<div class="form-group">
                                        <label>Escribe tu Correo electronico:</label>
                                            <div class="input-group">
                                                <input type="email" class="form-control" name="email_cliente" id="email_cliente" placeholder="Correo electronico" autocomplete="ÑÖcompletes" required>
                                            </div>
                                            </div>
                                        </div>`);

            }

            $("#metodo").show();

        }

        $("#envio_pdf_form").on("submit", function(e){

            // evitamos que se envie por defecto
            e.preventDefault();

             // create FormData with dates of formulary       
            var formData = new FormData(document.getElementById("envio_pdf_form"));

            const action = "enviar_pdf_cli";

            formData.append('action', action);

            enviarDatosCliente(formData);

        });



        function enviarDatosCliente(dates){
            console.log(...dates);
            /** Call to Ajax **/
            // create the object
            const xhr = new XMLHttpRequest();
            // open conection
            xhr.open('POST', 'ajax/uploadAjax.php', true);
            // pass Info
            xhr.onload = function(){
                //the conection is success
                if (this.status === 200) {

                    console.log(xhr.responseText);
                    const respuesta = JSON.parse(xhr.responseText);
                    console.log(respuesta);

                    if(respuesta.response == "success"){

                        Swal.fire({

                            position: 'top-end',
                            type: 'success',
                            title: 'Documento enviado con exito',
                            showConfirmButton: false,
                            timer: 3500

                        });

                        setTimeout(function(){location.reload} , 4000);

                    }else{

                        Swal.fire({

                            position: 'top-end',
                            type: 'error',
                            title: 'Error en el proceso',
                            showConfirmButton: false,
                            timer: 3500

                        });


                    }


                }
            }

            // send dates
            xhr.send(dates) 

        }


    </script>
</body>
</html>