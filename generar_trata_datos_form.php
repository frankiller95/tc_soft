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

$id_trata = $_GET['id'];

$hora_actual = date('Ymd');

$nom_establecimiento = "TU CELULAR COMERCIALIZADA S.A.S";

$query = "SELECT `cedula`, DATE_FORMAT(fecha_exp, '%m-%d-%Y') AS exp_fecha, `nombre_apellidos`, `direccion_ciudad`, `telefono_contacto`, `trabajo_ciudad`, `telefono_trabajo`, `cargo`, `salario`, `antiguedad`, `referencia1`, `referencia2`, `referencia3`, `referencia4`, `telefono_r1`, `telefono_r2`, `telefono_r3`, `telefono_r4`, `id_modelo_compra`, `nombre_modelo`,`marca_producto`, `cuota_inicial`, `cuotas_numero`, `valor_cuota`, `valor_total`, `codigo`, `clave` FROM `form_tratamiento_datos` LEFT JOIN modelos ON form_tratamiento_datos.id_modelo_compra = modelos.id LEFT JOIN marcas ON modelos.id_marca = marcas.id WHERE form_tratamiento_datos.id = $id_trata";

$result = qry($query);
while ($row = mysqli_fetch_array($result)) {

	$cedula = $row['cedula'];
	$fecha_exp = $row['exp_fecha'];
	$nombre_apellidos = $row['nombre_apellidos'];
	$direccion_ciudad = $row['direccion_ciudad'];
	$telefono_contacto = $row['telefono_contacto'];
	$trabajo_ciudad = $row['trabajo_ciudad'];
	$telefono_trabajo = $row['telefono_trabajo'];
	$cargo = $row['cargo'];
	$salario = $row['salario'];
    $salario = number_format($salario, 0, '.', '.');
	$antiguedad = $row['antiguedad'];
	$referencia1 = $row['referencia1'];
	$referencia2 = $row['referencia2'];
	$referencia3 = $row['referencia3'];
    $referencia4 = $row['referencia4'];
    $telefono_r1 = $row['telefono_r1'];
    $telefono_r2 = $row['telefono_r2'];
    $telefono_r3 = $row['telefono_r3'];
    $telefono_r4 = $row['telefono_r4'];
	$id_modelo_compra = $row['id_modelo_compra'];
	$nombre_modelo = $row['nombre_modelo'];
    $marca_producto = $row['marca_producto'];
    $cuota_inicial = $row['cuota_inicial'];
    $cuota_inicial = number_format($cuota_inicial, 0, '.', '.');
    $cuotas_numero = $row['cuotas_numero'];
    $valor_cuota = $row['valor_cuota'];
    $valor_cuota = number_format($valor_cuota, 0, '.', '.');
    $valor_total = $row['valor_total'];
    $valor_total = number_format($valor_total, 0, '.', '.');
    $codigo = $row['codigo'];
    $clave = $row['clave'];
}

ob_start();

class PDF extends FPDF
{



