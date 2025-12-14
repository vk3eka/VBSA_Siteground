<?php 

include('connection.inc');

$team_name = $_GET['team_name'];
$team_grade = $_GET['team_grade'];
$year = date("Y");

$players = array();

// get team id
$sql_team = "Select team_club_id, team_id, team_name, team_grade from Team_entries where team_name = '" . $team_name . "' and team_cal_year = " . $year . " and team_grade = '" . $team_grade . "'";
//echo($sql_team . "<br>");
$result_team = $dbcnx_client->query($sql_team);
$build_team = $result_team->fetch_assoc();
$team_id = $build_team['team_id'];
//echo("Team ID " . $team_id . "<br>");

if($team_id == '')
{
  return false;
}

// get players
$sql = "Select scrs.scrsID, scrs.scr_season, scrs.MemberID, scrs.team_grade, scrs.captain_scrs, scrs.team_id, scrs.selected, scrs.final_sub, scrs.tier, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade, count_played FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=" . $team_id . " and scr_season = 'S2' and current_year_scrs = 2024 ORDER BY members.FirstName";

//echo($sql . "<br>");
$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;
if ($num_rows != 0) 
{
  $i = 0;
  while($build_data = $result_players->fetch_assoc()) 
  {
    $tier = 0;
    $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['members.MemberID'] . ", " . $build_data['Team_entries.team_id'] . ", " . $build_data['count_played'] . ", " . $build_data['final_sub'] . ", " . $tier . ", " . $build_data['scrsID'] . ", " . $build_data['selected'] . ", " . $build_data['captain_scrs']);
    $i++;
  }
  $player_data = json_encode($players);
  echo($player_data);
}

?>