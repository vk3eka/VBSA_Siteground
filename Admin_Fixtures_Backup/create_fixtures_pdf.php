<?php 
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

require('../fpdf/fpdf.php');

$team_grade = $_GET['Team_Grade'];
$year = $_GET['Year'];
$season = $_GET['Season'];
$dayplayed = $_GET['DayPlayed'];
$rounds = $_GET['Rounds'];

/*
echo($year . "<br>");
echo($season . "<br>");
echo($team_grade . "<br>");
echo($dayplayed . "<br>");
*/

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../images/image001.png',10,6,30);
        $this->SetFont('Arial','B',15);
        $title1 = "Fixture List List - " . $_GET['Team_Grade'];
        $this->Cell(0,10,$title1,0,0,'C');
        $this->Ln(20);
        $title2 = $_GET['Season'] . ' - ' . $_GET['Year'];
        $this->Cell(0,10,$title2,0,0,'C');
        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function ImprovedTable($title_block, $date_block, $header, $data)
    {
        $this->SetFont('Arial','B',9);
        $this->Cell(175,8,$title_block,1,0,'C');
        $this->Ln();
        $this->Cell(175,8,$date_block,1,0,'C');
        $this->Ln();
        $w = array(80, 15, 80);
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],8,$header[$i],1,0,'C');
        $this->Ln();
        $this->SetFont('Arial','',9);
        foreach($data as $row)
        {
            foreach($row as $col) {
                $this->Cell($w[0],6,$col[0],1,0,'C');
                $this->Cell($w[1],6,$col[1],1,0,'C');
                $this->Cell($w[2],6,$col[2],1,0,'C');
                $this->Ln();
            }
        }
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$header = array('Home', 'v', 'Away');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();

//echo("Rounds " . $rounds . "<br>");
for($round = 0; $round < $rounds; $round++)
{
    $sql = "Select * from tbl_fixtures where year  = " . $year . " AND season = '" . $season . "' AND team_grade = '" . $team_grade . "' AND round = " . ($round+1);

    $result_select_member = mysql_query($sql, $connvbsa) or die(mysql_error());
    $num_rows = $result_select_member->num_rows;
    $title_block = 'Round ' . ($round+1);
    $row_data = $result_select_member->fetch_assoc();
    $date_block = 'Date ' . $row_data['date'];
    for($j = 0; $j < 7; $j++) 
    {
        if($row_data['fix' . ($j+1) . 'home'] != '')
        {
            $data[$j] = array(array($row_data['fix' . ($j+1) . 'home'], 'v', $row_data['fix' . ($j+1) . 'away']));
        }
    }
    $pdf->ImprovedTable($title_block, $date_block, $header, $data);

//echo("<pre>");
//echo(var_dump($data));
//echo("</pre>");

    if($round % 4)
    {
        //$pdf->AddPage();
    }
    $pdf->Ln(); 
}

$pdf->Output();   

?>