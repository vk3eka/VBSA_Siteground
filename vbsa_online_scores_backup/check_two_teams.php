<?php

include("connection.inc");
include("php_functions.php");

$home = $_GET['home'];
$away = $_GET['away'];
$round = $_GET['round'];
$year = $_GET['year'];
$current_season = $_GET['season'];
$team_grade = $_GET['grade'];

// get home team player names
$sql_home = "Select * from tbl_scoresheet where 
team = '" . $home . "' and 
opposition = '" . $away . "' and 
round = " . $round . " and 
year = " . $year . " and 
season = '" . $current_season . "' and 
team_grade = '" . $team_grade . "'";

$result_home = $dbcnx_client->query($sql_home);
$num_rows_home = $result_home->num_rows;

if ($num_rows_home != 0) 
{
  $i = 0;
  while($build_data_home = $result_home->fetch_assoc()) 
  {
    $players_home[$i] = ($build_data_home['players_name']); 
    $i++;
  }
  $player_home = json_encode($players_home);
}

// get away team player names
$sql_away = "Select * from tbl_scoresheet where 
team = '" . $away . "' and 
opposition = '" . $home . "' and 
round = " . $round . " and 
year = " . $year . " and 
season = '" . $current_season . "' and 
team_grade = '" . $team_grade . "'";

$result_away = $dbcnx_client->query($sql_away);
$num_rows_away = $result_away->num_rows;

if(($num_rows_home == 0) || ($num_rows_away == 0))
{
  echo(json_encode(False));
}
else
{
  echo(json_encode(True));
}

?>