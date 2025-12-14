<?php

include("connection.inc");
include("php_functions.php");

$home = $_GET['home'];
$away = $_GET['away'];
$round = $_GET['round'];
$year = $_GET['year'];
$current_season = $_GET['season'];
$team_grade = $_GET['grade'];
$title = $_GET['title'];

function trim_value(&$value) 
{ 
    $value = trim($value); 
}

// get home team player data
$sql_home = "Select * from tbl_scoresheet where 
team = '" . $home . "' and 
opposition = '" . $away . "' and 
round = " . $round . " and 
year = " . $year . " and 
season = '" . $current_season . "' and 
team_grade = '" . $team_grade . "'
order by playing_position";
$result_home = $dbcnx_client->query($sql_home);
$num_rows_home = $result_home->num_rows;
//echo($sql_home);
$players_home = array();
if ($num_rows_home != 0) 
{
  $i = 0;
  while($build_data_home = $result_home->fetch_assoc()) 
  {
    if($build_data_home['type'] == 'Billiards')
    {
      // billiards
      $players_home[$i] = $players_home[$i] . "" .
                          $build_data_home['players_name'] . "" . $build_data_home['tier'] . "" .
                          $build_data_home['billiard_stick'] . "" .
                          $build_data_home['break_1'] . ",";
    }

    // snooker
    if(($build_data_home['type'] == 'Snooker') && ($i == 0) && (($title == 'Semi Final') || ($title == 'Grand Final')))
    {
      $players_home[$i] = $build_data_home['players_name'];
      for ($j = 0; $j < 4; $j++) 
      {
        $players_home[$i] = $players_home[$i] . "" .
                            intval($build_data_home['score_' . ($j+1)]) . "" .
                            trim($build_data_home['break_' . ($j+1)]) . "";
        //$players_home[$i] = $players_home[$i] . "" .
                            //number_format($build_data_home['score_' . ($j+1)], 1) . "" .
                            //trim($build_data_home['break_' . ($j+1)]) . "";
      }
      $i++;
    }
    elseif($build_data_home['type'] == 'Snooker')
    {
      $players_home[$i] = $build_data_home['players_name'];
      for ($j = 0; $j < 3; $j++) 
      {
        $players_home[$i] = $players_home[$i] . "" .
                            intval($build_data_home['score_' . ($j+1)]) . "" .
                            trim($build_data_home['break_' . ($j+1)]) . "";
        //$players_home[$i] = $players_home[$i] . "" .
                            //number_format($build_data_home['score_' . ($j+1)], 1) . "" .
                            //trim($build_data_home['break_' . ($j+1)]) . "";
      }
      $i++;
    }
    $player_home = array_walk($players_home, 'trim_value');
    $player_home = ltrim($player_home, ",");
  }
}
$players_home = ltrim(json_encode($players_home), ",");
echo($players_home);

?>