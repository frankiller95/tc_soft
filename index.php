<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","36000");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","36000");

session_name("tc_shop");
session_start();


header('Content-Type: text/html; charset=utf-8');
// No mostrar los errores de PHP
//error_reporting(0);

include('includes/connection.php');
include('includes/functions.php');

date_default_timezone_set('America/Bogota');

$errorMsg = '';

//$caducidad = session_get_cookie_params();

//valida si se recibe el email del login
if (isset($_POST['email'])) {
	
	// captura los datos	
	$usuario_email = $_POST['email'];
	$usuario_pass = $_POST['password'];

	//contamos la cantidad de correos registrados con este email en la tabla usuarios
	$validar_email = execute_scalar("SELECT COUNT(*) FROM usuarios WHERE email = '$usuario_email'");

	$validar_cedula = execute_scalar("SELECT COUNT(*) FROM usuarios WHERE cedula = '$usuario_email'");

	$con_email = 0;

	$con_cedula = 0;

	// si el email es mayor a 0 significa que ya esta registrado
	if($validar_email > 0 || $validar_cedula > 0){

		if($validar_email > 0){
			$con_email = 1;
		}else if($validar_cedula > 0){
			$con_cedula = 1;
		}

			//traemos los datos del usuario con el correo validado
			$conn = new mysqli($host, $user, $pass, $db);
			if($con_email == 1){
				$stmt = $conn->prepare("select id, email, password, del from usuarios where email = ? AND del = 0");
			}else if($con_cedula == 1){
				$stmt = $conn->prepare("select id, cedula, password, del from usuarios where cedula = ? AND del = 0");
			}
			$stmt->bind_param("s" ,$usuario_email);
			$stmt->execute();
			if($con_email == 1){
				$stmt->bind_result($r_id,$r_email,$r_pass,$r_del);
			}else if($con_cedula == 1){
				$stmt->bind_result($r_id,$r_cedula,$r_pass,$r_del);
			}


			
			while ($stmt->fetch()) {

				$password = $r_pass;
				$id_usuario = $r_id;   
				$estado = $r_del;

				$validate_loguin = execute_scalar("SELECT logueado FROM loguin_control WHERE id_usuario = $id_usuario");			

			}

			
			$stmt->close();
			$conn ->close();


			if (password_verify($usuario_pass, $password) && $estado == 0){
			
				$errorMsg = "Inicio de sesión correcto";

				$_SESSION['id'] = $id_usuario;

			}elseif (password_verify($usuario_pass, $password) && $estado == 1) { // en caso de que el usuario este inactivo

				$errorMsg = '<span class="text-danger" style="font-weigth: 700;">UPPS!</span>'. ' '. 'Este usuario no se encuentra activado.';

			} else { // en caso de que la contraseña sea incorrecta.

				$errorMsg = '<strong>UPPS!</strong>'. ' '. 'La contraseña indicada es incorrecta para este usuario.';

			}	

	}else{

		$errorMsg = '<strong>UPPS!</strong>'. ' '. 'Este email es incorrecto o no esta registrado.';
		
	}

}else{
	//$errorMsg = '<strong>UPPS!</strong>'. ' '. 'Escribe un correo o una cedula registrada.';
}
	


if(isset($_SESSION['id'])){

	$id_usuario = $_SESSION['id'];
	$hora_actual = date('Y-m-j H:i:s', time());
    $validate_home = 0;

	$hoy = getdate();
	
	if (isset($_GET['page'])) {

		$page = $_GET['page'];

	}else{

		if (validateScreen($id_usuario)==1) {
			$page = "dashboard_IE";
		}else{
			$page = "dashboard";
		}	
		$validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario");
		$validate_gane2 = execute_scalar("SELECT COUNT(id) AS total FROM perfiles_usuarios WHERE id_usuario = $id_usuario AND id_permiso <> 11 AND del = 0");
		if ($validate_gane == 1 && $validate_gane2 == 0) {
			//$page = "clientes_gane";
			$page = "refiere_gana";
            $validate_home = 1;
		}
		
	}
	
    
    include 'home.php';
    

}else{

	$tablet_browser = 0;
	$mobile_browser = 0;
	$body_class = 'desktop';

	$dispositivo = '';
	
	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$tablet_browser++;
		$body_class = "tablet";
	}
	
	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$mobile_browser++;
		$body_class = "mobile";
	}
	
	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
		$body_class = "mobile";
	}
	
	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');
	
	if (in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}
	
	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
		$mobile_browser++;
		//Check for tablets on opera mini alternative headers
		$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
		$tablet_browser++;
		}
	}
	if ($tablet_browser > 0) {
	// Si es tablet has lo que necesites
	$dispositivo = 'es tablet';
	$pagina_login = "login_movil";
	}
	else if ($mobile_browser > 0) {
	// Si es dispositivo mobil has lo que necesites
	$dispositivo = 'es un mobil';
	$pagina_login = "login_movil";
	}
	else {
	// Si es ordenador de escritorio has lo que necesites
	$dispositivo = 'es un ordenador de escritorio';
	$pagina_login = "login";
	}  

	include $pagina_login.'.php'; 

}


?>