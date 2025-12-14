<?php

include('connection.inc');

$player_no = $_GET['MemberID'];

$sql = "Select PlayerNo FROM tbl_authorise where PlayerNo = ". $player_no;
$result_player = $dbcnx_client->query($sql) or die("Couldn't execute grade query. " . mysqli_error($dbcnx_client));
$players = $result_player->num_rows;
if($players > 0)
{
    $existing = 'Yes';
}
else
{
	$existing = 'No';
}
echo $existing;

?>