<?php 

include('connection.inc');

$team_name = $_GET['team_name'];
$season = $_GET['season'];
$year = $_GET['year'];
$team_grade = $_GET['team_grade'];

$sql_existing = "Select * FROM vbsa3364_vbsa2.scrs where scr_season = '" . $season . "' and current_year_scrs = " . $year . " and team_grade = '" . $team_grade . "'";
$result_existing = $dbcnx_client->query($sql_existing);
$existing = $result_existing->num_rows;
/*

if($existing == 0)
{
	$sql = "Select distinct team_name FROM vbsa3364_vbsa2.Team_entries order by team_name";
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
		// check if team name is already allocated
		$sql_team = "Select * FROM vbsa3364_vbsa2.scrs where scr_season = '" . $season . "' and current_year_scrs = " . $year . " and team_grade = '" . $team_grade . "' and team_name = '" . $team_name . "'";
		$result_team = $dbcnx_client->query($sql_team);
		$team = $result_team->num_rows;
		if($team > 0)
		{
			echo 'false';
			return;
		}
		else
		{
			echo 'true';
			return;
		}
	    
	}
	else
	{
		echo 'false';
		return;
	}
}
else
{
	echo 'false';
	return;
}
*/

echo('false'); // added for testing
?>