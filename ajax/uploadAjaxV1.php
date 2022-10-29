<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


//var_dump($_POST);

if(isset($_POST['data'])){

        $cedula_cliente = $_POST['cedula_cliente'];
        $fecha_exp = $_POST['fecha_exp'];
        $nombre_apellidos = $_POST['nombre_apellidos'];
        $direccion_ciudad = $_POST['direccion_ciudad'];
        $telefono_contacto = $_POST['telefono_contacto'];
        $trabajo_ciudad = $_POST['trabajo_ciudad'];
        $telefono_trabajo = $_POST['telefono_trabajo'];
        $cargo_cliente = $_POST['cargo_cliente'];
        $salario_cliente = $_POST['salario_cliente'];
        $salario_cliente = str_replace('.', '', $salario_cliente);
        $antiguedad_trabajo = $_POST['antiguedad_trabajo'];
        $referencia1 = $_POST['referencia1'];
        $referencia2 = $_POST['referencia2'];
        $referencia3 = $_POST['referencia3'];
        $id_modelo = $_POST['compra_modelo'];
        $cuota_inicial = $_POST['cuota_inicial']; 
        $numero_cuotas = $_POST['numero_cuotas'];
        $valor_cuota = $_POST['valor_cuota'];
        $valor_cuota = str_replace('.', '', $valor_cuota);
        $total_credito = $_POST['total_credito'];
        $total_credito = str_replace('.', '', $total_credito);
        $codigo = 2372;
        $clave = 'F321984';

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO `form_tratamiento_datos`(`cedula`, `fecha_exp`, `nombre_apellidos`, `direccion_ciudad`, `telefono_contacto`, `trabajo_ciudad`, `telefono_trabajo`, `cargo`, `salario`, `antiguedad`, `referencia1`, `referencia2`, `referencia3`, `id_modelo_compra`, `cuota_inicial`, `cuotas_numero`, `valor_cuota`, `valor_total`, `codigo`, `clave`, `fecha_registro`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
        $stmt->bind_param("isssssssdisssidiiiis", $cedula_cliente, $fecha_exp, $nombre_apellidos, $direccion_ciudad, $telefono_contacto, $trabajo_ciudad, $telefono_trabajo, $cargo_cliente, $salario_cliente, $antiguedad_trabajo, $referencia1, $referencia2, $referencia3, $id_modelo, $cuota_inicial, $numero_cuotas, $valor_cuota, $total_credito, $codigo, $clave);
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



        }else{

            $response = array('response' => 'error', 'error' => $stmt->error);
        }

        $stmt->close();   
        $conn ->close();

}else{

    $response = array(
        "response" => "firma"
    );

}


echo json_encode($response);

?>