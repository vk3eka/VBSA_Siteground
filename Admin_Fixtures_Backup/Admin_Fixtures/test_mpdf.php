<?php

require_once "../vendor/autoload.php";

$mpdf = new \Mpdf\Mpdf();
$html = file_get_contents('create_pdf_content.php');
$mpdf->WriteHTML($html);
$mpdf->Output();

?>
