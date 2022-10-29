<?php

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

		//var_dump($_POST);
		//die();
		//
		// FALTA LA VALIDACIÓN DE NO TENER IMEI REPETIDO

		$imei1 = $_POST['imei_1'];
		if(isset($_POST['imei_2'])){
			$imei2 = $_POST['imei_2']; 
		}else{
			$imei2 = '';
		}
		$id_color = $_POST['color_producto'];
		$id_proveedor = $_POST['proveedor_producto'];

		$id_producto = $_POST['id_producto'];

		$id_estado_producto = 1;

		$id_inventario = $_POST['id_inventario'];

		$validate_exist = execute_scalar("SELECT COUNT(productos_stock.id) AS total FROM productos_stock WHERE imei_1 = '$imei1' OR imei_2 = '$imei2' and del = 0");

		if($validate_exist == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO productos_stock (id_proveedor, id_inventario, imei_1, imei_2, id_color, id_estado_producto) VALUES (?,?,?,?,?,?)");
			$stmt->bind_param("iiiiii", $id_proveedor, $id_inventario, $imei1, $imei2, $id_color, $id_estado_producto);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$color = execute_scalar("SELECT color_desc FROM colores_productos WHERE id = $id_color");

				$proveedor_nombre = execute_scalar("SELECT proveedor_nombre FROM proveedores WHERE id = $id_proveedor");

				$estado = execute_scalar("SELECT estado_desc FROM estados_productos WHERE id = $id_estado_producto");

				$response = array(
					"response" => "success",
					"id_existencia" => $stmt->insert_id,
					"imei1" => $imei1,
					"imei2" => $imei2,
					"color" => $color,
					"proveedor" => $proveedor_nombre,
					"estado" => $estado
				);

			}else{

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);

			}

		}else{

			$response = array(
				"response" => "imei_existe",
				"total" => "$validate_exist"
			);

		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_existencia"){

	try {

		$id_existencia = $_POST['id_existencia'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT * FROM productos_stock WHERE id = ?");
		$stmt->bind_param("i", $id_existencia);
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

}else if($_POST['action'] == "editar"){

	try {

		$imei1 = $_POST['imei_1'];
		if(isset($_POST['imei_2'])){
			$imei2 = $_POST['imei_2']; 
		}else{
			$imei2 = '';
		}
		$id_color = $_POST['color_producto'];
		$id_proveedor = $_POST['proveedor_producto'];

		$id_producto = $_POST['id_producto'];

		$id_estado_producto = 1;

		$id_inventario = $_POST['id_inventario'];

		$id_existencia = $_POST['id_existencia'];

		$validate_exist = execute_scalar("SELECT COUNT(productos_stock.id) AS total FROM productos_stock WHERE (imei_1 = '$imei1' OR imei_2 = '$imei2') and del = 0 and id != $id_existencia");

		if($validate_exist == 0){

			qry("DELETE FROM productos_stock WHERE id = $id_existencia");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO productos_stock (id, id_proveedor, id_inventario, imei_1, imei_2, id_color, id_estado_producto) VALUES (?, ?,?,?,?,?,?)");
			$stmt->bind_param("iiiiiii", $id_existencia, $id_proveedor, $id_inventario, $imei1, $imei2, $id_color, $id_estado_producto);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				//qry("UPDATE inventario SET stock = stock + 1 WHERE id = $id_producto");

				$color = execute_scalar("SELECT color_desc FROM colores_productos WHERE id = $id_color");

				$proveedor_nombre = execute_scalar("SELECT proveedor_nombre FROM proveedores WHERE id = $id_proveedor");

				$estado = execute_scalar("SELECT estado_desc FROM estados_productos WHERE id = $id_estado_producto");

				$response = array(
					"response" => "success",
					"id_existencia" => $stmt->insert_id,
					"imei1" => $imei1,
					"imei2" => $imei2,
					"color" => $color,
					"proveedor" => $proveedor_nombre,
					"estado" => $estado
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
				"response" => "imei_existe",
				"total" => "$validate_exist"
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

		// FATA LA VALIDACIÓN QUE NO TENGA solicitudes activas.

		$id_existencia = $_POST['id_existencia'];

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE productos_stock SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_existencia);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array(
					"response" => "success",
					"id_existencia" => $id_existencia
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

}else if($_POST['action'] == "danado"){

	try {


		$id_existencia = $_POST['id_existencia'];

		$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE productos_stock SET id_estado_producto = 4 WHERE id = ?");
			$stmt->bind_param("i", $id_existencia);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$query = "SELECT productos_stock.id AS id_existencia, imei_1, imei_2, proveedores.proveedor_nombre, color_desc, id_estado_producto, estados_productos.estado_desc FROM productos_stock LEFT JOIN estados_productos ON productos_stock.id_estado_producto = estados_productos.id LEFT JOIN proveedores ON productos_stock.id_proveedor = proveedores.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE productos_stock.id = $id_existencia";

				$result = qry($query);

				while ($row = mysqli_fetch_array($result)) {

					$imei_1 = $row['imei_1'];
					$imei_2 = $row['imei_2'];
					$proveedor_nombre = $row['proveedor_nombre'];
					$color_desc = $row['color_desc'];
					$id_estado_producto = $row['id_estado_producto'];
					$estado_desc = $row['estado_desc'];

				}

				$response = array(
					"response" => "success",
					"id_existencia" => $id_existencia,
					"imei_1" => $imei_1,
					"imei_2" => $imei_2,
					"proveedor_nombre" => $proveedor_nombre,
					"color_desc" => $color_desc,
					"id_estado_producto" => $id_estado_producto,
					"estado_desc" => $estado_desc
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

}else if($_POST['action'] == "reparado"){

	try {

		$id_existencia = $_POST['id_existencia'];

		$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE productos_stock SET id_estado_producto = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_existencia);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$query = "SELECT productos_stock.id AS id_existencia, imei_1, imei_2, proveedores.proveedor_nombre, color_desc, id_estado_producto, estados_productos.estado_desc FROM productos_stock LEFT JOIN estados_productos ON productos_stock.id_estado_producto = estados_productos.id LEFT JOIN proveedores ON productos_stock.id_proveedor = proveedores.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id WHERE productos_stock.id = $id_existencia";

				$result = qry($query);

				while ($row = mysqli_fetch_array($result)) {

					$imei_1 = $row['imei_1'];
					$imei_2 = $row['imei_2'];
					$proveedor_nombre = $row['proveedor_nombre'];
					$color_desc = $row['color_desc'];
					$id_estado_producto = $row['id_estado_producto'];
					$estado_desc = $row['estado_desc'];

				}

				$response = array(
					"response" => "success",
					"id_existencia" => $id_existencia,
					"imei_1" => $imei_1,
					"imei_2" => $imei_2,
					"proveedor_nombre" => $proveedor_nombre,
					"color_desc" => $color_desc,
					"id_estado_producto" => $id_estado_producto,
					"estado_desc" => $estado_desc
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

}else if($_POST['action'] == "ver_productos"){
	try {

		//var_dump($_POST);
		//die();

		$id_existencia = $_POST['id_existencia'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT solicitudes.id AS id_solicitud, solicitudes.id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, estados_solicitudes.mostrar, solicitudes.id_estado_solicitud FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.del = 0 AND solicitudes.id_existencia = ?");
		$stmt->bind_param("i", $id_existencia);
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
}

?>