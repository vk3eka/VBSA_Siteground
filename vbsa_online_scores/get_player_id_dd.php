<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];
$team = $_GET['team'];
$team_data = explode(",", $team);
$team_id = $team_data[0];
$team_name = $team_data[1];

if($team_grade == 'all')
{
	$sql = "Select * FROM scrs LEFT JOIN members on members.MemberID = scrs.MemberID where scr_season = '". trim($current_season) . "' and current_year_scrs = " . $current_year  . " and scrs.MemberID != 1 order by LastName";
}
else
{
	$sql = "Select * FROM scrs LEFT JOIN members on members.MemberID = scrs.MemberID where scr_season = '". trim($current_season) . "' and current_year_scrs = " . $current_year  . " and team_grade = '" . trim($team_grade) . "' 
		and scrs.team_id = '" . $team_id . "' and scrs.MemberID != 1 order by LastName";
}
//echo($sql . "<br>");
$result_player = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$player = [];
while($build_player = $result_player->fetch_assoc()) 
{
    $player[$i] = $build_player['FirstName'] . ' ' . $build_player['LastName'] . ' - ' . $build_player['MemberID'];
	$i++;
}
$player_data = json_encode($player);
echo($player_data);

?>

