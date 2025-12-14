<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$page = "../Admin_DB_VBSA/vbsa_captains_test.php?season=$season";
$_SESSION['page'] = $page;

include('report_header.php'); ?>

<?php

// data query

mysql_select_db($database_connvbsa, $connvbsa);

// get data to email
//$query_memb = "Select members.MemberID, members.ReceiveEmail, LastName, FirstName, MobilePhone, Email, captain_scrs, Team_entries.team_id, Team_entries.team_grade, Team_entries.team_club, Team_entries.team_name, day_played, comptype FROM members LEFT JOIN scrs ON scrs.MemberID=members.MemberID LEFT JOIN Team_entries on Team_entries.team_id=scrs.team_id WHERE captain_scrs=1 AND team_cal_year = YEAR( CURDATE( ) ) AND scr_season='$season' AND ReceiveEmail=1 ORDER BY Team_entries.team_grade, Team_entries.team_club";

$query_memb = 'Select Email from test_members';
//echo($query_memb . "<br>");

$memb = mysql_query($query_memb, $connvbsa) or die(mysql_error());
$row_memb = mysql_fetch_assoc($memb);
$totalRows_memb = mysql_num_rows($memb);

$myRecordset=$memb; $myTotalRecords=$totalRows_memb; 

include 'php_mail_include.php';

include 'report_footer.php';

?>