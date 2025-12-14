<?php 

include('connection.inc');

$team_name = $_GET['team_name'];
$team_grade = $_GET['team_grade'];
$year = $_GET['year'];


$players = array();

// get team id
$sql_team = "Select team_club_id, team_id, team_name, team_grade from Team_entries where team_name = '" . $team_name . "' and team_cal_year = " . $year . " and team_grade = '" . $team_grade . "'";
//echo($sql_team . "<br>");
$result_team = $dbcnx_client->query($sql_team);
$build_team = $result_team->fetch_assoc();
$team_id = $build_team['team_id'];

if($team_id == '')
{
  return false;
}

$sql_club = "Select * from tbl_team_rego where team_grade = '" . $team_grade . "' and team_name = '" . $team_name . "' and selected = 1";
//echo($sql_club . "<br>");
$result_club_players = $dbcnx_client->query($sql_club);
$i = 0;
while($build_club_data = $result_club_players->fetch_assoc()) 
{
  $tier = 0;
  $players[$i] = ((trim($build_club_data['firstname'])) . ", " . (trim($build_club_data['lastname'])) . ", " . $build_club_data['member_id'] . ", " . $team_id . ", " . $build_club_data['captain_scrs'] . ", No, " . $tier . ", " . $build_club_data['id'] . ", " . $build_club_data['selected']);
  $i++;
}
$player_data = json_encode($players);
echo($player_data);


?>