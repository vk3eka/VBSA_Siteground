<?php
require_once('../Connections/connvbsa.php'); 
include('../vbsa_online_scores/php_functions.php'); 

mysql_select_db($database_connvbsa, $connvbsa);

$tournament_id = $_GET['tourn_id'];
$tournament_name = stripslashes($_GET['tourn_name']);

function GetMemberName($memberid)
{
  global $connvbsa;
  global $database_connvbsa;
  $query_member = 'Select FirstName, LastName, Email FROM vbsa3364_vbsa2.members where MemberID = ' . $memberid;
  $result_member = mysql_query($query_member, $connvbsa) or die(mysql_error());
  $build_member = $result_member->fetch_assoc();
  $member_name = $build_member['FirstName'] . " " . $build_member['LastName'];
  $member_email = $build_member['Email'];
  return $member_name . ", " . $member_email;
}

// get list of players for this tournament
$query_players = 'Select * FROM vbsa3364_vbsa2.tournament_scores where tourn_id = ' . $tournament_id;
$result_players = mysql_query($query_players, $connvbsa) or die(mysql_error());
// send email to all players
while($build_players = $result_players->fetch_assoc())
{
	$Member_Email = explode(", ", GetMemberName($build_players['member_id']));
	$fullname = $Member_Email[0];
	$email = $Member_Email[1];

	$subject = $tournament_name . " draw announced.";  
	$message = '<html><body>';
	$message .= "<p>" . $fullname . "</p>";
	$message .= "<p>The draw for the " . $tournament_name . " has been released.</p>";
	$message .= "<p>Please check the draw/schedule and Terms and Conditions on the <a href='https://vbsa.org.au/Tournaments/tourn_draw.php?tourn_id=" . $tournament_id . "'> website.</a></p>";
	$message .= "<p>If you believe you may have to withdraw, please email the <a href='mailto: tournaments@vbsa.org.au'>tournament director</a> as soon as possible.</p>";
	$message .= "<p>Thanks.</p>";
	$message .= "<p>Tournament Director.</p>";
	$message .= "</body></html>";
	Sendemail($subject, $message, $email);
}
//echo($message . "<br>");
echo("Email's Sent");

?>