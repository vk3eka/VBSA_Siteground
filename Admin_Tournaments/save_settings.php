<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$sort = $_GET['sort'];
$settings_days = $_GET['settings_days'];
$settings_best_of = $_GET['settings_best_of'];
$settings_time = $_GET['settings_time'];
$no_of_rounds = intVal($_GET['rounds']);
$table_data = $_GET['table_data'];

/*
$tourn_id = $_POST['tourn_id'];
$sort = $_POST['sort'];
$settings_days = $_POST['settings_days'];
$settings_best_of = $_POST['settings_best_of'];
$settings_time = $_POST['settings_time'];
$no_of_rounds = intVal($_POST['rounds']);
$table_data = $_POST['table_data'];
*/
/*
echo($tourn_id . "<br>");
echo($sort . "<br>");
echo($settings_days . "<br>");
echo($settings_best_of . "<br>");
echo($settings_time . "<br>");
echo($no_of_rounds . "<br>");
echo($table_data . "<br>");
*/
$settings_days = explode(", ", $settings_days);
$settings_best_of = explode(", ", $settings_best_of);
$settings_time = explode(", ", $settings_time);

if($no_of_rounds == 7)
{
	$sql_settings = "Update tournaments Set 
		best_of_1 = " .  $settings_best_of[0] . ", 
		best_of_2 = " .  $settings_best_of[1] . ", 
		best_of_3 = " .  $settings_best_of[2] . ", 
		best_of_4 = " .  $settings_best_of[3] . ", 
		best_of_5 = " .  $settings_best_of[4] . ", 
		best_of_6 = " .  $settings_best_of[5] . ", 
		best_of_7 = " .  rtrim($settings_best_of[6], ",") . ",

		start_day_1 = '" .  $settings_days[0] . "', 
		start_day_2 = '" .  $settings_days[1] . "', 
		start_day_3 = '" .  $settings_days[2] . "', 
		start_day_4 = '" .  $settings_days[3] . "', 
		start_day_5 = '" .  $settings_days[4] . "', 
		start_day_6 = '" .  $settings_days[5] . "', 
		start_day_7 = '" .  rtrim($settings_days[6], ",") . "', 

		time_1 = '" .  $settings_time[0] . "', 
		time_2 = '" .  $settings_time[1] . "', 
		time_3 = '" .  $settings_time[2] . "', 
		time_4 = '" .  $settings_time[3] . "', 
		time_5 = '" .  $settings_time[4] . "', 
		time_6 = '" .  $settings_time[5] . "', 
		time_7 = '" .  rtrim($settings_time[6], ",") . "',

		sort = '" . $sort . "'   

		where tourn_id = " . $tourn_id;
}
else if($no_of_rounds == 10)
{
	$sql_settings = "Update tournaments Set 
		best_of_1 = " .  $settings_best_of[0] . ", 
		best_of_2 = " .  $settings_best_of[1] . ", 
		best_of_3 = " .  $settings_best_of[2] . ", 
		best_of_4 = " .  $settings_best_of[3] . ", 
		best_of_5 = " .  $settings_best_of[4] . ", 
		best_of_6 = " .  $settings_best_of[5] . ", 
		best_of_7 = " .  $settings_best_of[6] . ",
		best_of_8 = " .  $settings_best_of[7] . ", 
		best_of_9 = " .  $settings_best_of[8] . ", 
		best_of_10 = " .  rtrim($settings_best_of[9], ",") . ",

		start_day_1 = '" .  $settings_days[0] . "', 
		start_day_2 = '" .  $settings_days[1] . "', 
		start_day_3 = '" .  $settings_days[2] . "', 
		start_day_4 = '" .  $settings_days[3] . "', 
		start_day_5 = '" .  $settings_days[4] . "', 
		start_day_6 = '" .  $settings_days[5] . "', 
		start_day_7 = '" .  $settings_days[6] . "', 
		start_day_8 = '" .  $settings_days[7] . "', 
		start_day_9 = '" .  $settings_days[8] . "', 
		start_day_10 = '" .  rtrim($settings_days[9], ",") . "', 

		time_1 = '" .  $settings_time[0] . "', 
		time_2 = '" .  $settings_time[1] . "', 
		time_3 = '" .  $settings_time[2] . "', 
		time_4 = '" .  $settings_time[3] . "', 
		time_5 = '" .  $settings_time[4] . "', 
		time_6 = '" .  $settings_time[5] . "', 
		time_7 = '" .  $settings_time[6] . "',
		time_8 = '" .  $settings_time[7] . "', 
		time_9 = '" .  $settings_time[8] . "', 
		time_10 = '" .  rtrim($settings_time[9], ",") . "',

		sort = '" . $sort . "'   

		where tourn_id = " . $tourn_id;
}

//echo($sql_settings . "<br>");
$update = mysql_query($sql_settings, $connvbsa) or die(mysql_error());


// delete existing data
$sql_delete = "Delete from tournament_day_time where tourn_id = " . $tourn_id;
$update = mysql_query($sql_delete, $connvbsa) or die(mysql_error());



//echo("<pre>");
//echo(var_dump($table_data));
//echo("</pre>");


$data = json_decode($table_data, true);

//echo("Data " . $data . "<br>");
if(!is_array($data)) 
{
	echo "Error Decoding JSON or data is not an array.";
}

foreach ($data as $item) 
{
    for($round = 1; $round <= $no_of_rounds; $round++)
    {
    	if($no_of_rounds == 7)
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
		}
		else if($no_of_rounds == 10)
    	{
			switch ($round)
			{
				case 1:
				  $col_no = 1;
				  break; 
				case 2:
				  $col_no = 2;
				  break; 
				case 3:
				  $col_no = 3;
				  break; 
				case 4:
				  $col_no = 4;
				  break; 

				case 5:
				  $col_no = 5;
				  break; 
				case 6:
				  $col_no = 6;
				  break; 
				case 7:
				  $col_no = 7;
				  break; 
				case 8:
				  $col_no = 8;
				  break; 
				case 9:
				  $col_no = 9;
				  break; 
				case 10:
				  $col_no = 10;
				  break; 
				case 11:
				  $col_no = 11;
				  break; 
			}
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

echo('Settings Updated');

?>