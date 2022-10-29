<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

require_once('../vendor/autoload.php');

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


if ($_POST['action'] == "select_resultados") {
    try {

        $id_prospecto = $_POST['id_prospecto'];
        $query = "SELECT resultados_prospectos.id_plataforma, plataformas_credito.nombre_plataforma, resultados_prospectos.resultado_dc FROM resultados_prospectos LEFT JOIN plataformas_credito ON resultados_prospectos.id_plataforma = plataformas_credito.id WHERE resultados_prospectos.id_prospecto = $id_prospecto AND resultados_prospectos.del = 0";
        $result = qry($query);
        $array_resultados_plataformas = array();
        while ($row = mysqli_fetch_array($result)) {
            $id_plataforma = $row['id_plataforma'];
            $nombre_plataforma = $row['nombre_plataforma'];
            $resultado_dc = $row['resultado_dc'];
            $ruta = 'N/A';

            $new_array = array('id_plataforma' => $id_plataforma, 'nombre_plataforma' => $nombre_plataforma, 'resultado_dc' => $resultado_dc, 'ruta' => $ruta);
            array_push($array_resultados_plataformas, $new_array);
        }

        $response = array(
            "response" => "success",
            "id_prospecto" => $id_prospecto
        );

        array_push($response, $array_resultados_plataformas);
    } catch (Exception $e) {
        $response = array(
            "response" => "e",
            "error" => $stmt->error
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "cargar_resultado") {

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $resultado_plataforma = $_POST['resultado_plataforma'];
        $id_plataforma = $_POST['id_plataforma'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO resultados_prospectos (id_prospecto, id_plataforma, resultado_dc) VALUES(?, ?, ?)");
        $stmt->bind_param("iii", $id_prospecto, $id_plataforma, $resultado_plataforma);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $id_resultado = $stmt->insert_id;
            $stmt->close();

            if ($resultado_plataforma != 4) {

                /*
                    SI REQUIERE COMPROBANTE

                 */

                $name = $_FILES['cargar_comprobante']['name'];
                $tmp_name = $_FILES['cargar_comprobante']['tmp_name'];
                $type = $_FILES['cargar_comprobante']['type'];

                if ($_FILES['cargar_comprobante']['type'] == "image/jpeg") {

                    $extension = "jpg";
                } else if ($_FILES['cargar_comprobante']['type'] == "image/png") {

                    $extension = "jpg";
                } else {

                    $response = array(
                        "response" => "tipo_incorrecto"
                    );

                    echo json_encode($response);

                    die();
                }

                if ($resultado_plataforma == 1) {
                    $nombre_resultado = "aprobado";
                } else if ($resultado_plataforma == 2) {
                    $nombre_resultado = "rechazado";
                } else if ($resultado_plataforma == 3) {
                    $nombre_resultado = "contra_activo";
                }

                $nombre_plataforma = execute_scalar("SELECT nombre_plataforma FROM plataformas_credito WHERE plataformas_credito.id = $id_plataforma");


                $stmt = $conn->prepare("INSERT INTO imagenes_resultados_prospectos (id_resultado, imagen_nombre_archivo, imagen_extension) VALUES(?, ?, ?)");
                $stmt->bind_param("iss", $id_resultado, $nombre_resultado, $extension);
                $stmt->execute();

                if ($stmt->affected_rows == 1) {

                    $route = '../documents/prospects/' . $id_prospecto . '/resultado/';
                    $pictureFileName = $nombre_plataforma . '.' . $extension;

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
                            "id_plataforma" => $id_plataforma,
                            "tipo_img" => $nombre_resultado,
                            "resultado" => $resultado_plataforma,
                            "route" => './documents/prospects/' . $id_prospecto . '/' . $pictureFileName,
                            "nombre_plataforma" => $nombre_plataforma
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
            } else if ($resultado_plataforma == 4) {

                /*
                Si el tipo de resultado es otro
                */

                $observacion_otro = $_POST['observacion_otro'];

                $stmt = $conn->prepare("INSERT INTO observaciones_resultados_prospectos (id_resultado, observacion_texto) VALUES(?, ?)");
                $stmt->bind_param("is", $id_resultado, $observacion_otro);
                $stmt->execute();

                if ($stmt->affected_rows == 1) {

                    $response = array(
                        "response" => "success",
                        "id_prospecto" => $id_prospecto,
                        "id_plataforma" => $id_plataforma,
                        "observacion" => $observacion_otro,
                        "resultado" => $resultado_plataforma
                    );
                } else {

                    $response = array(
                        "response" => "error",
                        "error" => $stmt->error
                    );
                }
            }

            $stmt->close();
        } else {

            $response = array(
                "response" => "error1",
                "error" => $stmt->error
            );
        }

        $conn->close();
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "select_plataformas") {

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $id_usuario = $_POST['id_usuario'];

        $lista_plataformas = traer_plataformas($id_prospecto);

        $logistico = profile(44, $id_usuario);

        $total_plataformas = execute_scalar("SELECT COUNT(id) AS resultados FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND del = 0");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_numero_contacto, ciudades.ciudad, departamentos.departamento, estados_prospectos.estado_prospecto, prospectos.id_estado_prospecto, prospectos.id_responsable_interno, prospectos.id_usuario_validador, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y %H:%i:%s') FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id WHERE prospectos.id = ?");
        $stmt->bind_param("i", $id_prospecto);
        $stmt->execute();
        $stmt->bind_result($prospecto_cedula, $prospecto_numero_contacto, $ciudad, $departamento, $estado_prospecto, $id_estado_prospecto, $id_responsable_interno, $id_usuario_validador, $fecha_registro);
        $stmt->store_result();
        $stmt->fetch();

        $prospecto_nombre = utf8_encode(execute_scalar("SELECT prospecto_nombre FROM prospecto_detalles WHERE id_prospecto = $id_prospecto"));

        $prospecto_apellidos = utf8_encode(execute_scalar("SELECT prospecto_apellidos FROM prospecto_detalles WHERE id_prospecto = $id_prospecto"));

        $asesor_nombre = utf8_encode(execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS asesor_nombre FROM usuarios WHERE id = $id_responsable_interno"));

        $validador_nombre = utf8_encode(execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS asesor_nombre FROM usuarios WHERE id = $id_usuario_validador"));

        $estado_texto = utf8_encode(execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $id_estado_prospecto"));

        $ubicacion = $ciudad . ' / ' . $departamento;

        if($ciudad = '' || $ciudad == null) {
            $ubicacion = 'N/A';
        }

        if($prospecto_numero_contacto == '' || $prospecto_numero_contacto == null) {
            $prospecto_numero_contacto = 'N/A';
        }

        $data = array(
            'status' => 'success',
            'message' => 'Prospecto actualizado correctamente',
            'id_prospecto' => $id_prospecto,
            'lista_plataformas' => $lista_plataformas,
            'logistico' => $logistico,
            'total_plataformas' => $total_plataformas,
            'prospecto_cedula' => $prospecto_cedula,
            'prospecto_nombre' => $prospecto_nombre . ' ' . $prospecto_apellidos,
            'prospecto_numero_contacto' => $prospecto_numero_contacto,
            'ubicacion' => $ubicacion,
            'id_estado_prospecto' => $id_estado_prospecto,
            'id_responsable_interno' => $id_responsable_interno,
            'id_usuario_validador' => $id_usuario_validador,
            'fecha_registro' => $fecha_registro,
            'validador_nombre' => $validador_nombre,
            'asesor_nombre' => $asesor_nombre,
            'estado_texto' => $estado_texto
        );

        $stmt->close();
        $conn->close();

        echo json_encode($data);
        die();

        /*

        $id_prospecto = $_POST['id_prospecto'];
        $id_usuario = $_POST['id_usuario'];

        $lista_plataformas = traer_plataformas($id_prospecto);

        $logistico = profile(44, $id_usuario);

        $total_plataformas = execute_scalar("SELECT COUNT(id) AS resultados FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND del = 0");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, ciudades.ciudad, departamentos.departamento, estados_prospectos.estado_prospecto, prospectos.id_estado_prospecto, prospectos.id_responsable_interno, prospectos.id_usuario_validador, DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y %H:%i:%s') FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id WHERE prospectos.id = ?");
        $stmt->bind_param("i", $id_prospecto);
        $stmt->execute();
        $stmt->bind_result($prospecto_cedula, $prospecto_nombre, $prospecto_apellidos, $prospecto_numero_contacto, $ciudad, $departamento, $estado_prospecto, $id_estado_prospecto, $id_responsable_interno, $id_usuario_validador, $fecha_registro);
        $stmt->store_result();
        $stmt->fetch();
        
        if ($stmt->num_rows == 1) {

            $asesor_nombre = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS asesor_nombre FROM usuarios WHERE id = $id_responsable_interno");

            $validador_nombre = execute_scalar("SELECT CONCAT(nombre, ' ', apellidos) AS asesor_nombre FROM usuarios WHERE id = $id_usuario_validador");

            $response = array(
                'status' => 'success',
                'message' => 'Prospecto actualizado correctamente',
                'data' => array(
                    'prospecto_cedula' => $prospecto_cedula,
                    'prospecto_nombre' => $prospecto_nombre. ' ' .$prospecto_apellidos,
                    'prospecto_numero_contacto' => $prospecto_numero_contacto,
                    'ubicacion' => $ciudad.' / '.$departamento,
                    'estado_prospecto' => $estado_prospecto,
                    'id_estado_prospecto' => $id_estado_prospecto,
                    'id_responsable_interno' => $id_responsable_interno,
                    'id_usuario_validador' => $id_usuario_validador,
                    'fecha_registro' => $fecha_registro,
                    'validador_nombre' => $validador_nombre,
                    'asesor_nombre' => $asesor_nombre
                ),
                'id_prospecto' => $id_prospecto,
                'lista_plataformas' => $lista_plataformas,
                'logistico' => $logistico,
                'total_plataformas' => $total_plataformas
            );

        } else {

            $response = array(
                "response" => "error",
                "error" => $stmt->error,
                'message' => 'Error en el proceso'
            );
        }

        */

        /*

        $prospecto_cedula = execute_scalar("SELECT prospectos.prospecto_cedula FROM prospectos WHERE prospectos.id = $id_prospecto");
        $prospecto_nombre = execute_scalar("SELECT CONCAT(prospecto_detalles.prospecto_nombre, ' ', prospecto_detalles.prospecto_apellidos) AS prospecto_info FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id WHERE prospectos.id = $id_prospecto");
        $prospecto_contacto = execute_scalar("SELECT prospecto_numero_contacto FROM prospecto_detalles WHERE id_prospecto = $id_prospecto AND del = 0");
        $prospecto_ubicacion = execute_scalar("SELECT CONCAT(ciudades.ciudad, ' / ', departamentos.departamento) AS ubicacion FROM prospecto_detalles LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id WHERE prospecto_detalles.id_prospecto = $id_prospecto AND prospecto_detalles.del = 0");
        $estado_prospecto = execute_scalar("SELECT estados_prospectos.estado_prospecto FROM prospectos LEFT JOIN estados_prospectos ON prospectos.id_estado_prospecto = estados_prospectos.id WHERE prospectos.id = $id_prospecto");
        $id_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE prospectos.id = $id_prospecto");
        $asesor_nombre = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS asesor_nombre FROM prospectos LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id = $id_prospecto");
        $validador_nombre = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS asesor_nombre FROM prospectos LEFT JOIN usuarios ON prospectos.id_usuario_validador = usuarios.id WHERE prospectos.id = $id_prospecto");
        $fecha_registro = execute_scalar("SELECT DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y %H:%i:%s') AS fecha_registro FROM prospectos WHERE prospectos.id = $id_prospecto");

        $total_plataformas = execute_scalar("SELECT COUNT(id) AS resultados FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND del = 0");

        $response = array(
            "response" => "success",
            "id_prospecto" => $id_prospecto,
            "lista_plataformas" => $lista_plataformas,
            "prospecto_nombre" => $prospecto_nombre,
            "contacto" => $prospecto_contacto,
            "ubicacion" => $prospecto_ubicacion,
            "estado_prospecto" => $estado_prospecto,
            "id_estado_prospecto" => $id_estado_prospecto,
            "asesor_nombre" => $asesor_nombre,
            "validador_nombre" => $validador_nombre,
            "fecha_registro" => $fecha_registro,
            "logistico" => $logistico,
            "total_plataformas" => $total_plataformas,
            "prospecto_cedula" => $prospecto_cedula
        );

        */

        //echo $response;
        //die();

    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "select_observacion") {

    try {

        $id_prospecto = $_POST['id_prospecto'];

        $id_plataforma = $_POST['id_plataforma'];

        $resultado_dc = 4;

        $id_resultado = execute_scalar("SELECT resultados_prospectos.id FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND id_plataforma = $id_plataforma AND resultado_dc = $resultado_dc AND del = 0");

        $observacion_prospecto = execute_scalar("SELECT observaciones_resultados_prospectos.observacion_texto FROM observaciones_resultados_prospectos WHERE observaciones_resultados_prospectos.id_resultado = $id_resultado AND observaciones_resultados_prospectos.del = 0");

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
} else if ($_POST['action'] == "eliminar_resultado") {

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $id_plataforma = $_POST['id_plataforma'];

        $id_resultado = execute_scalar("SELECT resultados_prospectos.id AS id_resultado FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND id_plataforma = $id_plataforma");
        $resultado_dc = execute_scalar("SELECT resultado_dc FROM resultados_prospectos WHERE id = $id_resultado");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("DELETE FROM resultados_prospectos WHERE id = ?");
        $stmt->bind_param("i", $id_resultado);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $stmt->close();

            if ($resultado_dc != 4) {

                $extension = execute_scalar("SELECT imagen_extension FROM imagenes_resultados_prospectos WHERE id_resultado = $id_resultado");

                $nombre_plataforma = execute_scalar("SELECT nombre_plataforma FROM plataformas_credito WHERE id = $id_plataforma");

                $stmt = $conn->prepare("DELETE FROM imagenes_resultados_prospectos WHERE id_resultado = ?");
                $stmt->bind_param("i", $id_resultado);
                $stmt->execute();
            } else {

                $stmt = $conn->prepare("DELETE FROM observaciones_resultados_prospectos WHERE id_resultado = ?");
                $stmt->bind_param("i", $id_resultado);
                $stmt->execute();
            }

            if ($stmt->affected_rows == 1) {

                if ($resultado_dc != 4) {

                    $route = '../documents/prospects/' . $id_prospecto . '/resultado/';
                    $pictureFileName = $nombre_plataforma . '.' . $extension;

                    if (is_file($route . $pictureFileName)) {

                        unlink($route . $pictureFileName);

                        $response = array(
                            "response" => "success",
                            "id_prospecto" => $id_prospecto,
                            "id_plataforma" => $id_plataforma,
                            "resultado_dc" => $resultado_dc
                        );
                    } else {

                        $response = array(
                            "response" => "error",
                            "error" => $stmt->error,
                            "number" => "3"
                        );
                    }
                } else {

                    $response = array(
                        "response" => "success",
                        "id_prospecto" => $id_prospecto,
                        "id_plataforma" => $id_plataforma,
                        "resultado_dc" => $resultado_dc
                    );
                }

                $stmt->close();
            } else {

                $response = array(
                    "response" => "error",
                    "error" => $stmt->error,
                    "number" => "2"
                );
            }
        } else {

            $response = array(
                "response" => "error",
                "error" => $stmt->error,
                "number" => "1"
            );
        }

        $conn->close();
    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "generar_zip") {

    try {

        $id_prospecto = $_POST['id_prospecto'];

        $route = '../documents/prospects/' . $id_prospecto . '/';
        // Creamos un instancia de la clase ZipArchive
        $zip = new ZipArchive();
        // Creamos y abrimos un archivo zip temporal
        $zip->open($route . "imagenes.zip", ZipArchive::CREATE);
        //$zip->open("imagenes.zip",ZipArchive::CREATE);
        // Añadimos un archivo en la raid del zip.
        $query_imagenes = "SELECT imagen_nombre_archivo, imagen_extension FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND del = 0";
        $result = qry($query_imagenes);
        while ($row = mysqli_fetch_array($result)) {
            $imagen_nombre_archivo = $row['imagen_nombre_archivo'];
            $imagen_extension = $row['imagen_extension'];
            $zip->addFile($route . $imagen_nombre_archivo . '.' . $imagen_extension, $imagen_nombre_archivo . '.' . $imagen_extension);
        }
        // Una vez añadido los archivos deseados cerramos el zip.
        $zip->close();

        if (file_exists($route . 'imagenes.zip')) {

            $route2 = './documents/prospects/' . $id_prospecto . '/imagenes.zip';
            $cc_cliente = execute_scalar("SELECT prospectos.prospecto_cedula FROM prospectos WHERE id = $id_prospecto");

            $response = array(
                "response" => "success",
                "id_prospecto" => $id_prospecto,
                "ruta" => $route2,
                "cc_cliente" => $cc_cliente
            );
        } else {

            $response = array(
                "response" => "error"
            );
        }
        /*
        // Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment; filename=miarchivo.zip");
        // leemos el archivo creado
        readfile('miarchivo.zip');
        // Por último eliminamos el archivo temporal creado
        unlink('miarchivo.zip');//Destruye el archivo temporal
        */
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "pdte_llamar_insert") {

    try {

        $pickup_date = $_POST['pickup_date'];
        $pickup_start = $_POST['pickup_start'] . ':00';
        $id_estado = $_POST['estado_pendiente_por_llamar'];
        $id_prospecto = $_POST['id_prospecto'];

        $fecha_format = substr($pickup_date, 6, 4) . '-' . substr($pickup_date, 0, 2) . '-' . substr($pickup_date, 3, 2) . ' ' . $pickup_start;

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO prospectos_pendiente_llamar (id_prospecto, id_estado_recordatorio, fecha_hora_llamada) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id_prospecto, $id_estado, $fecha_format);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $stmt->close();

            $id_estado_pdte_llamar = 7;

            $stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = ? WHERE id = ?");
            $stmt->bind_param("ii", $id_estado_pdte_llamar, $id_prospecto);
            $stmt->execute();

            if ($stmt->affected_rows == 1) {

                $estado_nombre = execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $id_estado_pdte_llamar");

                $response = array(
                    "response" => "success",
                    "id_prospecto" => $id_prospecto,
                    "estado_nombre" => $estado_nombre
                );
            } else {

                $response = array(
                    "response" => "error",
                    "error" => $stmt->error,
                    "number" => "2"
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
} else if ($_POST['action'] == "pdte_llamar_update") {

    try {

        $pickup_date = $_POST['pickup_date'];
        $pickup_start = $_POST['pickup_start'] . ':00';
        $id_estado = $_POST['estado_pendiente_por_llamar'];
        $id_prospecto = $_POST['id_prospecto'];

        $fecha_format = substr($pickup_date, 6, 4) . '-' . substr($pickup_date, 0, 2) . '-' . substr($pickup_date, 3, 2) . ' ' . $pickup_start;

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("UPDATE prospectos_pendiente_llamar SET id_prospecto = ?, id_estado_recordatorio = ?, fecha_hora_llamada = ?, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id_prospecto = ?");
        $stmt->bind_param("iisi", $id_prospecto, $id_estado, $fecha_format, $id_prospecto);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_prospecto" => $id_prospecto
            );
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
} else if ($_POST['action'] == "select_pro_llamada") {

    try {

        $id_prospecto = $_POST['id_prospecto'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT prospectos_pendiente_llamar.id, prospectos_pendiente_llamar.fecha_hora_llamada, prospectos_pendiente_llamar.id_estado_recordatorio FROM prospectos_pendiente_llamar WHERE id_prospecto = ?");
        $stmt->bind_param("i", $id_prospecto);
        $stmt->execute();
        $stmt->bind_result($id, $fecha_hora_llamada, $id_estado_recordatorio);
        $stmt->store_result();
        $stmt->fetch();
        //echo $stmt->num_rows;
        //die();
        if ($stmt->num_rows == 1) {

            $fecha_hora_llamada = explode(' ', $fecha_hora_llamada);
            $fecha_llamada = $fecha_hora_llamada[0];
            $fecha_llamada  = substr($fecha_llamada, 0, 16);
            $fecha_llamada  = explode(' ', $fecha_llamada);
            $fecha_llamada = $fecha_hora_llamada[0];
            $fecha_llamada = explode('-', $fecha_llamada);
            $fecha_llamada = $fecha_llamada[1] . '-' . $fecha_llamada[2] . '-' . $fecha_llamada[0];

            $estado_nombre = execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $id_estado_recordatorio");

            $response = array(
                "response" => "success",
                "id_prospecto" => $id_prospecto,
                "fecha_llamada" => $fecha_llamada,
                "hora_llamada" => $fecha_hora_llamada[1],
                "id_estado_recordatorio" => $id_estado_recordatorio,
                "estado_nombre" => $estado_nombre
            );
        } else {

            $response = array(
                "response" => "error",
                "error" => $stmt->error,
                "number" => "1",
                "num_rows" => $stmt->num_rows
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
} else if ($_POST['action'] == "validate_pdte_por_entregar") {

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $validate_existe_aprobado = execute_scalar("SELECT COUNT(id) AS total FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND resultado_dc = 1 AND del = 0");

        if ($validate_existe_aprobado == 0) {

            $response = array(
                "response" => "falta_aprobado"
            );

        } else {

            $ubicacion_prospecto = execute_scalar("SELECT ciudad_id FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");

            // SE VALIDA SI TIENE UNA PLATAFORMA O MAS DE UNA

            if ($validate_existe_aprobado == 1) {

                $id_plataforma = execute_scalar("SELECT id_plataforma FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND resultado_dc = 1 AND del = 0");

                $id_referencia = execute_scalar("SELECT id_referencia FROM referencias_prospectos WHERE id_prospecto = $id_prospecto AND del = 0");

                $id_existencia = execute_scalar("SELECT productos_stock.id FROM productos LEFT JOIN inventario ON inventario.id_producto = productos.id LEFT JOIN productos_stock ON inventario.id = productos_stock.id_inventario WHERE productos.id_modelo = $id_referencia AND productos_stock.id_estado_producto = 1 AND productos_stock.del = 0");

                if ($id_existencia == '') {
                    $id_existencia = 0;
                }

                if ($ubicacion_prospecto == 1) {

                    $id_estado_prospecto = 3;
                    $color_estado = "success";

                } else {

                    $id_estado_prospecto = 4;
                    $color_estado = "info";
                }

                $conn = new mysqli($host, $user, $pass, $db);
                $stmt = $conn->prepare("INSERT INTO despachos_prospectos (id_prospecto, id_medio_envio, id_existencia, id_plataforma, fecha_registro, ultimo_cambio) VALUES (?, 0, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
                $stmt->bind_param("iii", $id_prospecto, $id_existencia, $id_plataforma);
                $stmt->execute();

                if ($stmt->affected_rows == 1) {

                    $stmt->close();

                    $stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = ?, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
                    $stmt->bind_param("ii", $id_estado_prospecto, $id_prospecto);
                    $stmt->execute();

                    if ($stmt->affected_rows == 1) {

                        $stmt->close();
                        $id_estado_prospecto = 2;
                        $stmt = $conn->prepare("UPDATE productos_stock SET id_estado_producto = ? WHERE id = ?");
                        $stmt->bind_param("ii", $id_estado_prospecto, $id_existencia);
                        $stmt->execute();

                        if ($stmt->affected_rows == 1) {

                            $estado_nombre = execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $id_estado_prospecto");

                            $response = array(
                                "response" => "success",
                                "id_prospecto" => $id_prospecto,
                                "id_estado_prospecto" => $id_estado_prospecto,
                                "estado_nombre" => $estado_nombre,
                                "color_estado" => $color_estado
                            );

                        } else {

                            $response = array(
                                "response" => "error",
                                "error" => $stmt->error,
                                "number" => "3"
                            );

                        }

                    } else {

                        $response = array(
                            "response" => "error",
                            "error" => $stmt->error,
                            "number" => "1"
                        );
                    }

                } else {

                    $response = array(
                        "response" => "error",
                        "error" => $stmt->error,
                        "number" => "2"
                    );
                }

                $stmt->close();
                $conn->close();

            } else {

                //$plataformas_array = array();
                $query = "SELECT id_plataforma FROM resultados_prospectos WHERE id_prospecto = $id_prospecto AND resultado_dc = 1 AND del = 0";
                $result = qry($query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $new_array = array(
                        "id_plataforma" => $row['id_plataforma']
                    );
                    array_push($plataformas_array, $new_array);
                }

                $response = array(
                    "response" => "varios_aprobados",
                    "id_plataformas" => $plataformas_array,
                    "id_prospecto" => $id_prospecto
                );
            }
        }

    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

} else if ($_POST['action'] == "cargar_comprobante") {

    try {

        //var_dump($_POST);
        //die();

        $id_prospecto = $_POST['id_prospecto'];
        $id_usuario = $_POST['id_usuario'];
        $tipo_img = $_POST['tipo_img'];

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

            $stmt->close();

            $id_estado_prospecto = 3;

            $stmt = $conn->prepare("UPDATE prospectos SET prospectos.id_estado_prospecto = ?, prospectos.ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE prospectos.id = ?");
            $stmt->bind_param("ii", $id_estado_prospecto, $id_prospecto);
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

                    $estado_nombre = execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $id_estado_prospecto");

                    $response = array(

                        "response" => "success",
                        "id_prospecto" => $id_prospecto,
                        "tipo_img" => $tipo_img,
                        "route" => './documents/prospects/' . $id_prospecto . '/' . $pictureFileName,
                        "estado_nombre" => $estado_nombre
                    );
                } else {

                    $response = array(
                        "response" => "error",
                        "num" => "3"
                    );
                }
            } else {

                $response = array(
                    "response" => "error",
                    "error" => $stmt->error,
                    "num" => "2"
                );
            }
        } else {

            $response = array(
                "response" => "error",
                "error" => $stmt->error,
                "num" => "1"
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
} else if ($_POST['action'] == "cargar_contrato") {

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $id_usuario = $_POST['id_usuario'];
        $tipo_img = $_POST['tipo_img'];

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

        $validate_exist = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = '$tipo_img' AND imagenes_prospectos.del = 0");

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

            $stmt->close();

            $route = '../documents/prospects/' . $id_prospecto . '/';
            $pictureFileName = $tipo_img . '.' . $extension;

            if (!file_exists($route)) {

                mkdir($route, 0777, true);
            }

            if (is_file($route . $pictureFileName)) {
                unlink($route . $pictureFileName);
            }

            if (move_uploaded_file($tmp_name, $route . $pictureFileName)) {

                $validate_estado_prospecto = execute_scalar("SELECT id_estado_prospecto FROM prospectos WHERE id = $id_prospecto");

                if ($validate_estado_prospecto != 3) {

                    $id_estado_prospecto = 3;

                    $stmt = $conn->prepare("UPDATE prospectos SET id_estado_prospecto = ? ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE prospectos.id = ?");
                    $stmt->bind_param("ii", $id_estado_prospecto, $id_prospecto);
                    $stmt->execute();
                    $stmt->close();
                }

                $estado_nombre = execute_scalar("SELECT estado_prospecto FROM estados_prospectos WHERE id = $validate_estado_prospecto");

                $response = array(

                    "response" => "success",
                    "id_prospecto" => $id_prospecto,
                    "tipo_img" => $tipo_img,
                    "route" => './documents/prospects/' . $id_prospecto . '/' . $pictureFileName,
                    "estado_nombre" => $estado_nombre
                );
            } else {

                $response = array(
                    "response" => "error",
                    "num" => "2"
                );
            }
        } else {

            $response = array(
                "response" => "error",
                "error" => $stmt->error,
                "num" => "1"
            );
        }


        $conn->close();
    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "validate_imagenes") {

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $validate_comprobante = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = 'comprobante' AND imagenes_prospectos.del = 0");
        $validate_contrato = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_prospecto = $id_prospecto AND imagenes_prospectos.imagen_nombre_archivo = 'contrato' AND imagenes_prospectos.del = 0");
        $id_ciudad_prospecto = execute_scalar("SELECT ciudad_id FROM prospecto_detalles WHERE id_prospecto = $id_prospecto");
        $route_comprobante = 'N/A';
        $route_contrato = 'N/A';

        if ($validate_comprobante == 1) {

            $route_comprobante = './documents/prospects/' . $id_prospecto . '/comprobante.jpg';
        }

        if ($validate_contrato == 1) {

            $route_contrato = './documents/prospects/' . $id_prospecto . '/contrato.jpg';
        }

        $response = array(
            "response" => "success",
            "validate_comprobante" => $validate_comprobante,
            "validate_contrato" => $validate_contrato,
            "route_comprobante" => $route_comprobante,
            "route_contrato" => $route_contrato,
            "id_prospecto" => $id_prospecto,
            "id_ciudad_prospecto" => $id_ciudad_prospecto
        );
    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "completar_entrega") {

    try {

        $id_prospecto = $_POST['id_prospecto'];

        //verificamos si existe registro
        $validate_exist = execute_scalar("SELECT COUNT(id) AS total FROM despachos_prospectos WHERE id_prospecto = $id_prospecto AND despachos_prospectos.del = 0");

        if ($validate_exist > 0) {

            $conn = new mysqli($host, $user, $pass, $db);
            $stmt = $conn->prepare("SELECT despachos_prospectos.id AS id_despacho, id_medio_envio, imei_dispositivo FROM despachos_prospectos WHERE id_prospecto = ? AND despachos_prospectos.del = 0");
            $stmt->bind_param("i", $id_prospecto);
            $stmt->execute();
            $stmt->bind_result($id_despacho, $id_medio_envio, $imei_dispositivo);
            $stmt->store_result();
            $stmt->fetch();
            //echo $stmt->num_rows;
            //die();
            if ($stmt->num_rows == 1) {

                $id_domiciliario = 0;
                $guia_servientrega = 'N/A';
                $id_responsable_tienda = 0;

                if ($id_medio_envio == 1) {

                    $id_domiciliario = execute_scalar("SELECT id_domiciliario FROM despachos_prospectos_domicilios WHERE id_despacho = $id_despacho AND del = 0");
                } else if ($id_medio_envio == 2) {

                    $guia_servientrega = execute_scalar("SELECT guia_servientrega FROM despachos_prospectos_servientrega WHERE id_despacho = $id_despacho AND del = 0");
                } else if ($id_medio_envio == 3) {

                    $id_responsable_tienda = execute_scalar("SELECT id_responsable_tienda FROM despachos_prospectos_tienda WHERE id_despacho = $id_despacho AND del = 0");
                }

                $response = array(
                    "response" => "success",
                    "id_prospecto" => $id_prospecto,
                    "id_medio_envio" => $id_medio_envio,
                    "imei_dispositivo" => $imei_dispositivo,
                    "id_domiciliario" => $id_domiciliario,
                    "guia_servientrega" => $guia_servientrega,
                    "id_responsable_tienda" => $id_responsable_tienda
                );
            } else {

                $response = array(
                    "response" => "error",
                    "error" => $stmt->error,
                    "num" => "1"
                );
            }
        } else {

            $response = array(
                "response" => "success",
                "id_prospecto" => $id_prospecto,
                "id_medio_envio" => '',
                "imei_dispositivo" => ''
            );
        }
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "insert_despacho") {

    try {

        //var_dump($_POST);
        //die();
        $id_prospecto = $_POST['id_prospecto'];
        $imei_referencia = $_POST['imei_referencia'];
        $id_medio_envio = $_POST['medio_envio'];

        if ($imei_referencia == '') {

            $response = array(
                "response" => "falta_imei"
            );

            echo json_encode($response);
            die();
        }

        switch ($id_medio_envio) {
            case '1':
                if (!isset($_POST['domiciliario_solicitud'])) {

                    $response = array(
                        "response" => "falta_domiciliario"
                    );

                    echo json_encode($response);
                    die();
                }
                break;
            case '3':
                if (!isset($_POST['responsable_entrega'])) {

                    $response = array(
                        "response" => "falta_responsable_tienda"
                    );

                    echo json_encode($response);
                    die();
                }
                break;
            default:
                if ($_POST['guia_servientrega'] == '') {

                    $response = array(
                        "response" => "falta_guia_servientrega"
                    );

                    echo json_encode($response);
                    die();
                }
                break;
        }


        $validate_exist = execute_scalar("SELECT COUNT(id) AS total FROM despachos_prospectos WHERE id_prospecto = $id_prospecto AND despachos_prospectos.del = 0");

        $conn = new mysqli($host, $user, $pass, $db);
        if ($validate_exist == 1) {
            $id_despacho = execute_scalar("SELECT id FROM despachos_prospectos WHERE id_prospecto = $id_prospecto AND despachos_prospectos.del = 0");
            $id_medio_envio_old = execute_scalar("SELECT id_medio_envio FROM despachos_prospectos WHERE despachos_prospectos.id = $id_despacho");
            $tabla_medio = '';
            if ($id_medio_envio_old == 1) {
                $tabla_medio = 'despachos_prospectos_domicilios';
            } else if ($id_medio_envio_old == 2) {
                $tabla_medio = 'despachos_prospectos_servientrega';
            } else if ($id_medio_envio_old == 3) {
                $tabla_medio = 'despachos_prospectos_tienda';
            }
            $stmt = $conn->prepare("DELETE FROM $tabla_medio WHERE id_despacho = ? AND del = 0");
            $stmt->bind_param("i", $id_despacho);
            $stmt->execute();
            $stmt->close();
            $stmt = $conn->prepare("UPDATE despachos_prospectos SET id_medio_envio = ?, imei_dispositivo = ?, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE despachos_prospectos.id = ?");
            $stmt->bind_param("iii", $id_medio_envio, $imei_referencia, $id_despacho);
        } else {

            $stmt = $conn->prepare("INSERT INTO despachos_prospectos (id_prospecto, id_medio_envio, imei_dispositivo, fecha_registro) VALUES (?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
            $stmt->bind_param("iii", $id_prospecto, $id_medio_envio, $imei_referencia);
        }

        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $id_despacho = execute_scalar("SELECT id FROM despachos_prospectos WHERE id_prospecto = $id_prospecto AND despachos_prospectos.del = 0");

            $stmt->close();

            if ($id_medio_envio == 1) {

                $id_domiciliario = $_POST['domiciliario_solicitud'];

                $stmt = $conn->prepare("INSERT INTO despachos_prospectos_domicilios (id_despacho, id_domiciliario, fecha_registro, ultimo_cambio) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
                $stmt->bind_param("ii", $id_despacho, $id_domiciliario);
            } else if ($id_medio_envio == 2) {

                $guia_servientrega = $_POST['guia_servientrega'];

                $stmt = $conn->prepare("INSERT INTO despachos_prospectos_servientrega (id_despacho, guia_servientrega, fecha_registro, ultimo_cambio) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
                $stmt->bind_param("ii", $id_despacho, $guia_servientrega);
            } else if ($id_medio_envio == 3) {

                $id_responsable_entrega = $_POST['responsable_entrega'];

                $stmt = $conn->prepare("INSERT INTO despachos_prospectos_tienda (id_despacho, id_responsable_tienda, fecha_registro, ultimo_cambio) VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
                $stmt->bind_param("ii", $id_despacho, $id_responsable_entrega);
            }

            $stmt->execute();

            if ($stmt->affected_rows == 1) {

                $response = array(
                    "response" => "success",
                    "id_despacho" => $id_despacho,
                    "id_prospecto" => $id_prospecto
                );
            } else {

                $response = array(
                    "response" => "error",
                    "error" => $stmt->error,
                    "num" => "2"
                );
            }
        } else {

            $response = array(
                "response" => "error",
                "error" => $stmt->error,
                "num" => "1"
            );
        }

        $conn->close();
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "validate_despachado") {

    try {

        var_dump($_POST);
        die();
    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

}else if($_POST['action'] == "select_plantilla"){

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, colores_productos.color_desc, referencias_prospectos.inicial_confirmada, referencias_prospectos.plazo_meses, prospecto_detalles.prospecto_direccion, ciudades.ciudad, usuarios.nombre, usuarios.apellidos FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN referencias_prospectos ON referencias_prospectos.id_prospecto = prospectos.id LEFT JOIN modelos ON referencias_prospectos.id_referencia = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN colores_productos ON referencias_prospectos.id_color = colores_productos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id = ?");
        $stmt->bind_param("i", $id_prospecto);
        $stmt->execute();
        $stmt->bind_result($prospecto_cedula, $prospecto_nombre, $prospecto_apellidos, $prospecto_numero_contacto, $prospecto_email, $nombre_modelo, $marca_producto, $capacidad, $color_desc, $inicial_confirmada, $plazo_meses, $prospecto_direccion, $ciudad, $nombre, $apellidos);

        while ($stmt->fetch()){

            $response = array(
                "response" => "success",
                "prospecto_cedula" => $prospecto_cedula,
                "prospecto_nombre" => $prospecto_nombre,
                "prospecto_apellidos" => $prospecto_apellidos,
                "prospecto_numero_contacto" => $prospecto_numero_contacto,
                "prospecto_email" => $prospecto_email,
                "nombre_modelo" => $nombre_modelo,
                "marca_producto" => $marca_producto,
                "capacidad" => $capacidad,
                "color_desc" => $color_desc,
                "inicial_confirmada" => $inicial_confirmada,
                "plazo_meses" => $plazo_meses,
                "prospecto_direccion" => $prospecto_direccion,
                "ciudad" => $ciudad,
                "nombre_responsable" => $nombre.' '.$apellidos
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

}else if($_POST['action'] == "select_validadores"){

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $id_usuario = $_POST['id_usuario'];
        $validadoresArray = array();
        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT usuarios.nombre, usuarios.apellidos, id_usuario FROM usuarios LEFT JOIN perfiles_usuarios ON perfiles_usuarios.id_usuario = usuarios.id WHERE (id_permiso = 25 OR id_permiso = 43 AND id_permiso = 44 IS NULL) AND id_usuario <> ? GROUP BY id_usuario");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array("response" => "success");
        while ($row = $result->fetch_assoc()) {
            $newArray = array(
                "id_usuario" => $row['id_usuario'],
                "nombre" => $row['nombre'] . " " . $row['apellidos']
            );
            array_push($validadoresArray, $newArray);
        }

        $stmt->close();
        $conn->close();
        
        array_push($response, $validadoresArray);

    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

}else if($_POST['action'] == "transfer_prospecto"){

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $id_usuario = $_POST['id_usuario'];
        $id_validador_new = $_POST['validadores'];
        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("UPDATE prospectos SET id_usuario_validador = ?, ultimo_cambio = DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR) WHERE id = ?");
        $stmt->bind_param("ii", $id_validador_new, $id_prospecto);
        $stmt->execute();
        if($stmt->affected_rows == 1){
            $response = array(
                "response" => "success",
                "id_prospecto" => $id_prospecto
            );
        }else{
            $response = array(
                "response" => "error"
            );
        }
        $stmt->close();
    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

}else if($_POST['action'] == "print_remision"){

    try {

        $id_prospecto = $_POST['id_prospecto'];
        $nombre_impresora = "GP-80250";

        $conector = new WindowsPrintConnector($nombre_impresora);

        $printer = new Printer($conector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->setTextSize(1, 2);

        try{
            $logo = EscposImage::load("../assets/images/logo-remision.png", false);
            $printer->bitImage($logo);
        }catch(Exception $e){/*No hacemos nada si hay error*/}

        $printer->text("_______________________________________________" . "\n");
        $printer->text("REMISION" . "\n");
        $printer->setTextSize(1, 1);
        $printer->text("" . "\n");
        $printer->text("SAS"."    "."X"."\n");
        $printer->text("MAURICIO" . "\n");
        $printer->text(date("Y-m-d H:i:s") . "\n");
        $printer->text("CALI-VALLE DEL CAUCA TELEFONO:" . "\n");
        $printer->text("3226698790" . "\n");
        $printer->text("_______________________________________________" . "\n");
        $printer->feed(1);


        $id_prospecto = $_POST['id_prospecto'];
        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT prospectos.prospecto_cedula, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospecto_detalles.prospecto_numero_contacto, prospecto_detalles.prospecto_email, modelos.nombre_modelo, marcas.marca_producto, capacidades_telefonos.capacidad, colores_productos.color_desc, referencias_prospectos.inicial_confirmada, referencias_prospectos.plazo_meses, prospecto_detalles.prospecto_direccion, ciudades.ciudad, usuarios.nombre, usuarios.apellidos FROM prospectos LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN referencias_prospectos ON referencias_prospectos.id_prospecto = prospectos.id LEFT JOIN modelos ON referencias_prospectos.id_referencia = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN colores_productos ON referencias_prospectos.id_color = colores_productos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id = ?");
        $stmt->bind_param("i", $id_prospecto);
        $stmt->execute();
        $stmt->bind_result($prospecto_cedula, $prospecto_nombre, $prospecto_apellidos, $prospecto_numero_contacto, $prospecto_email, $nombre_modelo, $marca_producto, $capacidad, $color_desc, $inicial_confirmada, $plazo_meses, $prospecto_direccion, $ciudad, $nombre, $apellidos);

        while ($stmt->fetch()){

           $printer->text("ASESOR: " . strtoupper($nombre) ." ". strtoupper($apellidos). "\n");
           $printer->text("NOMBRE COMPLETO: " . strtoupper($prospecto_nombre) ." ". strtoupper($prospecto_apellidos). "\n");
           $printer->text("CEDULA: " . $prospecto_cedula. "\n");
           $printer->text("REFERENCIA EQUIPO: " . strtoupper($marca_producto)." ".strtoupper($nombre_modelo)." ".strtoupper($capacidad). "\n");
           $printer->text("INICIAL: ". number_format($inicial_confirmada, 0, ".", "."). "\n");
           $printer->text("PLAZO: " . $plazo_meses. "\n");
           $printer->text("COLOR: " . $color_desc. "\n");
           $printer->text("CORREO ELECTRONICO: " . strtoupper($prospecto_email). "\n");
           $printer->text("DIRECCION: " . strtoupper($prospecto_direccion). "\n");
           $printer->text("BARRIO: " . "\n");
           $printer->text("CIUDAD: " . strtoupper($ciudad). "\n");
           $printer->text("TELEFONO: " . $prospecto_numero_contacto. "\n");
           $printer->text("TELEFONO 2: " . "\n");
           

        }

        $printer->feed(1);
        $printer->text("EL CLIENTE PAGA CON 350.000, SOLICITA DEVUELTA" . "\n");
        $printer->feed(1);
        $printer->text("_______________________________________________" . "\n");
        $printer->text("IMEI: ". "864926058732166". "\n");
        $printer->text("_______________________________________________" . "\n");

        $stmt->close();
        $conn->close();


        $printer->cut();

        $printer->close();

    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

}
