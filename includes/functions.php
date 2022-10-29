<?php


function qry($query)
{

  global  $host;

  global $user;

  global $pass;

  global $db;


  $con = mysqli_connect($host, $user, $pass, $db);
  $con->set_charset("utf8"); //Estableciendo utf8
  $result = mysqli_query($con, $query);
  mysqli_close($con);

  return $result;
}


function img($img, $x = 50, $y = 50, $t = 0)
{
  global $id_pagina;
  $str = '<img src="../includes/img.php?img=' . $id_pagina . '/' . $img . '&x=' . $x . '&y=' . $y . '&t=' . $t . '"/>';

  return $str;
}


function image($img, $x = 50, $y = 50, $t = 0)
{
  $str = '<img src="../includes/image.php?img=' . $img . '&x=' . $x . '&y=' . $y . '&t=' . $t . '"/>';

  return $str;
}


function texto($texto, $capital = 0)
{
  global $a;
  $txt = ucfirst($a["$texto"]);

  if ($capital == 1) {
    $txt = strtolower($txt);
  } elseif ($capital == 2) {
    $txt = ucwords($txt);
  } elseif ($capital == 3) {
    $txt = strtoupper($txt);
  }
  return $txt;
}


function getToken()
{
  $token = sha1(mt_rand());
  if (!isset($_SESSION['tokens'])) {
    $_SESSION['tokens'] = array($token => 1);
  } else {
    $_SESSION['tokens'][$token] = 1;
  }
  return $token;
}


function isTokenValid($token)
{
  if (!empty($_SESSION['tokens'][$token])) {
    unset($_SESSION['tokens'][$token]);
    return true;
  }
  return false;
}



function generateRandomString($length)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}


function execute_scalar($sql, $def = "")
{
  //echo $sql;
  //die();

  global  $host;

  global $user;

  global $pass;

  global $db;

  $con = mysqli_connect($host, $user, $pass, $db);

  $rs = mysqli_query($con,$sql) or die(mysqli_error($sql));
  //$rs = mysqli_query($con, $sql) or die($sql);
  if (mysqli_num_rows($rs)) {
    $r = mysqli_fetch_row($rs);
    mysqli_free_result($rs);
    return $r[0];
  }
  return $def;
  mysqli_close($con);
  
}



function udate($format, $utimestamp = null)
{
  if (is_null($utimestamp))
    $utimestamp = microtime(true);

  $timestamp = floor($utimestamp);
  $milliseconds = round(($utimestamp - $timestamp) * 1000000);

  return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}



function generatelicense()
{

  $miliseconds = execute_scalar("select DATE_ADD(UTC_TIMESTAMP(6), INTERVAL -5 HOUR)");


  $license = execute_scalar("select DATE_FORMAT(DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), '%Y%m%d%k%i%s')");



  return $license . substr($miliseconds, -6);
}



function get_client_ip()
{
  $ipaddress = '';
  if (getenv('HTTP_CLIENT_IP'))
    $ipaddress = getenv('HTTP_CLIENT_IP');
  else if (getenv('HTTP_X_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
  else if (getenv('HTTP_X_FORWARDED'))
    $ipaddress = getenv('HTTP_X_FORWARDED');
  else if (getenv('HTTP_FORWARDED_FOR'))
    $ipaddress = getenv('HTTP_FORWARDED_FOR');
  else if (getenv('HTTP_FORWARDED'))
    $ipaddress = getenv('HTTP_FORWARDED');
  else if (getenv('REMOTE_ADDR'))
    $ipaddress = getenv('REMOTE_ADDR');
  else
    $ipaddress = 'UNKNOWN';
  return $ipaddress;
}






function profile($opc, $userid)
{

  global $host;
  global $user;
  global $pass;
  global $db;

  $conn = new mysqli($host, $user, $pass, $db);
  $stmt = $conn->prepare("SELECT count(*) FROM perfiles_usuarios where id_usuario = ? and id_permiso = ?");
  $stmt->bind_param("ii", $userid, $opc);
  $stmt->execute();
  $stmt->bind_result($count);

  while ($stmt->fetch()) {
  }

  $stmt->close();
  $conn->close();

  if ($count > 0) {
    return 1;
  } else {
    return 0;
  }
}




function encrypt($string, $key)
{
  $result = '';
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) + ord($keychar));
    $result .= $char;
  }
  return base64_encode($result);
}

function decrypt($string, $key)
{
  $result = '';
  $string = base64_decode($string);
  for ($i = 0; $i < strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key)) - 1, 1);
    $char = chr(ord($char) - ord($keychar));
    $result .= $char;
  }
  return $result;
}

