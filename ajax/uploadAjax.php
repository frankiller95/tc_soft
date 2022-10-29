<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

date_default_timezone_set('America/Bogota');
//var_dump($_POST);

if(isset($_POST['action'])){

    $medio = "";

    $validacion = 0;

    $id_trata = $_POST['id_trata'];

    if (isset($_POST['celular_cliente'])) {
        $celular_cliente = $_POST['celular_cliente'];
        $medio = "sms";

        include('../includes/httpPHPAltiria.php');

        $altiriaSMS = new AltiriaSMS();
        //$altiriaSMS->setUrl("http://www.altiria.net/api/http");
        $altiriaSMS->setLogin('soporte@tucelular.net.co');
        $altiriaSMS->setPassword('SOPORTE4302');

        //$sDestination = '346xxxxxxxx';
        $sDestination = '57'.$celular_cliente;
        //$sDestination = array('346xxxxxxxx','346yyyyyyyy');

        $respuesta = $altiriaSMS->sendSMS($sDestination, 'Atraves del siguiente link puedes visualizar el pdf firmado para tu credito con CREDIAVALES');

        $respuesta2 = $altiriaSMS->sendSMS($sDestination, 'https://www.noa10.com/crm/generar_trata_datos_form.php?id='.$id_trata);

        $validate = 1;

        if ($respuesta == $validate && $respuesta2 == $validate) {
            
            $validacion = 1;            

        }else{

            $response = array("response" => "error_sms");
            echo json_encode($response);
            die();
        }


    }else{


        $medio = "email";
        $email_cliente = $_POST['email_cliente'];

        $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'noreply.tcshop@gmail.com';                     //SMTP username
                    $mail->Password   = 'Soporte0319+';                               //SMTP password
                    $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom('noreply.tcshop@gmail.com', 'TcShop');
                    $mail->addAddress($email_cliente);     //Add a recipient
                    //$mail->addAddress($correo_int2);     //Add a recipient2



                    //Content
                    $mail->isHTML(true);//Set email format to HTML
                    $mail->Subject = 'DOCUMENTO TRATA.DATOS CREDIAVALES';
                    $mail->Body    = 'Atraves del siguiente link puedes visualizar el pdf firmado para tu credito con CREDIAVALES <a href="https://www.noa10.com/crm/generar_trata_datos_form.php?id='.$id_trata.'" target="blank">DESCARGAR PDF</a>.';
                 
                    if($mail->send()){
                
                        $validacion = 1;

                    }else{

                        $response = array(
                            "response" => "error_correo"
                        );

                        echo json_encode($response);
                        die();

                    }

                } catch (Exception $e) {

                    $response = array(
                        "response" => "error_correo",
                        "error" => $mail->ErrorInfo
                    );

                    echo json_encode($response);
                    die();

                }


    }

    if ($validacion == 1) {
        $response = array("response" => "success");        
    }else{
        $response = array("response" => "error");
    }


}else if(isset($_POST['data'])){

        $errores = array();

        //var_dump($_POST);
        //die();

        foreach($_POST as $nombre_campo => $valor){
            if ($valor == '' && $nombre_campo != 'telefono_trabajo') {
                array_push($errores, 1);
            }
            if($nombre_campo == "salario_cliente" || $nombre_campo == "cuota_inicial" || $nombre_campo == "valor_cuota" || $nombre_campo == "total_credito"){
                $valor = str_replace(array('\'', '"'), '', $valor);
               $valor == string_sanitize($valor);
            }
           $asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
           eval($asignacion);
        }

        if(empty($errores)){

            /*
            $salario_cliente = str_replace('.', '', $salario_cliente);
            $salario_cliente = str_replace("'", '', $salario_cliente);
            $salario_cliente = str_replace(',', '', $salario_cliente);
            $cuota_inicial = str_replace('.', '', $cuota_inicial);
            $cuota_inicial = str_replace("'", '', $cuota_inicial);
            $cuota_inicial = str_replace(',', '', $cuota_inicial);
            $valor_cuota = str_replace('.', '', $valor_cuota);
            $valor_cuota = str_replace("'", '', $valor_cuota);
            $valor_cuota = str_replace(',', '', $valor_cuota);
            $total_credito = str_replace('.', '', $total_credito);
            $valor_cuota = str_replace("'", '', $valor_cuota);
            $valor_cuota = str_replace(',', '', $total_credito);
            */

            $salario_cliente = string_sanitize($salario_cliente);
            $cuota_inicial = string_sanitize($cuota_inicial);
            $valor_cuota = string_sanitize($valor_cuota);
            $total_credito = string_sanitize($total_credito);

            $codigo = 2372;
            $clave = 'F321984';

            $conn = new mysqli($host, $user, $pass, $db);
            $stmt = $conn->prepare("INSERT INTO `form_tratamiento_datos`(`cedula`, `fecha_exp`, `nombre_apellidos`, `direccion_ciudad`, `telefono_contacto`, `trabajo_ciudad`, `telefono_trabajo`, `cargo`, `salario`, `antiguedad`, `referencia1`, `referencia2`, `referencia3`, `referencia4`, `telefono_r1`, `telefono_r2`, `telefono_r3`, `telefono_r4`, `id_modelo_compra`, `cuota_inicial`, `cuotas_numero`, `valor_cuota`, `valor_total`, `codigo`, `clave`, `fecha_registro`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
            $stmt->bind_param("isssssssdsssssssssidiiiis", $cedula_cliente, $fecha_exp, $nombre_apellidos, $direccion_ciudad, $telefono_contacto, $trabajo_ciudad, $telefono_trabajo, $cargo_cliente, $salario_cliente, $antiguedad_trabajo, $referencia1, $referencia2, $referencia3, $referencia4, $telefono1, $telefono2, $telefono3, $telefono4, $compra_modelo, $cuota_inicial, $numero_cuotas, $valor_cuota, $total_credito, $codigo, $clave);
            $stmt-> execute();

            if ($stmt->affected_rows == 1) {

                $id_trata = $stmt->insert_id;

                $response = array('response' => 'success', 'id_trata' => $id_trata);

                $route = '../documents/firmas/'.$id_trata.'/'; //route where images are saved for the checklist.

                $signature = $_POST['data']; 
                $signatureFileName = 'signature-'.$id_trata.'.png';
                $signature = str_replace('data:image/png;base64,', '', $signature);
                $signature = str_replace(' ', '+', $signature);
                $data = base64_decode($signature);
                if (!file_exists($route)) {
                mkdir($route, 0777, true);
                }
                $file = $route.$signatureFileName;
                file_put_contents($file, $data);

                $correo_int1 = "comercial1@gmail.com";

                $correo_int2 = "directorcomercial@tucelular.net.co";

                $correo_int3 = "frank95299@gmail.com";

                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'noreply.tcshop@gmail.com';                     //SMTP username
                    $mail->Password   = 'Soporte0319+';                               //SMTP password
                    $mail->SMTPSecure = 'tls';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom('noreply.tcshop@gmail.com', 'TcShop');
                    $mail->addAddress($correo_int1);     //Add a recipient
                    $mail->addAddress($correo_int2);     //Add a recipient2
                    $mail->addAddress($correo_int3);     //Add a recipient3

                    //Content
                    $mail->isHTML(true);//Set email format to HTML
                    $mail->Subject = 'NUEVO CLIENTE CREDIAVALES';
                    $mail->Body    = 'Un nuevo cliente creadiavales ha firmado el tratamiento de datos, descarga el PDF para ver la información: <a href="https://www.noa10.com/crm/generar_trata_datos_form.php?id='.$id_trata.'" target="blank">PDF TRATAMIENTO DE DATOS</a>.';
                 
                    if($mail->send()){
                
                        $validacion = 1;

                    }else{

                        $response = array(
                            "response" => "error_correo"
                        );

                        echo json_encode($response);
                        die();

                    }

                } catch (Exception $e) {

                    $response = array(
                        "response" => "error_correo",
                        "error" => $mail->ErrorInfo
                    );

                    echo json_encode($response);
                    die();

                }

            }else{

                $response = array('response' => 'error', 'error' => $stmt->error);
            }

            $stmt->close();   
            $conn ->close();

        }else{

            $response = array(
                "response" => "falta"
            );

        }

}else{

    $response = array(
        "response" => "firma"
    );

}


echo json_encode($response);

?>