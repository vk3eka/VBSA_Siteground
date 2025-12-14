<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$row = $_GET['row'];
$col = $_GET['col'];
$day = $_GET['day'];

// check if day already in table
$sql_select = 'Select day from tournament_day_time where row_no = ' . $row . ' and col_no = ' . $col;
$result_select = mysql_query($sql_select, $connvbsa) or die(mysql_error());
$total_select = $result_select->num_rows;

if($total_select > 0)
{
	$sql_update = "Update tournament_day_time Set 
		day = '" .  $day . "' 
		where tourn_id = " . $tourn_id . " and row_no = " . $row . " and col_no = " . $col;
		echo($sql_update . "<br>");
	$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
}
else
{
	$sql_insert = "Insert into tournament_day_time ( 
		row_no, 
		col_no, 
		day, 
		tourn_id) Values (
		" .  $row . ", 
		" .  $col . ", 
		'" .  $day . "', 
		" . $tourn_id . ')';
		echo($sql_insert . "<br>");
	$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

echo('Day Updated');
?>