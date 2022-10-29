<?php


//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if($_POST['action'] == "cargar_imagen"){

    try{

        if($_FILES['imagen_prospecto']['tmp_name'] != ''){

            $tipo_img = $_POST['tipo_img'];

            $id_confirmacion = $_POST['id_confirmacion'];

            if($id_confirmacion == 0){

                $codigo = 0;

                $id_confirmacion_max = execute_scalar("SELECT MAX(id) AS id_max FROM confirmacion_prospectos WHERE estado_confirmacion = 1 AND del = 0");
                $validando_prospecto = execute_scalar("SELECT COUNT(*) AS total FROM prospectos WHERE prospectos.id_confirmacion = $id_confirmacion_max AND prospectos.del = 0  ");



                //validando prospecto

                if($validando_prospecto == 0){

                    $id_confirmacion = $id_confirmacion_max;
                    $route = '../documents/prospects/'.$id_confirmacion.'/';
                    if(is_dir($route)){
                        rmDir_rf($route);
                    }

                }else{

                    $conn = new mysqli($host, $user, $pass, $db);
                    $stmt = $conn->prepare("INSERT INTO confirmacion_prospectos (codigo, fecha_registro, fecha_vencimiento, estado_confirmacion) VALUES (?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), b'1')");
                    $stmt->bind_param("i", $codigo);
                    $stmt-> execute();
                    if($stmt->affected_rows == 1){
                        $id_confirmacion = $stmt->insert_id;
                    }else{
                        $response = array(
                            "response" => "error",
                            "error" => $stmt->error
                        );

                        echo json_encode($response);
                        die();
                    }

                    $stmt->close();
                    $conn->close();

                }
            }

            $route = '../documents/prospects/'.$id_confirmacion.'/';
            $route1 = '../documents/prospects/'.$id_confirmacion;
            $extension = 'jpg';
            $pictureFileName = $id_confirmacion.'-'.$tipo_img.'.'.$extension
            ;
            $file = $route.$pictureFileName;

            if (!file_exists($route)) {

                mkdir($route, 0777, true);
            }

            if (is_file($route.$pictureFileName)) {
                unlink($file);
            }

            if ($_FILES['imagen_prospecto']['type'] == "image/jpeg") {

                $extension = "jpg";

            } else if ($_FILES['imagen_prospecto']['type'] == "image/png") {

                $extension = "jpg";

            } else {

                $response = array(
                    "response" => "tipo_incorrecto"
                );

                echo json_encode($response);

                die();
            }

            $filename = $id_confirmacion.'-'.$tipo_img;

            $validate_imagenes = execute_scalar("SELECT COUNT(id) AS total FROM imagenes_prospectos WHERE id_confirmacion = $id_confirmacion AND imagen_nombre_archivo = '$filename' AND del = 0");

            if($validate_imagenes != 0){

                $conn = new mysqli($host, $user, $pass, $db);
                $stmt = $conn->prepare("UPDATE imagenes_prospectos SET del = 1 WHERE id_confirmacion = ? AND imagen_nombre_archivo = ? AND del = 0");
                $stmt->bind_param("is", $id_confirmacion, $filename);
                $stmt-> execute();
                $stmt->close();
                $conn->close();

            }

            if (move_uploaded_file($_FILES['imagen_prospecto']['tmp_name'], $route . $filename . '.' . $extension)) {

                $tipo_img_name = "FRONTAL";

                if ($tipo_img == 1) {

                    $tipo_img_name = "ATRAS";
                    
                }else if($tipo_img == 2){

                    $tipo_img_name = "SELFIE";

                }

                $conn = new mysqli($host, $user, $pass, $db);
                $stmt = $conn->prepare("INSERT INTO imagenes_prospectos (id_confirmacion, imagen_nombre_archivo, tipo_img, imagen_extension, fecha_registro) VALUES (?, ?, ?, 'jpg', DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))");
                $stmt->bind_param("iss", $id_confirmacion, $filename, $tipo_img_name);
                $stmt-> execute();

                if($stmt->affected_rows == 1){

                    $tipo_img_next = $tipo_img+1;

                    $response = array(
                        "response" => "success",
                        "id_confirmacion" => $id_confirmacion,
                        "tipo_img" => $tipo_img,
                        "tipo_img_next" => $tipo_img_next,
                        "tipo_img_name" => $tipo_img_name
                    );

                }else{

                    $response = array(
                        "response" => "error"
                    );

                }

                $stmt->close();
                $conn->close();

            } else {

                $response = array(
                    "response" => "imagen_error"
                );
            }

        }else{
            $response = array(
                "response" => "error"
            );
        }

    }catch(Exception $e){

        $response = array(
            'error' => $e-> getMessage()
        );

    }

    echo json_encode($response);

}else if($_POST['action'] == "siguente_img"){

    try {

        $id_confirmacion = $_POST['id_confirmacion'];

        $tipo_imagen = $_POST['tipo_imagen'];

        $route = '../documents/prospects/'.$id_confirmacion.'/';
        $extension = 'jpg';
        $pictureFileName = $id_confirmacion.'-'.$tipo_imagen.'.'.$extension
        ;
        $file = $route.$pictureFileName;

        if (is_file($route.$pictureFileName)) {

            $tipo_imagen_next = $tipo_imagen + 1;

            $response = array(
                "response" => "success",
                "tipo_imagen_next" => $tipo_imagen_next,
                "id_confirmacion" => $id_confirmacion
            );
            
        }else{

            $response = array(
                "response" => "falta_img"
            );

        }
                
    } catch (Exception $e) {

       $response = array(
            'error' => $e-> getMessage()
        );

    }

    echo json_encode($response);


}else if($_POST['action']=="insertar_prospecto"){

    try {

        //var_dump($_POST);
        //die();
        if(!isset($_POST['dispositivo_referencia'])){

            $response = array(
                "response" => "falta_dispostivo"
            );

            echo json_encode($response);

            die();

        }

        if(!isset($_POST['ciudad_prospecto'])){

            $response = array(
                "response" => "falta_ciudad"
            );

            echo json_encode($response);

            die();
            
        }

        if(!isset($_POST['ciudad_exp'])){

            $response = array(
                "response" => "falta_ciudad_exp"
            );
            
            echo json_encode($response);

            die();

        }

        $observacion_create_prospecto = '';

        $sexo_prospecto = null;

        $dob_prospecto = null;

        //var_dump($_POST);
        //die();
        $asesor_externo = '';
        $asesor_tropa = '';
        foreach($_POST as $nombre_campo => $valor){
           $asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
           eval($asignacion);
        }

        $creacion_prospecto = 1;

        if(isset($cliente_interno)){

            $id_usuario_responsable = $asesor_externo;
            $creacion_prospecto = $cliente_interno;

        }

        if(isset($cliente_gane)){
            $id_usuario_responsable = $asesor_tropa;
            $creacion_prospecto = $cliente_gane;

        }

        if($id_usuario_responsable == ''){

            $response = array(
                "response" => "seleciona_asesor"
            );

            echo json_encode($response);

            die();

        }

        if($creacion_prospecto == 1){
            $response = array(
                "response" => "selecciona_desde"
            );

            echo json_encode($response);

            die();
        }

        //echo $contacto_prospecto;
        $contacto_prospecto = substr($contacto_prospecto, 0,3).substr($contacto_prospecto, 4,3).substr($contacto_prospecto, 8,4);
        $incial_prospecto = str_replace('.', '', $incial_prospecto);

        $cedula_prospecto = str_replace('.', '', $cedula_prospecto);



        $profile_20 = profile(20, $id_usuario);

        $validate_cedula = execute_scalar("SELECT COUNT(id) AS total FROM prospectos WHERE prospecto_cedula = '$cedula_prospecto' AND prospectos.del = 0");

        $profile_25 = profile(25, $id_usuario);

        if($profile_25 == 1){
            $id_usuario_validador = $id_usuario;
        }else{
            $id_usuario_validador = 0;
        }

        if($validate_cedula == 0){

            /*
            $query_insert = "INSERT INTO prospectos (prospecto_cedula, id_usuario_responsable, id_responsable_interno, id_confirmacion, id_plataforma, id_usuario_validador, fecha_registro, del) VALUES ($cedula_prospecto, $id_usuario_responsable, $id_usuario_responsable, $id_confirmacion, b'0', $id_usuario, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), b'0')";
            echo $query_insert;
            die();
            */

            $conn = new mysqli($host, $user, $pass, $db);
            $stmt = $conn->prepare("INSERT INTO prospectos (prospecto_cedula, id_usuario_responsable, id_responsable_interno, id_confirmacion, id_plataforma, id_usuario_validador, id_prospecto_creacion, fecha_registro, del) VALUES (?, ?, ?, ?, b'0', ?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR), b'0')");
            $stmt->bind_param("iiiiii", $cedula_prospecto, $id_usuario_responsable, $id_usuario_responsable, $id_confirmacion, $id_usuario_validador, $creacion_prospecto);
            $stmt-> execute();

            if($stmt->affected_rows == 1){

                $id_prospecto = $stmt->insert_id;

                $query = "INSERT INTO prospecto_detalles (id_prospecto, prospecto_nombre, prospecto_apellidos, prospecto_numero_contacto, contacto_w, prospecto_email, prospecto_sexo, prospecto_dob, prospecto_direccion, ciudad_id, fecha_exp, id_ciudad_exp, id_referencia, inicial_referencia, observacion_prospecto, fecha_registro) VALUES ($id_prospecto, '$nombre_prospecto', '$apellidos_prospecto', '$contacto_prospecto', '$contacto_prospecto', '$email_prospecto', '$sexo_prospecto', '$dob_prospecto', '$direccion_prospecto', $ciudad_prospecto, '$fecha_exp', $ciudad_exp, $dispositivo_referencia, $incial_prospecto, '$observacion_create_prospecto', DATE_ADD(UTC_TIMESTAMP(), INTERVAL -5 HOUR))";

                //echo $query;
                //die();

                $result = qry($query);

                if ($result) {

                    $ciudad_prospecto_nombre = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_prospecto");
                    $id_departamento_ciudad = execute_scalar("SELECT id_departamento FROM ciudades WHERE id = $ciudad_prospecto");
                    $departamento_nombre_ciudad = execute_scalar("SELECT departamento FROM departamentos WHERE id = $id_departamento_ciudad");
                    $ciudad_prospecto_expedicion = execute_scalar("SELECT ciudad FROM ciudades WHERE id = $ciudad_exp");
                    $id_departamento_exp = execute_scalar("SELECT id_departamento FROM ciudades WHERE id = $ciudad_exp");
                    $departamento_nombre_exp = execute_scalar("SELECT departamento FROM departamentos WHERE id = $id_departamento_exp");


                    $nombre_responsable = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS usuario_nombre_full FROM usuarios WHERE id = $id_usuario");
                    $creador_nombre = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS usuario_nombre_full FROM usuarios WHERE id = $id_usuario_responsable");
                    $fecha_registro = execute_scalar("SELECT DATE_FORMAT(prospectos.fecha_registro, '%m-%d-%Y ') AS fecha_registro_prospecto FROM prospectos WHERE prospectos.id = $id_prospecto");

                    $resultado_dc_prospecto = execute_scalar("SELECT prospectos.resultado_dc_prospecto FROM prospectos WHERE prospectos.id = = $id_prospecto");

                    $resultado_dc_texto = "N/A";
                    $resultado_dc_label = "info";

                    if($resultado_dc_prospecto == 1){
                        $resultado_dc_texto = "APROBADO";
                        $resultado_dc_label = "success";
                    }else if($resultado_dc_prospecto == 2){
                        $resultado_dc_texto = "RECHAZADO";
                        $resultado_dc_label = "danger";
                    }

                    if($id_usuario_validador == 0){
                        $validador_nombre = "N/A";
                    }else{
                        $validador_nombre = execute_scalar("SELECT CONCAT(usuarios.nombre, ' ', usuarios.apellidos) AS usuario_nombre_full FROM usuarios WHERE id = $id_usuario_validador");
                    }

                    $response = array(

                        "response" => "success",
                        "id_prospecto" => $id_prospecto,
                        "cedula_prospecto" => $cedula_prospecto,
                        "prospecto_nombre" => $nombre_prospecto.' '.$apellidos_prospecto,
                        "contacto_prospecto" => $contacto_prospecto,
                        "ciudad" => $ciudad_prospecto_nombre,
                        "departamento" => $departamento_nombre_ciudad,
                        "nombre_responsable" => $nombre_responsable,
                        "marca" => "N/A",
                        "profile_20" => $profile_20,
                        "id_usuario" => $id_usuario,
                        "creador_nombre" => $creador_nombre,
                        "validador_nombre" => $validador_nombre,
                        "fecha_registro" => $fecha_registro,
                        "resultado_dc_texto" => $resultado_dc_texto,
                        "resultado_dc_label" => $resultado_dc_label
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

        }else{

            $response = array(
                "response" => "cedula_repetida"
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