function utf8ize($d)
{

  if (is_array($d)) {
    foreach ($d as $k => $v) {
      $d[$k] = utf8ize($v);
    }
  } else if (is_string($d)) {
    return utf8_encode($d);
  }
  return $d;
}

function rmDir_rf($carpeta)
{
  $folderCont = scandir($carpeta);
  foreach ($folderCont as $clave => $valor) {
    unlink($carpeta . '/' . $folderCont[$clave]);
  }
  rmdir($carpeta);
}

function deleteDirectory($dir)
{

  if (!$dh = @opendir($dir)) return;
  while (false !== ($current = readdir($dh))) {
    if ($current != '.' && $current != '..') {
      //echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
      if (!@unlink($dir . '/' . $current))
        deleteDirectory($dir . '/' . $current);
    }
  }
  closedir($dh);
  //echo 'Se ha borrado el directorio '.$dir.'<br/>';
  @rmdir($dir);
}

function peticion_get($url, $token)
{

  //$url = "https://www.servicioprueba.com/request";
  $conexion = curl_init();
  // --- Url
  curl_setopt($conexion, CURLOPT_URL, $url);
  // --- Petición GET.
  curl_setopt($conexion, CURLOPT_HTTPGET, TRUE);
  // --- Cabecera HTTP.
  curl_setopt($conexion, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: ' . $token));
  // --- Para recibir respuesta de la conexión.
  curl_setopt($conexion, CURLOPT_RETURNTRANSFER, 1);
  // --- Respuesta
  $respuesta = curl_exec($conexion);

  if (curl_errno($conexion)) echo curl_error($conexion);
  else json_decode($respuesta, true);

  curl_close($conexion);

  return $respuesta;
}

function JSON2Array($data)
{

  return  (array) json_decode(stripslashes($data));
}

function peticion_post($url, $token, $dates)
{

  $dates_format = '';
  $dates_format = $dates;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $dates_format); //payload

  // --- Cabecera HTTP.
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: ' . $token));

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  if (curl_errno($ch)) {

    return curl_error($ch);
  } else {

    $decoded = json_decode($response, true);
    return $decoded;
  }

  curl_close($ch);
}

function post_aldeamo($message, $destination, $date_to_send)
{

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://apitellit.aldeamo.com/SmsiWS/smsSendPost/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '{
  "country": 57, "message": "' . $message . '", "encoding": "unicode", "messageFormat": 1, "addresseeList": [
  {
  "mobile": ' . $destination . ',
  "correlationLabel": "habeasDataSms", "url": "https://www.tcshop.co/politica-para-el-tratamiento-de-datos-personales/"
  }
  ]
  }
  ',
    CURLOPT_HTTPHEADER => array(
      'Authorization: Basic dHVjZWx1bGFyOlR1Y2VsdWxhcjIwMjEk',
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  $decoded = json_decode($response, true);
  curl_close($curl);
  $status = $decoded['status'];
  return $status;
}

function loginApiBeriblock($user_beriblock, $pass_beriblock, $url_send)
{

  $dates = array(
    'username' => $user_beriblock,
    'password' => $pass_beriblock
  );

  $url = $url_send . "api/v1/protocol/openid-connect/login";

  $jsonDataEncoded = json_encode($dates);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);

  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded); //payload

  // --- Cabecera HTTP.
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $response = curl_exec($ch);

  //print_r($response);
  //die();

  if (curl_errno($ch)) {

    return curl_error($ch);
  } else {

    $decoded = json_decode($response, true);
    if ($decoded == '') {
      $decoded = array(
        "response" => "error"
      );
    }
    return $decoded;
  }

  curl_close($ch);
}

function string_sanitize($s)
{
  $result = preg_replace("/[^a-zA-Z0-9]+/", "", html_entity_decode($s, ENT_QUOTES));
  return $result;
}

function validateScreen($id_usuario)
{

  $validate1 = execute_scalar("SELECT COUNT(id) AS total FROM perfiles_usuarios WHERE id_usuario = $id_usuario AND id_permiso = 13 AND del = 0");
  $validate2 = execute_scalar("SELECT COUNT(id) AS total FROM perfiles_usuarios WHERE id_usuario = $id_usuario AND id_permiso <> 13 AND del = 0");

  if ($validate1 == 1 && $validate2 == 0) {
    return 1;
  } else {
    return 0;
  }
}


