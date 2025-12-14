<?php 

include('header.php'); 
include('connection.inc'); 

echo("<div id='form'>");
echo('<table class="table table-striped table-bordered dt-responsive display text-center" class="col-6">
<tr>
    <td colspan=20 align="center"><b>Analysis Data</td>
</tr>
<tr>
    <td rowspan=2 align="center">Grade</td>
    <td rowspan=2 align="center">Club/Team</td>
    <td colspan=18 align="center">Round</td>
</tr>
<tr>
    <td align="center">1</td>
    <td align="center">2</td>
    <td align="center">3</td>
    <td align="center">4</td>
    <td align="center">5</td>
    <td align="center">6</td>
    <td align="center">7</td>
    <td align="center">8</td>
    <td align="center">9</td>
    <td align="center">10</td>
    <td align="center">11</td>
    <td align="center">12</td>
    <td align="center">13</td>
    <td align="center">14</td>
    <td align="center">15</td>
    <td align="center">16</td>
    <td align="center">17</td>
    <td align="center">18</td>
</tr>');
$x = 0;
//while($build_data_club = $result_club_display->fetch_assoc())
//{
echo('
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">GPC Geelong</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Yarraville Breath Hackers</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Brunswick Mafia</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Yarrville Thunder</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">BVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">North Brighton Whirlwinds</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Dandenong RSL Green</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Frankston RSL Sea Side</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Camberwell Cobras</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
<tr>
    <td align="center">CVS1</td>
    <td align="center">' . $x . '</td>
    <td align="center">Cheltenham Legends</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
    <td align="center">' . $x . '</td>
</tr>
');
$x++;
//}


echo("</table>");
echo("</div>");
?>

