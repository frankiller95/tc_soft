<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action']=="select_dispositivos") {

	try {

		$url = "https://app.nuovopay.com/dm/api/v1/devices.json?page=0";
		$token = "Token 4c191e2b135f4672a14bc0a71e9c0166";
		$respuesta = peticion_get($url, $token);

		$response = array();

		$devices = JSON2Array($respuesta);
		header('Content-Type: text/html; charset=utf-8');

		$total_devices = $devices['total_count'];

		//echo $total_devices;

		for ($i=0; $i<$total_devices; $i++) { 

			$objeto = $devices['devices'][$i];
			$values = get_object_vars($objeto);
			//echo $values;
			//$new_array = array("dispositivo" => $values);
			array_push($response, $values); 
		}

		//die();
		//var_dump($devices['devices'][0]);
		/*
		$objeto = $devices['devices'][0];

		
		$device_id = $values['id'];

		echo $device_id;

		die();
		*/
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);
}


?>