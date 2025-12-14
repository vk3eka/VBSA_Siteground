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
//echo($sql_home . "<br>");
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
                          $build_data_home['players_name'] . "" . 
                          $build_data_home['tier'] . "" . 
                          $build_data_home['billiard_stick'] . "" .
                          trim($build_data_home['break_1']) . "";
    }
   
    // snooker
    if(($build_data_home['type'] == 'Snooker') && ($i == 0) && (($title == 'Semi Final') || ($title == 'Grand Final')))
    {
      $players_home[$i] = $build_data_home['players_name'];
      for ($k = 0; $k < 4; $k++) 
      {
        $players_home[$i] = $players_home[$i] . "" .
                            intval($build_data_home['score_' . ($k+1)]) . "" .
                            trim($build_data_home['break_' . ($k+1)]);
      }
      $l++;
    }
    elseif($build_data_home['type'] == 'Snooker')
    {
      // snooker
      $players_home[$i] = $build_data_home['players_name'];
      for ($k = 0; $k < 3; $k++) 
      {
        $players_home[$i] = $players_home[$i] . "" .
                            intval($build_data_home['score_' . ($k+1)]) . "" .
                            trim($build_data_home['break_' . ($k+1)]);
      }
    }
    $i++;
  }
}

// get away team player data
$sql_away = "Select * from tbl_scoresheet where 
team = '" . $away . "' and 
opposition = '" . $home . "' and 
round = " . $round . " and 
year = " . $year . " and 
season = '" . $current_season . "' and 
team_grade = '" . $team_grade . "'
order By playing_position";
//echo($sql_away . "<br>");
$result_away = $dbcnx_client->query($sql_away);
$num_rows_away = $result_away->num_rows;

$players_away = array();
if ($num_rows_away != 0) 
{
  $l = 0;
  while($build_data_away = $result_away->fetch_assoc()) 
  {
    // billiards
    if($build_data_away['type'] == 'Billiards')
    { 
      $players_away[$l] = $players_away[$l] . "" . 
                          $build_data_away['players_name'] . "" . 
                          $build_data_away['tier'] . "" . 
                          $build_data_away['billiard_stick'] . "" .
                          trim($build_data_away['break_1']) . "";
    }

    // snooker
    if(($build_data_away['type'] == 'Snooker') && ($l == 0) && (($title == 'Semi Final') || ($title == 'Grand Final')))
    {
      $players_away[$l] = $build_data_away['players_name'];
      for ($j = 0; $j < 4; $j++) 
      {
        $players_away[$l] = $players_away[$l] . "" .
                            intval($build_data_away['score_' . ($j+1)]) . "" .
                            trim($build_data_away['break_' . ($j+1)]);
      }
      $l++;
    }
    elseif($build_data_away['type'] == 'Snooker')
    {
      $players_away[$l] = $build_data_away['players_name'];
      for ($j = 0; $j < 3; $j++) 
      {
        $players_away[$l] = $players_away[$l] . "" .
                            intval($build_data_away['score_' . ($j+1)]) . "" .
                            trim($build_data_away['break_' . ($j+1)]);
      }
    }
    $l++;
  }
}

$players_all = array_merge($players_home, $players_away);
echo(json_encode($players_all));

?>