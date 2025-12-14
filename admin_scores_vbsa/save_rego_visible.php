<?php 
require_once('../Connections/connvbsa.php'); 

$start_date = $_GET['start'];
$finish_date = $_GET['end'];
$season = $_GET['season'];
$year = $_GET['year'];

mysql_select_db($database_connvbsa, $connvbsa);

$sql = "Select * from tbl_team_select_visible WHERE year = " . $year . " and season = '" . $season . "'";
$team_rego = mysql_query($sql, $connvbsa) or die(mysql_error());
$row_grades_fix = mysql_fetch_assoc($team_rego);
$total_team_rego = mysql_num_rows($team_rego);

if($total_team_rego > 0)
{
	$sql_rego = "Update tbl_team_select_visible Set start_date = '" . $start_date . "', finish_date = '" . $finish_date . "' WHERE year = " . $year . " and season = '" . $season . "'";
}
else
{
	$sql_rego = "Insert INTO tbl_team_select_visible (start_date, finish_date, year, season) VALUES ('" . $start_date . "', '" . $finish_date . "', " . $year . ", '" . $season . "')";
}		

$update= mysql_query($sql_rego, $connvbsa) or die(mysql_error());
echo($sql_rego);

?>