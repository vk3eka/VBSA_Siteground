<?php

require_once '../vendor/autoload.php';
ob_start();
include 'create_pdf_content.php';
//echo($html_content);

?>
<!DOCTYPE html>
<html>
<body>
  <h1>Team List</h1>
  <div id="data-output">
    <?= $html_content ?>
  </div>
</body>
</html>
<?php
$html = ob_get_clean();

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('filename_vbsa.pdf', \Mpdf\Output\Destination::DOWNLOAD);


//$html = ob_get_clean();
//$mpdf = new \Mpdf\Mpdf();
//header('Content-Type: application/pdf');
?>
<!--<style>

.all {
  border: 1px solid black;
  border-collapse: collapse;
}

.top {
  border-top: 1px solid black;
  border-collapse: collapse;
}

.bottom {
  border-bottom: 1px solid black;
  border-collapse: collapse;
}

.left {
  border-left: 1px solid black;
  border-collapse: collapse;
}

.right {
  border-right: 1px solid black;
  border-collapse: collapse;
}

</style>-->
<?php
/*$html_content = "<table><tr>
    <td colspan='9'>&nbsp;</td>
</tr>
<tr>
    <td colspan='3'>&nbsp;</td>
    <td colspan='2' width='100px' align='center' class='top left'>Semi Finals</td>
    <td width='100px' align='center' class='top right'>28-Feb</td>
    <td colspan='3'>&nbsp;</td>
</tr>
<tr>
    <td colspan='3'>&nbsp;</td>
    <td colspan='2' width='100px' align='center' class='bottom left'>Grand Finals</td>
    <td width='100px' align='center' class='bottom right'>28-Feb</td>
    <td colspan='3'>&nbsp;</td>
</tr>
<tr>
    <td colspan='9'>&nbsp;</td>
</tr></table>";

*/
//include 'create_pdf_content.php';
//$html = file_get_contents('create_pdf_content.php');
//$mpdf->WriteHTML($html);
//$mpdf->Output();
//$mpdf->Output('filename_vbsa.pdf', \Mpdf\Output\Destination::DOWNLOAD);
//$mpdf->Output('filename.pdf', \Mpdf\Output\Destination::FILE);
/*
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Test.pdf"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
header('Cache-Control: private');
header('Pragma: private');
*/
?>