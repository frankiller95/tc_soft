<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","36000");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","36000");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "count_all") {


	try {
		
		if (isset($_POST['id_usuario'])) {

			$id_usuario = $_POST['id_usuario'];
			$permiso_validaciones_pdtes = profile(25, $id_usuario);

			
			if($permiso_validaciones_pdtes == 1){

				$total_mis_validaciones = execute_scalar("SELECT COUNT(id) AS total FROM `prospectos` WHERE prospectos.id_estado_prospecto = 12 AND prospectos.id_usuario_validador = 0 AND prospectos.del = 0");

			}else{

				$total_mis_validaciones = 0;

			}

			$response = array(
				'response' => "success",
				"permiso_validaciones_pdtes" => $permiso_validaciones_pdtes,
				"total_mis_validaciones" => $total_mis_validaciones
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

}else if($_POST['action'] == "count_prospectos2"){

	try {

		//var_dump($_POST);
		$total_prospectos_old = $_POST['total_prospectos'];
		$total_prospectos = execute_scalar("SELECT COUNT(id) FROM prospectos WHERE prospectos.del = 0 AND prospectos.id_responsable_interno = 0");

		$cambio = 0;

		if($total_prospectos != $total_prospectos_old){

		    $cambio = 1;

		}

		$response = array(
			"total_prospectos_old" => $total_prospectos_old,
			"total_prospectos" => $total_prospectos,
			"cambio" => $cambio
		);

		//echo $total_prospectos;
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_mis_prospectos"){

	try {

		$id_usuario = $_POST['id_usuario'];

		$permiso_20 = profile(20, $id_usuario);
	    $response = array(
	    	"permiso_20" => $permiso_20
	    );

	    $misProspectosArray = array();

	    $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos.id_usuario_responsable, prospectos.fecha_registro, prospectos.id_plataforma, plataformas_credito.nombre_plataforma, prospectos.id_estado_prospecto, estados_prospectos.estado_prospecto, prospectos.id_estado_prospecto FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id  LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id WHERE prospectos.del = 0 AND prospectos.id_responsable_interno = $id_usuario ORDER BY prospectos.fecha_registro ASC";

	    $result = qry($query);

	    while ($row = mysqli_fetch_array($result)) {

	    	$id_prospecto = $row['id_prospecto'];
	    	$prospecto_cedula = $row['prospecto_cedula'];
	    	$prospecto_nombre_full = $row['prospecto_nombre'].' '.$row['prospecto_apellidos'];
	    	$id_usuario_responsable = $row['id_usuario_responsable'];
			$id_plataforma = $row['id_plataforma'];
			$nombre_plataforma = $row['nombre_plataforma'];
			$estado_prospecto = $row['estado_prospecto'];
			$id_estado_prospecto = $row['id_estado_prospecto'];

			if($estado_prospecto == '' && $id_plataforma == 1 || $id_plataforma == 2){
				$estado_prospecto = "PDTE. VALIDAR";
			}else if($id_plataforma == 3){
				$validate_solicitud = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE id_prospecto = $id_prospecto AND solicitudes.del = 0");
				if($validate_solicitud != 0){
					$estado_prospecto = execute_scalar("SELECT estados_solicitudes.mostrar FROM solicitudes LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id AND id_prospecto = $id_prospecto");
				}else{
					$estado_prospecto = "SIN SOLICITUD";
				}
			}else{

				$estado_prospecto = "SIN PLATAFORMA";
				$validate_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

				if($validate_estado_prospecto == 0){
					$estado_prospecto = "PDTE. VALIDAR";
				}else{
					$estado_prospecto = execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $validate_estado_prospecto");
				}
			}

	    	$validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");
			if ($validate_gane == 1) {
				$id_punto_gane = execute_scalar("SELECT id_punto_gane FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario_responsable");
				$creado_en = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE ID = $id_punto_gane");
			} else {
				$creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
			}
	    	$fecha_registro = $row['fecha_registro'];

	    	$newArray = array("validate_gane" => $validate_gane, "id_prospecto" => $id_prospecto, "prospecto_cedula" => $prospecto_cedula, "prospecto_nombre_full" => $prospecto_nombre_full, "creado_en" => $creado_en, "fecha_registro" => $fecha_registro, "nombre_plataforma" => $nombre_plataforma, "estado_prospecto" => $estado_prospecto, "id_plataforma" => $id_plataforma, "id_estado_prospecto" => $id_estado_prospecto);

	    	array_push($misProspectosArray, $newArray);
	    }


	    array_push($response, $misProspectosArray);
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_prospectos"){

	try {

		$response = array();

	    $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospectos.id_usuario_responsable, prospectos.fecha_registro FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.del = 0 AND prospectos.id_responsable_interno = 0 ORDER BY prospectos.fecha_registro ASC";

	    $result = qry($query);

	    while ($row = mysqli_fetch_array($result)) {
	    	$id_prospecto = $row['id_prospecto'];
	    	$prospecto_cedula = $row['prospecto_cedula'];
	    	$prospecto_nombre_full = $row['prospecto_nombre'].' '.$row['prospecto_apellidos'];
			$prospecto_numero_contacto = $row['prospecto_numero_contacto'];
	    	$id_usuario_responsable = $row['id_usuario_responsable'];

	    	$validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");
	    	if ($validate_gane == 1) {
				$id_punto_gane = execute_scalar("SELECT id_punto_gane FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario_responsable");
				$agencia = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE ID = $id_punto_gane");
				$cod = execute_scalar("SELECT COD FROM puntos_gane WHERE ID = $id_punto_gane");
				$creado_en = $cod.' - '.$agencia;
			} else {
				$creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
			}
	    	$fecha_registro = $row['fecha_registro'];

	    	$newArray = array("validate_gane" => $validate_gane, "id_prospecto" => $id_prospecto, "prospecto_cedula" => $prospecto_cedula, "prospecto_nombre_full" => $prospecto_nombre_full, "contacto" => $prospecto_numero_contacto, "creado_en" => $creado_en, "fecha_registro" => $fecha_registro);

	    	array_push($response, $newArray);
	    }
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_entregas_pdtes"){

	try {

		$response = array();

		$id_usuario = $_POST['id_usuario'];

		if(profile(36, $id_usuario)){
			
            $query = "SELECT solicitudes.id AS id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS fecha_entrega_format, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento FROM solicitudes_domiciliarios LEFT JOIN solicitudes ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN usuarios ON solicitudes_domiciliarios.id_domiciliario = usuarios.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE prospectos.id_medio_envio = 1 AND solicitudes.id_estado_solicitud = 6 AND solicitudes.id_estado_solicitud = 6 AND solicitudes.del = 0 AND solicitudes_domiciliarios.del = 0 ORDER BY solicitudes_domiciliarios.solicitud_fecha_entrega ASC";

        }else{

            $query = "SELECT solicitudes.id AS id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS fecha_entrega_format, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento FROM solicitudes_domiciliarios LEFT JOIN solicitudes ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN usuarios ON solicitudes_domiciliarios.id_domiciliario = usuarios.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE prospectos.id_medio_envio = 1 AND solicitudes.id_estado_solicitud = 6 AND solicitudes.del = 0 AND solicitudes_domiciliarios.del = 0 AND solicitudes_domiciliarios.id_domiciliario = $id_usuario ORDER BY solicitudes_domiciliarios.solicitud_fecha_entrega ASC";

        }

		//echo $query;
		//die();

	    $result = qry($query);

	    while ($row = mysqli_fetch_array($result)) {

	    	$id_solicitud = $row['id_solicitud'];
	    	$prospecto_cedula = $row['prospecto_cedula'];
	    	$prospecto_nombre_full = $row['prospecto_nombre'].' '.$row['prospecto_apellidos'];
	    	$domiciliario = $row['nombre'].' '.$row['apellidos'];
			$fecha_entrega_format = $row['fecha_entrega_format'];
			$solicitud_inicio_tiempo = $row['solicitud_inicio_tiempo'];
			$solicitud_final_tiempo = $row['solicitud_final_tiempo'];
			$prospecto_direccion = $row['prospecto_direccion'];
			$ciudad = $row['ciudad'];
			$departamento = $row['departamento'];
			$prospecto_numero_contacto = $row['prospecto_numero_contacto'];

	    	$newArray = array("id_solicitud" => $id_solicitud, "prospecto_cedula" => $prospecto_cedula, "prospecto_nombre_full" => $prospecto_nombre_full, "domiciliario" => $domiciliario, "fecha_entrega_format" => $fecha_entrega_format, "solicitud_inicio_tiempo" => $solicitud_inicio_tiempo, "solicitud_final_tiempo" => $solicitud_final_tiempo, "prospecto_direccion" => $prospecto_direccion, "ciudad" => $ciudad, "departamento" => $departamento, "prospecto_numero_contacto" => $prospecto_numero_contacto);

	    	array_push($response, $newArray);

	    }

	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);
	
}else if($_POST['action'] == "select_validaciones_pdtes"){
	
	try {

		$response = array();

	    $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, usuarios.nombre, usuarios.apellidos, prospectos.id_usuario_validador, CONCAT(ciudades.ciudad, '/', departamentos.departamento) AS ubicacion FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE prospectos.id_estado_prospecto = 12 AND prospectos.id_usuario_validador = 0 AND prospectos.del = 0";


	    $result = qry($query);

	    while ($row = mysqli_fetch_array($result)) {

	    	$id_prospecto = $row['id_prospecto'];
	    	$prospecto_cedula = $row['prospecto_cedula'];
	    	$prospecto = $row['prospecto_nombre'].' '.$row['prospecto_apellidos'];
	    	$asesor = $row['nombre'].' '.$row['apellidos'];
			$prospecto_numero_contacto = $row['prospecto_numero_contacto'];

			$id_usuario_validador = $row['id_usuario_validador'];

			$ubicacion = $row['ubicacion'];

	    	$newArray = array("id_prospecto" => $id_prospecto, "prospecto_cedula" => $prospecto_cedula, "prospecto" => $prospecto, "asesor" => $asesor, "prospecto_numero_contacto" => $prospecto_numero_contacto, "id_usuario_validador" => $id_usuario_validador, "ubicacion" => $ubicacion);

	    	array_push($response, $newArray);
	    }

	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "select_mis_validaciones"){

	try {
		
		$response = array();

		$id_usuario = $_POST['id_usuario'];

	    $query = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, usuarios.nombre, usuarios.apellidos, prospectos.id_usuario_validador,  prospectos.id_plataforma, prospectos.id_estado_prospecto, plataformas_credito.nombre_plataforma, estados_prospectos.estado_prospecto FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id WHERE prospectos.id_usuario_validador = $id_usuario AND prospectos.id_plataforma <> 3 AND prospectos.id_estado_prospecto <> 4 AND prospectos.del = 0";

	    $result = qry($query);

	    while ($row = mysqli_fetch_array($result)) {

	    	$id_prospecto = $row['id_prospecto'];
	    	$prospecto_cedula = $row['prospecto_cedula'];
	    	$prospecto = $row['prospecto_nombre'].' '.$row['prospecto_apellidos'];
	    	$asesor = $row['nombre'].' '.$row['apellidos'];
			$prospecto_numero_contacto = $row['prospecto_numero_contacto'];

			$id_usuario_validador = $row['id_usuario_validador'];

			$nombre_plataforma = $row['nombre_plataforma'];

			$estado_prospecto = $row['estado_prospecto'];

			$id_estado_prospecto = $row['id_estado_prospecto'];

			$id_plataforma = $row['id_plataforma'];

	    	$newArray = array("id_prospecto" => $id_prospecto, "prospecto_cedula" => $prospecto_cedula, "prospecto" => $prospecto, "asesor" => $asesor, "prospecto_numero_contacto" => $prospecto_numero_contacto, "id_usuario_validador" => $id_usuario_validador, "nombre_plataforma" => $nombre_plataforma, "estado_prospecto" => $estado_prospecto, "id_plataforma" => $id_plataforma, "id_estado_prospecto" => $id_estado_prospecto);

	    	array_push($response, $newArray);
	    }

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "select_dispositivo"){

	try {
		
		$id_solicitud = $_POST['id_solicitud'];
		$response = array();

		$query = "SELECT marcas.marca_producto, modelos.nombre_modelo, capacidades_telefonos.capacidad, colores_productos.color_desc FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id WHERE solicitudes.id = $id_solicitud";

		$result = qry($query);

		while ($row = mysqli_fetch_array($result)){

			$marca_producto = $row['marca_producto'];
			$nombre_modelo = $row['nombre_modelo'];
			$capacidad = $row['capacidad'];
			$color_desc = $row['color_desc'];

			$newArray = array("marca_producto" => $marca_producto, "nombre_modelo" => $nombre_modelo, "capacidad" => $capacidad, "color_desc" => $color_desc);

			array_push($response, $newArray);

		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "confirmar_entrega"){

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE solicitudes_domiciliarios SET entrega_confirmada = b'1', fecha_entrega_confirmada = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ?");
		$stmt->bind_param("i", $id_solicitud);
		$stmt-> execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt-> close();

		if ($rows == 1) {

			$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = 7 WHERE id = ?");
			$stmt->bind_param("i", $id_solicitud);
			$stmt-> execute();
			$rows2 = $stmt->affected_rows;
			$error2 = $stmt->error;
			$stmt-> close();

			if($rows2 == 1){

				$response = array('response' => "success",
					"id_solicitud" => $id_solicitud
				);

			}else{
				$response = array(
					"response" => "error",
					"error2" => $error2
				);
			}

		}else{

			$response = array(
				"response" => "error",
				"error" => $error
			);
		}

		$conn ->close();

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action']=="tomar_prospecto"){

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$validador = profile(25, $id_usuario);

		$conn = new mysqli($host, $user, $pass, $db);
		if($validador == 1){
			$stmt = $conn->prepare("UPDATE prospectos SET id_responsable_interno = ?, id_usuario_validador = ? WHERE id = ?");
			$stmt->bind_param("iii", $id_usuario, $id_usuario, $id_prospecto);
		}else{
			$stmt = $conn->prepare("UPDATE prospectos SET id_responsable_interno = ? WHERE id = ?");
			$stmt->bind_param("ii", $id_usuario, $id_prospecto);
		}
		$stmt-> execute();

		if ($stmt->affected_rows == 1) {

			$response = array('response' => "success",
				"id_prospecto" => $id_prospecto,
				"validador" => $validador
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

}else if($_POST['action'] == "validar_prospecto"){

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$validador_select = execute_scalar("SELECT id_usuario_validador FROM prospectos WHERE id = $id_prospecto");

		if($validador_select == 0 || $validador_select <> $id_usuario){
			
			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE prospectos SET id_usuario_validador = ? WHERE id = ?");
			$stmt->bind_param("ii", $id_usuario, $id_prospecto);
			$stmt-> execute();

			if($stmt->affected_rows == 1){

				$response = array(
					"response" => "success",
					"id_prospecto" => $id_prospecto,
					"insert" => 1
				);

			}else{

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);

			}

			$stmt->close();
			$conn->close();

		}else if($validador_select == $id_usuario){

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto,
				"insert" => 0
			);

		}else{

			$response = array(
				"response" => "validacion_proceso",
				"id_prospecto" => $id_prospecto
			);

		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "cargar_images_entrega"){

	try {

		// Imagen base64 enviada desde javascript en el formulario
		// En este caso, con PHP plano podriamos obtenerla usando :
		// $baseFromJavascript = $_POST['base64'];
		$images = $_POST['images'];

		$total_images = count($images);

		$id_solicitud = $_POST['id_solicitud'];

		//var_dump($_POST);

		//die();

		$filepath = "../documents/solicitudes/".$id_solicitud."/confirmaciones/"; // or image.jpg

		if (!file_exists($filepath)) {
			mkdir($filepath, 0777, true);
		}else{
			rmDir_rf($filepath);
			mkdir($filepath, 0777, true);
		}

		$index = 0;

		foreach ($images as $image){

			$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));


			$file = $filepath . $index. '.jpg';
			
			if (is_file($file)) {
				unlink($file);
			}
			
			// Finalmente guarda la imágen en el directorio especificado y con la informacion dada
			file_put_contents($file,$data);
			$index++;
		}

		if (file_exists($filepath)) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE solicitudes_domiciliarios SET entrega_confirmada = b'1', fecha_entrega_confirmada = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
			$stmt->bind_param("i", $id_solicitud);
			$stmt-> execute();
			$rows = $stmt->affected_rows;
			$error = $stmt->error;
			$stmt-> close();

			if ($rows == 1) {

				$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = 7 WHERE id = ?");
				$stmt->bind_param("i", $id_solicitud);
				$stmt-> execute();
				$rows2 = $stmt->affected_rows;
				$error2 = $stmt->error;
				$stmt-> close();

				if($rows2 == 1){

					$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

					$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 5 WHERE id = ?");
					$stmt->bind_param("i", $id_prospecto);
					$stmt-> execute();
					$rows3 = $stmt->affected_rows;
					$error3 = $stmt->error;
					$stmt-> close();

					if($rows3 == 1){

						$response = array('response' => "success",
							"id_solicitud" => $id_solicitud
						);

					}else{
						$response = array(
							"response" => "error",
							"error3" => $error3
						);
					}

				}else{
					$response = array(
						"response" => "error",
						"error2" => $error2
					);
				}

			}else{

				$response = array(
					"response" => "error",
					"error" => $error
				);
			}

			$conn ->close();
		}

		// Remover la parte de la cadena de texto que no necesitamos (data:image/png;base64,)
		// y usar base64_decode para obtener la información binaria de la imagen
		
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}else if($_POST['action'] == "select_rutas_gane"){

	//var_dump($_POST);
	//die();

	try {

		$id_usuario = $_POST['id_usuario'];

		$rutas_gane_array = array();

		$comentarios_array = array();

		if(profile(34, $id_usuario) == 1){

			$query1 = "SELECT rutas_gane.id AS id_ruta, rutas_gane.id_usuario AS id_usuario_ruta, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(rutas_gane.fecha_visita, '%m-%d-%Y') AS fecha_programada, puntos_gane.confirmado_capacitacion FROM rutas_gane LEFT JOIN puntos_gane ON rutas_gane.id_punto_gane = puntos_gane.ID LEFT JOIN usuarios ON rutas_gane.id_usuario = usuarios.id WHERE rutas_gane.del = 0 AND puntos_gane.confirmado_capacitacion = 0 ORDER BY fecha_programada desc";

		}else{

			$query1 = "SELECT rutas_gane.id AS id_ruta, rutas_gane.id_usuario AS id_usuario_ruta, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(rutas_gane.fecha_visita, '%m-%d-%Y') AS fecha_programada, puntos_gane.confirmado_capacitacion FROM rutas_gane LEFT JOIN puntos_gane ON rutas_gane.id_punto_gane = puntos_gane.ID LEFT JOIN usuarios ON rutas_gane.id_usuario = usuarios.id WHERE rutas_gane.id_usuario = $id_usuario AND rutas_gane.del = 0 AND puntos_gane.confirmado_capacitacion = 0 ORDER BY fecha_programada ASC";

		}
                                       $result1 = qry($query1);
                                        while ($row1 = mysqli_fetch_array($result1)) {

                                            $id_ruta = $row1['id_ruta'];
											$id_usuario_ruta = $row1['id_usuario_ruta'];
                                            $COD = $row1['COD'];
                                            $AGENCIA = $row1['AGENCIA'];
                                            $nombre = $row1['nombre'];
                                            $apellidos = $row1['apellidos'];
											$direccion = $row1['DIRECCION'];
                                            $fecha_programada = $row1['fecha_programada'];
                                            $confirmado_capacitacion = $row1['confirmado_capacitacion'];

											$validate_comentarios = execute_scalar("SELECT COUNT(id) AS total FROM comentarios_rutas_gane WHERE id_ruta_gane = $id_ruta AND del = 0");

											$new_array = array("id_ruta" => $id_ruta, "agencia" => $AGENCIA, "cod" => $COD, "direccion" => $direccion, "fecha" => $fecha_programada, "confirmado_capacitacion" => $confirmado_capacitacion, "id_usuario_ruta" => $id_usuario_ruta, "validate_comentarios" => $validate_comentarios);

											if($validate_comentarios != 0){

												$query2 = "SELECT comentarios_rutas_gane.id AS id_comentario, comentario_texto, comentarios_rutas_gane.fecha_registro, usuarios.nombre, usuarios.apellidos FROM comentarios_rutas_gane LEFT JOIN usuarios ON comentarios_rutas_gane.realizado_por = usuarios.id WHERE id_ruta_gane = $id_ruta AND comentarios_rutas_gane.del = 0 ORDER BY comentarios_rutas_gane.fecha_registro ASC";
                                                        $result2 = qry($query2);
                                                        while ($row2 = mysqli_fetch_array($result2)) {
                                                            $id_comentario = $row2['id_comentario'];
                                                            $comentario_texto = $row2['comentario_texto'];
                                                            $fecha_registro = $row2['fecha_registro'];
                                                            $nombre_comentario = $row2['nombre'] . ' ' . $row2['apellidos'];

															$new_array_2 = array("id_comentario" => $id_comentario, "comentario_texto" => $comentario_texto, "fecha_registro" => $fecha_registro, "nombre_comentario" => $nombre_comentario);

															array_push($comentarios_array, $new_array_2);
														}

													array_push($new_array, $comentarios_array);

											}

											array_push($rutas_gane_array, $new_array);

										}

										$response = array(
											"response" => "success"
										);

										array_push($response, $rutas_gane_array);



	} catch (Exception $e) {
		$response = array(
			'error' => $e-> getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "update_capacitado"){

	try {

		$id_ruta = $_POST['id_ruta'];
		$id_usuario = $_POST['id_usuario'];

		$id_punto_gane = execute_scalar("SELECT id_punto_gane FROM rutas_gane WHERE id = $id_ruta");

		$confirmacion_capacitado = $_POST['confirmacion_capacitado'];

		/*estdos rutas:
		
		0 - FALTA
		1- CAPACITADO
		2- CERRADO SIN CAPACITACIÓN 
		
		*/

		$capacitado = 1;

		$profile_34 = profile(34, $id_usuario);

		$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE puntos_gane SET confirmado_capacitacion = ? WHERE id = ?");
			$stmt->bind_param("ii", $confirmacion_capacitado, $id_punto_gane);
			$stmt-> execute();
			$rows = $stmt->affected_rows;
			$error = $stmt->error;
			$stmt-> close();

			if($rows == 1){

				$response = array(
					"response" => "success",
					"confirmacion_capacitado" => $confirmacion_capacitado,
					"profile_34" => $profile_34,
					"id_ruta" => $id_ruta
				);

			}else{

				$response = array(
					"response" => "error",
					"error" => $error
				);

			}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);

	}

	echo json_encode($response);

}


?>