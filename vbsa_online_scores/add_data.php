<?php 

include('connection.inc');

$year = $_GET['year'];
$season = $_GET['season'];

$sql_select = "Select distinct season, fix_cal_year FROM tbl_team_grade where season = '" . $season . "' and fix_cal_year = " . $year . " order by season";
$result_season = $dbcnx_client->query($sql_select) or die("Couldn't execute season query. " . mysqli_error($dbcnx_client));
$num_of_rows = $result_season->num_rows;

if($num_of_rows == 0)
{
  if($season == 'S1')
  {
    $year = ($year-1);
  }
  $sql_add_data = "Select * from tbl_team_grade where year = " . $year . " order by season, fix_cal_year";
  //echo($sql_add_data . "<br>");
  $result_add_data = $dbcnx_client->query($sql_add_data) or die("Couldn't execute season query. " . mysqli_error($dbcnx_client));
  while($build_add_data = $result_add_data->fetch_assoc()) 
  {
    $sql = "Insert into tbl_team_grade (
    grade, 
    grade_name, 
    season,
    type,
    RP,
    min_breaks,
    fix_upload, 
    fix_cal_year, 
    current,
    finals_teams,
    dayplayed,
    no_of_matches,
    no_of_rounds,
    no_of_players,
    games_round,
    no_of_fixtures
    )
    VALUES
    ('" . 
    $build_add_data['grade'] . "', '" . 
    $build_add_data['grade_name'] . "', '" . 
    trim($season) . "', '" . 
    $build_add_data['type'] . "', " . 
    $build_add_data['RP'] . ", " . 
    $build_add_data['min_breaks'] . ", '" . 
    $build_add_data['fix_upload'] . "', " . 
    $year . ", 0, " . 
    $build_add_data['finals_teams'] . ", '" . 
    $build_add_data['dayplayed'] . "', " . 
    $build_add_data['no_of_matches'] . ", " . 
    $build_add_data['no_of_rounds'] . ", " . 
    $build_add_data['no_of_players'] . ", " . 
    $build_add_data['games_round'] . ", " . 
    $build_add_data['no_of_fixtures'] . ")";
    $update = $dbcnx_client->query($sql);
    if(!$update )
    {
      die("Could not insert data into settings: " . mysqli_error($dbcnx_client));
    }
  }
  
}

echo("Added");


?>