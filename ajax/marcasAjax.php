<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action'] == "insertar") {

	try {

		$marca_nombre = $_POST['nueva_marca'];

		//$queryV = "SELECT COUNT(id) AS total FROM modelos WHERE id_marca = $marca_producto AND nombre_modelo = '$nuevo_modelo' AND del = 0";

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM marcas WHERE marca_producto = '$marca_nombre' AND del = 0");

		if($validate == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO marcas (marca_producto, fecha_registro) VALUES (?,DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("s", $marca_nombre);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array('response' => "success",
					"id_marca" => $stmt->insert_id,
					"marca" => $marca_nombre
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
				"response" => "existe"
			);
		}

		
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_marcas"){
	try {

		$id_marca = $_POST['id_marca'];

		$response = array();

		$query = "SELECT * FROM marcas WHERE id = $id_marca";
		$result = qry($query);
		while ($row = mysqli_fetch_array($result)) {

			$id_marca = $row['id'];
			$marca = $row['marca_producto'];

			$newArray = array("id_marca" => $id_marca, "marca" => $marca);
			array_push($response, $newArray);
		}

		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);


}else if($_POST['action'] == "editar"){

	try {

		$marca_nombre = $_POST['nueva_marca'];

		$id_marca = $_POST['id_marca'];

		//$queryV = "SELECT COUNT(id) AS total FROM modelos WHERE id_marca = $marca_producto AND nombre_modelo = '$nuevo_modelo' AND del = 0";

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM marcas WHERE marca_producto = '$marca_nombre' AND del = 0 AND id <> $id_marca");

		if($validate == 0){

			qry("DELETE FROM marcas WHERE id = $id_marca");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO marcas (id, marca_producto, fecha_registro) VALUES (?, ?,DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("is", $id_marca, $marca_nombre);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array('response' => "success",
					"id_marca" => $id_marca,
					"marca" => $marca_nombre
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
				"response" => "existe"
			);
		}


		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "eliminar_marca"){

	try {

		$id_marca = $_POST['id_marca'];

		$validate = execute_scalar("SELECT COUNT(productos_stock.id) AS total FROM productos_stock LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN marcas ON productos.id_marca = marcas.id WHERE marcas.id = $id_marca AND productos_stock.id_estado_producto <> 1 OR productos_stock.id_estado_producto <> 4");

		if($validate == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE marcas SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_marca);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array('response' => "success",
					"id_marca" => $id_marca
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
				"response" => "modelos_activos"
			);

		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);
}