<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$row = $_GET['row'];
$col = $_GET['col'];
$time = $_GET['time'];

// check if day/time already in table
$sql_select = 'Select day from tournament_day_time where row_no = ' . $row . ' and col_no = ' . $col;
//echo($sql_select . "<br>");
$result_select = mysql_query($sql_select, $connvbsa) or die(mysql_error());
$total_select = $result_select->num_rows;

if($total_select > 0)
{
	$sql_update = "Update tournament_day_time Set 
		time = '" .  $time . "' 
		where tourn_id = " . $tourn_id . " and row_no = " . $row . " and col_no = " . $col;
		//echo($sql_update . "<br>");
	$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
}
else
{
	$sql_insert = "Insert into tournament_day_time ( 
		row_no, 
		col_no, 
		time, 
		tourn_id) Values (
		" .  $row . ", 
		" .  $col . ", 
		'" .  $time . "', 
		" . $tourn_id . ')';
	$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

echo('Time Updated');
?>