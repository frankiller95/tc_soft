<?php


//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if($_POST['action']=="crear_solicitud"){

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$validate_solicitudes = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE solicitudes.id_prospecto = $id_prospecto AND solicitudes.del = 0 AND solicitudes.id_estado_solicitud <> 7 AND solicitudes.id_estado_solicitud <> 8");

		if($validate_solicitudes == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO solicitudes (id_prospecto, id_existencia, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, precio_producto, id_estado_solicitud, fecha_inicio_credito, id_usuario) VALUES (?, 0, 0, 0, 0, 0, 1, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), ?)");
			$stmt->bind_param("ii", $id_prospecto, $id_usuario);
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

		}else{

			$response = array(

				"response" => "prospecto_solicitud_activa"

			);

		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "select_precio_producto"){

	try {

		$id_existencia = $_POST['id_existencia'];

		$id_solicitud = $_POST['id_solicitud'];

		$id_inventario = execute_scalar("SELECT id_inventario FROM productos_stock WHERE id = $id_existencia");

		$id_producto = execute_scalar("SELECT id_producto FROM inventario WHERE id = $id_inventario");

		$id_modelo = execute_scalar("SELECT id_modelo FROM productos WHERE id = $id_producto");

		$precio_producto = execute_scalar("SELECT precio_venta FROM modelos WHERE id = $id_modelo");

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

			$query = "SELECT id AS id_porcentaje, porcentaje FROM porcentajes_iniciales WHERE del = 0";

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
				"precio_producto" => str_replace(',', '.', number_format($precio_producto)),
				"id_producto" => $id_producto
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


}else if($_POST['action'] == "validate"){

	try {

		if ($_POST['id_prospecto'] != '') {

			$id_prospecto = $_POST['id_prospecto'];
			
		}else{

			$id_prospecto = execute_scalar("SELECT MAX(id) AS id_prospecto FROM prospectos");
			$id_prospecto = $id_prospecto + 1;

		}

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto");

		$response = array(
			"validate" => $validate,
			"id_prospecto" => $id_prospecto
		);
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);


}else if($_POST['action'] == "eliminar_imagenes"){

	try {

		$id_prospecto = execute_scalar("SELECT MAX(id) AS id_prospecto FROM prospectos");
		$id_prospecto = $id_prospecto + 1;

		$route = '../documents/clients/'.$id_prospecto.'/';
		$route1 = '../documents/clients/'.$id_prospecto;

		qry("DELETE FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto");

		if(file_exists($route)){

			rmDir_rf($route1);

		}
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

}else if($_POST['action'] == "subir_img2"){
	try {

		$id_prospecto = $_POST['id_prospecto'];

		$route = '../documents/clients/'.$id_prospecto.'/';
		$route1 = '../documents/clients/'.$id_prospecto;

		$from = $_POST['from'];

		$img_number = 0; 

		if(!file_exists($route)){

			mkdir($route, 0777, true);

		}

		$extension = '';

		if ($from == "atras") {
			$img_number = 1;
		}else if($from == "selfie"){
			$img_number = 2;
		}

		$filename = $id_prospecto.'-'.$img_number;

		if ($_FILES['file2']['type'] == "image/jpeg") {

			$extension = "jpg";

		}else if($_FILES['file2']['type'] == "image/png"){

			$extension = "png";

		}else{

			$response = array(
				"response" => "tipo_incorrecto"
			);

			echo json_encode($response);

			die();
		}

		move_uploaded_file($_FILES['file2']['tmp_name'], $route.$filename.'.'.$extension);

		$query = "INSERT INTO imagenes_prospectos (id_prospecto, imagen_nombre_archivo, tipo_img, imagen_extension) VALUES ($id_prospecto, '$filename', '$from', '$extension')";

		$result = qry($query);

		$validate_image = 0;

		if ($result) {
			$validate_image = 1;
		}

		$validate = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagen_nombre_archivo = '$filename'");

		$total_imagenes = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto");

		if ($validate == 1) {

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto,
				"from" => $from,
				"filename" => $filename,
				"extension" => $extension,
				"number" => $img_number,
				"validate_image" => $validate_image,
				"total_imagenes" => $total_imagenes
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

}else if($_POST['action'] == "validar_cedula"){

	try {

		$cedula = $_POST['cedula'];

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE prospecto_cedula = $cedula");

		if($validate == 0){
			$respuesta = "si";
		}else{
			$respuesta = "repeat";
		}

		echo $respuesta;
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

		echo json_encode($response);
		
	}

}else if($_POST['action'] == "actualizar"){

	try {

		$id_existencia = $_POST['solicitud_producto'];
		$precio = str_replace('.', '', $_POST['precio_producto']);
		$termino = $_POST['termino_prestamo'];
		$frecuencia = $_POST['frecuencia_pago'];
		$porcentaje_inicial = $_POST['porcentaje_inicial'];
		$inicial = execute_scalar("SELECT id FROM porcentajes_iniciales WHERE porcentaje = $porcentaje_inicial");
		$id_usuario = $_POST['id_usuario'];
		$id_solicitud = $_POST['id_solicitud'];
		$id_prospecto = $_POST['id_prospecto'];

		//$id_inventario = execute_scalar("SELECT id_inventario FROM productos_stock WHERE id = $id_existencia");

		//$id_producto = execute_scalar("SELECT id_producto FROM inventario WHERE id = $id_inventario");

		$id_estado_solicitud = execute_scalar("SELECT id_estado_solicitud FROM solicitudes WHERE id = $id_solicitud");

		//echo $id_estado_solicitud;
		//die();

		$id_existencia_old = execute_scalar("SELECT id_existencia FROM solicitudes WHERE id = $id_solicitud");

		qry("DELETE FROM solicitudes WHERE id = $id_solicitud");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO solicitudes (id, id_prospecto, id_existencia, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, precio_producto, id_estado_solicitud, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("iiiiiiiii", $id_solicitud, $id_prospecto, $id_existencia, $termino, $frecuencia, $inicial, $precio, $id_estado_solicitud, $id_usuario);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			if ($id_existencia_old != $id_existencia) {

				qry("UPDATE productos_stock SET id_estado_producto = 1 WHERE id = $id_existencia_old");
				
			}

			qry("UPDATE productos_stock SET id_estado_producto = 2 WHERE id = $id_existencia");

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
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

}else if($_POST['action'] == "insert_existencia"){

	try {

		$id_modelo = $_POST['dispositivos'];
		$imei_1 = $_POST['imei_1'];
		$imei_2 = $_POST['imei_2'];
		$color_producto = $_POST['color_producto'];
		$proveedor_producto = $_POST['proveedor_producto'];

		$validate_inventario = execute_scalar("SELECT COUNT(id) AS total FROM productos WHERE id_modelo = $id_modelo AND del = 0");

		$id_inventario = 0;

		if($validate_inventario == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO productos (id_marca, id_modelo, id_capacidad) VALUES (0, ?, 0)");
			$stmt->bind_param("i", $id_modelo);
			$stmt-> execute();

			if($stmt->affected_rows == 1){

				$id_producto = $stmt->insert_id;

				$stmt->close();

				$stmt = $conn->prepare("INSERT INTO inventario (id_producto, costo, precio_venta, fecha_registro) VALUES (?, 0, 0, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("i", $id_producto);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$id_inventario = $stmt->insert_id;

					$resultado_c = 3; //Rechazado por default;
					$stmt->close();

					$stmt = $conn->prepare("INSERT INTO cifin_productos (id_resultado_cifin, id_producto) VALUES(?,?)");
					$stmt->bind_param("ii", $resultado_c, $id_producto);
					$stmt->execute();
					

					if ($stmt->affected_rows == 1) {

						$stmt->close();

						$terminos_p = [2,5,3];

						$count_rows = 0;

						foreach ($terminos_p as $termino) {

							$stmt = $conn->prepare("insert into terminos_productos (id_termino, id_producto) values(?,?)");
							$stmt->bind_param("ii", $termino, $id_producto);
							$stmt->execute();

							$rows1 = $stmt->affected_rows;
							$count_rows = $count_rows + $rows1;
						}

						$stmt->close();

						$frecuencias_p = [1,2,3];

						foreach ($frecuencias_p as $frecuencia) {

							$stmt = $conn->prepare("insert into frecuencias_productos (id_frecuencia_pago, id_producto) values(?,?)");
							$stmt->bind_param("ii", $frecuencia, $id_producto);
							$stmt->execute();
							
							$rows2 = $stmt->affected_rows;
							$count_rows = $count_rows + $rows2;

						}

						//echo $count_rows;
						//die();

						if($count_rows !== 6){

							$response = array(
								"response" => "error",
								"error" => $stmt->error
							);

							$id_inventario = 0;
							
						}

						$stmt->close();
						$conn->close();

					}else{

						$response = array(
							"response" => "error",
							"error" => $stmt->error
						);

						$id_inventario = 0;

					}

				}else{

					$response = array(
						"response" => "error",
						"error" => $stmt->error
					);

					$id_inventario = 0;

				}

			}else{

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);

				$id_inventario = 0;

			}

		}else{

			$id_producto = execute_scalar("SELECT id FROM productos WHERE id_modelo = $id_modelo");

			$id_inventario = execute_scalar("SELECT id FROM inventario WHERE id_producto = $id_producto");

		}

		if($id_inventario != 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO productos_stock (id_proveedor, id_inventario, imei_1, imei_2, id_color, id_estado_producto) VALUES (?,?,?,?,?,1)");
			$stmt->bind_param("iiiii", $proveedor_producto, $id_inventario, $imei_1, $imei_2, $color_producto);
			$stmt-> execute();

			if($stmt->affected_rows == 1){

				$id_existencia = $stmt->insert_id;

				$query = "SELECT modelos.nombre_modelo, productos_stock.id_color, capacidades_telefonos.capacidad, marcas.marca_producto, productos_stock.id_estado_producto, productos_stock.imei_1, colores_productos.color_desc FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id WHERE productos_stock.id = $id_existencia";

				$result = qry($query);

				while($row = mysqli_fetch_array($result)){

					$nombre_modelo = $row['nombre_modelo'];
					$color_desc = $row['color_desc'];
					$marca_producto = $row['marca_producto'];
					$imei_1 = $row['imei_1'];
					$capacidad = $row['capacidad'];

				}

				$response = array(
					"response" => "success",
					"id_existencia" => $id_existencia,
					"marca" => $marca_producto,
					"modelo" => $nombre_modelo,
					"color" => $color_desc,
					"imei1" => $imei_1
				);

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
