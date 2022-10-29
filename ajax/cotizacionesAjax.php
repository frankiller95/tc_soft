<?php

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");
session_start();

include('../includes/connection.php');
include('../includes/functions.php');


if ($_POST['action'] == "insertar_cotizacion") {

	try {

        $id_prospecto = $_POST['prospecto_coti'];
        $convenio_coti = $_POST['convenio_coti'];
        $dispositivo_coti = $_POST['dispositivo_coti'];
        $fecha_valida = $_POST['fecha_valida'];
        $descuento_coti = $_POST['descuento_coti'];
        $texto_adicional_coti = $_POST['texto_adicional_coti'];


	} catch (Exception $e) {

		$response = array(
			'error' => $e-> getMessage()
		);
		
	}

	echo json_encode($response);


}

?>