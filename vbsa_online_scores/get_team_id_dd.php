<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];

$sql = "Select distinct team_name, scrs.team_id FROM scrs LEFT JOIN members on members.MemberID = scrs.MemberID LEFT JOIN Team_entries on Team_entries.team_id = scrs.team_id where scr_season = '" . trim($current_season) . "' and current_year_scrs = " . $current_year . " and members.MemberID != 1 and scrs.team_grade = '" . $team_grade . "'";
//echo($sql . "<br>");
$result_team = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$team = [];
while($build_team = $result_team->fetch_assoc()) 
{
    $team[$i] = $build_team['team_id'] . ' - ' . $build_team['team_name'];
	$i++;
}
$team_data = json_encode($team);
echo($team_data);

?>