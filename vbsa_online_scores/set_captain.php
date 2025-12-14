<?php

include('connection.inc');

$memberID = $_GET['MemberID'];
$team_grade = $_GET['TeamGrade'];
$team_id = $_GET['TeamID'];
$scrs_id = $_GET['ScrsID'];
$season = $_GET['Season'];
$captain = $_GET['Captain'];

//check if already a captain and disable if true
$sql_select = "Select captain_scrs from scrs WHERE scrsID = " . $scrs_id;
$result_select = $dbcnx_client->query($sql_select);
$build_select = $result_select->fetch_assoc();
if($build_select['captain_scrs'] == 1)
{
   $captain = 0;
}
else
{
   $captain = 1;
}

$sql = ("Update scrs SET 
   MemberID = " . $memberID . ", 
   team_grade = '" . $team_grade . "', 
   team_id = " . $team_id  . ", 
   scr_season = '" . $season  . "', 
   captain_scrs = " . $captain . " 
   WHERE scrsID = " . $scrs_id);
$update = $dbcnx_client->query($sql);
if(!$update )
{
    die("Could not update data: " . mysqli_error($dbcnx_client));
} 

?>
