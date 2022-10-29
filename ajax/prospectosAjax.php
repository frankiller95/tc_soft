<?php


//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "subir_img_prospecto") {

	try {

		$foto = $_POST['foto'];
		$id_confirmacion = $_POST['id_confirmacion'];
		$type = $_POST['type'];

		$route = '../documents/prospects/' . $id_confirmacion . '/';
		$route2 = './documents/prospects/' . $id_confirmacion . '/';
		$pictureFileName = $id_confirmacion . '-' . $type . '.jpg';
		$foto = str_replace('data:image/png;base64,', '', $foto);
		$foto = str_replace(' ', '+', $foto);
		$data = base64_decode($foto);
		if (!file_exists($route)) {
			mkdir($route, 0777, true);
		}
		$file = $route . $pictureFileName;
		if (is_file($route . $pictureFileName)) {
			unlink($file);
		}
		file_put_contents($file, $data);

		if (is_file($route . $pictureFileName)) {

			$img_nombre_archivo = $id_confirmacion . '-' . $type;

			$tipo_img = "FRONTAL";

			if ($type == 1) {
				$tipo_img = "ATRAS";
			} else if ($type == 2) {
				$tipo_img = "SELFIE";
			}

			$img_ext = "jpg";

			$validate_save = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND tipo_img = '$tipo_img' AND del = 0");

			if ($validate_save == 0) {
				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("INSERT INTO `imagenes_prospectos`(`id_confirmacion`, `imagen_nombre_archivo`, `tipo_img`, `imagen_extension`, `fecha_registro`) VALUES (?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("isss", $id_confirmacion, $img_nombre_archivo, $tipo_img, $img_ext);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$id_imagen_prospecto = $stmt->insert_id;

					$response = array(
						"response" => "success",
						"full_route" => $route2 . $pictureFileName,
						"id_confirmacion" => $id_confirmacion,
						"type" => $type
					);
				} else {

					$respose = array(
						"response" => "error",
						"error" => $stmt->error
					);
				}
			} else {

				$response = array(
					"response" => "success",
					"full_route" => $route2 . $pictureFileName,
					"id_confirmacion" => $id_confirmacion,
					"type" => $type
				);
			}
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

} else if ($_POST['action'] == "insertar_prospecto") {

	try {

		$cedula_prospecto = $_POST['cedula_prospecto'];
		$nombre_prospecto = $_POST['nombre_prospecto'];
		$apellidos_prospecto = $_POST['apellidos_prospecto'];
		$contacto_prospecto = $_POST['contacto_prospecto'];
		$contacto_prospecto = substr($contacto_prospecto, 0, 3) . substr($contacto_prospecto, 4, 3) . substr($contacto_prospecto, 8, 4);
		$ciudad_prospecto = $_POST['ciudad_prospecto'];
		$id_usuario = $_POST['id_usuario'];
		$contacto_w = $_POST['contacto2_whatsaap'];
		if($contacto_w != ''){
			$contacto_w = substr($contacto_w, 0, 3) . substr($contacto_w, 4, 3) . substr($contacto_w, 8, 4);
		}else{
			$contacto_w = '';
		}
		$marca_prospecto = $_POST['marca_prospecto'];
		$charContacto = strlen($contacto_prospecto);
		$charWhatsaap = strlen($contacto_w);
		$id_confirmacion = $_POST['id_confirmacion3'];

		$nombre_marca = execute_scalar("SELECT marca_producto FROM marcas WHERE id = $marca_prospecto");

		$texto_creacion = "CLIENTE INTERESADO EN UN DISPOSITIVO ".$nombre_marca;

		//validamos los caracteres del numero celular;
		if ($charContacto >= 10) {

			$validate_cedula_prospectos = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE prospecto_cedula = $cedula_prospecto AND del = 0");

			if ($validate_cedula_prospectos != 0) {

				$id_prospecto = execute_scalar("SELECT prospectos.id FROM prospectos WHERE prospecto_cedula = $cedula_prospecto AND del = 0");
				$validate_solicitudes_activas_prospecto = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE id_prospecto = $id_prospecto AND solicitudes.id_estado_solicitud <> 8 AND solicitudes.del = 0");

				if ($validate_solicitudes_activas_prospecto != 0) {
					$response = array(
						"response" => "registrada"
					);

					echo json_encode($response);
					die();

				} else {

					$proceso = "update";

				}

				 echo $proceso;
				 die();

			} else {

				$proceso = "insert";
			}

			$conn = new mysqli($host, $user, $pass, $db);
			if($proceso == "update"){
				$stmt = $conn->prepare("UPDATE prospectos SET id_usuario_responsable = ?, id_responsable_interno = 0, id_confirmacion = ?, fecha_registro = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
				$stmt->bind_param("iii", $id_usuario, $id_confirmacion, $id_prospecto);
			}else{
				$stmt = $conn->prepare("INSERT INTO prospectos (prospecto_cedula, id_usuario_responsable, id_confirmacion, fecha_registro) VALUES (?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("sii", $cedula_prospecto, $id_usuario, $id_confirmacion);
			}
			$stmt->execute();
			if ($stmt->affected_rows == 1) {

				if($proceso == "insert"){

					$id_prospecto = $stmt->insert_id;

				}else{

					$id_detalles = execute_scalar("SELECT prospecto_detalles.id FROM prospecto_detalles WHERE id_prospecto = $id_prospecto AND del = 0");

					qry("DELETE FROM prospecto_detalles WHERE id = $id_detalles");

				}

				$stmt->close();

				if($proceso == "insert"){

					$stmt = $conn->prepare("INSERT INTO prospecto_detalles (id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, ciudad_id, observacion_prospecto, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("issssis", $id_prospecto, $nombre_prospecto, $apellidos_prospecto, $contacto_prospecto, $contacto_w, $ciudad_prospecto, $texto_creacion);

				}else{

					$stmt = $conn->prepare("INSERT INTO prospecto_detalles (id, id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, ciudad_id, observacion_prospecto, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("iissssis", $id_detalles, $id_prospecto, $nombre_prospecto, $apellidos_prospecto, $contacto_prospecto, $contacto_w, $ciudad_prospecto, $texto_creacion);
				}
				
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					qry("DELETE FROM modelos_prospectos WHERE id_prospecto = $id_prospecto");

                    qry("INSERT INTO modelos_prospectos (id_prospecto, id_marca, fecha_registro) VALUES ($id_prospecto, $marca_prospecto, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");

					
					$stmt = $conn->prepare("UPDATE confirmacion_prospectos SET proceso = 6, estado_confirmacion = 1 WHERE id = ?");
                    $stmt->bind_param("i", $id_confirmacion);
                    $stmt->execute();

                    if ($stmt->affected_rows == 1) {

						$hoy = getdate();

						$zero = 0;

						if ($hoy['mon'] < 10) {
							$hoy['mon'] = $zero . $hoy['mon'];
						}

						if ($hoy['mday'] < 10) {
							$hoy['mday'] = $zero . $hoy['mday'];
						}

						$fecha_actual = $hoy['year'] . '-' . $hoy['mon'] . '-' . $hoy['mday'];

						$total_prospectos_cont = execute_scalar("SELECT COUNT(id) AS TOTAL FROM prospectos WHERE prospectos.del = 0 AND prospectos.id_usuario_responsable = $id_usuario AND prospectos.fecha_registro >= '$fecha_actual 00:00:00' AND prospectos.fecha_registro <= '$fecha_actual 23:59:00' ORDER BY prospectos.fecha_registro DESC");

                        $response = array(
                            "response" => "success",
                            "total_prospectos" => $total_prospectos_cont
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
				
			} else {

				$response = array(
					"response" => "error",
					"error" => $stmt->error
				);
			}


		} else {

			$response = array(
				"response" => "celular_contacto"
			);
		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "eliminar_prospecto") {
	try {

		$id_prospecto = $_POST['id_prospecto'];

		$validate_solicitudes = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE id_prospecto = $id_prospecto AND solicitudes.del = 0");

		if ($validate_solicitudes == 0) {

			$id_confirmacion = execute_scalar("SELECT id_confirmacion FROM prospectos WHERE id = $id_prospecto");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE prospectos SET del = 1 WHERE id = ?");
			$stmt->bind_param("i", $id_prospecto);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$stmt->close();

				$stmt = $conn->prepare("UPDATE prospecto_detalles SET del = 1 WHERE id_prospecto = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();
				$stmt->close();

				$stmt = $conn->prepare("UPDATE modelos_prospectos SET del = 1 WHERE id_prospecto = ?");
				$stmt->bind_param("i", $id_confirmacion);
				$stmt->execute();
				$stmt->close();
				$stmt = $conn->prepare("UPDATE imagenes_prospectos SET del = 1 WHERE id_confirmacion = ?");
				$stmt->bind_param("i", $id_confirmacion);
				$stmt->execute();
				$stmt->close();

				$stmt = $conn->prepare("UPDATE imagenes_prospectos SET del = 1 WHERE id_confirmacion = ?");
				$stmt->bind_param("i", $id_confirmacion);
				$stmt->execute();
				$stmt->close();

				$response = array(
					"response" => "success",
					"id_prospecto" => $id_prospecto
				);
			} else {

				$stmt->close();

				$response = array(
					"response" => "error"
				);
			}


			$conn->close();
		} else {

			$response = array(
				"response" => "solicitues_activas"
			);
		}
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "select_imagenes") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$imagenesp_array = array();

		$nombres = execute_scalar("SELECT prospecto_detalles.prospecto_nombre FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

		$apellidos = execute_scalar("SELECT prospecto_detalles.prospecto_apellidos FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

		$nombre_prospecto = $nombres . ' ' . $apellidos;

		$response = array(
			"response" => "success",
			"nombre_prospecto" => $nombre_prospecto
		);

		$query = "SELECT imagenes_prospectos.id AS id_imagen, imagenes_prospectos.imagen_nombre_archivo, imagenes_prospectos.tipo_img, imagenes_prospectos.imagen_extension FROM imagenes_prospectos WHERE imagenes_prospectos.id_prospecto = $id_prospecto AND imagenes_prospectos.del = 0 ORDER BY imagen_nombre_archivo ASC";

		$result = qry($query);
		while ($row = mysqli_fetch_array($result)) {
			$id_imagen = $row['id_imagen'];
			$imagen_nombre_archivo = $row['imagen_nombre_archivo'];
			$tipo_img = $row['tipo_img'];
			$imagen_extension = $row['imagen_extension'];
			$newArray = array("id_imagen" => $id_imagen, "imagen_nombre_archivo" => $imagen_nombre_archivo, "tipo_img" => $tipo_img, "imagen_extension" => $imagen_extension);
			array_push($imagenesp_array, $newArray);
		}

		array_push($response, $imagenesp_array);
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "cargar_imagenp") {

	try {

		$id_prospecto_img = $_POST['id_prospecto_img'];
		$id_confirmacion = execute_scalar("SELECT id_confirmacion FROM imagenes_prospectos WHERE id = $id_prospecto_img");
		$imagen_nombre_archivo = execute_scalar("SELECT imagen_nombre_archivo FROM imagenes_prospectos WHERE id = $id_prospecto_img");
		$tipo_img = execute_scalar("SELECT tipo_img FROM imagenes_prospectos WHERE id = $id_prospecto_img");
		$imagen_extension = execute_scalar("SELECT imagen_extension FROM imagenes_prospectos WHERE id = $id_prospecto_img");

		$route = '../documents/prospects/' . $id_confirmacion . '/';

		if ($_FILES['file']['type'] == "image/jpeg") {

			$extension = "jpg";
		} else if ($_FILES['file']['type'] == "image/png") {

			$extension = "png";
		} else {

			$response = array(
				"response" => "tipo_incorrecto"
			);

			echo json_encode($response);
			die();
		}


		unlink($route . $imagen_nombre_archivo . '.' . $imagen_extension);

		if (!file_exists($route . $imagen_nombre_archivo . '.' . $imagen_extension)) {

			if (move_uploaded_file($_FILES['file']['tmp_name'], $route . $imagen_nombre_archivo . '.' . $imagen_extension)) {

				if ($imagen_extension != $extension) {
					qry("UPDATE imagenes_prospectos SET imagen_extension = '$imagen_extension' WHERE id = $id_prospecto_img");
				}

				$response = array("response" => "success");
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

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "soltar_prospecto") {

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$id_usuario = $_POST['id_usuario'];

		$permiso_20 = profile(20, $id_usuario);

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_responsable_interno = 0 WHERE id = ?");
		$stmt->bind_param("i", $id_prospecto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			/*
			$query = "SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id WHERE prospectos.id = $id_prospecto";

			$result = qry($query);

			while ($row1 = mysqli_fetch_array($result)) {

				$prospecto_cedula = $row1['prospecto_cedula'];
				$prospecto_nombre = $row1['prospecto_nombre'];
				$prospecto_apellidos = $row1['prospecto_apellidos'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$prospecto_numero_contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$contacto_w = $row1['contacto_w'];
				$ciudad = $row1['ciudad'];
				$departamento = $row1['departamento'];
				$marca_producto = $row1['marca_producto'];

				$id_usuario_responsable = $row1['id_usuario_responsable'];
				$id_responsable_interno = $row1['id_responsable_interno'];

				$validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");
				if ($validate_gane == 1) {
					$creado_en = execute_scalar("SELECT nombre_punto FROM puntos_gane WHERE id_usuario = $id_usuario_responsable");
				} else {
					$creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
				}

				if ($id_responsable_interno == 0) {
					$responsable = '<span class="label label-success" style="font-size: 16px;">EN COLA</span>';
				} else {
					$responsable = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) FROM usuarios WHERE id = $id_responsable_interno");
				}
			}

			*/

			$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id WHERE prospectos.id = $id_prospecto";
			$result1 = qry($query1);
			while ($row1 = mysqli_fetch_array($result1)) {

				$id_prospecto = $row1['id_prospecto'];
				$prospecto_cedula = $row1['prospecto_cedula'];
				$prospecto_nombre = $row1['prospecto_nombre'];
				$prospecto_apellidos = $row1['prospecto_apellidos'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$prospecto_numero_contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$contacto_w = $row1['contacto_w'];
				$ciudad = $row1['ciudad'];
				$departamento = $row1['departamento'];
				$marca_producto = $row1['marca_producto'];

				$id_usuario_responsable = $row1['id_usuario_responsable'];
				$id_responsable_interno = $row1['id_responsable_interno'];

				$validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");
				if ($validate_gane == 1) {
					$id_punto_gane = execute_scalar("SELECT id_punto_gane FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario_responsable");
					$creado_en = execute_scalar("SELECT nombre_punto FROM puntos_gane WHERE id = $id_punto_gane");
				} else {
					$creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
				}

				if ($id_responsable_interno == 0) {
					$responsable = '<span class="label label-success" style="font-size: 16px;">EN COLA</span>';
				} else {
					$responsable = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) FROM usuarios WHERE id = $id_responsable_interno");
				}
			}

			$response = array(
				"response" => "success",
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
				"creado_en" => $creado_en,
				"responsable" => $responsable,
				"permiso_20" => $permiso_20
			);
		} else {
			$response = array(
				"response" => "error",
				"error" => $stmt->error
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "actualizar_prospecto") {

	try {

		//var_dump($_POST);
		//die();

		$id_prospecto = $_POST['id_prospecto'];

		$ciudad_residencia = execute_scalar("SELECT ciudad_id FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

		$type = $_POST['type'];

		$date = $_POST['date'];

		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		switch ($type) {

			case 'prospecto_email':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_email` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'prospecto_numero_contacto':
				$contacto = substr($date, 0, 3) . substr($date, 4, 3) . substr($date, 8, 4);

				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_numero_contacto` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $contacto, $id_prospecto);
				break;

			case 'prospecto_apellidos':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_apellidos` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'prospecto_nombre':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_nombre` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'prospecto_cedula':
				$stmt = $conn->prepare("UPDATE `prospectos` SET `prospecto_cedula` = ? WHERE `prospectos`.`id` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'prospecto_sexo':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_sexo` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'prospecto_direccion':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_direccion` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'prospecto_dob':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `prospecto_dob` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'ciudad_id':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `ciudad_id` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("ii", $date, $id_prospecto);
				break;

			case 'fecha_exp':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `fecha_exp` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'id_ciudad_exp':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `id_ciudad_exp` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("ii", $date, $id_prospecto);
				break;

			case 'id_referencia':
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `id_referencia` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("ii", $date, $id_prospecto);
				break;

			case 'inicial_referencia':
				$inicial_referencia = str_replace('.', '', $date);
				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `inicial_referencia` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("di", $inicial_referencia, $id_prospecto);
				break;

			case 'observacion_prospecto':

				$stmt = $conn->prepare("UPDATE `prospecto_detalles` SET `observacion_prospecto` = ? WHERE `prospecto_detalles`.`id_prospecto` = ?");
				$stmt->bind_param("si", $date, $id_prospecto);
				break;

			case 'id_plataforma':

				$stmt = $conn->prepare("UPDATE `prospectos` SET `id_plataforma` = ? WHERE `prospectos`.`id` = ?");
				$stmt->bind_param("ii", $date, $id_prospecto);
				break;

			case 'id_estado_prospecto':

				$stmt = $conn->prepare("UPDATE `prospectos` SET `resultado_dc_prospecto` = ? WHERE `prospectos`.`id` = ?");
				$stmt->bind_param("ii", $date, $id_prospecto);
				break;

			default:
				$response = 'fucking error';
				break;
		}
		$stmt->execute();

		if ($stmt->affected_rows == 1) {
			$response = array(
				'response' => 'success',
				'type' => $type,
				'date' => $date,
				'id_prospecto' => $id_prospecto,
				'ciudad_residencia' => $ciudad_residencia,
				"id_usuario" => $id_usuario
			);
		} else {
			$response = array(
				'response' => 'error'
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

} else if ($_POST['action'] == "despachar_equipo") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);

		$stmt = $conn->prepare("INSERT INTO solicitudes (id_prospecto, id_existencia, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, precio_producto, id_estado_solicitud, fecha_inicio_credito, id_usuario) VALUES (?, 0, 0, 0, 0, 0, 5, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), ?)");
		$stmt->bind_param("ii", $id_prospecto, $id_usuario);
		$stmt->execute();
		$id_solicitud = $stmt->insert_id;
		$error = $stmt->error;
		$rows = $stmt->affected_rows;
		$stmt->close();

		if ($rows == 1) {

			$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 3 WHERE id = ?");
			$stmt->bind_param("i", $id_prospecto);
			$stmt->execute();
			$id_solicitud = $stmt->insert_id;
			$error = $stmt->error;
			$rows = $stmt->affected_rows;
			$stmt->close();

			if ($rows == 1) {

				$response = array(
					"response" => "success",
					"id_prospecto" => $id_prospecto,
					"id_solicitud" => $id_solicitud
				);
			} else {

				$response = array(
					"response" => "error",
					"error" => $error
				);
			}
		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);
		}

		$conn->close();
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "crear_solicitud") {

	try {


		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);

		$validate_solicitudes_activas = execute_scalar("SELECT COUNT(solicitudes.id) AS total FROM solicitudes WHERE solicitudes.id_prospecto = $id_prospecto AND solicitudes.del = 0 AND solicitudes.id_estado_solicitud <> 8");

		if ($validate_solicitudes_activas == 0) {

			$stmt = $conn->prepare("INSERT INTO solicitudes (id_prospecto, id_existencia, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, precio_producto, id_estado_solicitud, fecha_inicio_credito, id_usuario) VALUES (?, 0, 0, 0, 0, 0, 1, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), ?)");
			$stmt->bind_param("ii", $id_prospecto, $id_usuario);
			$stmt->execute();
			$id_solicitud = $stmt->insert_id;
			$error = $stmt->error;
			$rows = $stmt->affected_rows;
			$stmt->close();

			if ($rows == 1) {

				$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 3, confirmar_aprobado = b'1' WHERE id = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();
				$id_solicitud = $stmt->insert_id;
				$error = $stmt->error;
				$rows = $stmt->affected_rows;
				$stmt->close();

				if ($rows == 1) {

					$response = array(
						"response" => "success",
						"id_prospecto" => $id_prospecto,
						"id_solicitud" => $id_solicitud
					);
				} else {

					$response = array(
						"response" => "error",
						"error" => $error
					);
				}
			} else {

				$response = array(
					"response" => "error",
					"error" => $error
				);
			}

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

} else if ($_POST['action'] == "confirmar_rechazado") {

	try {

		//var_dump($_POST);
		//die();

		$id_prospecto = $_POST['id_prospecto'];
		$conexion_activa = 0;
		
		$validate_confirmar_rechazado = execute_scalar("SELECT confirmar_rechazado FROM prospectos WHERE id = $id_prospecto");
		if($validate_confirmar_rechazado == 0){

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE prospectos SET confirmar_rechazado = b'1' WHERE id = ?");
			$stmt->bind_param("i", $id_prospecto);
			$stmt->execute();
			$rows = $stmt->affected_rows;
			$error = $stmt->error;
			$stmt->close();

			$conexion_activa = 1;
			
		}else{

			$rows = 1;
		}
		

		if($rows == 1) {

			$validar_estado_rechazado = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id  = $id_prospecto");

			if($validar_estado_rechazado != 2){

				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 2 WHERE id = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();
				$stmt->close();

				$conexion_activa = 1;
			
			}

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto
			);
			
		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);

		}

		if($conexion_activa == 1){
			$conn->close();
		}
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "show_plataformas") {

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$id_usuario = $_POST['id_usuario'];

		$permiso_23 = profile(23, $id_usuario);

		$validate_email = execute_scalar("SELECT prospecto_email FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

		if ($validate_email != '') {

			$validate_responsable_interno = execute_scalar("SELECT id_responsable_interno FROM prospectos WHERE id = $id_prospecto");

			if ($validate_responsable_interno != 0) {

				$response = array(
					"response" => "success",
					"id_prospecto" => $id_prospecto,
					"permiso_23" => $permiso_23
				);
			} else {

				$response = array("response" => "responsable");
			}
		} else {

			$response = array(
				"response" => "email"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "crear_solicitud_p") {

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$id_usuario = $_POST['id_usuario'];

		$id_plataforma = $_POST['plataformas_credito'];

		$validate_solicitudes = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE solicitudes.id_prospecto = $id_prospecto AND solicitudes.del = 0 AND solicitudes.id_estado_solicitud <> 7 AND solicitudes.id_estado_solicitud <> 8");

		$conn = new mysqli($host, $user, $pass, $db);

		if ($validate_solicitudes == 0) {

			$stmt = $conn->prepare("UPDATE `prospectos` SET `id_plataforma` = ? WHERE `prospectos`.`id` = ?");
			$stmt->bind_param("ii", $id_plataforma, $id_prospecto);
			$stmt->execute();
			$error = $stmt->error;
			$rows = $stmt->affected_rows;
			$stmt->close();

			if ($rows == 1) {

				if ($id_plataforma == 3) {

					$stmt = $conn->prepare("INSERT INTO solicitudes (id_prospecto, id_existencia, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, precio_producto, id_estado_solicitud, fecha_inicio_credito, id_usuario) VALUES (?, 0, 0, 0, 0, 0, 1, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), ?)");
					$stmt->bind_param("ii", $id_prospecto, $id_usuario);
					$stmt->execute();
					$id_solicitud = $stmt->insert_id;
					$error = $stmt->error;
					$rows = $stmt->affected_rows;
					$stmt->close();
				} else {

					$rows = 1;
					$error = '';
					$id_solicitud = '';
				}

				if ($rows == 1) {

					$response = array(
						"response" => "success",
						"id_solicitud" => $id_solicitud,
						"id_plataforma" => $id_plataforma
					);
				} else {

					$response = array(
						"response" => "error",
						"error" => $error
					);
				}
			} else {

				$response = array(
					'response' => 'error',
					'error' => $error
				);
			}
		} else {

			$response = array(

				"response" => "prospecto_solicitud_activa"

			);
		}

		$conn->close();
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "tomar_prospecto") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_responsable_interno = ? WHERE id = ?");
		$stmt->bind_param("ii", $id_usuario, $id_prospecto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, prospectos.id_responsable_interno FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id WHERE prospectos.id = $id_prospecto";
			$result1 = qry($query1);
			while ($row1 = mysqli_fetch_array($result1)) {

				$id_prospecto = $row1['id_prospecto'];
				$prospecto_cedula = $row1['prospecto_cedula'];
				$prospecto_nombre = $row1['prospecto_nombre'];
				$prospecto_apellidos = $row1['prospecto_apellidos'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$prospecto_numero_contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$contacto_w = $row1['contacto_w'];
				$ciudad = $row1['ciudad'];
				$departamento = $row1['departamento'];
				$marca_producto = $row1['marca_producto'];

				$id_usuario_responsable = $row1['id_usuario_responsable'];
				$id_responsable_interno = $row1['id_responsable_interno'];

				$validate_gane = execute_scalar("SELECT cliente_gane FROM usuarios WHERE id = $id_usuario_responsable");
				if ($validate_gane == 1) {
					$id_punto_gane = execute_scalar("SELECT id_punto_gane FROM usuarios_puntos_gane WHERE id_usuario = $id_usuario_responsable");
					$creado_en = execute_scalar("SELECT nombre_punto FROM puntos_gane WHERE id = $id_punto_gane");
				} else {
					$creado_en = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) FROM usuarios WHERE id = $id_usuario_responsable");
				}

				if ($id_responsable_interno == 0) {
					$responsable = '<span class="label label-success" style="font-size: 16px;">EN COLA</span>';
				} else {
					$responsable = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) FROM usuarios WHERE id = $id_responsable_interno");
				}
			}

			$permiso_20 = profile(20, $id_usuario);

			$response = array(
				'response' => "success",
				"id_prospecto" => $id_prospecto,
				"prospecto_cedula" => $prospecto_cedula,
				"prospecto_nombre" => $prospecto_nombre,
				"prospecto_apellidos" => $prospecto_apellidos,
				"prospecto_numero_contacto" => $prospecto_numero_contacto,
				"contacto_w" => $contacto_w,
				"ciudad" => $ciudad,
				"departamento" => $departamento,
				"creado_en" => $creado_en,
				"responsable" => $responsable,
				"id_prospecto" => $id_prospecto,
				"prospecto_cedula" => $prospecto_cedula,
				"permiso_20" => $permiso_20,
				"marca_producto" => $marca_producto,
				"id_usuario" => $id_usuario
			);
		} else {

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
} else if ($_POST['action'] == "update_validacion") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];
		$validacion_resultado = $_POST['validacion_resultado'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = ? WHERE id = ?");
		$stmt->bind_param("ii", $validacion_resultado, $id_prospecto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto,
				"id_estado_prospecto" => $validacion_resultado
			);
		} else {

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
} else if ($_POST['action'] == "quitar_resultado") {

	try {

		$id_prospecto = $_POST['id_prospecto'];


		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 0 WHERE id = ?");
		$stmt->bind_param("i", $id_prospecto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto
			);
		} else {

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

}else if($_POST['action'] == "venta_no_realizada"){

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 6 WHERE id = ?");
		$stmt->bind_param("i", $id_prospecto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$query = "SELECT solicitudes.id FROM solicitudes WHERE id_prospecto = $id_prospecto AND del = 0";
			$result = qry($query);
			while($row = mysqli_fetch_array($result)){

				$id_solicitud = $row['id'];

				qry("UPDATE solicitudes SET del = 1 WHERE id = $id_solicitud AND del = 0");

				qry("UPDATE solicitudes_domiciliarios SET del = 1 WHERE id_solicitud = $id_solicitud AND del = 0");

			}

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto
			);
			
		} else {

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

}else if($_POST['action'] == "downgrade_validar"){
	
	try {
		
		$id_prospecto = $_POST['id_prospecto'];
		$id_usuario = $_POST['id_usuario'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 0 WHERE id = ?");
		$stmt->bind_param("i", $id_prospecto);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto
			);

			$query = "SELECT solicitudes.id FROM solicitudes WHERE id_prospecto = $id_prospecto AND del = 0";
			$result = qry($query);
			while($row = mysqli_fetch_array($result)){

				$id_solicitud = $row['id'];

				qry("UPDATE solicitudes SET del = 1 WHERE id = $id_solicitud AND del = 0");

				qry("UPDATE solicitudes_domiciliarios SET del = 1 WHERE id_solicitud = $id_solicitud AND del = 0");

			}
			
		} else {

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
	
}
