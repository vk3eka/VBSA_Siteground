<?php 

include('connection.inc');
include('php_functions.php'); 

$team_name = $_GET['team_name'];
$firstname = $_GET['firstname'];
$surname = $_GET['surname'];
$team_grade = $_GET['team_grade'];
$club_name = $_GET['club_name'];
$teamID = $_GET['team_id'];
//echo($teamID . "<br>");

$surname = addslashes($surname);

$sql_member = "Select MemberID from members where FirstName = '" . $firstname . "' AND LastName = '" . $surname . "'";
//echo("Member - " . $sql_member . "<br>");
$result_member = $dbcnx_client->query($sql_member) or die("Couldn't execute member query. " . mysqli_error($dbcnx_client));
while($build_member = $result_member->fetch_assoc())
{
	$memberID = $build_member['MemberID'];
}

/*
$sql_club_players = "Insert INTO tbl_team_rego 
( 
member_id, 
firstname,
lastname,
selected,
captain_scrs,
team_id,
team_grade,
team_name
)
values 
(
" . $memberID . ", 
'" . $firstname . "',
'" . $surname . "',
0,
0,
" . $teamID . ",
'" . $team_grade . "',
'" . $team_name . "')";
*/

$sql_club_players = "Insert INTO tbl_team_rego 
( 
member_id, 
firstname,
lastname,
selected,
captain_scrs,
club_name,
team_grade,
team_name
)
values 
(
" . $memberID . ", 
'" . $firstname . "',
'" . $surname . "',
0,
0,
'" . $club_name . "',
'" . $team_grade . "',
'" . $team_name . "')";

//echo($sql_club_players . "<br>");
if($dbcnx_client->query($sql_club_players) === true)
{
	return true;
}

?>