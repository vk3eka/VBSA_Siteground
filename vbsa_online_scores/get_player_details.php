<?php

include('connection.inc');

$player_no = $_GET['memberID'];

$sql = "Select FirstName, LastName, Email, MobilePhone FROM members where MemberID = " . $player_no;
//echo($sql . "<br>");
$result_player = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$build_team = $result_player->fetch_assoc();
$rows = array();

$rows[0] = $player_no;
$rows[1] = $build_team['FirstName'];
$rows[2] = $build_team['LastName'];
$rows[3] = $build_team['Email'];
$rows[4] = $build_team['MobilePhone'];

echo json_encode($rows);

?>