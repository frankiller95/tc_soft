<?php


session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "insertar") {

	try {

		$marca_producto = $_POST['marca_producto'];
		$modelo_producto = $_POST['modelo_producto'];
		$costo_producto = $_POST['costo_producto'];
		$precio_venta_producto = $_POST['precio_venta_producto'];
		$stock_producto = $_POST['stock_producto'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO productos (id_marca, modelo_producto) VALUES (?, ?)");
		$stmt->bind_param("is", $marca_producto, $modelo_producto);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$id_producto = $stmt->insert_id;
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO inventario (id_producto, costo, precio_venta, stock) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("iddi", $id_producto, $costo_producto, $precio_venta_producto, $stock_producto);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$id_inventario = $stmt->insert_id;

				$marca_nombre = execute_scalar("SELECT marca_producto FROM marcas WHERE id = $marca_producto");

				$response = array(
					"response" => "success",
					"id_inventario" => $id_inventario,
					"id_producto" => $id_producto,
					"marca_nombre" => $marca_nombre,
					"modelo_producto" => $modelo_producto,
					"costo" => number_format($costo_producto, 2),
					"precio_venta" => number_format($precio_venta_producto, 2),
					"stock" => $stock_producto
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

		$stmt->close();	  
		$conn ->close();
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_productos"){

	try {

		$id_producto = $_POST['id_producto'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT productos.id AS id_producto , productos.id_marca, productos.modelo_producto, inventario.costo, inventario.precio_venta, inventario.stock FROM productos LEFT JOIN inventario ON inventario.id_producto = productos.id WHERE productos.id = ?");
		$stmt->bind_param("i", $id_producto);
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

		$marca_producto = $_POST['marca_producto'];
		$modelo_producto = $_POST['modelo_producto'];
		$costo_producto = $_POST['costo_producto'];
		$precio_venta_producto = $_POST['precio_venta_producto'];
		$stock_producto = $_POST['stock_producto'];
		$id_producto = $_POST['id_producto'];
		$id_inventario = execute_scalar("SELECT id FROM inventario WHERE inventario.id_producto = $id_producto");

		qry("DELETE FROM productos WHERE id = $id_producto");
		qry("DELETE FROM inventario WHERE id = $id_inventario");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO productos (id, id_marca, modelo_producto) VALUES (?, ?, ?)");
		$stmt->bind_param("iis", $id_producto, $marca_producto, $modelo_producto);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {
			
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO inventario (id, id_producto, costo, precio_venta, stock) VALUES (?, ?, ?, ?, ?)");
			$stmt->bind_param("iiddi", $id_inventario, $id_producto, $costo_producto, $precio_venta_producto, $stock_producto);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$id_inventario = $stmt->insert_id;

				$marca_nombre = execute_scalar("SELECT marca_producto FROM marcas WHERE id = $marca_producto");

				$response = array(
					"response" => "success",
					"id_inventario" => $id_inventario,
					"id_producto" => $id_producto,
					"marca_nombre" => $marca_nombre,
					"modelo_producto" => $modelo_producto,
					"costo" => number_format($costo_producto, 2),
					"precio_venta" => number_format($precio_venta_producto, 2),
					"stock" => $stock_producto
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

		$id_producto = $_POST['id_producto'];
		$id_inventario = execute_scalar("SELECT id FROM inventario WHERE inventario.id_producto = $id_producto");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE productos SET del = 1 WHERE id = ?");
		$stmt->bind_param("i", $id_producto);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$stmt->close();
			$stmt = $conn->prepare("UPDATE inventario SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_inventario);
			$stmt-> execute();

			if ($stmt->affected_rows == 1) {

				$response = array(
					"response" => "success",
					"id_producto" => $id_producto
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

		$stmt->close();	  
		$conn ->close();

		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);
}