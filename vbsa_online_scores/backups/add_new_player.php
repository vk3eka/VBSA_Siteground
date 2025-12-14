<?php 

include('connection.inc');
include('php_functions.php'); 

$clubname = $_GET['clubname'];
$firstname = $_GET['firstname'];
$surname = $_GET['surname'];
$team_grade = $_GET['team_grade'];
$type = $_GET['type'];
$current_year = $_GET['year'];
$season = $_GET['season'];
$email = $_GET['email'];
$mobile = $_GET['mobile'];
$user = $_GET['user'];
$round = $_GET['round'];

$surname = addslashes($surname);

$sql_member = "Select MemberID from members where FirstName = '" . $firstname . "' AND LastName = '" . $surname . "'";
//echo("Member - " . $sql_member . "<br>");
$result_member = $dbcnx_client->query($sql_member) or die("Couldn't execute member query. " . mysqli_error($dbcnx_client));
while($build_member = $result_member->fetch_assoc())
{
	$memberID = $build_member['MemberID'];
}

// emergency player or forfeit player
if($memberID == '')
{
	// get next memberid
	$sql_em_player = "Insert into members (FirstName, LastName, Email, MobilePhone, entered_on) VALUES ('" . ucfirst($firstname) . "', '" . ucfirst($surname) . "', '" . $email . "', '" . $mobile . "', '" . date('Y-m-d') . "')"; 
	$update = $dbcnx_client->query($sql_em_player);
/*
	$sql_em_player = "Insert into members (FirstName, LastName, Email, MobilePhone, entered_on) VALUES (?, ?, ?, ?, ?)"; 
	$stmt = $dbcnx_client->prepare($sql_em_player);
	$stmt->bind_param("sssss", ucfirst($firstname), ucfirst($surname), $email, $mobile, date('Y-m-d');
	$stmt->execute();

	$stmt->close();
*/
	//echo("Insert Member - " . $sql_em_player . "<br>");
	$sql_next = "Select MemberID, FirstName, LastName, MobilePhone from members where FirstName = '" . $firstname . "' AND LastName = '" . $surname . "'";
	//echo("Get Member ID - " . $sql_next . "<br>");
	$result_next = $dbcnx_client->query($sql_next) or die("Couldn't execute member query. " . mysqli_error($dbcnx_client));
	$build_next = $result_next->fetch_assoc();
	$memberID = $build_next['MemberID'];
	$mobile = $build_next['MobilePhone'];

	if(trim($firstname) != "Player")
	{
		$sql_emergency = "Insert into tbl_emergency (MemberID, FirstName, LastName, Email, MobilePhone, entered_on, Team, AddedBy) VALUES (" . $memberID . ", '" . ucfirst($firstname) . "', '" . ucfirst($surname) . "', '" . $email . "', '" . $mobile . "', '" . date('Y-m-d') . "', '" . $clubname . "', '" . $user . "')"; 
		$update = $dbcnx_client->query($sql_emergency);

		// send email to scores director
		$subject = 'VBSA New Non Member Player';  
		$message = '<html><body>';
		$message .= "<p>Scores Registrar</p>";
		$message .= "<p>&nbsp;</p>";
		$message .= "<p>A new non member player has been added.</p>";
		$message .= "<p>Date Added: " . date("F j, Y") . ".</p>";
		$message .= "<p>Name of player: -  " . $firstname . " " . stripslashes($surname) . "</p>";
		$message .= "<p>Email Address: - " . $email . ".</p>";
		$message .= "<p>Mobile Phone: - " . $mobile . ".</p>";
		$message .= "<p>Playing for: - " . $clubname . ".</p>";
		$message .= "<p>Team Grade: - " . $team_grade . ".</p>";
		$message .= "<p>Round No, : - " . $round . ".</p>";
		$message .= "<p>&nbsp;</p>";
		$message .= "<p>Thanks.</p>";
		$message .= "<p>Database Administrator.</p>";
		$message .= "</body></html>";
	    Sendemail($subject, $message, 'scores@vbsa.org.au');
	}
	elseif(trim($firstname) == "Player Forfeit")
	{
		// send email to scores director
		$subject = 'VBSA New Player Forfeit';  
		$message = '<html><body>';
		$message .= "<p>Scores Registrar</p>";
		$message .= "<p>&nbsp;</p>";
		$message .= "<p>A new player forfeit has been added.</p>";
		$message .= "<p>Added on: - " . date('Y-m-d') . ".</p>";
		$message .= "<p>Grade: - " . $team_grade . ".</p>";
		$message .= "<p>Playing for: - " . $clubname . ".</p>";
		$message .= "<p>&nbsp;</p>";
		$message .= "<p>Thanks.</p>";
		$message .= "<p>Database Administrator.</p>";
		$message .= "</body></html>";
	    Sendemail($subject, $message, 'scores@vbsa.org.au');
	}
}
// end emergency player

//echo("MemberID - " . $memberID . "<br>");
$sql_team = "Select team_id from Team_entries where team_name = '" . $clubname . "' AND team_grade = '". $team_grade . "' AND team_cal_year = " . $current_year;
//echo("Select Team - " . $sql_team . "<br>");

$result_team = $dbcnx_client->query($sql_team) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
while($build_team = $result_team->fetch_assoc())
{
	$teamID = $build_team['team_id'];
}

$sql_check = "Select team_id, MemberID, team_grade from scrs where team_id = '" . $teamID . "'  and team_grade = '" . $team_grade . "' and MemberID = '" . $memberID . "' and current_year_scrs = " . $current_year;
//echo("Sql Check - " . $sql_check . "<br>");
$result_check = $dbcnx_client->query($sql_check) or die("Couldn't execute team query. " . mysqli_error($dbcnx_client));
$num_rows_check = $result_check->num_rows;

if($num_rows_check == 0)
{
	$sql = "Insert into scrs (team_id, MemberID, team_grade, game_type, scr_season, current_year_scrs, count_played, pts_won, percent_won, total_RP, maxpts, average_position, captain_scrs, fin_year_scrs) 
Values (" . $teamID . ", " . $memberID . ", '" . $team_grade . "', '" . $type . "', '" . $season. "', " . $current_year . ", 0, 0, 0.00, 0, 3, 0.00, 0, " . $current_year . ")";
}
else
{
	$sql = "Update scrs Set team_id =  " . $teamID . ", MemberID = " . $memberID . ", team_grade = '" .  $team_grade . "', game_type = '" . $type . "', scr_season = '" . $season . "', current_year_scrs = " . $current_year  . " where team_grade = '" .  $team_grade . "' and current_year_scrs = " . $current_year  . " and MemberID = " . $memberID;
}
//echo("Update/Insert - " . $sql . "<br>");

if($dbcnx_client->query($sql) === true)
{
	return true;
}

?>