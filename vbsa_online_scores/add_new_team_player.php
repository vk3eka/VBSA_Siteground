<?php 

include('connection.inc');
include('php_functions.php'); 

$clubname = htmlspecialchars($_GET['clubname']);
$teamname = $_GET['teamname'];
$firstname = $_GET['firstname'];
$surname = $_GET['surname'];
$team_grade = $_GET['team_grade'];
$type = $_GET['type'];
$current_year = $_GET['year'];
$season = $_GET['season'];
$email = $_GET['email'];
$mobile = $_GET['mobile'];
$user = $_GET['user'];
$gender = $_GET['gender'];
$previous = $_GET['previous'];
if($_GET['previous'] == 1)
{
	$previous = "Yes";
}
else
{
	$previous = "No";
}
$teamID = $_GET['teamID'];
//echo($previous . "<br>");

$surname = addslashes($surname);

$sql_member = "Select MemberID from members where FirstName = '" . $firstname . "' AND LastName = '" . $surname . "'";
//echo("Member - " . $sql_member . "<br>");
$result_member = $dbcnx_client->query($sql_member) or die("Couldn't execute member query. " . mysqli_error($dbcnx_client));
while($build_member = $result_member->fetch_assoc())
{
	$memberID = $build_member['MemberID'];
}
//echo("Member ID - " . $memberID . "<br>");
// new player
if($memberID == '')
{
	// get next memberid
	$sql_em_player = "Insert into members (FirstName, LastName, Email, MobilePhone, entered_on) VALUES ('" . ucfirst($firstname) . "', '" . ucfirst($surname) . "', '" . $email . "', '" . $mobile . "', '" . date('Y-m-d') . "')"; 
	//echo("Insert Member - " . $sql_em_player . "<br>");
	$update = $dbcnx_client->query($sql_em_player);

	$sql_next = "Select MemberID, FirstName, LastName from members where FirstName = '" . $firstname . "' AND LastName = '" . $surname . "'";
	//echo("Get Member ID - " . $sql_next . "<br>");
	$result_next = $dbcnx_client->query($sql_next) or die("Couldn't execute member query. " . mysqli_error($dbcnx_client));
	$build_next = $result_next->fetch_assoc();
	$memberID = $build_next['MemberID'];

	if(trim($firstname) != "Player")
	{
		$sql_emergency = "Insert into tbl_emergency (MemberID, FirstName, LastName, Email, MobilePhone, entered_on, Team, AddedBy, Gender) VALUES (" . $memberID . ", '" . ucfirst($firstname) . "', '" . ucfirst($surname) . "', '" . $email . "', '" . $mobile . "', '" . date('Y-m-d') . "', '" . $teamname . "', '" . $user . "', '" . $gender . "')"; 
		//echo($sql_emergency . "<br>");
		//$update = $dbcnx_client->query($sql_emergency);

		// send email to members director

		$subject = 'VBSA Team Registration';  
		$message = '<html><body>';
		$message .= "<p>Members Registrar</p>";
		$message .= "<p>&nbsp;</p>";
		$message .= "<p>A new non member player has registered to play.</p>";
		$message .= "<p>Date Added: " . date("F j, Y") . ".</p>";
		$message .= "<p>Name of player: -  " . $firstname . " " . stripslashes($surname) . "</p>";
		$message .= "<p>Email Address: - " . $email . ".</p>";
		$message .= "<p>Gender: - " . ucfirst($gender) . ".</p>";
		$message .= "<p>Played Previously: - " . $previous . ".</p>";
		$message .= "<p>Mobile Phone: - " . $mobile . ".</p>";
		$message .= "<p>Playing for: - " . $teamname . ".</p>";
		$message .= "<p>Team Grade: - " . $team_grade . ".</p>";
		$message .= "<p>&nbsp;</p>";
		$message .= "<p>Thanks.</p>";
		$message .= "<p>Database Administrator.</p>";
		$message .= "</body></html>";
	    Sendemail($subject, $message, 'members@vbsa.org.au');
	}
}
// end emergency player

$sql_club_players = "Insert INTO tbl_team_rego 
( 
member_id, 
firstname,
lastname,
selected,
captain_scrs,
club_name,
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
'" . $clubname . "',
" . $teamID . ",
'" . $team_grade . "',
'" . $teamname . "')";

//echo($sql_club_players . "<br>");
$update = $dbcnx_client->query($sql_club_players);
if(!$update)
{
    die("Could not insert bye team data: " . mysqli_error($dbcnx_client));
} 
else
{
	echo('Data Updated');
}
//echo('true');
?>