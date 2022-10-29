<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");


session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

date_default_timezone_set('America/Bogota');

if ($_POST['action'] == "show_prospectos_dia") {

	try {

		$id_usuario = $_POST['id_usuario'];

		$hoy = getdate();

		$response = array();

		$zero = 0;

		if ($hoy['mon'] < 10) {
			$hoy['mon'] = $zero . $hoy['mon'];
		}

		if ($hoy['mday'] < 10) {
			$hoy['mday'] = $zero . $hoy['mday'];
		}

		$fecha_actual = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];

		$fecha_actual = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'].' 00:10:00';

		$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno, prospectos.fecha_registro FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id WHERE prospectos.del = 0 AND prospectos.id_usuario_responsable = $id_usuario AND prospectos.fecha_registro >= '$fecha_actual 00:00:00' AND prospectos.fecha_registro <= '$fecha_actual 23:59:00' ORDER BY prospectos.fecha_registro DESC";
		$result1 = qry($query1);
		while ($row1 = mysqli_fetch_array($result1)) {

			$id_prospecto = $row1['id_prospecto'];
			$prospecto_cedula = $row1['prospecto_cedula'];
			$prospecto_nombre = $row1['prospecto_nombre'];
			$prospecto_apellidos = $row1['prospecto_apellidos'];
			$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
			$prospecto_numero_contacto = substr($prospecto_numero_contacto, 0, 3) . '-' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
			$contacto_w = $row1['contacto_w'];
			$ciudad = $row1['ciudad'];
			$departamento = $row1['departamento'];
			$marca_producto = $row1['marca_producto'];

			$id_usuario_responsable = $row1['id_usuario_responsable'];
			$id_responsable_interno = $row1['id_responsable_interno'];

			$fecha_registro = $row1['fecha_registro'];

			$newArray = array(
				"id_prospecto" => $id_prospecto,
				"prospecto_cedula" => $prospecto_cedula,
				"prospecto_nombre" => $prospecto_nombre,
				"prospecto_apellidos" => $prospecto_apellidos,
				"prospecto_numero_contacto" => $prospecto_numero_contacto,
				"contacto_w" => $contacto_w,
				"ciudad" => $ciudad,
				"departamento" => $departamento,
				"marca_producto" => $marca_producto,
				"id_usuario_responsable" => $id_usuario_responsable,
				"id_responsable_interno" => $id_responsable_interno,
				"fecha_registro" => $fecha_registro
			);

			array_push($response, $newArray);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "select_info_prospecto") {

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$response = array();

		$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno, prospectos.fecha_registro, prospecto_detalles.ciudad_id, modelos_prospectos.id_marca FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id WHERE prospectos.id = $id_prospecto";
		$result1 = qry($query1);
		while ($row1 = mysqli_fetch_array($result1)) {

			$id_prospecto = $row1['id_prospecto'];
			$prospecto_cedula = $row1['prospecto_cedula'];
			$prospecto_nombre = $row1['prospecto_nombre'];
			$prospecto_apellidos = $row1['prospecto_apellidos'];
			$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
			$prospecto_numero_contacto = substr($prospecto_numero_contacto, 0, 3) . '-' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
			$contacto_w = $row1['contacto_w'];
			$ciudad = $row1['ciudad'];
			$departamento = $row1['departamento'];
			$marca_producto = $row1['marca_producto'];

			$id_usuario_responsable = $row1['id_usuario_responsable'];
			$id_responsable_interno = $row1['id_responsable_interno'];

			$fecha_registro = $row1['fecha_registro'];

			$ciudad_id = $row1['ciudad_id'];

			$id_marca = $row1['id_marca'];

			$newArray = array(

				"id_prospecto" => $id_prospecto,
				"prospecto_cedula" => $prospecto_cedula,
				"prospecto_nombre" => $prospecto_nombre,
				"prospecto_apellidos" => $prospecto_apellidos,
				"prospecto_numero_contacto" => $prospecto_numero_contacto,
				"contacto_w" => $contacto_w,
				"ciudad" => $ciudad,
				"departamento" => $departamento,
				"marca_producto" => $marca_producto,
				"id_usuario_responsable" => $id_usuario_responsable,
				"id_responsable_interno" => $id_responsable_interno,
				"fecha_registro" => $fecha_registro,
				"ciudad_id" => $ciudad_id,
				"id_marca" => $id_marca

			);

			array_push($response, $newArray);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "update_prospecto") {

	try {

		$cedula_prospecto = $_POST['cedula_prospecto_edit'];
		$nombre_prospecto = $_POST['nombre_prospecto_edit'];
		$apellidos_prospecto = $_POST['apellidos_prospecto_edit'];
		$contacto_prospecto = $_POST['contacto_prospecto_edit'];
		$contacto_prospecto = substr($contacto_prospecto, 0, 3) . substr($contacto_prospecto, 4, 3) . substr($contacto_prospecto, 8, 4);
		$contacto_w = $_POST['contacto2_whatsaap_edit'];
		$contacto_w = substr($contacto_w, 0, 3) . substr($contacto_w, 4, 3) . substr($contacto_w, 8, 4);
		$ciudad_prospecto = $_POST['ciudad_prospecto_edit'];
		$marca_prospecto = $_POST['marca_prospecto_edit'];
		$id_usuario = $_POST['id_usuario'];
		$id_prospecto = $_POST['id_prospecto'];
		$id_prospecto_detalles = execute_scalar("SELECT prospecto_detalles.id FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");
		$id_modelos_prospectos = execute_scalar("SELECT modelos_prospectos.id FROM modelos_prospectos WHERE id_prospecto = $id_prospecto");
		$id_responsable_interno = execute_scalar("SELECT id_responsable_interno FROM prospectos WHERE id = $id_prospecto");

		$validate = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE prospecto_cedula = $cedula_prospecto AND prospectos.del = 0 AND prospectos.id <> $id_prospecto");

		if ($validate == 0) {

			qry("DELETE FROM prospectos WHERE id = $id_prospecto");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO prospectos (id, prospecto_cedula, id_usuario_responsable, id_responsable_interno) VALUES (?, ?, ?, ?)");
			$stmt->bind_param("isii", $id_prospecto, $cedula_prospecto, $id_usuario, $id_responsable_interno);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {
				/*
				$query_insert = "INSERT INTO prospecto_detalles (id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, ciudad_id, fecha_registro) VALUES ($id_prospecto, '$nombre_prospecto', '$apellidos_prospecto', '$contacto_prospecto', '$contacto_w', $ciudad_prospecto, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))";
				echo $query_insert;
				die();
				*/
				$stmt->close();

				qry("DELETE FROM prospecto_detalles WHERE id = $id_prospecto_detalles");

				$stmt = $conn->prepare("INSERT INTO prospecto_detalles (id, id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, ciudad_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
				$stmt->bind_param("iissssi", $id_prospecto_detalles, $id_prospecto, $nombre_prospecto, $apellidos_prospecto, $contacto_prospecto, $contacto_w, $ciudad_prospecto);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {


					qry("DELETE FROM modelos_prospectos WHERE id = $id_modelos_prospectos");

					qry("INSERT INTO modelos_prospectos (id, id_prospecto, id_marca, fecha_registro) VALUES ($id_modelos_prospectos, $id_prospecto, $marca_prospecto, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");

					$ciudad_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_prospecto");

					$id_departamento = execute_scalar("SELECT id_departamento FROM ciudades WHERE id = $ciudad_prospecto");

					$departamento_nombre = execute_scalar("SELECT departamento FROM departamentos WHERE id = $id_departamento");

					$marca_nombre = execute_scalar("SELECT marca_producto FROM marcas WHERE id = $marca_prospecto");

					$fecha_registro = execute_scalar("SELECT fecha_registro FROM prospectos WHERE id = $id_prospecto");

					$contacto_prospecto = substr($contacto_prospecto, 0, 3) . '-' . substr($contacto_prospecto, 3, 3) . '-' . substr($contacto_prospecto, 6, 4);

					//$contacto_w = substr($contacto_w, 0,3).'-'.substr($contacto_w, 3,3).'-'.substr($contacto_w, 6,4);

					$response = array(
						"response" => "success",
						"id_prospecto" => $id_prospecto,
						"prospecto_cedula" => $cedula_prospecto,
						"prospecto_nombre" => $nombre_prospecto,
						"prospecto_apellidos" => $apellidos_prospecto,
						"prospecto_numero_contacto" => $contacto_prospecto,
						"contacto_w" => $contacto_w,
						"ciudad" => $ciudad_nombre,
						"departamento" => $departamento_nombre,
						"marca_producto" => $marca_nombre,
						"fecha_registro" => $fecha_registro,
						"id_responsable_interno" => $id_responsable_interno
					);
				} else {

					$response = array(
						"response" => "error",
						"error" => $stmt->error
					);
				}
			} else {

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);
			}

			$stmt->close();
			$conn->close();
		} else {

			$response = array('response' => "repetida");
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
	
} else if ($_POST['action'] == "enviar_sms") {

	try {

		$celular_prospecto = $_POST['celular_prospecto'];

		$id_usuario = $_POST['id_usuario'];

		$digitos_contacto = strlen($celular_prospecto);

		//alimentamos el generador de aleatorios
		mt_srand(time());
		//generamos un número aleatorio
		$codigo = mt_rand(100000, 999999);

		if ($celular_prospecto != "" && $digitos_contacto == 12) {

			$celular_prospecto = substr($celular_prospecto, 0, 3) . substr($celular_prospecto, 4, 3) . substr($celular_prospecto, 8, 4);

			$validate_confirmacion = execute_scalar("SELECT COUNT(id) AS total FROM confirmacion_prospectos WHERE id_usuario = $id_usuario AND proceso = 1 AND del = 0");

			if($validate_confirmacion == 0){

				$proceso = "insert";

			}else{

				$proceso = "update";

			}

			$conn = new mysqli($host, $user, $pass, $db);
			if($proceso == "insert"){

				$stmt = $conn->prepare("INSERT INTO confirmacion_prospectos (numero_contacto_confirmacion, codigo, fecha_registro, fecha_vencimiento, estado_confirmacion, proceso, id_usuario) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), b'0', 1, ?)");
				$stmt->bind_param("sii", $celular_prospecto, $codigo, $id_usuario);

			}else{

				$id_confirmacion = execute_scalar("SELECT id FROM confirmacion_prospectos WHERE id_usuario = $id_usuario AND proceso = 1 AND del = 0");
				$stmt = $conn->prepare("UPDATE confirmacion_prospectos SET numero_contacto_confirmacion = ?, codigo = ?, fecha_registro = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), proceso = 1 WHERE id = ?");
				$stmt->bind_param("sii", $celular_prospecto, $codigo, $id_confirmacion);

			}
			
			$stmt->execute();

			if($stmt->affected_rows == 1){

				if($proceso == "insert"){
					$id_confirmacion = $stmt->insert_id;
				}

				$validate = 1;

				$proveedor_sms = execute_scalar("SELECT envios_sms_gane FROM configuraciones WHERE id = 1");

				if($proveedor_sms == 0){

					include('../includes/httpPHPAltiria.php');

					$altiriaSMS = new AltiriaSMS();
					//$altiriaSMS->setUrl("http://www.altiria.net/api/http");
					$altiriaSMS->setLogin('soporte@tucelular.net.co');
					$altiriaSMS->setPassword('SOPORTE9529');

					//$sDestination = '346xxxxxxxx';
					$sDestination = '57' . $celular_prospecto;

					$respuesta = $altiriaSMS->sendSMS($sDestination, 'Codigo ' . $codigo . ' Autoriza a TCShop consultar historial crediticio y aceptas nuestras politicas de tratamiento de datos. ver politicas https://acortar.link/I78w3p');

				}else if($proveedor_sms == 1){

					$message = 'Codigo ' . $codigo . ' Autoriza a TCShop consultar historial crediticio y aceptas nuestras politicas de tratamiento de datos. ver politicas';

					$mifecha = date('Y-m-d H:i:s'); 
					$NuevaFecha = strtotime('+20 seconds', strtotime ($mifecha));
					$date_to_send = date('Y-m-d H:i:s' , $NuevaFecha); 

					$respuesta = post_aldeamo($message, $celular_prospecto, $date_to_send);


				}

				//echo $respuesta;
				//die();

				if ($respuesta == $validate) {

					$response = array(
						"response" => "success",
						"id_confirmacion_prospecto" => $id_confirmacion,
						"respuesta" => $respuesta
					);

				} else {

					$response = array("response" => "error_sms", "respuesta" => $respuesta);
					echo json_encode($response);
					die();

				}


			}else{

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);

			}

		} else {

			$response = array(
				"response" => "celular"
			);
		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "mensaje_prospecto") {

	try {

		$celular_prospecto = $_POST['contacto_prospecto_1'];
		$codigo = $_POST['codigo_confirmacion'];

		$id_confirmacion = $_POST['id_confirmacion'];

		//var_dump($_POST);
		//die();

		if($id_confirmacion != ''){

			$codigo_real = execute_scalar("SELECT codigo FROM confirmacion_prospectos WHERE id = $id_confirmacion");

			$codigo_provisional = 1234;

			if ($codigo_real != "") {

				if ($codigo != "") {

					//if ($codigo == $codigo_real || $codigo == $codigo_provisional) {
					if ($codigo == $codigo_real) {

						$query = "UPDATE confirmacion_prospectos SET proceso = 2 WHERE id = $id_confirmacion";
						$result = qry($query);

						if ($result) {
							$response = array(
								"response" => "success",
								"id_confirmacion" => $id_confirmacion
							);
						} else {
							$response = array(
								"response" => "error"
							);
						}
						
					} else {

						$response = array(
							"response" => "codigo_incorrecto"
						);
					}
				} else {
					$response = array(
						"response" => "falta_codigo"
					);
				}
			} else {

				$response = array(
					"response" => "no_codigo",
					"numero" => 2
				);
			}

		}else{

			$response = array(
				"response" => "no_codigo",
				"numero" => 1
			);
		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
	
} else if ($_POST['action'] == "select_contacto") {

	try {

		$id_confirmacion = $_POST['id_confirmacion'];

		$celular_prospecto = execute_scalar("SELECT numero_contacto_confirmacion FROM confirmacion_prospectos WHERE id = $id_confirmacion");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE confirmacion_prospectos SET proceso = 5 WHERE id = ?");
		$stmt->bind_param("i", $id_confirmacion);
		$stmt->execute();
		$stmt->close();
		$conn->close();

		if ($celular_prospecto != '') {

			$celular_prospecto = substr($celular_prospecto, 0, 3) . '-' . substr($celular_prospecto, 3, 3) . '-' . substr($celular_prospecto, 6, 4);

			$response = array(
				"response" => "success",
				"id_confirmacion" => $id_confirmacion,
				"celular_prospecto" => $celular_prospecto
			);
		} else {

			$response = array(
				"response" => "error"
			);
		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "next_process") {
	try {

		$id_usuario = $_POST['id_usuario'];
		$id_confirmacion = $_POST['id_confirmacion'];
		$foto_tipo = $_POST['foto_tipo'];

		if ($foto_tipo == 0) {
			$proceso = 2;
		} else if ($foto_tipo == 1) {
			$proceso = 3;
		} else if ($foto_tipo == 2) {
			$proceso = 4;
		}

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE confirmacion_prospectos SET proceso = ? WHERE id = ?");
		$stmt->bind_param("ii", $proceso, $id_confirmacion);
		$stmt->execute();

		if($stmt->affected_rows == 1){

			$response = array(
				"response" => "success",
				"id_confirmacion" => $id_confirmacion
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

}else if($_POST['action'] == "reset_proceso"){
	
	try {

		$id_usuario = $_POST['id_usuario'];

		$id_confirmacion = execute_scalar("SELECT confirmacion_prospectos.id FROM confirmacion_prospectos WHERE confirmacion_prospectos.id_usuario = $id_usuario AND estado_confirmacion = 0 AND del = 0");
		//echo $id_confirmacion;
		//die();
		$numero_vacio = '';

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE confirmacion_prospectos SET proceso = 1, numero_contacto_confirmacion = ? WHERE id = ?");
		$stmt->bind_param("si", $numero_vacio, $id_confirmacion);
		$stmt->execute();
		if($stmt->affected_rows == 1){

			$route = '../documents/prospects/'.$id_confirmacion.'/';
			$route1 = '../documents/prospects/'.$id_confirmacion;

			qry("UPDATE imagenes_prospectos SET del = 1 WHERE id_confirmacion = $id_confirmacion");

			if(file_exists($route)){

				rmDir_rf($route1);

			}

			if(!file_exists($route)){

				$response = array(
					"response" => "success",
					"id_confirmacion" => "id_confirmacion"
				);

			}else{

				$response = array(
					"response" => "error"
				);

			}

		}else{

			$response = array(
				"response" => "error",
				"error" => $stmt->error
			);

		}

		$stmt->close();
		$conn->close();

	} catch (\Throwable $th) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "insertar_prospecto"){

	try {

		//var_dump($_POST);
		//die();
		$nombre_prospecto = $_POST['nombre_prospecto'];
		$apellidos_prospecto = $_POST['apellidos_prospecto'];
		$contacto_prospecto = $_POST['contacto_prospecto'];
		$contacto_prospecto = substr($contacto_prospecto, 0, 3).substr($contacto_prospecto, 4, 3).substr($contacto_prospecto, 8, 4);
		$id_usuario = $_POST['id_usuario'];
		
		$query_test = "INSERT INTO `prospectos` (`id`, `prospecto_cedula`, `id_usuario_responsable`, `id_responsable_interno`, `id_confirmacion`, `id_plataforma`, `id_usuario_validador`, `id_estado_prospecto`, `imei_referencia`, `id_medio_envio`, `confirmar_rechazado`, `confirmar_aprobado`, `resultado_dc_prospecto`, `id_prospecto_creacion`, `fecha_registro`, `del`, `ultimo_cambio`) VALUES (NULL, '0', $id_usuario, '0', '0', '0', '0', '0', '0', '0', b'0', b'0', '0', '0', current_timestamp(), b'0', current_timestamp())";
		//echo $query_test;
		//die();
		/*
		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO `prospectos` (`id`, `prospecto_cedula`, `id_usuario_responsable`) VALUES (NULL, 0, ?)");
		$stmt->bind_param("i", $id_usuario);
		$stmt->execute();
		*/
		$result = qry($query_test);

		if($result){

			$id_prospecto = execute_scalar("SELECT MAX(id) FROM prospectos");
			//echo $id_prospecto;
			//die();
			/*
			$query2 = "INSERT INTO `prospecto_detalles` (`id`, `id_prospecto`, `prospecto_nombre`, `prospecto_apellidos`, `prospecto_numero_contacto`, `contacto_w`, `prospecto_email`, `prospecto_sexo`, `prospecto_dob`, `prospecto_direccion`, `ciudad_id`, `fecha_exp`, `id_ciudad_exp`, `id_referencia`, `inicial_referencia`, `observacion_prospecto`, `fecha_registro`, `del`, `ultimo_cambio`) VALUES (NULL, $id_prospecto, $nombre_prospecto, $apellidos_prospecto, $contacto_prospecto, '', '', NULL, NULL, '', '0', NULL, '0', '0', '0', NULL, current_timestamp(), b'0', current_timestamp())";
			$result2 = qry($query2);
			*/
			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO `prospecto_detalles` (`id`, `id_prospecto`, `prospecto_nombre`, `prospecto_apellidos`, `prospecto_numero_contacto`, `contacto_w`, `prospecto_email`, `prospecto_sexo`, `prospecto_dob`, `prospecto_direccion`, `ciudad_id`, `fecha_exp`, `id_ciudad_exp`, `id_referencia`, `inicial_referencia`, `observacion_prospecto`, `fecha_registro`, `del`, `ultimo_cambio`) VALUES (NULL, ?, ?, ?, ?, '', '', NULL, NULL, '', '0', NULL, '0', '0', '0', NULL, current_timestamp(), b'0', current_timestamp());");
			$stmt->bind_param("isss", $id_prospecto, $nombre_prospecto, $apellidos_prospecto, $contacto_prospecto);
			$stmt->execute();
			if($stmt->affected_rows == 1){

				$id_detalles = $stmt->insert_id;

				$response = array(
					"response" => "success",
					"id_prospecto" => $id_prospecto,
					"id_detalles" => $id_detalles
				);

				

			}else{

				$response = array(
					"response" => "error2",
					"error2" => $stmt->error
				);

			}

			$stmt->close();
			$conn->close();

		}else{

			$response = array(
				"response" => "error"
			);

		}

	} catch (\Throwable $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}
