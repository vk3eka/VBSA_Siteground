<?php

include("connection.inc");
include("php_functions.php");

error_reporting(0);

$home = $_GET['home'];
$away = $_GET['away'];
$round = $_GET['round'];
$year = $_GET['year'];
$current_season = $_GET['season'];
$team_grade = $_GET['grade'];
$title = $_GET['title'];
$type = $_GET['type'];
$player_home = '';
// get scoresheet data
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
//echo($num_rows_home);
if ($num_rows_home != 0) 
{
  $i = 0;
  while($build_data_home = $result_home->fetch_assoc()) 
  {
    if($type == 'Snooker')
    { 
      if(($title == 'Semi Final') || ($title == 'Grand Final'))
      {
        $players_home[$i] = $build_data_home['players_name'] . ", " . $build_data_home['score_1'] . ", " . $build_data_home['score_2'] . ", " . $build_data_home['score_3'] . ", " . $build_data_home['score_4'] . ", " . $build_data_home['break_1'] . ", " . $build_data_home['break_2'] . ", " . $build_data_home['break_3'] . ", " . $build_data_home['break_4'];
      }
      else
      {
        $players_home[$i] = $build_data_home['players_name'] . ", " . $build_data_home['score_1'] . ", " . $build_data_home['score_2'] . ", " . $build_data_home['score_3'] . ", " . $build_data_home['break_1'] . ", " . $build_data_home['break_2'] . ", " . $build_data_home['break_3'];
      }
    }
    else if($type == 'Billiards')
    {
      $players_home[$i] = $build_data_home['players_name'] . ", " . $build_data_home['score_1'] . ", " . $build_data_home['break_1'] . ", " . $build_data_home['tier'];
    }
    $i++;
  }
  $player_home = json_encode($players_home);
}

echo($player_home);

?>