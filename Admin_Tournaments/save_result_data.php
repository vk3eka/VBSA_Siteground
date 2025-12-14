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
$packedscoredata_1 = json_decode(stripslashes($_GET['score_data_1']), true);
$packedscoredata_2 = json_decode(stripslashes($_GET['score_data_2']), true);
$packedscoredata_3 = json_decode(stripslashes($_GET['score_data_3']), true);
/*
echo("<pre>");
echo(var_dump($packedscoredata_1));
echo("</pre>");
echo("<pre>");
echo(var_dump($packedscoredata_2));
echo("</pre>");
echo("<pre>");
echo(var_dump($packedscoredata_3));
echo("</pre>");
*/
$score_1 = explode(",", $packedscoredata_1);
$score_2 = explode(",", $packedscoredata_2);
$score_3 = explode(",", $packedscoredata_3);

$index = 0;
foreach($score_1 as $score)
{
	//echo("Index " . $index . ", Score[] " . $score . "<br>");
	$index++;
}

$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($score_1[2]) . "%'";
//echo($sql . "<br>");
$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
$build_member = $result_member->fetch_assoc();
$member_id_1 = $build_member['MemberID'];
//echo($member_id_1 . "<br>");

$sql = "Select *, Concat(FirstName, ' ', LastName) as FullName FROM members WHERE Concat(FirstName, ' ', LastName) Like '%" . trim($score_2[2]) . "%'";
//echo($sql . "<br>");
$result_member = mysql_query($sql, $connvbsa) or die(mysql_error());
$build_member = $result_member->fetch_assoc();
$member_id_2 = $build_member['MemberID'];
//echo($member_id_2 . "<br>");

