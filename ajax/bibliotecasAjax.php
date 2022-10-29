<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');

if ($_POST['action'] == "actualizar_arandela") {

    $id_arandela = 1;

    $type = $_POST['type'];

    $date = $_POST['date'];


    $conn = new mysqli($host, $user, $pass, $db);
    switch ($type) {

        case 'estudio_credito':
            //valor normal
            $estudio_credito = str_replace('.', '', $date);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `estudio_credito` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $estudio_credito, $id_arandela);
            break;

        case 'fianza':
            //porcentaje
            $fianza = str_replace('%', '', $date);
            $fianza = str_replace(',', '.', $fianza);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `fianza` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $fianza, $id_arandela);
            break;

        case 'interaccion_tecnologica':
            //valor normal
            $interaccion_tecnologica = str_replace('.', '', $date);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `interaccion_tecnologica` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $interaccion_tecnologica, $id_arandela);
            break;

        case 'beriblock':
            //valor normal
            $beriblock = str_ireplace('.', '', $date);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `beriblock` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $beriblock, $id_arandela);
            break;

        case 'seguro_pantalla':
            //porcentaje
            $seguro_pantalla = str_replace('%', '', $date);
            $seguro_pantalla = str_replace(',', '.', $seguro_pantalla);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `seguro_pantalla` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $seguro_pantalla, $id_arandela);
            break;

        case 'domicilio':
            //valor normal
            $domicilio = str_replace('.', '', $date);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `domicilio` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $domicilio, $id_arandela);
            break;

        case 'iva_arandelas':
            //porcentaje
            $iva_arandelas = str_replace('%', '', $date);
            $iva_arandelas = str_replace(',', '.', $iva_arandelas);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `iva_arandelas` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $iva_arandelas, $id_arandela);
            break;

        case 'tasa_interes_usura':
            //porcentaje
            $tasa_interes_usura = str_replace('%', '', $date);
            $tasa_interes_usura = str_replace(',', '.', $tasa_interes_usura);
            $stmt = $conn->prepare("UPDATE `arandelas_creditos` SET `tasa_interes_usura` = ? WHERE `arandelas_creditos`.`id` = ?");
            $stmt->bind_param("di", $tasa_interes_usura, $id_arandela);
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
            'date' => $date
        );
    } else {
        $response = array(
            'response' => 'error',
            "error" => $stmt->error
        );
    }
    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else if ($_POST['action'] == "insertar_departamento") {

    try {

        $nuevo_departamento = $_POST['nuevo_departamento'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO departamentos (departamento) VALUES (?)");
        $stmt->bind_param("s", $nuevo_departamento);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_departamento" => $stmt->insert_id,
                "departamento" => $nuevo_departamento
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
} else if ($_POST['action'] == "insertar_ciudad") {

    try {

        $departamento_ciudad = $_POST['departamento_ciudad'];
        $nueva_ciudad = $_POST['nueva_ciudad'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO ciudades (ciudad, id_departamento) VALUES (?, ?)");
        $stmt->bind_param("si", $nueva_ciudad, $departamento_ciudad);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $departamento_nombre = execute_scalar("SELECT departamento FROM departamentos WHERE id = $departamento_ciudad");

            $response = array(
                "response" => "success",
                "id_ciudad" => $stmt->insert_id,
                "ciudad" => $nueva_ciudad,
                "departamento" => $departamento_nombre
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
} else if ($_POST['action'] == "select_departamento") {

    try {

        $id_departamento = $_POST['id_departamento'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT id AS id_departamento, departamento FROM departamentos WHERE id = ?");
        $stmt->bind_param("i", $id_departamento);
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
} else if ($_POST['action'] == "select_ciudad") {

    try {

        $id_ciudad = $_POST['id_ciudad'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT id AS id_ciudad, ciudad, id_departamento FROM ciudades WHERE id = ?");
        $stmt->bind_param("i", $id_ciudad);
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
} else if ($_POST['action'] == "editar_departamento") {

    try {

        $nuevo_departamento = $_POST['nuevo_departamento'];
        $id_departamento = $_POST['id_departamento'];

        qry("DELETE FROM departamentos WHERE id = $id_departamento");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO departamentos (id, departamento) VALUES (?, ?)");
        $stmt->bind_param("is", $id_departamento, $nuevo_departamento);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_departamento" => $id_departamento,
                "departamento" => $nuevo_departamento
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
} else if ($_POST['action'] == "editar_ciudad") {

    try {

        $departamento_ciudad = $_POST['departamento_ciudad'];
        $nueva_ciudad = $_POST['nueva_ciudad'];
        $id_ciudad = $_POST['id_ciudad'];

        qry("DELETE FROM ciudades WHERE id = $id_ciudad");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO ciudades (id, ciudad, id_departamento) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $id_ciudad, $nueva_ciudad, $departamento_ciudad);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $departamento_nombre = execute_scalar("SELECT departamento FROM departamentos WHERE id = $departamento_ciudad");

            $response = array(
                "response" => "success",
                "id_ciudad" => $id_ciudad,
                "ciudad" => $nueva_ciudad,
                "departamento" => $departamento_nombre
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
} else if ($_POST['action'] == "eliminar_departamento") {

    $id_departamento = $_POST['id_departamento'];

    $conn = new mysqli($host, $user, $pass, $db);
    $stmt = $conn->prepare("UPDATE departamentos SET del = b'1' WHERE id = ?");
    $stmt->bind_param("i", $id_departamento);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {

        $response = array(
            "response" => "success",
            "id_departamento" => $id_departamento
        );
    } else {

        $response = array(
            "response" => "error",
            "error" => $stmt->error
        );
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else if ($_POST['action'] == "eliminar_ciudad") {

    $id_ciudad = $_POST['id_ciudad'];

    $conn = new mysqli($host, $user, $pass, $db);
    $stmt = $conn->prepare("UPDATE ciudades SET del = b'1' WHERE id = ?");
    $stmt->bind_param("i", $id_ciudad);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {

        $response = array(
            "response" => "success",
            "id_ciudad" => $id_ciudad
        );
    } else {

        $response = array(
            "response" => "error",
            "error" => $stmt->error
        );
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else if ($_POST['action'] == "insertar_color") {

    try {

        $nuevo_color = $_POST['nuevo_color'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO colores_productos (color_desc) VALUES (?)");
        $stmt->bind_param("s", $nuevo_color);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_color" => $stmt->insert_id,
                "color_desc" => $nuevo_color
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
} else if ($_POST['action'] == "select_color") {

    try {

        $id_color = $_POST['id_color'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT id AS id_color, color_desc FROM colores_productos WHERE id = ?");
        $stmt->bind_param("i", $id_color);
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
} else if ($_POST['action'] == "editar_color") {

    try {

        $nuevo_color = $_POST['nuevo_color'];
        $id_color = $_POST['id_color'];

        qry("DELETE FROM colores_productos WHERE id = $id_color");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO colores_productos (id, color_desc) VALUES (?, ?)");
        $stmt->bind_param("is", $id_color, $nuevo_color);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_color" => $id_color,
                "color_desc" => $nuevo_color
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
} else if ($_POST['action'] == "eliminar_color") {

    try {

        $id_color = $_POST['id_color'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("UPDATE colores_productos SET del = b'1' WHERE id = ?");
        $stmt->bind_param("i", $id_color);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $response = array(
                "response" => "success",
                "id_color" => $id_color
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

}else if($_POST['action'] == "insertar_punto_gane"){

    try {

        $codigo_punto = $_POST['codigo_punto'];
        $nombre_punto = $_POST['nombre_punto'];
        $direccion_punto = $_POST['direccion_punto'];
        $barrio_punto = $_POST['barrio_punto'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO puntos_gane (COD, AGENCIA, DIRECCION, BARRIO, fecha_registro) VALUES (?, ?, ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
        $stmt->bind_param("ssss", $codigo_punto, $nombre_punto, $direccion_punto, $barrio_punto);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $id_punto_gane = $stmt->insert_id;

            $response = array(
                "response" => "success",
                "id_punto_gane" => $id_punto_gane,
                "codigo_punto" => $codigo_punto,
                "nombre_punto" => $nombre_punto,
                "direccion_punto" => $direccion_punto,
                "barrio_punto" => $barrio_punto
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

}else if($_POST['action'] == "select_punto_gane"){

    try {

        $id_punto_gane = $_POST['id_punto_gane'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("SELECT ID AS id_punto_gane, COD, AGENCIA, DIRECCION, BARRIO FROM puntos_gane WHERE ID = ?");
        $stmt->bind_param("i", $id_punto_gane);
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

}else if($_POST['action'] == "editar_punto_gane"){

    try {

        $codigo_punto = $_POST['codigo_punto'];
        $nombre_punto = $_POST['nombre_punto'];
        $direccion_punto = $_POST['direccion_punto'];
        $barrio_punto = $_POST['barrio_punto'];
        $id_punto_gane = $_POST['id_punto_gane'];

        qry("DELETE FROM puntos_gane WHERE id = $id_punto_gane");

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("INSERT INTO puntos_gane (ID, COD, AGENCIA, DIRECCION, BARRIO) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_punto_gane, $codigo_punto, $nombre_punto, $direccion_punto, $barrio_punto);
        $stmt->execute();

        if ($stmt->affected_rows == 1) {

            $total_digitadores = execute_scalar("SELECT COUNT(usuarios_puntos_gane.id) AS digitadores FROM usuarios_puntos_gane LEFT JOIN usuarios ON usuarios_puntos_gane.id_usuario = usuarios.id WHERE usuarios_puntos_gane.id_punto_gane = $id_punto_gane AND usuarios_puntos_gane.del = 0 AND usuarios.del = 0");

            $response = array(
                "response" => "success",
                "id_punto_gane" => $id_punto_gane,
                "codigo_punto" => $codigo_punto,
                "nombre_punto" => $nombre_punto,
                "direccion_punto" => $direccion_punto,
                "barrio_punto" => $barrio_punto,
                "total_digitadores" => $total_digitadores
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

}else if($_POST['action'] == "eliminar_punto_gane"){
    try {

        $id_punto_gane = $_POST['id_punto_gane'];
        $total_digitadores = $_POST['total_digitadores'];

        $conn = new mysqli($host, $user, $pass, $db);
        $stmt = $conn->prepare("UPDATE puntos_gane SET del = b'1' WHERE id = ?");
        $stmt->bind_param("i", $id_punto_gane);
        $stmt->execute();

        if($stmt->affected_rows == 1){

            if($total_digitadores != 0){

                $query = "SELECT id_usuario, usuarios.del FROM usuarios_puntos_gane LEFT JOIN usuarios ON usuarios_puntos_gane.id_usuario = usuarios.id WHERE id_punto_gane = $id_punto_gane AND del = 0";
                $result = qry($query);
                $x = 1;
                while($row = mysqli_fetch_array($result)){

                    $id_usuario = $row['id_usuario'];
                    $del = $row1['del'];

                    if($del == 0){

                        $stmt = $conn->prepare("UPDATE usuarios SET del = b'1' WHERE id = ?");
                        $stmt->bind_param("i", $id_usuario);
                        $stmt->execute();
                        $stmt->close();
                        $conn->close();
                        
                    }

                    $x++;

                }

                echo $x.'-';
                echo $total_digitadores;

                if($x == $total_digitadores){

                    $stmt = $conn->prepare("UPDATE usuarios_puntos_gane SET del = b'1' WHERE id_punto_gane = ? AND del = 0");
                    $stmt->bind_param("i", $id_punto_gane);
                    $stmt->execute();
                    $stmt->close();
                    $conn->close();

                    $response = array(
                        "response" => "success",
                        "id_punto_gane" => $id_punto_gane
                    );

                }else{

                    $response = array(
                        "response" => "error"
                    );

                }

            }else{

                $response = array(
                    "response" => "success",
                    "id_punto_gane" => $id_punto_gane
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

    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);

}else if($_POST['action'] == "change_codigo"){
    try {

        $valor = $_POST['valor'];

        $validate_valor = execute_scalar("SELECT envios_sms_gane FROM configuraciones WHERE id = 1");

        if($validate_valor != $valor){

            $conn = new mysqli($host, $user, $pass, $db);
            $stmt = $conn->prepare("UPDATE configuraciones SET envios_sms_gane = ? WHERE id = 1");
            $stmt->bind_param("i", $valor);
            $stmt->execute();
            if($stmt->affected_rows == 1){
                $response = array(
                    "response" => "success"
                );
            }else{
                $response = array(
                    "response" => "error"
                );
            }
        }else{
            $response = array(
                "response" => "success"
            );
        }
        

    } catch (Exception $e) {
        $response = array(
            'error' => $e->getMessage()
        );
    }

    echo json_encode($response);
}
