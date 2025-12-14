<?php 

include('connection.inc');

$team_grade = $_GET['TeamGrade'];
$club_name = htmlspecialchars($_GET['clubname']);
$year = date("Y");
$players = array();
$sql_club = "Select ClubNumber from clubs where ClubTitle = '" . html_entity_decode($club_name) . "'";
//echo($sql_club . "<br>");
$result_club = $dbcnx_client->query($sql_club);
$build_club = $result_club->fetch_assoc();
$club_id = $build_club['ClubNumber'];
//echo("Club ID " . $club_id . "<br>");
$sql = "Select scrs.scrsID, scrs.MemberID, scrs.selected, scrs.captain_scrs, LastName, FirstName FROM Team_entries, scrs, members WHERE team_club_id = " . $club_id . " AND scrs.team_id = Team_entries.team_id AND scrs.MemberID = members.MemberID AND team_cal_year >= curdate() - interval 1 year AND (scrs.MemberID !=1 AND scrs.MemberID !=10 AND scrs.MemberID !=100 AND scrs.MemberID !=1000) GROUP BY members.MemberID ORDER BY LastName, FirstName";
//echo($sql . "<br>");

$team_id = 0;

$result_players = $dbcnx_client->query($sql);
$num_rows = $result_players->num_rows;
if ($num_rows != 0) 
{
  $i = 0;
  while($build_data = $result_players->fetch_assoc()) 
  {
    $tier = 0;
    $players[$i] = ((trim($build_data['FirstName'])) . ", " . (trim($build_data['LastName'])) . ", " . $build_data['MemberID'] . ", " . $team_id . ", " . $build_data['captain_scrs'] . ", No, " . $tier . ", " . $build_data['scrsID'] . ", " . $build_data['selected']);
    $i++;
  }
  $player_data = json_encode($players);
  echo($player_data);
}

?>