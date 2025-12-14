<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$team = $_GET['team'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];

$sql = "Select distinct players_name, lastname FROM tbl_scoresheet where season = '". trim($current_season) . "' and year = " . $current_year . " and team_grade = '" . trim($team_grade) . "' and team = '" . trim($team) . "' order by lastname";
$result_player = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$player = [];
while($build_player = $result_player->fetch_assoc()) 
{
    $player[$i] = $build_player['players_name'];
	$i++;
}
$player_data = json_encode($player);
echo($player_data);

?>