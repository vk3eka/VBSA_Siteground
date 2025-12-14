<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$team = $_GET['team'];
$player = $_GET['player'];
$sort = $_GET['sort'];
$sort_order = $_GET['sort_order'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];

$sql_part = " and team_grade = '" . trim($team_grade) . "' and team = '" . trim($team) . "' and players_name = '" . trim($player) . "'";
if(($team_grade == 'all') || ($team_grade == ''))
{
	$sql_part = " ";
}
elseif(($team == 'all') || ($team == ''))
{
	$sql_part = " and team_grade = '" . trim($team_grade) . "'";
}
elseif(($player == 'all') || ($player == ''))
{
	$sql_part = " and team_grade = '" . trim($team_grade) . "' and team = '" . trim($team) . "' ";
}
if($sort != '')
{
	$sql_sort = " order by " . $sort . " " . $sort_order;
}
else
{
	$sql_sort = "";
}
$sql = "Select * FROM tbl_scoresheet where season = '". trim($current_season) . "' and year = " . $current_year . " " . $sql_part . $sql_sort;

$result_player = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$player = [];
while($build_player = $result_player->fetch_assoc()) 
{
    $player[$i] = $build_player['players_name'] . ", " . $build_player['playing_position'] . ", " . $build_player['team'] . ", " . $build_player['year'] . ", " . $build_player['season'] . ", " . $build_player['team_grade'] . ", " . $build_player['round'] . ", " . $build_player['date_played'] . ", " . $build_player['win_1'] . ", " . $build_player['win_2'] . ", " . $build_player['win_3'] . ", " . $build_player['win_4'] . ", " . $build_player['score_1'] . ", " . $build_player['score_2'] . ", " . $build_player['score_3'] . ", " . $build_player['score_4'] . ", " . $build_player['break_1'] . ", " . $build_player['break_2'] . ", " . $build_player['break_3'] . ", " . $build_player['break_4'];
	$i++;
}
$player_data = json_encode($player);
echo($player_data);

?>