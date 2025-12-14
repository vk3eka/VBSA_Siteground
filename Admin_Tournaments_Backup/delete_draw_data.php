<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tournament_id = $_GET['tourn_id'];
// delete existing records for testing
$query_delete = 'Delete from tournament_scores where tourn_id = ' . $tournament_id;
$result_delete = mysql_query($query_delete, $connvbsa) or die(mysql_error());

echo("Data Deleted");

?>