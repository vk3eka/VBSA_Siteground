<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

function GetMemberID($fullname)
{
	global $connvbsa;
	if($fullname != "Bye")
	{
		$sql = "Select MemberID, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($fullname) . "%'";
		$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
		$build_member = $result_member->fetch_assoc();
		return $build_member['MemberID'];
	}
}

$tourn_id = $_GET['tourn_id'];
$packeddata = json_decode(stripslashes($_GET['player_data']), true);
$packeddata = substr($packeddata, 0, -1);
$players = explode(":", $packeddata);

foreach($players as $player)
{
	$player = explode(", ", $player);
	$memb_id = GetMemberID($player[0]);
	
// added 

	// check id member data exists for this row/column
	$sql_select = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and row_no = " . $player[1] . " and col_no = " . ($player[2]+2);
	//echo($sql_select . "<br>");
	$result_select = mysql_query($sql_select, $connvbsa) or die(mysql_error());
	echo("Scores exist (rows) " . $result_select->num_rows . "<br>");
	if($result_select->num_rows == 0)
	{
		$sql_insert = "Insert into tournament_results (tourn_id, memb_id, row_no, col_no) VALUES (" . $tourn_id . ", " . $memb_id . ", " . $player[1] . ", " . ($player[2]+2) . ")";
		echo($sql_insert . "<br>");
		$update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}

	$sql_players_home = "Update tournament_results Set
	row_no = '" . $player[1] . "',
	col_no = '" . ($player[2]+2) . "',
	memb_id = " . $memb_id . " 
	Where tourn_id = " . $tourn_id . " and row_no = " . $player[1] . " and col_no = " . $player[2];
	//echo($sql_players_home . "<br>");
	$update = mysql_query($sql_players_home, $connvbsa) or die(mysql_error());


// end added

	// check id member name exists for this row/column
	$sql_player = "Select tourn_id, memb_id, row_no, col_no from tournament_players where tourn_id = " . $tourn_id . " and row_no = " . $player[1] . " and col_no = " . ($player[2]);
	//echo($sql_player . "<br>");
	$result_player = mysql_query($sql_player, $connvbsa) or die(mysql_error());
	$select_player_rows = $result_player->num_rows;
	if($select_player_rows > 0)
	{
		//$memb_id = GetMemberID($player[0]);
		$sql_update = "Update tournament_players Set 
			added_player = 1, 
			memb_id = " .  $memb_id . ", 
			fullname = '" .  trim($player[0]) . "' 
			where tourn_id = " . $tourn_id . " and row_no = " . $player[1] . " and col_no = " . ($player[2]);
		//echo($sql_update . "<br>");
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_players (
			memb_id, 
			added_player,
			row_no, 
			col_no, 
			fullname, 
			tourn_id) Values (
			" .  $memb_id . ", 
			1, 
			" .  $player[1] . ", 
			" .  $player[2] . ", 
			'" . trim($player[0]) . "', 
			" . $tourn_id . ')';
		//echo($sql_insert . "<br>");
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}

}
echo "Bye Data Updated";

?>