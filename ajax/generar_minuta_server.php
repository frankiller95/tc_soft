<?

//require('../assets/class/fpdf/fpdf.php');

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

    $factura_venta = 11111;

	$numero_meses_l = '';
	switch ($numero_meses) {
        case 1:
            $numero_meses_l = 'UNO';
            break;
        case 2:
            $numero_meses_l = 'DOS';
            break;
        case 3:
            $numero_meses_l = 'TRES';
            break;
        case 4:
            $numero_meses_l = 'CUATRO';
            break;
        case 5:
            $numero_meses_l = 'CINCO';
            break;
        case 6:
            $numero_meses_l = 'SEIS';
            break;
        case 7:
            $numero_meses_l = 'SIETE';
            break;
        case 8:
            $numero_meses_l = 'OCHO';
            break;
        case 9:
            $numero_meses_l = 'NUEVE';
            break;
        case 10:
            $numero_meses_l = 'DIEZ';
            break;
        case 11:
            $numero_meses_l = 'ONCE';
            break;
        case 10:
            $numero_meses_l = 'DOCE';
            break;
        default:
            $numero_meses_l = 'error';
            break;
    }
}

class PDF4 extends FPDF
{

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



$pdf = new PDF4('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage(); // pag1
$pdf->SetMargins(10, 10, 10);
$pdf->SetTextColor(76,92,109);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(265, 5, utf8_decode('Anexo N°1'), 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(280, 5, utf8_decode('Documento aceptación de garantía, reporte ante las centrales de riesgo, autorización de manejo de la información y aviso de privacidad.'), 0, 'C');
$pdf->Ln(5);
$pdf->SetTextColor(70,67,64);
$pdf->SetFont('Arial','',10);
$pdf->Cell(265, 4, utf8_decode('Señores'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(265, 4, utf8_decode('GARANTÍAS COMUNITARIAS GRUPO S.A'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->SetFont('Arial','',8);
$pdf->Cell(265, 4, utf8_decode('Medellin'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(280, 4, utf8_decode('1. Yo '.$deudor.', identificado con cédula de ciudadanía número '.$prospecto_cedula.' de '.$ciudad.', actuando en nombre propio y/o en representación de ____________________________ y/o_______________________, identificado con la cédula de ciudadanía No. __________________ de ________________________, actuando en calidad de deudor y/o codeudor, acepto (amos) la cobertura de GARANTÍAS COMUNITARIAS GRUPO S.A. para respaldar la operación aprobada por la Entidad y me comprometo a cancelar las comisiones por concepto de la garantía otorgada más el IVA.'), 0, 'FJ');
$pdf->MultiCell(280, 4, utf8_decode('2. Manifiesto (amos) que conozco (cemos) las condiciones, acepto (amos) que no habrá devolución de remuneración por prepago en caso de no haber un nuevo crédito y reconozco cemos)que el pago que llegare a realizar GARANTÍAS COMUNITARIAS GRUPO S.A., no extingue parcial, ni totalmente la obligación. Si como consecuencia del incumplimiento en el pago de la obligación adquirida, GARANTÍAS COMUNITARIAS GRUPO S.A. se ve obligada a pagar esta obligación total o parcialmente, esta se subrogará ante la Entidad en calidad de acreedor por el valor pagado en mi nombre, incluyendo los valores pagados por intereses corrientes, de mora y demás conceptos que la Entidad haya reclamado y en consecuencia, cancelaré (mos) a su favor, según acuerdo pactado con GARANTÍAS COMUNITARIAS GRUPO S.A., el total de lo adeudado.'), 0, 'FJ');
$pdf->MultiCell(280, 4, utf8_decode('3. Declaro (amos) que los recursos utilizados para el pago de las remuneraciones, o las recuperaciones a favor de GARANTÍAS COMUNITARIAS GRUPO S.A., por el servicio de fianza prestado mediante este instrumento,'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('provienen de fuentes licitas y no de ninguna actividad penalizada por la legislación colombiana.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('4. Como beneficiario (s) de la garantía cubierta por GARANTÍAS COMUNITARIAS GRUPO S.A., autorizo (amos) a GARANTÍAS COMUNITARIAS GRUPO S.A. o a quien represente sus derechos u ostente en el futuro la calidad de acreedor, a reportar, actualizar, solicitar, compartir y divulgar a las Centrales de información TRANSUNION, DATACREDITO, PROCREDITO y/o a cualquier otra Entidad que maneje o administre bases de datos con los mismos fines, toda mi (nuestra) información referente al comportamiento crediticio. Igualmente, a ser notificado por medios físicos y/o virtuales a los datos de contacto que suministre (emos), actualice (emos) o que'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('sean recolectados de Centrales de Información.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('5. Autorizo (amos) a GARANTÍAS COMUNITARIAS GRUPO S.A. a que conozca, actualice, conserve, custodie en servidores propios o en la nube, rectifique y utilice todos los datos personales suministrados a la Entidad o que sean actualizados o recolectados mediante gestión directa o indirecta con las referencias personales, familiares o laborales, de acuerdo con la ley 1581 de 2012 y el decreto reglamentario 1377 de 2013.'), 0, 'FJ');
$pdf->SetFont('Arial','B',8);
$pdf->cell(36, 4, utf8_decode('6.AVISO DE PRIVACIDAD:'), 0, 0, 'L');$pdf->SetFont('Arial','',8);$pdf->cell(244, 4, utf8_decode('GARANTÍAS COMUNITARIAS GRUPO S. A., domiciliado en la ciudad de Medellín, Colombia en la Calle 11 A # 31 A – 89 Int 601 Ed. Bosko en una base de datos y posteriormente utilizados para las siguientes'), 0, 0, 'FJ');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('finalidades: 1. Realizar la gestión de cobro directa o indirectamente de las cuotas o créditos garantizados por GARANTÍAS COMUNITARIAS GRUPO S. A. y que mediante el proceso de reclamación de la garantía haya sido cobrado por parte de la Entidad a GARANTÍAS COMUNITARIAS GRUPO S. A.; 2. Informar sobre los servicios y promociones que tenga GARANTÍAS COMUNITARIAS GRUPO S. A.; 3. Dar cumplimiento a'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('obligaciones contraídas con las entidades que otorgan créditos y deudores garantizados.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('7. Se les comunica a los titulares de la información, que pueden consultar el Manual Interno de Políticas y Procedimientos de Datos Personales de GARANTÍAS COMUNITARIAS GRUPO S. A., el cual contiene las políticas para el tratamiento de la información obtenida, así como los procedimientos de consulta y reclamación que le permitirán hacer efectivos sus derechos al acceso, consulta, rectificación, actualización y supresión de los datos, ingresando a: www.garantiascomunitarias.com/protecciondatospersonales. Para más información, escriba al correo electrónico: info@garantiascomunitarias.com o comuníquese a los teléfonos: (4) 604 45 95- (4)'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('444 57 50.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('8. Las partes que firman este contrato y el Anexo No. 1, aceptan de manera expresa e irrevocable, el mandato que por este instrumento se otorga y que se basa en las siguientes consideraciones: el(los) tomador(es) del crédito autoriza (mos) a facturar y recaudar por cuenta de la Entidad, el pago de la remuneración de la garantía otorgada por GARANTÍAS COMUNITARIAS GRUPO S. A., quien actúa como fiador del crédito y responde ante la Entidad en caso de no pago de la obligación por parte del tomador (es) del crédito, una vez recaudada la remuneración, la cual constituye un ingreso para terceros que recauda la Entidad y quien deberá transferir el'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('dinero a GARANTÍAS COMUNITARIAS GRUPO S.A. sin el cobro de la retención en la fuente.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('9. Autorizo (mos) en forma irrevocable, que a mi retiro de la Entidad, los aportes consignados en esta sean destinados en su totalidad para cubrir las obligaciones pagadas y/o garantizadas por GARANTÍAS'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('COMUNITARIAS GRUPO S. A.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->MultiCell(280, 4, utf8_decode('10. Acepto que por tratarse de una situación intuito personae, el tomador (es) del crédito no está facultado para ceder o traspasar el presente contrato, salvo que medie autorización por escrito por parte de GARANTÍAS'), 0, 'FJ');
$pdf->cell(280, 4, utf8_decode('COMUNITARIAS GRUPO S. A.'), 0, 0, 'L');
$pdf->Ln(4);
$pdf->cell(280, 4, utf8_decode('En constancia de haber leído y aceptado lo anterior, firmo el presente documento, en la ciudad de '.$ciudad.' a los '.$dia.' de '.$mes.' del año '.$ano.'.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(7);
$pdf->cell(140, 4, utf8_decode('DEUDOR: '.$deudor), 0, 0, 'L');$pdf->cell(140, 4, utf8_decode('CODEUDOR: ________________________________'), 0, 0, 'L');
$pdf->Ln(6);
$pdf->cell(140, 4, utf8_decode('FIRMA: _______________ CC: '.$prospecto_cedula), 0, 0, 'L');$pdf->cell(140, 4, utf8_decode('FIRMA: ___________________ CC: ______________'), 0, 0, 'L');
$pdf->Output($file4, 'F');