<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action'] == "select_imagenes") {

	try {

		$id_cliente = $_POST['id_cliente'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT * FROM imagenes_clientes WHERE id_cliente = ? AND del = 0");
		$stmt->bind_param("i", $id_cliente);
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

}else if($_POST['action']=="eliminar_imagenes"){

	try {

		$id_cliente = $_POST['id_cliente'];

		$query = "UPDATE imagenes_clientes SET del = 1 WHERE id_cliente = $id_cliente";
		$result = qry($query);
		if($result){

			$query2 = "SELECT clientes.id AS id_cliente, clientes.cliente_cedula, CONCAT(cliente_nombre, ' ', cliente_apellidos) AS full_cliente, cliente_numero_contacto, cliente_email, CONCAT(cliente_direccion, ', ',ciudades.ciudad) AS ubicacion FROM clientes LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id WHERE clientes.id = $id_cliente";
			$result2 = qry($query2);
			while ($row1 = mysqli_fetch_array($result2)) {
				$id_cliente = $row1['id_cliente'];
				$cliente_cedula = $row1['cliente_cedula'];
				$full_cliente = $row1['full_cliente'];
				$contacto = $row1['cliente_numero_contacto'];
				$contacto = '('.substr($contacto, 0,3).') '.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
				$email = $row1['cliente_email'];
				$ubicacion = $row1['ubicacion'];
			}

			$route1 = '../documents/clients/'.$id_cliente;
			rmDir_rf($route1); //eliminamos todas las imagenes del cliente

			$response = array(
				"response" => "success",
				"id_cliente" => $id_cliente,
				"cliente_cedula" => $cliente_cedula,
				"full_cliente" => $full_cliente,
				"contacto" => $contacto,
				"email" => $email,
				"ubicacion" => $ubicacion
			);
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

}else if($_POST['action'] == "insertar_cliente"){

	try {

			$cedula_cliente = $_POST['cedula_cliente'];
	 		$nombre_cliente = $_POST['nombre_cliente'];
	 		$apellidos_cliente = $_POST['apellidos_cliente'];
	 		$contacto_cliente = $_POST['contacto_cliente'];
	 		$contacto_cliente = substr($contacto_cliente, 1,3).substr($contacto_cliente, 5,3).substr($contacto_cliente, 9,4);
	 		$direccion_cliente = $_POST['direccion_cliente'];
	 		$dob_cliente = $_POST['dob_cliente'];
	 		$ciudad_cliente = $_POST['ciudad_cliente'];
	 		$email_cliente = $_POST['email_cliente'];
	 		$sexo_cliente = $_POST['sexo_cliente'];
	 		$id_usuario = $_POST['id_usuario'];
	 		$fecha_exp = $_POST['fecha_exp'];
	 		$ciudad_exp = $_POST['ciudad_exp'];

	 		$id_usuario = $_POST['id_usuario'];

	 		$validate = execute_scalar("SELECT COUNT(id) AS total FROM clientes WHERE cliente_cedula = $cedula_cliente AND clientes.del = 0");

	 		if($validate == 0){

		 		$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO clientes (cliente_cedula, id_usuario) VALUES (?, ?)");
				$stmt->bind_param("si", $cedula_cliente, $id_usuario);
				$stmt-> execute();

				if ($stmt->affected_rows == 1) {

					$id_cliente = $stmt->insert_id;

					$stmt->close();
					$stmt = $conn->prepare("INSERT INTO cliente_detalles (id_cliente, cliente_nombre, cliente_apellidos, cliente_numero_contacto, cliente_email, cliente_sexo, cliente_dob, cliente_direccion, ciudad_id, fecha_exp, id_ciudad_exp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("isssssssisi", $id_cliente, $nombre_cliente, $apellidos_cliente, $contacto_cliente, $email_cliente, $sexo_cliente, $dob_cliente, $direccion_cliente, $ciudad_cliente, $fecha_exp, $ciudad_exp);
					$stmt-> execute();

					if ($stmt->affected_rows == 1) {

						$query2 = "SELECT clientes.id AS id_cliente, clientes.cliente_cedula, CONCAT(cliente_nombre, ' ', cliente_apellidos) AS full_cliente, cliente_numero_contacto, cliente_email, CONCAT(cliente_direccion, ', ',ciudades.ciudad) AS ubicacion FROM clientes LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id WHERE clientes.id = $id_cliente";
						$result2 = qry($query2);
						while ($row1 = mysqli_fetch_array($result2)) {
							$id_cliente = $row1['id_cliente'];
							$cliente_cedula = $row1['cliente_cedula'];
							$full_cliente = $row1['full_cliente'];
							$contacto = $row1['cliente_numero_contacto'];
							$contacto = '('.substr($contacto, 0,3).') '.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
							$email = $row1['cliente_email'];
							$ubicacion = $row1['ubicacion'];
						}

						$response = array(
							"response" => "success",
							"id_cliente" => $id_cliente,
							"cliente_cedula" => $cliente_cedula,
							"full_cliente" => $full_cliente,
							"contacto" => $contacto,
							"email" => $email,
							"ubicacion" => $ubicacion
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

}else if($_POST['action'] == "select_cliente"){


	try {
		
		$id_cliente = $_POST['id_cliente'];


		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT clientes.id AS id_cliente, clientes.cliente_cedula, cliente_nombre, cliente_apellidos, cliente_numero_contacto, cliente_email, cliente_direccion, cliente_sexo, cliente_dob, cliente_detalles.ciudad_id, departamentos.id AS id_departamento, fecha_exp, id_ciudad_exp FROM clientes LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE clientes.id = ?");
		$stmt->bind_param("i", $id_cliente);
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

	    if ($response[0]['cliente_numero_contacto'] != '') {
	    	$response[0]['cliente_numero_contacto'] = '('.substr($response[0]['cliente_numero_contacto'], 0,3).')'.substr($response[0]['cliente_numero_contacto'], 3,3).'-'.substr($response[0]['cliente_numero_contacto'], 6,4);
	    }
	   
	    $stmt->close();
	    $conn ->close();

	    $img_array = array();
	    $query = "SELECT imagen_nombre_archivo, imagen_extension FROM imagenes_clientes WHERE id_cliente = $id_cliente";
	    $result = qry($query);
	    while ($row = mysqli_fetch_array($result)) {
	    	$imagen_nombre_archivo = $row['imagen_nombre_archivo'];
	    	$imagen_extension = $row['imagen_extension'];

	    	$newArray = array("img" => $imagen_nombre_archivo, "ext" => $imagen_extension);
	    	array_push($img_array, $newArray);
	    }


	    array_push($response, $img_array);

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "update_cliente"){

	try {

			$cedula_cliente = $_POST['cedula_cliente'];
	 		$nombre_cliente = $_POST['nombre_cliente'];
	 		$apellidos_cliente = $_POST['apellidos_cliente'];
	 		$contacto_cliente = $_POST['contacto_cliente'];
	 		$contacto_cliente = substr($contacto_cliente, 1,3).substr($contacto_cliente,5,3).substr($contacto_cliente, 9,4);
	 		$direccion_cliente = $_POST['direccion_cliente'];
	 		$dob_cliente = $_POST['dob_cliente'];
	 		$ciudad_cliente = $_POST['ciudad_cliente'];
	 		$email_cliente = $_POST['email_cliente'];
	 		$sexo_cliente = $_POST['sexo_cliente'];
	 		$id_usuario = $_POST['id_usuario'];
	 		$fecha_exp = $_POST['fecha_exp'];
	 		$ciudad_exp = $_POST['ciudad_exp'];
	 		$id_cliente = $_POST['id_cliente'];

	 		$validate = execute_scalar("SELECT COUNT(id) AS total FROM clientes WHERE cliente_cedula = $cedula_cliente AND clientes.id <> $id_cliente");

	 		if($validate == 0){

	 			qry("DELETE FROM clientes WHERE id = $id_cliente");

		 		$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO clientes (id, cliente_cedula, id_usuario) VALUES (?, ?, ?)");
				$stmt->bind_param("isi", $id_cliente, $cedula_cliente, $id_usuario);
				$stmt-> execute();

				if ($stmt->affected_rows == 1) {

					$stmt->close();

					qry("DELETE FROM cliente_detalles WHERE id_cliente = $id_cliente");

					$stmt = $conn->prepare("INSERT INTO cliente_detalles (id, id_cliente, cliente_nombre, cliente_apellidos, cliente_numero_contacto, cliente_email, cliente_sexo, cliente_dob, cliente_direccion, ciudad_id, fecha_exp, id_ciudad_exp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("iisssssssisi", $id, $id_cliente, $nombre_cliente, $apellidos_cliente, $contacto_cliente, $email_cliente, $sexo_cliente, $dob_cliente, $direccion_cliente, $ciudad_cliente, $fecha_exp, $ciudad_exp);
					$stmt-> execute();

					if ($stmt->affected_rows == 1) {

						$query2 = "SELECT clientes.id AS id_cliente, clientes.cliente_cedula, CONCAT(cliente_nombre, ' ', cliente_apellidos) AS full_cliente, cliente_numero_contacto, cliente_email, CONCAT(cliente_direccion, ', ',ciudades.ciudad) AS ubicacion FROM clientes LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id WHERE clientes.id = $id_cliente";
						$result2 = qry($query2);
						while ($row1 = mysqli_fetch_array($result2)) {
							$id_cliente = $row1['id_cliente'];
							$cliente_cedula = $row1['cliente_cedula'];
							$full_cliente = $row1['full_cliente'];
							$contacto = $row1['cliente_numero_contacto'];
							$contacto = '('.substr($contacto, 0,3).') '.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
							$email = $row1['cliente_email'];
							$ubicacion = $row1['ubicacion'];
						}

						$response = array(
							"response" => "success",
							"id_cliente" => $id_cliente,
							"cliente_cedula" => $cliente_cedula,
							"full_cliente" => $full_cliente,
							"contacto" => $contacto,
							"email" => $email,
							"ubicacion" => $ubicacion
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

}else if($_POST['action'] == "eliminar_cliente"){

	try {

		$id_cliente = $_POST['id_cliente'];

		if(isset($_POST['from'])){

			$query = "DELETE FROM imagenes_clientes WHERE id_cliente = $id_cliente";
			$result = qry($query);
			if ($result) {
				$query2 = "DELETE FROM cliente_detalles WHERE id_cliente = $id_cliente";
				$result2 = qry($query2);
				if($result2){
					$query3 = "DELETE FROM clientes WHERE id = $id_cliente";
					$result3 = qry($query3);
					if($result3){

						$response = array(
							"response" => "success",
							"id_cliente" => $id_cliente
						);

					}else{
						$response = array(
							"response" => "error"
						);
					}
				}else{
					$response = array(
						"response" => "error"
					);
				}
			}else{
				$response = array(
					"response" => "error"
				);
			}

		}else{

			$validation = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE id_cliente = $id_cliente AND id_estado_solicitud <> 8 AND del = 0");

			if ($validation == 0) {

				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("UPDATE clientes SET del = 1 WHERE id = ?");
				$stmt->bind_param("i", $id_cliente);
				$stmt-> execute();

				if ($stmt->affected_rows == 1) {

					$stmt->close();
					$stmt = $conn->prepare("UPDATE cliente_detalles SET del = 1 WHERE id_cliente = ?");
					$stmt->bind_param("i", $id_cliente);
					$stmt-> execute();

					if ($stmt->affected_rows == 1) {

						$imagenes = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_clientes WHERE id_cliente = $id_cliente AND imagenes_clientes.del = 0");

						if ($imagenes == 3) {
							
							$route1 = '../documents/clients/'.$id_cliente;
							rmDir_rf($route1); //eliminamos todas las imagenes del cliente

						}

						$response = array(
							"response" => "success",
							"id_cliente" => $id_cliente
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

				$response = array(
					"response" => "error_solicitudes",
					"id_cliente" => $id_cliente
				);
			}

		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "upload_imagenes"){

	try {

		$id_cliente = $_POST['id_cliente'];

		$countfiles = count($_FILES['file']['name']);

		if ($countfiles == 3) {

			$route = '../documents/clients/'.$id_cliente.'/';
			$route1 = '../documents/clients/'.$id_cliente;

			qry("DELETE FROM imagenes_clientes WHERE id_cliente = $id_cliente");

			if(!file_exists($route)){

				mkdir($route, 0777, true);

			}else{

				rmDir_rf($route1);
				mkdir($route, 0777, true);
			}

			$extension = '';
			
			for($i = 0; $i < $countfiles; $i++){

				$filename = $id_cliente.'-'.$i;

				if ($_FILES['file']['type'][$i] == "image/jpeg") {

					$extension = "jpg";

				}else if($_FILES['file']['type'][$i] == "image/png"){

					$extension = "png";

				}else{

					$response = array(
						"response" => "tipo_incorrecto"
					);

					qry("DELETE FROM imagenes_clientes WHERE id_cliente = $id_cliente");

					rmDir_rf($route1);

					die();
				}

				move_uploaded_file($_FILES['file']['tmp_name'][$i], $route.$filename.'.'.$extension);

				qry("INSERT INTO imagenes_clientes (id_cliente, imagen_nombre_archivo, imagen_extension) VALUES ($id_cliente, '$filename', '$extension')");

			}

			$validate = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_clientes WHERE id_cliente = $id_cliente");

			if ($validate == 3) {

				$query2 = "SELECT clientes.id AS id_cliente, clientes.cliente_cedula, CONCAT(cliente_nombre, ' ', cliente_apellidos) AS full_cliente, cliente_numero_contacto, cliente_email, CONCAT(cliente_direccion, ', ',ciudades.ciudad) AS ubicacion FROM clientes LEFT JOIN cliente_detalles ON cliente_detalles.id_cliente = clientes.id LEFT JOIN ciudades ON cliente_detalles.ciudad_id = ciudades.id WHERE clientes.id = $id_cliente";
				$result2 = qry($query2);
				while ($row1 = mysqli_fetch_array($result2)) {
					$id_cliente = $row1['id_cliente'];
					$cliente_cedula = $row1['cliente_cedula'];
					$full_cliente = $row1['full_cliente'];
					$contacto = $row1['cliente_numero_contacto'];
					$contacto = '('.substr($contacto, 0,3).') '.substr($contacto, 3,3).'-'.substr($contacto, 6,4);
					$email = $row1['cliente_email'];
					$ubicacion = $row1['ubicacion'];

					$imagenes = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_clientes WHERE id_cliente = $id_cliente AND imagenes_clientes.del = 0");
				}

				$response = array(
					"response" => "success",
					"id_cliente" => $id_cliente,
					"cliente_cedula" => $cliente_cedula,
					"full_cliente" => $full_cliente,
					"contacto" => $contacto,
					"email" => $email,
					"ubicacion" => $ubicacion,
					"imagenes" => $imagenes
				);

				//array_push($response, $arrayFiles);

			}else{

				$response = array(
					"response" => "error"
				);
			}
			
		}else{

			$response = array(
				"response" => "3_img"
			);

		}

		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);


}else if($_POST['action']=="cambiar_imagen"){

	try {

		//var_dump($_FILES);
		//die();

		$id_imagen = $_POST['id_img'];

		$id_cliente = execute_scalar("SELECT id_cliente FROM imagenes_clientes WHERE id = $id_imagen");

		$file_name = execute_scalar("SELECT imagen_nombre_archivo FROM imagenes_clientes WHERE id = $id_imagen");

		$ext = execute_scalar("SELECT imagen_extension FROM imagenes_clientes WHERE id = $id_imagen");

		$route = '../documents/clients/'.$id_cliente.'/';

		if(move_uploaded_file($_FILES['file2']['tmp_name'], $route.$file_name.'.'.$ext)){

			$response = array(
				"response" => "success"
			);

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
}


?>