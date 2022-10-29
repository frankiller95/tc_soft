<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime", "3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime", "3600");

session_name("tc_shop");
session_start();

date_default_timezone_set('America/Bogota');

include('../includes/connection.php');
include('../includes/functions.php');


    if($_POST['action'] == "show_bandeja"){
        
        try {
            
            $id_usuario = $_POST['id_usuario'];
            $validate_validador = execute_scalar("SELECT COUNT(*) FROM perfiles_usuarios WHERE id_usuario = '$id_usuario' AND id_permiso = 43 AND del = 0");

            $fecha_actual = date('d-m-Y H:i:s', time());
            //$fecha_actual = '2022-04-26';

            if($validate_validador == 1){

                /*
                $query = "SELECT prospectos_pendiente_llamar.id_prospecto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos_pendiente_llamar.fecha_hora_llamada, prospectos_pendiente_llamar.vencida, prospectos_pendiente_llamar.id_estado_recordatorio, estados_pendiente_llamar.estado_mostrar FROM prospectos_pendiente_llamar LEFT JOIN prospectos ON prospectos_pendiente_llamar.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_pendiente_llamar ON prospectos_pendiente_llamar.id_estado_recordatorio = estados_pendiente_llamar.id WHERE prospectos.id_estado_prospecto = 7 AND prospectos_pendiente_llamar.fecha_hora_llamada BETWEEN '$fecha_actual 00:00:00' AND '$fecha_actual 23:59:59'
                ORDER BY fecha_hora_llamada ASC";

                $query2 = "SELECT prospectos_pendiente_llamar.id_prospecto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos_pendiente_llamar.fecha_hora_llamada, prospectos_pendiente_llamar.vencida, prospectos_pendiente_llamar.id_estado_recordatorio, estados_pendiente_llamar.estado_mostrar FROM prospectos_pendiente_llamar LEFT JOIN prospectos ON prospectos_pendiente_llamar.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_pendiente_llamar ON prospectos_pendiente_llamar. id_estado_recordatorio = estados_pendiente_llamar.id WHERE prospectos.id_responsable_interno = $id_usuario AND prospectos.id_estado_prospecto = 7 AND prospectos.del = 0 AND prospectos_pendiente_llamar.fecha_hora_llamada NOT BETWEEN '$fecha_actual 00:00:00' AND '$fecha_actual 23:59:59' ORDER BY fecha_hora_llamada ASC";
                */

                $query = "SELECT prospectos_pendiente_llamar.id_prospecto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos_pendiente_llamar.fecha_hora_llamada, prospectos_pendiente_llamar.vencida, prospectos_pendiente_llamar.id_estado_recordatorio, estados_pendiente_llamar.estado_mostrar, usuarios.nombre, usuarios.apellidos, prospectos.id_responsable_interno FROM prospectos_pendiente_llamar LEFT JOIN prospectos ON prospectos_pendiente_llamar.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_pendiente_llamar ON prospectos_pendiente_llamar. id_estado_recordatorio = estados_pendiente_llamar.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id_estado_prospecto = 7 AND prospectos.del = 0 ORDER BY fecha_hora_llamada ASC";

            }else{

                $query = "SELECT prospectos_pendiente_llamar.id_prospecto, prospecto_detalles.prospecto_nombre, prospecto_detalles.prospecto_apellidos, prospectos_pendiente_llamar.fecha_hora_llamada, prospectos_pendiente_llamar.vencida, prospectos_pendiente_llamar.id_estado_recordatorio, estados_pendiente_llamar.estado_mostrar, usuarios.nombre, usuarios.apellidos, prospectos.id_responsable_interno FROM prospectos_pendiente_llamar LEFT JOIN prospectos ON prospectos_pendiente_llamar.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_pendiente_llamar ON prospectos_pendiente_llamar. id_estado_recordatorio = estados_pendiente_llamar.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id_responsable_interno = $id_usuario AND prospectos.id_estado_prospecto = 7 AND prospectos.del = 0 ORDER BY fecha_hora_llamada ASC";
            }

            $result = qry($query);
            $pendientes_llamarArray = array();
            $vencida = 0;
            $prospecto_validador = 0;
            while($rows = mysqli_fetch_array($result)){
                $id_prospecto = $rows['id_prospecto'];
                $prospecto_nombre = $rows['prospecto_nombre'];
                $prospecto_apellidos = $rows['prospecto_apellidos'];
                $fecha_hora_llamada = $rows['fecha_hora_llamada'];
                //$vencida = $rows['vencida'];
                $id_estado_recordatorio = $rows['id_estado_recordatorio'];
                $estado_mostrar = $rows['estado_mostrar'];
                $nombre_usuario = $rows['nombre'].' '.$rows['apellidos'];
                $id_responsable_interno = $rows['id_responsable_interno'];
                
                $fecha_hora_llamada = date("d-m-Y H:i:s", strtotime($fecha_hora_llamada));

                if($fecha_actual > $fecha_hora_llamada){
                    $vencida = 1;
                }

                if($id_responsable_interno == $id_usuario && $validate_validador == 1){
                    $prospecto_validador = 1;
                }
            
                $new_array = array("id_prospecto" => $id_prospecto, "prospecto_nombre" => $prospecto_nombre, "prospecto_apellidos" => $prospecto_apellidos, "fecha_hora_llamada" => $fecha_hora_llamada, "vencida" => $vencida, "id_estado_recordatorio" => $id_estado_recordatorio, "estado_mostrar" => $estado_mostrar, "nombre_usuario" => $nombre_usuario, "prospecto_validador" => $prospecto_validador);
                array_push($pendientes_llamarArray, $new_array);
                $vencida = 0;
                $prospecto_validador = 0;
            }

            
            $response = array("response" => "success", "pendientes_llamarArray" => $pendientes_llamarArray, "validate_validador" => $validate_validador, "fecha_actual" => $fecha_actual);


        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage()
            );
        }

        echo json_encode($response);

    }else if($_POST['action'] == "select_alertas_count"){

        try {
            
            $id_usuario = $_POST['id_usuario'];
            $validate_validador = execute_scalar("SELECT COUNT(*) FROM perfiles_usuarios WHERE id_usuario = '$id_usuario' AND id_permiso = 43 AND del = 0");

            $total_records = 0;

            if($validate_validador == 1){

                $total_records = execute_scalar("SELECT COUNT(prospectos_pendiente_llamar.id) AS total FROM prospectos_pendiente_llamar LEFT JOIN prospectos ON prospectos_pendiente_llamar.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_pendiente_llamar ON prospectos_pendiente_llamar. id_estado_recordatorio = estados_pendiente_llamar.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id_estado_prospecto = 7 AND prospectos.del = 0");

            }else{

                $total_records = execute_scalar("SELECT COUNT(prospectos_pendiente_llamar.id) AS total FROM prospectos_pendiente_llamar LEFT JOIN prospectos ON prospectos_pendiente_llamar.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN estados_pendiente_llamar ON prospectos_pendiente_llamar. id_estado_recordatorio = estados_pendiente_llamar.id LEFT JOIN usuarios ON prospectos.id_responsable_interno = usuarios.id WHERE prospectos.id_responsable_interno = ".$id_usuario." AND prospectos.id_estado_prospecto = 7 AND prospectos.del = 0");

            }

           
            $response = array("response" => "success", "total_recordatorios" => $total_records);


        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage()
            );
        }

        echo json_encode($response);
    }
