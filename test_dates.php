<?php

//$dbcnx_client = new mysqli("172.16.10.16", "peterj", "abj059XZ@!", "vbsa3364_vbsa2");
//if ($dbcnx_client->connect_errno) {
//    echo "Failed to connect to MySQL: " . $dbcnx_client->connect_error;
//}

/*
$today = date("Y-m-d H:i:s"); 
echo($today . "<br>");
$sql_update = "Update tbl_dates Set text_entry = '2023-10-19 17:53:38', current_date = now() where id = 1";
//$sql_update = "Insert Into tbl_dates values (current_timestamp)";
echo($sql_update . "<br>");
$update = $dbcnx_client->query($sql_update);
if(!$update)
{
    die("Could not update: " . mysqli_error($dbcnx_client));
} 

$sql_home = "Select * from tbl_dates where id = 1";
echo($sql_home . "<br>");
$result_home = $dbcnx_client->query($sql_home);
$build_data_home = $result_home->fetch_assoc();
*/

echo("Before<br>");
echo("Server Date " .  date('Y-m-d H:i:s') . "<br>");
echo("TimeZone " . date_default_timezone_get() . "<br>");
//echo("MYSQL Current Date " .  $build_data_home['current_date'] . "<br>");
//echo("MYSQL Current Timestamp " .  $build_data_home['timestamp'] . "<br>");


date_default_timezone_set('Australia/Melbourne');

echo("After<br>");
echo("Server Date " .  date('Y-m-d H:i:s') . "<br>");
echo("TimeZone " . date_default_timezone_get() . "<br>");
//echo("MYSQL Current Date " .  $build_data_home['current_date'] . "<br>");
//echo("MYSQL Current Timestamp " .  $build_data_home['timestamp'] . "<br>");