function beriblock_upload_documents($path, $url, $fingerprint, $url_simple, $ip_local, $token, $name, $description)
{

  //echo $path;
  //die();

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('document' => curl_file_create($path), 'name' => $name, 'description' => $description, 'url' => $url_simple, 'isTemplate' => 'false', "ip" => $ip_local, "fringerprint" => $fingerprint),
    CURLOPT_HTTPHEADER => array(
      'Authorization: ' . $token
    ),
  ));

  $response = json_decode(curl_exec($curl));

  curl_close($curl);

  //var_dump($response);
  //die();

  return $response;
}


function beriblock_upload_documents_pagare($path, $url, $fingerprint, $url_simple, $ip_local, $token, $name, $description)
{

  //echo $path;
  //die();

  $curl2 = curl_init();

  curl_setopt_array($curl2, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('document' => curl_file_create($path), 'name' => $name, 'description' => $description, 'url' => $url_simple, 'isTemplate' => 'false', "ip" => $ip_local, "fringerprint" => $fingerprint),
    CURLOPT_HTTPHEADER => array(
      'Authorization: ' . $token
    ),
  ));

  $response2 = json_decode(curl_exec($curl2));

  curl_close($curl2);

  //var_dump($response);
  //die();

  return $response2;
}

function AltiriaCertPdf($destination, $path, $prospecto_cedula, $debug, $from)
{

  //URL base de los recursos REST
  $baseUrl = 'http://www.altiria.net/apirest/ws';

  //Se inicia el objeto CUrl 
  $ch = curl_init($baseUrl . '/certPdfFile');

  //XX, YY y ZZ se corresponden con los valores de identificaci�n del
  //usuario en el sistema.
  // domainId solo es necesario si el login no es un email
  $credentials = array(
    //'domainId' => 'XX', 
    'login'    => 'soporte@tucelular.net.co',
    'passwd'   => 'SOPORTE4302'
  );

  $opc1 = "contrato";
  $opc2 = "CONTRATO";

  if ($from == "pagare") {

    $opc1 = "pagaré";
    $opc2 = "PAGARÉ";
  }

  $textoSms = "Ingresa al siguiente link para firmar tu " . $opc1 . " de credito con TCSHOP ";
  $titulo_altiria = $opc2 . " FIRMADO DEL CLIENTE " . $prospecto_cedula;

  $document = array(
    'destination' => $destination,
    'sequence' => 1,
    'sigLocX' => 0,
    'sigLocY' => 50,
    'sigLocWidth' => 200,
    'sigLocHeight' => 140,
    'sigLocPage' => 1,
    'sigLocPage' => 2,
    'type'    => 'simple',
    'smsOtpSig'   => true,
    'smsText' => $textoSms,
    'title' => $titulo_altiria,
    "callback" => true
  );

  $jsonData = array(
    'credentials' => $credentials,
    'document' => $document,
  );

  //Se construye el mensaje JSON
  $jsonDataEncoded = json_encode($jsonData);

  //Indicamos que nuestra petici�n sera Post
  curl_setopt($ch, CURLOPT_POST, 1);

  //Se fija el tiempo m�ximo de espera para conectar con el servidor (5 segundos)
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

  //Se fija el tiempo m�ximo de espera de la respuesta del servidor (60 segundos)
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);

  //Para que la peticion no imprima el resultado como un 'echo' comun
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  //Se a�ade el JSON al cuerpo de la petici�n codificado en UTF-8
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

  //Se fija el tipo de contenido de la peticion POST
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));

  //Se env�a la petici�n y se consigue la respuesta
  $response = curl_exec($ch);

  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  //Error en la respuesta del servidor 

  if ($statusCode == 200) {

    $json_parsed = json_decode($response);
    $status = $json_parsed->status;
    $url = $json_parsed->url;
    $id = $json_parsed->id;

    //Upload file request			
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/pdf'));
    $filesize = filesize($path);
    $fp = fopen($path, 'rb');
    $binary = fread($fp, $filesize);
    fclose($fp);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $binary);

    // Get response from the server.
    $httpResponse = curl_exec($ch);

    curl_close($ch);

    $respuesta = array(
      "response" => "success",
      "status" => $status,
      "url" => $url,
      "id" => $id
    );
  } else {

    $respuesta = array(
      "response" => "error",
      "status" => $statusCode
    );
  }

  return $respuesta;
}

