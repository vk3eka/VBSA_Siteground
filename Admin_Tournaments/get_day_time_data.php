<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];

$day_time_array = [];

$query_times = 'Select * FROM vbsa3364_vbsa2.tournament_day_time where tourn_id = ' . $tourn_id;
$result_times = mysql_query($query_times, $connvbsa) or die(mysql_error());
while($build_times = $result_times->fetch_assoc())
{
  // Format a specific timestamp
  $timestamp = strtotime($build_times['time']);
  $new_time = date("h:i", $timestamp); // Output: 03:30 PM
  $day_time_array[] = $build_times['row_no'] . ", " . $build_times['col_no'] . ", " . $new_time . ", " . $build_times['day'] . ", ";
}

echo(json_encode($day_time_array));

?>