<?php

if (!isset($_SESSION)) 
{
  session_start();
}

error_reporting(0);

$player_avg = ($_SESSION['HeaderData']);
$result = ($_SESSION['PlayerData']);

$player = explode(', ', $player_avg);

$top_player_headers = 
['All Players', 
'', 
'',
'|-----',
'------',
'------',
'A Grade',
'------',
'-----|',

'|-----',
'------',
'------',
'B Grade',
'------',
'-----|',
'|-----',
'------',
'------',
'C Grade',
'------',
'-----|'];

$player_headers = 
['', 
'',
'',
'Won (A Grade)',
'Played (A)',
'% Won (A)',
'Ave Pos (A)',
'Strength Index (A)',
'Style',
'% Variation (A)',
'Won (B Grade)',
'Played (B)',
'% Won(B)',
'Ave Pos(B)',
'Strength Index(B)',
'Style',
'% Variation(B)',
'Won (C Grade)',
'Played (C)',
'% Won (C)',
'Ave Pos (C)',
'Strength Index (C)',
'Style',
'% Variation (C)'];

$player_headers_data = 
['All Players', 
'',
'',
'',
'',
number_format($player[0],2),
number_format($player[1],2),
number_format($player[2],2),
'',
'',
'',
'',
number_format($player[3],2),
number_format($player[4],2),
number_format($player[5],2),
'',
'',
'',
'',
number_format($player[6],2),
number_format($player[7],2),
number_format($player[8],2),
'',
'',
''];

$top_headers = 
['', 
'', 
'',
'|-----',
'------',
'------',
'A Grade',
'------',
'------',
'-----|',

'|-----',
'------',
'------',
'B Grade',
'------',
'------',
'-----|',
'|-----',
'------',
'------',
'C Grade',
'------',
'------',
'-----|'];

$headers = 
['Grade', 
'Team Name', 
'Player Name',
'Won',
'Played',
'% Won',
'Ave Pos',
'Strength Index',
'Style',
'% Variation',
'Won',
'Played',
'% Won',
'Ave Pos',
'Strength Index',
'Style',
'% Variation',
'Won',
'Played',
'% Won',
'Ave Pos',
'Strength Index',
'Style',
'% Variation'];

$fp = fopen('php://output', 'w');
if ($fp && $result) 
{
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="TeamAssessment.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    //fputcsv($fp, array_values($top_player_headers));
    fputcsv($fp, array_values($player_headers));
    fputcsv($fp, array_values($player_headers_data));
    
    //fputcsv($fp, array_values($top_headers));
    //fputcsv($fp, array_values($headers));
    foreach($result as $row) 
    {
        fputcsv($fp, array_values($row));
    }
    fclose($fp);
}
else
{
    echo("Nothing here<br>");
}

?>