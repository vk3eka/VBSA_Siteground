<?php

include('connection.inc');

$memberID = $_GET['MemberID'];
$team_grade = $_GET['TeamGrade'];
$team_id = $_GET['TeamID'];

$current_year = date("Y");
$current_season = "S2";

$captain = $_GET['Captain'];
$selected = $_GET['Selected'];

// delete existing data

// added team_id 27/3/24
$sql_scrsheet = "Delete From scrs where scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year . " and team_grade = '" . $team_grade . "' and team_id = " . $team_id; 
$update = $dbcnx_client->query($sql_scrsheet);

$sql_select = "Select * FROM Team_grade WHERE season = '" . $current_season . "' and fix_cal_year = " . $current_year . " and grade = '" . $team_grade . "'";
$result_select = $dbcnx_client->query($sql_select);
$build_select = $result_select->fetch_assoc();
$allocated_rp = $build_select['allocated_rp'];
$count_played = $build_select['count_played'];
$average_position = $build_select['average_position'];
$max_pts = $build_select['maxpts'];
$final_sub = $build_select['final_sub'];
$type = $build_select['type'];

// to be added if no grade data

//$count_played = 0;
//$allocated_rp = 50;
//$average_position = 0;
//$max_pts = 0;
//$final_sub = 'No';
//$type = 'Snooker';

if($result_select->num_rows == 0)
{
   $sql = "Insert INTO scrs (
   MemberID,
   team_grade,
   allocated_rp,
   game_type,
   scr_season,
   team_id,
   selected,
   captain_scrs,
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
       $current_season . "', " .
       $team_id . ", " .
       $selected . ", " .
       $captain . ", " .
       $count_played . ", " .
       $average_position . ", " .
       $max_pts . ", '" .
       $final_sub . "', " .
       $current_year . ", " .
       $current_year . ")";
   echo($sql . "<br>");
   $update = $dbcnx_client->query($sql);
}
else
{
   echo("Already Added");
}
//echo($sql);

?>
