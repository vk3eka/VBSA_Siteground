<?php 

include('connection.inc');

$team_id = $_GET['team_id'];
$team_name = $_GET['team_name'];
$club_name = htmlspecialchars($_GET['club_name']);
$team_grade = $_GET['team_grade'];
$memberID = $_GET['member_id'];
$season = $_GET['season'];
$year = $_GET['year'];

echo($team_name . ", " . $team_grade . ", " . $memberID . ", " . $season . ", " . $year . "<br>");
$players = array();

$sql = "Select scrs.scrsID, scrs.scr_season, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.final_sub, scrs.tier, members.MemberID, members.FirstName, members.LastName, Team_entries.team_id, Team_entries.team_name, Team_entries.team_grade, count_played FROM scrs, members, Team_entries WHERE members.MemberID=scrs.MemberID AND Team_entries.team_id=scrs.team_id AND Team_entries.team_id=" . $team_id . " ORDER BY members.FirstName";
//echo($sql . "<br>");
$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;
if ($num_rows != 0) 
{
  $i = 0;
  while($build_data = $result_players->fetch_assoc()) 
  {
    $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $build_data['team_id'] . ", " . $build_data['count_played'] . ", " . $build_data['final_sub'] . ", " . $build_data['scrsID']);
    $i++;
  }
  $player_data = json_encode($players);
  echo($player_data);
}

?>