// check id member data exists for this row/column
$sql_select = "Select tourn_id, memb_id, row_no, col_no from tournament_results where tourn_id = " . $tourn_id . " and row_no = " . $score_1[0] . " and col_no = " . $score_1[1];
//echo($sql_select . "<br>");
$result_select = mysql_query($sql_select, $connvbsa) or die(mysql_error());
//echo("Scores exist (rows) " . $result_select->num_rows . "<br>");
if($result_select->num_rows == 0)
{
	$sql_insert = "Insert into tournament_results (tourn_id, memb_id, row_no, col_no) VALUES (" . $tourn_id . ", " . $member_id_1 . ", " . $score_1[0] . ", " . $score_1[1] . ")";
	//echo($sql_insert . "<br>");
	$update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

$sql_players_home = "Update tournament_results Set
game_1 = '" .  $score_1[3] . "',
score_1 = '" .  $score_1[4] . "',
score_2 = '" .  $score_1[5] . "',
score_3 = '" .  $score_1[6] . "',
score_4 = '" .  $score_1[7] . "',
score_5 = '" .  $score_1[8] . "',
score_6 = '" .  $score_1[9] . "',
score_7 = '" .  $score_1[10] . "',
forfeit_1 = '" . $score_1[11] . "',
walkover_1 = '" . $score_1[12] . "',
breaks_1 = '" .  $score_1[13] . "',
breaks_2 = '" .  $score_1[14] . "',
breaks_3 = '" .  $score_1[15] . "',
breaks_4 = '" .  $score_1[16] . "',
breaks_5 = '" .  $score_1[17] . "',
breaks_6 = '" .  $score_1[18] . "',
breaks_7 = '" .  $score_1[19] . "',
to_break_1 = '" .  $score_1[20] . "',
to_break_2 = '" .  $score_1[21] . "',
to_break_3 = '" .  $score_1[22] . "',
to_break_4 = '" .  $score_1[23] . "',
to_break_5 = '" .  $score_1[24] . "',
to_break_6 = '" .  $score_1[25] . "',
to_break_7 = '" .  $score_1[26] . "',
row_no = '" . $score_1[0] . "',
col_no = '" . $score_1[1] . "',
memb_id = " . $member_id_1 . " 
Where tourn_id = " . $tourn_id . " and row_no = " . $score_1[0] . " and col_no = " . $score_1[1];
//echo($sql_players_home . "<br>");
$update = mysql_query($sql_players_home, $connvbsa) or die(mysql_error());

// check id member data exists for this row/column
$sql_select = "Select memb_id from tournament_results where tourn_id = " . $tourn_id . " and row_no = " . $score_2[0] . " and col_no = " . $score_2[1];
//echo($sql_select . "<br>");
$result_select = mysql_query($sql_select, $connvbsa) or die(mysql_error());
if($result_select->num_rows == 0)
{
	$sql_insert = "Insert into tournament_results (tourn_id, memb_id, row_no, col_no) VALUES (" . $tourn_id . ", " . $member_id_2 . ", " . $score_2[0] . ", " . $score_2[1] . ")";
	//echo($sql_insert . "<br>");
	$update = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

$sql_players_away = "Update tournament_results Set
game_1 = '" .  $score_2[3] . "',
score_1 = '" .  $score_2[4] . "',
score_2 = '" .  $score_2[5] . "',
score_3 = '" .  $score_2[6] . "',
score_4 = '" .  $score_2[7] . "',
score_5 = '" .  $score_2[8] . "',
score_6 = '" .  $score_2[9] . "',
score_7 = '" .  $score_2[10] . "',
forfeit_2 = '" . $score_2[11] . "',
walkover_2 = '" . $score_2[12] . "',
breaks_1 = '" .  $score_2[13] . "',
breaks_2 = '" .  $score_2[14] . "',
breaks_3 = '" .  $score_2[15] . "',
breaks_4 = '" .  $score_2[16] . "',
breaks_5 = '" .  $score_2[17] . "',
breaks_6 = '" .  $score_2[18] . "',
breaks_7 = '" .  $score_2[19] . "',
to_break_1 = '" .  $score_2[20] . "',
to_break_2 = '" .  $score_2[21] . "',
to_break_3 = '" .  $score_2[22] . "',
to_break_4 = '" .  $score_2[23] . "',
to_break_5 = '" .  $score_2[24] . "',
to_break_6 = '" .  $score_2[25] . "',
to_break_7 = '" .  $score_2[26] . "',
row_no = '" . $score_2[0] . "',
col_no = '" . $score_2[1] . "',
memb_id = " . $member_id_2 . "
Where tourn_id = " . $tourn_id . " and row_no = " . $score_2[0] . " and col_no = " . $score_2[1];
//echo($sql_players_away . "<br>");
$update = mysql_query($sql_players_away, $connvbsa) or die(mysql_error());

$sql_players_1 = "Update tournament_results Set 
referee = '" . $score_3[0] . "', 
roving = '" . $score_3[1] . "', 
self = '" . $score_3[2] . "', 
marker = '" . $score_3[3] . "', 
table_no = '" . $score_3[4] . "', 
round = '" . $score_3[5] . "', 
start = '" . $score_3[6] . "', 
finish = '" . $score_3[7] . "', 
match_no = '" . $score_3[8] . "' 
Where tourn_id = " . $tourn_id . " and memb_id = " . $member_id_1;  
//echo($sql_players_1 . "<br>");
$update = mysql_query($sql_players_1, $connvbsa) or die(mysql_error());

$sql_players_2 = "Update tournament_results Set 
referee = '" . $score_3[0] . "', 
roving = '" . $score_3[1] . "', 
self = '" . $score_3[2] . "', 
marker = '" . $score_3[3] . "', 
table_no = '" . $score_3[4] . "', 
round = '" . $score_3[5] . "', 
start = '" . $score_3[6] . "', 
finish = '" . $score_3[7] . "', 
match_no = '" . $score_3[8] . "' 
Where tourn_id = " . $tourn_id . " and memb_id = " . $member_id_2;  
//echo($sql_players_2 . "<br>");
$update = mysql_query($sql_players_2, $connvbsa) or die(mysql_error());

// add tournament winner data

// get winner name
$move_to_col = $score_1[28];
//echo("Move col " . $move_to_col . "<br>");
if((int)($score_1[3]) > (int)($score_2[3]))
{
	//echo("Winner is " . $score_1[2] . "<br>");
	$winner_id = $score_1[2];
	$move_to_row = $score_1[27];
}
if((int)($score_1[3]) < (int)($score_2[3]))
{
	//echo("Winner is " . $score_2[2] . "<br>");
	$winner_id = $score_2[2];
	$move_to_row = $score_2[27];
}
//echo("Move row " . $move_to_row . "<br>");
// check id member name exists for this row/column
$memb_id = GetMemberID($winner_id);
$sql_player = "Select tourn_id, memb_id, row_no, col_no from tournament_players where tourn_id = " . $tourn_id . " and row_no = " . $move_to_row . " and col_no = " . $move_to_col;
//echo($sql_player . "<br>");
$result_player = mysql_query($sql_player, $connvbsa) or die(mysql_error());
$select_player_rows = $result_player->num_rows;
if($select_player_rows > 0)
{
	$sql_update = "Update tournament_players Set 
		added_player = 1, 
		memb_id = " .  $memb_id . ", 
		fullname = '" .  trim($winner_id) . "' 
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
		'" . trim($winner_id) . "', 
		" . $tourn_id . ')';
	//echo($sql_insert . "<br>");
	$insert = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
}

echo "Score Data Updated";

?>