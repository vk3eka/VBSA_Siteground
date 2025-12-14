<?php 

include('connection.inc');

$id = $_GET['scrs_id'];

$sql = "Delete FROM scrs WHERE scrsID = '" . $id . "'";
//echo($sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update )
{
    die("Could not remove player: " . mysqli_error($dbcnx_client));
}
		
?>