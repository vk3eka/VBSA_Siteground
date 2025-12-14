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
team_grade = '" . $team_grade . "'
order By playing_position";
//echo($sql_home . "<br>");
$result_home = $dbcnx_client->query($sql_home);
$num_rows_home = $result_home->num_rows;

if ($num_rows_home != 0) 
{
  $i = 0;
  while($build_data_home = $result_home->fetch_assoc()) 
  {
    if($build_data_home['type'] == 'Billiards')
    {
      $players_home[$i] = ($build_data_home['players_name'] . " (" . $build_data_home['tier'] . ")"); 
    }
    else
    {
      $players_home[$i] = ($build_data_home['players_name']); 
    }
    
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
team_grade = '" . $team_grade . "'
order By playing_position";

$result_away = $dbcnx_client->query($sql_away);
$num_rows_away = $result_away->num_rows;

if ($num_rows_away != 0) 
{
  $i = 0;
  while($build_data_away = $result_away->fetch_assoc()) 
  {
    if($build_data_away['type'] == 'Billiards')
    {
      $players_away[$i] = ($build_data_away['players_name'] . " (" . $build_data_away['tier'] . ")"); 
    }
    else
    {
      $players_away[$i] = ($build_data_away['players_name']); 
    }
    $i++;
  }
  $player_away = json_encode($players_away);
}

$players_all = array_merge($players_home, $players_away);
echo(json_encode($players_all));

?>