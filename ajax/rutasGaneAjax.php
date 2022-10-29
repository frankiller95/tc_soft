<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action'] == "insertar_ruta") {

    try {

        $id_usuario = $_POST['ruta_responsable'];
        $id_punto_gane = $_POST['punto_gane_ruta'];
        $fecha_visita = $_POST['fecha_visita'];
        $fecha_visita = substr($fecha_visita, 6, 4) . '-' . substr($fecha_visita, 0, 2) . '-' . substr($fecha_visita, 3, 2);

        $validate_ruta_creada = execute_scalar("SELECT COUNT(id) AS total FROM rutas_gane WHERE id_punto_gane = $id_punto_gane AND del = 0");

        if ($validate_ruta_creada == 0) {

            $validate_capacitado = execute_scalar("SELECT confirmado_capacitacion FROM puntos_gane WHERE ID = $id_punto_gane");

            if ($validate_capacitado == 0) {

                $conn = new mysqli($host, $user, $pass, $db);
                $stmt = $conn->prepare("INSERT INTO rutas_gane (id_usuario, id_punto_gane, fecha_visita) VALUES (?, ?, ?)");
                $stmt->bind_param("iis", $id_usuario, $id_punto_gane, $fecha_visita);
                $stmt->execute();

                if ($stmt->affected_rows == 1) {

                    $id_ruta = $stmt->insert_id;

                    $codigo_punto = execute_scalar("SELECT COD FROM puntos_gane WHERE ID = $id_punto_gane");
                    $agencia = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE ID = $id_punto_gane");

                    $nombre_responsable = execute_scalar("SELECT nombre FROM usuarios WHERE id = $id_usuario");
                    $apellidos_responsable = execute_scalar("SELECT apellidos FROM usuarios WHERE id = $id_usuario");

                    $response = array(
                        "response" => "success",
                        "id_ruta" => $id_ruta,
                        "punto_gane" => $codigo_punto . '-' . $agencia,
                        "responsable" => $nombre_responsable . ' ' . $apellidos_responsable,
                        "fecha_visita" => substr($fecha_visita, 5, 2) . '-' . substr($fecha_visita, 8, 2) . '-' . substr($fecha_visita, 0, 4)
                    );
                } else {

                    $response = array(
                        "response" => "error",
                        "error" => $stmt->error
                    );
                }

                $stmt->close();
                $conn->close();
            } else {

                $response = array(
                    "response" => "capacitado"
                );
            }
        } else {

            $response = array(
                "response" => "existe"
            );
        }
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "select_ruta_info") {

    try {

        $id_ruta = $_POST['id_ruta'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT id AS id_ruta, id_usuario, id_punto_gane, DATE_FORMAT(rutas_gane.fecha_visita, '%m-%d-%Y') AS fecha_programada FROM rutas_gane WHERE id = ?");
        $stmt->bind_param("i", $id_ruta);
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

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "editar_ruta") {

    try {

        $id_usuario = $_POST['ruta_responsable'];
        $id_punto_gane = $_POST['punto_gane_ruta'];
        $fecha_visita = $_POST['fecha_visita'];
        $fecha_visita = substr($fecha_visita, 6, 4) . '-' . substr($fecha_visita, 0, 2) . '-' . substr($fecha_visita, 3, 2);
        $id_ruta = $_POST['id_ruta'];

        $validate_ruta_creada = execute_scalar("SELECT COUNT(id) AS total FROM rutas_gane WHERE id_punto_gane = $id_punto_gane AND id <> $id_ruta AND del = 0");

        if ($validate_ruta_creada == 0) {

            $validate_capacitado = execute_scalar("SELECT confirmado_capacitacion FROM puntos_gane WHERE ID = $id_punto_gane");

            if ($validate_capacitado == 0) {

                qry("DELETE FROM rutas_gane WHERE id = $id_ruta");

                $conn = new mysqli($host, $user, $pass, $db);
                $stmt = $conn->prepare("INSERT INTO rutas_gane (id, id_usuario, id_punto_gane, fecha_visita) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiis", $id_ruta, $id_usuario, $id_punto_gane, $fecha_visita);
                $stmt->execute();

                if ($stmt->affected_rows == 1) {

                    $id_ruta = $stmt->insert_id;

                    $codigo_punto = execute_scalar("SELECT COD FROM puntos_gane WHERE ID = $id_punto_gane");
                    $agencia = execute_scalar("SELECT AGENCIA FROM puntos_gane WHERE ID = $id_punto_gane");

                    $nombre_responsable = execute_scalar("SELECT nombre FROM usuarios WHERE id = $id_usuario");
                    $apellidos_responsable = execute_scalar("SELECT apellidos FROM usuarios WHERE id = $id_usuario");

                    $validate_comentarios = execute_scalar("SELECT COUNT(id) AS total FROM comentarios_rutas_gane WHERE id_ruta_gane = $id_ruta AND del = 0");

                    $response = array(
                        "response" => "success",
                        "id_ruta" => $id_ruta,
                        "punto_gane" => $codigo_punto . '-' . $agencia,
                        "responsable" => $nombre_responsable . ' ' . $apellidos_responsable,
                        "fecha_visita" => substr($fecha_visita, 5, 2) . '-' . substr($fecha_visita, 8, 2) . '-' . substr($fecha_visita, 0, 4),
                        "validate_comentarios" => $validate_comentarios
                    );

                    if ($validate_comentarios != 0) {

                        $comentarios_array = array();
                        $query = "SELECT comentarios.rutas_gane.id AS id_comentario, comentario_texto, usuarios.nombre, usuarios.apellidos, comentarios.rutas_gane.fecha_registro FROM comentarios_rutas_gane LEFT JOIN usuarios ON comentarios_rutas_gane.realizado_por = usuarios.id WHERE id_ruta_gane = $id_ruta AND comentarios.rutas_gane.del = 0 ORDER BY comentarios.rutas_gane.fecha_registro ASC";
                        $result = qry($query);
                        while ($row = mysqli_fetch_array($result)) {

                            $id_comentario = $row['id_comentario'];
                            $comentario_texto = $row['comentario_texto'];
                            $fecha_registro = $row['fecha_registro'];
                            $realizado_por = $row1['nombre'].' '.$row1['apellidos'];

                            $new_array = array("id_comentario" => $id_comentario, "comentario_texto" => $comentario_texto, "realizado_por" => $$realizado_por, "fecha_registro" => $fecha_registro);
                            array_push($comentarios_array, $new_array);
                        }

                        array_push($response, $comentarios_array);
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

                $response = array(
                    "response" => "capacitado"
                );
            }
        } else {

            $response = array(
                "response" => "existe"
            );
        }
    } catch (Exception $e) {

        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
} else if ($_POST['action'] == "select_evidencias") {

    try {

        $id_ruta = $_POST['id_ruta'];

        $validate_evidencias = execute_scalar("SELECT COUNT(id) AS total FROM evidencias_rutas_gane WHERE id_ruta_gane = $id_ruta AND del = 0");

        if ($validate_evidencias <> 0) {
            $evidencias_ruta_array = array();
            $query = "SELECT  img_nombre_archivo, img_nombre_personalizado, img_ext, usuarios.nombre, usuarios.apellidos FROM evidencias_rutas_gane LEFT JOIN usuarios ON evidencias_rutas_gane.cargado_por = usuarios.id WHERE id_ruta_gane = $id_ruta AND evidencias_rutas_gane.del = 0";

            $result = qry($query);
            while ($row = mysqli_fetch_array($result)) {

                $img_nombre_archivo = $row['img_nombre_archivo'];
                $img_nombre_personalizado = $row['img_nombre_personalizado'];
                $img_ext = $row['img_ext'];
                $nombre_responsable = $row['nombre'] . ' ' . $row['apellidos'];
                $new_array = array("id_ruta_gane" => $id_ruta, "img_nombre_archivo" => $img_nombre_archivo, "img_nombre_personalizado" => $img_nombre_personalizado, "ext" => $img_ext, "usuario_responsable" => $nombre_responsable);
                //var_dump($new_array);
                array_push($evidencias_ruta_array, $new_array);
            }
            //die();

            $response = array(
                "response" => "success"
            );

            array_push($response, $evidencias_ruta_array);
        } else {

            $response = array(
                "response" => "sin_evidencias"
            );
        }
    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

} else if ($_POST['action'] == "new_comentario") {

    try {

        $id_ruta = $_POST['id_ruta'];
        $add_comentario = $_POST['add_comentario'];
        $id_usuario = $_POST['id_usuario'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO comentarios_rutas_gane (id_ruta_gane, comentario_texto, realizado_por, fecha_registro) VALUES (?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
        $stmt->bind_param("isi", $id_ruta, $add_comentario, $id_usuario);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_ruta" => $id_ruta
            );

            $comentarios_array = array();
            $query = "SELECT id AS id_comentario, comentario_texto, fecha_registro FROM comentarios_rutas_gane WHERE id_ruta_gane = $id_ruta AND del = 0 ORDER BY fecha_registro ASC";
            $result = qry($query);
            while ($row = mysqli_fetch_array($result)) {

                $id_comentario = $row['id_comentario'];
                $comentario_texto = $row['comentario_texto'];
                $fecha_registro = $row['fecha_registro'];

                $new_array = array("id_comentario" => $id_comentario, "comentario_texto" => $comentario_texto, "fecha_registro" => $fecha_registro);
                array_push($comentarios_array, $new_array);

            }

            array_push($response, $comentarios_array);

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
}
