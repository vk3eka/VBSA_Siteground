<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = intval($_GET['tourn_id']);
$data = json_decode($_GET['data'], true);
$no_of_rounds = intVal($_GET['no_of_rounds']);

// delete existing data
$sql_delete = "Delete from tournament_day_time where tourn_id = " . $tourn_id;
$update = mysql_query($sql_delete, $connvbsa) or die(mysql_error());

foreach ($data as $item) 
{
    for($round = 1; $round <= $no_of_rounds; $round++)
    {
		switch ($round)
		{
			case 1:
			  $col_no = 5;
			  break; 
			case 2:
			  $col_no = 6;
			  break; 
			case 3:
			  $col_no = 7;
			  break; 
			case 4:
			  $col_no = 8;
			  break; 
			case 5:
			  $col_no = 9;
			  break; 
			case 6:
			  $col_no = 10;
			  break; 
			case 7:
			  $col_no = 11;
			  break; 
		}
		// get default start time and start day for particular round
		$sql_day_time = 'Select * FROM tournaments Where tourn_id = ' . $tourn_id;
		//echo($sql_day_time . "<br>");
		$result_day_time = mysql_query($sql_day_time, $connvbsa) or die(mysql_error());
		$build_day_time = $result_day_time->fetch_assoc();
		$day = $build_day_time['start_day_' . $round];
		$time = $build_day_time['time_' . $round];
		switch ($day)
		{
			case 0:
			  $day = 'Mon';
			  break; 
			case 1:
			  $day = 'Tue';
			  break; 
			case 2:
			  $day = 'Wed';
			  break; 
			case 3:
			  $day = 'Thur';
			  break; 
			case 4:
			  $day = 'Fri';
			  break; 
			case 5:
			  $day = 'Sat';
			  break; 
			case 6:
			  $day = 'Sun';
			  break; 
		}
		if(intval($item['col']) == $col_no)
		{
		  	$sql_insert = "Insert into tournament_day_time ( 
		    row_no, 
		    col_no, 
		    day, 
		    time,
		    tourn_id) Values (
		    " .  intval($item['row']) . ", 
		    " .  $col_no . ", 
		    '" .  $day . "', 
		    '" .  $time . "', 
		    " . $tourn_id . ')
		    ON DUPLICATE KEY UPDATE
		    row_no = VALUES(row_no),
		    col_no = VALUES(col_no);';
		    //echo($sql_insert . "<br>");
		  	$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
		}
	}
}

echo("Data Inserted");
?>