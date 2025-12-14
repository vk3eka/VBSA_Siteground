<?php

include('connection.inc');

$team_grade = $_GET['team_grade'];
$team_name = $_GET['team_name'];

$sql = "Select * From tbl_team_rego where team_grade = '" . $team_grade . "' and team_name = '" . $team_name . "'";
$result = $dbcnx_client->query($sql);
$rows_existing = $result->num_rows;

if($rows_existing > 0)
{
	// delete existing temp data
	$sql_delete = "Delete From tbl_team_rego where team_grade = '" . $team_grade . "' and team_name = '" . $team_name . "'";
	$update = $dbcnx_client->query($sql_delete);
}

?>