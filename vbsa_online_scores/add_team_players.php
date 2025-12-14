<?php

include('connection.inc');

$memberID = $_GET['MemberID'];
$team_grade = $_GET['TeamGrade'];
$team_name = $_GET['TeamName'];
$club_name = $_GET['ClubName'];

$captain = $_GET['Captain'];
$authoriser = $_GET['Authoriser'];
$selected = $_GET['Selected'];

$sql_team_players = "Update tbl_team_rego Set selected = " . $selected . ", captain_scrs = " . $captain . ", authoriser_scrs = " . $authoriser . " where team_grade = '" . $team_grade . "' and member_id = " . $memberID . " and team_name = '" . $team_name . "' and club_name = '" . $club_name . "'";

//$sql_team_players = "Delete FROM tbl_team_rego where team_grade = '" . $team_grade . "' and member_id = " . $memberID . " and team_name = '" . $team_name . "'";


$update = $dbcnx_client->query($sql_team_players);
if(!$update)
{
    die("Could not update data: " . mysqli_error($dbcnx_client));
}


?>
