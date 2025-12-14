<?php 

include('connection.inc');

$season = $_GET['season'];
$season_data = explode(",", $season);
$current_year = $season_data[0];
$current_season = $season_data[1];

$sql = "Select distinct team_grade, grade, type, dayplayed FROM tbl_fixtures  where season = '". trim($current_season) . "' and year = ". $current_year . " order by team_grade";
$result_grade = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$i = 0;
$grade = [];
while($build_grade = $result_grade->fetch_assoc()) 
{
    $grade[$i] = $build_grade['team_grade'];
	$i++;
}
$grade_data = json_encode($grade);
echo($grade_data);

?>