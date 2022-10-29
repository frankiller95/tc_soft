<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action'] == "insertar_prospecto") {

	try {

		$cedula_prospecto = $_POST['cedula_prospecto'];
		$prospecto_nombre = $_POST['prospecto_nombre'];
		$prospecto_apellidos = $_POST['prospecto_apellidos'];
		$id_usuario =  $_POST['id_usuario'];
		$id_estado_prospecto = 11;

		$validate_cedula = execute_scalar("SELECT COUNT(prospectos.id) AS total FROM prospectos WHERE prospectos.prospecto_cedula = '$cedula_prospecto' AND prospectos.del = 0");

		if ($validate_cedula == 0) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO prospectos (prospecto_cedula, id_usuario_responsable, id_responsable_interno, id_estado_prospecto, fecha_registro) VALUES (?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("siii", $cedula_prospecto, $id_usuario, $id_usuario, $id_estado_prospecto);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$id_prospecto = $stmt->insert_id;

				$stmt->close();

				$stmt = $conn->prepare("INSERT INTO prospecto_detalles (id_prospecto, prospecto_nombre, prospecto_apellidos, fecha_registro) VALUES (?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
				$stmt->bind_param("iss", $id_prospecto, $prospecto_nombre, $prospecto_apellidos);
				$stmt->execute();

				$nombre_asesor = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS nombre_usuario FROM prospectos LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id = $id_prospecto");

				$fecha_hora = execute_scalar("SELECT DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%i:%s') AS fecha_hora FROM prospectos WHERE id = $id_prospecto");

				if ($stmt->affected_rows == 1) {

					$response = array(
						"response" => "success",
						"id_prospecto" => $id_prospecto,
						"cedula_prospecto" =>  $cedula_prospecto,
						"prospecto_nombre" => $prospecto_nombre,
						"prospecto_apellidos" => $prospecto_apellidos,
						"contacto" => 'N/A',
						"ubicacion" => 'N/A',
						"estado" => "CREACIÓN",
						"resultado" => "N/A",
						"nombre_asesor" => $nombre_asesor,
						"fecha_hora" => $fecha_hora
					);
				} else {

					$response = array(
						"response" => "error",
						"error" => $stmt->error,
						"num" => "erro2"
					);
				}
			} else {

				$response = array(
					"response" => "error",
					"error" => $stmt->error,
					"num" => "erro1"
				);
			}

			$stmt->close();
			$conn->close();
		} else {

			$response = array(
				"response" => "cedula_repetida"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "completar_prospecto") {

	try {

		$cedula_prospecto = $_POST['cedula_prospecto'];
		$prospecto_nombre = $_POST['prospecto_nombre'];
		$prospecto_apellidos = $_POST['prospecto_apellidos'];
		$id_referencia_equipo = $_POST['id_referencia_equipo'];
		$inicial_referencia = $_POST['inicial_referencia'];
		$plazo_credito = $_POST['plazo_credito'];
		$contacto_prospecto = $_POST['contacto_prospecto'];
		$email_prospecto = $_POST['email_prospecto'];
		$direccion_prospecto = $_POST['direccion_prospecto'];
		$fecha_nacimiento_prospecto = $_POST['fecha_nacimiento_prospecto'];
		$from = $_POST['from'];
		if (isset($_POST['sexo_prospecto'])) {
			$sexo_prospecto = $_POST['sexo_prospecto'];
		} else {
			// jajajajajaja
			$response = array(
				"response" => "falta_sexo"
			);
			echo json_encode($response);
			die();
		}
		if (isset($_POST['ciudad_prospecto'])) {
			$ciudad_prospecto = $_POST['ciudad_prospecto'];
		} else {
			$response = array(
				"response" => "falta_ciudad"
			);
			echo json_encode($response);
			die();
		}
		$id_prospecto = $_POST['id_prospecto'];

		$longitud_contacto = strlen($contacto_prospecto);

		if ($longitud_contacto == 12) {

			$contacto_prospecto_format = substr($contacto_prospecto, 0, 3) . substr($contacto_prospecto, 4, 3) . substr($contacto_prospecto, 8, 4);
		} else {
			$response = array(
				"response" => "telefono_invalido"
			);
			echo json_encode($response);
			die();
		}

		$inicial_referencia_format = str_replace('.', '', $inicial_referencia);

		$id_usuario = $_POST['id_usuario'];

		$color_dispositivo = 0;

		if(isset($_POST['color_dispositivo'])){
			$color_dispositivo = $_POST['color_dispositivo'];
		}
		

		//valida si la cedula esta repetida

		$validate_cedula = execute_scalar("SELECT COUNT(prospectos.id) AS total FROM prospectos WHERE prospectos.prospecto_cedula = '$cedula_prospecto' AND prospectos.id <> $id_prospecto AND prospectos.del = 0");

		if ($validate_cedula == 0) {

			$query1 = "UPDATE prospectos SET prospecto_cedula = '$cedula_prospecto', ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE prospectos.id = $id_prospecto";

			$conn = new mysqli($host, $user, $pass, $db);

			$result = qry($query1);

			if ($result) {

				$query2 = "UPDATE prospecto_detalles SET prospecto_nombre = '$prospecto_nombre', prospecto_apellidos = '$prospecto_apellidos',  prospecto_numero_contacto = '$contacto_prospecto_format', prospecto_email = '$email_prospecto', prospecto_sexo = '$sexo_prospecto', prospecto_dob = '$fecha_nacimiento_prospecto', prospecto_direccion = '$direccion_prospecto', ciudad_id = $ciudad_prospecto, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE prospecto_detalles.id_prospecto = $id_prospecto";

				$result2 = qry($query2);

				if ($result2) {

					$validar_existe = execute_scalar("SELECT COUNT(*) AS total FROM referencias_prospectos WHERE referencias_prospectos.id_prospecto = $id_prospecto AND referencias_prospectos.del = 0");
					$conn = new mysqli($host, $user, $pass, $db);

					if ($validar_existe == 0) {

						$stmt = $conn->prepare("INSERT INTO referencias_prospectos (id_prospecto, id_referencia, inicial_confirmada, plazo_meses, id_color, fecha_registro) VALUES (?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
						$stmt->bind_param("iidsi", $id_prospecto, $id_referencia_equipo, $inicial_referencia_format, $plazo_credito, $color_dispositivo);

					} else {

						$stmt = $conn->prepare("UPDATE referencias_prospectos SET id_referencia = ?, inicial_confirmada = ?, plazo_meses = ?, id_color = ?, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_prospecto = ?");
						$stmt->bind_param("idsii", $id_referencia_equipo, $inicial_referencia_format, $plazo_credito, $color_dispositivo, $id_prospecto);
					}

					$stmt->execute();

					if ($stmt->affected_rows == 1) {

						qry("UPDATE prospectos SET info_completa = b'1' WHERE id = $id_prospecto");

						$ciudad_nombre = execute_scalar("SELECT CONCAT(ciudades.ciudad, ' / ', departamentos.departamento) AS ciudad_full FROM ciudades INNER JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE ciudades.id = $ciudad_prospecto");

						$nombre_asesor = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS nombre_usuario FROM prospectos LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id = $id_prospecto");

						$fecha_hora = execute_scalar("SELECT DATE_FORMAT(fecha_registro, '%d/%m/%Y %H:%i:%s') AS fecha_hora FROM prospectos WHERE id = $id_prospecto");

						$plataformas = traer_plataformas($id_prospecto);

						$all_prospectos = profile(14, $id_usuario);

						$id_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

						$response = array(
							"response" => "success",
							"id_prospecto" => $id_prospecto,
							"prospecto_nombre" => $prospecto_nombre,
							"prospecto_apellidos" => $prospecto_apellidos,
							"cedula_prospecto" => $cedula_prospecto,
							"contacto" => $contacto_prospecto_format,
							"ubicacion" => $ciudad_nombre,
							"estado" => "CREACIÓN",
							"resultados" => $plataformas,
							"nombre_asesor" => $nombre_asesor,
							"fecha_hora" => $fecha_hora,
							"all_prospectos" => $all_prospectos,
							"id_estado_prospecto" => $id_estado_prospecto
						);

					} else {

						$response = array(
							"response" => "error3",
							"error" => $stmt->error
						);

					}

					$stmt->close();
					$conn->close();

				} else {

					$response = array(
						"response" => "error2",
						"error" => $stmt->error
					);

				}

			} else {

				$response = array(
					"response" => "error1",
					"error" => $stmt->error
				);
			}

		} else {

			$response = array(
				"response" => "cedula_repetida"
			);

		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);

	}

	echo json_encode($response);

} else if ($_POST['action'] == "select_info") {

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, referencias_prospectos.id_referencia, referencias_prospectos.inicial_confirmada, referencias_prospectos.plazo_meses, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, prospecto_detalles.prospecto_sexo, prospecto_detalles.prospecto_direccion, prospecto_detalles.ciudad_id, prospecto_detalles.prospecto_dob, usuarios.nombre AS nombre_usuario, usuarios.apellidos AS apellido_usuario, referencias_prospectos.id_color FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN referencias_prospectos ON referencias_prospectos.id_prospecto = prospectos.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id WHERE prospectos.id = ?");
		$stmt->bind_param("i", $id_prospecto);
		$stmt->execute();

		$meta = $stmt->result_metadata();
		while ($field = $meta->fetch_field()) {
			$params[] = &$row[$field->name];
		}

		call_user_func_array(array($stmt, 'bind_result'), $params);

		while ($stmt->fetch()) {
			foreach ($row as $key => $val) {
				$c[$key] = $val;
			}
			$response[] = $c;
		}
		if ($response[0]['prospecto_numero_contacto'] !== '') {
			$response[0]['prospecto_numero_contacto'] = substr($response[0]['prospecto_numero_contacto'], 0, 3) . '-' . substr($response[0]['prospecto_numero_contacto'], 3, 3) . '-' . substr($response[0]['prospecto_numero_contacto'], 6, 4);
		}

		if ($response[0]['inicial_confirmada'] !== '') {
			$response[0]['inicial_confirmada'] = number_format($response[0]['inicial_confirmada'], 0, ".", ".");
		}

		$stmt->close();
		$conn->close();
		
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "select_inicial") {

	try {

		//var_dump($_POST);
		//die();
		$id_referencia = $_POST['id_referencia'];
		$id_prospecto = $_POST['id_prospecto'];
		$validate_referencia = execute_scalar("SELECT COUNT(*) FROM referencias_prospectos WHERE id_referencia = $id_referencia AND id_prospecto = $id_prospecto");
		if ($validate_referencia == 0) {

			$precio_referencia = execute_scalar("SELECT modelos.precio_venta FROM modelos WHERE modelos.id = $id_referencia");
			$porcentaje_defecto = 30;
			$inicial_defecto = ($porcentaje_defecto * $precio_referencia) / 100;

			$response = array("response" => "success", "inicial" => number_format($inicial_defecto, 0, ".", "."));
		} else {

			$response = array("response" => "ya_existe");
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

		$validate_imagen1 = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_prospectos WHERE imagenes_prospectos.id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = 'FRONTAL' AND imagenes_prospectos.del = 0");
		$validate_imagen2 = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_prospectos WHERE imagenes_prospectos.id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = 'ATRAS' AND imagenes_prospectos.del = 0");
		$validate_imagen3 = execute_scalar("SELECT COUNT(*) AS total FROM imagenes_prospectos WHERE imagenes_prospectos.id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = 'SELFIE' AND imagenes_prospectos.del = 0");

		$response = array(
			"response" => "success",
			"id_prospecto" => $id_prospecto,
			"imagen_1" => $validate_imagen1,
			"imagen_2" => $validate_imagen2,
			"imagen_3" => $validate_imagen3
		);
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "cargar_imagen") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$tipo_img = $_POST['tipo_img'];
		$id_usuario = $_POST['id_usuario'];

		//info imagen

		$name = $_FILES['imagen_cc']['name'];
		$tmp_name = $_FILES['imagen_cc']['tmp_name'];
		$type = $_FILES['imagen_cc']['type'];

		if ($_FILES['imagen_cc']['type'] == "image/jpeg") {

			$extension = "jpg";
		} else if ($_FILES['imagen_cc']['type'] == "image/png") {

			$extension = "jpg";
		} else {

			$response = array(
				"response" => "tipo_incorrecto"
			);

			echo json_encode($response);

			die();
		}

		$validate_exist = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = '$tipo_img' AND imagenes_prospectos.del = 1");

		$conn = new mysqli($host, $user, $pass, $db);

		if ($validate_exist == 1) {

			$stmt = $conn->prepare("UPDATE imagenes_prospectos SET fecha_registro = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), imagenes_prospectos.del = 0 WHERE imagenes_prospectos.id_prospecto = ? AND imagenes_prospectos.imagen_nombre_archivo = ?");
			$stmt->bind_param("is", $id_prospecto, $tipo_img);
		} else {

			$stmt = $conn->prepare("INSERT INTO imagenes_prospectos (id_prospecto, imagen_nombre_archivo, imagen_extension, fecha_registro) VALUES (?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("iss", $id_prospecto, $tipo_img, $extension);
		}

		$stmt->execute();

		if ($stmt->affected_rows == 1) {


			$route = '../documents/prospects/' . $id_prospecto . '/';
			$pictureFileName = $tipo_img . '.' . $extension;

			if (!file_exists($route)) {

				mkdir($route, 0777, true);
			}

			if (is_file($route . $pictureFileName)) {
				unlink($route . $pictureFileName);
			}

			if (move_uploaded_file($tmp_name, $route . $pictureFileName)) {

				$response = array(

					"response" => "success",
					"id_prospecto" => $id_prospecto,
					"tipo_img" => $tipo_img,
					"route" => './documents/prospects/' . $id_prospecto . '/' . $pictureFileName
				);
			} else {

				$response = array(
					"response" => "error"
				);
			}
		} else {

			$response = array(
				"response" => "error",
				"error" => $stmt->error,
				"num" => "erro1"
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
} else if ($_POST['action'] == "actualizar_imagen") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$tipo_img = $_POST['tipo_img'];
		$id_usuario = $_POST['id_usuario'];

		//info imagen

		$name = $_FILES['imagen_cc']['name'];
		$tmp_name = $_FILES['imagen_cc']['tmp_name'];
		$type = $_FILES['imagen_cc']['type'];

		if ($_FILES['imagen_cc']['type'] == "image/jpeg") {

			$extension = "jpg";
		} else if ($_FILES['imagen_cc']['type'] == "image/png") {

			$extension = "jpg";
		} else {

			$response = array(
				"response" => "tipo_incorrecto"
			);

			echo json_encode($response);

			die();
		}


		$route = '../documents/prospects/' . $id_prospecto . '/';
		$pictureFileName = $tipo_img . '.' . $extension;

		if (!file_exists($route)) {

			mkdir($route, 0777, true);
		}

		if (is_file($route . $pictureFileName)) {
			unlink($route . $pictureFileName);
		}

		if (move_uploaded_file($tmp_name, $route . $pictureFileName)) {

			$response = array(

				"response" => "success",
				"id_prospecto" => $id_prospecto,
				"tipo_img" => $tipo_img,
				"route" => './documents/prospects/' . $id_prospecto . '/' . $pictureFileName
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
} else if ($_POST['action'] == "eliminar_imagen") {

	try {

		//var_dump($_POST);
		//die();

		$id_prospecto = $_POST['id_prospecto'];
		$tipo_img = $_POST['tipo_img'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE imagenes_prospectos SET del = 1 WHERE id_prospecto = ? AND imagen_nombre_archivo = ? AND del = 0");
		$stmt->bind_param("is", $id_prospecto, $tipo_img);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$extension = "jpg";

			$route = '../documents/prospects/' . $id_prospecto . '/';
			$pictureFileName = $tipo_img . '.' . $extension;

			//echo $route.$pictureFileName;
			//die();


			if (is_file($route . $pictureFileName)) {

				unlink($route . $pictureFileName);

				$response = array(
					"response" => "success"
				);
			} else {

				$response = array(
					"response" => "error"
				);
			}
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
} else if ($_POST['action'] == "validate_pdte_validar") {

	try {

		$id_prospecto =  $_POST['id_prospecto'];
		$from = $_POST['from'];

		$validate_imagenes = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagenes_prospectos.del = 0");

		$validate_info = execute_scalar("SELECT prospectos.info_completa FROM prospectos WHERE id = $id_prospecto");

		if ($validate_info == 1) {

			if ($validate_imagenes == 3) {

				$completed = 0;

				if ($from == 1) {

					$id_estado_prospecto = 12;

					$conn = new mysqli($host, $user, $pass, $db);
					$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = ? WHERE id = ?");
					$stmt->bind_param("ii", $id_estado_prospecto, $id_prospecto);
					$stmt->execute();

					if ($stmt->affected_rows == 1) {

						$response = array(
							"response" => "success",
							"id_prospecto" => $id_prospecto
						);
					} else {

						$response = array('response' => "error", "error" => $stmt->error);
					}

					$stmt->close();
					$conn->close();
				} else if ($from == 2) {

					$response = array(
						"response" => "success",
						"id_prospecto" => $id_prospecto
					);
				}
			} else {

				$response = array(
					'response' => "falta_imagenes",
					'total_imagenes' => $validate_imagenes
				);
			}
		} else {

			$response = array('response' => 'falta_informacion');
		}
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "delete_prospecto") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$observacion_cancelacion = $_POST['observacion_cancelacion'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO detalles_prospectos_cancelados (id_prospecto, observacion_texto, fecha_registro, ultimo_cambio) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
		$stmt->bind_param("is", $id_prospecto, $observacion_cancelacion);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$stmt = $conn->prepare("UPDATE prospectos SET del = b'1', ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
			$stmt->bind_param("i", $id_prospecto);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				$stmt->close();
				$stmt = $conn->prepare("UPDATE prospecto_detalles SET del = b'1', ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$id_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

					if ($id_estado_prospecto != 11) {
						$array = array("prospectos_pendiente_llamar", "prospectos_pendiente_validar", "referencias_prospectos", "resultados_prospectos");
						foreach ($array as $value) {
							$stmt = $conn->prepare("DELETE FROM $value WHERE id_prospecto = ?");
							$stmt->bind_param("i", $id_prospecto);
							$stmt->execute();
						}
					}

					$route_died = '../documents/prospects/' . $id_prospecto . '/';
					$validador_eliminar = is_dir($route_died);
					if ($validador_eliminar) {
						deleteDirectory($route_died);
					}

					$response = array(
						"response" => "success",
						"id_prospecto" => $id_prospecto
					);

					$stmt->close();
				} else {

					$response = array(
						"response" => "error2",
						"error" => $stmt->error
					);

					$stmt->close();
				}
			} else {

				$response = array(
					"response" => "error1",
					"error" => $stmt->error
				);

				$stmt->close();
			}

		} else {

			$response = array(
				"response" => "error",
				"error" => $stmt->error
			);

			$stmt->close();
		}

		$conn->close();
		
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "pdte_validar_update") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$id_estado = $_POST['estado_pendiente_validar'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO prospectos_pendiente_validar (id_prospecto, id_estado) VALUES (?, ?)");
		$stmt->bind_param("ii", $id_prospecto, $id_estado);
		$stmt->execute();

		if ($stmt->affected_rows == 1) {

			$stmt->close();
			$id_estado_prospecto = 12;

			$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = ? WHERE id = ?");
			$stmt->bind_param("ii", $id_estado_prospecto, $id_prospecto);
			$stmt->execute();

			if ($stmt->affected_rows == 1) {

				if ($id_estado == 1) {

					$estado_nombre = "CUENTA CON LA INICIAL";
				} else if ($id_estado == 2) {

					$estado_nombre = "CLIENTE DISPONIBLE PARA LLAMAR";
				}

				$stmt->close();

				$stmt = $conn->prepare("UPDATE prospectos_pendiente_llamar SET del = b'1', ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_prospecto = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();

				if ($stmt->affected_rows == 1) {

					$response = array(
						"response" => "success",
						"id_prospecto" => $id_prospecto,
						"estado_nombre" => $estado_nombre,
						"id_estado" => $id_estado
					);
				} else {

					$response = array(
						"response" => "error2",
						"error" => $stmt->error,
						"num" => "3"
					);
				}
			} else {

				$response = array(
					"response" => "error2",
					"error" => $stmt->error,
					"num" => "2"
				);
			}
		} else {

			$response = array(
				"response" => "error",
				"error" => $stmt->error,
				"number" => "1"
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
} else if ($_POST['action'] == "select_pro_comprobante") {

	try {

		$id_prospecto = $_POST['id_prospecto'];

		$validate_comprobante = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagen_nombre_archivo = 'comprobante' AND del = 0");

		$texto = '';

		if ($validate_comprobante == 0) {
			$texto = 'success';
		} else {
			$texto = 'cargado';
		}

		$response = array(
			"response" => $texto,
			"id_prospecto" => $id_prospecto
		);
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}
	echo json_encode($response);
}
