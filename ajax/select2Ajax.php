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

}else if($_POST['action'] == "select_productos_marca"){

	try {

		$id_marca = $_POST['id_marca'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT * FROM productos WHERE id_marca = ? AND del = 0");
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
}

?>