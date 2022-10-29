<?

use Luecano\NumeroALetras\NumeroALetras;

$hora_actual = date('Ymd');

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

}

ob_start();

class PDF2 extends FPDF
{


    function Header()
    {
      $this->Rect(5, 5, 200, 287, 'D'); //For A4
    }
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode('Página').$this->PageNo().'/{nb}',0,0,'C');
    }

    function TablaBasica($header){

    //Cabecera
    foreach($header as $col)
    $this->Cell(45,10,$col,1);
    $this->Ln();
   
    $this->Cell(45,8,utf8_decode('4 meses'),1);
    $this->Cell(45,8,utf8_decode('15% más IVA'),1);
    $this->Ln();
    $this->Cell(45,8,utf8_decode('6 meses'),1);
    $this->Cell(45,8,utf8_decode('20% más IVA'),1);
    $this->Ln();
    $this->Cell(45,8,utf8_decode('8 meses'),1);
    $this->Cell(45,8,utf8_decode('25% más IVA'),1);

    }

    function TablaBasica2($header){

    //Cabecera
    foreach($header as $col)
    if($col == 'Porcentaje sobre saldo vencido'){
        $this->Cell(70,10,$col,1);
    }else{
        $this->Cell(50,10,$col,1);
    }
    $this->Ln();
   
    $this->Cell(50,8,utf8_decode('Prejudicial'),1);
    $this->Cell(70,8,utf8_decode('10% más IVA'),1);
    $this->Ln();
    $this->Cell(50,8,utf8_decode('Judicial'),1);
    $this->Cell(70,8,utf8_decode('20% más IVA'),1);
    }

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $k=$this->k;
        if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
        {
            $x=$this->x;
            $ws=$this->ws;
            if($ws>0)
            {
                $this->ws=0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation);
            $this->x=$x;
            if($ws>0)
            {
                $this->ws=$ws;
                $this->_out(sprintf('%.3F Tw',$ws*$k));
            }
        }
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $s='';
        if($fill || $border==1)
        {
            if($fill)
                $op=($border==1) ? 'B' : 'f';
            else
                $op='S';
            $s=sprintf('%.2F %.2F %.2F %.2F re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
        }
        if(is_string($border))
        {
            $x=$this->x;
            $y=$this->y;
            if(is_int(strpos($border,'L')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
            if(is_int(strpos($border,'T')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
            if(is_int(strpos($border,'R')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
            if(is_int(strpos($border,'B')))
                $s.=sprintf('%.2F %.2F m %.2F %.2F l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
        }
        if($txt!='')
        {
            if($align=='R')
                $dx=$w-$this->cMargin-$this->GetStringWidth($txt);
            elseif($align=='C')
                $dx=($w-$this->GetStringWidth($txt))/2;
            elseif($align=='FJ')
            {
                //Set word spacing
                $wmax=($w-2*$this->cMargin);
                $this->ws=($wmax-$this->GetStringWidth($txt))/substr_count($txt,' ');
                $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                $dx=$this->cMargin;
            }
            else
                $dx=$this->cMargin;
            $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
            if($this->ColorFlag)
                $s.='q '.$this->TextColor.' ';
            $s.=sprintf('BT %.2F %.2F Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt);
            if($this->underline)
                $s.=' '.$this->_dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
            if($this->ColorFlag)
                $s.=' Q';
            if($link)
            {
                if($align=='FJ')
                    $wlink=$wmax;
                else
                    $wlink=$this->GetStringWidth($txt);
                $this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$wlink,$this->FontSize,$link);
            }
        }
        if($s)
            $this->_out($s);
        if($align=='FJ')
        {
            //Remove word spacing
            $this->_out('0 Tw');
            $this->ws=0;
        }
        $this->lasth=$h;
        if($ln>0)
        {
            $this->y+=$h;
            if($ln==1)
                $this->x=$this->lMargin;
        }
        else
            $this->x+=$w;
    }



}


$pdf = new PDF2('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(10, 10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190, 10, utf8_decode('CARTA DE INSTRUCCIONES PARA DILIGENCIAR PAGARE EN BLANCO No. '.$credito_no), 0, 0, 'C');
$pdf->Ln(15);
$pdf->SetFont('Arial','',10);
$pdf->Cell(70, 5, utf8_decode($ciudad.', '.$fecha_firmado), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(35, 5, utf8_decode('Señores'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70, 5, utf8_decode('TU CELULAR COMERCIALIZADORA S.A.S.'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20, 5, utf8_decode($ciudad.'.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(190, 4, utf8_decode('El suscrito '.$deudor.', mayor de edad, vecino de la ciudad de '.$ciudad.', identificado(a) como aparece al pie de su firma obrando en este acto en nombre propio, (el "Deudor") conforme al artículo 622 del Código de Comercio colombiano, por medio de la presente carta imparto instrucciones y otorgo facultades irrevocables y permanentes a TU CELULAR COMERCIALIZADORA S.A.S. , quien actúa como Acreedor (el “Acreedor”), para llenar todos y cada uno de los espacios en blanco dejados en el Pagaré No. '.$credito_no.', igualmente identificado en el encabezado de esta carta de instrucciones (el "Pagaré"), cuando se hagan exigibles las obligaciones a cargo del Deudor producto del préstamo otorgado el '.$fecha_firmado.' (dd/mm/aaaa) por un capital de (COP$'.number_format($precio_producto, 0, '.', '.').'=) pesos colombianos moneda corriente a una tasa de interés remuneratorio del ('.$anual.'%) efectivo anual (E.A.), por un plazo de '.$numero_meses.' meses desde la fecha de otorgamiento, para la adquisición de un Equipo dispositivo móvil (celular o table) marca: '.$marca_producto.', modelo: '.$modelo.', IMEI:'.$imei_1.', (el "Equipo"), todo lo cual se encuentra documentado en la carta de otorgamiento de préstamo No. '.$credito_no.' (el "Préstamo"), contenidas en el Pagaré, en los términos que se'), 0, 'FJ');
$pdf->cell(40, 4, utf8_decode('indican a continuación:'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 4, utf8_decode('1. Autorización para llenar el Pagaré. El pagaré suscrito podrá ser llenado cuando el Deudor incumpla en todo o en parte su obligación de pago de cualquiera de las cuotas de capital, intereses o demás sumas debidas al Acreedor, en las fechas previstas en el documento de términos y condiciones del crédito No. '.$credito_no.'. Además podrá ser llenado cuando el Deudor incurra en alguna de las siguientes causales: I) Si el Deudor llegare a ser: (i) Condenado penalmente por parte de las autoridades competentes por delitos de narcotráfico, terrorismo, secuestro, lavado de activos, financiación del terrorismo y administración de recursos relacionados con actividades terroristas u otros delitos relacionados con el lavado de activos y financiación del terrorismo; (ii) incluido en listas para el control de lavado de activos y financiación del terrorismo administradas por cualquier autoridad nacional o extranjera, tales como la lista de la Oficina de Control de Activos en el Exterior – OFAC emitida por la Oficina del Tesoro de los Estados Unidos de América, la lista de la Organización de las Naciones Unidas y otras listas públicas relacionadas con el tema del lavado de activos y financiación del terrorismo; o (iii) condenado por parte de las autoridades competentes en cualquier tipo de proceso judicial relacionado con la comisión de cualquier otro delito; II) Si el Deudor incurre en conductas delictuales o de cualquier manera ilegales o contrarias a las leyes de la República de Colombia haciendo uso del Equipo; III) Si el Deudor enajena o grava, en todo o en parte, el Equipo sin el consentimiento previo expreso y escrito del Acreedor, o si de cualquier manera pierde la titularidad o posesión del Equipo por cualquiera de los medios de que trata el artículo 789 del Código Civil; IV) Si se causan daños a terceros con el Equipo, o si es usado para la comisión de delitos o actividades ilícitas, perjudiciales o peligrosas para los intereses del Acreedor y la sociedad o la ciudadanía en general, o en general, contrarias a las leyes de la República de Colombia haciendo uso del Equipo; V) Si el Deudor incumple una o cualquiera de las obligaciones establecidas en este documento, o en cualquier otro documento que instrumente o incorpore el Crédito como el pagaré; VI) En caso de fraude o suplantación de personalidad o cualquier otra conducta similar o relacionada con la alteración de la personalidad o documentos, en la adquisición o la afiliación o registro del Equipo con un operador de telefonía móvil celular, respecto de medidas cautelares o gravámenes sobre el mismo, o en caso de falsedad de los documentos o manifestaciones que sirvieron de base para adquisición, la afiliación o registro y vinculación con el operador de telefonía móvil celular, o para la solicitud y otorgamiento del Crédito.'), 0, 'FJ');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, utf8_decode('2. El Deudor autoriza expresa e irrevocablemente que el Acreedor y/o sus cesionarios, endosatarios o sucesores, para que llenen los espacios en blanco del Pagaré de manera mecánica (a máquina) o a mano, de conformidad con las'), 0, 'FJ');
$pdf->cell(47, 4, utf8_decode('instrucciones que se indican a continuación:'), 0, 0, 'L');
$pdf->Ln(6);
$pdf->MultiCell(190, 4, utf8_decode('2.1 Saldo de capital. El espacio en blanco identificado con el numeral 1 del Pagaré se llenará con las sumas por concepto de capital que el Deudor adeude al Acreedor el día en que éste opte por llenarlo, incluyendo las sumas adeudadas y pendientes por virtud del vencimiento del plazo y las sumas declaradas vencidas por aceleración del'), 0, 'FJ');
$pdf->cell(20, 4, utf8_decode('plazo.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('2.2. Intereses remuneratorios. El espacio en blanco identificado con el numeral 2 del Pagaré se llenará con las sumas, causadas y pendientes de pago por concepto de los intereses remuneratorios hasta la fecha de vencimiento del Pagaré. Los intereses remuneratorios que el Deudor reconocerá y pagará al Acreedor serán los correspondientes al cálculo de la tasa de interés remuneratorio indicada en el primer párrafo de esta carta de instrucciones.'), 0, 'FJ');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('2.3. Honorarios, costos, gastos y otros. El espacio en blanco identificado con el numeral 3 del Pagaré se llenará con las sumas por concepto de honorarios de cobranza, pago de garantías a fiadores o fondos de garantías, y otros montos, causados y no pagados, que deben ser asumidos por el Deudor en relación con el Préstamo.'), 0, 'FJ');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('2.4. Fecha de vencimiento. El espacio en blanco e identificado con el numeral 5 del Pagaré, correspondiente a la fecha de vencimiento del Pagaré, se llenará con la fecha del día en el que el Deudor decida llenar o completar el Pagaré, como consecuencia de un incumplimiento del Deudor respecto de cualquier causal establecida en el numeral 1 de esta carta de instrucciones; y que, en caso de producirse, será cualquier fecha posterior a la de emisión del Pagaré.'), 0, 'FJ');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('3. Se causarán intereses de mora, con sujeción a la ley de la República de Colombia, sobre las cuotas de capital, o cualquier otro monto vencido y pendiente de pago a la máxima tasa de interés moratorio permitida por las normas vigentes de la República de Colombia para obligaciones de consumo, según sea certificada por la Superintendencia Financiera de Colombia, desde la fecha de vencimiento del Pagaré. De acuerdo con lo establecido en el artículo 2.2.2.35.7 del Decreto 1074 de 2015, según este sea modificado, adicionado o sustituido en el tiempo, no se causarán'), 0, 'FJ');
$pdf->cell(100, 4, utf8_decode('intereses moratorios sobre los intereses remuneratorios no pagados.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('4. Cómputo. Todos los intereses bajo el Pagaré deberán ser calculados sobre la base de un año de trescientos sesenta (360) días y deberán ser pagaderos por el número de días efectivamente transcurridos (incluyendo el primer día, pero.'), 0, 'FJ');
$pdf->cell(70, 4, utf8_decode('excluyendo el último día).'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 5, utf8_decode('5. Los espacios en blanco pueden ser llenados por las personas que sean cesionarios, endosatarios o sucesores del'), 0, 'FJ');
$pdf->cell(70, 4, utf8_decode('Acreedor.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('6. En el evento de que en desarrollo de esta autorización se cometieren errores involuntarios en su diligenciamiento, el Acreedor, o quien haga sus veces, queda expresamente facultado para aclararlos, enmendarlos y corregirlos, de'), 0, 'FJ');
$pdf->cell(70, 4, utf8_decode('manera tal que los mismos correspondan a la realidad.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('El Deudor declara voluntaria, incondicional y expresamente que conoce los términos y condiciones Pagaré y la Carta de Instrucciones y que los suscribe después de haberlos leído atentamente, que las dudas sobre los términos, condiciones y conceptos contenidos en dichos documentos fueron absueltas satisfactoriamente por el Acreedor, y que por tanto los'), 0, 'FJ');
$pdf->cell(70, 4, utf8_decode('firma con pleno conocimiento de lo estipulado en ellos.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40, 5, utf8_decode('El Deudor'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(40, 5, utf8_decode('_______________________'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(70, 5, utf8_decode($deudor), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(70, 5, utf8_decode('C.C. No. '.$prospecto_cedula.' de '.$expedicion_c), 0, 0, 'L');
$pdf->Output($file3, 'F');
ob_end_flush();