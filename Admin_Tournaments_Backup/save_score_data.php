<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$packedscoredata_1 = json_decode(stripslashes($_GET['score_data_1']), true);
$packedscoredata_2 = json_decode(stripslashes($_GET['score_data_2']), true);
$packedscoredata_3 = json_decode(stripslashes($_GET['score_data_3']), true);
$packedscoredata_4 = json_decode(stripslashes($_GET['score_data_4']), true);

//echo("<pre>");
//echo(var_dump($packedscoredata_4));
//echo("</pre>");

$score_1 = explode(",", $packedscoredata_1);
$sql_players = "Update tournament_scores Set
r_" . $score_1[0] . "_game_1 = '" .  $score_1[2] . "',
r_" . $score_1[0] . "_score_1 = '" .  $score_1[3] . "',
r_" . $score_1[0] . "_score_2 = '" .  $score_1[4] . "',
r_" . $score_1[0] . "_score_3 = '" .  $score_1[5] . "',
r_" . $score_1[0] . "_score_4 = '" .  $score_1[6] . "',
r_" . $score_1[0] . "_score_5 = '" .  $score_1[7] . "',
r_" . $score_1[0] . "_score_6 = '" .  $score_1[8] . "',
r_" . $score_1[0] . "_score_7 = '" .  $score_1[9] . "',
r_" . $score_1[0] . "_breaks_1 = '" .  $score_1[10] . "',
r_" . $score_1[0] . "_breaks_2 = '" .  $score_1[11] . "',
r_" . $score_1[0] . "_breaks_3 = '" .  $score_1[12] . "',
r_" . $score_1[0] . "_breaks_4 = '" .  $score_1[13] . "',
r_" . $score_1[0] . "_breaks_5 = '" .  $score_1[14] . "',
r_" . $score_1[0] . "_breaks_6 = '" .  $score_1[15] . "',
r_" . $score_1[0] . "_breaks_7 = '" .  $score_1[16] . "',
r_" . $score_1[0] . "_position = '" . $score_1[17] . "'
Where tourn_id = " . $tourn_id . " and member_id = " . $score_1[1];  
//echo($sql_players . "<br>");
$update = mysql_query($sql_players, $connvbsa) or die(mysql_error());

$score_2 = explode(",", $packedscoredata_2);
$sql_players = "Update tournament_scores Set
r_" . $score_2[0] . "_game_1 = '" .  $score_2[2] . "',
r_" . $score_2[0] . "_score_1 = '" .  $score_2[3] . "',
r_" . $score_2[0] . "_score_2 = '" .  $score_2[4] . "',
r_" . $score_2[0] . "_score_3 = '" .  $score_2[5] . "',
r_" . $score_2[0] . "_score_4 = '" .  $score_2[6] . "',
r_" . $score_2[0] . "_score_5 = '" .  $score_2[7] . "',
r_" . $score_2[0] . "_score_6 = '" .  $score_2[8] . "',
r_" . $score_2[0] . "_score_7 = '" .  $score_2[9] . "',
r_" . $score_2[0] . "_breaks_1 = '" .  $score_2[10] . "',
r_" . $score_2[0] . "_breaks_2 = '" .  $score_2[11] . "',
r_" . $score_2[0] . "_breaks_3 = '" .  $score_2[12] . "',
r_" . $score_2[0] . "_breaks_4 = '" .  $score_2[13] . "',
r_" . $score_2[0] . "_breaks_5 = '" .  $score_2[14] . "',
r_" . $score_2[0] . "_breaks_6 = '" .  $score_2[15] . "',
r_" . $score_2[0] . "_breaks_7 = '" .  $score_2[16] . "',
r_" . $score_2[0] . "_position = '" . $score_2[17] . "'
Where tourn_id = " . $tourn_id . " and member_id = " . $score_2[1];  
//echo($sql_players . "<br>");
$update = mysql_query($sql_players, $connvbsa) or die(mysql_error());

$score_3 = explode(",", $packedscoredata_3);
$sql_players = "Update tournament_scores Set r_" . trim($score_3[2]) . "_position = " . $score_3[3] . " Where tourn_id = " . $tourn_id . " and member_id = " . $score_3[0];  
//echo($sql_players . "<br>");
$update = mysql_query($sql_players, $connvbsa) or die(mysql_error());

$score_4 = explode(",", $packedscoredata_4);;
$sql_players_1 = "Update tournament_scores Set 
referee = '" . $score_4[0] . "', 
marker = '" . $score_4[1] . "', 
table_no = '" . $score_4[2] . "', 
round = '" . $score_4[3] . "', 
grade = '" . $score_4[4] . "', 
start = '" . $score_4[5] . "', 
finish = '" . $score_4[6] . "', 
match_no = '" . $score_4[7] . "' 
Where tourn_id = " . $tourn_id . " and member_id = " . $score_1[1];  
//echo($sql_players_1 . "<br>");
$update = mysql_query($sql_players_1, $connvbsa) or die(mysql_error());

$sql_players_2 = "Update tournament_scores Set 
referee = '" . $score_4[0] . "', 
marker = '" . $score_4[1] . "', 
table_no = '" . $score_4[2] . "', 
round = '" . $score_4[3] . "', 
grade = '" . $score_4[4] . "', 
start = '" . $score_4[5] . "', 
finish = '" . $score_4[6] . "', 
match_no = '" . $score_4[7] . "' 
Where tourn_id = " . $tourn_id . " and member_id = " . $score_2[1];  
//echo($sql_players_2 . "<br>");
$update = mysql_query($sql_players_2, $connvbsa) or die(mysql_error());

echo "Score Data Updated";

?>