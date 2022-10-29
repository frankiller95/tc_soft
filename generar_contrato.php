<?

//seteo la vida de la session en 3600 segundos para 1 hora    
ini_set("session.cookie_lifetime","3600");
//seteo el maximo tiempo de vida de la seession
ini_set("session.gc_maxlifetime","3600");

session_name("tc_shop");

session_start();

ini_set('display_errors', 'on');
error_reporting(E_ALL | E_STRICT);

include('includes/connection.php');

include('includes/functions.php');

require('./assets/class/fpdf/fpdf.php');

//require('./assets/class/fpdf/html2pdf.php');

require('./vendor/luecano/numero-a-letras/src/NumeroALetras.php');

use Luecano\NumeroALetras\NumeroALetras;

$id_solicitud = $_GET['id'];

$hora_actual = date('Ymd');

$anual = 25;

$equivalente = 1.88;

$query = "SELECT CONCAT(prospecto_detalles.prospecto_apellidos, ' ', prospecto_detalles.prospecto_nombre) AS deudor, prospectos.prospecto_cedula, CONCAT(ciudades.ciudad, ', ', departamentos.departamento) AS expedicion_c, marcas.marca_producto, CONCAT(modelos.nombre_modelo, ' ', capacidades_telefonos.capacidad) AS modelo, productos_stock.imei_1, solicitudes.precio_producto, terminos_prestamos.numero_meses, porcentajes_iniciales.porcentaje, frecuencias_pagos.frecuencia, firmas_solicitudes.codigo, prospecto_detalles.prospecto_direccion, prospecto_detalles.prospecto_numero_contacto, DATE_FORMAT(firmas_solicitudes.fecha_utilizada, '%d/%m/%Y') AS fecha_firmado FROM `solicitudes` LEFT JOIN prospectos ON solicitudes.id_prospecto = prospectos.id LEFT JOIN prospecto_detalles ON prospecto_detalles.id_prospecto = prospectos.id LEFT JOIN ciudades ON prospecto_detalles.ciudad_id = ciudades.id LEFT JOIN departamentos ON ciudades.id_departamento = departamentos.id LEFT JOIN productos_stock ON solicitudes.id_existencia = productos_stock.id LEFT JOIN inventario ON productos_stock.id_inventario = inventario.id LEFT JOIN productos ON inventario.id_producto = productos.id LEFT JOIN marcas ON productos.id_marca = marcas.id LEFT JOIN capacidades_telefonos ON productos.id_capacidad = capacidades_telefonos.id LEFT JOIN terminos_prestamos ON solicitudes.id_terminos_prestamo = terminos_prestamos.id LEFT JOIN porcentajes_iniciales ON solicitudes.id_porcentaje_inicial = porcentajes_iniciales.id LEFT JOIN frecuencias_pagos ON solicitudes.id_frecuencia_pago = frecuencias_pagos.id LEFT JOIN firmas_solicitudes ON firmas_solicitudes.id_solicitud = solicitudes.id LEFT JOIN modelos ON productos.id_modelo = modelos.id WHERE solicitudes.id = $id_solicitud";

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
	$codigo = $row['codigo'];
	$prospecto_direccion = $row['prospecto_direccion'];
	$prospecto_numero_contacto = $row['prospecto_numero_contacto'];
	$inicial = ($porcentaje*$precio_producto)/100;
	$ciudad = "CALI";
    $fecha_firmado = $row['fecha_firmado'];

    $array_fecha = explode('/', $fecha_firmado);

    $credito_no = $array_fecha['2'].$array_fecha['1'].$array_fecha['0'];

    $factura_venta = 11111;

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

function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['V']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

$text = "";

ob_start();

class PDF extends FPDF
{

    //variables of html parser
    protected $B;
    protected $I;
    protected $U;
    protected $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;

