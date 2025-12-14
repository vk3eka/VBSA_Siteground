<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$round = $_GET['round'];

// bet best of values from tournaments table
$sql_best_of = 'Select * from tournaments where tourn_id = ' . $tourn_id;
$result_best_of = mysql_query($sql_best_of, $connvbsa) or die(mysql_error());

if($result_best_of->num_rows > 0)
{
	$build_best_of = $result_best_of->fetch_assoc();
	$best_of_data = $build_best_of['best_of_' . $round]; 
	echo($best_of_data);
}
else
{
	echo("No Data");
}

?>