<?php



function send_email($recipent, $codigo){

	$mail = new PHPMailer(true);

	try {
	    //Server settings
	    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
	    $mail->isSMTP();                                            //Send using SMTP
	    $mail->Host       = 'smtp.hostinger.co';                     //Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	    $mail->Username   = 'no-reply@franseb.com';                     //SMTP username
	    $mail->Password   = 'Franciscokld95%';                               //SMTP password
	    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; 
	    $mail->SMTPSecure = 'ssl';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	    //Recipients
	    $mail->setFrom('no-reply@franseb.com', 'TcShop');
	    $mail->addAddress($recipent);     //Add a recipient

	    //Content
	    $mail->isHTML(true);                                  //Set email format to HTML
	    $mail->Subject = 'Confirmación Tratamiento de datos';
	    $mail->Body    = 'Su codigo para autorizar el tratamiento de datos y validar en<br>
	    centrales de riesgo es:&nbsp;<b>'.$codigo.'</b>';

	    $mail->send();
	    return 1;
	} catch (Exception $e) {
	    return 0;
	}
}