    function Header()
    {
    //Rect(float x, float y, float w, float h [, string style])
      $this->Rect(30, 20, 150, 250, 'D'); //For A4
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


    function Cell($w, $h=0, $txt='', $border=1, $ln=0, $align='', $fill=false, $link='')
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
$pdf->SetTopMargin(15);
$pdf->SetFont('Arial','B',8);
//Rect(float x, float y, float w, float h [, string style])
//$this->Rect(30, 25, 150, 250, 'D'); //For A4
// SetMargins(float left, float top [, float right])
$pdf->Ln(10);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 10, utf8_decode('NOMBRE DEL ESTABLECIMIENTO:'), 1, 0, 'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(75, 10, utf8_decode($nom_establecimiento), 1, 0, 'L');
$pdf->Ln(10);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(75, 10, utf8_decode('CÓDIGO Y CLAVE:'), 0, 0, 'C');
$pdf->Cell(75, 10, utf8_decode($codigo.' '.$clave), 0, 0, 'C');
$pdf->Ln(10);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(150, 10, utf8_decode('NOMBRE Y TELÉFONO DEL ASESOR:'), 1, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(150, 10, utf8_decode('AVAL O COMPRA DE CARTERA'), 1, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('DATOS'), 1, 0, 'C');
$pdf->Cell(75, 5, utf8_decode('PAGARES Y CHEQUES'), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','',8);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('# CÉDULA DEL DEUDOR'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($cedula), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('FECHA DE EXPEDICIÓN DE LA CEDULA'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($fecha_exp), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('NOMBRES Y APELLIDOS COMPLETOS'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($nombre_apellidos), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('DIRECCIÓN DE LA CASA Y CIUDAD'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($direccion_ciudad), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('TELÉFONO CASA'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($telefono_contacto), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('DIRECCIÓN DEL TRABAJO Y CIUDAD'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($trabajo_ciudad), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('TELÉFONO TRABAJO'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($telefono_trabajo), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('CARGO -SALARIO - ANTIGUEDAD'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($cargo.' -'.$salario.' -'.$antiguedad), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('QUE COMPRA?'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($marca_producto.' '.$nombre_modelo), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('CUOTA INICIAL?'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($cuota_inicial), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('A CUANTAS CUOTAS?'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($cuotas_numero), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('VALOR DE CADA CUOTA'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($valor_cuota), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('VALOR TOTAL CON SIMULADOR'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($valor_total), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('REFERENCIA FAMILIAR # 1'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($referencia1), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('TELEFONO # 1'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($telefono_r1), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('REFERENCIA FAMILIAR # 2'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($referencia2), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('TELEFONO # 2'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($telefono_r2), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('REFERENCIA PERSONAL # 1'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($referencia3), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('TELEFONO # 1'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($telefono_r3), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('REFERENCIA PERSONAL # 2'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($referencia4), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('TELEFONO # 2'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode($telefono_r4), 1, 0, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(150, 10, utf8_decode('INFORMACIÓN ADICIONAL CON CHEQUES'), 1, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',8);
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('CUENTA CORRIENTE DEL CHEQUE'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode(''), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('BANCO Y SUCURSAL'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode(''), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(75, 5, utf8_decode('NUMERO DE CHEQUES, FECHA Y VALOR'), 1, 0, 'L');
$pdf->Cell(75, 5, utf8_decode(''), 1, 0, 'C');
$pdf->Ln(5);
$pdf->Cell(20, 3, utf8_decode(''), 0, 0, 'L');
$pdf->SetFont('Arial','', 8);
$pdf->Cell(150, 3, utf8_decode('Autorización, consulta y Reporte de información'), 0, 0, 'C');
$pdf->Ln(3);
$pdf->Cell(20, 3, utf8_decode(''), 0, 0, 'L');
$pdf->SetFont('Arial','', 6);
$pdf->MultiCell(150, 3, utf8_decode('El(los) abajo firmante(s) actuando en nombre propio y/o en representación de la persona juridica, autorizo (amos) a CREDIAVALES SAS o a quien represente sus derechos. de manera irrevocable, escrita, expresa, concreta, suficiente, voluntaria e informada para que toda la información personal comercial, financiera actual y la que genere en el futuro. fruto de las relaciones comerciales y/o contractuales establecidas con CREDIAVALES o con sus afiliados, referentes a mi (nuestro) comportamiento(s) financiero crediticio, origen de fondos, comercial y de servicios que exista o pueda existir en bases de datos, centrales de riesgo de información nacionales o extranjeras, especialmente aquella referida al nacimiento, ejecución y extinción del obligaciones que directa o indirectamente tengan carácter o nerarias, independientemente de la naturaleza del contracto que los de origen, sea administrada, capturada, procesada, operada, verificada, transmitida, usada o puesta en circulación y consultada igualmente autorizamos a CREDIAVALES a entregar esa información de forma verbal o escrita, poner a disposición de terceras personas, autoridades administrativas y judiciales que lo requieran organos de control y demás dependencias de investigación disciplinaria, siempre que medie orden de autoridad competente ( ley de habeas data 1266/2008)'), 0, 'FJ');
$pdf->Ln(2);
$pdf->Cell(20, 3, utf8_decode(''), 0, 0, 'L');
$pdf->MultiCell(150, 3, utf8_decode('Bajo la gravedad de juramento cerifico (amos) que los datos personales por mi (nosotros) suministrados son veraces, completos y exactos, actualizados y comprobables. por tanto, cualquier error en la información suministrada por mi sera de mi (nuestra) unica y exclusiva responsabilidad y que saldre a'), 0, 'FJ');
$pdf->Cell(20, 3, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(150, 3, utf8_decode('responder ante la por o frente a cualquier inconsistencia aqui consignada.'), 0, 0, 'C');
$pdf->Ln(5);
//Image(string file [, float x [, float y [, float w [, float h [, string type [, mixed link]]]]]])
$pdf->Image('documents/firmas/'.$id_trata.'/signature-'.$id_trata.'.png', 85, 245, 33);
$pdf->Ln(27);
$pdf->Cell(40, 5, utf8_decode(''), 0, 0, 'L');
$pdf->Cell(100, 5, utf8_decode('FIRMA DEL DEUDOR'), 0, 0, 'C');
$pdf->Output();
ob_end_flush();