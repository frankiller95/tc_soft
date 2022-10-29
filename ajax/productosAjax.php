<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "insertar") {

	try {

		$id_modelo = $_POST['dispositivos'];
		$resultado_c = $_POST['resultado_c'];

		//echo $query_validate = "SELECT COUNT(id) AS total FROM productos WHERE id_modelo = $modelo_producto AND id_capacidad = $capacidad_producto";
		//die();

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM productos WHERE id_modelo = $id_modelo AND productos.del = 0");

		if ($validate == 0) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO productos (id_marca, id_modelo, id_capacidad, fecha_registro) VALUES (0, ?, 0, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("i", $id_modelo);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$id_producto = $stmt->insert_id;
				$stmt->close();

				$stmt = $conn->prepare("INSERT INTO inventario (id_producto, costo, precio_venta, fecha_registro) VALUES (?, 0, 0, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("i", $id_producto);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$id_inventario = $stmt->insert_id;

					$stmt->close();

					$stmt = $conn->prepare("INSERT INTO cifin_productos (id_resultado_cifin, id_producto) VALUES(?,?)");
					$stmt->bind_param("ii", $resultado_c, $id_producto);
					$stmt->execute();
					$stmt->close();

					if (isset($_POST['terminos_p']) && is_array($_POST['terminos_p'])) {
						foreach ($_POST['terminos_p'] as $termino) {

							$stmt = $conn->prepare("insert into terminos_productos (id_termino, id_producto) values(?,?)");
							$stmt->bind_param("ii", $termino, $id_producto);
							$stmt->execute();
							$stmt->close();
						}
					}

					if (isset($_POST['frecuencias_p']) && is_array($_POST['frecuencias_p'])) {
						foreach ($_POST['frecuencias_p'] as $frecuencia) {

							$stmt = $conn->prepare("insert into frecuencias_productos (id_frecuencia_pago, id_producto) values(?,?)");
							$stmt->bind_param("ii", $frecuencia, $id_producto);
							$stmt->execute();
							$stmt->close();
						}
					}

					$query1 = "SELECT inventario.id, productos.id as producto_id, marcas.marca_producto, productos.id_modelo, modelos.nombre_modelo, capacidades_telefonos.capacidad, 
					(SELECT COUNT(productos_stock.id) AS total FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE inventario.id_producto = producto_id AND productos_stock.del = 0) AS stock,
					(SELECT COUNT(productos_stock.id) AS total2 FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE inventario.id_producto = producto_id AND productos_stock.id_estado_producto = 1 AND productos_stock.del = 0) AS disponibles,
					modelos.id_capacidad, modelos.precio_venta FROM inventario LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE inventario.id = $id_inventario";

					$result1 = qry($query1);
					while ($row1 = mysqli_fetch_array($result1)) {

						$id_producto = $row1['producto_id'];
						$marca = $row1['marca_producto'];
						$modelo_producto = $row1['nombre_modelo'];
						$precio_venta = $row1['precio_venta'];
						$stock = $row1['stock'];
						$capacidad = $row1['capacidad'];

						$disponibles = $row1['disponibles'];

						$id_resultado_cifin = execute_scalar("SELECT id_resultado_cifin FROM cifin_productos WHERE id_producto = $id_producto");

						$estado_cifin = execute_scalar("SELECT estado FROM resultados_cifin WHERE id = $id_resultado_cifin");
					}

					$response = array(
						"response" => "success",
						"id_inventario" => $id_inventario,
						"id_producto" => $id_producto,
						"marca_nombre" => $marca,
						"modelo_producto" => $modelo_producto,
						"precio_venta" => number_format($precio_venta, 0, '.', '.'),
						"stock" => $stock,
						"capacidad" => $capacidad,
						"disponibles" => $disponibles,
						"estado_cifin" => $estado_cifin
					);
				} else {
					$response = array(
						"response" => "error"
					);
				}
			} else {
				$response = array(
					"response" => "error"
				);
			}

			$conn->close();
		} else {

			$response = array(
				"response" => "existe"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "select_productos") {

	try {

		$id_producto = $_POST['id_producto'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT productos.id AS id_producto, id_marca, id_modelo, id_capacidad, inventario.costo, inventario.precio_venta, cifin_productos.id_resultado_cifin FROM productos LEFT JOIN inventario ON inventario.id_producto = productos.id LEFT JOIN cifin_productos ON cifin_productos.id_producto = productos.id WHERE productos.id = ?");
		$stmt->bind_param("i", $id_producto);
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

		$stmt->close();
		$conn->close();

		$frecuencias_array = array();

		$query = "SELECT id_frecuencia_pago FROM frecuencias_productos WHERE id_producto = $id_producto";

		$result = qry($query);

		while ($row = mysqli_fetch_array($result)) {

			$id_frecuencia = $row['id_frecuencia_pago'];

			$newArray = array("id_frecuencia" => $id_frecuencia);

			array_push($frecuencias_array, $newArray);
		}

		$terminos_array = array();

		$query = "SELECT id_termino FROM terminos_productos WHERE id_producto = $id_producto";

		$result = qry($query);

		while ($row = mysqli_fetch_array($result)) {

			$id_termino = $row['id_termino'];

			$newArray = array("id_termino" => $id_termino);

			array_push($terminos_array, $newArray);
		}

		array_push($response, $frecuencias_array);
		array_push($response, $terminos_array);
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "editar") {

	try {

		$id_modelo = $_POST['dispositivos'];
		$resultado_c = $_POST['resultado_c'];
		$id_producto = $_POST['id_producto'];

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM productos WHERE id_modelo = $id_modelo AND id <> $id_producto AND productos.del = 0");

		$id_modelo_old = execute_scalar("SELECT id_modelo FROM productos WHERE id = $id_producto");

		//valida si hay CREDITOS ya entregados al cliente.
		$validate2 = execute_scalar("SELECT COUNT(productos_stock.id) AS total FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id  LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id WHERE modelos.id = $id_modelo_old AND productos_stock.id_estado_producto = 3 AND productos_stock.del = 0");

		if ($validate == 0) {

			if ($validate2 == 0) {

				qry("DELETE FROM productos WHERE id = $id_producto AND del = 0");

				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO productos (id, id_marca, id_modelo, id_capacidad) VALUES (?, 0, ?, 0)");
				$stmt->bind_param("ii", $id_producto, $id_modelo);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$id_inventario = execute_scalar("SELECT id FROM inventario WHERE id_producto = $id_producto AND del = 0");

					$stmt->close();
					qry("DELETE FROM inventario WHERE id = $id_inventario");

					$stmt = $conn->prepare("INSERT INTO inventario (id, id_producto, costo, precio_venta) VALUES (?, ?, 0, 0)");
					$stmt->bind_param("ii", $id_inventario, $id_producto);
					$stmt->execute();

					if ($stmt->affected_rows == 1) {

						$id_inventario = $stmt->insert_id;

						$stmt->close();

						$id_cifin = execute_scalar("SELECT id FROM cifin_productos WHERE id_producto = $id_producto");
						qry("DELETE FROM cifin_productos WHERE id = $id_cifin");
						$stmt = $conn->prepare("INSERT INTO cifin_productos (id_resultado_cifin, id_producto) VALUES(?,?)");
						$stmt->bind_param("ii", $resultado_c, $id_producto);
						$stmt->execute();
						$stmt->close();

						qry("DELETE FROM terminos_productos WHERE id_producto = $id_producto");
						if (isset($_POST['terminos_p']) && is_array($_POST['terminos_p'])) {

							foreach ($_POST['terminos_p'] as $termino) {

								$stmt = $conn->prepare("insert into terminos_productos (id_termino, id_producto) values(?,?)");
								$stmt->bind_param("ii", $termino, $id_producto);
								$stmt->execute();
								$stmt->close();
							}
						}

						qry("DELETE FROM frecuencias_productos WHERE id_producto = $id_producto");
						if (isset($_POST['frecuencias_p']) && is_array($_POST['frecuencias_p'])) {
							foreach ($_POST['frecuencias_p'] as $frecuencia) {

								$stmt = $conn->prepare("insert into frecuencias_productos (id_frecuencia_pago, id_producto) values(?,?)");
								$stmt->bind_param("ii", $frecuencia, $id_producto);
								$stmt->execute();
								$stmt->close();
							}
						}

							$query1 = "SELECT inventario.id, productos.id as producto_id, marcas.marca_producto, productos.id_modelo, modelos.nombre_modelo, capacidades_telefonos.capacidad, 
						(SELECT COUNT(productos_stock.id) AS total FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE inventario.id_producto = producto_id AND productos_stock.del = 0) AS stock,
						(SELECT COUNT(productos_stock.id) AS total2 FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE inventario.id_producto = producto_id AND productos_stock.id_estado_producto = 1 AND productos_stock.del = 0) AS disponibles,
						modelos.id_capacidad, modelos.precio_venta FROM inventario LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE inventario.id = $id_inventario";

						$result1 = qry($query1);
						while ($row1 = mysqli_fetch_array($result1)) {

							$id_producto = $row1['producto_id'];
							$marca = $row1['marca_producto'];
							$modelo_producto = $row1['nombre_modelo'];
							$precio_venta = $row1['precio_venta'];
							$stock = $row1['stock'];
							$capacidad = $row1['capacidad'];

							$disponibles = $row1['disponibles'];

							$id_resultado_cifin = execute_scalar("SELECT id_resultado_cifin FROM cifin_productos WHERE id_producto = $id_producto");

							$estado_cifin = execute_scalar("SELECT estado FROM resultados_cifin WHERE id = $id_resultado_cifin");
						}

						$response = array(
							"response" => "success",
							"id_inventario" => $id_inventario,
							"id_producto" => $id_producto,
							"marca_nombre" => $marca,
							"modelo_producto" => $modelo_producto,
							"precio_venta" => number_format($precio_venta, 0, '.', '.'),
							"stock" => $stock,
							"capacidad" => $capacidad,
							"disponibles" => $disponibles,
							"estado_cifin" => $estado_cifin
						);
					} else {
						$response = array(
							"response" => "error1"
						);
					}

				} else {
					$response = array(
						"response" => "error2"
					);
				}

				$conn->close();

			} else {
				$response = array(
					"response" => "entregados"
				);
			}

		} else {

			$response = array(
				"response" => "existe"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "eliminar") {

	try {

		$id_producto = $_POST['id_producto'];

		$id_inventario = execute_scalar("SELECT id FROM inventario WHERE inventario.id_producto = $id_producto AND del = 0");

		$validate = execute_scalar("SELECT COUNT(solicitudes.id) AS total FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id WHERE solicitudes.del = 0 AND productos_stock.del = 0 AND inventario.del = 0 AND productos.id = $id_producto");

		if ($validate == 0) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE productos SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_producto);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$stmt->close();
				$stmt = $conn->prepare("UPDATE inventario SET del = 1 WHERE id = ?");
				$stmt->bind_param("i", $id_inventario);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					qry("UPDATE productos_stock SET del = 1 WHERE id_inventario = $id_inventario AND del = 0");

					$response = array(
						"response" => "success",
						"id_producto" => $id_producto
					);
				} else {
					$response = array(
						"response" => "error"
					);
				}
			} else {
				$response = array(
					"response" => "error"
				);
			}

			$stmt->close();
			$conn->close();
		} else {

			$response = array(
				"response" => "existe"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "crear_plantilla"){

	try {
		
		$id_producto = $_POST['id_producto'];
		$plantilla_producto = $_POST['plantilla_producto'];

		$validate_plantilla_create = execute_scalar("SELECT COUNT(*) AS total FROM plantillas_productos WHERE id_producto = $id_producto AND del = 0");

		$conn = new mysqli($host, $user, $pass, $db);

		if($validate_plantilla_create == 0){

			$stmt = $conn->prepare("INSERT INTO plantillas_productos (id_producto, texto_plantilla, fecha_registro, ultimo_cambio) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("is", $id_producto, $plantilla_producto);

		}else{

			$stmt = $conn->prepare("UPDATE plantillas_productos SET texto_plantilla = ?, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_producto = ?");
			$stmt->bind_param("si", $plantilla_producto, $id_producto);

		}

		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_producto" => $id_producto
			);
		
		}else{

			$response = array(
				"response" => "error",
				"error" => $stmt->error
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

}else if($_POST['action'] == "select_plantilla"){

	try {

		$id_producto = $_POST['id_producto'];
		$plantilla_producto = execute_scalar("SELECT texto_plantilla FROM plantillas_productos WHERE id_producto = $id_producto AND del = 0");
		$referencia_completa = execute_scalar("SELECT CONCAT(marcas.marca_producto, ' ', modelos.nombre_modelo, ' ', capacidades_telefonos.capacidad) AS referencia_completa FROM productos LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE productos.id = $id_producto");

		$response = array(
			"response" => "success",
			"plantilla_producto" => $plantilla_producto,
			"id_producto" => $id_producto,
			"referencia_completa" => $referencia_completa
		);
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);

	}
	echo json_encode($response);
}