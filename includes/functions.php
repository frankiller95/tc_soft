<?php


function qry($query) {
	
global	$host;

global $user;

global $pass;

global $db;
	

$con=mysqli_connect($host,$user,$pass,$db);
$result =mysqli_query($con, $query);
mysqli_close($con);
	
return $result;
}


function img($img,$x=50,$y=50,$t=0) {
global $id_pagina;
$str = '<img src="../includes/img.php?img='.$id_pagina.'/'.$img.'&x='.$x.'&y='.$y.'&t='.$t.'"/>' ;

return $str;

}


function image($img,$x=50,$y=50,$t=0) {
$str = '<img src="../includes/image.php?img='.$img.'&x='.$x.'&y='.$y.'&t='.$t.'"/>' ;

return $str;

}


function texto($texto,$capital=0){
  global $a;
  $txt = ucfirst($a["$texto"]);

  if($capital==1){ 
    $txt =strtolower($txt);

  }elseif($capital==2){
    $txt =ucwords($txt);

  }elseif($capital==3){
    $txt =strtoupper($txt);
  }
  return $txt;
}
  
    
function getToken(){
  $token = sha1(mt_rand());
  if(!isset($_SESSION['tokens'])){
    $_SESSION['tokens'] = array($token => 1);
  }
  else{
    $_SESSION['tokens'][$token] = 1;
  }
  return $token;
}


function isTokenValid($token){
  if(!empty($_SESSION['tokens'][$token])){
    unset($_SESSION['tokens'][$token]);
    return true;
  }
  return false;
}



function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}


function execute_scalar($sql,$def="") {
	
	
global	$host;

global $user;

global $pass;

global $db;
	
$con=mysqli_connect($host,$user,$pass,$db);

	$rs = mysqli_query($con,$sql) or die(mysqli_error().$sql);
	if (mysqli_num_rows($rs)) {
		$r = mysqli_fetch_row($rs);
		mysqli_free_result($rs);
		return $r[0];
		}
	return $def;
	mysqli_close($con);
}



function udate($format, $utimestamp = null) {
  if (is_null($utimestamp))
    $utimestamp = microtime(true);

  $timestamp = floor($utimestamp);
  $milliseconds = round(($utimestamp - $timestamp) * 1000000);

  return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
}



function generatelicense(){
	
	$miliseconds = execute_scalar("select DATE_ADD(UTC_TIMESTAMP(6), INTERVAL -5 HOUR)");
	
	
	$license = execute_scalar("select DATE_FORMAT(DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), '%Y%m%d%k%i%s')");
	
	
	
	return $license.substr($miliseconds,-6);
	
}



function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}






 function profile($opc,$userid) {
	 
	 global $host;
	 global $user;
	 global $pass;
	 global $db;
	 
$conn = new mysqli($host, $user, $pass, $db);
$stmt = $conn->prepare("SELECT count(*) FROM user_profile where id_user = ? and opc = ?");
$stmt->bind_param("ii", $userid,$opc); 
$stmt->execute(); 
$stmt->bind_result($count);

   while ($stmt->fetch()) {

   }

   $stmt->close();
	$conn ->close();
	 
	 if($count>0){
		 	return 1;
	 }else{
		 return 0;
	 }


}




function encrypt($string, $key) {
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}
 
function decrypt($string, $key) {
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   return $result;
}


function deleteDirectory($dir) {
  
    if(!$dh = @opendir($dir)) return;
    while (false !== ($current = readdir($dh))) {
        if($current != '.' && $current != '..') {
            //echo 'Se ha borrado el archivo '.$dir.'/'.$current.'<br/>';
            if (!@unlink($dir.'/'.$current)) 
                deleteDirectory($dir.'/'.$current);
        }       
    }
    closedir($dh);
    //echo 'Se ha borrado el directorio '.$dir.'<br/>';
    @rmdir($dir);
}


function utf8ize($d) {

    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
    
}

function rmDir_rf($carpeta){
  $folderCont = scandir($carpeta);
  foreach ($folderCont as $clave => $valor) {
    @unlink($carpeta.'/'.$folderCont[$clave]);
  }
  rmdir($carpeta);
}



?>