    function __construct($orientation='P', $unit='mm', $format='A4')
    {
        //Call parent constructor
        parent::__construct($orientation,$unit,$format);
        //Initialization
        $this->B=0;
        $this->I=0;
        $this->U=0;
        $this->HREF='';
        $this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
        $this->issetfont=false;
        $this->issetcolor=false;
    }

    
    function WriteHTML($html)
    {
        //HTML parser
        $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><textarea><input>"); //supprime tous les tags sauf ceux reconnus
        $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,stripslashes(txtentities($e)));
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract attributes
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }
    
    /*
    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }
    */

    function OpenTag($tag, $attr)
    {
        //Opening tag
        switch($tag){
            case 'STRONG':
                $this->SetStyle('B',true);
                break;
            case 'EM':
                $this->SetStyle('I',true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag,true);
                break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont=false;
            }
        }
    }

    function SetStyle($tag, $enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
        {
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }

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


$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage(); // pag1
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190, 15, utf8_decode('TÉRMINOS Y CONDICIONES DEL CREDITO'), 0, 0, 'C');
$pdf->Ln(20);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(67, 30, utf8_decode('FECHA (dd/mm/aaaa):'), 0, 0, 'L');$pdf->SetFont('Arial','',12);$pdf->Cell(40, 30, utf8_decode($fecha_firmado.'.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',12);
$pdf->Ln(10);
$pdf->Cell(67, 30, utf8_decode('CIUDAD:'), 0, 0, 'L');$pdf->SetFont('Arial','',12);$pdf->Cell(80, 30, utf8_decode($ciudad.'.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(67, 30, utf8_decode('NOMBRE DEL DEUDOR:'), 0, 0, 'L');$pdf->SetFont('Arial','',12);$pdf->Cell(90, 30, utf8_decode($deudor.'.(el "Deudor")'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(84, 30, utf8_decode('DOCUMENTO DE IDENTIDAD:     C.C. No.'), 0, 0, 'L');$pdf->SetFont('Arial','',12);$pdf->Cell(30, 30, utf8_decode($prospecto_cedula.' de '.$expedicion_c.'.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',12);
$pdf->Ln(10);
$pdf->Cell(86, 30, utf8_decode('ORIGEN DEL CREDITO:').'                '.utf8_decode('FECHA'), 0, 0, 'L');$pdf->SetFont('Arial','',12);$pdf->Cell(40, 30, utf8_decode($fecha_firmado.'.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',12);
$pdf->Ln(10);
$pdf->Cell(67, 30, utf8_decode('CREDITO No.:'), 0, 0, 'L');$pdf->SetFont('Arial','',12);$pdf->Cell(40, 30, utf8_decode($credito_no), 0, 0, 'L');
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->MultiCell(190, 5, utf8_decode('TU CELULAR COMERCIALIZADORA S.A.S. con NIT. 901.310.075-9 domiciliada en la Calle 47 Norte # 3DN - 24 local 101 de la ciudad de Cali Valle del Cauca (el "Acreedor"), ha decidido aprobar su solicitud de crédito, y le ha otorgado un mutuo comercial con intereses bajo la modalidad de crédito de consumo (el "Crédito") para la adquisición de un Equipo dispositivo móvil (celular o table) marca: '.$marca_producto.', modelo: '.$modelo.', IMEI: '.$imei_1.', (el "Equipo") bajo'), 0, 'FJ');
$pdf->Ln(3);
$pdf->cell(70, 0, utf8_decode('los siguientes términos y condiciones:'), 0, 0, 'L');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(10);
$pdf->cell(35, 5, '1. Capital Aprobado:', 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(4, 5,utf8_decode(' $'.number_format($precio_producto, 0, '.', '.').' pesos colombianos.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(10);
$pdf->cell(15, 0, '2. Plazo:', 0, 0, 'L');$pdf->SetFont('Arial','',10);$pdf->Cell(15, 0,utf8_decode($numero_meses.' meses.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(34, 0, utf8_decode('3. Tasas de interés:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(150, 0,utf8_decode('Se conviene una tasa de interés corriente remuneratorio fija efectivo anual del '.$anual.'%, equivalente al '), 0, 0, 'L');
$pdf->Ln(3);
$pdf->MultiCell(190, 5, utf8_decode($equivalente.'% N.M.V. En el evento en que se ejecute judicialmente el pagaré que incorpora el Crédito Las Partes convienen los intereses a la tasa máxima permitida por las leyes colombianas sobre el saldo de capital no pagado a esa fecha, facultad que se ejercerá por parte del fiador o garante que se subrogue en la posición del Acreedor, o endose el título al fiador o garante, y solamente a partir de la fecha de vencimiento del pagaré, que ocurrirá en o después de la referida'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('subrogación o endoso.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(10);
$pdf->cell(47, 5, utf8_decode('4. Término de amortización:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(143, 5,utf8_decode('Mensual en el monto y fechas según se establecen en el documento adjunto a este'), 0, 0, 'FJ');
$pdf->Ln(5);
$pdf->Cell(60, 5,utf8_decode('documento que contiene la tabla de amortización.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(27, 5, utf8_decode('5. Cuota inicial:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(152, 5,utf8_decode(' Se deja expresamente establecido que el Deudor cancela una cuota inicial de $'.number_format($inicial, 0, '.', '.').', monto que no'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(190, 5,utf8_decode('se incluye en el capital aprobado y objeto del presente contrato.'), 0, 0, 'L');
$pdf->Cell(60, 25,utf8_decode(' '), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(32, 5, utf8_decode('6. Cuota mensual:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 11);$pdf->Cell(190, 5,utf8_decode('Las partes acuerdan que el crédito otorgado se cancelará en '.$numero_cuotas.' cuotas fijas'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode($frecuencia_l.' hasta concluir el plazo, en los valores y forma como se indica en la tabla de amortización adjunta en la que se incluye un cargo mensual que cubre los costos y gastos por el estudio del crédito, consulta en centrales de riesgo, administración y manejo del crédito, papelería, comisiones de la fianza,'), 0, 'FJ');
$pdf->cell(190, 5, utf8_decode('interacción tecnológica y costos de firma electrónica de documentos desmaterializados.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(47, 5, utf8_decode('7. Condiciones especiales:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(150, 5, utf8_decode('a. El Deudor deberá realizar el pago de las cuotas periódicas sin perjuicio de que el Equipo'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('se dañe total oparcialmente, se deteriore, tenga defectos de fabricación, deje de funcionar o funcione de manera incorrecta o no deseada, se extravíe o sea hurtado. En caso de hurto o pérdida, el Deudor se obliga a denunciar dicha conducta ante las autoridades competentes, y ante el prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular con quien está el Equipo vinculado y quien le preste el servicio de telefonía móvil celular para los efectos pertinentes. Se recomienda al Deudor reportar cualquier caso de hurto o pérdida a la línea de servicio al prospecto telefónico del Acreedor, al número en Cali, (2) 3226698790, con el fin de que el Acreedor proceda con la'), 0, 'FJ');
$pdf->cell(80, 5, utf8_decode('inhabilitación del Equipo, que el Deudor autoriza expresamente por este medio.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('b. El Acreedor no es responsable por la calidad, seguridad y funcionamiento del Equipo, ni por su garantía sea esta legal o ampliada por el fabricante, cualquiera que sea de acuerdo con las normas de protección al consumidor en Colombia. Cualquier reclamo relacionado con estos asuntos deberá realizarlos el Deudor directamente al fabricante del Equipo, y renuncia, en la medida en que las normas lo permitan, a reclamar al Acreedor por el funcionamiento, calidad, seguridad o garantías del Equipo, así como tampoco requerir de este ninguna actividad o erogación relacionada.'), 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('c. El Acreedor no forma parte de la relación de consumo entre el Deudor y fabricante del Equipo. Esto es aceptado y entendido por el Deudor. Tampoco hace parte el Acreedor de la relación de consumo entre el Deudor y cualquier'), 0, 'FJ');
$pdf->cell(120, 5, utf8_decode('prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('d. El pago de cualquier factura por la prestación del servicio de telefonía móvil u otras relacionadas emitidas por el prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular, deben ser pagadas por el Deudor directamente a este, ni la relación contractual entre el Deudor y dicho prestado u operador es oponible al Acreedor. En el mismo sentido, el prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular será el único responsable frente al Deudor por la continuidad, oportunidad, calidad y seguridad en la prestación'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('del servicio de telefonía móvil celular en Colombia.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('e. El Deudor reconoce que el hecho de solicitar y tomar este Crédito no está atado o vinculado con la venta de un plan o planes de telefonía móvil celular por parte del prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular, ni viceversa. El Deudor ha decidido de manera autónoma, espontánea y libre suscribir este'), 0, 'FJ');
 $pdf->MultiCell(190, 5, utf8_decode('escrito y solicitar el Crédito, y no ha sido constreñido ni obligado a ello, ni se ha condicionado la venta ni sus términos y condiciones, ni la prestación del servicio de telefonía móvil celular por parte de un prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular, a la solicitud el Crédito y la suscripción de este documento.'), 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('f. No existe restricción para que el Deudor haga uso del Equipo contratando el servicio con el prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular de su preferencia. Aun cuando el Acreedor no forma parte de la relación contractual o de consumo entre el Deudor y el prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular, el Deudor manifiesta que conoce y acepta, y que le fue informado antes de solicitar y tomar el Crédito para la compra del Equipo, que el Equipo está afiliado a la tarjeta SIM con la cual se tramitó el Crédito. En cualquier caso, en el que el Deudor quiera cambiar la tarjeta SIM con la cual se tramitó el Crédito, o hacer tantos cambios como lo considere durante la vigencia del Crédito, podrá llamar a los números de atención al prospecto del Acreedor para anunciar el cambio SIM. El servicio al prospecto del Acreedor procederá a realizar el cambio correspondiente. Todo esto con la única finalidad de prevenir casos de fraude al Acreedor, y de reventa de Equipos que han sido adquiridos con deuda del Acreedor y no están pagados, engañando a terceros de buena fe exenta de culpa. También tiene utilidad para limitar la funcionalidad del Equipo en casos de hurto, sin que esto reemplace el trámite que debe surtir el Deudor ante el prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular. Teniendo en cuenta la finalidad indicada aquí, en caso de que el Deudor realice un cambio de SIM sin informar a el servicio al prospecto del Acreedor, el equipo quedará inhabilitado conforme a lo indicado en esta cláusula, y será habilitado nuevamente desde el momento en el que'), 0, 'FJ');
$pdf->cell(150, 5, utf8_decode('notifique de dicho cambio como se indica aquí.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('g. El Deudor reconoce y acepta que el Equipo objeto de financiación, descrito en el presente documento ha sido escogido por éste de manera libre, autónoma y espontánea, y que corresponde al equipo que solicitó adquirir, con'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('todas sus características técnicas.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('h. El Deudor no podrá utilizar el Equipo para actividades diferentes a las de comunicación móvil celular por cualquier medio del que disponga el Equipo, ni tampoco para la realización de cualquier actividad ilegal o delictual de acuerdo con las leyes de la República de Colombia. El Acreedor no será responsable ni frente al Deudor ni frente a terceros por'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('el uso que este le dé al Equipo.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(63, 5, utf8_decode('8. Distribución y aplicación de pagos:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(128, 5, utf8_decode(' Los pagos que efectúe el Deudor al Acreedor se aplicarán en el siguiente orden:'), 0, 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode(' gastos y costas judiciales y de cobranza, incluyendo ejecución de garantías, si las hubiere, honorarios razonables de abogado, si los hubiere, cargos por administración y por uso del recurso tecnológico (si el Deudor lo hubiere solicitado y aceptado), si los hubiere, cuotas del fondo de garantías, de recaudo u otros gastos a cargo del Deudor cuando cada uno de ellos resulte aplicable, intereses remuneratorios, si los hubiere y capital. Si sobrare un remanente será aplicado a otras obligaciones del Deudor con el Acreedor, o será devuelto o reintegrado al Deudor vía transferencia bancaria u'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('otra vía a disposición del Acreedor.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(33, 5, utf8_decode('9. Incumplimiento:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(157, 5, utf8_decode('El Acreedor podrá declarar el incumplimiento del Deudor respecto del Crédito otorgado, e iniciar'), 0, 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('cualquier acción judicial o extra judicial requerida, sin tener que requerir privada o judicialmente al Deudor, ni constituirlo en mora, derechos a los que expresamente renuncia el Deudor, por las siguientes causas:'), 0, 'FJ');
$pdf->Ln(5);
$pdf->cell(180, 5, utf8_decode('a. Cuando el Deudor incumpla con el pago de cualquiera de las cuotas periódicas.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('b. Si el Deudor llegare a ser: (i) Condenado penalmente por parte de las autoridades competentes por delitos de narcotráfico, terrorismo, secuestro, lavado de activos, financiación del terrorismo y administración de recursos relacionados con actividades terroristas u otros delitos relacionados con el lavado de activos y financiación del terrorismo; (ii) incluido en listas para el control de lavado de activos y financiación del terrorismo administradas por cualquier autoridad nacional o extranjera, tales como la lista de la Oficina de Control de Activos en el Exterior – OFAC emitida por la Oficina del Tesoro de los Estados Unidos de América, la lista de la Organización de las Naciones Unidas y otras listas públicas relacionadas con el tema del lavado de activos y financiación del terrorismo; o (iii) condenado por parte de las autoridades competentes en cualquier tipo de proceso judicial relacionado con la comisión de cualquier otro'), 0, 'FJ');
$pdf->cell(20, 5, utf8_decode('delito.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('c. Si el Deudor incurre en conductas delictuales o de cualquier manera ilegales o contrarias a las leyes de la República'), 0, 'FJ');
$pdf->cell(20, 5, utf8_decode('de Colombia haciendo uso del Equipo.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('d. Si durante el plazo del Crédito, el Deudor enajena o grava, en todo o en parte, el Equipo sin el consentimiento previo expreso y escrito del Acreedor, o si de cualquier manera pierde la titularidad o posesión del Equipo por cualquiera de los medios de que trata el artículo 789 del Código Civil, a la luz de la facultad de pactar, establecida en la ley 1676 de'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('2013 y de la garantía mobiliaria establecida en la cláusula 10.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('e. Si se causan daños a terceros con el Equipo, o si es usado para la comisión de delitos o actividades ilícitas, perjudiciales o peligrosas para los intereses del Acreedor y la sociedad o la ciudadanía en general, o en general,'), 0, 'FJ');
$pdf->cell(120, 5, utf8_decode('contrarias a las leyes de la República de Colombia haciendo uso del Equipo.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('Si el Deudor incumple una o cualquiera de las obligaciones establecidas en este documento, o en cualquier otro'), 0, 'FJ');
$pdf->cell(100, 5, utf8_decode('documento que instrumente o incorpore el Crédito como el pagaré.'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('g. En caso de fraude o suplantación de personalidad o cualquier otra conducta similar o relacionada con la alteración de la personalidad o documentos, en el proceso de solicitud y otorgamiento del Crédito, en la adquisición o la afiliación o registro del Equipo con un prestador de redes y servicios de telecomunicaciones u operador de telefonía móvil celular, respecto de medidas cautelares o gravámenes sobre el mismo, o en caso de falsedad de los documentos o manifestaciones que sirvieron de base para adquisición, la afiliación o registro y vinculación con el prestador de redes y'), 0, 'FJ');
$pdf->cell(100, 5, utf8_decode('servicios de telecomunicaciones u operador de telefonía móvil celular.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(75, 5, utf8_decode('10. Garantía Mobiliaria respecto del Equipo:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(116, 5, utf8_decode(' El Deudor constituye por medio de este documento una garantía mobiliaria'), 0, 0, 'FJ');
$pdf->Ln(5);
$pdf->Cell(55, 5, utf8_decode('respecto del Equipo, en los siguientes términos:'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('a. El Deudor, obrando en el carácter y en las condiciones indicadas bajo este Crédito, en los términos de la Ley 1676 de 2013 y demás normas concordantes y complementarias aplicables, constituye por el presente documento en favor del Acreedor, una garantía mobiliaria sobre el Equipo (que se identifica detalladamente en el encabezado del presente documento), con el fin de respaldar las obligaciones garantizadas, que corresponden a las obligaciones del Deudor de pagar el Crédito que el Acreedor le otorga por este medio, y cualesquiera otras obligaciones crediticias que el Acreedor le haya otorgado, o que le otorgará en el futuro, particularmente el pago de todas las sumas pendientes por concepto de capital, intereses remuneratorios y moratorios (como se establecen en la cláusula 3) que se llegasen a causar, diferencias en tasa de interés, gastos, (incluyendo los gastos de cobranza judicial y extrajudicial, en caso de requerirse), costos, derechos de registro de este Contrato, honorarios de abogado, honorarios de peritos avaluadores'), 0, 'FJ');
$pdf->cell(120, 5, utf8_decode('para la valoración del Equipo cuando así se requieran de acuerdo con este Contrato.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('Las obligaciones garantizadas incluyen también cualesquiera otras obligaciones monetarias que en el futuro llegase a contraer el Deudor para con el Acreedor, a cualquier título o por cualquier concepto, hasta la total cancelación de las obligaciones garantizadas, dentro de los términos contemplados en el Crédito, u otros créditos u obligaciones crediticias que el Deudor tenga o llegue a tener con el Acreedor, o en este documento o documentos que consagren o contengan las obligaciones garantizadas. Como quiera que las obligaciones garantizadas han sido adquiridas por el Deudor para la compra del Equipo, la garantía mobiliaria que por este Contrato se constituye será una garantía mobiliaria prioritaria e adquisición de acuerdo con lo establecido en la Ley 1676 de 2013 y sus decretos reglamentarios para esta clase de garantía. El Equipo y su funcionalidad respaldarán el cumplimiento del Deudor respecto de las obligaciones garantizadas, incluso en el evento en el que el Deudor haya perdido su propiedad. El Deudor declara que acepta y entiende que esta garantía mobiliaria garantiza el pago de todas las obligaciones accesorias a las obligaciones garantizadas de acuerdo con cualquier este documento, o en los demás documentos en los que consten estas obligaciones, así como en los documentos que modifiquen o amplíen este Crédito, en cualquier materia. Las Partes expresamente acuerdan que ni la garantía constituida por el presente documento, ni el monto máximo de la misma, deberán ser modificados ni disminuidos, en forma alguna, en el evento en que las obligaciones garantizadas sean parcialmente satisfechas. El levantamiento total o parcial del gravamen sobre el Equipo debe ser autorizado de forma'), 0, 'FJ');
$pdf->cell(120, 5, utf8_decode('previa, expresa y escrita por el Acreedor.'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(190, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('b. Para efectos del artículo 10 de la Ley 1676 de 2013, las Partes declaran que el monto de las obligaciones garantizadas presentes cubiertas por este Contrato, será hasta la suma de $'.number_format($valor_declarado, 0, '.', '.').' pesos colombianos, que corresponde al valor de mercado del Equipo al momento de la suscripción de este documento, y por encima de dicha suma cobija todos los intereses, costos y gastos de conformidad con lo dispuesto en el Crédito.'), 0, 'FJ');
$pdf->Ln(5);
$pdf->Cell(170, 5, utf8_decode('c. La presente es una garantía mobiliaria sin tenencia del Acreedor.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('d. La garantía mobiliaria permanecerá vigente desde la fecha de su suscripción, mientras existan saldos pendientes de pago de las obligaciones garantizadas, y hasta que sea cancelada expresamente por el Acreedor a través de su'), 0, 'FJ');
$pdf->Cell(170, 5, utf8_decode('representante legal o apoderado.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 5, utf8_decode('e. El Acreedor podrá registrar la presente garantía mobiliaria en el Registro Nacional de Garantías Mobiliarias para efectos de oponibilidad, publicidad y determinación del grado de prelación correspondiente.'), 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('f. Condición especial sobre el uso del Equipo como bien dado en garantía. Amparadas en la facultad de pactar sobre el uso del bien dado en garantía, establecida en el artículo 18 de la Ley 1676 de 2013 y en línea con el artículo 1018 del Código de Comercio, las Partes acuerdan, y el Deudor manifiesta que conoce y acepta, y que le fue informado antes de solicitar y tomar el Crédito y suscribir este documento, que ante cualquier evento de mora en el pago de sus obligaciones bajo el Crédito, en cualquier momento durante el plazo del Crédito, el Equipo será inhabilitado para su uso de manera remota y temporal, a través de una aplicación instalada en este, hasta tanto no se ponga al día y cumpla con el pago de por lo menos una (1) cuota periódica, vencida y pendiente de pago, momento en el que el Equipo se habilitará nuevamente, por un término de duración equivalente al de la o las cuotas periódicas vencidas que hubiera pagado. En cualquier caso, aunque el Equipo esté inhabilitado, el Deudor podrá hacer llamadas a números de emergencia sin limitación alguna. El Deudor por este medio autoriza expresa e irrevocablemente al Acreedor a inhabilitar el Equipo ante cualquier caso de mora en el pago de las cuotas periódicas. El Acreedor deberá habilitar nuevamente el Equipo en un periodo que no podrá exceder veinticuatro (24) horas desde el momento en que este acredite el recibo del pago de la cuota periódica vencida. Este procedimiento de inhabilitación no forma parte de los procesos de bloqueo de código IMEI que se surten frente a los prestadores de redes y servicios de telecomunicaciones u operadores de telefonía móvil celular de acuerdo con las normas de la Comisión de Regulación de Comunicaciones que los regulan. Así mismo, durante el término de duración de la inhabilitación del Equipo, el Acreedor no liquidará, acumulará ni cobrará intereses remuneratorios ni moratorios (en línea con la cláusula 4) al Deudor, por lo que se entiende que el plazo del Crédito, y sus términos y condiciones, se suspenden por el número de días que dure la'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('inhabilitación.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(50, 5, utf8_decode('11. Incorporación del Crédito:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(140, 5, utf8_decode('El deudor deberá suscribir un pagaré en blanco con carta de instrucciones No.'.$credito_no), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('a favor del Acreedor El Deudor irrevocablemente autoriza al Acreedor a completar todos los espacios en blanco'), 0, 'FJ');
$pdf->cell(100, 5, utf8_decode('conforme la carta de instrucciones del pagaré.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(78, 5, utf8_decode('12. Fianza otorgada por un fondo de garantías:'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(105, 5, utf8_decode('  El Deudor acepta irrevocablemente el pago del valor de una fianza a'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('la sociedad GARANTÍAS COMUNITARIAS GRUPO S.A., o FIGARANTÍAS S.A.S., o cualquier otro fondo aplicable (“el Afianzador”), un pago único por un porcentaje sobre el monto de capital del mismo, más IVA, dependiendo del plazo del Crédito. El pago será realizado directamente por el Deudor a dicha entidad, por lo que el Acreedor solamente prestará el servicio de recaudo del mismo en calidad de mandataria de la mencionada sociedad. El precio de dicha fianza podrá ser pagado por el Deudor con sus propios recursos, en la primera cuota de pago del Crédito, o financiado por el Acreedor y adicionado al monto del capital, monto respecto del cual no se cobrarán intereses de ninguna naturaleza. Sin embargo, el Deudor podrá contratar la fianza con cualquier otro fondo de garantías, previo cumplimiento de los requisitos que dicho fondo requiera del Acreedor y del Deudor, y siempre que dicho fondo tenga un patrimonio neto total de mínimo mil (1.000) salarios mínimos legales mensuales vigentes (SMMLV). Para esto, el Deudor deberá entregar al Acreedor, en un plazo de treinta (30) días calendario siguientes a la fecha de desembolso, los documentos que acrediten la fianza. De no entregarlos al Acreedor en dicho plazo, se entenderá que el Deudor acepta realizar el pago para la contratación de la fianza con la sociedad GARANTÍAS COMUNITARIAS GRUPO S.A., o FIGARANTÍAS S.A.S., o cualquier otro fondo aplicable (“el Afianzador”) bajo los términos de esta cláusula y aquellos del anexo de este documento que contiene los términos y condiciones de la fianza. Esta fianza deberá estar vigente durante todo el plazo indicado en este documento, y hasta el pago total del Crédito. En caso de entregarlos, el Deudor autoriza al Acreedor para cancelar la fianza inicialmente otorgada desde la fecha de entrada en vigor de la fianza contratada directamente por el Deudor y cuyos documentos entrega al Acreedor en el término establecido. En este evento, el Acreedor cesará el cobro de cualquier monto de la fianza inicial, y en caso de que el Deudor la haya pagado en su totalidad en la primera cuota del Crédito, este autoriza al Acreedor para aplicar el monto no utilizado de dicho pago,'), 0, 'FJ');
$pdf->cell(120, 5, utf8_decode('como abono o prepago de su obligación de pago del capital del Crédito.'), 0, 0, 'L');
$pdf->Ln(20);
//Títulos de las columnas
$header=array(utf8_decode('Plazo del Crédito'),utf8_decode('Valor (% del capital)'));
$pdf->TablaBasica($header);
$pdf->Ln(20);
$pdf->SetFont('Arial','B',10);
$pdf->cell(24, 5, utf8_decode('13. Recaudo.'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(165, 5, utf8_decode('El Deudor podrá realizar el pago de sus cuotas en las redes y entidades autorizadas que podrá consultar'), 0, 0, 'FJ');
$pdf->Ln(5);
$pdf->Cell(29, 5, utf8_decode('en la página web '), 0, 0, 'L');$link = $pdf->AddLink();$pdf->SetFont('Arial','B',10);$pdf->Write(5,'www.tcshop.co.', 'http://www.tucelular.net.co/');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(24, 5, utf8_decode('14. Prepagos.'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(100, 5, utf8_decode('El Deudor en cualquier momento de la vigencia del Crédito podrá hacer pagos anticipados de las cuotas'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('o saldos pendientes de pago, lo cual no generará ninguna carga adicional ni el pago de intereses no causados, ni'), 0, 'FJ');
$pdf->cell(100, 5, utf8_decode('tampoco será objeto de ninguna sanción de ningún tipo.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(20, 5, utf8_decode('15. Cesión.'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(100, 5, utf8_decode('El Acreedor podrá ceder este Crédito, su posición contractual en este documento, y endosar el pagaré que'), 0, 0, 'L');
$pdf->cell(47, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(100, 5, utf8_decode('lo incorpora a cualquier persona, para lo cual notificará al Deudor, en los términos establecidos en las normas aplicables.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(63, 5, utf8_decode('16. Gastos y honorarios de cobranza.'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(100, 5, utf8_decode(' Solamente en el evento en que se haya desplegado una actividad prejudicial o'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('judicial tendiente al cobro de tales saldos vencidos y pendientes de pago, por parte del fiador o garante que se subrogue en la posición del Acreedor, o endose el título al fiador o garante, y solamente a partir de la fecha de vencimiento del pagaré, que ocurrirá en o después de la referida subrogación o endoso; dicho fiador o garante cobrará honorarios de cobranza prejudicial y judicial en los que deba incurrir para lograr el pago de las cuotas vencidas y no pagadas al momento del endoso o subrogación. La gestión de cobranza será realizada por personal del garante o fiador y por terceros contratados para tal fin. Los gastos y honorarios de cobranza corresponderán al porcentaje del saldo en mora efectivamente recaudado, equivalente al gasto incurrido en la cobranza y los montos cobrados al garante o fiador por los terceros a cargo de la gestión de cobranza, según se indican en la tabla abajo. El valor resultante será sujeto de impuesto sobre las ventas IVA por el servicio de cobranza a cargo del Deudor. El Deudor con la firma del presente documento reconoce y acepta su obligación de pagar los gastos de cobranza que le correspondan en caso se encuentre en mora de pagar una o varias cuotas, y que el Acreedor haya cedido y endosado el Crédito al garante o fiador, o se haya subrogado. No se cobran estos montos cuando no hay saldos vencidos y pendientes de pago, ni de'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('manera automática posterior a la mora.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->Ln(5);
$pdf->cell(75, 5, utf8_decode('Tarifas de honorarios de cobranza'), 0, 0, 'L');
$pdf->Ln(15);
//Títulos de las columnas
$header=array(utf8_decode('Tipo de cobranzas'),utf8_decode('Porcentaje sobre saldo vencido'));
$pdf->TablaBasica2($header);
$pdf->Ln(20);
$pdf->cell(80, 5, utf8_decode('17. Política de tratamiento de datos personales.'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(130, 5, utf8_decode(' El Deudor, identificado en la parte superior del presente documento,'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('autoriza de forma libre, previa, expresa e informada a TU CELULAR COMERCIALIZADORA S.A.S. (El “Acreedor”) y/o a la sociedad GARANTÍAS COMUNITARIAS GRUPO S.A., o FIGARANTÍAS S.A.S., o cualquier otro fondo aplicable (“El Afianzador”), en calidad de RESPONSABLE DEL TRATAMIENTO, para que recolecte, almacene, use, procese, suprima, transmita y/o transfiera a terceros mis datos personales, con las finalidades que se indican a continuación: a) Registrarlo como prospecto de LA EMPRESA o del AFIANZADOR. b) Recolectar, registrar y actualizar sus datos personales con la finalidad de informar, comunicar, organizar, controlar, atender, acreditar las actividades en relación a su condición de PROSPECTO de LA EMPRESA o del AFIANZADOR. c) Dar cumplimiento y seguimiento a las obligaciones contraídas por El Deudor (EL PROSPECTO) con El Acreedor (LA EMPRESA) y/o con EL AFIANZADOR. d) Consultar en cualquier tiempo en los bancos de datos toda la información relevante para su vinculación como titular de los productos ofrecidos por las entidades autorizadas, conocer su desempeño como deudor, su capacidad de pago o para valorar el riesgo futuro de concederle un crédito o un seguro, así como para verificar el cumplimiento de sus deberes legales y/o contractuales. e) Reportar en los bancos de datos, directamente o por intermedio de las autoridades de vigilancia y control, datos tratados o sin tratar, referidos a cumplimiento o incumplimiento de sus obligaciones crediticias o deberes de contenido patrimonial y sus solicitudes de crédito, datos personales, así como información de sus relaciones comerciales, financieras y en general socioeconómicas que haya entregado a las autoridades autorizadas o que consten en registros públicos, bases de datos públicas o documentos públicos. f) Responder a solicitudes o requerimientos de información de nuestros productos. g) Proveer, procesar, completar y darles seguimiento a los productos adquiridos por el prospecto. h) Realizar labores de facturación. i) Gestionar el cobro de las obligaciones financieras adquiridas por el Deudor (PROSPECTO) con el Acreedor (LA EMPRESA) y/o con EL AFIANZADOR. j) Enviar a su correo físico, electrónico, celular o dispositivo móvil, vía mensajes de texto (SMS y/o MMS, WhatsApp, Facebook Messenger) o a través de cualquier otro medio análogo y/o digital de comunicación creado o por crearse, información comercial, publicitaria o promocional sobre los productos y/o servicios, eventos y/o promociones de tipo comercial, con el fin de impulsar, invitar, dirigir, ejecutar, informar y, de manera general, llevar a cabo campañas, promociones o concursos de carácter comercial o publicitario, adelantados directamente por el Acreedor (LA EMPRESA) y/o por terceras personas. k) Realizar análisis estadísticos de tendencias, hábitos de'), 0, 'FJ');
$pdf->cell(150, 5, utf8_decode('consumo y comportamientos del consumidor.'), 0, 0, 'L');
$pdf->Ln(10);
//$pdf->AliasNbPages();
//$pdf->AddPage();
//$pdf->SetMargins(10, 10);
//$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(190, 5, utf8_decode('DERECHOS QUE LE ASISTEN COMO TITULAR DE DATOS PERSONALES. El Acreedor (LA EMPRESA) y/o EL AFIANZADOR, garantiza que el trámite del ejercicio del derecho de Habeas Data promovido por los titulares de información se efectuará conforme a lo estipulado en su POLÍTICA DE TRATAMIENTO DE DATOS PERSONALES, la consulta o reclamo podrá realizarse vía comunicación física a la Calle 47 Norte No. 3DN-24 Barrio'), 0, 'FJ');
$pdf->Cell(155, 5, utf8_decode('Vipasa Cali, o a través de comunicación electrónica a la siguiente cuenta de correo electrónico:'), 0, 0, 'FJ');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);$pdf->Write(5,'habeasdata@tucelular.net.co.', 'mailto:habeasdata@tucelular.net.co');$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, 5, utf8_decode(' Recuerde que usted deberá indicar en el asunto "Habeas Data" informando su nombre'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->Cell(25, 5, utf8_decode('completo y documento de identificación.'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',10);
$pdf->cell(47, 5, utf8_decode('18. Consideraciones finales.'), 0, 0, 'L');$pdf->SetFont('Arial', '', 10);$pdf->Cell(163, 5, utf8_decode(' El presente documento contiene los términos y condiciones del Crédito dando cumplimiento'), 0, 0, 'L');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('a lo establecido en el artículo 45 de la ley 1080 de 2011 y los artículos 2.2.2.35.5 y 2.2.2.35.7 del Decreto 1074 de 2015. La firma del Deudor en esta comunicación se entenderá como aceptación y consentimiento respecto de los términos y condiciones del contrato de mutuo comercial con intereses bajo la modalidad de consumo que suscribe con el Acreedor; como constancia de aceptación del contenido de este documento y de la solicitud de su desembolso.'), 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('Con la firma de este documento se ratifica la autorización para el tratamiento de sus datos personales conforme a las políticas de privacidad y protección de datos personales del Acreedor, así como la autorización para que el Deudor sea consultado, seguido y reportado en bases o bancos de datos de información financiera. En caso de incumplimientos en el pago de las cuotas mensuales previstas en este documento, se harán los reportes de acuerdo con lo establecido por la ley 1066 del 2008, sus normas reglamentarias y aquellas que las modifiquen, adicionen o sustituyan en el tiempo'), 0, 'FJ');
$pdf->Ln(5);
$pdf->MultiCell(190, 5, utf8_decode('Después de haber leído y entendido el presente documento, y de haber obtenido respuestas a las preguntas que hubiera tenido al respecto por parte del Acreedor, el Deudor suscribe el presente documento en señal de aceptación de los términos y condiciones del contrato de mutuo comercial con intereses bajo la modalidad de consumo, y declara que'), 0, 'FJ');
$pdf->cell(47, 5, utf8_decode('recibe copia del mismo.'), 0, 0, 'L');
$pdf->Ln(40);
$pdf->SetFont('Arial','U',10);
$pdf->Cell(80, 15, utf8_decode('                       '.$codigo.'                        '), 0, 0, 'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(80, 15, utf8_decode('__________________________'), 0, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(80, 15, utf8_decode($deudor), 0, 0, 'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(80, 15, utf8_decode('Representante legal'), 0, 0, 'C');
$pdf->Ln(10);
$pdf->Cell(10, 15, utf8_decode('C.C.'), 0, 0, 'L');$pdf->SetFont('Arial','',10);$pdf->Cell(80, 15, utf8_decode($prospecto_cedula.' DE '.$expedicion_c), 0, 0, 'L');$pdf->SetFont('Arial','B',10);$pdf->Cell(40, 15, utf8_decode(' TU CELULAR COMERCIALIZADORA S.A.S. '), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(10, 15, utf8_decode('Dir.'), 0, 0, 'L');$pdf->SetFont('Arial','',10);$pdf->Cell(90, 15, utf8_decode($prospecto_direccion.','.$expedicion_c.'.'), 0, 0, 'L');
$pdf->SetFont('Arial','B',10);
$pdf->Ln(10);
$pdf->Cell(10, 15, utf8_decode('Tel.'), 0, 0, 'L');$pdf->SetFont('Arial','',10);$pdf->Cell(28, 15, utf8_decode($prospecto_numero_contacto.'.'), 0, 0, 'L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(185, 10, utf8_decode('PAGARE No. '.$credito_no), 0, 0, 'C');
$pdf->Ln(10);
$pdf->Cell(180, 4, utf8_decode('Fecha de Emisión:              '.$fecha_firmado), 0, 0, 'L');
$pdf->Ln(4);
$pdf->Cell(180, 4, utf8_decode('Nombre del Acreedor:        TU CELULAR COMERCIALIZADORA S.A.S.        Valor del capital: COP$'.number_format($precio_producto, 0, '.', '.').'='), 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190, 4, utf8_decode('El suscrito '.$deudor.', mayor de edad, vecino(a) de la ciudad de '.$ciudad.', domiciliado(a) en: '.$prospecto_direccion.', identificado(a) como aparece al pie de su firma obrando en este acto en su propio nombre y representación (el “Deudor”); promete de manera incondicional e irrevocable, pagar a la orden de la sociedad TU CELULAR COMRCIALIZADORA S.A.S. identificada con el NIT 901.310.075-9, con domicilio en la ciudad de Cali, Colombia, (el "Acreedor"), o de quien represente sus derechos y que se encuentre debidamente autorizado para recibir el pago, en la ciudad de Cali, Colombia, en la fecha de vencimiento de este pagaré que se indica en el numeral (5) siguiente, en dinero efectivo, las sumas que se consignan en este instrumento por concepto de capital, que el Deudor ha recibido del Acreedor a su entera satisfacción, a título de mutuo comercial con interés bajo la modalidad de consumo, y las demás sumas de dinero de las cuales se haga mención en el presente instrumento'), 0, 'FJ');
$pdf->cell(150, 4, utf8_decode('(el "Préstamo") otorgado al Deudor el '.$fecha_firmado.', de acuerdo con los siguientes términos:'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->MultiCell(190, 4, utf8_decode('1. La suma de ()'), 0, 'FJ');
$pdf->cell(80, 4, utf8_decode('pesos colombianos moneda corriente por concepto de capital.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('2. La suma de __________________________________________________________________________________ ($                                        ) pesos colombianos moneda corriente por concepto de intereses remuneratorios causados y pendientes de pago a partir del día'), 0, 'FJ');
$pdf->cell(80, 4, utf8_decode('___ del mes de _____________ del año _______ y hasta el día _____ del mes de ______________ del año _______.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('3. La suma de __________________________________________________________________________________ ($                                        ) pesos colombianos moneda corriente por concepto de honorarios de cobranza, pago de garantías a fiadores o fondos de garantías, y otros montos,'), 0, 'FJ');
$pdf->cell(80, 4, utf8_decode('causados y no pagados, que deben ser asumidos por el Deudor en relación con el Préstamo.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('4. La suma indicada en el numeral 1 devengará intereses de mora, a partir de la fecha de vencimiento de este pagaré, a la máxima tasa de interés'), 0, 'FJ');
$pdf->cell(100, 4, utf8_decode('moratorio permitida por las normas vigentes en la República de Colombia.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('5. Todos los pagos bajo el presente pagaré deben ser hechos por el Deudor en la ciudad de Cali Colombia con fondos inmediatamente disponibles, libres de impuestos y deducciones, y sin compensación o contrademanda alguna, en la siguiente fecha de vencimiento, la cual se ha establecido'), 0, 'FJ');
$pdf->cell(100, 4, utf8_decode('como vencimiento de este pagaré: día ___ del mes de _____________ del año _______.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('6. El Deudor renuncia irrevocablemente a la presentación, reconvención privada o judicial, protesto, denuncia, reclamación, constitución en mora o cualquier otro tipo de aviso, notificación o requisito adicional de cualquier naturaleza para el cobro de este Pagaré. Así mismo, renuncia al fuero de domicilio, a efecto de lo cual el Acreedor podrá demandar al Deudor en cualquier jurisdicción en donde uno o más de los suscriptores posean activos'), 0, 'FJ');
$pdf->cell(50, 4, utf8_decode('de cualquier clase.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('7. El Deudor, acepta que serán de su cargo los gastos y honorarios profesionales que se generen por la cobranza de este Pagaré.'), 0, 'FJ');
$pdf->MultiCell(190, 4, utf8_decode('8. El Deudor acepta que serán de su cargo todos los impuestos que pueda causar el presente Pagaré, en cualquier jurisdicción, incluyendo, si resultare aplicable, el impuesto de timbre, quedando el Acreedor autorizado para pagarlos por cuenta del Deudor si fuere necesario.'), 0, 'FJ');
$pdf->MultiCell(190, 4, utf8_decode('9. El Deudor acepta expresamente que, en caso de prórroga, novación o modificación de la obligación a su cargo contenida en este Pagaré, el'), 0, 'FJ');
$pdf->cell(50, 4, utf8_decode('presente Pagaré continuará vigente hasta la fecha pactada en dicha prórroga, novación o modificación.'), 0, 0, 'L');
$pdf->Ln(8);
$pdf->MultiCell(190, 4, utf8_decode('10. Este pagaré se encuentra regido por, y debe interpretarse de conformidad con las leyes de la República de Colombia, y el Deudor expresamente declara que las leyes que rigen su creación son las leyes de la República de Colombia, lugar donde ha sido firmado por el Deudor.'), 0, 'FJ');
$pdf->MultiCell(190, 4, utf8_decode('11. Los espacios en blanco de este pagaré deberán llenarse con sujeción a las instrucciones contenidas en la carta de instrucciones que se adjunta.'), 0, 'FJ');
$pdf->Ln(8);
$pdf->Cell(90, 5, utf8_decode('Se firma en '.$ciudad.' en la fecha: '.$fecha_firmado.' (dd/mm/aaaa).'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(50, 5, utf8_decode('El Deudor'), 0, 0, 'L');
$pdf->Ln(15);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50, 5, utf8_decode('______'.$codigo.'______'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(100, 5, utf8_decode($deudor), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(70, 5, utf8_decode('C.C. No. '.$prospecto_cedula.' de '.$expedicion_c.'. '), 0, 0, 'L');
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
$pdf->Cell(40, 5, utf8_decode('______'.$codigo.'______'), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(70, 5, utf8_decode($deudor), 0, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(70, 5, utf8_decode('C.C. No. '.$prospecto_cedula.' de '.$expedicion_c), 0, 0, 'L');
$pdf->Output();
ob_end_flush();