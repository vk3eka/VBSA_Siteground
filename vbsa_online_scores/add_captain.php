<?php 

include('connection.inc');

$scrs_id = $_GET['scrs_id'];
$captain = $_GET['captain'];
$selected = $_GET['selected'];

//$sql_scrs = "Update scrs Set captain_scrs = " . $captain . ", selected = " . $selected . " where scrsID = " . $scrs_id;
$sql_scrs = "Insert INTO scrs Set captain_scrs = " . $captain . ", selected = " . $selected;
//echo($sql_scrs . "<br>");  
$update1 = $dbcnx_client->query($sql_scrs);

$member_id = $_GET['member_id'];
$team_name = $_GET['team_name'];

$sql_authorise = "Update tbl_authorise Set Team_1 = '" . $team_name . "' where PlayerNo = " . $member_id;
//echo($sql_authorise . "<br>");  
$update2 = $dbcnx_client->query($sql_authorise);
/*
if($update1 && $update2)
{
  echo("Team Submitted");
}
else
{
  die("Could not update team data: " . mysqli_error($dbcnx_client));
}
*/
?>