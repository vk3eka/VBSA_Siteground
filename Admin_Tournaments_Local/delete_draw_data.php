<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tournament_id = $_GET['tourn_id'];
// delete existing records for testing
$query_delete = 'Delete from tournament_scores where tourn_id = ' . $tournament_id;
$result_delete = mysql_query($query_delete, $connvbsa) or die(mysql_error());

$query_delete_date = 'Delete from tournament_draw_dates where tourn_id = ' . $tournament_id;
$result_delete_date = mysql_query($query_delete_date, $connvbsa) or die(mysql_error());

$query_delete_players = 'Delete from tournament_players where tourn_id = ' . $tournament_id;
$result_delete_players = mysql_query($query_delete_players, $connvbsa) or die(mysql_error());

echo("Data Deleted");

?>