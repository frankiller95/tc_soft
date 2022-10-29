<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');



if ($_POST['action'] == "insert") {

	try {

		//var_dump($_POST);
		//die();

		$nombre_usuario = $_POST['nombre_usuario'];
		$apellidos_usuario = $_POST['apellidos_usuario'];
		if($_POST['correo_usuario'] != ''){
			$correo_usuario = $_POST['correo_usuario'];
		}else{
			$correo_usuario = '';
		}
		$cedula_usuario = $_POST['cedula_usuario'];
		$contacto_usuario = $_POST['contacto_usuario'];
		$contacto_usuario_insert = substr($contacto_usuario, 1, 3) . substr($contacto_usuario, 5, 3) . substr($contacto_usuario, 9, 4);
		$dob_usuario = '';
		

		if (isset($_POST['ciudad_usuario'])) {
			$ciudad_usuario = $_POST['ciudad_usuario'];	
		}else{
			$ciudad_usuario = 0;
		}

		if (isset($_POST['cargo_usuario'])) {
			$cargo_usuario = $_POST['cargo_usuario'];
		}else{
			$cargo_usuario = 0;
		}

		//$password = "1234";
		$password = substr($cedula_usuario, -4);
		$passHash = password_hash($password, PASSWORD_BCRYPT);

		$usuario_gane = 0;

		if (isset($_POST['usuario_gane'])) {
			$usuario_gane = $_POST['usuario_gane'];
		}

		$domiciliario = 0;

		if (isset($_POST['usuario_domi'])) {
			$domiciliario = $_POST['usuario_domi'];
		}

        $tropa = 0;

        if(isset($_POST['usuario_tropa'])){
            $tropa = $_POST['usuario_tropa'];
        }

		if($correo_usuario != ''){
			$validate_correo = execute_scalar("SELECT COUNT(id) AS total FROM usuarios WHERE email = '$correo_usuario' AND del = 0");
		}else{
			$validate_correo = 0;
		}
		

		$validate_cedula = execute_scalar("SELECT COUNT(id) AS total FROM usuarios WHERE cedula = '$cedula_usuario' AND del = 0");

		if ($validate_correo == 0) {

			if ($validate_cedula == 0) {

				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto, cliente_gane, domiciliario, usuario_tropa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param("sssssiissiii", $nombre_usuario, $apellidos_usuario, $cedula_usuario, $correo_usuario, $passHash, $cargo_usuario, $ciudad_usuario, $dob_usuario, $contacto_usuario_insert, $usuario_gane, $domiciliario, $tropa);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$id_insert = $stmt->insert_id;

					$error = $stmt->error;

					$cargo_descripcion = execute_scalar("SELECT descripcion_cargo FROM cargos WHERE id = $cargo_usuario");

					$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_usuario");

					$departamento_nombre = execute_scalar("SELECT departamento FROM ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE ciudades.id = $ciudad_usuario");

					$stmt->close();

					if (isset($_POST['permisos_usuario']) && is_array($_POST['permisos_usuario'])) {
						foreach ($_POST['permisos_usuario'] as $checkbox) {

							$stmt = $conn->prepare("insert into perfiles_usuarios (id_usuario, id_permiso) values(?,?)");
							$stmt->bind_param("ii", $id_insert, $checkbox);
							$stmt->execute();
							$stmt->close();
						}
					}

					if ($usuario_gane == 1) {

						$punto_gane = $_POST['punto_gane'];

						$stmt = $conn->prepare("INSERT INTO usuarios_puntos_gane (id_usuario, id_punto_gane, fecha_registro) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
						$stmt->bind_param("ii", $id_insert, $punto_gane);
						$stmt->execute();
						$stmt->close();
					}

					$response = array(
						"response" => "success",
						"id_insert" => $id_insert,
						"nombre" => $nombre_usuario,
						"apellidos" => $apellidos_usuario,
						"email" => $correo_usuario,
						"contacto" => $contacto_usuario,
						"cargo" => $cargo_descripcion,
						"ciudad" => $ciudad_nombre,
						"departamento" => $departamento_nombre,
						"del" => 0,
						"usuario_gane" => $usuario_gane,
						"cedula" => $cedula_usuario
					);
				} else {

					$response = array(
						"response" => "error",
						"error" => $error
					);
				}

				$conn->close();
			} else {

				$response = array(
					"response" => "cedula_repetida"
				);
			}
		} else {

			$response = array(
				"response" => "correo_repetido"
			);
		}
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "select_usuarios") {

	try {

		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT usuarios.id AS id_usuario, nombre, apellidos, email, cedula, numero_contacto, id_cargo, id_ciudad, id_departamento, usuarios.fecha_nacimiento, usuarios.cliente_gane, usuarios.domiciliario, usuarios.usuario_tropa, usuarios_puntos_gane.id_punto_gane FROM usuarios LEFT JOIN ciudades ON usuarios.id_ciudad = ciudades.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id WHERE usuarios.id = ?");
		$stmt->bind_param("i", $id_usuario);
		$stmt->execute();

		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}

		call_user_func_array(array($stmt, 'bind_result'), $params);

		while ($stmt->fetch()) {
			foreach ($row as $key => $val) {
				$c[$key] = $val;
			}
			$response[] = $c;
		}

		if ($response[0]['numero_contacto'] != '') {
			$response[0]['numero_contacto'] = '(' . substr($response[0]['numero_contacto'], 0, 3) . ')' . substr($response[0]['numero_contacto'], 3, 3) . '-' . substr($response[0]['numero_contacto'], 6, 4);
		}

		$stmt->close();
		$conn->close();

		$permisos_array = array();
		$query = "SELECT id_permiso FROM perfiles_usuarios WHERE id_usuario = $id_usuario";
		$result = qry($query);

		while ($row = mysqli_fetch_array($result)) {
			$id_permiso = $row['id_permiso'];
			$new_array = array("id_permiso" => $id_permiso);
			array_push($permisos_array, $new_array);
		}

		array_push($response, $permisos_array);

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "update") {

	try {

		$nombre_usuario = $_POST['nombre_usuario'];
		$apellidos_usuario = $_POST['apellidos_usuario'];
		if($_POST['correo_usuario'] != ''){
			$correo_usuario = $_POST['correo_usuario'];
		}else{
			$correo_usuario = '';
		}
		$cedula_usuario = $_POST['cedula_usuario'];
		$contacto_usuario = $_POST['contacto_usuario'];
		$contacto_usuario_insert = substr($contacto_usuario, 1, 3) . substr($contacto_usuario, 5, 3) . substr($contacto_usuario, 9, 4);
		$dob_usuario = '';
		

		if (isset($_POST['ciudad_usuario'])) {
			$ciudad_usuario = $_POST['ciudad_usuario'];	
		}else{
			$ciudad_usuario = 0;
		}

		if (isset($_POST['cargo_usuario'])) {
			$cargo_usuario = $_POST['cargo_usuario'];
		}else{
			$cargo_usuario = 0;
		}


		$id_usuario = $_POST['id_usuario'];
		$id_usuario_index = $_POST['id_usuario_index'];

		if($correo_usuario != ''){
			$validate_correo = execute_scalar("SELECT COUNT(id) AS total FROM usuarios WHERE email = '$correo_usuario' AND id <> $id_usuario AND del = 0");
		}else{
			$validate_correo = 0;
		}

		$validate_cedula = execute_scalar("SELECT COUNT(id) AS total FROM usuarios WHERE cedula = '$cedula_usuario' AND id <> $id_usuario AND del = 0");

		if($validate_correo != 0){

			$response = array(
				"response" => "correo_repetido"
			);
			echo json_encode($response);
			die();

		}

		if($validate_cedula != 0){
			$response = array(
				"response" => "cedula_repetida"
			);

			echo json_encode($response);

			die();
		}

		$passHash = execute_scalar("SELECT password FROM usuarios WHERE id = $id_usuario");

		if (isset($_POST['usuario_gane'])) {
			$usuario_gane = $_POST['usuario_gane'];
		} else {
			$usuario_gane = 0;
		}

		$domiciliario = 0;

		if (isset($_POST['usuario_domi'])) {
			$domiciliario = $_POST['usuario_domi'];
		}

        $tropa = 0;

        if(isset($_POST['usuario_tropa'])){
            $tropa = $_POST['usuario_tropa'];
        }

        qry("DELETE FROM usuarios WHERE id = $id_usuario");

		//$query = "INSERT INTO usuarios (id, nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto)VALUES ('$id_usuario', $nombre_usuario', '$apellidos_usuario', '$cedula_usuario', '$correo_usuario', '$passHash', $cargo_usuario, $ciudad_usuario, '$dob_usuario_insert', '$contacto_usuario_insert')";

		//$reult = qry($query);

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto, cliente_gane, domiciliario, usuario_tropa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isssssiissiii", $id_usuario, $nombre_usuario, $apellidos_usuario, $cedula_usuario, $correo_usuario, $passHash, $cargo_usuario, $ciudad_usuario, $dob_usuario, $contacto_usuario_insert, $usuario_gane, $domiciliario, $tropa);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			//$id_insert = $stmt->insert_id;

			$cargo_descripcion = execute_scalar("SELECT descripcion_cargo FROM cargos WHERE id = $cargo_usuario");

			$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_usuario");

			$departamento_nombre = execute_scalar("SELECT departamento FROM ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE ciudades.id = $ciudad_usuario");

			qry("DELETE FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario AND del = 0");

			if ($usuario_gane == 1) {

				$id_punto_gane = $_POST['punto_gane'];

				$stmt = $conn->prepare("INSERT INTO usuarios_puntos_gane (id_usuario, id_punto_gane, fecha_registro) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("ii", $id_usuario, $id_punto_gane);
				$stmt->execute();
				$stmt->close();

			}

			$response = array(
				"response" => "success",
				"id_insert" => $id_usuario,
				"nombre" => $nombre_usuario,
				"apellidos" => $apellidos_usuario,
				"email" => $correo_usuario,
				"contacto" => $contacto_usuario,
				"cargo" => $cargo_descripcion,
				"ciudad" => $ciudad_nombre,
				"departamento" => $departamento_nombre,
				"del" => 0,
				"usuario_gane" => $usuario_gane,
				"cedula" => $cedula_usuario,
				"id_usuario_index" => $id_usuario_index
			);

			//$stmt->close();

			if (isset($_POST['permisos_usuario']) && is_array($_POST['permisos_usuario'])) {
				qry("DELETE FROM perfiles_usuarios WHERE id_usuario = $id_usuario");
				foreach ($_POST['permisos_usuario'] as $checkbox) {

					$stmt = $conn->prepare("insert into perfiles_usuarios (id_usuario, id_permiso) values(?,?)");
					$stmt->bind_param("ii", $id_usuario, $checkbox);
					$stmt->execute();
					$stmt->close();
				}
			}
		} else {

			$response = array(
				"response" => "error",
				"error" => $stmt->error
			);
		}

		$conn->close();

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "eliminar") {
	try {

		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE usuarios SET del = 1 WHERE id = ?");
		$stmt->bind_param("i", $id_usuario);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$validate_punto_gane = execute_scalar("SELECT COUNT(id) AS total FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario AND del = 0");

			if($validate_punto_gane != 0){
				qry("UPDATE usuarios_puntos_gane SET del = b'1' WHERE id_usuario = $id_usuario AND del = 0");
			}

			$response = array(
				"response" => "success",
				"id_usuario" => $id_usuario
			);
		} else {

			$response = array(
				"response" => "error"
			);
		}

		$stmt->close();
		$conn->close();
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "edit_password") {


	try {

		$id_usuario = $_POST['id_usuario'];
		$contra_usuario = $_POST['contra_usuario'];

		$passHash = password_hash($contra_usuario, PASSWORD_BCRYPT);

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
		$stmt->bind_param("si", $passHash, $id_usuario);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_usuario" => $id_usuario
			);
		} else {

			$response = array(
				"response" => "error"
			);
		}

		$stmt->close();
		$conn->close();
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "add_punto_gane") {

	try {

		$nombre_punto = $_POST['nombre_punto'];
		$direccion_punto = $_POST['direccion_punto'];
		$contacto_punto = $_POST['contacto_punto'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO puntos_gane (nombre_punto, direccion_punto, contacto_punto, fecha_registro) VALUES (?, ? , ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
		$stmt->bind_param("sss", $nombre_punto, $direccion_punto, $contacto_punto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$id_punto_gane = $stmt->insert_id;

			$response = array(
				"response" => "success",
				"id_punto_gane" => $id_punto_gane,
				"nombre_punto" => $nombre_punto,
				"direccion_punto" => $direccion_punto
			);
		} else {

			$response = array(
				"response" => "error"
			);
		}

		$stmt->close();
		$conn->close();
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_extends"){

	try {
		//code...
	} catch (Exception $e) {
		
	}

}
