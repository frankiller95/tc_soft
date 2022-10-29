<?php

//============================================================+
// File name   : example_014.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 014 for TCPDF class
//               Javascript Form and user rights (only works on Adobe Acrobat)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+


$assets_path = '../assets/class/tcpdf/examples';
//echo dirname(__FILE__).$assets_path.'\tcpdf_include.php';
//die();
// Include the main TCPDF library (search for installation path).
require_once('../assets/class/tcpdf/tcpdf.php');

use Luecano\NumeroALetras\NumeroALetras;

//echo $_SERVER['DOCUMENT_ROOT'] . 'output.pdf';
//die();

$anual = 25;

$equivalente = 1.88;

$query = "SELECT CONCAT(prospecto_detalles.prospecto_apellidos, ' ', prospecto_detalles.prospecto_nombre) AS deudor, prospectos.prospecto_cedula, CONCAT(ciudades.ciudad, ', ', departamentos.departamento) AS expedicion_c, marcas.marca_producto, CONCAT(modelos.nombre_modelo, ' ', capacidades_telefonos.capacidad) AS modelo, productos_stock.imei_1, solicitudes.precio_producto, terminos_prestamos.numero_meses, porcentajes_iniciales.porcentaje, frecuencias_pagos.frecuencia, prospecto_detalles.prospecto_direccion, prospecto_detalles.prospecto_numero_contacto FROM `solicitudes` LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN modelos ON productos.id_modelo = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON modelos.id_capacidad = capacidades_telefonos.id LEFT JOIN terminos_prestamos ON solicitudes.id_terminos_prestamo = terminos_prestamos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN frecuencias_pagos ON solicitudes.id_frecuencia_pago = frecuencias_pagos.id WHERE solicitudes.id = $id_solicitud";

$result = qry($query);
while ($row = mysqli_fetch_array($result)) {

	$deudor = $row['deudor'];
	$prospecto_cedula = $row['prospecto_cedula'];
	$expedicion_c = $row['expedicion_c'];
	$marca_producto = $row['marca_producto'];
	$modelo = $row['modelo'];
	$imei_1 = $row['imei_1'];
	$precio_producto = $row['precio_producto'];
	$numero_meses = $row['numero_meses'];
	$porcentaje = $row['porcentaje'];
	$frecuencia = $row['frecuencia'];
	$prospecto_direccion = $row['prospecto_direccion'];
	$prospecto_numero_contacto = $row['prospecto_numero_contacto'];
	$inicial = ($porcentaje*$precio_producto)/100;
	$ciudad = "CALI";

    $numero_cuotas = '';

    $formatter = new NumeroALetras();

    $frecuencia_l = '';

    if($frecuencia == "QINCENAL"){

        $numero_meses = $numero_meses * 2;

    }else if($frecuencia == "SEMANAL"){

        $numero_meses = $numero_meses * 4;
               
    }

    $numero_cuotas = $formatter->toMoney($numero_meses);

    $frecuencia_l = $frecuencia.'ES';

    $porcentaje = '';

    if($numero_meses <= 4){

         $porcentaje = 15;   

    }else if($numero_meses > 4 && $numero_meses >= 6){

        $porcentaje = 20;

    }else if($numero_meses > 6 && $numero_meses > 8){

        $porcentaje = 25;

    }

    $porcentaje_iva = 19;

    $valor_intereses = $precio_producto*$porcentaje / 100;

    $valor_iva = $precio_producto*$porcentaje_iva / 100;

    $valor_declarado = $precio_producto + $valor_intereses + $valor_iva;

    $fecha_firmado = date('d-m-Y', time());

		$array_fecha = explode('-', $fecha_firmado);

		$dia = $array_fecha['0'];

		$mes = $array_fecha['1'];

		$ano = $array_fecha['2'];

    	$credito_no = $array_fecha['2'].$array_fecha['1'].$array_fecha['0'];

    $credito_no = execute_scalar("SELECT credito_numero FROM solicitudes_creditos_numeros WHERE id_solicitud = $id_solicitud");
		
			$route = '../documents/solicitudes/'.$id_solicitud.'/';
			$route1 = '../documents/solicitudes/'.$id_solicitud;

			$extension = 'pdf';
			$contrato1 = 'document1.'.$extension;
			$pagare = 'document2.'.$extension;
			$contrato2 = 'document3.'.$extension;
			$minuta = 'document4.'.$extension;
			$file1 = $route.$contrato1;
			$file2 = $route.$pagare;
			$file3 = $route.$contrato2;
			$file4 = $route.$minuta;

}

//echo $file2;

ob_start();

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Javascript Form and user rights (only works on Adobe Acrobat)
 * @author Nicola Asuni
 * @since 2008-03-04
 */
