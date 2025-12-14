<?php 
include('../vbsa_online_scores/connection.inc');

$sql = "Select allocated_rp, scrsID from scrs where current_year_scrs > '2020-01-01' and game_type = 'Billiards'";
//echo("SCRS Select " . $sql . "<br>");
$result = $dbcnx_client->query($sql);

while($build_data = $result->fetch_assoc()) 
{
	$sql_update = "Update scrs Set allocated_rp = (allocated_rp*2) where scrsID = " . $build_data['scrsID'];
    //echo("SCRS Update " . $sql_update . "<br>");
   	$update = $dbcnx_client->query($sql_update);
}

return "Updated";
