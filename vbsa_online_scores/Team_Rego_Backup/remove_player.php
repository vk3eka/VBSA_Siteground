<?php 

include('connection.inc');

$id = $_GET['scrs_id'];
//$team_grade = $_GET['team_grade'];
//$season = $_GET['season'];
//$year = $_GET['year'];

//echo("ID " . $id . "<br>");
$sql = "Delete FROM scrs WHERE scrsID = " . $id;
//$sql = "Delete FROM scrs WHERE scrsID = " . $id . " and team_grade = '" . $team_grade . "' and current_year_scrs = " . $year . " and scr_season = '" . $season . "'";
//echo($sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update )
{
    die("Could not remove player: " . mysqli_error($dbcnx_client));
}
		
?>