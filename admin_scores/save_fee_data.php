<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$season = $_GET['season'];
$year = $_GET['year'];
$fee = $_GET['pennant_fee'];

$sql_fees = "Update Team_entries Set pennant_fee = " .  $fee . " where team_season = '" . $season . "' and team_cal_year = " . $year;
$update = mysql_query($sql_fees, $connvbsa) or die(mysql_error());

echo('Fee Change Saved');
?>