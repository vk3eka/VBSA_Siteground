<?php 

include('connection.inc');

$season = $_GET['season'];
$team_grade = $_GET['grade'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];

// LEFT JOIN tbl_scoresheet on tbl_scoresheet.team = tbl_fixtures.fix1home 

//$sql = "Select distinct fix1home From tbl_fixtures where season = '". trim($current_season) . "' and year = " . $current_year . "  and team_grade = '" . trim($team_grade) . "' order by fix1home";

$sql = "Select * FROM Team_entries where team_season = '". trim($current_season) . "' and team_cal_year = " . $current_year . " and team_grade = '" . $team_grade . "' Order by team_name";

//echo($sql . "<br>");
$result_team = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$team = [];
while($build_team = $result_team->fetch_assoc()) 
{
    $team[$i] = $build_team['team_name'];
	$i++;
}
$team_data = json_encode($team);
echo($team_data);

?>