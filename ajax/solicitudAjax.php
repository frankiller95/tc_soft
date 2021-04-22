<?php


session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "insertar_img_cliente") {

	try {

		
		$countfiles = count($_FILES['file']['name']);

		if ($countfiles == 3) {

			$id_cliente = execute_scalar("SELECT MAX(id) AS id_cliente FROM clientes");
			$id_cliente = $id_cliente + 1;

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

			$arrayFiles = array();

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

				$newArray = array("filename" => $filename, "extension" => $extension);

				array_push($arrayFiles, $newArray);

			}

			$validate = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_clientes WHERE id_cliente = $id_cliente");

			if ($validate == 3) {

				$response = array(
					"response" => "success",
					"id_cliente" => $id_cliente
				);

				array_push($response, $arrayFiles);

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

}else if($_POST['action'] == "insertar_cliente"){

	try {

		$cedula_cliente = $_POST['cedula_cliente'];
 		$nombre_cliente = $_POST['nombre_cliente'];
 		$apellidos_cliente = $_POST['apellidos_cliente'];
 		$contacto_cliente = $_POST['contacto_cliente'];
 		$contacto_cliente = substr($contacto_cliente, 1,3).substr($contacto_cliente, 6,3).substr($contacto_cliente, 12,4);
 		$direccion_cliente = $_POST['direccion_cliente'];
 		$dob_cliente = $_POST['dob_cliente'];
 		$dob_cliente = substr($dob_cliente, 6, 4).'-'.substr($dob_cliente, 0, 2).'-'.substr($dob_cliente, 3, 2);
 		$departamento_cliente = $_POST['departamento_cliente'];
 		$ciudad_cliente = $_POST['ciudad_cliente'];
 		$email_cliente = $_POST['email_cliente'];
 		$sexo_cliente = $_POST['sexo_cliente'];
 		$id_cliente = $_POST['id_cliente'];
 		$id_usuario = $_POST['id_usuario'];

 		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO clientes (id, cliente_cedula, id_usuario) VALUES (?, ?)");
		$stmt->bind_param("isi", $id_cliente, $cedula_cliente, $id_usuario);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO cliente_detalles (id_cliente, cliente_nombre, cliente_apellidos, cliente_numero_contacto, cliente_dob, cliente_direccion, ciudad_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("isssssi", $id_cliente, $nombre_cliente, $apellidos_cliente, $contacto_cliente, $dob_cliente, $direccion_cliente, $ciudad_cliente);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array(
					"response" => "success",
					"id_cliente" => $id_cliente,
					"nombre_cliente" => $nombre_cliente,
					"apellido_cliente" => $apellidos_cliente,
					"cedula_cliente" => $cedula_cliente
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

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action']=="creada"){

	try {

		$id_cliente = $_POST['id_cliente'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO solicitudes (id_cliente, id_producto, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, inicial, id_estado_solicitud, fecha_inicio_credito) VALUES (?, 0, 0, 0, 0, 0, 9, '0000-00-00 00:00:00')");
		$stmt->bind_param("i", $id_cliente);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_solicitud" => $stmt->insert_id
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

}else if($_POST['action'] == "select_precio_producto"){

	try {

		$id_producto = $_POST['id_producto'];

		$precio_producto = execute_scalar("SELECT precio_venta FROM inventario WHERE id_producto = $id_producto");

		$terminosProductosArray = array();

		$query = "SELECT id_termino, numero_meses FROM terminos_productos LEFT JOIN terminos_prestamos ON terminos_productos.id_termino = terminos_prestamos.id WHERE id_producto = $id_producto";

		$result = qry($query);

		while($row = mysqli_fetch_array($result)) {

			$id_termino = $row['id_termino'];
			$numero_meses = $row['numero_meses'];

			$newArray = array("id_termino" => $id_termino, "numero_meses" => $numero_meses);

			array_push($terminosProductosArray, $newArray);
		}

		$frecuenciasProductosArray = array();

		$query2 = "SELECT id_frecuencia_pago, frecuencia FROM frecuencias_productos LEFT JOIN frecuencias_pagos ON frecuencias_productos.id_frecuencia_pago = frecuencias_pagos.id WHERE id_producto = $id_producto";

		$result2 = qry($query2);

		while($row2 = mysqli_fetch_array($result2)) {

			$id_frecuencia_pago = $row2['id_frecuencia_pago'];
			$frecuencia = $row2['frecuencia'];

			$newArray = array("id_frecuencia_pago" => $id_frecuencia_pago, "frecuencia" => $frecuencia);

			array_push($frecuenciasProductosArray, $newArray);
		}

		$inicialesProductosArray = array();

		$query = "SELECT id_porcentaje, porcentaje FROM porcentajesini_productos LEFT JOIN porcentajes_iniciales ON porcentajesini_productos.id_porcentaje = porcentajes_iniciales.id WHERE id_producto = $id_producto";

		$result = qry($query);

		while($row = mysqli_fetch_array($result)) {

			$id_porcentaje = $row['id_porcentaje'];
			$porcentaje = $row['porcentaje'];
			$valor_porcentaje = ($porcentaje * $precio_producto)/100;

			$newArray = array("id_porcentaje" => $id_porcentaje, "porcentaje" => $porcentaje, "valor_porcentaje" => number_format($valor_porcentaje));

			array_push($inicialesProductosArray, $newArray);
		}


		$response = array(
			"response" => "success",
			"precio_producto" => $precio_producto
		);

		array_push($response, $terminosProductosArray);
		array_push($response, $frecuenciasProductosArray);
		array_push($response, $inicialesProductosArray);
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);


	}

	echo json_encode($response);

}else if($_POST['action'] == "inicial"){

	try {

		$solicitud_marca = $_POST['solicitud_marca'];
		$solicitud_producto = $_POST['solicitud_producto'];
		$precio_producto = $_POST['precio_producto'];
		$id_termino_prestamo = $_POST['termino_prestamo'];
		$id_frecuencia_pago = $_POST['frecuencia_pago'];
		$id_porcentaje_inicial = $_POST['porcentaje_inicial'];
		$id_solicitud = $_POST['id_solicitud'];

		$porcentaje_inicial = execute_scalar("SELECT porcentaje FROM porcentajes_iniciales WHERE id = $id_porcentaje_inicial");

		$inicial = ($porcentaje_inicial*$precio_producto)/100;

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE solicitudes SET id_producto = ?, id_terminos_prestamo = ?, id_frecuencia_pago = ?, id_porcentaje_inicial = ?, inicial = ?, id_estado_solicitud = 1 WHERE id = ?");
		$stmt->bind_param("iiiiii", $solicitud_producto, $id_termino_prestamo, $id_frecuencia_pago, $id_porcentaje_inicial, $inicial, $id_solicitud);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_solicitud" => $stmt->insert_id
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