<?

require_once dirname(__FILE__).'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;


$content = '<h1>test</h1>';

$html2pdf = new Html2Pdf('P', 'mm', 'A4', 'fr', 'true', 'UTF-8');
$html2pdf->writeHTML($content);
$html2pdf->output();

?>




