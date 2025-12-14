<?php

include('connection.inc');

$current_year = date("Y");
$current_season = "S2";
$team_array = $_GET['members_array'];
$teamgrade = $_GET['teamgrade'];
$row_arr = explode(";", $team_array);

 // delete existing data
$sql_scrsheet = "Delete From scrs where scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year . " and team_grade = '" . $teamgrade . "'"; 
$update = $dbcnx_client->query($sql_scrsheet);

for($i = 0; $i < (count($row_arr)-1); $i++)
{
   $member_arr = explode(", ", $row_arr[$i]);
   $memberID = $member_arr[0];
   // remove , from start of string
   if(substr($memberID, 0, 1) == ',')
   {
      $memberID = substr($memberID, 1, strlen($memberID));
   }
   $team_grade = $member_arr[2];
   $team_id = $member_arr[1];
   $captain = $member_arr[3];
   $selected = $member_arr[4];

/*
   $sql_select = "Select * FROM scrs WHERE scr_season = '" . $current_season . "' and current_year_scrs = " . $current_year . " and team_grade = '" . $team_grade . "'";
   echo($sql_select . "<br>");

   $result_select = $dbcnx_client->query($sql_select);
   $build_select = $result_select->fetch_assoc();
   $type = $build_select['game_type'];
   $fin_year = $build_select['current_year_scrs'];
   $allocated_rp = $build_select['allocated_rp'];
   $count_played = $build_select['count_played'];
   $average_position = $build_select['average_position'];
   $max_pts = $build_select['maxpts'];
   $final_sub = $build_select['final_sub'];

*/
   // added if no grade data

   $count_played = 0;
   $allocated_rp = 50;
   $average_position = 0;
   $max_pts = 0;
   $final_sub = 'No';
   $type = 'Snooker';
   //$captain = 0;
   //$selected = 0;

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
   //echo($sql . "<br>");
   $update = $dbcnx_client->query($sql);

}
$caption = "true";
//$caption = json_encode($caption);
echo($caption);

?>
