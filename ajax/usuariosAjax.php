<?php


session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "select_ciudades_departamento") {

	try {

		$id_departamento = $_POST['id_departamento'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT * FROM ciudades WHERE id_departamento = ?");
		$stmt->bind_param("i", $id_departamento);
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
	   
	    $stmt->close();
	    $conn ->close();
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "insert"){

	try {

		$nombre_usuario = $_POST['nombre_usuario'];
		$apellidos_usuario = $_POST['apellidos_usuario'];
		$correo_usuario = $_POST['correo_usuario'];
		$cedula_usuario = $_POST['cedula_usuario'];
		$contacto_usuario = $_POST['contacto_usuario'];
		$contacto_usuario_insert = substr($contacto_usuario, 1,3).substr($contacto_usuario, 6,3).substr($contacto_usuario, 12,4);
		$dob_usuario = $_POST['dob_usuario'];
		$dob_usuario_insert = substr($dob_usuario, 6, 4).'-'.substr($dob_usuario, 0, 2).'-'.substr($dob_usuario, 3, 2);
		$cargo_usuario = $_POST['cargo_usuario'];
		$departamento_usuario = $_POST['departamento_usuario'];
		$ciudad_usuario = $_POST['ciudad_usuario'];

		$pass = "1234";    
		$passHash = password_hash($pass, PASSWORD_BCRYPT);

		$query = "INSERT INTO usuarios (nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto)VALUES ('$nombre_usuario', '$apellidos_usuario', '$cedula_usuario', '$correo_usuario', '$passHash', $cargo_usuario, $ciudad_usuario, '$dob_usuario_insert', '$contacto_usuario_insert')";

		//$reult = qry($query);

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssiiss", $nombre_usuario, $apellidos_usuario, $cedula_usuario, $correo_usuario, $passHash, $cargo_usuario, $ciudad_usuario, $dob_usuario_insert, $contacto_usuario_insert);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$id_insert = $stmt->insert_id;

			$cargo_descripcion = execute_scalar("SELECT descripcion_cargo FROM cargos WHERE id = $cargo_usuario");

			$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_usuario");

			$departamento_nombre = execute_scalar("SELECT departamento FROM ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE ciudades.id = $ciudad_usuario");

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
				"del" => 0
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

}else if($_POST['action'] == "select_usuarios"){

	try {

		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT usuarios.id AS id_usuario, nombre, apellidos, email, cedula, numero_contacto, id_cargo, id_ciudad, departamentos.id AS id_departamento, usuarios.fecha_nacimiento FROM usuarios LEFT JOIN departamentos ON usuarios.id_ciudad = departamentos.id WHERE usuarios.id = ?");
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

	    if ($response[0]['numero_contacto'] != '') {
	    	$response[0]['numero_contacto'] = '('.substr($response[0]['numero_contacto'], 0,3).') '.substr($response[0]['numero_contacto'], 3,3).'-'.substr($response[0]['numero_contacto'], 6,4);
	    }

	    if ($response[0]['fecha_nacimiento'] != '') {
	    	$response[0]['fecha_nacimiento'] = substr($response[0]['fecha_nacimiento'], 5,2).'/'.substr($response[0]['fecha_nacimiento'], 8,2).'/'.substr($response[0]['fecha_nacimiento'], 0,4);
	    }
	   
	    $stmt->close();
	    $conn ->close();
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "update"){
	try {

		$nombre_usuario = $_POST['nombre_usuario'];
		$apellidos_usuario = $_POST['apellidos_usuario'];
		$correo_usuario = $_POST['correo_usuario'];
		$cedula_usuario = $_POST['cedula_usuario'];
		$contacto_usuario = $_POST['contacto_usuario'];
		$contacto_usuario_insert = substr($contacto_usuario, 1,3).substr($contacto_usuario, 6,3).substr($contacto_usuario, 12,4);
		$dob_usuario = $_POST['dob_usuario'];
		$dob_usuario_insert = substr($dob_usuario, 6, 4).'-'.substr($dob_usuario, 0, 2).'-'.substr($dob_usuario, 3, 2);
		$cargo_usuario = $_POST['cargo_usuario'];
		$departamento_usuario = $_POST['departamento_usuario'];
		$ciudad_usuario = $_POST['ciudad_usuario'];

		$pass = "1234";    
		$passHash = password_hash($pass, PASSWORD_BCRYPT);

		$id_usuario = $_POST['id_usuario'];

		qry("DELETE FROM usuarios WHERE id = $id_usuario");

		$query = "INSERT INTO usuarios (id, nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto)VALUES ('$id_usuario', $nombre_usuario', '$apellidos_usuario', '$cedula_usuario', '$correo_usuario', '$passHash', $cargo_usuario, $ciudad_usuario, '$dob_usuario_insert', '$contacto_usuario_insert')";

		//$reult = qry($query);

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, apellidos, cedula, email, password, id_cargo, id_ciudad, fecha_nacimiento, numero_contacto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isssssiiss", $id_usuario, $nombre_usuario, $apellidos_usuario, $cedula_usuario, $correo_usuario, $passHash, $cargo_usuario, $ciudad_usuario, $dob_usuario_insert, $contacto_usuario_insert);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$id_insert = $stmt->insert_id;

			$cargo_descripcion = execute_scalar("SELECT descripcion_cargo FROM cargos WHERE id = $cargo_usuario");

			$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_usuario");

			$departamento_nombre = execute_scalar("SELECT departamento FROM ciudades LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE ciudades.id = $ciudad_usuario");

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
				"del" => 0
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
} 


?>