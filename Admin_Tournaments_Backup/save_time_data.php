<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$member_id = $_GET['player_id'];
$new_day = $_GET['new_day'];
$new_time = $_GET['new_time'];
$round = $_GET['round_no'];

//echo($new_day . "<br>");

$sql_players = "Update tournament_scores Set r_" . $round . "_day = '" .  $new_day . "', r_" . $round . "_time = '" .  $new_time . "' where tourn_id = " . $tourn_id . " and member_id = " . $member_id;
//echo($sql_players . "<br>");
$update = mysql_query($sql_players, $connvbsa) or die(mysql_error());

echo($new_day . ", " . $new_time);
?>