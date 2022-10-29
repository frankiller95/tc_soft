<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

require_once('../includes/connection.php');
require_once('../includes/functions.php');

// No mostrar los errores de PHP
//error_reporting(0);

if ($_POST['action'] == "reporte_puntos_gane") {

	try {

		//var_dump($_POST);
		//die();

		$fecha_inicio = $_POST['fecha_inicio'];
		if ($fecha_inicio !== "") {
			$fecha_inicio = substr($fecha_inicio, 6, 4) . '-' . substr($fecha_inicio, 0, 2) . '-' . substr($fecha_inicio, 3, 2) . ' ' . '00:00:00';
		}
		$fecha_final = $_POST['fecha_final'];
		if ($fecha_final !== '') {
			$fecha_final = substr($fecha_final, 6, 4) . '-' . substr($fecha_final, 0, 2) . '-' . substr($fecha_final, 3, 2) . ' ' . '23:59:59';
		}

		$nombre_digitador = '';
		$apellidos_digitador = '';
		$cedula_digitador = '';
		$nombre_punto = '';

		if (isset($_POST['puntos_gane'])) {
			$puntos_gane = $_POST['puntos_gane'];
			$nombre_punto = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE ID = $puntos_gane");
		} else {
			$puntos_gane = 0;
		}

		if (isset($_POST['digitadores_gane'])) {
			$digitador_gane = $_POST['digitadores_gane'];
			$nombre_digitador = execute_scalar("SELECT nombre FROM usuarios WHERE id = $digitador_gane");
			$apellidos_digitador = execute_scalar("SELECT apellidos FROM usuarios WHERE id = $digitador_gane");
			$cedula_digitador = execute_scalar("SELECT cedula FROM usuarios WHERE id = $digitador_gane");
		} else {
			$digitador_gane = 0;
		}

		//echo $total_orders;
		$report1array = array();

		$query_hecho = 0;


		if ($fecha_inicio == '' && $fecha_final == '') {

			if (empty($puntos_gane) || $puntos_gane == 0) {

				if (empty($digitador_gane) || $digitador_gane == 0) {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1";

				} else {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_usuario = $digitador_gane";
				}

				$ejecutado = 1;
			} else {

				if (empty($digitador_gane) || $digitador_gane == 0) {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane";
				} else {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND usuarios_puntos_gane.id_usuario = $digitador_gane";
				}

				$ejecutado = 2;
			}

			$query_hecho = 1;
		} else if ($fecha_inicio != "" && $fecha_final == "") {

			if (empty($puntos_gane) || $puntos_gane == 0) {

				if (empty($digitador_gane) || $digitador_gane == 0) {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND prospectos.fecha_registro >= '$fecha_inicio'";
				} else {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND prospectos.fecha_registro >= '$fecha_inicio' AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND usuarios_puntos_gane.id_usuario = $digitador_gane";
				}

				$ejecutado = 3;
			} else {

				if (empty($digitador_gane) || $digitador_gane == 0) {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND prospectos.fecha_registro >= '$fecha_inicio'";
				} else {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND prospectos.fecha_registro >= '$fecha_inicio' AND usuarios_puntos_gane.id_usuario = $digitador_gane";
				}

				$ejecutado = 4;
			}

			$query_hecho = 1;
		} else if ($fecha_inicio == "" && $fecha_final != '') {

			if (empty($puntos_gane) || $puntos_gane == 0) {

				if (empty($digitador_gane) || $digitador_gane == 0) {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND prospectos.fecha_registro <= '$fecha_final'";
				} else {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND prospectos.fecha_registro <= '$fecha_final' AND usuarios_puntos_gane.id_usuario = $digitador_gane";
				}

				$ejecutado = 5;
			} else {

				if (empty($digitador_gane) || $digitador_gane == 0) {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND prospectos.fecha_registro <= '$fecha_inicio'";
				} else {

					$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND prospectos.fecha_registro <= '$fecha_inicio' AND usuarios_puntos_gane.id_usuario = $digitador_gane";
				}

				$ejecutado = 6;
			}

			$query_hecho = 1;
		} else if (empty($puntos_gane) || $puntos_gane == 0 && $query_hecho == 0) {

			if (empty($digitador_gane) || $digitador_gane == 0) {

				$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND prospectos.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_final'";
			} else {

				$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND prospectos.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_final' AND usuarios_puntos_gane.id_usuario = $digitador_gane";
			}

			$ejecutado = 7;
		} else {

			if (empty($digitador_gane) || $digitador_gane == 0) {

				$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND prospectos.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_final'";
			} else {

				$query1 = "SELECT prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.contacto_w, ciudades.ciudad, departamentos.departamento, marcas.marca_producto, prospectos.id_usuario_responsable, puntos_gane.COD, puntos_gane.AGENCIA, puntos_gane.DIRECCION, DATE_FORMAT(prospectos.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha_creado, usuarios.cedula AS digitador_cedula FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN modelos_prospectos ON modelos_prospectos.id_prospecto = prospectos.id LEFT JOIN marcas ON modelos_prospectos.id_marca = marcas.id LEFT JOIN usuarios ON prospectos.id_usuario_responsable = usuarios.id LEFT JOIN usuarios_puntos_gane ON usuarios_puntos_gane.id_usuario = usuarios.id LEFT JOIN puntos_gane ON usuarios_puntos_gane.id_punto_gane = puntos_gane.id WHERE prospectos.del = 0 AND usuarios.cliente_gane = 1 AND usuarios_puntos_gane.id_punto_gane = $puntos_gane AND prospectos.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_final' AND usuarios_puntos_gane.id_usuario = $digitador_gane";
			}

			$ejecutado = 8;
		}

		//echo $query1;
		//echo $ejecutado;
		//die();

		$result = qry($query1);
		while ($row = mysqli_fetch_array($result)) {

			$id_prospecto = $row['id_prospecto'];
			$prospecto_cedula = $row['prospecto_cedula'];
			$prospecto_nombre = $row['prospecto_nombre'];
			$prospecto_apellidos = $row['prospecto_apellidos'];
			$ciudad = $row['ciudad'];
			$departamento = $row['departamento'];
			$nombre_punto = $row['AGENCIA'];
			$direccion_punto = $row['DIRECCION'];
			$codigo = $row['COD'];
			$fecha_creado = $row['fecha_creado'];
			$digitador_cedula = $row['digitador_cedula'];

			$estado_prospecto_dc = execute_scalar("SELECT resultado_dc_prospecto FROM prospectos WHERE id = $id_prospecto");

			$estado_prospecto = execute_scalar("SELECT estados_prospectos.estado_prospecto FROM prospectos LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id WHERE prospectos.id = $id_prospecto");

			$newarray = array("id_prospecto" => $id_prospecto, "prospecto_cedula" => $prospecto_cedula, "prospecto_nombre" => $prospecto_nombre, "prospecto_apellidos" => $prospecto_apellidos, "ciudad" => $ciudad, "departamento" => $departamento, "AGENCIA" => $nombre_punto, "DIRECCION" => $direccion_punto, "codigo" => $codigo, "fecha_creado" => $fecha_creado, "digitador_cedula" => $digitador_cedula, "estado" => $estado_prospecto, "estado_prospecto_dc" => $estado_prospecto_dc);
			array_push($report1array, $newarray);
		}

		$response = array(
			"response" => 'success',
			"ejecutado" => $ejecutado,
			"puntos_gane" => $puntos_gane,
			"digitador_gane" => $digitador_gane,
			"nombre_digitador" => $nombre_digitador,
			"apellidos_digitador" => $apellidos_digitador,
			"cedula_digitador" => $cedula_digitador,
			"nombre_punto" => $nombre_punto
		);


		array_push($response, $report1array);
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode(utf8ize($response));


} else if ($_POST['action'] == "insertar_domi") {

	try {

		//var_dump($_POST);
		//die();

		$pickup_date = $_POST['pickup_date'];
		$pickup_date = substr($pickup_date, 6, 4) . '-' . substr($pickup_date, 0, 2) . '-' . substr($pickup_date, 3, 2);
		$pickup_start = $_POST['pickup_start'];
		$pickup_end = $_POST['pickup_end'];

		$id_domiciliario = 0;
		$guia_servientrega = 0;
		$responsable_tienda = 0;
		$imei_referencia = 0;

		$id_solicitud = $_POST['id_solicitud'];
		$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");
		$id_plataforma = execute_scalar("SELECT id_plataforma FROM prospectos WHERE id = $id_prospecto");

		$medio_envio = $_POST['medio_envio'];

		$entrega_confirmada = 0;
		$fecha_entrega_confirmada = null;

		$clase_color = '';

		if ($medio_envio == 1) {

			$id_domiciliario = $_POST['domiciliario_solicitud'];

			$clase_color = "solicitud-asignada";
		} else if ($medio_envio == 2) {

			$guia_servientrega = $_POST['guia_servientrega'];

			$sobreflete_servientrega = $_POST['sobreflete_servientrega'];

            $sobreflete_servientrega = str_replace('.', '', $sobreflete_servientrega);

			$destino_servientrega = $_POST['destino_servientrega'];

			$bolsa_servientrega = $_POST['bolsa_servientrega'];

			$clase_color = "solicitud-asignada-servientrega";

		} else if ($medio_envio == 3) {

			$responsable_tienda = $_POST['responsable_entrega'];

			$clase_color = "solicitud-asignada-tienda";
		}

		$validate_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

		if ($validate_estado_prospecto != 0) {

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO solicitudes_domiciliarios (id_solicitud, id_domiciliario, solicitud_fecha_entrega, solicitud_inicio_tiempo, solicitud_final_tiempo, entrega_confirmada, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("iisssi", $id_solicitud, $id_domiciliario, $pickup_date, $pickup_start, $pickup_end, $entrega_confirmada);
			$stmt->execute();
			$error = $stmt->error;
			$rows = $stmt->affected_rows;
			$stmt->close();

			if ($rows == 1) {

				$id_estado_solicitud = 6;

				if ($id_plataforma == 1 || $id_plataforma == 2) {

					$imei_referencia = $_POST['imei_referencia'];

					$stmt = $conn->prepare("UPDATE prospectos SET imei_referencia = ?, id_medio_envio = ? WHERE id = ?");
					$stmt->bind_param("iii", $imei_referencia, $medio_envio, $id_prospecto);
				} else {

					$stmt = $conn->prepare("UPDATE prospectos SET id_medio_envio = ? WHERE id = ?");
					$stmt->bind_param("ii", $medio_envio, $id_prospecto);
				}

				$stmt->execute();
				$error = $stmt->error;
				$rows = $stmt->affected_rows;
				$stmt->close();

				$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = ? WHERE id = ?");
				$stmt->bind_param("ii", $id_estado_solicitud, $id_solicitud);
				$stmt->execute();
				$error = $stmt->error;
				$rows = $stmt->affected_rows;
				$stmt->close();

				if ($medio_envio == 2) {

					$id_estado_servientrega = 1;
                    /*
                    $validate_insert = "INSERT INTO entregas_servientrega (id_solicitud, numero_guia, id_estado_solicitud, sobreflete_servientrega, destino_servientrega, bolsa_servientrega, fecha_registro) VALUES ($id_solicitud, '$guia_servientrega', $id_estado_servientrega, $sobreflete_servientrega, $destino_servientrega, $bolsa_servientrega, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))";
                    echo $validate_insert;
                    die();
                    */

					$stmt = $conn->prepare("INSERT INTO entregas_servientrega (id_solicitud, numero_guia, id_estado_solicitud, sobreflete_servientrega, destino_servientrega, bolsa_servientrega, fecha_registro) VALUES (?, ?, ?, ?, ? ,?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("isidii", $id_solicitud, $guia_servientrega, $id_estado_servientrega, $sobreflete_servientrega, $destino_servientrega, $bolsa_servientrega);
					$stmt->execute();
					$error = $stmt->error;
					$rows = $stmt->affected_rows;
					$stmt->close();

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



				} else if ($medio_envio == 3) {

					$stmt = $conn->prepare("INSERT INTO entregas_tienda (id_solicitud, id_usuario_responsable, fecha_entrega, fecha_registro) VALUES (?, ?, ?,  DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("iis", $id_solicitud, $responsable_tienda, $pickup_date);
					$stmt->execute();
					$error = $stmt->error;
					$rows = $stmt->affected_rows;
					$stmt->close();

				}

				if ($rows == 1) {

					$query1 = "SELECT solicitudes.id AS id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre,prospecto_detalles.prospecto_apellidos, marcas.marca_producto, modelos.nombre_modelo, productos_stock.imei_1, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento, estados_solicitudes.mostrar, solicitudes_domiciliarios.id_domiciliario, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS solicitud_delivery_date, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, solicitudes.id_estado_solicitud, solicitudes.del AS estado_eliminado, prospectos.id_plataforma, prospecto_detalles.id_referencia, capacidades_telefonos.capacidad, prospectos.imei_referencia, prospectos.id_medio_envio, plataformas_credito.nombre_plataforma FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospectos.id = prospecto_detalles.id_prospecto LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN usuarios ON solicitudes_domiciliarios.id_domiciliario = usuarios.id WHERE solicitudes.id = $id_solicitud";
					$result = qry($query1);
					while ($row1 = mysqli_fetch_array($result)) {

						$id_solicitud = $row1['id_solicitud'];
						$prospecto_cedula = $row1['prospecto_cedula'];
						$prospecto_nombre = $row1['prospecto_nombre'];
						$prospecto_apellidos = $row1['prospecto_apellidos'];
						$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
						$prospecto_direccion = $row1['prospecto_direccion'];
						$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
						$prospecto_direccion = $row1['prospecto_direccion'];
						$ciudad = $row1['ciudad'];
						$departamento = $row1['departamento'];
						$mostrar = $row1['mostrar'];
						$marca_producto = $row1['marca_producto'];
						$nombre_modelo = $row1['nombre_modelo'];
						$imei_1 = $row1['imei_1'];
						$id_domiciliario = $row1['id_domiciliario'];
						$id_estado_solicitud = $row1['id_estado_solicitud'];
						$estado_eliminado = $row1['estado_eliminado'];
						$id_plataforma = $row1['id_plataforma'];
						$id_referencia = $row1['id_referencia'];
						$capacidad = $row1['capacidad'];
						$id_medio_envio = $row1['id_medio_envio'];
						$nombre_plataforma = $row1['nombre_plataforma'];

						if ($id_plataforma == 1 || $id_plataforma == 2) {

							$marca_producto2 = execute_scalar("SELECT marcas.marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
							$nombre_modelo2 = execute_scalar("SELECT nombre_modelo FROM modelos WHERE modelos.id = $id_referencia");
							$capacidad2 = execute_scalar("SELECT capacidades_telefonos.capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");
							$imei_1_2 = $row1['imei_referencia'];
							$dispositivo = $marca_producto2 . '-' . $nombre_modelo2 . '-' . $imei_1_2;
						} else {

							$dispositivo = $marca_producto . '-' . $nombre_modelo . '-' . $capacidad . '-' . $imei_1;
						}

						if ($id_medio_envio == 1) {

							$usuario_nombre = $row1['nombre'];
							$usuario_apellidos = $row1['apellidos'];
							$usuario_full_name = $usuario_nombre . ' ' . $usuario_apellidos;
						} else if ($id_medio_envio == 2) {

							$usuario_full_name = "SERVIENTREGA";
						} else if ($id_medio_envio == 3) {


							$usuario_full_name = "RECOGE EN TIENDA";
						} else {
							$usuario_full_name = "N/A";
						}

						if (is_null($id_domiciliario)) {

							$solicitud_fecha_confirmada = 'N/A';
						} else {

							$solicitud_fecha_entrega = $row1['solicitud_delivery_date'];
							$solicitud_inicio_tiempo = $row1['solicitud_inicio_tiempo'];
							$solicitud_final_tiempo = $row1['solicitud_final_tiempo'];
							$solicitud_fecha_confirmada = $solicitud_fecha_entrega . ' DESDE ' . $solicitud_inicio_tiempo . ' HASTA ' . $solicitud_final_tiempo;
						}
					}

					$response = array(
						"response" => "success",
						"id_solicitud" => $id_solicitud,
						"prospecto" => $prospecto_cedula . '-' . $prospecto_nombre . ' ' . $prospecto_apellidos,
						"dispositivo" => $dispositivo,
						"plataforma" => str_replace('_', ' ', $nombre_plataforma),
						"contacto" => $prospecto_numero_contacto,
						"ubicacion" => $prospecto_direccion . '-' . $ciudad . '/' . $departamento,
						"estado" => $mostrar,
						"domiciliario" => $usuario_full_name,
						"fecha_entrega" => $solicitud_fecha_confirmada,
						"clase_color" => $clase_color,
						"id_medio_envio" => $id_medio_envio
					);

				} else {

					$response = array(
						"response" => "error",
						"error2" => $error
					);
				}
			} else {

				$response = array(
					"response" => "error",
					"error1" => $error
				);
			}
		} else {

			$response = array(
				"response" => "pdte_validar",
				"id_solicitud" => $id_solicitud
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "select_entrega") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

		$validate_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

		if ($validate_estado_prospecto != 0) {

			$infoArray = array();

			$query = "SELECT prospectos.prospecto_cedula, solicitudes.id_prospecto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, marcas.marca_producto, modelos.nombre_modelo, capacidades_telefonos.capacidad, productos_stock.imei_1, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS solicitud_delivery_date, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, prospectos.id_medio_envio, prospectos.id_plataforma, prospecto_detalles.id_referencia, solicitudes_domiciliarios.id_domiciliario, prospecto_detalles.ciudad_id AS id_destino FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id WHERE solicitudes.id = $id_solicitud";

			//echo $query;
			//die();

			$result = qry($query);
			while ($row1 = mysqli_fetch_array($result)) {

				$prospecto_cedula = $row1['prospecto_cedula'];
				$prospecto_nombre = $row1['prospecto_nombre'];
				$prospecto_apellidos = $row1['prospecto_apellidos'];

				$id_prospecto = $row1['id_prospecto'];

				$marca_producto = $row1['marca_producto'];
				$nombre_modelo = $row1['nombre_modelo'];
				$capacidad_modelo = $row1['capacidad'];
				$imei_1 = $row1['imei_1'];

				$id_plataforma = $row1['id_plataforma'];

				if ($id_plataforma == 1 || $id_plataforma == 2) {

					$id_referencia = $row1['id_referencia'];
					$marca_producto = execute_scalar("SELECT marcas.marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
					$nombre_modelo = execute_scalar("SELECT nombre_modelo FROM modelos WHERE modelos.id = $id_referencia");
					$capacidad_modelo = execute_scalar("SELECT capacidades_telefonos.capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");
					$imei_1 = execute_scalar("SELECT imei_referencia FROM prospectos WHERE id = $id_prospecto");
				}

				$solicitud_fecha_entrega = $row1['solicitud_delivery_date'];
				$solicitud_inicio_tiempo = $row1['solicitud_inicio_tiempo'];
				$solicitud_final_tiempo = $row1['solicitud_final_tiempo'];

				$id_destino = $row1['id_destino'];

				$id_medio_envio = $row1['id_medio_envio'];

				$id_domiciliario = 0;

				$guia_servientrega = 0;

				$id_responsable_entrega = 0;

				if ($id_medio_envio == 1) {

					$id_domiciliario = $row1['id_domiciliario'];
				} else if ($id_medio_envio == 2) {

					$guia_servientrega = execute_scalar("SELECT numero_guia FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");
				} else if ($id_medio_envio == 3) {

					$id_responsable_entrega = execute_scalar("SELECT id_usuario_responsable FROM entregas_tienda WHERE id_solicitud = $id_solicitud AND del = 0");
				}

				$response = array(
					"response" => "success",
					"id_solicitud" => $id_solicitud,
					"prospecto" => $prospecto_nombre . ' ' . $prospecto_apellidos,
					"dispositivo" => $marca_producto . ' ' . $nombre_modelo . '-' . $imei_1,
					"pick_up_date" => $solicitud_fecha_entrega,
					"pick_up_start" => $solicitud_inicio_tiempo,
					"pick_up_end" => $solicitud_final_tiempo,
					"id_domiciliario" => $id_domiciliario,
					"id_medio_envio" => $id_medio_envio,
					"guia_servientrega" => $guia_servientrega,
					"id_plataforma" => $id_plataforma,
					"imei_1" => $imei_1,
					"id_responsable_entrega" => $id_responsable_entrega,
					"id_destino" => $id_destino
				);
			}
		} else {

			$response = array(
				"response" => "pdte_validar",
				"id_solicitud" => $id_solicitud
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "editar_domi") {
	try {

		$pickup_date = $_POST['pickup_date'];
		$pickup_date = substr($pickup_date, 6, 4) . '-' . substr($pickup_date, 0, 2) . '-' . substr($pickup_date, 3, 2);
		$pickup_start = $_POST['pickup_start'];
		$pickup_end = $_POST['pickup_end'];

		$id_domiciliario = 0;
		$guia_servientrega = 0;
		$responsable_tienda = 0;
		$imei_referencia = 0;

		$id_solicitud = $_POST['id_solicitud'];
		$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");
		$id_plataforma = execute_scalar("SELECT id_plataforma FROM prospectos WHERE id = $id_prospecto");

		$medio_envio = $_POST['medio_envio'];

		$entrega_confirmada = 0;
		$fecha_entrega_confirmada = null;

		$clase_color = '';

		if ($medio_envio == 1) {

			$id_domiciliario = $_POST['domiciliario_solicitud'];
			$clase_color = "solicitud-asignada";
		} else if ($medio_envio == 2) {

			$guia_servientrega = $_POST['guia_servientrega'];
			$clase_color = "solicitud-asignada-servientrega";
		} else if ($medio_envio == 3) {

			$responsable_tienda = $_POST['responsable_entrega'];
			$clase_color = "solicitud-asignada-tienda";
		}

		$validate_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

		if ($validate_estado_prospecto != 0) {

			$id_solicitud_domiciliario = execute_scalar("SELECT id FROM solicitudes_domiciliarios WHERE id_solicitud = $id_solicitud AND del = 0");

			qry("DELETE FROM solicitudes_domiciliarios WHERE id = $id_solicitud_domiciliario");

			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("INSERT INTO solicitudes_domiciliarios (id, id_solicitud, id_domiciliario, solicitud_fecha_entrega, solicitud_inicio_tiempo, solicitud_final_tiempo, entrega_confirmada, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
			$stmt->bind_param("iiisssi", $id_solicitud_domiciliario, $id_solicitud, $id_domiciliario, $pickup_date, $pickup_start, $pickup_end, $entrega_confirmada);
			$stmt->execute();
			$error = $stmt->error;
			$rows = $stmt->affected_rows;
			$stmt->close();

			if ($rows == 1) {

				$id_estado_solicitud = 6;

				$validate_medio_envio = execute_scalar("SELECT id_medio_envio FROM prospectos WHERE id = $id_prospecto");

				if ($id_plataforma == 1 || $id_plataforma == 2) {

					$imei_referencia = $_POST['imei_referencia'];

					$validate_imei = execute_scalar("SELECT imei_referencia FROM prospectos WHERE id = $id_prospecto");

					if ($validate_imei != $imei_referencia || $validate_medio_envio != $medio_envio) {

						$stmt = $conn->prepare("UPDATE prospectos SET imei_referencia = ?, id_medio_envio = ? WHERE id = ?");
						$stmt->bind_param("iii", $imei_referencia, $medio_envio, $id_prospecto);
						$stmt->execute();
						$error = $stmt->error;
						$rows = $stmt->affected_rows;
						$stmt->close();
					}
				} else {

					if ($validate_medio_envio != $medio_envio) {
						$stmt = $conn->prepare("UPDATE prospectos SET id_medio_envio = ? WHERE id = ?");
						$stmt->bind_param("ii", $medio_envio, $id_prospecto);
						$stmt->execute();
						$error = $stmt->error;
						$rows = $stmt->affected_rows;
						$stmt->close();
					}
				}


				if ($medio_envio == 2) {

					$id_estado_servientrega = 1;

					$id_entregas_servientrega = execute_scalar("SELECT id FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");

					qry("DELETE FROM entregas_servientrega WHERE id = $id_entregas_servientrega");

					$stmt = $conn->prepare("INSERT INTO entregas_servientrega (id, id_solicitud, numero_guia, id_estado_solicitud, fecha_registro) VALUES (?, ?, ?, ?,  DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("iisi", $id_entregas_servientrega, $id_solicitud, $guia_servientrega, $id_estado_servientrega);
					$stmt->execute();
					$error = $stmt->error;
					$rows = $stmt->affected_rows;
					$stmt->close();
				} else if ($medio_envio == 3) {

					$id_entregas_tienda = execute_scalar("SELECT id FROM entregas_tienda WHERE id_solicitud = $id_solicitud AND del = 0");

					qry("DELETE FROM entregas_tienda WHERE id = $id_entregas_tienda");

					$stmt = $conn->prepare("INSERT INTO entregas_tienda (id, id_solicitud, id_usuario_responsable, fecha_entrega, fecha_registro) VALUES (?, ?, ?, ?,  DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
					$stmt->bind_param("iiis", $id_entregas_tienda, $id_solicitud, $responsable_tienda, $pickup_date);
					$stmt->execute();
					$error = $stmt->error;
					$rows = $stmt->affected_rows;
					$stmt->close();
				}

				if ($rows == 1) {

					$query1 = "SELECT solicitudes.id AS id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre,prospecto_detalles.prospecto_apellidos, marcas.marca_producto, modelos.nombre_modelo, productos_stock.imei_1, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento, estados_solicitudes.mostrar, solicitudes_domiciliarios.id_domiciliario, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS solicitud_delivery_date, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, solicitudes.id_estado_solicitud, solicitudes.del AS estado_eliminado, prospectos.id_plataforma, prospecto_detalles.id_referencia, capacidades_telefonos.capacidad, prospectos.imei_referencia, prospectos.id_medio_envio, plataformas_credito.nombre_plataforma FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospectos.id = prospecto_detalles.id_prospecto LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN usuarios ON solicitudes_domiciliarios.id_domiciliario = usuarios.id WHERE solicitudes.id = $id_solicitud";
					$result = qry($query1);
					while ($row1 = mysqli_fetch_array($result)) {

						$id_solicitud = $row1['id_solicitud'];
						$prospecto_cedula = $row1['prospecto_cedula'];
						$prospecto_nombre = $row1['prospecto_nombre'];
						$prospecto_apellidos = $row1['prospecto_apellidos'];
						$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
						$prospecto_direccion = $row1['prospecto_direccion'];
						$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
						$prospecto_direccion = $row1['prospecto_direccion'];
						$ciudad = $row1['ciudad'];
						$departamento = $row1['departamento'];
						$mostrar = $row1['mostrar'];
						$marca_producto = $row1['marca_producto'];
						$nombre_modelo = $row1['nombre_modelo'];
						$imei_1 = $row1['imei_1'];
						$id_domiciliario = $row1['id_domiciliario'];
						$id_estado_solicitud = $row1['id_estado_solicitud'];
						$estado_eliminado = $row1['estado_eliminado'];
						$id_plataforma = $row1['id_plataforma'];
						$id_referencia = $row1['id_referencia'];
						$capacidad = $row1['capacidad'];
						$id_medio_envio = $row1['id_medio_envio'];
						$nombre_plataforma = $row1['nombre_plataforma'];

						if ($id_plataforma == 1 || $id_plataforma == 2) {

							$marca_producto2 = execute_scalar("SELECT marcas.marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
							$nombre_modelo2 = execute_scalar("SELECT nombre_modelo FROM modelos WHERE modelos.id = $id_referencia");
							$capacidad2 = execute_scalar("SELECT capacidades_telefonos.capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");
							$imei_1_2 = $row1['imei_referencia'];
							$dispositivo = $marca_producto2 . '-' . $nombre_modelo2 . '-' . $imei_1_2;
						} else {

							$dispositivo = $marca_producto . '-' . $nombre_modelo . '-' . $capacidad . '-' . $imei_1;
						}

						if ($id_medio_envio == 1) {

							$usuario_nombre = $row1['nombre'];
							$usuario_apellidos = $row1['apellidos'];
							$usuario_full_name = $usuario_nombre . ' ' . $usuario_apellidos;
						} else if ($id_medio_envio == 2) {

							$usuario_full_name = "SERVIENTREGA";
							$validate_guia = execute_scalar("SELECT COUNT(id) AS total FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");
							$id_estado_servientrega = '';
							if ($validate_guia != 0) {
								$id_estado_servientrega = execute_scalar("SELECT id_estado_solicitud FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");
							}
						} else if ($id_medio_envio == 3) {


							$usuario_full_name = "RECOGE EN TIENDA";
						} else {
							$usuario_full_name = "N/A";
						}

						if (is_null($id_domiciliario)) {

							$solicitud_fecha_confirmada = 'N/A';
						} else {

							$solicitud_fecha_entrega = $row1['solicitud_delivery_date'];
							$solicitud_inicio_tiempo = $row1['solicitud_inicio_tiempo'];
							$solicitud_final_tiempo = $row1['solicitud_final_tiempo'];
							$solicitud_fecha_confirmada = $solicitud_fecha_entrega . ' DESDE ' . $solicitud_inicio_tiempo . ' HASTA ' . $solicitud_final_tiempo;
						}
					}

					$response = array(
						"response" => "success",
						"id_solicitud" => $id_solicitud,
						"prospecto" => $prospecto_cedula . '-' . $prospecto_nombre . ' ' . $prospecto_apellidos,
						"dispositivo" => $dispositivo,
						"plataforma" => str_replace('_', ' ', $nombre_plataforma),
						"contacto" => $prospecto_numero_contacto,
						"ubicacion" => $prospecto_direccion . '-' . $ciudad . '/' . $departamento,
						"estado" => $mostrar,
						"domiciliario" => $usuario_full_name,
						"fecha_entrega" => $solicitud_fecha_confirmada,
						"clase_color" => $clase_color,
						"id_medio_envio" => $id_medio_envio,
						"id_estado_servientrega" => $id_estado_servientrega
					);
				} else {

					$response = array(
						"response" => "error",
						"error2" => $error
					);
				}
			} else {

				$response = array(
					"response" => "error",
					"error1" => $error
				);
			}
		} else {

			$response = array(
				"response" => "pdte_validar",
				"id_solicitud" => $id_solicitud
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "select_digitadores") {
	try {

		$id_punto_gane = $_POST['id_punto'];

		$response = array();

		$query = "SELECT usuarios_puntos_gane.id_usuario, usuarios.cedula, usuarios.nombre, usuarios.apellidos FROM usuarios_puntos_gane LEFT JOIN usuarios ON usuarios_puntos_gane.id_usuario = usuarios.id WHERE usuarios_puntos_gane.id_punto_gane = $id_punto_gane AND usuarios_puntos_gane.del = 0 AND usuarios.del = 0";

		if ($id_punto_gane == 0) {

			$query = "SELECT usuarios_puntos_gane.id_usuario, usuarios.cedula, usuarios.nombre, usuarios.apellidos FROM usuarios_puntos_gane LEFT JOIN usuarios ON usuarios_puntos_gane.id_usuario = usuarios.id WHERE usuarios_puntos_gane.del = 0 AND usuarios.del = 0";
		}

		$result = qry($query);
		while ($row = mysqli_fetch_array($result)) {

			$id_usuario = $row['id_usuario'];
			$cedula = $row['cedula'];
			$nombre = $row['nombre'];
			$apellidos = $row['apellidos'];

			$new_array = array("id_usuario" => $id_usuario, "cedula" => $cedula, "nombre" => $nombre, "apellidos" => $apellidos);
			array_push($response, $new_array);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "reporte_ventas_dia") {

	try {

		$condition_fecha = 0;

		$id_usuario = $_POST['id_usuario'];

		$fecha_inicio = $_POST['fecha_inicio'];
		if ($fecha_inicio !== "") {
			$fecha_inicio = substr($fecha_inicio, 6, 4) . '-' . substr($fecha_inicio, 0, 2) . '-' . substr($fecha_inicio, 3, 2) . ' ' . '00:00:00';
			$condition_fecha = 1;
		}
		$fecha_final = $_POST['fecha_final'];
		if ($fecha_final !== '') {
			$fecha_final = substr($fecha_final, 6, 4) . '-' . substr($fecha_final, 0, 2) . '-' . substr($fecha_final, 3, 2) . ' ' . '23:59:59';
			$condition_fecha = 1;
		}


		$condition_responsable = 0;

		if (isset($_POST['id_responsable_directo']) && $_POST['id_responsable_directo'] != 0) {

			$id_responsable = $_POST['id_responsable_directo'];
			$condition_responsable = 1;
		}

		$creditosArray = array();

		$query_fecha = '';
		$query_responsable = '';

		if ($condition_fecha == 1) {

			if ($fecha_inicio != '' && $fecha_final != '') {

				$query_fecha = " AND solicitudes_domiciliarios.fecha_entrega_confirmada BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "' ";
			} else {

				$response = array(
					"response" => "falta_fecha"
				);

				echo json_encode($response);
				die();
			}
		}

		if ($condition_responsable == 1) {

			$segunda_condicion = '';

			if ($condition_fecha == 1) {

				$segunda_condicion = ' AND ';
			}

			$query_responsable = " AND prospectos.id_responsable_interno = " . $id_responsable . " ";
		}

		$query = "SELECT DISTINCT solicitudes.id AS id_solicitud, prospectos.id AS id_prospecto, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, productos_stock.imei_1, prospectos.imei_referencia, usuarios.nombre, usuarios.apellidos, estados_solicitudes.mostrar, plataformas_credito.nombre_plataforma, prospecto_detalles.id_referencia, prospectos.id_plataforma, prospectos.id_medio_envio,DATE_FORMAT(solicitudes_domiciliarios.fecha_entrega_confirmada, '%m-%d-%Y') AS fecha_entrega FROM `solicitudes` LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id WHERE solicitudes.id_estado_solicitud = 7 AND prospectos.id_estado_prospecto = 5 " . $query_responsable . $query_fecha . "AND prospectos.del = 0 AND solicitudes.del = 0";

		//echo $query;
		//die();

		$result = qry($query);

		while ($row = mysqli_fetch_array($result)) {

			$id_solicitud = $row['id_solicitud'];
			$id_prospecto = $row['id_prospecto'];
			$prospecto_cedula = $row['prospecto_cedula'];
			$prospecto_nombre = $row['prospecto_nombre'];
			$prospecto_apellidos = $row['prospecto_apellidos'];
			$nombre_modelo = $row['nombre_modelo'];
			$marca_producto = $row['marca_producto'];
			$capacidad = $row['capacidad'];
			$imei_1 = $row['imei_1'];
			$imei_referencia = $row['imei_referencia'];
			$responsable_nombre = $row['nombre'];
			$responsable_apellidos = $row['apellidos'];
			$mostrar = $row['mostrar'];
			$nombre_plataforma = $row['nombre_plataforma'];
			$id_referencia = $row['id_referencia'];
			$id_plataforma = $row['id_plataforma'];
			$fecha_entrega = $row['fecha_entrega'];
			$id_medio_envio = $row['id_medio_envio'];
			if ($id_medio_envio == 1) {
				$medio_envio = "DOMICILIO";
			} else if ($id_medio_envio == 2) {
				$medio_envio = "SERVIENTREGA";
			} else if ($id_medio_envio == 3) {
				$medio_envio = "EN TIENDA";
			}

			if ($id_plataforma == 1 || $id_plataforma == 2) {
				$nombre_modelo = execute_scalar("SELECT nombre_modelo FROM modelos WHERE id = $id_referencia");
				$marca_producto = execute_scalar("SELECT marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
				$capacidad = execute_scalar("SELECT capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");
				$imei_1 = $imei_referencia;
			}

			$new_array = array("id_solicitud" => $id_solicitud, "id_prospecto" => $id_prospecto, "prospecto_cedula" => $prospecto_cedula, "prospecto_nombre" => $prospecto_nombre, "prospecto_apellidos" => $prospecto_apellidos, "nombre_modelo" => $nombre_modelo, "marca_producto" => $marca_producto, "capacidad" => $capacidad, "imei_1" => $imei_1, "responsable_nombre" => $responsable_nombre, "responsable_apellidos" => $responsable_apellidos, "mostrar" => $mostrar, "nombre_plataforma" => $nombre_plataforma, "id_referencia" => $id_referencia, "id_plataforma" => $id_plataforma, "fecha_entrega" => $fecha_entrega, "medio_envio" => $medio_envio, "id_medio_envio" => $id_medio_envio);

			array_push($creditosArray, $new_array);
		}

		$response = array(
			"response" => "success"
		);

		array_push($response, $creditosArray);
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "select_comprobantes_entrega") {

	try {

		$id_solicitud = $_POST['id_solicitud'];
		$filepath = "../documents/solicitudes/" . $id_solicitud . "/confirmaciones/";
		$filepath2 = "./documents/solicitudes/" . $id_solicitud . "/confirmaciones/";

		//escaneamos el directorio y se dejan solo las diferencias
		$files = array_diff(scandir($filepath), array('.', '..'));

		if (count($files) != 0) {

			$img_array = array();

			foreach ($files as $key => $value) {
				$new_array = array('img' => $value);
				array_push($img_array, $new_array);
			}

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud,
				"filepath" => $filepath2
			);

			array_push($response, $img_array);

		} else {

			$response = array(
				"response" => "vacio"
			);
		}

	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "reporte_prospectos") {

	var_dump($_POST);
	die();

	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_final = $_POST['fecha_final'];
	$id_asesor = $_POST['id_asesor'];
	$id_estado_prospecto = $_POST['id_estado_prospecto'];
	$id_usuario = $_POST['id_usuario'];


}else if($_POST['action'] == "reporte_prospectos_resumen"){

	try {

		//var_dump($_POST);
		//die();
		$condition_asesor = 0;
		$condition_fecha = 0;

		$id_usuario = $_POST['id_usuario'];

		if (profile(14, $id_usuario)) {

			if (isset($_POST['id_asesor']) && $_POST['id_asesor'] != 0) {

				$id_asesor = $_POST['id_asesor'];
				$condition_asesor = 1;
			}

		} else {

			$id_asesor = $id_usuario;
			$condition_asesor = 1;
		}

		$fecha_inicio = $_POST['fecha_inicio'];
		if ($fecha_inicio !== "") {
			$fecha_inicio = substr($fecha_inicio, 6, 4) . '-' . substr($fecha_inicio, 0, 2) . '-' . substr($fecha_inicio, 3, 2) . ' ' . '00:00:00';
			$condition_fecha = 1;
		}

		$fecha_final = $_POST['fecha_final'];
		if ($fecha_final !== '') {
			$fecha_final = substr($fecha_final, 6, 4) . '-' . substr($fecha_final, 0, 2) . '-' . substr($fecha_final, 3, 2) . ' ' . '23:59:59';
			$condition_fecha = 1;
		}

		$query_fecha = '';
		$query_asesor = '';

		if ($condition_fecha == 1) {

			if ($fecha_inicio != '' && $fecha_final != '') {

				$query_fecha = "prospectos.fecha_registro BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "' AND ";

			} else {

				$response = array(
					"response" => "falta_fecha"
				);

				echo json_encode($response);
				die();
			}
		}

		if ($condition_asesor == 1) {

			$query_asesor = "prospectos.id_responsable_interno = " . $id_asesor . " AND ";
		}

		$pdte_validar = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE ".$query_fecha.$query_asesor."prospectos.id_responsable_interno = 33 AND prospectos.id_estado_prospecto = 0 AND prospectos.del = 0");
		$pdte_entregar = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE ".$query_fecha.$query_asesor."prospectos.id_estado_prospecto = 3 AND prospectos.del = 0");
		$pdte_comprobante = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE ".$query_fecha.$query_asesor."prospectos.id_estado_prospecto = 4 AND prospectos.del = 0");
		$entregados = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE ".$query_fecha.$query_asesor."prospectos.id_estado_prospecto = 5 AND prospectos.del = 0");
		$ventas_no_realizadas = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE ".$query_fecha.$query_asesor."prospectos.id_estado_prospecto = 6 AND prospectos.del = 0");
		$rechazados = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE ".$query_fecha.$query_asesor."prospectos.id_estado_prospecto = 2 AND prospectos.del = 0");

		$response = array(
			"response" => "success",
			"pdte_validar" => $pdte_validar,
			"pdte_entregar" => $pdte_entregar,
			"pdte_comprobante" => $pdte_comprobante,
			"entregados" => $entregados,
			"ventas_no_realizadas" => $ventas_no_realizadas,
			"rechazados" => $rechazados
		);

	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "reporte_servientrega_detallado") {

	try {

		$id_usuario = $_POST['id_usuario'];

		$condition_estado = 0;

		$condition_fecha = 0;

		$fecha_inicio = $_POST['fecha_inicio'];
		if ($fecha_inicio !== "") {
			$fecha_inicio = substr($fecha_inicio, 6, 4) . '-' . substr($fecha_inicio, 0, 2) . '-' . substr($fecha_inicio, 3, 2) . ' ' . '00:00:00';
			$condition_fecha = 1;
		}
		$fecha_final = $_POST['fecha_final'];
		if ($fecha_final !== '') {
			$fecha_final = substr($fecha_final, 6, 4) . '-' . substr($fecha_final, 0, 2) . '-' . substr($fecha_final, 3, 2) . ' ' . '23:59:59';
			$condition_fecha = 1;
		}

		if (isset($_POST['id_estado_servientrega']) && $_POST['id_estado_servientrega'] != "all") {

			$id_estado_servientrega = $_POST['id_estado_servientrega'];
			$condition_estado = 1;
			if ($id_estado_servientrega == "all") {
				$condition_estado = 0;
			}
		}

		$servientregaArray = array();

		$query_fecha = '';
		$query_estado = '';

		if ($condition_fecha == 1) {

			if ($fecha_inicio != '' && $fecha_final != '') {

				$query_fecha = " AND entregas_servientrega.fecha_registro BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_final . "' ";
			} else {

				$response = array(
					"response" => "falta_fecha"
				);

				echo json_encode($response);
				die();
			}
		}

		if ($condition_estado == 1) {

			$segunda_condicion = '';

			if ($condition_fecha == 1) {

				$segunda_condicion = ' AND ';
			}

			$query_estado = " AND entregas_servientrega.id_estado_solicitud = " . $id_estado_servientrega . " ";
		}

		$query = "SELECT DISTINCT entregas_servientrega.id AS id_servientrega, entregas_servientrega.id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.id_referencia, modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, productos_stock.imei_1, prospectos.imei_referencia, entregas_servientrega.numero_guia, entregas_servientrega.id_estado_solicitud, DATE_FORMAT(entregas_servientrega.fecha_registro, '%m-%d-%Y') AS fecha_envio, prospectos.id_plataforma, entregas_servientrega.del FROM entregas_servientrega LEFT JOIN solicitudes ON entregas_servientrega.id_solicitud = solicitudes.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE entregas_servientrega.del = 0".$query_fecha.$query_estado;

		//echo $query;

		//die();

		$result = qry($query);

		$servientrega_array = array();

		while ($row = mysqli_fetch_array($result)) {

			$id_servientrega = $row['id_servientrega'];
			$id_solicitud = $row['id_solicitud'];
			$prospecto_cedula = $row['prospecto_cedula'];
			$prospecto_nombre = $row['prospecto_nombre'];
			$prospecto_apellidos = $row['prospecto_apellidos'];
			$nombre_modelo = $row['nombre_modelo'];
			$marca_producto = $row['marca_producto'];
			$capacidad = $row['capacidad'];
			$imei_1 = $row['imei_1'];

			$id_plataforma = $row['id_plataforma'];

			$del = $row['del'];

			if ($id_plataforma == 1 || $id_plataforma == 2) {

				$id_referencia = $row['id_referencia'];
				$nombre_modelo = execute_scalar("SELECT nombre_modelo FROM modelos WHERE id = $id_referencia");
				$marca_producto = execute_scalar("SELECT marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
				$capacidad = execute_scalar("SELECT capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");

			}

			$dispositivo = $marca_producto . ' ' . $nombre_modelo . '-' . $capacidad;

			$numero_guia = $row['numero_guia'];

			$id_estado_solicitud = $row['id_estado_solicitud'];

			$estado_servientrega = "N/A";

			if($id_estado_solicitud == 1){

				$estado_servientrega = "EN RUTA";

			}else if($id_estado_solicitud == 2){

				$estado_servientrega = "ENTREGADO - PDTE POR PAGAR";

			}else if($id_estado_solicitud == 3){

				$estado_servientrega = "DEVOLUCIÓN";

			}else if($id_estado_solicitud == 4){

				$estado_servientrega = "PAGADO";

			}


			$fecha_envio = $row['fecha_envio'];

			$new_array = array("id_servientrega" => $id_servientrega, "id_solicitud" => $id_solicitud, "prospecto_cedula" => $prospecto_cedula, "prospecto_nombre" => $prospecto_nombre, "prospecto_apellidos" => $prospecto_apellidos, "dispositivo" => $dispositivo, "numero_guia" => $numero_guia, "id_estado_solicitud" => $id_estado_solicitud, "estado_servientrega" => $estado_servientrega, "fecha_envio" => $fecha_envio, "del" => $del);

			array_push($servientrega_array, $new_array);

		}

		$response = array(
			"response" => "success"
		);

		array_push($response, $servientrega_array);

	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "select_observacion") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$observacion_prospecto = execute_scalar("SELECT observacion_prospecto FROM prospecto_detalles WHERE id_prospecto = $id_prospecto AND prospecto_detalles.del = 0");

		$response = array(
			"response" => "success",
			"observacion" => $observacion_prospecto,
			"id_prospecto" => $id_prospecto
		);
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
	
} else if ($_POST['action'] == "confirmar_recogida_tienda") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE solicitudes_domiciliarios SET entrega_confirmada = b'1', fecha_entrega_confirmada = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ?");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = 7 WHERE id = ?");
			$stmt->bind_param("i", $id_solicitud);
			$stmt->execute();
			$rows2 = $stmt->affected_rows;
			$error2 = $stmt->error;
			$stmt->close();

			if ($rows2 == 1) {

				$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

				$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 5 WHERE id = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();
				$rows3 = $stmt->affected_rows;
				$error3 = $stmt->error;
				$stmt->close();

				if ($rows3 == 1) {

					$response = array(
						'response' => "success",
						"id_solicitud" => $id_solicitud
					);
				} else {
					$response = array(
						"response" => "error",
						"error3" => $error3
					);
				}
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


		// Remover la parte de la cadena de texto que no necesitamos (data:image/png;base64,)
		// y usar base64_decode para obtener la información binaria de la imagen


	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);
} else if ($_POST['action'] == "confirmar_entregado_servientrega") {

	try {
		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE solicitudes_domiciliarios SET entrega_confirmada = b'1', fecha_entrega_confirmada = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = 7, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
			$stmt->bind_param("i", $id_solicitud);
			$stmt->execute();
			$rows2 = $stmt->affected_rows;
			$error2 = $stmt->error;
			$stmt->close();

			if ($rows2 == 1) {

				$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

				$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 5, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();
				$rows3 = $stmt->affected_rows;
				$error3 = $stmt->error;
				$stmt->close();

				if ($rows3 == 1) {

					$stmt = $conn->prepare("UPDATE entregas_servientrega SET id_estado_solicitud = 2, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
					$stmt->bind_param("i", $id_solicitud);
					$stmt->execute();
					$rows4 = $stmt->affected_rows;
					$error4 = $stmt->error;
					$stmt->close();

					if ($rows4 == 1) {

						$query1 = "SELECT solicitudes.id AS id_solicitud, prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre,prospecto_detalles.prospecto_apellidos, marcas.marca_producto, modelos.nombre_modelo, productos_stock.imei_1, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_direccion, ciudades.ciudad, departamentos.departamento, estados_solicitudes.mostrar, solicitudes_domiciliarios.id_domiciliario, usuarios.nombre, usuarios.apellidos, DATE_FORMAT(solicitudes_domiciliarios.solicitud_fecha_entrega, '%m-%d-%Y') AS solicitud_delivery_date, solicitudes_domiciliarios.solicitud_inicio_tiempo, solicitudes_domiciliarios.solicitud_final_tiempo, solicitudes.id_estado_solicitud, solicitudes.del AS estado_eliminado, prospectos.id_plataforma, prospecto_detalles.id_referencia, capacidades_telefonos.capacidad, prospectos.imei_referencia, prospectos.id_medio_envio, plataformas_credito.nombre_plataforma FROM solicitudes LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospectos.id = prospecto_detalles.id_prospecto LEFT JOIN plataformas_credito ON prospectos.id_plataforma = plataformas_credito.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_solicitudes ON solicitudes.id_estado_solicitud = estados_solicitudes.id LEFT JOIN solicitudes_domiciliarios ON solicitudes_domiciliarios.id_solicitud = solicitudes.id LEFT JOIN usuarios ON solicitudes_domiciliarios.id_domiciliario = usuarios.id WHERE solicitudes.id = $id_solicitud";
						$result = qry($query1);
						while ($row1 = mysqli_fetch_array($result)) {

							$id_solicitud = $row1['id_solicitud'];
							$prospecto_cedula = $row1['prospecto_cedula'];
							$prospecto_nombre = $row1['prospecto_nombre'];
							$prospecto_apellidos = $row1['prospecto_apellidos'];
							$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
							$prospecto_direccion = $row1['prospecto_direccion'];
							$prospecto_numero_contacto = $row1['prospecto_numero_contacto'];
							$prospecto_direccion = $row1['prospecto_direccion'];
							$ciudad = $row1['ciudad'];
							$departamento = $row1['departamento'];
							$mostrar = $row1['mostrar'];
							$marca_producto = $row1['marca_producto'];
							$nombre_modelo = $row1['nombre_modelo'];
							$imei_1 = $row1['imei_1'];
							$id_domiciliario = $row1['id_domiciliario'];
							$id_estado_solicitud = $row1['id_estado_solicitud'];
							$estado_eliminado = $row1['estado_eliminado'];
							$id_plataforma = $row1['id_plataforma'];
							$id_referencia = $row1['id_referencia'];
							$capacidad = $row1['capacidad'];
							$id_medio_envio = $row1['id_medio_envio'];
							$nombre_plataforma = $row1['nombre_plataforma'];

							if ($id_plataforma == 1 || $id_plataforma == 2) {

								$marca_producto2 = execute_scalar("SELECT marcas.marca_producto FROM modelos LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE modelos.id = $id_referencia");
								$nombre_modelo2 = execute_scalar("SELECT nombre_modelo FROM modelos WHERE modelos.id = $id_referencia");
								$capacidad2 = execute_scalar("SELECT capacidades_telefonos.capacidad FROM modelos LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id WHERE modelos.id = $id_referencia");
								$imei_1_2 = $row1['imei_referencia'];
								$dispositivo = $marca_producto2 . '-' . $nombre_modelo2 . '-' . $imei_1_2;
							} else {

								$dispositivo = $marca_producto . '-' . $nombre_modelo . '-' . $capacidad . '-' . $imei_1;
							}

							$clase_color = "";

							if ($id_medio_envio == 1) {

								$usuario_nombre = $row1['nombre'];
								$usuario_apellidos = $row1['apellidos'];
								$usuario_full_name = $usuario_nombre . ' ' . $usuario_apellidos;

								$clase_color = "solicitud-asignada";
							} else if ($id_medio_envio == 2) {

								$usuario_full_name = "SERVIENTREGA";
								$validate_guia = execute_scalar("SELECT COUNT(id) AS total FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");
								$id_estado_servientrega = '';
								if ($validate_guia != 0) {
									$id_estado_servientrega = execute_scalar("SELECT id_estado_solicitud FROM entregas_servientrega WHERE id_solicitud = $id_solicitud AND del = 0");
								}

								$clase_color = "solicitud-asignada-servientrega";
							} else if ($id_medio_envio == 3) {


								$usuario_full_name = "RECOGE EN TIENDA";
								$clase_color = "solicitud-asignada-tienda";
							} else {
								$usuario_full_name = "N/A";
							}

							if (is_null($id_domiciliario)) {

								$solicitud_fecha_confirmada = 'N/A';
							} else {

								$solicitud_fecha_entrega = $row1['solicitud_delivery_date'];
								$solicitud_inicio_tiempo = $row1['solicitud_inicio_tiempo'];
								$solicitud_final_tiempo = $row1['solicitud_final_tiempo'];
								$solicitud_fecha_confirmada = $solicitud_fecha_entrega . ' DESDE ' . $solicitud_inicio_tiempo . ' HASTA ' . $solicitud_final_tiempo;
							}
						}

						$response = array(
							"response" => "success",
							"id_solicitud" => $id_solicitud,
							"prospecto" => $prospecto_cedula . '-' . $prospecto_nombre . ' ' . $prospecto_apellidos,
							"dispositivo" => $dispositivo,
							"plataforma" => str_replace('_', ' ', $nombre_plataforma),
							"contacto" => $prospecto_numero_contacto,
							"ubicacion" => $prospecto_direccion . '-' . $ciudad . '/' . $departamento,
							"estado" => $mostrar,
							"domiciliario" => $usuario_full_name,
							"fecha_entrega" => $solicitud_fecha_confirmada,
							"clase_color" => $clase_color,
							"id_medio_envio" => $id_medio_envio,
							"id_estado_servientrega" => $id_estado_servientrega
						);
					} else {

						$response = array(
							"response" => "error",
							"error4" => $error4
						);
					}
				} else {
					$response = array(
						"response" => "error",
						"error3" => $error3
					);
				}
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
	} catch (Exception $e) {
		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "confirmar_devolucion_servientrega") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		//$id_solicitud = $_POST['id_solicitud'];

		//$validate_query = "UPDATE entregas_servientrega SET id_estado_solicitud = 4 WHERE id_solicitud = $id_solicitud";
		//echo $validate_query;
		//die();

		$conn = new mysqli($host, $user, $pass, $db);
		// id_estado_servientrega 4 = pagado
		$stmt = $conn->prepare("UPDATE entregas_servientrega SET id_estado_solicitud = 3, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
			);
		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "volver_pdte_por_pagar"){

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		// id_estado_servientrega 4 = pagado
		$stmt = $conn->prepare("UPDATE entregas_servientrega SET id_estado_solicitud = 2 WHERE id_solicitud = ? AND del = 0");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
			);

		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);

		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "retornar_en_ruta"){

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		// id_estado_servientrega 1 = EN RUTA
		$stmt = $conn->prepare("UPDATE entregas_servientrega SET id_estado_solicitud = 1, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
			);

		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);

		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "volver_nuevamente_entrega"){

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		// id_estado_servientrega 4 = pagado
		$stmt = $conn->prepare("UPDATE entregas_servientrega SET del = 1, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del = 0");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$stmt = $conn->prepare("UPDATE solicitudes_domiciliarios SET del = 1, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_solicitud = ? AND del =0");
			$stmt->bind_param("i", $id_solicitud);
			$stmt->execute();
			$rows2 = $stmt->affected_rows;
			$error2 = $stmt->error;
			$stmt->close();

			if($rows2 == 1){

				$stmt = $conn->prepare("UPDATE solicitudes SET id_estado_solicitud = 5, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
				$stmt->bind_param("i", $id_solicitud);
				$stmt->execute();
				$rows3 = $stmt->affected_rows;
				$error3 = $stmt->error;
				$stmt->close();

				if($rows3 == 1){

					$id_prospecto = execute_scalar("SELECT id_prospecto FROM solicitudes WHERE id = $id_solicitud");

					$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 3, id_medio_envio = 0, imei_referencia = 0, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
					$stmt->bind_param("i", $id_prospecto);
					$stmt->execute();
					$rows4 = $stmt->affected_rows;
					$error4 = $stmt->error;
					$stmt->close();

					if($rows4 == 1){

						$response = array(
							"response" => "success",
							"id_solicitud" => $id_solicitud
						);

					}else{

						$response = array(
							"response" => "error4",
							"error" => $error4
						);

					}

				}else{

					$response = array(
						"response" => "error3",
						"error" => $error3
					);

				}

			}else{

				$response = array(
					"response" => "error2",
					"error" => $error2
				);

			}


		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);

		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "restaurar_prospecto") {

	try {

		$id_prospecto = $_POST['id_prospecto'];
		//$validate_query
		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = 0, id_plataforma = 0, imei_referencia = 0, id_medio_envio = 0, confirmar_rechazado = 0, confirmar_aprobado = 0, resultado_dc_prospecto = 0, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
		$stmt->bind_param("i", $id_prospecto);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$solicitudes_activas = execute_scalar("SELECT COUNT(id) AS total FROM solicitudes WHERE id_prospecto = $id_prospecto AND id_estado_solicitud <> 7 AND id_estado_solicitud <> 8 AND del = 0");

			if($solicitudes_activas != 0){

				$conn = new mysqli($host, $user, $pass, $db);
				$stmt = $conn->prepare("UPDATE solicitudes SET del = 1, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_prospecto = ?");
				$stmt->bind_param("i", $id_prospecto);
				$stmt->execute();
				$rows2 = $stmt->affected_rows;
				$error2 = $stmt->error;
				$stmt->close();

			}

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto
			);


			
		}else{

			$response = array(
				"response" => "error",
				"error" => $error
			);

		}

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

} else if ($_POST['action'] == "pagar_servientrega") {

	try {

		$id_solicitud = $_POST['id_solicitud'];

		$conn = new mysqli($host, $user, $pass, $db);
		// id_estado_servientrega 4 = pagado
		$stmt = $conn->prepare("UPDATE entregas_servientrega SET id_estado_solicitud = 4 WHERE id_solicitud = ? AND del = 0");
		$stmt->bind_param("i", $id_solicitud);
		$stmt->execute();
		$rows = $stmt->affected_rows;
		$error = $stmt->error;
		$stmt->close();

		if ($rows == 1) {

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
			);
		} else {

			$response = array(
				"response" => "error",
				"error" => $error
			);
		}
	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);
	}

	echo json_encode($response);

}else if($_POST['action'] == "cambiar_comprobantes"){

	try {

		//var_dump($_FILES['comprobantes_entrega']);
		//die();

		$id_solicitud = $_POST['id_solicitud'];

		$filepath = "../documents/solicitudes/".$id_solicitud."/confirmaciones/"; // or image.jpg

		$extension = "jpg";

		if (!file_exists($filepath)) {
			mkdir($filepath, 0777, true);
		}else{
			rmDir_rf($filepath);
			mkdir($filepath, 0777, true);
		}

		// Count # of uploaded files in array
		$total = count($_FILES['comprobantes_entrega']['name']);

		//echo $total;
		//die();

		$validate_array = array();

		// Loop through each file
		for( $i=0 ; $i < $total ; $i++ ) {

			//Get the temp file path
			$tmpFilePath = $_FILES['comprobantes_entrega']['tmp_name'][$i];

			//Make sure we have a file path
			if ($tmpFilePath != ""){
				//Setup our new file path
				$newFilePath = $filepath.$i.$extension;

				//Upload the file into the temp dir
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {

					array_push($validate_array, 1);

				}
			}

		}

		if($total == count($validate_array)){

			$response = array(
				"response" => "success",
				"id_solicitud" => $id_solicitud
			);

		}else{

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

}else if($_POST['action'] == "definir_venta_no_realizada"){

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

}else if($_POST['action'] == "add_comentario"){

	try {

		$id_prospecto = $_POST['id_prospecto'];
		$texto_actual = execute_scalar("SELECT prospecto_detalles.observacion_prospecto FROM prospecto_detalles WHERE prospecto_detalles.id = $id_prospecto AND del = 0");
		$add_observacion = $_POST['observacion_create_prospecto'];

		$nuevo_texto = $texto_actual.'<br>'.$add_observacion;

		$conn = new mysqli($host, $user, $pass, $db);
		$stmt = $conn->prepare("UPDATE prospecto_detalles SET observacion_prospecto = ? WHERE id = ?");
		$stmt->bind_param("si", $nuevo_texto, $id_prospecto);
		$stmt->execute();

		if($stmt->affected_rows == 1){

			$response = array(
				"response" => "success",
				"id_prospecto" => $id_prospecto,
				"nuevo_texto" => $nuevo_texto
			);

		}else{

			$response = array(
				"response" => "error",
				"error" => $stmt->error
			);

		}

		//echo $nuevo_texto;
		//die();

	} catch (Exception $e) {

		$response = array(
			'error' => $e->getMessage()
		);

	}

	echo json_encode($response);
}
