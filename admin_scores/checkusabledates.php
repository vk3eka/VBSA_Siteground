<?php

require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$date = $_GET['Date'];
//echo("Date " . $_GET['Date'] . "<br>");
//$date = $date.toISOString().substring(0, 10);
//$date = $date->format(DateTime::ATOM);

$sql_playing_dates = 'Select * from tbl_ics_dates where DTSTART = "' . $date . '" and donotuse = 0';
//echo($sql_playing_dates . "<br>");
$result_playing_dates = mysql_query($sql_playing_dates, $connvbsa) or die(mysql_error());
$row_count = $result_playing_dates->num_rows;
/*
if($row_count > 0)
{
    return true;
}
else
{
    return false;
}
*/

if($row_count > 0)
{
    echo("True");
}
else
{
    echo("False");
}
?>
