<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use \setasign\Fpdi\Fpdi;

require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';



date_default_timezone_set('America/Bogota');

if ($_POST['action'] == "confirmacion") {

	$medio_envio = $_POST['medio_envio'];

	$id_solicitud = $_POST['id_solicitud'];

	$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

	$email_prospecto = execute_scalar("SELECT prospecto_email FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

	$contacto_prospecto = execute_scalar("SELECT prospecto_numero_contacto FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

	$validacion = 0;

	//alimentamos el generador de aleatorios
	mt_srand(time());
	//generamos un número aleatorio
	$codigo = mt_rand(100000, 999999);

	if ($medio_envio == 1) {


		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = 0;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'mail.noa10.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'no-reply@noa10.com';                     //SMTP username
			$mail->Password   = 'Soporte0319+';                               //SMTP password
			$mail->SMTPSecure = 'ssl';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = 465;

			//Recipients
			$mail->setFrom('no-reply@noa10.com', 'Noa10 linea de credito');
			$mail->addAddress($email_prospecto);     //Add a recipient



			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'Confirmar Tratamiento de datos TCSHOP';
			//$mail->Body    = 'El codigo para confirmar el tratamiento de datos en centrales de riesgo es:&nbsp;'.$codigo;

			$mail->Body = 'Codigo ' . $codigo . ' Autoriza a TCShop consultar historial crediticio y aceptas nuestras politicas de tratamiento de datos. ver politicas https://acortar.link/I78w3p';

			if ($mail->send()) {

				$validacion = 1;
			} else {

				$response = array(
					"response" => "error_correo"
				);

				echo json_encode($response);
				die();
			}
		} catch (Exception $e) {

			$response = array(
				"response" => "error_correo",
				"error" => $mail->ErrorInfo
			);

			echo json_encode($response);
			die();
		}
	} else {

		include('../includes/httpPHPAltiria.php');

		$altiriaSMS = new AltiriaSMS();
		//$altiriaSMS->setUrl("http://www.altiria.net/api/http");
		$altiriaSMS->setLogin('soporte@tucelular.net.co');
		$altiriaSMS->setPassword('SOPORTE9529');

		//$sDestination = '346xxxxxxxx';
		$sDestination = '57' . $contacto_prospecto;
		//$sDestination = array('346xxxxxxxx','346yyyyyyyy');

		//$respuesta = $altiriaSMS->sendSMS($sDestination, 'Codigo '.$codigo.' Autoriza a TCShop consultar historial crediticio y aceptas nuestras politicas de tratamiento de datos.');

		$respuesta = $altiriaSMS->sendSMS($sDestination, 'Codigo ' . $codigo . ' Autoriza a TCShop consultar historial crediticio y aceptas nuestras politicas de tratamiento de datos. ver politicas https://acortar.link/I78w3p');

		$validate = 1;

		if ($respuesta == $validate) {

			$validacion = 1;
		} else {

			$response = array("response" => "error_sms");
			echo json_encode($response);
			die();
		}
	}

	if ($validacion == 1) {

		$validate_codigo = execute_scalar("SELECT COUNT(id) AS total FROM confirmacion_solicitud WHERE id_solicitud = $id_solicitud AND confirmacion_solicitud.estado_confirmacion = 1");

		$conn = new mysqli($host, $user, $pass, $db);

		if ($validate_codigo != 0) {

			$stmt = $conn->prepare("UPDATE confirmacion_solicitud SET id_medio_envio = ?, codigo = ?, fecha_registro = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), fecha_vencimiento = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
			$stmt->bind_param("iii", $medio_envio, $codigo, $id_solicitud);
			$stmt->execute();
		} else {

			$stmt = $conn->prepare("INSERT INTO confirmacion_solicitud (id_solicitud, id_medio_envio, codigo, fecha_registro, fecha_vencimiento) VALUES (?, ?, ?,  DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("iii", $id_solicitud, $medio_envio, $codigo);
			$stmt->execute();
		}

		if ($stmt->affected_rows == 1) {

			qry("UPDATE solicitudes SET id_estado_solicitud = 11 WHERE id = $id_solicitud");

			$id_confirmacion_solicitud = $stmt->insert_id;

			$fecha_vencimiento = execute_scalar("SELECT fecha_vencimiento FROM confirmacion_solicitud WHERE id = $id_confirmacion_solicitud");

			$horas_v = 0;
			$minutos_v = 10;

			$date = $fecha_vencimiento;
			$newDate = strtotime('+' . $horas_v . ' hour', strtotime($date));
			$newDate = strtotime('+' . $minutos_v . ' minute', $newDate);
			$newDate = strtotime('+10 second', $newDate);
			$newDate = date('Y-m-j H:i:s', $newDate);

			qry("UPDATE confirmacion_solicitud SET fecha_vencimiento = '$newDate' WHERE id = $id_confirmacion_solicitud");

			$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
			$result1 = qry($query1);
			while ($row1 = mysqli_fetch_array($result1)) {

				$prospecto_nombre = $row1['fullname'];
				$full_producto = $row1['full_producto'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$prospecto_email = $row1['prospecto_email'];

				$porcentaje = $row1['porcentaje'];
				$precio_producto = $row1['precio_producto'];

				$inicial = ($porcentaje * $precio_producto) / 100;

				$id_estado_solicitud = $row1['id_estado_solicitud'];

				$mostrar = $row1['mostrar'];

				$clase_color = '';

				$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

				if ($validate_resultados_solicitud_cifin != 0) {
					$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
					if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
						$clase_color = "solicitud-aprobada";
					} else {
						$clase_color = "solicitud-rechazada";
					}
				}

				$response = array(
					"response" => "success",
					"id_confirm_solicitud" => $id_confirmacion_solicitud,
					"id_solicitud" => $id_solicitud,
					"nombre" => $prospecto_nombre,
					"modelo" => $full_producto,
					"contacto" => $contacto,
					"email" => $prospecto_email,
					"inicial" => number_format($inicial, 0, '.', '.'),
					"texto_estado" => $mostrar,
					"id_estado_solicitud" => $id_estado_solicitud,
					"id_codigo" => $id_confirmacion_solicitud
				);
			}
		} else {

			$response = array(
				"response" => "error"
			);
		}
		$stmt->close();
		$conn->close();
	} else {
		$response = array(
			"response" => "error_proceso"
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "validar_codigo") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$hora_actual = date('Y-m-d H:i:s', time());

		$validate_codigo_exist = execute_scalar("SELECT COUNT(id) AS total FROM confirmacion_solicitud WHERE id_solicitud = $id_solicitud AND estado_confirmacion = 0 AND del = 0");

		if ($validate_codigo_exist == 1) {

			$id_codigo = execute_scalar("SELECT id FROM confirmacion_solicitud WHERE id_solicitud = $id_solicitud AND estado_confirmacion = 0 AND del = 0");

			$fecha_vencimiento = execute_scalar("SELECT fecha_vencimiento FROM confirmacion_solicitud WHERE id = $id_codigo");

			if ($fecha_vencimiento > $hora_actual) {

				$response = array(
					"response" => "success",
					"id_codigo" => $id_codigo
				);
			} else {

				$query = "UPDATE confirmacion_solicitud SET del = b'1' WHERE id = $id_codigo";

				$result = qry($query);

				if ($query) {

					$response = array(
						"response" => "fecha_vencida"
					);
				} else {

					$response = array(
						"response" => "error"
					);
				}
			}
		} else {

			$response = array(
				"response" => "codigo_error"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "confirmar_codigo") {

	try {

		$id_confirmacion_solicitud = $_POST['id_solicitud_codigo'];

		$codigoArray = $_POST['codigoN'];

		$codigo = execute_scalar("SELECT codigo FROM confirmacion_solicitud WHERE id = $id_confirmacion_solicitud");

		$id_solicitud = execute_scalar("SELECT id_solicitud FROM confirmacion_solicitud WHERE id = $id_confirmacion_solicitud");

		$codigoValidate = implode("", $codigoArray);

		//echo $codigo.'-'.$codigoValidate;

		$estado_confirmado = 1;

		if ($codigoValidate == $codigo) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("UPDATE confirmacion_solicitud SET estado_confirmacion = ? WHERE id = ?");
			$stmt->bind_param("ii", $estado_confirmado, $id_confirmacion_solicitud);
			$stmt->execute();
			if ($stmt->affected_rows == 1) {

				qry("UPDATE solicitudes SET id_estado_solicitud = 2 WHERE id = $id_solicitud"); //pdte verificacion cifin

				$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
				$result1 = qry($query1);
				while ($row1 = mysqli_fetch_array($result1)) {

					$prospecto_nombre = $row1['fullname'];
					$full_producto = $row1['full_producto'];
					$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
					$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
					$prospecto_email = $row1['prospecto_email'];

					$porcentaje = $row1['porcentaje'];
					$precio_producto = $row1['precio_producto'];

					$inicial = ($porcentaje * $precio_producto) / 100;

					$id_estado_solicitud = $row1['id_estado_solicitud'];

					$mostrar = $row1['mostrar'];

					$clase_color = '';

					$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

					if ($validate_resultados_solicitud_cifin != 0) {
						$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
						if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
							$clase_color = "solicitud-aprobada";
						} else {
							$clase_color = "solicitud-rechazada";
						}
					}

					$response = array(
						"response" => "success",
						"id_confirm_solicitud" => $id_confirmacion_solicitud,
						"id_solicitud" => $id_solicitud,
						"nombre" => $prospecto_nombre,
						"modelo" => $full_producto,
						"contacto" => $contacto,
						"email" => $prospecto_email,
						"inicial" => number_format($inicial, 2, '.', '.'),
						"texto_estado" => $mostrar,
						"id_estado_solicitud" => $id_estado_solicitud
					);
				}
			} else {

				$response = array(
					"response" => "error"
				);
			}
			$stmt->close();
			$conn->close();
		} else {

			$response = array(
				"response" => "error_codigo"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "validar_confirmacion") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$hora_actual = date('Y-m-j H:i:s', time());
		/*
		$query = "SELECT COUNT(id) AS total FROM confirmacion_solicitud WHERE id_solicitud = $id_solicitud AND fecha_vencimiento < $hora_actual AND estado_confirmacion = 0"; 

		echo $query;
		die();
		*/
		$validate = execute_scalar("SELECT COUNT(id) AS total FROM confirmacion_solicitud WHERE id_solicitud = $id_solicitud AND fecha_vencimiento > '$hora_actual' AND estado_confirmacion = 0");

		$id_confirm_solicitud = 0;

		if ($validate == 1) {
			$id_confirm_solicitud = execute_scalar("SELECT id FROM confirmacion_solicitud WHERE id_solicitud = $id_solicitud AND fecha_vencimiento > '$hora_actual' AND estado_confirmacion = 0");
		}

		$response = array(
			"response" => "success",
			"validate" => $validate,
			"id_confirm_solicitud" => $id_confirm_solicitud
		);
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "insertar_cifin") {

	try {

		$id_solicitud = $_POST['id_solicitud'];
		$id_resultado = $_POST['id_resultado'];
		$id_estado_solicitud = 0;

		qry("DELETE FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO resultados_solicitud_cifin (id_solicitud, id_estado_cifin) VALUES (?, ?)");
		$stmt->bind_param("ii", $id_solicitud, $id_resultado);
		$stmt->execute();
		if ($stmt->affected_rows == 1) {

			$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
			$result1 = qry($query1);
			while ($row1 = mysqli_fetch_array($result1)) {

				$prospecto_nombre = $row1['fullname'];
				$full_producto = $row1['full_producto'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$prospecto_email = $row1['prospecto_email'];

				$porcentaje = $row1['porcentaje'];
				$precio_producto = $row1['precio_producto'];

				$inicial = ($porcentaje * $precio_producto) / 100;

				$id_estado_solicitud = $row1['id_estado_solicitud'];

				$mostrar = $row1['mostrar'];

				$clase_color = '';

				$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

				if ($validate_resultados_solicitud_cifin != 0) {

					$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
					if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {

						$clase_color = "solicitud-aprobada";
						$texto_cifin = "ELEGIBLE AA";
						if($id_resultados_solicitud_cifin == 2){
							$texto_cifin = "ELEGIBLE A";
						}

					} else {
						$clase_color = "solicitud-rechazada";
						$texto_cifin = "RECHAZADO";
					}
				}
			}

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud,
				"nombre" => $prospecto_nombre,
				"modelo" => $full_producto,
				"contacto" => $contacto,
				"email" => $prospecto_email,
				"inicial" => number_format($inicial, 0, '.', '.'),
				"texto_estado" => $mostrar,
				"id_estado_solicitud" => $id_estado_solicitud,
				"clase_color" => $clase_color,
				"texto_cifin" => $texto_cifin
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
} else if ($_POST['action'] == "select_cifin") {
	try {

		$id_solicitud = $_POST['id_solicitud'];
		$resultado_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

		$response = array(
			"response" => "success",
			"cifin" => $resultado_cifin,
			"id_solicitud" => $id_solicitud
		);
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "validar_enrolada") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_usuario = $_POST['id_usuario'];

		$token = "Token 4c191e2b135f4672a14bc0a71e9c0166";

		$imei = execute_scalar("SELECT productos_stock.imei_1 FROM solicitudes LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id WHERE solicitudes.id = $id_solicitud");

		$url = "https://app.nuovopay.com/dm/api/v1/devices.json?page=0&search_string=" . $imei;

		$respuesta = peticion_get($url, $token);

		$device = JSON2Array($respuesta);
		header('Content-Type: text/html; charset=utf-8');

		if ($device['total_count'] == 0) {

			$permiso_nuovo = profile(5, $id_usuario);

			$response = array(
				"response" => "error",
				"permiso" => $permiso_nuovo
			);
		} else {

			// pasamos la solicitud al estado 12 = aprobada sin contracto.

			qry("UPDATE solicitudes SET id_estado_solicitud = 12 WHERE solicitudes.id = $id_solicitud");

			$id_estado_solicitud = 12;

			$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
			$result1 = qry($query1);
			while ($row1 = mysqli_fetch_array($result1)) {

				$prospecto_nombre = $row1['fullname'];
				$full_producto = $row1['full_producto'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$prospecto_email = $row1['prospecto_email'];

				$porcentaje = $row1['porcentaje'];
				$precio_producto = $row1['precio_producto'];

				$inicial = ($porcentaje * $precio_producto) / 100;

				$id_estado_solicitud = $row1['id_estado_solicitud'];

				$mostrar = $row1['mostrar'];

				$clase_color = '';

				$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

				if ($validate_resultados_solicitud_cifin != 0) {

					$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
					if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
						$clase_color = "solicitud-aprobada";
						$texto_cifin = "ELEGIBLE A";

						if ($id_resultados_solicitud_cifin == 1) {
							$texto_cifin = "ELEGIBLE AA";
						}
					} else {
						$clase_color = "solicitud-rechazada";
						$texto_cifin = "RECHAZADO";
					}
				}
			}

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud,
				"nombre" => $prospecto_nombre,
				"modelo" => $full_producto,
				"contacto" => $contacto,
				"email" => $prospecto_email,
				"inicial" => number_format($inicial, 0, '.', '.'),
				"texto_estado" => $mostrar,
				"id_estado_solicitud" => $id_estado_solicitud,
				"clase_color" => $clase_color,
				"texto_cifin" => $texto_cifin,
				"prospecto_numero_contacto" => $prospecto_numero_contacto
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "enviar_mensaje_firmar" || $_POST['action'] == "reenviar_mensaje_firmar") {

	try {

		$id_solicitud = $_POST['id_solicitud'];
		$celular = $_POST['celular'];
		$reenvio = $_POST['reenvio'];
		$validacion = 0;

		$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

		$prospecto_cedula = execute_scalar("SELECT prospecto_cedula FROM prospectos WHERE id = $id_prospecto");

		$destination = "+57" . $celular;

		$fecha_firmado = date('d-m-Y', time());

		$array_fecha = explode('-', $fecha_firmado);

		$dia = $array_fecha['0'];

		$mes = $array_fecha['1'];

		$ano = $array_fecha['2'];

		$credito_no = $array_fecha['2'] . $array_fecha['1'] . $array_fecha['0'];

		$conn = new mysqli($host, $user, $pass, $db);

		if ($reenvio == 1) {

			//$validate_q = "UPDATE solicitudes_creditos_numeros SET credito_numero = $credito_no fecha_registro = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = $id_solicitud AND del = 0";

			//echo $validate_q;
			//die();

			$route = '../documents/solicitudes/' . $id_solicitud . '/';

			$file_contracto = $route . 'conmbinado_sin_firmar.pdf';

			$file_pagare = $route . 'document2.pdf';

			$firmaAltiria = AltiriaCertPdf($destination, $file_contracto, $prospecto_cedula, false, 'contracto');

			$firmaAltiriaPagare = AltiriaCertPdf($destination, $file_pagare, $prospecto_cedula, false, 'pagare');

			if ($firmaAltiria['response'] == "success" || $firmaAltiriaPagare['response'] == "success") {
				$response = array(
					"response" => "contrato_firmado"
				);
				echo json_encode($response);
				die();
			}

			$stmt = $conn->prepare("UPDATE solicitudes_creditos_numeros SET credito_numero = ?, fecha_registro = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
			$stmt->bind_param("ii", $credito_no, $id_solicitud);
			$stmt->execute();
			$stmt->close();
		}

		$validate_credito_no = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes_creditos_numeros WHERE id_solicitud = $id_solicitud AND del = 0");

		if ($validate_credito_no == 0) {

			$stmt = $conn->prepare("INSERT INTO solicitudes_creditos_numeros (id_solicitud, credito_numero, fecha_registro) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("ii", $id_solicitud, $credito_no);
			$stmt->execute();
			$stmt->close();
		}

		$credito_no = execute_scalar("SELECT credito_numero FROM solicitudes_creditos_numeros WHERE id_solicitud = $id_solicitud");

		$route = '../documents/solicitudes/' . $id_solicitud . '/';
		$route1 = '../documents/solicitudes/' . $id_solicitud;

		$extension = 'pdf';
		$contrato1 = 'document1.' . $extension;
		$pagare = 'document2.' . $extension;
		$contrato2 = 'document3.' . $extension;
		$minuta = 'document4.' . $extension;
		$file1 = $route . $contrato1;
		$file2 = $route . $pagare;
		$file3 = $route . $contrato2;
		$file4 = $route . $minuta;

		//echo $file1.'<br>';
		//echo $file2.'<br>';
		//echo $file3.'<br>';
		//echo $file4.'<br>';
		//die();

		if (!file_exists($route)) {

			mkdir($route, 0777, true);
		}

		if (is_file($file1)) {
			unlink($file1);
		}

		if (is_file($file2)) {
			unlink($file2);
		}

		if (is_file($file3)) {
			unlink($file3);
		}

		if (is_file($file4)) {
			unlink($file4);
		}

		if ($reenvio == 1) {

			if (is_file($route . 'conmbinado_sin_firmar.pdf')) {
				unlink($route . 'conmbinado_sin_firmar.pdf');
			}
		}

		require_once('../assets/class/fpdf/fpdf.php');

		require_once('../vendor/luecano/numero-a-letras/src/NumeroALetras.php');

		require_once('../assets/class/fpdi/src/autoload.php');

		include('./generar_contracto_server.php'); //1ra parte contrato

		include('./generar_pagare_server.php'); // generar pagare

		include('./generar_contracto_server2.php'); //2da parte contracto

		include('./generar_minuta_server.php'); //minuta

		$directory = "../documents/solicitudes/" . $id_solicitud . "/";

		//$files = [$file1, $file2, $file3, $file4]; con file2

		$files = [$file1, $file3, $file4]; //sin file2 por motivos de plataforma

		$pdf = new Fpdi();

		// iterate over array of files and merge
		foreach ($files as $file) {
			$pageCount = $pdf->setSourceFile($file);

			for ($i = 0; $i < $pageCount; $i++) {
				$tpl = $pdf->importPage($i + 1, '/CropBox');
				$specs = $pdf->getTemplateSize($tpl);
				$pdf->addPage($specs['height'] > $specs['width'] ? 'P' : 'L');
				$pdf->useTemplate($tpl);
			}
		}

		// output the pdf as a file (http://www.fpdf.org/en/doc/output.htm)
		$pdf->Output('F', $directory . 'conmbinado_sin_firmar.pdf');

		//echo 'soy el mas perron aqui';
		//die();

		$file_definitive = $directory . 'conmbinado_sin_firmar.pdf';

		$pagare_definitive = $directory . 'document2.pdf';

		$firmaAltiria = AltiriaCertPdf($destination, $file_definitive, $prospecto_cedula, false, 'contracto');

		$firmaAltiriaPagare = AltiriaCertPdf($destination, $pagare_definitive, $prospecto_cedula, false, 'pagare');


		if ($firmaAltiria['response'] == "success" && $firmaAltiriaPagare['response'] == "success") {

			//if ($reenvio == 1) {

			$stmt = $conn->prepare("UPDATE contratos_solicitudes_altiria SET del = b'1' WHERE id_solicitud = ? AND del = 0");
			$stmt->bind_param("i", $id_solicitud);
			$stmt->execute();
			$stmt->close();
			//}

			$id_documento_altiria = $firmaAltiria['id'];
			$id_documento_altiria_pagare = $firmaAltiriaPagare['id'];



			$stmt = $conn->prepare("INSERT INTO contratos_solicitudes_altiria (id_solicitud, id_documento_altiria, tipo, hora_enviado, fecha_registro) VALUES (?, ?, 1, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("is", $id_solicitud, $id_documento_altiria);
			$stmt->execute();
			$rows = $stmt->affected_rows;
			$error = $stmt->error;
			$stmt->close();

			$stmt = $conn->prepare("INSERT INTO contratos_solicitudes_altiria (id_solicitud, id_documento_altiria, tipo, hora_enviado, fecha_registro) VALUES (?, ?, 2, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("is", $id_solicitud, $id_documento_altiria_pagare);
			$stmt->execute();
			$rows = $stmt->affected_rows;
			$error = $stmt->error;
			$stmt->close();

			if ($rows == 1) {

				$id_estado_solicitud = 13;

				if ($reenvio == 1) {

					$rows2 = 1;
				} else {

					$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = ? WHERE id = ?");
					$stmt->bind_param("ii", $id_estado_solicitud, $id_solicitud);
					$stmt->execute();
					$rows2 = $stmt->affected_rows;
					$error2 = $stmt->error;
					$stmt->close();
				}

				if ($rows2 == 1) {

					$id_estado_solicitud = 13;

					$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
					$result1 = qry($query1);
					while ($row1 = mysqli_fetch_array($result1)) {

						$prospecto_nombre = $row1['fullname'];
						$full_producto = $row1['full_producto'];
						$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
						$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
						$prospecto_email = $row1['prospecto_email'];

						$porcentaje = $row1['porcentaje'];
						$precio_producto = $row1['precio_producto'];

						$inicial = ($porcentaje * $precio_producto) / 100;

						$id_estado_solicitud = $row1['id_estado_solicitud'];

						$mostrar = $row1['mostrar'];

						$clase_color = '';

						$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

						if ($validate_resultados_solicitud_cifin != 0) {

							$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
							if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
								$clase_color = "solicitud-aprobada";
								$texto_cifin = "ELEGIBLE A";

								if ($id_resultados_solicitud_cifin == 1) {
									$texto_cifin = "ELEGIBLE AA";
								}
							} else {
								$clase_color = "solicitud-rechazada";
								$texto_cifin = "RECHAZADO";
							}
						}
					}

					$response = array(
						"response" => "success",
						"id_solicitud" => $id_solicitud,
						"nombre" => $prospecto_nombre,
						"modelo" => $full_producto,
						"contacto" => $contacto,
						"email" => $prospecto_email,
						"inicial" => number_format($inicial, 0, '.', '.'),
						"texto_estado" => $mostrar,
						"id_estado_solicitud" => $id_estado_solicitud,
						"clase_color" => $clase_color,
						"texto_cifin" => $texto_cifin
					);
				} else {

					$response = array(
						"response" => "error",
						"error" => $error2
					);
				}
			} else {

				$response = array(
					"response" => "error",
					"error" => $error
				);
			}
		} else {
			$response = array(
				"response" => "error"
			);
		}

		$conn->close();
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "enviar_codigo") {
	try {

		$id_solicitud = $_POST['id_solicitud'];
		$celular = execute_scalar("SELECT prospecto_detalles.prospecto_numero_contacto FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE solicitudes.id = $id_solicitud");

		$validacion = 0;

		//$query_codigo = "SELECT codigo FROM firmas_solicitudes WHERE id_solicitud = $id_solicitud AND terminado = 0";

		$codigo = execute_scalar("SELECT codigo FROM firmas_solicitudes WHERE id_solicitud = $id_solicitud AND terminado = 0");

		include('../includes/httpPHPAltiria.php');

		$altiriaSMS = new AltiriaSMS();
		//$altiriaSMS->setUrl("http://www.altiria.net/api/http");
		$altiriaSMS->setLogin('soporte@tucelular.net.co');
		$altiriaSMS->setPassword('SOPORTE9529');

		//$sDestination = '346xxxxxxxx';
		$sDestination = '57' . $celular;
		//$sDestination = array('346xxxxxxxx','346yyyyyyyy');

		$respuesta = $altiriaSMS->sendSMS($sDestination, 'Tu codigo para firmar los documentos con TC SHOP es ' . $codigo);

		$validate = 'OK dest:' . $celular;

		if ($respuesta == $validate) {


			$response = array("response" => "error_sms");
			echo json_encode($response);
			die();
		} else {


			$validacion = 1;
		}

		if ($validacion == 1) {

			qry("UPDATE firmas_solicitudes SET enviado_codigo = 1 WHERE id_solicitud = $id_solicitud AND terminado = 0");

			$response = array(
				"response" => "success"
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
} else if ($_POST['action'] == "firmar_documento") {
	try {

		$id_solicitud = $_POST['id_solicitud'];

		$codigo1 = $_POST['codigo1'];

		$celular = execute_scalar("SELECT prospecto_detalles.prospecto_numero_contacto FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE solicitudes.id = $id_solicitud");

		$validacion = 0;

		$codigo2 = execute_scalar("SELECT codigo FROM firmas_solicitudes WHERE id_solicitud = $id_solicitud AND terminado = 0");

		if ($codigo1 == $codigo2) {

			include('../includes/httpPHPAltiria.php');

			$altiriaSMS = new AltiriaSMS();
			//$altiriaSMS->setUrl("http://www.altiria.net/api/http");
			$altiriaSMS->setLogin('soporte@tucelular.net.co');
			$altiriaSMS->setPassword('SOPORTE9529');

			//$sDestination = '346xxxxxxxx';
			$sDestination = '57' . $celular;
			//$sDestination = array('346xxxxxxxx','346yyyyyyyy');

			$respuesta = $altiriaSMS->sendSMS($sDestination, 'Ingresa a este link para descargar los documentos firmados con TCSHOP URL: http://192.168.0.143:80/tc_soft/descargar_documentos.php?id=' . $id_solicitud);

			$validate = 'OK dest:' . $celular;

			if ($respuesta == $validate) {


				$response = array("response" => "error_sms");
				echo json_encode($response);
				die();
			} else {


				$validacion = 1;
			}

			if ($validacion == 1) {

				qry("UPDATE firmas_solicitudes SET terminado = 1, fecha_utilizada = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = $id_solicitud AND enviado_codigo = 1");

				// update a estado de aprobada_full

				qry("UPDATE solicitudes SET id_estado_solicitud = 5 WHERE id = $id_solicitud");

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
				"response" => "codigo_incorrecto"
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "validar_equipo") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_existencia = execute_scalar("SELECT id_existencia FROM solicitudes WHERE id = $id_solicitud");

		$response = array(
			"id_existencia" => $id_existencia
		);
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "select_imei") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_existencia = execute_scalar("SELECT id_existencia FROM solicitudes WHERE id = $id_solicitud");

		$imei = execute_scalar("SELECT imei_1 FROM productos_stock WHERE id = $id_existencia");

		if ($imei != '') {

			$response = array(
				"response" => "success",
				"imei" => $imei,
				"id_solicitud" => $id_solicitud
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
} else if ($_POST['action'] == "enrolar_trustonic") {

	require '../includes/trustonicFunctions.php';

	try {

		$imei = $_POST['imei'];

		$id_solicitud = $_POST['id_solicitud'];

		$privateKey = <<<EOD
		-----BEGIN RSA PRIVATE KEY-----
		MIIEowIBAAKCAQEAysMW2yqLrudY9TcjBa42pn5o+NWnm17EPhikfmFZFflyA7sY
		anytpiSzspGw5Ci+R2neD3I8UicIiSlvyFjkz4m+t2b8LCrDd91Bq2A7RDDh0oJu
		pkpW9SEeSr9W6jCYbWNJkRhw0rEvLZ6k9Kt5xBg7tpZwcixH49+P4/9WAaTwsHZM
		ssoXSdDFPqqfBLF9958o+qXYfphhibpSAlY6U7YNumxbXmDgrKDadJOsCDya1dkp
		S0debV5QjuYOaW+2oQblAygvDPV7XbKnenEdg/OkUyYRYh6Ma8qEz+XWL3PWwlJO
		ZvUdGjlyou4Lo4rPAeBoePCAQGTs4ZSnND5vzQIDAQABAoIBAAZYcqerPjHcFaGe
		9x6fZCBzxk87SIKJRbT3ynYWD9arNtE9EreKbVjMvz7wZkvNpp7Phl6PYankhNTe
		EhyAA7EnC2vr8ipjsDhZGUwGt/QbX4XaBSJ5Ix2KysrmEA9GNA8AU3YSAL3nQ6W2
		2Ey77RSg+YVuUdiQ4DMWdQdan1NvysF9WkUKAHWJ1GSmM6O6xvUrvilmaWW6D/BH
		QrmGmwJpAx3W8xfKOhFc6+NmOSP082dsprRy2uIbmTtTcjQm6V3jU0GSPHmO/yam
		xoedr2pE6ytYM45q8uj2R0TUXgtslPAqe0O7OpriQkfKa/ht6WksDjitpH20bLtt
		scKc1QECgYEA50jvLdqABUTdZT6boj9bEReuhzIjt8Io/wRSbjF7xdOULTjS55KC
		vb/bXrZRz/dR9+QDQvh88aXvfje9YPjZaE9ipV0opS/urc5rSexk4J6Nfyh/WB+u
		raqA7B8IwvbE7gdkcJz17JtmlAmymJqi1OmmlBFZOjcI3vE6V4pxtY0CgYEA4G3h
		RL2LaZLU7ZuhnIueu8QEr56/cDoxhG3KXsmSnk/zQSIbJVGhvh+yurxe/9266Uty
		h3MsXW5gzLutSsJz6p1xrf/uDvGv92rXYb/ypX+hoNPbaKzXzUqwCuKWEd/cLCm2
		5KCe6dW2o+2lUvmxc6f1AZ8mysx1lmHiFrVVc0ECgYBSQXIvEKKvPQqwU0/uqDGv
		Joj/tClX1UnXSKY4Yojulo0AeFEp4sV0zqMqUBVQrVkNnJ48Vzu4hZdjgFduAZLw
		sisXPMaT2TQ9xEgMBnLqH/ma28BOixYI1bb8Qx1OmYz4StDB7FDaUXpt8/T8qSJ4
		LpD74B+nwKL7BzgEokxGkQKBgGJ5VCKpWIaWWr93Tk42JQwWUkgMy8sefaOx2N4G
		bELyqbWmlWqmiuW1WuL8zGJQlDbRWAt+ybSlPewXXU40TFBhjQPgDECaY6+YPbEy
		W/GqMu0yiE9Bp974Ko5cJaTqKN1n4Ewo/hF0KJN9LtFdpjMtOx/fCVJqrB20DVQR
		dNEBAoGBAM//5Ea1sKCPNwg2+9harPVdVdhciVAscag+5sKZpQrY8FSMudmOHaAP
		lT7gblR8DYxmdHASgYbra0nw0u4/HGxeosSgeo9HYjMUTUmaLWQCoj4+TuemVSEl
		mRUyHFYevjWQGVAV3STcrSkXCuRxQw1bGEX2HHWvNz01RPrm8/4k
		-----END RSA PRIVATE KEY-----
		EOD;

		$publicKey = <<<EOD
		-----BEGIN PUBLIC KEY-----
		MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAysMW2yqLrudY9TcjBa42
		pn5o+NWnm17EPhikfmFZFflyA7sYanytpiSzspGw5Ci+R2neD3I8UicIiSlvyFjk
		z4m+t2b8LCrDd91Bq2A7RDDh0oJupkpW9SEeSr9W6jCYbWNJkRhw0rEvLZ6k9Kt5
		xBg7tpZwcixH49+P4/9WAaTwsHZMssoXSdDFPqqfBLF9958o+qXYfphhibpSAlY6
		U7YNumxbXmDgrKDadJOsCDya1dkpS0debV5QjuYOaW+2oQblAygvDPV7XbKnenEd
		g/OkUyYRYh6Ma8qEz+XWL3PWwlJOZvUdGjlyou4Lo4rPAeBoePCAQGTs4ZSnND5v
		zQIDAQAB
		-----END PUBLIC KEY-----
		EOD;

		$unix = strtotime("+1 hours");

		$kid = "tucelular2979";

		//Iniciamos la clase
		//$obj = new jwt_token();
		//$obj->getToken($privateKey, $publicKey, $unix, $kid);

		$token_jwt = jwt_token($privateKey, $publicKey, $unix, $kid);

		$token_jwt = "Bearer " . $token_jwt;
		//$jwt_generate = $obj;

		$url = "https://api.tucelular.cloud.trustonic.com";

		$url_register = "/api/public/v1/devices/register";

		$url_get_pin = "/api/public/v1/devicesgetPin";

		$url_status = "/api/public/v1/devices/status";

		$url_update = "/api/public/v1/devices/update";

		$politica = "FINANCIADO";

		$dates_register = '{"devices": [{"assignedPolicy": "' . $politica . '", "imei": "' . $imei . '"}]}';

		$dates_status = '{"devices": [{"imei": "' . $imei . '"}]}';

		$peticion1 = peticion_post($url . $url_status, $token_jwt, $dates_status);

		if (isset($peticion1['devices'][0]['error'])) {
			$error = $peticion1['devices'][0]['error'];
		}

		if (isset($error)) {

			$peticion2 = peticion_post($url . $url_register, $token_jwt, $dates_register);
		} else {

			$dates_update = '{"devices": [{"assignedPolicy": "' . $politica . '", "forceReapply": "true", "imei": "' . $imei . '"}]}';
			$peticion2 = peticion_post($url . $url_update, $token_jwt, $dates_update);
		}

		if (isset($peticion2['devices'][0]['error'])) {
			$error2 = $peticion2['devices'][0]['error'];
		}

		if (isset($error2)) {

			$response = array(

				"response" => "error",
				"jwt" => $token_jwt,
				"dispositivos" => $peticion2

			);
		} else {

			// pasamos la solicitud al estado 12 = aprobada sin contracto.

			qry("UPDATE solicitudes SET id_estado_solicitud = 12 WHERE solicitudes.id = $id_solicitud");

			$id_estado_solicitud = 12;

			$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";

			$result1 = qry($query1);

			while ($row1 = mysqli_fetch_array($result1)) {

				$prospecto_nombre = $row1['fullname'];
				$full_producto = $row1['full_producto'];
				$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
				$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
				$prospecto_email = $row1['prospecto_email'];

				$porcentaje = $row1['porcentaje'];
				$precio_producto = $row1['precio_producto'];

				$inicial = ($porcentaje * $precio_producto) / 100;

				$id_estado_solicitud = $row1['id_estado_solicitud'];

				$mostrar = $row1['mostrar'];

				$clase_color = '';

				$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

				if ($validate_resultados_solicitud_cifin != 0) {

					$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
					if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {

						$clase_color = "solicitud-aprobada";
						$texto_cifin = "ELEGIBLE A";

						if ($id_resultados_solicitud_cifin == 1) {
							$texto_cifin = "ELEGIBLE AA";
						}
					} else {

						$clase_color = "solicitud-rechazada";
						$texto_cifin = "RECHAZADO";
					}
				}
			}

			$response = array(

				"response" => "success",
				"id_solicitud" => $id_solicitud,
				"nombre" => $prospecto_nombre,
				"modelo" => $full_producto,
				"contacto" => $contacto,
				"prospecto_numero_contacto" => $prospecto_numero_contacto,
				"email" => $prospecto_email,
				"inicial" => number_format($inicial, 0, '.', '.'),
				"texto_estado" => $mostrar,
				"id_estado_solicitud" => $id_estado_solicitud,
				"clase_color" => $clase_color,
				"texto_cifin" => $texto_cifin,
				"jwt" => $token_jwt,
				"dispositivos" => $peticion2

			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "verificar_firmado") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_proccess = execute_scalar("SELECT contratos_solicitudes_altiria.id FROM contratos_solicitudes_altiria WHERE id_solicitud = $id_solicitud AND tipo = 1 AND del = 0");

		$id_documento_altiria = execute_scalar("SELECT id_documento_altiria FROM contratos_solicitudes_altiria WHERE id = $id_proccess");

		$hora_enviado = execute_scalar("SELECT contratos_solicitudes_altiria.hora_enviado FROM contratos_solicitudes_altiria WHERE id = $id_proccess");

		$fecha_registro = execute_scalar("SELECT contratos_solicitudes_altiria.fecha_registro FROM contratos_solicitudes_altiria WHERE id = $id_proccess");

		$id_proccess_pagare = execute_scalar("SELECT contratos_solicitudes_altiria.id FROM contratos_solicitudes_altiria WHERE id_solicitud = $id_solicitud AND tipo = 2 AND del = 0");

		$id_documento_altiria_pagare = execute_scalar("SELECT id_documento_altiria FROM contratos_solicitudes_altiria WHERE id = $id_proccess_pagare");

		$hora_enviado = execute_scalar("SELECT contratos_solicitudes_altiria.hora_enviado FROM contratos_solicitudes_altiria WHERE id = $id_proccess_pagare");

		$fecha_registro = execute_scalar("SELECT contratos_solicitudes_altiria.fecha_registro FROM contratos_solicitudes_altiria WHERE id = $id_proccess_pagare");

		$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

		$cedula_prospecto = execute_scalar("SELECT prospecto_cedula FROM prospectos WHERE id = $id_prospecto");

		$id_existencia = execute_scalar("SELECT id_existencia FROM solicitudes WHERE id = $id_solicitud"); //necesaria para cambiar el estado al momento de firmar el contracto a producto vendido.

		$validacion_firma = Altiria_check_pdf($id_documento_altiria);

		$validacion_firma2 = Altiria_check_pdf($id_documento_altiria_pagare);


		if ($validacion_firma['response'] == "success" && $validacion_firma2['response'] == "success") {

			if ($validacion_firma['fileStatus'] == "signed" && $validacion_firma2['fileStatus'] == "signed") {

				if ($validacion_firma['document_signed_status'] == "signed" && $validacion_firma2['document_signed_status'] == "signed") {

					//$filename = 'http://cckk.ca/KE645R26/geico.com_SEO_Domain_Dashboard_20121101_00.pdf';
					$filename_link = $validacion_firma['document_signed_url']; //contrato
					$filename_link2 = $validacion_firma2['document_signed_url']; //PAGARE
					$filename = "contrato_minuta_firm_" . $cedula_prospecto . ".pdf";
					$filename2 = "pagare_firmado_" . $cedula_prospecto . ".pdf";

					file_put_contents(
						'../documents/solicitudes/' . $id_solicitud . '/' . $filename, // where to save file
						file_get_contents($filename_link)
					);

					file_put_contents(
						'../documents/solicitudes/' . $id_solicitud . '/' . $filename2, // where to save file
						file_get_contents($filename_link2)
					);

					$file1 = '../documents/solicitudes/' . $id_solicitud . '/' . $filename;

					$file2 = '../documents/solicitudes/' . $id_solicitud . '/' . $filename2;

					//die();

					if (is_file($file1) && is_file($file2)) {

						qry("DELETE FROM contratos_solicitudes_altiria WHERE id = $id_proccess");
						qry("DELETE FROM contratos_solicitudes_altiria WHERE id = $id_proccess_pagare");

						$query_insert = "INSERT INTO contratos_solicitudes_altiria (id, id_solicitud, id_documento_altiria, tipo, hora_enviado, firmado, fecha_registro) VALUES ($id_proccess, $id_solicitud, '$id_documento_altiria', '$hora_enviado', b'1', '$fecha_registro')";

						$conn = new mysqli($host, $user, $pass, $db);
						$stmt = $conn->prepare("INSERT INTO contratos_solicitudes_altiria (id, id_solicitud, id_documento_altiria, tipo, hora_enviado, firmado, fecha_registro) VALUES (?, ?, ?, 1, ?, b'1', ?)");
						$stmt->bind_param("iisss", $id_proccess, $id_solicitud, $id_documento_altiria, $hora_enviado, $fecha_registro);
						$stmt->execute();
						$rows = $stmt->affected_rows;
						$error = $stmt->error;
						$stmt->close();

						$stmt = $conn->prepare("INSERT INTO contratos_solicitudes_altiria (id, id_solicitud, id_documento_altiria, tipo, hora_enviado, firmado, fecha_registro) VALUES (?, ?, ?, 2, ?, b'1', ?)");
						$stmt->bind_param("iisss", $id_proccess_pagare, $id_solicitud, $id_documento_altiria_pagare, $hora_enviado, $fecha_registro);
						$stmt->execute();
						$rows2 = $stmt->affected_rows;
						$error2 = $stmt->error;
						$stmt->close();

						if ($rows == 1 && $rows2 == 1) {

							$sandbox = 0;

							$user_beriblock = "soporte@tucelular.net.co";
							$pass_beriblock = "Ampg2979+";

							$url = "https://api-prod.beriblock.com/";

							$user_transfer = "d086ff61-cc13-44ea-88c6-6ecf3295e9e4";

							$url_based = "https://app.beriblock.com/";

							if ($sandbox == 1) {

								$user_beriblock = "financiero@tucelular.net.co";
								$pass_beriblock = "Ampg2979+";

								$url = "https://beriblock-sandbox.beriblock.com/";

								$user_transfer = "6d84861c-aa19-558d-98a4-8a8b69963d47";

								$url_based = "https://beriblock-sandbox-app.beriblock.com/";
							}

							$veriblock_credentials = loginApiBeriblock($user_beriblock, $pass_beriblock, $url);

							if (isset($veriblock_credentials['response'])) {

								$response = array(
									"response" => "error_nodo"
								);

								echo json_encode($response);
								die();
							} else {

								$message = $veriblock_credentials['metaData']['message'];
								$request_id = $veriblock_credentials['metaData']['request_id'];
							}

							if ($message == "You have successfully loggedIn.") {

								$url_user_info = $url . "api/v1/user-info";
								$access_token = 'Bearer ' . $veriblock_credentials['result']['access_token'];

								$veriblock_user = peticion_get($url_user_info, $access_token);

								$veriblock_user = JSON2Array($veriblock_user);

								//print_r($veriblock_user);

								//die();

								$message_user = $veriblock_user['metaData']->message;

								if ($message_user == "User details have been successfully fetched.") {

									$account_id = $veriblock_user['result']->user->account_id;

									$localIp = gethostbyname(gethostname());
									$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");
									$cedula_user = execute_scalar("SELECT prospecto_cedula FROM prospectos WHERE id = $id_prospecto"); // as fingerprint

									$path = "../documents/solicitudes/" . $id_solicitud . "/contrato_minuta_firm_" . $cedula_user . ".pdf";
									$path2 = "../documents/solicitudes/" . $id_solicitud . "/pagare_firmado_" . $cedula_user . ".pdf";

									$url_upload_document = $url . "api/v1/users/" . $account_id . "/documents";

									$name = 'contrato_' . $cedula_user;

									$description = 'contrato, anexo y minuta_' . $cedula_user;

									$beriblock_upload = beriblock_upload_documents($path, $url_upload_document, $cedula_user, $url_based, $localIp, $access_token, $name, $description);

									$name2 = 'pagaré_' . $cedula_user;

									$description2 = 'pagaré_' . $cedula_user;

									$beriblock_upload2 = beriblock_upload_documents_pagare($path2, $url_upload_document, $cedula_user, $url_based, $localIp, $access_token, $name2, $description2);


									if ($beriblock_upload->metaData->message == "Document has been successfully uploaded" && $beriblock_upload2->metaData->message == "Document has been successfully uploaded") {

										$id_document_contracto = $beriblock_upload->result->document->id;
										$id_document_pagare = $beriblock_upload2->result->document->id;
									} else if ($beriblock_upload->metaData->message == "The document already exists, it was loaded successfully" && $beriblock_upload2->metaData->message == "The document already exists, it was loaded successfully") {

										$id_document_contracto = $beriblock_upload->result->document->document_id;
										$id_document_pagare = $beriblock_upload2->result->document->document_id;
									} else {

										$response = array(
											"response" => "error_upload"
										);

										echo json_encode($response);

										die();
									}

									//Endozando a garantias
									/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://beriblock-sandbox.beriblock.com/api/v1/users/2662f7d2-1d5e-4f26-9189-44a9bd86bf00/documents/transfer',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
"userId" :"6d84861c-aa19-558d-98a4-8a8b69963d47",
"documentId":"7926c5a8-a5c3-44bf-8483-612aff85859f",
"language":"es"
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJxVjVOVEtoUEtucjItc3RZVmNWVkMyVFhuNGpBOFlfMDVOMmlwTVY5TDRVIn0.eyJqdGkiOiJjYjBjYjk5NC0zZmVhLTRjOGItOTY2My00NDg3ZWY5MGRhOTgiLCJleHAiOjE2MzM1NzM4MzUsIm5iZiI6MCwiaWF0IjoxNjMzNTU1ODM1LCJpc3MiOiJodHRwOi8va2V5Y2xvYWs6ODA4MC9hdXRoL3JlYWxtcy9iZXJpYmxvY2siLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiZjk5MjZjMzQtNzE4OC00ZTI0LWExMDMtMDZjYjkwM2U0MzlhIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoicmVhY3QiLCJhdXRoX3RpbWUiOjAsInNlc3Npb25fc3RhdGUiOiIwOWI3MmRmMi0xMzlmLTQxMzctYTc2NS1iZDI4NDAzZmUwNzciLCJhY3IiOiIxIiwiYWxsb3dlZC1vcmlnaW5zIjpbIioiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbIm9yZy1lbXBsb3llZSIsIm9mZmxpbmVfYWNjZXNzIiwidW1hX2F1dGhvcml6YXRpb24iXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6ImVtYWlsIHByb2ZpbGUiLCJlbWFpbF92ZXJpZmllZCI6ZmFsc2UsIm5hbWUiOiJDQVJMT1MgRkVSTkFORE8gUEVSQUZBTiBHQUxBUlpBIiwicHJlZmVycmVkX3VzZXJuYW1lIjoiZmluYW5jaWVyb0B0dWNlbHVsYXIubmV0LmNvIiwiZ2l2ZW5fbmFtZSI6IkNBUkxPUyBGRVJOQU5ETyIsImZhbWlseV9uYW1lIjoiUEVSQUZBTiBHQUxBUlpBIiwiZW1haWwiOiJmaW5hbmNpZXJvQHR1Y2VsdWxhci5uZXQuY28ifQ.cSk7kUpXJUva2LMy1P5DU1UhQfeX9mQkwYdGOoORy3YLu1p_asZSl34OFJXxS2EOqN5G6vNm8oconzJUldPVmoIcvg6WERLQQWOW21sFtum5mh_ra9dThbNsJ2kIQhW1bxrmTLR6Oa7OY5GkFIlBhFpchkKHXWeBsmeKzuKJl9bNSVhz8T1KoDpHoVPnF-yPrD7RilfjFu0ajXnFNi_QsIvwAlMWZiQ6cZLae5F5xdhkdnpAlyFEyIiqliTmRtqEzdhknpje3v1Coz4bDfd1isEIr7Ey9tpL8aSLv1k2AZipgZLesnD0TJCDy0EGt-PhzFCygPvb4Z65hULE1A-ALQ',
    'Content-Type: application/json',
    'Cookie: connect.sid=s%3AQEv6a4KeO2BosrtAIU662BE3UJu5Lrac.mdDUdXF%2FM0v%2FVxdOvr%2Br2y34qEM0LlUXY8I6CDj4KX4'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

*/

									$url_transfer = $url . "api/v1/users/" . $account_id . "/documents/transfer";

									$dates_contrato = '{
										"userId" :"' . $user_transfer . '",
										"documentId":"' . $id_document_contracto . '",
										"language":"es"
										}';

									$dates_pagare = '{
										"userId" :"' . $user_transfer . '",
										"documentId":"' . $id_document_pagare . '",
										"language":"es"
									}';
									//echo $dates_contrato.'<br>';
									//echo $dates_pagare;
									//die();

									$endoce_array_contrato = peticion_post($url_transfer, $access_token, $dates_contrato);

									$endoce_array_pagare = peticion_post($url_transfer, $access_token, $dates_pagare);

									//equipo vendido
									qry("UPDATE productos_stock SET id_estado_producto = 3 WHERE id = $id_existencia");
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

							$id_estado_solicitud = 5;

							$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = ? WHERE id = ?");
							$stmt->bind_param("ii", $id_estado_solicitud, $id_solicitud);
							$stmt->execute();
							$rows1 = $stmt->affected_rows;
							$error1 = $stmt->error;
							$stmt->close();
							if ($rows1 == 1) {

								$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar, prospectos.prospecto_cedula FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
								$result1 = qry($query1);
								while ($row1 = mysqli_fetch_array($result1)) {

									$prospecto_nombre = $row1['fullname'];
									$full_producto = $row1['full_producto'];
									$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
									$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
									$prospecto_email = $row1['prospecto_email'];

									$porcentaje = $row1['porcentaje'];
									$precio_producto = $row1['precio_producto'];

									$inicial = ($porcentaje * $precio_producto) / 100;

									$id_estado_solicitud = $row1['id_estado_solicitud'];

									$mostrar = $row1['mostrar'];

									$prospecto_cedula = $row1['prospecto_cedula'];

									$clase_color = '';

									$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

									if ($validate_resultados_solicitud_cifin != 0) {

										$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
										if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
											$clase_color = "solicitud-aprobada";
											$texto_cifin = "ELEGIBLE A";

											if ($id_resultados_solicitud_cifin == 1) {
												$texto_cifin = "ELEGIBLE AA";
											}
										} else {
											$clase_color = "solicitud-rechazada";
											$texto_cifin = "RECHAZADO";
										}
									}
								}

								$response = array(
									"response" => "success",
									"id_solicitud" => $id_solicitud,
									"nombre" => $prospecto_nombre,
									"modelo" => $full_producto,
									"contacto" => $contacto,
									"email" => $prospecto_email,
									"inicial" => number_format($inicial, 0, '.', '.'),
									"texto_estado" => $mostrar,
									"id_estado_solicitud" => $id_estado_solicitud,
									"clase_color" => $clase_color,
									"texto_cifin" => $texto_cifin,
									"prospecto_cedula" => $prospecto_cedula,
									"beriblock_upload" => $beriblock_upload
								);
							} else {
								$response = array(
									"response" => "error",
									"error1" => $error1
								);
							}
						} else {

							$response = array(
								"response" => "error",
								"error" => $error
							);
						}
					} else {

						$response = array(
							"response" => "error"
						);
					}
				} else {

					$response = array(
						"response" => "falta_firmar",
						"num" => 2
					);
				}
			} else {

				$response = array(
					"response" => "falta_firmar",
					"num" => 1
				);
			}
		} else if ($validacion_firma['response'] == "error") {

			$response = array(
				"response" => "error"
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
} else if ($_POST['action'] == "subir_garantias") {

	/*
	try {

		$id_solicitud = $_POST['id_solicitud'];

		$user_beriblock = "financiero@tucelular.net.co";
		$pass_beriblock = "Ampg2979+";

		$url = "https://beriblock-sandbox.beriblock.com/";

		$veriblock_credentials = loginApiBeriblock($user_beriblock, $pass_beriblock);

		$message = $veriblock_credentials['metaData']['message'];
		$request_id = $veriblock_credentials['metaData']['request_id'];

		if($message == "You have successfully loggedIn."){

			$url_user_info = $url."api/v1/user-info";
			$access_token = 'Bearer '.$veriblock_credentials['result']['access_token'];

			$veriblock_user = peticion_get($url_user_info, $access_token);

			$veriblock_user = JSON2Array($veriblock_user);

			//print_r($veriblock_user);

			//die();

			$message_user = $veriblock_user['metaData']->message;

			if($message_user == "User details have been successfully fetched."){

				$account_id = $veriblock_user['result']->user->account_id;
				
				$localIp = gethostbyname(gethostname());
				$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");
				$cedula_user = execute_scalar("SELECT prospecto_cedula FROM prospectos WHERE id = $id_prospecto"); // as fingerprint

				$path = "../documents/solicitudes/".$id_solicitud."/combinado_firmado_".$cedula_user.".pdf";

				$url_upload_document = $url."api/v1/users/".$account_id."/documents";

				$name = 'contrato_'.$cedula_user;

				$description = 'contrato, anexo y minuta_'.$cedula_user;

				$beriblock_upload = beriblock_upload_documents($path, $url_upload_document, $cedula_user, $url, $localIp, $access_token, $name, $description);

				print_r($beriblock_upload);
				die();


			}


		}

	} catch (Exception $e) {
		$response = array(
			'error'=> $e-> getMessage()
		);
	}

	echo json_encode($response);

	*/
} else if ($_POST['action'] == "eliminar_solicitud") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_existencia = execute_scalar("SELECT id_existencia FROM solicitudes WHERE id = $id_solicitud");

		$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE solicitudes SET del  = b'1' WHERE id = ?");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		if ($stmt->affected_rows == 1) {

			qry("UPDATE productos_stock SET id_estado_producto = 1 WHERE id = $id_existencia"); //reinicia existencia

			qry("UPDATE prospectos SET id_plataforma = 0, id_estado_prospecto = 0, confirmar_aprobado = b'0' WHERE id = $id_prospecto"); //reinicia plataforma

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
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
} else if ($_POST['action'] == "crear_solicitud_p") {

	try {

		$id_prospecto = $_POST['prospecto_solicitud'];
		$id_usuario = $_POST['id_usuario'];
		$id_plataforma = 3;

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("INSERT INTO solicitudes (id_prospecto, id_existencia, id_terminos_prestamo, id_frecuencia_pago, id_porcentaje_inicial, precio_producto, id_estado_solicitud, fecha_inicio_credito, id_usuario) VALUES (?, 0, 0, 0, 0, 0, 1, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), ?)");
		$stmt->bind_param("ii", $id_prospecto, $id_usuario);
		$stmt->execute();
		$id_solicitud = $stmt->insert_id;
		$error = $stmt->error;
		$rows = $stmt->affected_rows;
		$stmt->close();

		if ($rows == 1) {

			$stmt = $conn->prepare("UPDATE `prospectos` SET `id_plataforma` = ?, confirmar_aprobado = b'1' WHERE `prospectos`.`id` = ?");
			$stmt->bind_param("ii", $id_plataforma, $id_prospecto);
			$stmt->execute();
			$error2 = $stmt->error;
			$rows2 = $stmt->affected_rows;
			$stmt->close();

			if ($rows2 == 1) {

				$query1 = "SELECT solicitudes.id AS id_solicitud, CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS fullname, CONCAT(modelos.nombre_modelo, '-', capacidades_telefonos.capacidad, '-', productos_stock.imei_1) AS full_producto, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, porcentajes_iniciales.porcentaje, solicitudes.precio_producto, solicitudes.id_estado_solicitud, estados_solicitudes.mostrar FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN colores_productos ON productos_stock.id_color = colores_productos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id WHERE solicitudes.id = $id_solicitud";
				$result1 = qry($query1);
				while ($row1 = mysqli_fetch_array($result1)) {

					$prospecto_nombre = $row1['fullname'];
					$full_producto = $row1['full_producto'];
					$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
					$contacto = '(' . substr($prospecto_numero_contacto, 0, 3) . ')' . substr($prospecto_numero_contacto, 3, 3) . '-' . substr($prospecto_numero_contacto, 6, 4);
					$prospecto_email = $row1['prospecto_email'];

					$porcentaje = $row1['porcentaje'];
					$precio_producto = $row1['precio_producto'];

					$inicial = ($porcentaje * $precio_producto) / 100;

					$id_estado_solicitud = $row1['id_estado_solicitud'];

					$mostrar = $row1['mostrar'];

					$clase_color = '';

					$validate_resultados_solicitud_cifin = execute_scalar("SELECT COUNT(id) AS total FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");

					if ($validate_resultados_solicitud_cifin != 0) {

						$id_resultados_solicitud_cifin = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud");
						if ($id_resultados_solicitud_cifin == 1 || $id_resultados_solicitud_cifin == 2) {
							$clase_color = "solicitud-aprobada";
						} else {
							$clase_color = "solicitud-rechazada";
						}
					}
				}

				$response = array(
					"response" => "success",
					"id_solicitud" => $id_solicitud,
					"nombre" => $prospecto_nombre,
					"modelo" => $full_producto,
					"contacto" => $contacto,
					"email" => $prospecto_email,
					"inicial" => number_format($inicial, 0, '.', '.'),
					"texto_estado" => $mostrar,
					"id_estado_solicitud" => $id_estado_solicitud,
					"clase_color" => $clase_color
				);
			} else {

				$response = array(
					"response" => "error",
					"error2" => $error2
				);
			}
		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);
		}

		$conn->close();
	} catch (\Throwable $th) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "cambiar_cifin") {

	try {

		$id_cifin = $_POST['id_resultado'];

		$id_solicitud = $_POST['id_solicitud'];

		$id_cifin_old = execute_scalar("SELECT id_estado_cifin FROM resultados_solicitud_cifin WHERE id_solicitud = $id_solicitud AND del = 0");

		$id_cifin_producto = $_POST['id_cifin_producto'];

		$alerta = 0;

		if ($id_cifin_old != $id_cifin) {

			if ($id_cifin == $id_cifin_producto) {

				$alerta = 0;
			} else {

				if ($id_cifin == 1) {
					$alerta = 0;
				} else if ($id_cifin == 2 && $id_cifin_producto == 1) {
					$alerta = 1;
				} else if ($id_cifin == 3 && $id_cifin_producto == 2) {
					$alerta = 1;
				} else if ($id_cifin == 3 && $id_cifin_producto == 1) {
					$alerta = 1;
				}
			}

			if ($alerta == 0) {

				$query = "UPDATE resultados_solicitud_cifin SET id_estado_cifin = $id_cifin WHERE id_solicitud = $id_solicitud AND del = 0";
				$result = qry($query);

				if ($result) {

					$estado_cifin = execute_scalar("SELECT estado FROM resultados_cifin WHERE id = $id_cifin");

					$response = array(
						"response" => "success",
						"id_cifin" => $id_cifin,
						"id_cifin_old" => $id_cifin_old,
						"estado" => $estado_cifin
					);
				} else {

					$response = array(
						"response" => "error"
					);
				}
			} else {
				$response = array(
					"response" => "afecta_cel",
					"id_cifin" => $id_cifin,
					"id_solicitud" => $id_solicitud
				);
			}
		} else {

			$response = array(
				"response" => "el_mismo"
			);
		}
	} catch (\Throwable $th) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "confir_cambiar_cifin") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_cifin = $_POST['id_cifin'];

		$id_existencia = execute_scalar("SELECT id_existencia FROM solicitudes WHERE id = $id_solicitud");

		$query = "UPDATE solicitudes SET id_existencia = 0, id_terminos_prestamo = 0, id_frecuencia_pago = 0, id_porcentaje_inicial = 0, precio_producto = 0, id_estado_solicitud = 4 WHERE id = $id_solicitud";

		$result = qry($query);

		if($result){

			$query = "UPDATE productos_stock SET id_estado_producto = 1 WHERE id = $id_existencia";

			$result = qry($query);

			if($result){

				$query = "UPDATE resultados_solicitud_cifin SET id_estado_cifin = $id_cifin WHERE id_solicitud = $id_solicitud AND del = 0";
				$result = qry($query);

				if ($result) {

					$estado_cifin = execute_scalar("SELECT estado FROM resultados_cifin WHERE id = $id_cifin");

					$response = array(
						"response" => "success",
						"id_cifin" => $id_cifin,
						"estado" => $estado_cifin
					);

				} else {

					$response = array(
						"response" => "error"
					);

				}

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
		
	} catch (\Throwable $th) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
}