//echo dirname(__FILE__);


// create new PDF document
$pdf = new TCPDF('P','mm','A4', true, 'UTF-8', false);
/*
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Contracto TCSHOP');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 014', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
*/
// set some language-dependent strings (optional)
/*
if (@file_exists(dirname(__FILE__).$assets_path.'\lang\spa.php')) {
	require_once(dirname(__FILE__).$assets_path.'\lang\spa.php');
	$pdf->setLanguageArray($l);
}
*/
if (@file_exists($assets_path.'/lang/spa.php')) {
	require_once($assets_path.'/lang/spa.php');
	$pdf->setLanguageArray($l);
}
// ---------------------------------------------------------

// IMPORTANT: disable font subsetting to allow users editing the document
$pdf->setFontSubsetting(false);

// set font
$pdf->SetFont('helvetica', '', 10, '', false);

$pdf->Rect(5, 5, 200, 287, 'D'); //For A4

// add a page
$pdf->AddPage();

/*
It is possible to create text fields, combo boxes, check boxes and buttons.
Fields are created at the current position and are given a name.
This name allows to manipulate them via JavaScript in order to perform some validation for instance.
*/

// set default form properties
$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(255, 255, 200), 'strokeColor'=>array(255, 128, 128)));
$pdf->Ln(10);
$pdf->Cell(185, 10, 'PAGARE No. '.$credito_no, 0, 0, 'C');
$pdf->Ln(10);
$pdf->Cell(180, 4, 'Fecha de Emisión:              '.$fecha_firmado, 0, 0, 'L');
$pdf->Ln(4);
$pdf->Cell(180, 4, 'Nombre del Acreedor:        TU CELULAR COMERCIALIZADORA S.A.S.        Valor del capital: COP$'.number_format($precio_producto, 0, '.', '.').'=', 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('helvetica','',8);
$pdf->MultiCell(190, 4, 'El suscrito '.$deudor.', mayor de edad, vecino(a) de la ciudad de '.$ciudad.', domiciliado(a) en: '.$prospecto_direccion.', identificado(a) como aparece al pie de su firma obrando en este acto en su propio nombre y representación (el “Deudor”); promete de manera incondicional e irrevocable, pagar a la orden de la sociedad TU CELULAR COMRCIALIZADORA S.A.S. identificada con el NIT 901.310.075-9, con domicilio en la ciudad de Cali, Colombia, (el "Acreedor"), o de quien represente sus derechos y que se encuentre debidamente autorizado para recibir el pago, en la ciudad de Cali, Colombia, en la fecha de vencimiento de este pagaré que se indica en el numeral (5) siguiente, en dinero efectivo, las sumas que se consignan en este instrumento por concepto de capital, que el Deudor ha recibido del Acreedor a su entera satisfacción, a título de mutuo comercial con interés bajo la modalidad de consumo, y las demás sumas de dinero de las cuales se haga mención en el presente instrumento', 0, 'FJ');
$pdf->cell(150, 4, '(el "Préstamo") otorgado al Deudor el '.$fecha_firmado.', de acuerdo con los siguientes términos:', 0, 0, 'L');
$pdf->Ln(10);
$pdf->cell(20, 4, '1. La suma de ');
$pdf->TextField('campo1', 70, 6);
$pdf->cell(5, 4, '($');
$pdf->TextField('campo2', 40, 6);
$pdf->cell(80, 4, ') pesos colombianos moneda corriente por', 0, 0, 'L');
$pdf->Ln(6);
$pdf->cell(80, 4, 'concepto de capital.', 0, 0, 'L');
$pdf->Ln(8);
$pdf->cell(20, 4, '2. La suma de ');
$pdf->TextField('campo3', 70, 6);
$pdf->cell(5, 4, '($');
$pdf->TextField('campo4', 40, 6);
$pdf->cell(80, 4, ') pesos colombianos moneda corriente por', 0, 0, 'L');
$pdf->Ln(6);
$pdf->cell(110, 4, 'concepto de intereses remuneratorios causados y pendientes de pago a partir del día', 0, 0, 'L');
$pdf->TextField('campo5', 20, 6);
$pdf->cell(18, 4, ' del mes de ');
$pdf->TextField('campo6', 40, 6);
$pdf->Ln(6);
$pdf->cell(13, 4, ' del año ');
$pdf->TextField('campo7', 20, 6);
$pdf->cell(20, 4, ' y hasta el día ');
$pdf->TextField('campo8', 10, 6);
$pdf->cell(20, 4, ' del mes de ');
$pdf->TextField('campo9', 40, 6);
$pdf->cell(13, 4, ' del año ');
$pdf->TextField('campo10', 10, 6);
$pdf->cell(4, 4, ' .');
$pdf->Ln(8);
$pdf->cell(20, 4, '3. La suma de ');
$pdf->TextField('campo11', 70, 6);
$pdf->cell(5, 4, '($');
$pdf->TextField('campo12', 40, 6);
$pdf->cell(80, 4, ') pesos colombianos moneda corriente por', 0, 0, 'L');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, 'concepto de honorarios de cobranza, pago de garantías a fiadores o fondos de garantías, y otros montos, causados y no pagados, que deben ser asumidos por el Deudor en relación con el Préstamo.', 0, 'FJ');
$pdf->Ln(4);
$pdf->MultiCell(190, 4, '4. La suma indicada en el numeral 1 devengará intereses de mora, a partir de la fecha de vencimiento de este pagaré, a la máxima tasa de interés', 0, 'FJ');
$pdf->cell(100, 4, 'moratorio permitida por las normas vigentes en la República de Colombia.', 0, 0, 'L');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, '5. Todos los pagos bajo el presente pagaré deben ser hechos por el Deudor en la ciudad de Cali Colombia con fondos inmediatamente disponibles, libres de impuestos y deducciones, y sin compensación o contrademanda alguna, en la siguiente fecha de vencimiento, la cual se ha establecido', 0, 'FJ');
//$pdf->cell(100, 4, 'como vencimiento de este pagaré: día ___ del mes de _____________ del año _______.', 0, 0, 'L');
$pdf->cell(50, 4, 'como vencimiento de este pagaré: día ', 0, 0, 'L');
$pdf->TextField('campo13', 10, 6);
$pdf->cell(18, 4, ' del mes de ');
$pdf->TextField('campo14', 40, 6);
$pdf->cell(13, 4, ' del año ');
$pdf->TextField('campo15', 10, 6);
$pdf->cell(4, 4, ' .');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, '6. El Deudor renuncia irrevocablemente a la presentación, reconvención privada o judicial, protesto, denuncia, reclamación, constitución en mora o cualquier otro tipo de aviso, notificación o requisito adicional de cualquier naturaleza para el cobro de este Pagaré. Así mismo, renuncia al fuero de domicilio, a efecto de lo cual el Acreedor podrá demandar al Deudor en cualquier jurisdicción en donde uno o más de los suscriptores posean activos', 0, 'FJ');
$pdf->cell(50, 4, 'de cualquier clase.', 0, 0, 'L');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, '7. El Deudor, acepta que serán de su cargo los gastos y honorarios profesionales que se generen por la cobranza de este Pagaré.', 0, 'FJ');
$pdf->Ln(4);
$pdf->MultiCell(190, 4, '8. El Deudor acepta que serán de su cargo todos los impuestos que pueda causar el presente Pagaré, en cualquier jurisdicción, incluyendo, si resultare aplicable, el impuesto de timbre, quedando el Acreedor autorizado para pagarlos por cuenta del Deudor si fuere necesario.', 0, 'FJ');
$pdf->Ln(4);
$pdf->MultiCell(190, 4, '9. El Deudor acepta expresamente que, en caso de prórroga, novación o modificación de la obligación a su cargo contenida en este Pagaré, el', 0, 'FJ');
$pdf->cell(50, 4, 'presente Pagaré continuará vigente hasta la fecha pactada en dicha prórroga, novación o modificación.', 0, 0, 'L');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, '10. Este pagaré se encuentra regido por, y debe interpretarse de conformidad con las leyes de la República de Colombia, y el Deudor expresamente declara que las leyes que rigen su creación son las leyes de la República de Colombia, lugar donde ha sido firmado por el Deudor.', 0, 'FJ');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, '11. Los espacios en blanco de este pagaré deberán llenarse con sujeción a las instrucciones contenidas en la carta de instrucciones que se adjunta.', 0, 'FJ');
$pdf->Ln(4);
$pdf->Cell(90, 5, 'Se firma en '.$ciudad.' en la fecha: '.$fecha_firmado.' (dd/mm/aaaa).', 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(50, 5, 'El Deudor', 0, 0, 'L');
$pdf->Ln(15);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(50, 5, '_______________________', 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(100, 5, $deudor, 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(70, 5, 'C.C. No. '.$prospecto_cedula.' de '.$expedicion_c.'. ', 0, 0, 'L');
//$pdf->Output('example_014.pdf', 'D');
ob_clean();

$pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'documents/solicitudes/'.$id_solicitud.'/document2.pdf', 'F');
//$pdf->Output($file2, 'F');
//============================================================+
// END OF FILE
//============================================================+

ob_end_flush();
