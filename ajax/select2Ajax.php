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

	if ($id_departamento != "") {

		echo json_encode($response);

	}

}else if($_POST['action'] == "select_productos_marca"){

	try {

		$id_marca = $_POST['id_marca'];
		$resultado_cifin = $_POST['resultado_cifin'];

		$join = '';
		$condition = '';
		if($resultado_cifin == 2){
			$join = " LEFT JOIN cifin_productos ON cifin_productos.id_producto = productos.id ";
			$condition = " AND cifin_productos.id_resultado_cifin = 2 OR cifin_productos.id_resultado_cifin = 3 ";
		}else if($resultado_cifin == 3){
			$join = " LEFT JOIN cifin_productos ON cifin_productos.id_producto = productos.id ";
			$condition = " AND cifin_productos.id_resultado_cifin = 3 ";
		}

		$query = "SELECT DISTINCT productos.id, modelo_producto, capacidad, id_marca FROM productos LEFT JOIN capacidades_telefonos ON productos.id_capacidad = capacidades_telefonos.id".$join."WHERE productos.del = 0 AND productos.id_marca = $id_marca".$condition." ORDER BY modelo_producto ASC";

		if ($resultado_cifin == 1) {
			$query = "SELECT productos.id, modelo_producto, capacidad, id_marca FROM productos LEFT JOIN capacidades_telefonos ON productos.id_capacidad = capacidades_telefonos.id WHERE productos.del = 0 AND productos.id_marca = $id_marca ORDER BY modelo_producto ASC";
		}
		//echo $query;

		$response = array();
		$result = qry($query);

		while ($row = mysqli_fetch_array($result)) {

			$id = $row['id'];
			$modelo_producto = $row['modelo_producto'];
			$capacidad = $row['capacidad'];
			$id_marca2 = $row['id_marca'];

			if ($id_marca2 == $id_marca) {
				$newArray = array("id" => $id, "modelo_producto" => $modelo_producto, "capacidad" => $capacidad, "id_marca" => $id_marca);
				array_push($response, $newArray);
			}

		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "select_all_marcas"){

	try {

		$id_marca = $_POST['id_marca'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT * FROM modelos WHERE id_marca = ?");
		$stmt->bind_param("i", $id_marca);
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


}else if($_POST['action'] == "otro"){

	try {

		$from = $_POST['from'];

		$conn = new mysqli($host, $user, $pass, $db);

		if ($from == "ciudad") {
			
			$id_departamento = $_POST['id_depart'];
			$ciudad = $_POST['ciudad_otra'];

			$validate = execute_scalar("SELECT COUNT(id) AS total FROM ciudades WHERE ciudad = '$ciudad'");

			if ($validate == 0) {
				

				$stmt = $conn->prepare("INSERT INTO ciudades (ciudad, id_departamento) VALUES (?, ?)");
				$stmt->bind_param("si", $ciudad, $id_departamento);
				$stmt-> execute();

				if ($stmt->affected_rows == 1) {
					$response = array(
						"response" => "success",
						"id_departamento" => $id_departamento,
						"id_ciudad" => $stmt->insert_id,
						"ciudad" => $ciudad,
						"from" => $from
					);

				}else{

					$response = array(
						"response" => "error",
						"error" => $stmt->error
					);

				}

				$stmt->close();

			}else{

				$response = array(
					"response" => "ciudad_repeat"
				);

			}
			

		}else if($from == "departamento"){

			$departamento = $_POST['depar_otro'];

			$validate = execute_scalar("SELECT COUNT(id) AS total FROM departamentos WHERE departamento = '$departamento'");

			if ($validate == 0) {

				$stmt = $conn->prepare("INSERT INTO departamentos (departamento) VALUES (?)");
				$stmt->bind_param("s", $departamento);
				$stmt-> execute();

				if ($stmt->affected_rows == 1) {

					$response = array(

						"response" => "success",
						"id_departamento" => $stmt->insert_id,
						"departamento" => $departamento,
						"from" => $from
					);
					
				}else{

					$response = array(
						"response" => "error",
						"error" => $stmt->error
					);

				}

				$stmt->close();

			}else{

				$response = array(
					"response" => "depart_repeat"
				);

			}

		}
	  
		$conn ->close();
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "departamentos_list"){

	try {

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("select id, departamento from departamentos order by departamento");
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