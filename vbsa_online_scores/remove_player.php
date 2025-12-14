<?php 

include('connection.inc');

$id = $_GET['id'];

$sql = "Update tbl_team_rego Set selected = 0, captain_scrs = 0, authoriser_scrs = 0 WHERE id = " . $id;
$update = $dbcnx_client->query($sql);
if(!$update )
{
    die("Could not remove player: " . mysqli_error($dbcnx_client));
}
		
?>