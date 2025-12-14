<?php

include('connection.inc');

$memberID = $_GET['MemberID'];
$team_grade = $_GET['TeamGrade'];
$team_id = $_GET['TeamID'];
$current_year = $_GET['Year'];

$sql_select = "Select * FROM scrs WHERE current_year_scrs = " . $current_year . " and team_grade = '" . $team_grade . "'";
//echo($sql_select . "<br>");
$result_select = $dbcnx_client->query($sql_select);
$build_select = $result_select->fetch_assoc();

$allocated_rp = $build_select['allocated_rp'];
$count_played = $build_select['count_played'];
$average_position = $build_select['average_position'];
$type = $build_select['game_type'];
$season = $build_select['scr_season'];
$max_pts = $build_select['maxpts'];
$final_sub = $build_select['final_sub'];
$fin_year = $build_select['current_year_scrs'];

$sql = "Insert INTO scrs (
MemberID,
team_grade,
allocated_rp,
game_type,
scr_season,
team_id,
count_played,
average_position,
maxpts,
final_sub,
fin_year_scrs,
current_year_scrs
)
 VALUES 
 (" .
    $memberID . ", '" .
    $team_grade . "', " .
    $allocated_rp . ", '" .
    $type . "', '" .
    $season . "', " .
    $team_id . ", " .
    $count_played . ", " .
    $average_position . ", " .
    $max_pts . ", '" .
    $final_sub . "', " .
    $fin_year . ", " .
    $current_year . ")";
//echo($sql . "<br>");
$update = $dbcnx_client->query($sql);
if(!$update )
{
    echo("Could not update data: " . mysqli_error($dbcnx_client));
} 
else
{
   echo("Player list has been updated.");
}

?>
