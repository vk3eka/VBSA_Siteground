<?php
require_once('../Connections/connvbsa.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$TournType = $_GET['tourn_type'];
$TournID = $_GET['tourn_id'];
$MemberID = $_GET['player_id'];
$Element = $_GET['element_id'];
$Existing = $_GET['existing'];
$New_Member = $_GET['new_player'];
/*
echo("Type " . $TournType . "<br>");
echo("Tournament " . $TournID . "<br>");
echo("Member ID " . $MemberID . "<br>");
echo("Element " . $Element . "<br>");
echo("New Player " . $New_Member . "<br>");
*/

// need to update tourn_entries table


// get rank num
$player_name = explode(" ", $New_Member);
$firstname = $player_name[0];
$lastname = $player_name[1];
$fullname = $player_name[0] . " " . $player_name[1];
$query_players = "Select MemberID FROM members where FirstName = '" . $firstname . "' and Lastname = '" . $lastname . "'";
//echo($query_players . "<br>");
$result = mysql_query($query_players, $connvbsa) or die(mysql_error());
$build_players = $result->fetch_assoc();
$New_Member_ID = $build_players['MemberID'];

// get rank num
if($TournType == 'Billiards')
{
   $query_tourn_players = 'Select ranknum, FirstName, LastName FROM rank_Billiards left join members on MemberID = memb_id where memb_id = ' . $New_Member_ID;
}
else if($TournType == 'Snooker')
{
   $query_tourn_players = 'Select ranknum, FirstName, LastName FROM rank_S_open_tourn left join members on MemberID = memb_id where memb_id = ' . $New_Member_ID;
}
//echo($query_tourn_players . "<br>");
$result = mysql_query($query_tourn_players, $connvbsa) or die(mysql_error());
$build_tourn = $result->fetch_assoc();
if($result->num_rows > 0)
{
   $ranknum = $build_tourn['ranknum'];
   //$fullname = $build_tourn['FirstName'] . " " . $build_tourn['LastName'];

}
else
{
   $ranknum = 0;
   //$fullname = $New_Member;
}

$sql_tourn_entry = ("Update tourn_entry SET 
   tourn_memb_id = " . $New_Member_ID . "
   WHERE tourn_memb_id = " . $MemberID . " and tournament_number = " . $TournID);
//echo($sql_tourn_entry . "<br>");
$update = mysql_query($sql_tourn_entry, $connvbsa) or die(mysql_error());
if(!$update)
{
    die("Could not update data: " . mysql_error());
} 

$sql_tourn_scores = ("Update tournament_players SET 
   memb_id = " . $New_Member_ID . ", 
   fullname = '" . $fullname . "'
   WHERE memb_id = " . $MemberID . " and tourn_id = " . $TournID);
//echo($sql . "<br>");
$update = mysql_query($sql_tourn_scores, $connvbsa) or die(mysql_error());
if(!$update)
{
    die("Could not update data: " . mysql_error());
} 

$sql_tourn_scores = ("Update tournament_results SET 
   memb_id = " . $New_Member_ID . " 
   WHERE memb_id = " . $MemberID . " and tourn_id = " . $TournID);
//echo($sql . "<br>");
$update = mysql_query($sql_tourn_scores, $connvbsa) or die(mysql_error());
if(!$update)
{
    die("Could not update data: " . mysql_error());
} 

echo("Data Updated");
?>
