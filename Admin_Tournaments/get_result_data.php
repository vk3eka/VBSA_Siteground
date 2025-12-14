<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$player_name_1 = $_GET['player_name_1'];
$player_name_2 = $_GET['player_name_2'];
$row_1 = $_GET['row_1'];
$row_2 = $_GET['row_2'];
$col = $_GET['col'];

/*
echo($tourn_id . "<br>");
echo($player_name_1 . "<br>");
echo($player_name_2 . "<br>");
echo($row_1 . "<br>");
echo($row_2 . "<br>");
echo($col . "<br>");
*/

function GetMemberID($fullname)
{
	global $connvbsa;
	if($fullname != "Bye")
	{
		$sql = "Select MemberID, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($fullname) . "%'";
		//echo($sql . "<br>");
		$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
		$build_member = $result_member->fetch_assoc();
		return $build_member['MemberID'];
	}
	else
	{
		return 1;
	}
}

$sql = "Select * FROM tournament_results WHERE memb_id = " . GetMemberID($player_name_1) . " and row_no = " . $row_1 . " and col_no = " . $col . " and tourn_id = " . $tourn_id;
//echo($sql . "<br>");
$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());

if($result_member->num_rows > 0)
{
	$build_member_1 = $result_member->fetch_assoc();
	$member_1_data = 
	$build_member_1['memb_id'] . ", " . 
	intval($build_member_1['score_1']) . ", " . 
	intval($build_member_1['score_2']) . ", " . 
	intval($build_member_1['score_3']) . ", " . 
	intval($build_member_1['score_4']) . ", " . 
	intval($build_member_1['score_5']) . ", " . 
	intval($build_member_1['score_6']) . ", " . 
	intval($build_member_1['score_7']) . ", " . 
	$build_member_1['forfeit_1'] . ", " . 
	$build_member_1['walkover_1'] . ", " . 
	$build_member_1['breaks_1'] . ", " . 
	$build_member_1['breaks_2'] . ", " . 
	$build_member_1['breaks_3'] . ", " . 
	$build_member_1['breaks_4'] . ", " . 
	$build_member_1['breaks_5'] . ", " . 
	$build_member_1['breaks_6'] . ", " . 
	$build_member_1['breaks_7'] . ", " . 
	$build_member_1['to_break_1'] . ", " . 
	$build_member_1['to_break_2'] . ", " . 
	$build_member_1['to_break_3'] . ", " . 
	$build_member_1['to_break_4'] . ", " . 
	$build_member_1['to_break_5'] . ", " . 
	$build_member_1['to_break_6'] . ", " . 
	$build_member_1['to_break_7'] . ", " . 
	$build_member_1['game_1'] . ", " . 
	$build_member_1['referee'] . ", " . 
	$build_member_1['roving'] . ", " . 
	$build_member_1['self'] . ", " . 
	$build_member_1['marker'] . ", " . 
	$build_member_1['table_no'] . ", " . 
	$build_member_1['round'] . ", " . 
	$build_member_1['start'] . ", " . 
	$build_member_1['finish'] . ", " . 
	$build_member_1['match_no'];

	// get scores data member 2
	$sql = "Select * FROM tournament_results WHERE memb_id = " . GetMemberID($player_name_2) . " and row_no = " . $row_2 . " and col_no = " . $col . " and tourn_id = " . $tourn_id;
	//echo($sql . "<br>");
	$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());

	$build_member_2 = $result_member->fetch_assoc();
	$member_2_data = 
	$build_member_2['memb_id'] . ", " . 
	intval($build_member_2['score_1']) . ", " . 
	intval($build_member_2['score_2']) . ", " . 
	intval($build_member_2['score_3']) . ", " . 
	intval($build_member_2['score_4']) . ", " . 
	intval($build_member_2['score_5']) . ", " . 
	intval($build_member_2['score_6']) . ", " . 
	intval($build_member_2['score_7']) . ", " . 
	$build_member_2['forfeit_2'] . ", " . 
	$build_member_2['walkover_2'] . ", " . 
	$build_member_2['breaks_1'] . ", " . 
	$build_member_2['breaks_2'] . ", " . 
	$build_member_2['breaks_3'] . ", " . 
	$build_member_2['breaks_4'] . ", " . 
	$build_member_2['breaks_5'] . ", " . 
	$build_member_2['breaks_6'] . ", " . 
	$build_member_2['breaks_7'] . ", " . 
	$build_member_2['to_break_1'] . ", " . 
	$build_member_2['to_break_2'] . ", " . 
	$build_member_2['to_break_3'] . ", " . 
	$build_member_2['to_break_4'] . ", " . 
	$build_member_2['to_break_5'] . ", " . 
	$build_member_2['to_break_6'] . ", " . 
	$build_member_2['to_break_7'] . ", " . 
	$build_member_2['game_1'] . ", " . 
	$build_member_2['referee'] . ", " . 
	$build_member_2['roving'] . ", " . 
	$build_member_2['self'] . ", " . 
	$build_member_2['marker'] . ", " . 
	$build_member_2['table_no'] . ", " . 
	$build_member_1['round'] . ", " . 
	$build_member_2['start'] . ", " . 
	$build_member_2['finish'] . ", " . 
	$build_member_2['match_no'];

	$memberArr = $member_1_data . ";" . $member_2_data;
	echo($memberArr);
}
else
{
	echo("No Data");
}


?>