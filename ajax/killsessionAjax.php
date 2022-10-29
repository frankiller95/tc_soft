<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

date_default_timezone_set('America/Bogota');



if ($_POST['action'] == "cerrar_session") {
	
	try {


		$id_usuario = $_POST['id_usuario'];

		$query = "UPDATE loguin_control SET logueado = b'0' WHERE id_usuario = $id_usuario";

		$result = qry($query);
 		
 		if ($result) {
 			$response = 1;
 		}else{
 			$response = 0;
 		}
 	
 		//var_dump($_POST);
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo $response;

}

?>