function  Altiria_check_pdf($id_documento_altiria)
{

  //URL base de los recursos REST
  $baseUrl = 'http://www.altiria.net/apirest/ws';

  //Se inicia el objeto CUrl 
  $ch = curl_init($baseUrl . '/checkPdfFile');

  //XX, YY y ZZ se corresponden con los valores de identificaci�n del
  //usuario en el sistema.
  // domainId solo es necesario si el login no es un email
  $credentials = array(
    //'domainId' => 'XX', 
    'login'    => 'soporte@tucelular.net.co',
    'passwd'   => 'SOPORTE4302'
  );

  $query = array(
    'id' => $id_documento_altiria
  );

  $jsonData = array(
    'credentials' => $credentials,
    'query' => $query,
  );

  //Se construye el mensaje JSON
  $jsonDataEncoded = json_encode($jsonData);

  //Indicamos que nuestra petici�n sera Post
  curl_setopt($ch, CURLOPT_POST, 1);

  //Se fija el tiempo m�ximo de espera para conectar con el servidor (5 segundos)
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

  //Se fija el tiempo m�ximo de espera de la respuesta del servidor (60 segundos)
  curl_setopt($ch, CURLOPT_TIMEOUT, 60);

  //Para que la peticion no imprima el resultado como un 'echo' comun
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  //Se a�ade el JSON al cuerpo de la petici�n codificado en UTF-8
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

  //Se fija el tipo de contenido de la peticion POST
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));

  //Se env�a la petici�n y se consigue la respuesta
  $response = curl_exec($ch);

  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  //echo $statusCode."<br>";
  //print_r($response);

  if ($statusCode == 200) {

    $json_parsed = json_decode($response);

    //var_dump($json_parsed);
    //die();

    $status = $json_parsed->status;
    $fileStatus = $json_parsed->fileStatus;
    $array_urls = $json_parsed->files;
    $array_urls_length = count($array_urls);

    //echo $array_urls_length."<br>";

    //print_r($array_urls);
    //die();

    $document_signed_status = "";
    $document_signed_url = "";

    if ($fileStatus == "signed") {

      if ($array_urls_length < 8) {
        $document_signed_status = $array_urls[1]->fileType;
        $document_signed_url = $array_urls[1]->fileUrl;
      } else {
        $document_signed_status = $array_urls[5]->fileType;
        $document_signed_url = $array_urls[5]->fileUrl;
      }
    }

    $respuesta = array(
      "response" => "success",
      "status" => $status,
      "fileStatus" => $fileStatus,
      "document_signed_status" => $document_signed_status,
      "document_signed_url" => $document_signed_url
    );
  } else {

    $respuesta = array(
      "response" => "error",
      "status" => $statusCode
    );
  }

  return $respuesta;
}


function traer_plataformas($id_prospecto)
{

  $query_exec = "SELECT resultados_prospectos.id_plataforma, plataformas_credito.nombre_plataforma, resultados_prospectos.resultado_dc FROM resultados_prospectos LEFT JOIN plataformas_credito ON resultados_prospectos.id_plataforma = plataformas_credito.id WHERE resultados_prospectos.id_prospecto = $id_prospecto AND resultados_prospectos.del = 0";
  $result_exec = qry($query_exec);
  $lista_plataformas = '<ul>';
  $puntos_li = "";

  while ($row_exec = mysqli_fetch_array($result_exec)) {
    //var_dump($row_exec);
    $id_plataforma = $row_exec['id_plataforma'];
    $nombre_plataforma = $row_exec['nombre_plataforma'];
    $resultado_dc = $row_exec['resultado_dc'];
    if ($id_plataforma == 1) {
      $color_plataforma = "success";
    } else if ($id_plataforma == 2) {
      $color_plataforma = "info";
    } else if ($id_plataforma = 3) {
      $color_plataforma = "warning";
    }
    if ($resultado_dc == 1) {
      $color_resultado = "success";
      $estado_resultado = "APROBADO";
    } else if ($resultado_dc == 2) {
      $color_resultado = "danger";
      $estado_resultado = "RECHAZADO";
    } else if ($resultado_dc == 3) {
      $color_resultado = "warning";
      $estado_resultado = "CONTRACTO ACTIVO";
    } else if ($resultado_dc == 4) {
      $color_resultado = "info";
      $estado_resultado = "OTRO";
    }


    $puntos_li .= '<li><span class="label label-' . $color_plataforma . '">' . $nombre_plataforma . '</span>&nbsp;&nbsp;<span class="label label-' . $color_resultado . '">' . $estado_resultado . '</span></li>';
  }

  if ($puntos_li == '') {

    $lista_plataformas = '<span class="label label-info">N/A</span>';
  } else {

    $lista_plataformas .= $puntos_li;
    $lista_plataformas .= "</ul>";
  }

  return $lista_plataformas;
}
