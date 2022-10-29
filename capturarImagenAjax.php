<?php


//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "subir_img_prospecto") {
	
	try {

		$foto = $_POST['foto'];
		$id_prospecto = $_POST['id_prospecto'];
		$type = $_POST['type'];

		$route = '../documents/prospects/'.$id_prospecto.'/';
		$route2 = './documents/prospects/'.$id_prospecto.'/'; 
		$pictureFileName = $id_prospecto.'-'.$type.'.jpg';
		$foto = str_replace('data:image/png;base64,', '', $foto);
		$foto = str_replace(' ', '+', $foto);
		$data = base64_decode($foto);
		if (!file_exists($route)) {
			mkdir($route, 0777, true);
		}
		$file = $route.$pictureFileName;
		if (is_file($route.$pictureFileName)) {
			unlink($file);
		}
		file_put_contents($file, $data);

		if (is_file($route.$pictureFileName)) {

			$img_nombre_archivo = $id_prospecto.'-'.$type;

			$tipo_img = "FRONTAL";

			if($type == 1){
				$tipo_img = "ATRAS";
			}else if($type == 2){
				$tipo_img = "SELFIE";
			}

			$img_ext = "jpg";

			$validate_save = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND tipo_img = '$tipo_img'");

			if ($validate_save == 0) {
				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO `imagenes_prospectos`(`id_prospecto`, `imagen_nombre_archivo`, `tipo_img`, `imagen_extension`, `fecha_registro`) VALUES (?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
		        $stmt->bind_param("isss", $id_prospecto, $img_nombre_archivo, $tipo_img, $img_ext);
		        $stmt-> execute();

		        if ($stmt->affected_rows == 1) {

		        	$response = array(
						"response" => "success",
						"full_route" => $route2.$pictureFileName,
						"id_prospecto" => $id_prospecto,
						"type" => $type
					);

		        }else{

		        	$respose = array(
		        		"response" => "error",
		        		"error" => $stmt->error
		        	);

		        }

			}else{

				$response = array(
					"response" => "success",
					"full_route" => $route2.$pictureFileName,
					"id_prospecto" => $id_prospecto,
					"type" => $type
				);

			}

			
			
		}else{
			$response = array(
				"response" => "error"
			);
		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "insertar_prospecto"){

	try {

		$cedula_prospecto = $_POST['cedula_prospecto'];
 		$nombre_prospecto = $_POST['nombre_prospecto'];
 		$apellidos_prospecto = $_POST['apellidos_prospecto'];
 		$contacto_prospecto = $_POST['contacto_prospecto'];
 		$contacto_prospecto = substr($contacto_prospecto, 1,3).substr($contacto_prospecto, 5,3).substr($contacto_prospecto, 9,4);
 		$ciudad_prospecto = $_POST['ciudad_prospecto'];
 		$id_usuario = $_POST['id_usuario'];

 		$contacto_w = $_POST['contacto2_whatsaap'];

 		$id_prospecto = $_POST['id_prospecto'];

 		$marca_prospecto = $_POST['marca_prospecto'];

	 		$validate = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE prospecto_cedula = $cedula_prospecto AND prospectos.del = 0");

	 		if($validate == 0){

		 		$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO prospectos (id, prospecto_cedula, id_usuario_responsable, fecha_registro) VALUES (?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("isi", $id_prospecto, $cedula_prospecto, $id_usuario);
				$stmt-> execute();

				if ($stmt->affected_rows == 1) {
					/*
					$query_insert = "INSERT INTO prospecto_detalles (id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, ciudad_id, fecha_registro) VALUES ($id_prospecto, '$nombre_prospecto', '$apellidos_prospecto', '$contacto_prospecto', '$contacto_w', $ciudad_prospecto, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))";
					echo $query_insert;
					die();
					*/
					$stmt->close();
					$stmt = $conn->prepare("INSERT INTO prospecto_detalles (id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, ciudad_id, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("issssi", $id_prospecto, $nombre_prospecto, $apellidos_prospecto, $contacto_prospecto, $contacto_w, $ciudad_prospecto);
					$stmt-> execute();

					if ($stmt->affected_rows == 1) {

						$id_prospecto_next = execute_scalar("SELECT MAX(id) AS max_id FROM prospectos");

						$id_prospecto_next = $id_prospecto_next + 1;

						qry("INSERT INTO modelos_prospectos (id_prospecto, id_marca, fecha_registro) VALUES ($id_prospecto, $marca_prospecto, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");

						$response = array(
							"response" => "success",
							"id_prospecto_next" => $id_prospecto_next
						);

					}else{

						$response = array(
							"response" => "error",
							"error" => $stmt->error
						);

					}

				}else{

					$response = array(
						"response" => "error",
						"error" => $stmt->error
					);

				}

				$stmt->close();	  
				$conn ->close();

			}else{

				$response = array('response' => "repetida");

			}

		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);



}