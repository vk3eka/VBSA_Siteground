<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$round = $_GET['total_players'];
$member1 = $_GET['member_1'];
$member2 = $_GET['member_2'];

// get scores data member 1
$sql_member_1 = "Select * from tournament_scores where member_id = " . $member1;
$result_member_1 = mysql_query($sql_member_1, $connvbsa) or die(mysql_error());
$build_member_1 = $result_member_1->fetch_assoc();

$member_1_data = 
$member1 . ", " . 
intval($build_member_1['r_' . $round . '_score_1']) . ", " . 
intval($build_member_1['r_' . $round . '_score_2']) . ", " . 
intval($build_member_1['r_' . $round . '_score_3']) . ", " . 
intval($build_member_1['r_' . $round . '_score_4']) . ", " . 
intval($build_member_1['r_' . $round . '_score_5']) . ", " . 
intval($build_member_1['r_' . $round . '_score_6']) . ", " . 
intval($build_member_1['r_' . $round . '_score_7']) . ", " . 
$build_member_1['r_' . $round . '_breaks_1'] . ", " . 
$build_member_1['r_' . $round . '_breaks_2'] . ", " . 
$build_member_1['r_' . $round . '_breaks_3'] . ", " . 
$build_member_1['r_' . $round . '_breaks_4'] . ", " . 
$build_member_1['r_' . $round . '_breaks_5'] . ", " . 
$build_member_1['r_' . $round . '_breaks_6'] . ", " . 
$build_member_1['r_' . $round . '_breaks_7'] . ", " . 
$build_member_1['r_' . $round . '_game_1'] . ", " . 
$build_member_1['r_' . $round . '_game_2'];

// get scores data member 2
$sql_member_2 = "Select * from tournament_scores where member_id = " . $member2;
$result_member_2 = mysql_query($sql_member_2, $connvbsa) or die(mysql_error());
$build_member_2 = $result_member_2->fetch_assoc();

$member_2_data = 
$member2 . ", " . 
intval($build_member_2['r_' . $round . '_score_1']) . ", " . 
intval($build_member_2['r_' . $round . '_score_2']) . ", " . 
intval($build_member_2['r_' . $round . '_score_3']) . ", " . 
intval($build_member_2['r_' . $round . '_score_4']) . ", " . 
intval($build_member_2['r_' . $round . '_score_5']) . ", " . 
intval($build_member_2['r_' . $round . '_score_6']) . ", " . 
intval($build_member_2['r_' . $round . '_score_7']) . ", " . 
$build_member_2['r_' . $round . '_breaks_1'] . ", " . 
$build_member_2['r_' . $round . '_breaks_2'] . ", " . 
$build_member_2['r_' . $round . '_breaks_3'] . ", " . 
$build_member_2['r_' . $round . '_breaks_4'] . ", " . 
$build_member_2['r_' . $round . '_breaks_5'] . ", " . 
$build_member_2['r_' . $round . '_breaks_6'] . ", " . 
$build_member_2['r_' . $round . '_breaks_7'] . ", " . 
$build_member_2['r_' . $round . '_game_1'] . ", " . 
$build_member_2['r_' . $round . '_game_2'];

// get scores data member 1
$sql_member_3 = "Select * from tournament_scores where member_id = " . $member1;
$result_member_3 = mysql_query($sql_member_3, $connvbsa) or die(mysql_error());
$build_member_3 = $result_member_3->fetch_assoc();

$member_3_data = 
$member1 . ", " . 
$build_member_1['referee'] . ", " . 
$build_member_1['marker'] . ", " . 
$build_member_1['table_no'] . ", " . 
$build_member_1['grade'] . ", " . 
$build_member_1['start'] . ", " . 
$build_member_1['finish'] . ", " . 
$build_member_1['match_no'];


$memberArr = $member_1_data . ":" . $member_2_data . ":" . $member_3_data;
echo($memberArr);

?>