<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$player_name = $_GET['player_name'];
$row = $_GET['row'];
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

$sql = "Select * FROM tournament_results WHERE memb_id = " . GetMemberID($player_name) . " and row_no = " . $row . " and col_no = " . $col . " and tourn_id = " . $tourn_id;
//echo($sql . "<br>");
$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
if($result_member->num_rows > 0)
{
	$build_member = $result_member->fetch_assoc();
	$member_data = 
	$build_member['memb_id'] . ", " . 
	intval($build_member['score_1']) . ", " . 
	intval($build_member['score_2']) . ", " . 
	intval($build_member['score_3']) . ", " . 
	intval($build_member['score_4']) . ", " . 
	intval($build_member['score_5']) . ", " . 
	intval($build_member['score_6']) . ", " . 
	intval($build_member['score_7']) . ", " . 
	$build_member['forfeit_1'] . ", " . 
	$build_member['walkover_1'] . ", " . 
	$build_member['breaks_1'] . ", " . 
	$build_member['breaks_2'] . ", " . 
	$build_member['breaks_3'] . ", " . 
	$build_member['breaks_4'] . ", " . 
	$build_member['breaks_5'] . ", " . 
	$build_member['breaks_6'] . ", " . 
	$build_member['breaks_7'] . ", " .
	$build_member['game_1']; 

	echo($member_data);
}
else
{
	echo("No Data");
}


?>