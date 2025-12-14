<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tourn_id = $_GET['tourn_id'];
$packeddata = json_decode(stripslashes($_GET['player_data']), true);
$players = explode(", ", $packeddata);

//echo("<pre>");
//echo(var_dump($packeddata));
//echo("</pre>");


function GetMemberID($fullname, $bye_id)
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
	else if($fullname === "Bye")
	{
		return $bye_id;
	}
}

function GetRankNum($memberID, $tourn_id)
{
	global $connvbsa;
	$sql_rank = "Select tourn_memb_id, ranknum FROM tourn_entry Left Join rank_S_open_tourn on tourn_entry.tourn_memb_id = rank_S_open_tourn.memb_id where tournament_number = " . $tourn_id . " and tourn_memb_id = " . $memberID;
	//echo($sql_rank . "<br>");
	$result_rank = mysql_query($sql_rank, $connvbsa) or die(mysql_error());
	$build_rank = $result_rank->fetch_assoc();

	if(!empty($build_rank['ranknum']))
	{
		return $build_rank['ranknum'];
	}
	else
	{
		return 0;
	}
}

// check if tournaments_players table already has players
$sql_select = "Select memb_id From tournament_players Where tourn_id = " . $tourn_id;
$result_select = mysql_query($sql_select, $connvbsa) or die(mysql_error());
$total_select = $result_select->num_rows;
$bye_id = 10000;
foreach($players as $player)
{
	$id = explode(": ", $player);
	if(!empty($id[1]))
	{
		$subStr = '_';
		$first = strpos($id[1], $subStr);
		$second = strpos($id[1], $subStr, $first + 1);
	    $row = substr($id[1], 0, $first);
	    $col_1 = substr($id[1], ($first+1));
	    $col_2 = substr($id[1], ($second+1));
	    $memb_id = GetMemberID($id[0], $bye_id);
	    $ranknum = GetRankNum($memb_id, $tourn_id);
	    $fullname = $id[0];
		if(($total_select == 0) && ($fullname != ''))
		{
			$sql_insert = "Insert INTO tournament_players  
			(row_no,
			col_no, 
			tourn_id, 
			ranknum, 
			memb_id, 
			fullname) Values (
			" . $row . ",
			" . $col_2 . ", 
			" . $tourn_id . ", 
			" . $ranknum . ", 
			" . $memb_id . ", 
			'" . $fullname . "')";
			//echo($sql_insert . "<br>");
			$result = mysql_query($sql_insert, $connvbsa) or die(mysql_error());
		}
		else if(($total_select > 0) && ($fullname != ''))
		{
			$sql_update = "Update tournament_players Set
			row_no = " .  $row . ",
			col_no = " .  $col_2 . ",
			ranknum = " .  $ranknum . ",
			fullname = '" .  $fullname . "'
			Where tourn_id = " . $tourn_id . " and memb_id = " . $memb_id; 
			//echo($sql_update . "<br>"); 
			$update = mysql_query($sql_update, $connvbsa) or die(mysql_error());
		}
	}
	$bye_id++;
}

echo "Player Data Updated";

?>