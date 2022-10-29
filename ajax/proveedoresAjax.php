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

		$nombre_proveedor = $_POST['nombre_proveedor'];
		$nit_proveedor = $_POST['nit_proveedor'];
		$direccion_proveedor = $_POST['direccion_proveedor'];

		if (isset($_POST['ciudad_proveedor'])) {

			$ciudad_proveedor = $_POST['ciudad_proveedor'];	
			$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_proveedor");
				
		}else{
			
			$ciudad_proveedor = 0;
			$ciudad_nombre = "N/A";

		}

		if (isset($_POST['contacto_proveedor'])) {

			$contacto_proveedor = $_POST['contacto_proveedor'];
			$contacto_proveedor = substr($contacto_proveedor, 1,3).substr($contacto_proveedor, 6,3).substr($contacto_proveedor, 12,4);

		}else{

			$contacto_proveedor = '';			

		}

		if (isset($_POST['email_proveedor'])) {

			$email_proveedor = $_POST['email_proveedor'];

		}else{

			$email_proveedor = '';

		}
		

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO proveedores (proveedor_nombre, proveedor_nit, proveedor_direccion, proveedor_ciudad_id, proveedor_contacto, proveedor_email) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssiss", $nombre_proveedor, $nit_proveedor, $direccion_proveedor, $ciudad_proveedor, $contacto_proveedor, $email_proveedor);
		$stmt-> execute();
		if ($stmt->affected_rows == 1) {

			$contacto_proveedor = '('.substr($contacto_proveedor, 0,3).') '.substr($contacto_proveedor, 3,3).'-'.substr($contacto_proveedor, 6,4);

			$response = array(
				"response" => "success",
				"id_proveedor" => $stmt->insert_id,
				"proveedor_nombre" => $nombre_proveedor,
				"proveedor_nit" => $nit_proveedor,
				"contacto_proveedor" => $contacto_proveedor,
				"direccion_proveedor" => $direccion_proveedor,
				"ciudad_nombre" => $ciudad_nombre,
				"email_proveedor" => $email_proveedor
			);
			
		}else{

			$response = array(
				"response" => "error",
				"error" => $stmt->error
			);

		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_proveedor"){

	try {

		$id_proveedor = $_POST['id_proveedor'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT proveedores.id, proveedor_nombre, proveedor_nit, proveedor_direccion, proveedor_ciudad_id, id_departamento, proveedor_contacto, proveedor_email FROM proveedores LEFT JOIN ciudades ON proveedores.proveedor_ciudad_id  = ciudades.id WHERE proveedores.id = ?");
		$stmt->bind_param("i", $id_proveedor);
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

		if ($response[0]['proveedor_contacto'] != '') {
	    	$response[0]['proveedor_contacto'] = '('.substr($response[0]['proveedor_contacto'], 0,3).') '.substr($response[0]['proveedor_contacto'], 3,3).'-'.substr($response[0]['proveedor_contacto'], 6,4);
	    }

		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

		
	}

	echo json_encode($response);

}else if($_POST['action'] == "editar"){

	try {

		$nombre_proveedor = $_POST['nombre_proveedor'];
		$nit_proveedor = $_POST['nit_proveedor'];
		$direccion_proveedor = $_POST['direccion_proveedor'];

		if (isset($_POST['ciudad_proveedor'])) {

			$ciudad_proveedor = $_POST['ciudad_proveedor'];	
			$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_proveedor");
				
		}else{
			
			$ciudad_proveedor = 0;
			$ciudad_nombre = "N/A";

		}

		if (isset($_POST['contacto_proveedor'])) {

			$contacto_proveedor = $_POST['contacto_proveedor'];
			$contacto_proveedor = substr($contacto_proveedor, 1,3).substr($contacto_proveedor, 6,3).substr($contacto_proveedor, 12,4);

		}else{

			$contacto_proveedor = '';			

		}

		if (isset($_POST['email_proveedor'])) {

			$email_proveedor = $_POST['email_proveedor'];

		}else{

			$email_proveedor = '';

		}

		$id_proveedor = $_POST['id_proveedor'];

		qry("DELETE FROM proveedores WHERE id = $id_proveedor");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO proveedores (id, proveedor_nombre, proveedor_nit, proveedor_direccion, proveedor_ciudad_id, proveedor_contacto, proveedor_email) VALUES (?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isssiss", $id_proveedor, $nombre_proveedor, $nit_proveedor, $direccion_proveedor, $ciudad_proveedor, $contacto_proveedor, $email_proveedor);
		$stmt-> execute();
		if ($stmt->affected_rows == 1) {

			$contacto_proveedor = '('.substr($contacto_proveedor, 0,3).') '.substr($contacto_proveedor, 3,3).'-'.substr($contacto_proveedor, 6,4);

			$response = array(
				"response" => "success",
				"id_proveedor" => $id_proveedor,
				"proveedor_nombre" => $nombre_proveedor,
				"proveedor_nit" => $nit_proveedor,
				"contacto_proveedor" => $contacto_proveedor,
				"direccion_proveedor" => $direccion_proveedor,
				"ciudad_nombre" => $ciudad_nombre,
				"email_proveedor" => $email_proveedor
			);
			
		}else{

			$response = array(
				"response" => "error",
				"error" => $stmt->error
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


		$id_proveedor = $_POST['id_proveedor'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE proveedores SET del = 1 WHERE id = ?");
		$stmt->bind_param("i", $id_proveedor);
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_proveedor" => $id_proveedor
			);
				
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

?>