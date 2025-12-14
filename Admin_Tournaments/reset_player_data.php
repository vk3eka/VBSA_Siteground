<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$sql_delete = "Delete from tournament_players where tourn_id = " . $tourn_id;
$update = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

$sql_delete = "Delete from tournament_day_time where tourn_id = " . $tourn_id;
$update = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

$sql_delete = "Delete from tournament_results where tourn_id = " . $tourn_id;
$update = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

$sql_delete = "Update tournaments Set start_day_1 = 0, start_day_2 = 0, start_day_3 = 0, start_day_4 = 0, start_day_5 = 0, start_day_6 = 0, start_day_7 = 0, start_day_8 = 0, start_day_9 = 0, start_day_10 = 0, 
best_of_1 = 0, best_of_2 = 0, best_of_3 = 0, best_of_4 = 0, best_of_5 = 0, best_of_6 = 0, best_of_7 = 0, best_of_8 = 0, best_of_9 = 0, best_of_10 = 0, 
time_1 = 0, time_2 = 0, time_3 = 0, time_4 = 0, time_5 = 0, time_6 = 0, time_7 = 0, time_8 = 0, time_9 = 0, time_10 = 0 Where tourn_id =  " . $tourn_id;
$update = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

echo('Tournament Reset');
?>