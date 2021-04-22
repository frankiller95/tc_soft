<?php

session_name("tc_shop");
session_start();

// No mostrar los errores de PHP
//error_reporting(0);

include('includes/connection.php');
include('includes/functions.php');


$errorMsg = '';

//valida si se recibe el email del login
if (isset($_POST['email'])) {
	
	// captura los datos	
	$usuario_email = $_POST['email'];
	$usuario_pass = $_POST['password'];

	//contamos la cantidad de correos registrados con este email en la tabla usuarios
	$validar_email = execute_scalar("SELECT COUNT(*) FROM usuarios WHERE email = '$usuario_email'");

	// si el email es mayor a 0 significa que ya esta registrado
	if($validar_email > 0){

			//traemos los datos del usuario con el correo validado
			$conn = new mysqli($host, $user, $pass, $db);
			$stmt = $conn->prepare("select id, email, password, del from usuarios where email = ?");
			$stmt->bind_param("s" ,$usuario_email);
			$stmt->execute(); 
			$stmt->bind_result($r_id,$r_email,$r_pass,$r_del);

			while ($stmt->fetch()) {

				$password = $r_pass;
				$id_usuario = $r_id;   
				$estado = $r_del;

			}


			$stmt->close();
			$conn ->close();


			if (password_verify($usuario_pass, $password) && $estado == 0){ // verifica que la contraseña sea correcto y que el usuario este activado

				$errorMsg = "Inicio de sesión correcto";

				$_SESSION['id'] = $id_usuario;

			}elseif (password_verify($usuario_pass, $password) && $estado == 1) { // en caso de que el usuario este inactivo

				$errorMsg = '<span class="text-danger" style="font-weigth: 700;">UPPS!</span>'. ' '. 'Este usuario no se encuentra activado.';

			} else { // en caso de que la contraseña sea incorrecta.

				$errorMsg = '<strong>UPPS!</strong>'. ' '. 'La contraseña indicada es incorrecta para este usuario.';

			}	

	}else{

		$errorMsg = '<strong>UPPS!</strong>'. ' '. 'Este email es incorrecto o no esta registrado.';
	}

}
	


if(isset($_SESSION['id'])){

	$id_usuario = $_SESSION['id'];

	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}else{
		$page = "crear_solicitud";
	}
	
	
	include 'home.php';

}else{

	include 'login.php'; 

}


?>