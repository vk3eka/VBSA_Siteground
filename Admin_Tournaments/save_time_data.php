<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$member_id = $_GET['player_id'];
$new_day = $_GET['new_day'];
$new_time = $_GET['new_time'];
$round = $_GET['round_no'];
$match_index = $_GET['match_index'];

//echo($new_day . "<br>");

$sql_players = "Update tournament_draw_dates Set day = '" .  $new_day . "', time = '" .  $new_time . "' where tourn_id = " . $tourn_id . " and match_index = " . $match_index;
//echo($sql_players . "<br>");
$update = mysql_query($sql_players, $connvbsa) or die(mysql_error());

echo($new_day . ", " . $new_time);
?>