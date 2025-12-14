<?php 
require_once('../Connections/connvbsa.php'); 


echo $_POST['surname'];


//$teams = array();
/*
$sql_team = "Select team_id, team_name, team_grade from Team_entries where team_cal_year = " . $_GET['year'] . " and team_grade = '" . $_GET['grade'] . "'";
//echo($sql_team . "<br>");
$result_team = $dbcnx_client->query($sql_team);
$i = 0;
while($build_team = $result_team->fetch_assoc()) 
{
  $teams[$i] = $build_team['team_name']; 
  $i++;
}
$team_data = json_encode($teams);
echo($team_data);
*/
?>
