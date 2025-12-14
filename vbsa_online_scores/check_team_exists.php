<?php 

include('connection.inc');

$team_name = $_GET['team_name'];
$season = $_GET['season'];
$year = $_GET['year'];

$sql = "Select distinct team_name FROM vbsa3364_vbsa2.Team_entries where team_season = '" . $season . "' and team_cal_year = " . $year . " order by team_name";
$result = $dbcnx_client->query($sql);
$team_array = [];
$x = 0;
while($build_data = $result->fetch_assoc())
{
	$team_array[$x] = $build_data['team_name'];
	$x++;
}

if(in_array($team_name, $team_array))
{
	$caption = 'true';
}
else
{
	$caption = 'false';
}
echo($caption);
?>