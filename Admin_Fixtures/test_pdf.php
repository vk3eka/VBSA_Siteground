<?php
/*
require_once('../vendor/setasign/fpdf/src/autoload.php');

use \setasign\Fpdi\Fpdi;

require_once('../fpdf/fpdf.php');

//$file1 = $current_year . "_" . $current_season . "_" . $dayplayed . "_" . $team_grade . '.pdf';
$file2 = "Pennant_Dress_Code.pdf";
$file3 = "Pennant_Rules.pdf";

// define some files to concatenate
$files = array(
    $file3,
    $file2
);

// initiate FPDI
$pdf = new Fpdi();

// iterate through the files
foreach ($files AS $file) {
    // get the page count
    $pageCount = $pdf->setSourceFile($file);
    // iterate through all pages
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        // import a page
        $templateId = $pdf->importPage($pageNo);
        // get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);

        // add a page with the same orientation and size
        $pdf->AddPage($size['orientation'], $size);

        // use the imported page
        $pdf->useTemplate($templateId);

        $pdf->SetFont('Helvetica');
        $pdf->SetXY(5, 5);
        $pdf->Write(8, 'A simple concatenation demo with FPDI');
    }
}

// Output the new PDF
$pdf->Output();
*/
?>
<?php
// require composer autoload
require '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();

$url = urldecode($_REQUEST['url']);

// To prevent anyone else using your script to create their PDF files
if (!preg_match('@^https?://172.16.10.32/@', $url)) {
    die("Access denied");
}

// For $_POST i.e. forms with fields
if (count($_POST) > 0) {

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1 );

    foreach($_POST as $name => $post) {
      $formvars = array($name => $post . " \n");
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $formvars);
    $html = curl_exec($ch);
    curl_close($ch);

} elseif (ini_get('allow_url_fopen')) {
    $html = file_get_contents($url);

} else {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , 1 );
    $html = curl_exec($ch);
    curl_close($ch);
}

$mpdf = new \Mpdf\Mpdf();

$mpdf->useSubstitutions = true; // optional - just as an example
$mpdf->SetHeader($url . "\n\n" . 'Page {PAGENO}');  // optional - just as an example
$mpdf->CSSselectMedia='mpdf'; // assuming you used this in the document header
$mpdf->setBasePath($url);
$mpdf->WriteHTML($html);

$mpdf->Output();

?>
