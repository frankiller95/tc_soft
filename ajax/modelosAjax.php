<?

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

		$marca_producto = $_POST['marca_producto'];
		$nuevo_modelo = $_POST['nuevo_modelo'];
		$id_capacidad = $_POST['capacidad_modelo'];
		$precio_modelo = $_POST['precio_modelo'];
		$precio_modelo = str_replace('.', '', $precio_modelo);

		//$queryV = "SELECT COUNT(id) AS total FROM modelos WHERE id_marca = $marca_producto AND nombre_modelo = '$nuevo_modelo' AND del = 0";

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM modelos WHERE id_marca = $marca_producto AND nombre_modelo = '$nuevo_modelo' AND id_capacidad = $id_capacidad AND del = 0");

		if ($validate == 0) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO modelos (id_marca, nombre_modelo, costo, precio_venta, id_capacidad, fecha_registro) VALUES (?, ?, 0, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("isii", $marca_producto, $nuevo_modelo, $precio_modelo, $id_capacidad);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$marca_nombre = execute_scalar("SELECT marca_producto FROM marcas WHERE id = $marca_producto");
				$capacidad_modelo = execute_scalar("SELECT capacidad FROM capacidades_telefonos WHERE id = $id_capacidad");

				$porcentaje_inicial = 30;
				$inicial_30 = ($porcentaje_inicial * $precio_modelo) / 100;
				$valor_credito_30 = $precio_modelo - $inicial_30;
				$porcentaje_inicial = 40;
				$inicial_40 = ($porcentaje_inicial * $precio_modelo) / 100;
				$valor_credito_40 = $precio_modelo - $inicial_40;
				$porcentaje_inicial = 50;
				$inicial_50 = ($porcentaje_inicial * $precio_modelo) / 100;
				$valor_credito_50 = $precio_modelo - $inicial_50;

				$total_cuotas = 8;
				$cuota_4_meses = $precio_modelo / $total_cuotas;
				$total_cuotas = 12;
				$cuota_6_meses = $precio_modelo / $total_cuotas;
				$total_cuotas = 16;
				$cuota_8_meses = $precio_modelo / $total_cuotas;

				$response = array(
					'response' => "success",
					"id_modelo" => $stmt->insert_id,
					"marca" => $marca_nombre,
					"modelo" => $nuevo_modelo,
					"precio_modelo" => number_format($precio_modelo, 0, '.', '.'),
					"capacidad_modelo" => $capacidad_modelo,
					"inicial_30" => number_format($inicial_30, 0, '.', '.'),
					"inicial_40" => number_format($inicial_40, 0, '.', '.'),
					"inicial_50" => number_format($inicial_50, 0, '.', '.'),
					"cuota_4_meses" => number_format($cuota_4_meses, 0, '.', '.'),
					"cuota_6_meses" => number_format($cuota_6_meses, 0, '.', '.'),
					"cuota_8_meses" => number_format($cuota_8_meses, 0, '.', '.')
				);
			} else {

				$response = array(
					"response" => "error",
					"error" => $stmt->error
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
} else if ($_POST['action'] == "select_modelos") {

	try {

		$id_modelo = $_POST['id_modelo'];

		$response = array();

		$query = "SELECT modelos.id AS id_modelo, id_marca, nombre_modelo, precio_venta, id_capacidad FROM modelos WHERE modelos.id = $id_modelo";
		$result = qry($query);
		while ($row = mysqli_fetch_array($result)) {

			$id_marca = $row['id_marca'];
			$modelo = $row['nombre_modelo'];
			$precio_venta = $row['precio_venta'];
			$id_capacidad = $row['id_capacidad'];

			$newArray = array("id_marca" => $id_marca, "modelo" => $modelo, "precio_venta" => number_format($precio_venta, 0, '.', '.'), "id_capacidad" => $id_capacidad);
			array_push($response, $newArray);
		}
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "editar") {

	try {

		$marca_producto = $_POST['marca_producto'];
		$nuevo_modelo = $_POST['nuevo_modelo'];
		$id_capacidad = $_POST['capacidad_modelo'];
		$precio_modelo = $_POST['precio_modelo'];
		$precio_modelo = str_replace('.', '', $precio_modelo);
		$id_modelo = $_POST['id_modelo'];

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM modelos WHERE id_marca = $marca_producto AND nombre_modelo = '$nuevo_modelo' AND id_capacidad = $id_capacidad AND del = 0 AND id <> $id_modelo");

		if ($validate == 0) {

			qry("DELETE FROM modelos WHERE id = $id_modelo");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO modelos (id, id_marca, nombre_modelo, costo, precio_venta, id_capacidad, fecha_registro) VALUES (?, ?, ?, 0, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("iisii", $id_modelo, $marca_producto, $nuevo_modelo, $precio_modelo, $id_capacidad);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$marca_nombre = execute_scalar("SELECT marca_producto FROM marcas WHERE id = $marca_producto");
				$capacidad_modelo = execute_scalar("SELECT capacidad FROM capacidades_telefonos WHERE id = $id_capacidad");

				$porcentaje_inicial = 30;
				$inicial_30 = ($porcentaje_inicial * $precio_modelo) / 100;
				$valor_credito_30 = $precio_modelo - $inicial_30;
				$porcentaje_inicial = 40;
				$inicial_40 = ($porcentaje_inicial * $precio_modelo) / 100;
				$valor_credito_40 = $precio_modelo - $inicial_40;
				$porcentaje_inicial = 50;
				$inicial_50 = ($porcentaje_inicial * $precio_modelo) / 100;
				$valor_credito_50 = $precio_modelo - $inicial_50;

				$total_cuotas = 8;
				$cuota_4_meses = $precio_modelo / $total_cuotas;
				$total_cuotas = 12;
				$cuota_6_meses = $precio_modelo / $total_cuotas;
				$total_cuotas = 16;
				$cuota_8_meses = $precio_modelo / $total_cuotas;

				$response = array(
					'response' => "success",
					"id_modelo" => $id_modelo,
					"marca" => $marca_nombre,
					"modelo" => $nuevo_modelo,
					"precio_modelo" => number_format($precio_modelo, 0, '.', '.'),
					"capacidad_modelo" => $capacidad_modelo,
					"inicial_30" => number_format($inicial_30, 0, '.', '.'),
					"inicial_40" => number_format($inicial_40, 0, '.', '.'),
					"inicial_50" => number_format($inicial_50, 0, '.', '.'),
					"cuota_4_meses" => number_format($cuota_4_meses, 0, '.', '.'),
					"cuota_6_meses" => number_format($cuota_6_meses, 0, '.', '.'),
					"cuota_8_meses" => number_format($cuota_8_meses, 0, '.', '.')
				);

			} else {

				$response = array(
					"response" => "error",
					"error" => $stmt->error
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
} else if ($_POST['action'] == "eliminar_modelo") {

	try {

		$id_modelo = $_POST['id_modelo'];

		$validate = execute_scalar("SELECT COUNT(solicitudes.id) AS total FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id WHERE solicitudes.del = 0 AND productos_stock.del = 0 AND inventario.del = 0 AND productos.del = 0 AND modelos.id = $id_modelo");

		if ($validate == 0) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE modelos SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_modelo);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$query = "SELECT productos.id FROM productos WHERE id_modelo = $id_modelo AND del = 0";

				$result = qry($query);


				while ($row = mysqli_fetch_array($result)) {

					$id_producto = $row['id'];

					qry("UPDATE productos SET del = 1 WHERE id = $id_producto");

					$query2 = "SELECT inventario.id FROM inventario WHERE id_producto = $id_producto AND inventario.del = 0";

					$result2 = qry($query2);
					
					while ($row2 = mysqli_fetch_array($result2)) {

						$id_inventario = $row2['id'];

						qry("UPDATE inventario SET del = 1 WHERE id = $id_inventario");

						qry("UPDATE productos_stock SET del = 1 WHERE id_inventario = $id_inventario");

					}

				}

				$response = array(
					'response' => "success",
					"id_modelo" => $id_modelo
				);

			} else {

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);
			}

			$stmt->close();
			$conn->close();
		} else {

			$response = array(
				"response" => "solicitudes_activas"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
}
