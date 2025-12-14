<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$row_1 = $_GET['winner_row_no'];
$row_2 = $_GET['opp_row_no'];
$col = ($_GET['common_col']-1); // name column
$game_1 = $_GET['winner_score'];
$game_2 = $_GET['opp_score'];
$player_1 = $_GET['player_1'];
$player_2 = $_GET['player_2'];
$winner_name = $_GET['winner_name'];
$move_to_row = $_GET['move_to_row'];
$move_to_col = $_GET['move_to_col'];

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

if($col == 40) // finals
{
	$row_1 = $move_to_row;
	$row_2 = $move_to_row;
	$col = ($move_to_col);
}

if($col == 42) // grand final
{
	// check id member data exists for this row/column
	$sql_select_1 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and memb_id = " . GetMemberID($winner_name) . " and row_no = " . $move_to_row . " and col_no = " . $move_to_col;
	$result_select_1 = mysql_query($sql_select_1, $connvbsa) or die(mysql_error());
	$select_1_rows = $result_select_1->num_rows;
	// add score to database
	if($select_1_rows > 0)
	{
		$sql_update = "Update tournament_results Set 
			row_no = " .  $move_to_row . ", 
			col_no = " .  $col . ", 
			game_1 = " .  $game_1 . " 
			where tourn_id = " . $tourn_id . " and row_no = " . $move_to_row . " and col_no = " . $col . " and memb_id = " . GetMemberID($winner_name);
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_results ( 
			memb_id,
			row_no, 
			col_no, 
			game_1, 
			tourn_id) Values (
			" .  GetMemberID($winner_name) . ", 
			" .  $move_to_row . ", 
			" .  $col . ", 
			" .  $game_1 . ", 
			" . $tourn_id . ')';
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}
}
else
{
	$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($player_1) . "%'";
	$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
	$build_member = $result_member->fetch_assoc();
	$member_id_1 = $build_member['MemberID'];

	$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($player_2) . "%'";
	$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
	$build_member = $result_member->fetch_assoc();
	$member_id_2 = $build_member['MemberID'];

	// check id member data exists for this row/column
	$sql_select_1 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and memb_id = " . $member_id_1 . " and row_no = " . $row_1 . " and col_no = " . $col;
	$result_select_1 = mysql_query($sql_select_1, $connvbsa) or die(mysql_error());
	$select_1_rows = $result_select_1->num_rows;
	if($select_1_rows > 0)
	{
		$sql_update = "Update tournament_results Set 
			row_no = " .  $row_1 . ", 
			col_no = " .  $col . ", 
			game_1 = " .  $game_1 . " 
			where tourn_id = " . $tourn_id . " and row_no = " . $row_1 . " and col_no = " . $col . " and memb_id = " . $member_id_1;
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_results ( 
			memb_id,
			row_no, 
			col_no, 
			game_1, 
			tourn_id) Values (
			" .  $member_id_1 . ", 
			" .  $row_1 . ", 
			" .  $col . ", 
			" .  $game_1 . ", 
			" . $tourn_id . ')';
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}

	// check id member data exists for this row/column
	$sql_select_2 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and memb_id = " . $member_id_2 . " and row_no = " . $row_2 . " and col_no = " . $col;
	$result_select_2 = mysql_query($sql_select_2, $connvbsa) or die(mysql_error());
	$select_2_rows = $result_select_2->num_rows;
	if($select_2_rows > 0)
	{
		$sql_update = "Update tournament_results Set 
			row_no = " .  $row_2 . ", 
			col_no = " .  $col . ", 
			game_1 = " .  $game_2 . " 
			where tourn_id = " . $tourn_id . " and row_no = " . $row_2 . " and col_no = " . $col . " and memb_id = " . $member_id_2;
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_results (
			memb_id, 
			row_no, 
			col_no, 
			game_1, 
			tourn_id) Values (
			" .  $member_id_2 . ", 
			" .  $row_2 . ", 
			" .  $col . ", 
			" .  $game_2 . ", 
			" . $tourn_id . ')';
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}
}

// check id member name exists for this row/column
$memb_id = GetMemberID($winner_name);
$sql_player = "Select tourn_id, memb_id, row_no, col_no from tournament_players where tourn_id = " . $tourn_id . " and fullname = '" . $winner_name . "' and row_no = " . $move_to_row . " and col_no = " . $move_to_col . " and memb_id = " . $memb_id;
$result_player = mysql_query($sql_player, $connvbsa) or die(mysql_error());
$select_player_rows = $result_player->num_rows;
if($select_player_rows > 0)
{
	$sql_update = "Update tournament_players Set 
		added_player = 1, 
		row_no = " .  $move_to_row . ", 
		col_no = " .  $move_to_col . ", 
		memb_id = " .  $memb_id . ", 
		fullname = '" .  $winner_name . "' 
		where tourn_id = " . $tourn_id . " and row_no = " . $move_to_row . " and col_no = " . $move_to_col;
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
		" .  $move_to_row . ", 
		" .  $move_to_col . ", 
		'" .  $winner_name . "', 
		" . $tourn_id . ')';
	$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

echo('Match Updated');
?>