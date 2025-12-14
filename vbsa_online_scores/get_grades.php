<?php

include("connection.inc");
include("php_functions.php");

$requested_year = $_GET['year'];
$requested_season = trim($_GET['season']);

// check if scoresheet data exists
$sql_list = "Select * from tbl_team_grade where season = '" . $requested_season . "' and fix_cal_year = " . $requested_year . " Order By grade";
//echo($sql_list . "<br>");

$result_list = $dbcnx_client->query($sql_list) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
$num_rows = $result_list->num_rows;

// if not add it
if($num_rows == 0)
{
  if($requested_season == 'S1')
  {
    $year = ($requested_year-1);
    $season = 'S2';
  }
  else
  {
    $year = $requested_year;
    $season = 'S1';
  }
  $sql_add_data = "Select * from tbl_team_grade where season = '" . $season . "' and fix_cal_year = " . $year . " order by season, fix_cal_year";
  //echo($sql_add_data . "<br>");
  $result_add_data = $dbcnx_client->query($sql_add_data) or die("Couldn't execute season query. " . mysqli_error($dbcnx_client));
  while($build_add_data = $result_add_data->fetch_assoc()) 
  {
    // get fixtures from current year fixtures list ... needs checking.......
    $sql_grades = "Select distinct team_grade, season, year FROM tbl_fixtures where team_grade = '" . $build_add_data['grade'] . "' and season = '" . trim($season) . "' and year = " . $year;
    //echo("New List = " . $sql_grades . "<br>");
    $result_grades = $dbcnx_client->query($sql_grades) or die("Couldn't execute fixture query. " . mysqli_error($dbcnx_client));
    $num_rows = $result_grades->num_rows;
    if($num_rows > 0)
    {
      $current = 'Yes';
    }
    else
    {
      $current = 'No';
    }
    //echo("Current " . $current . "<br>");
    
    //$current = 'No';

    $sql = "Insert into tbl_team_grade (
    grade, 
    grade_name, 
    season,
    type,
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
    no_of_fixtures,
    tier1_rp,
    tier2_rp,
    tier3_rp,
    tier4_rp,
    tier5_rp,
    tier6_rp
    )
    VALUES
    ('" . 
    $build_add_data['grade'] . "', '" . 
    $build_add_data['grade_name'] . "', '" . 
    $requested_season . "', '" . 
    $build_add_data['type'] . "', " . 
    $build_add_data['RP'] . ", " . 
    $build_add_data['min_breaks'] . ", '" . 
    $build_add_data['fix_upload'] . "', " . 
    $requested_year . ", '" .
    $current . "', " . 
    $build_add_data['finals_teams'] . ", '" . 
    $build_add_data['dayplayed'] . "', " . 
    $build_add_data['no_of_matches'] . ", " . 
    $build_add_data['no_of_rounds'] . ", " . 
    $build_add_data['no_of_players'] . ", " . 
    $build_add_data['games_round'] . ", " . 
    $build_add_data['no_of_fixtures'] . ", " . 
    $build_add_data['tier1_rp'] . ", " . 
    $build_add_data['tier2_rp'] . ", " . 
    $build_add_data['tier3_rp'] . ", " . 
    $build_add_data['tier4_rp'] . ", " . 
    $build_add_data['tier5_rp'] . ", " . 
    $build_add_data['tier6_rp'] . ")";
    //echo($sql . "<br>");
    $update = $dbcnx_client->query($sql);
    if(!$update )
    {
      die("Could not insert data into settings: " . mysqli_error($dbcnx_client));
    }
  }
}

//elseif ($num_rows != 0) 
//{
$i = 0;
// get scoresheet data after adding if required
$sql_list_refresh = "Select * from tbl_team_grade where season = '" . trim($requested_season) . "' and fix_cal_year = " . $requested_year . " Order By grade";
//echo($sql_list_refresh . "<br>");
$result_list_refresh = $dbcnx_client->query($sql_list_refresh) or die("Couldn't execute query. " . mysqli_error($dbcnx_client));
while($build_data_refresh = $result_list_refresh->fetch_assoc()) 
{
  $grades_list[$i] = $build_data_refresh['grade'] . ", " . $build_data_refresh['grade_name'] . ", " . $build_data_refresh['season'] . ", " . $build_data_refresh['type'] . ", " . $build_data_refresh['RP'] . ", " . $build_data_refresh['min_breaks'] . ", " . $build_data_refresh['fix_upload'] . ", " . $build_data_refresh['fix_cal_year'] . ", " . $build_data_refresh['current'] . ", " . $build_data_refresh['finals_teams'] . ", " . $build_data_refresh['dayplayed'] . ", " . $build_data_refresh['no_of_matches'] . ", " . $build_data_refresh['no_of_rounds'] . ", " . $build_data_refresh['no_of_players'] . ", " . $build_data_refresh['games_round'] . ", " . $build_data_refresh['no_of_fixtures'] . ", " . $build_data_refresh['tier1_rp'] . ", " . $build_data_refresh['tier2_rp'] . ", " . $build_data_refresh['tier3_rp'] . ", " . $build_data_refresh['tier4_rp'] . ", " . $build_data_refresh['tier5_rp'] . ", " . $build_data_refresh['tier6_rp'] . ", " . $build_data_refresh['id'];
  $i++;
}
//}

echo(json_encode($grades_list));

?>