<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action'] == "eliminar") {
	
	try {

		$id_trata = $_POST['id_trata'];

		$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE form_tratamiento_datos SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_trata);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array(
					"response" => "success",
					"id_trata" => $id_trata
				);

			}else{

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);

			}

			$stmt->close();
	    	$conn ->close();
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}


?>