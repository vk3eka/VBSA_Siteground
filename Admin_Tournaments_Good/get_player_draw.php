<?php
require_once('../Connections/connvbsa.php'); 

$tourn_id = $_GET['tourn_id'];
$team_count = $_GET['team_count'];

mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn = 'Select * FROM vbsa3364_vbsa2.tourn_entry LEFT JOIN members on tourn_memb_id=MemberID LEFT JOIN tournaments on tourn_id=tournament_number where tournament_number = ' . $tourn_id;
//echo($query_tourn . "<br>");
$result_tourn = mysql_query($query_tourn, $connvbsa) or die(mysql_error());
$num_rows = $result_tourn->num_rows;
if($num_rows != 0) 
{
  $i = 0;
  while($build_data = $result_tourn->fetch_assoc()) 
  {
    $players[$i] = ((trim($build_data['FirstName'])) . " " . (trim($build_data['LastName']))); 
    $i++;
  }
  for($x = $i; $x < $team_count; $x++) // add byes for non specified players
  {
  	$players[$x] = "Bye"; 
  }
  $player_data = json_encode($players);
  echo($player_data);
}

