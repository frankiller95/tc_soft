<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if($_POST['action'] == "insertar"){

	try {

		//var_dump($_POST);
		//die();

		$materia_nombre = $_POST['materia_nombre'];

		$validate_materia = execute_scalar("SELECT COUNT(id) AS total FROM materias WHERE materia_nombre = '$materia_nombre'");

		if($validate_materia == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO materias (materia_nombre, fecha_registro) VALUES (?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("s", $materia_nombre);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$id_materia = $stmt->insert_id;

				$error = $stmt->error;

				$stmt->close();

				$response = array(
					"response" => "success",
					"id_materia" => $id_materia,
					"materia_nombre" => $materia_nombre
				);

			}else{
				
				$response = array(
					"response" => "error",
					"error" => $error
				);
			}
		  
			$conn ->close();

		}else{

			$response = array(
				"response" => "repetido"
			);

		}

	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_usuario"){

	try {

		$id_usuario = $_POST['id_usuario'];

		/*
		$query = "SELECT nombre, apellidos, email, usuario, id_rol, tipo_iden, numero_iden, celular, fijo, dob, ciudad_id FROM usuarios LEFT JOIN detalles_usuarios ON detalles_usuarios.id_usuario = usuarios.id WHERE usuarios.id = $id_usuario";

		echo $query;
		die();

		*/

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT nombre, apellidos, email, usuario, id_rol, tipo_iden, numero_iden, celular, fijo, dob, ciudad_id FROM usuarios LEFT JOIN detalles_usuarios ON detalles_usuarios.id_usuario = usuarios.id WHERE usuarios.id = ?");
		$stmt->bind_param("i", $id_usuario);
		$stmt-> execute();

		$meta = $stmt->result_metadata();
	    while ($field = $meta->fetch_field())
	    {
	        $params[] = &$row[$field->name];
	    }

	    call_user_func_array(array($stmt, 'bind_result'), $params);

	    while ($stmt->fetch()) {
	        foreach($row as $key => $val)
	        {
	            $c[$key] = $val;
	        }
	        $response[] = $c;
	    }

	    if ($response[0]['celular'] != '') {
	    	$response[0]['celular'] = '('.substr($response[0]['celular'], 0,3).')'.substr($response[0]['celular'], 3,3).'-'.substr($response[0]['celular'], 6,4);
	    }

	    if ($response[0]['fijo'] != '') {
	    	$response[0]['fijo'] = '('.substr($response[0]['fijo'], 0,3).')'.substr($response[0]['fijo'], 3,2).'-'.substr($response[0]['fijo'], 5,2);
	    }
	   
	    $stmt->close();
	    $conn ->close();
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "actualizar"){

	try {

		//var_dump($_POST);
		//die();

		$nombre_usuario = $_POST['nombre_usuario'];
		$apellidos_usuario = $_POST['apellidos_usuario'];
		$correo_usuario = $_POST['correo_usuario'];
		$nick_usuario = $_POST['nick_usuario'];
		$rol_usuario = $_POST['rol_usuario'];

		$tipo_iden_usuario = $_POST['tipo_iden_usuario'];
		$numero_iden_usuario = $_POST['numero_iden_usuario'];

		$password = "1234";    
		$passHash = password_hash($password, PASSWORD_BCRYPT);

		$id_usuario = $_POST['id_usuario'];

		$validate_email = execute_scalar("SELECT COUNT(id) AS total FROM usuarios WHERE email = '$correo_usuario' AND usuarios.id <> $id_usuario");

		$validate_nick = execute_scalar("SELECT COUNT(id) AS total FROM usuarios WHERE usuario = '$nick_usuario' AND usuarios.id <> $id_usuario");

		$validate_cedula = execute_scalar("SELECT COUNT(id) AS total FROM detalles_usuarios WHERE tipo_iden = $tipo_iden_usuario AND numero_iden = $numero_iden_usuario AND detalles_usuarios.id_usuario <> $id_usuario");


		if($validate_email == 0 && $validate_nick == 0 && $validate_cedula == 0){

			qry("DELETE FROM usuarios WHERE id = $id_usuario");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, apellidos, email, usuario, password, id_rol, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("isssssi", $id_usuario, $nombre_usuario, $apellidos_usuario, $correo_usuario, $nick_usuario, $passHash, $rol_usuario);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$error = $stmt->error;

				$stmt->close();

				$id_detalles = execute_scalar("SELECT detalles_usuarios.id FROM detalles_usuarios WHERE id_usuario = $id_usuario");

				qry("DELETE FROM detalles_usuarios WHERE id_usuario = $id_usuario");

				
				$celular_usuario = $_POST['celular_usuario'];
				$celular_usuario = substr($celular_usuario, 1,3).substr($celular_usuario, 5,3).substr($celular_usuario, 9,4);
				$fijo_usuario = $_POST['fijo_usuario'];
				$fijo_usuario = substr($fijo_usuario, 1,3).substr($fijo_usuario, 5,2).substr($fijo_usuario, 8,2);
				$dob_usuario = $_POST['dob_usuario'];
				$ciudad_usuario = $_POST['ciudad_usuario'];

				$id_institucion = 0;

				$stmt = $conn->prepare("INSERT INTO `detalles_usuarios`(id, `id_usuario`, `tipo_iden`, `numero_iden`, `celular`, `fijo`, `dob`, `id_institucion`, `ciudad_id`, `fecha_registro`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("iiissssii", $id_detalles, $id_usuario, $tipo_iden_usuario, $numero_iden_usuario, $celular_usuario, $fijo_usuario, $dob_usuario, $id_institucion, $ciudad_usuario);
				$stmt-> execute();
				$id_detalles = $stmt->insert_id;
				$stmt->close();


				if($rol_usuario == 2){

					$id_profesor = execute_scalar("SELECT profesores.id FROM profesores WHERE id_detalles = $id_detalles");

					qry("DELETE FROM profesores WHERE id = $id_profesor");

					$stmt = $conn->prepare("INSERT INTO profesores (id, id_detalles, fecha_registro) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("ii", $id_profesor, $id_detalles);
					$stmt-> execute();

				}else if($rol_usuario == 3){

					$id_estudiante = execute_scalar("SELECT estudiantes.id FROM estudiantes WHERE id_detalles = $id_detalles");

					qry("DELETE FROM estudiantes WHERE id = $id_estudiante");

					$stmt = $conn->prepare("INSERT INTO estudiantes (id, id_detalles, fecha_registro) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("ii", $id_estudiante, $id_detalles);
					$stmt-> execute();

				}

				$rol_nombre = execute_scalar("SELECT rol_tipo FROM roles WHERE id = $rol_usuario");

				$response = array(
					"response" => "success",
					"id_usuario" => $id_usuario,
					"nombre" => $nombre_usuario,
					"apellidos" => $apellidos_usuario,
					"email" => $correo_usuario,	
					"rol_nombre" => $rol_nombre,
					"nick_name" => $nick_usuario
				);

			}else{
				
				$response = array(
					"response" => "error",
					"error" => $error
				);
			}
		  
			$conn ->close();

		}else{

			$response = array(
				"response" => "repetido",
				"v_email" => $validate_email,
				"v_nick" => $validate_nick,
				"v_cedula" => $validate_cedula
			);

		}

	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "eliminar"){
	try {

		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE usuarios SET del = 1 WHERE id = ?");
		$stmt->bind_param("i", $id_usuario);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_usuario" => $id_usuario
			);
				
		}else{

			$response = array(
				"response" => "error"
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

}else if($_POST['action'] == "actualizar_password"){


	try {

		$id_usuario = $_POST['id_usuario'];
		$contra_usuario = $_POST['contra_usuario'];

		$passHash = password_hash($contra_usuario, PASSWORD_BCRYPT);

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
		$stmt->bind_param("si", $passHash, $id_usuario);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_usuario" => $id_usuario
			);
				
		}else{

			$response = array(
				"response" => "error"
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