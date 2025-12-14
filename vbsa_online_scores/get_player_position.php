<?php

include("connection.inc");
include("php_functions.php");

$name = $_GET['name'];
$teamgrade = $_GET['team_grade'];
$team_id = $_GET['team_id'];
$date = MySqlDate($_GET['date']);

$sql = "Select * from tbl_scoresheet where players_name = '" . trim($name) . "' and date_played = '" . $date . "' and team_grade = '" . $teamgrade . "' and team_id = " . $team_id;

$result = $dbcnx_client->query($sql);
$rows = array();
$build_data = $result->fetch_assoc();

$rows[0] = $build_data['players_name'];
$rows[1] = $build_data['playing_position'];
$rows[2] = $build_data['date_played'];

echo json_encode($rows);

?>