<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$no_of_rounds = $_GET['no_of_rounds'];

$sql = "Select * FROM tournaments WHERE tourn_id = " . $tourn_id;
//echo($sql . "<br>");
$result_tournaments = mysql_query($sql, $connvbsa) or die(mysql_error());

if($result_tournaments->num_rows > 0)
{
	$build_tournaments = $result_tournaments->fetch_assoc();
	if($no_of_rounds == 7)
	{
		$tournaments_data = 

		$build_tournaments['best_of_1'] . ", " . 
		$build_tournaments['best_of_2'] . ", " . 
		$build_tournaments['best_of_3'] . ", " . 
		$build_tournaments['best_of_4'] . ", " . 
		$build_tournaments['best_of_5'] . ", " . 
		$build_tournaments['best_of_6'] . ", " . 
		$build_tournaments['best_of_7'] . ", " . 

		$build_tournaments['start_day_1'] . ", " . 
		$build_tournaments['start_day_2'] . ", " . 
		$build_tournaments['start_day_3'] . ", " . 
		$build_tournaments['start_day_4'] . ", " . 
		$build_tournaments['start_day_5'] . ", " . 
		$build_tournaments['start_day_6'] . ", " . 
		$build_tournaments['start_day_7'] . ", " . 

		$build_tournaments['time_1'] . ", " . 
		$build_tournaments['time_2'] . ", " . 
		$build_tournaments['time_3'] . ", " . 
		$build_tournaments['time_4'] . ", " . 
		$build_tournaments['time_5'] . ", " . 
		$build_tournaments['time_6'] . ", " . 
		$build_tournaments['time_7'] . ", " . 

		$build_tournaments['sort']; 
	}
	else if($no_of_rounds == 9)
	{
		$tournaments_data = 

		$build_tournaments['best_of_1'] . ", " . 
		$build_tournaments['best_of_2'] . ", " . 
		$build_tournaments['best_of_3'] . ", " . 
		$build_tournaments['best_of_4'] . ", " . 
		$build_tournaments['best_of_5'] . ", " . 
		$build_tournaments['best_of_6'] . ", " . 
		$build_tournaments['best_of_7'] . ", " . 
		$build_tournaments['best_of_8'] . ", " . 
		$build_tournaments['best_of_9'] . ", " . 

		$build_tournaments['start_day_1'] . ", " . 
		$build_tournaments['start_day_2'] . ", " . 
		$build_tournaments['start_day_3'] . ", " . 
		$build_tournaments['start_day_4'] . ", " . 
		$build_tournaments['start_day_5'] . ", " . 
		$build_tournaments['start_day_6'] . ", " . 
		$build_tournaments['start_day_7'] . ", " . 
		$build_tournaments['start_day_8'] . ", " . 
		$build_tournaments['start_day_9'] . ", " . 

		$build_tournaments['time_1'] . ", " . 
		$build_tournaments['time_2'] . ", " . 
		$build_tournaments['time_3'] . ", " . 
		$build_tournaments['time_4'] . ", " . 
		$build_tournaments['time_5'] . ", " . 
		$build_tournaments['time_6'] . ", " . 
		$build_tournaments['time_7'] . ", " . 
		$build_tournaments['time_8'] . ", " . 
		$build_tournaments['time_9'] . ", " . 

		$build_tournaments['sort']; 
	}
	else if($no_of_rounds == 10)
	{

		$tournaments_data = 

		$build_tournaments['best_of_1'] . ", " . 
		$build_tournaments['best_of_2'] . ", " . 
		$build_tournaments['best_of_3'] . ", " . 
		$build_tournaments['best_of_4'] . ", " . 
		$build_tournaments['best_of_5'] . ", " . 
		$build_tournaments['best_of_6'] . ", " . 
		$build_tournaments['best_of_7'] . ", " . 
		$build_tournaments['best_of_8'] . ", " . 
		$build_tournaments['best_of_9'] . ", " . 
		$build_tournaments['best_of_10'] . ", " . 

		$build_tournaments['start_day_1'] . ", " . 
		$build_tournaments['start_day_2'] . ", " . 
		$build_tournaments['start_day_3'] . ", " . 
		$build_tournaments['start_day_4'] . ", " . 
		$build_tournaments['start_day_5'] . ", " . 
		$build_tournaments['start_day_6'] . ", " . 
		$build_tournaments['start_day_7'] . ", " . 
		$build_tournaments['start_day_8'] . ", " . 
		$build_tournaments['start_day_9'] . ", " . 
		$build_tournaments['start_day_10'] . ", " . 

		$build_tournaments['time_1'] . ", " . 
		$build_tournaments['time_2'] . ", " . 
		$build_tournaments['time_3'] . ", " . 
		$build_tournaments['time_4'] . ", " . 
		$build_tournaments['time_5'] . ", " . 
		$build_tournaments['time_6'] . ", " . 
		$build_tournaments['time_7'] . ", " . 
		$build_tournaments['time_8'] . ", " . 
		$build_tournaments['time_9'] . ", " . 
		$build_tournaments['time_10'] . ", " . 

		$build_tournaments['sort']; 
	}

	echo($tournaments_data);
}
else
{
	echo("No Data");
}


?>