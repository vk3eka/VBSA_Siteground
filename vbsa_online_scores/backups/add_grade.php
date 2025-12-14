<?php 

include('connection.inc');

$grade = $_GET['grade'];
$grade_name = $_GET['name'];
$grade_type = $_GET['type'];
$day_played = $_GET['dayplayed'];
$season = $_GET['season'];
$year = $_GET['year'];

$sql = "Insert into tbl_team_grade 
(grade, grade_name, type, dayplayed, season, fix_cal_year)
VALUES
('" . $grade . "', '" . $grade_name . "', '" . $grade_type . "', '" . $day_played . "', '" . trim($season) . "', " . $year . ")";
//echo($sql);
$update = $dbcnx_client->query($sql);
if(!$update )
{
  die("Could not insert data into settings: " . mysqli_error($dbcnx_client));
}
else
{
  echo("Added");
}

?>