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

/*
echo("Column No " . $col . "<br>");
echo("Winner Score " . $game_1 . "<br>");
echo("Loser Score " . $game_2 . "<br>");
echo("Winner Name " . $winner_name . "<br>");
echo("Move to Row " . $move_to_row . "<br>");
echo("Move to Col " . $move_to_col . "<br>");
*/

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

if($col == 20) // finals
{
	$row_1 = $move_to_row;
	$row_2 = $move_to_row;
	$col = ($move_to_col);
}

if($col == 22) // grand final
{
	// check id member data exists for this row/column
	//$sql_select_1 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and memb_id = " . GetMemberID($winner_name) . " and row_no = " . $move_to_row . " and col_no = " . $move_to_col;
	
	$sql_select_1 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " row_no = " . $move_to_row . " and col_no = " . $move_to_col;
	//echo($sql_select_1 . "<br>");
	$result_select_1 = mysql_query($sql_select_1, $connvbsa) or die(mysql_error());
	$select_1_rows = $result_select_1->num_rows;
	// add score to database
	if($select_1_rows > 0)
	{
		$sql_update = "Update tournament_results Set 
			game_1 = " .  $game_1 . ", 
			score_1 = " .  $game_1 . ",
			memb_id = " . GetMemberID($winner_name) . ", 
			where tourn_id = " . $tourn_id . " and row_no = " . $move_to_row . " and col_no = " . $col;
		/*$sql_update = "Update tournament_results Set 
			game_1 = " .  $game_1 . ", 
			score_1 = " .  $game_1 . " 
			where tourn_id = " . $tourn_id . " and row_no = " . $move_to_row . " and col_no = " . $col . " and memb_id = " . GetMemberID($winner_name);
		*/
		//echo($sql_update . "<br>");
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_results ( 
			memb_id,
			row_no, 
			col_no, 
			game_1, 
			score_1,
			tourn_id) Values (
			" .  GetMemberID($winner_name) . ", 
			" .  $move_to_row . ", 
			" .  $col . ", 
			" .  $game_1 . ", 
			" .  $game_1 . ", 
			" . $tourn_id . ')';
		//echo($sql_insert . "<br>");
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}
}
else
{
	if(trim($player_1) != 'Bye')
	{
		$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($player_1) . "%'";
		//echo($sql . "<br>");
		$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
		$build_member = $result_member->fetch_assoc();
		$member_id_1 = $build_member['MemberID'];
		//echo($member_id_1 . "<br>");
	}
	else
	{
		$member_id_1 = 1;
	}

	if(trim($player_2) != 'Bye')
	{
		$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($player_2) . "%'";
		//echo($sql . "<br>");
		$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
		$build_member = $result_member->fetch_assoc();
		$member_id_2 = $build_member['MemberID'];
		//echo($member_id_2 . "<br>");
	}
	else
	{
		$member_id_2 = 1;
	}

	// check id member data exists for this row/column
	//$sql_select_1 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and memb_id = " . $member_id_1 . " and row_no = " . $row_1 . " and col_no = " . $col;
	
	$sql_select_1 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and row_no = " . $row_1 . " and col_no = " . $col;
	//echo($sql_select_1 . "<br>");
	$result_select_1 = mysql_query($sql_select_1, $connvbsa) or die(mysql_error());
	$select_1_rows = $result_select_1->num_rows;
	if($select_1_rows > 0)
	{
		$sql_update = "Update tournament_results Set 
			game_1 = " .  $game_1 . ", 
			score_1 = " .  $game_1 . " ,
			memb_id = " . $member_id_1 . "
			where tourn_id = " . $tourn_id . " and row_no = " . $row_1 . " and col_no = " . $col;
		//echo($sql_update . "<br>");
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_results ( 
			memb_id,
			row_no, 
			col_no, 
			game_1, 
			score_1, 
			tourn_id) Values (
			" .  $member_id_1 . ", 
			" .  $row_1 . ", 
			" .  $col . ", 
			" .  $game_1 . ", 
			" .  $game_1 . ", 
			" . $tourn_id . ')';
			//echo($sql_insert . "<br>");
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}

	// check id member data exists for this row/column
	//$sql_select_2 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and memb_id = " . $member_id_2 . " and row_no = " . $row_2 . " and col_no = " . $col;
	
	$sql_select_2 = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and row_no = " . $row_2 . " and col_no = " . $col;
	//echo($sql_select_2 . "<br>");
	$result_select_2 = mysql_query($sql_select_2, $connvbsa) or die(mysql_error());
	$select_2_rows = $result_select_2->num_rows;
	if($select_2_rows > 0)
	{
		$sql_update = "Update tournament_results Set 
			game_1 = " .  $game_2 . ", 
			score_1 = " .  $game_2 . ",
			memb_id = " . $member_id_2 . "
			where tourn_id = " . $tourn_id . " and row_no = " . $row_2 . " and col_no = " . $col;
		//echo($sql_update . "<br>");
		$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
	}
	else
	{
		$sql_insert = "Insert into tournament_results (
			memb_id, 
			row_no, 
			col_no, 
			game_1,
			score_1, 
			tourn_id) Values (
			" .  $member_id_2 . ", 
			" .  $row_2 . ", 
			" .  $col . ", 
			" .  $game_2 . ", 
			" .  $game_2 . ", 
			" . $tourn_id . ')';
		//echo($sql_insert . "<br>");
		$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
	}
}

// check id member name exists for this row/column
$memb_id = GetMemberID($winner_name);
$sql_player = "Select tourn_id, memb_id, row_no, col_no from tournament_players where tourn_id = " . $tourn_id . " and fullname = '" . $winner_name . "' and row_no = " . $move_to_row . " and col_no = " . $move_to_col . " and memb_id = " . $memb_id;
//echo($sql_player . "<br>");
$result_player = mysql_query($sql_player, $connvbsa) or die(mysql_error());
$select_player_rows = $result_player->num_rows;
if($select_player_rows > 0)
{
	$sql_update = "Update tournament_players Set 
		added_player = 1, 
		memb_id = " .  $memb_id . ", 
		fullname = '" .  $winner_name . "' 
		where tourn_id = " . $tourn_id . " and row_no = " . $move_to_row . " and col_no = " . $move_to_col;
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
		" .  $move_to_row . ", 
		" .  $move_to_col . ", 
		'" .  $winner_name . "', 
		" . $tourn_id . ')';
	//echo($sql_insert . "<br>");
	$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

echo('Match Updated');
?>