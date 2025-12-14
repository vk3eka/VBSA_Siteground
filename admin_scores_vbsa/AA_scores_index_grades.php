<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../page_error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;    
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}
//$season = "S2";

mysql_select_db($database_connvbsa, $connvbsa);
$query_teamSnook = "Select COUNT( Team_entries.team_grade ) AS teams, team_grade, day_played, grade_name, fix_upload, team_season, comptype, current FROM Team_entries LEFT JOIN Team_grade ON grade=team_grade AND season=team_season AND team_cal_year=fix_cal_year WHERE team_season ='$season' AND include_draw = 'Yes'  AND type='Snooker' AND team_cal_year = YEAR( CURDATE( ) ) AND team_season = '" . $season . "'  AND current = 'Yes' GROUP BY team_grade, day_played, team_season, comptype ORDER BY day_played, team_grade";
//echo("<br>Count Team Snooker " . $query_teamSnook . "<br>");
$teamSnook = mysql_query($query_teamSnook, $connvbsa) or die(mysql_error());
$row_teamSnook = mysql_fetch_assoc($teamSnook);
$totalRows_teamSnook = mysql_num_rows($teamSnook);

mysql_select_db($database_connvbsa, $connvbsa);
$query_teamBill = "Select COUNT( Team_entries.team_grade ) AS teams, team_grade, day_played, grade_name, fix_upload, team_season, comptype, current FROM Team_entries LEFT JOIN Team_grade ON grade=team_grade AND season=team_season AND team_cal_year=fix_cal_year WHERE team_season = '" . $season . "' AND include_draw = 'Yes' AND type='Billiards' AND team_cal_year = YEAR( CURDATE( ) ) AND team_season = '" . $season . "' AND current = 'Yes' GROUP BY team_grade, day_played, team_season, comptype ORDER BY day_played, team_grade";
//echo("<br>Count Team Billiards " . $query_teamBill . "<br>");
$teamBill = mysql_query($query_teamBill, $connvbsa) or die(mysql_error());
$row_teamBill = mysql_fetch_assoc($teamBill);
$totalRows_teamBill = mysql_num_rows($teamBill);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Tot_Inc = "Select SUM(players*9*15) AS TotInc FROM Team_entries WHERE team_name != 'Bye' AND team_season='$season' AND team_cal_year = YEAR( CURDATE( ) )";
//echo("<br>Select Tot_Inc " . $query_Tot_Inc . "<br>");
$Tot_Inc = mysql_query($query_Tot_Inc, $connvbsa) or die(mysql_error());
$row_Tot_Inc = mysql_fetch_assoc($Tot_Inc);
$totalRows_Tot_Inc = mysql_num_rows($Tot_Inc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Tot_Players = "Select SUM(players) AS TotPositions FROM Team_entries WHERE team_name != 'Bye' AND team_season='$season' AND team_cal_year = YEAR( CURDATE( ) )";
//echo("<br>Select Tot Players " . $query_Tot_Players . "<br>");
$Tot_Players = mysql_query($query_Tot_Players, $connvbsa) or die(mysql_error());
$row_Tot_Players = mysql_fetch_assoc($Tot_Players);
$totalRows_Tot_Players = mysql_num_rows($Tot_Players);
//echo("<br>Rows " . $totalRows_Tot_Players . "<br>");
//echo("<br>Select Tot Players " . $row_Tot_Players['TotPositions'] . "<br>");
$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, username, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);

mysql_select_db($database_connvbsa, $connvbsa);
$query_team_count = "Select COUNT(team_id) AS totalteams, team_name  FROM Team_entries  WHERE team_season='$season' AND team_cal_year = YEAR( CURDATE( ) ) AND include_draw ='Yes' AND team_name != 'Bye'";
$team_count = mysql_query($query_team_count, $connvbsa) or die(mysql_error());
$row_team_count = mysql_fetch_assoc($team_count);
$totalRows_team_count = mysql_num_rows($team_count);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 


</head>

<body>

<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>

<table width="700" align="center">
  <tr>
    <td colspan="4" align="center"><?php echo $_SESSION['MM_Username']; ?> <?php echo $row_getusername['usertype']; ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><span class="red_bold"><?php echo $colname_teamSnook ?> Scoring</span></td>
    <td align="right" nowrap="nowrap" class="greenbg"><a href="AA_scores_index_select_season.php">Return to opening page</a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='5' align="center">Total Playing Positions = <?php echo $row_Tot_Players['TotPositions']; ?>. Total Teams = <?php echo $row_team_count['totalteams']; ?>. Total Approx Income $<?php echo $row_Tot_Inc['TotInc']; ?> (Based on $9 per player per round over 15 rounds)</td>
    <!--<td align="right">Total Playing Positions = </td>
    <td><?php echo $row_Tot_Play_Positions['TotPositions']; ?></td>
    <td>Teams in total: <?php echo $row_team_count['totalteams']; ?></td>
    <td align="right">Total Income (Approx) </td>
    <td>$<?php echo $row_Tot_Inc['TotInc']; ?> (Based on $9 per player per round over 15 rounds)</td>-->
  </tr>
  <!--<tr>
    <td align="right">Total Playing Positions </td>
    <td><?php echo $row_Tot_Play_Positions['TotPositions']; ?></td>
    <td>Teams in total: <?php echo $row_team_count['totalteams']; ?></td>
    <td align="right">Total Income (Approx) </td>
    <td>$<?php echo $row_Tot_Inc['TotInc']; ?> (Based on $9 per player per round over 15 rounds)</td>
  </tr>-->

  </table>
<table width="900" align="center" cellpadding="3" cellspacing="3" class="greenbg">
      <tr>
        <td colspan="9" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="9" class="red_bold">Billiards</td>
      </tr>
      <tr>
        <th align="left">Grade Code</th>
        <th align="left">Grade Name</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th align="left">Day</th>
        <th align="center"> Teams</th>
        <th align="center">Season</th>
        <th align="left">Fixture</th>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_teamBill['team_grade']; ?></td>
        <td><?php echo $row_teamBill['grade_name']; ?></td>
        <td><a href="scores_ladders.php?grade=<?php echo $row_teamBill['team_grade']; ?>&comptype=<?php echo $row_teamBill['comptype']; ?>&season=<?php echo $season; ?>">Ladder</a></td>
        <td nowrap="nowrap"><a href="scores_index_update_rounds.php?rds=<?php echo $row_teamBill['team_grade']; ?>&season=<?php echo $season; ?>">Update Rounds</a></td>
        <td><a href="scores_index_finals.php?grade=<?php echo $row_teamBill['team_grade']; ?>&comptype=<?php echo $row_teamBill['comptype']; ?>&season=<?php echo $season; ?>">Finals</a></td>
        <td align="left"><?php echo $row_teamBill['day_played']; ?></td>
        <td align="center"><?php echo $row_teamBill['teams']; ?></td>
        <td align="center"><?php echo $row_teamBill['team_season']; ?></td>
        <td align="left"><a href="../fix_upload/<?php echo $row_teamBill['fix_upload']; ?>" title="View"><?php echo $row_teamBill['fix_upload']; ?></a></td>
      </tr>
      <?php } while ($row_teamBill = mysql_fetch_assoc($teamBill)); ?>
    </table>
      <table width="900" align="center" cellpadding="3" cellspacing="3" class="greenbg">
      <tr>
        <td colspan="9" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="9" class="red_bold">Snooker</td>
        </tr>
      <tr>
        <th align="left">Grade Code</th>
        <th align="left">Grade Name</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th align="left">Day</th>
        <th align="center">Teams</th>
        <th align="center">Season</th>
        <th align="left">Fixture</th>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_teamSnook['team_grade']; ?></td>
        <td><?php echo $row_teamSnook['grade_name']; ?></td>
        <td><a href="scores_ladders.php?grade=<?php echo $row_teamSnook['team_grade']; ?>&comptype=<?php echo $row_teamSnook['comptype']; ?>&season=<?php echo $season; ?>">Ladder</a></td>
        <td nowrap="nowrap"><a href="scores_index_update_rounds.php?rds=<?php echo $row_teamSnook['team_grade']; ?>&season=<?php echo $season; ?>">Update Rounds</a></td>
        
        <td><a href="scores_index_finals.php?grade=<?php echo $row_teamSnook['team_grade']; ?>&comptype=<?php echo $row_teamSnook['comptype']; ?>&season=<?php echo $season; ?>">Finals</a></td>
        
        <td align="left"><?php echo $row_teamSnook['day_played']; ?></td>
        <td align="center"><?php echo $row_teamSnook['teams']; ?></td>
        <td align="center"><?php echo $row_teamSnook['team_season']; ?></td>
        <td align="left"><a href="../fix_upload/<?php echo $row_teamSnook['fix_upload']; ?>" title="View"><?php echo $row_teamSnook['fix_upload']; ?></a></td>
      </tr>
      <?php } while ($row_teamSnook = mysql_fetch_assoc($teamSnook)); ?>
    </table>
    <table width="1120" align="center">
      <tr>
        <td colspan="5" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" align="left" class="red_bold">Scores, Administrators have access to all views, please do not edit or insert scores without contacting the Score Registrar</td>
      </tr>
      <tr>
        <td width="288" class="greenbg_menu"><a href="players_qual.php?season=<?php echo $season ?>">Qualified players <?php echo $season ?></a></td>
        <td>View all qualified players, Billiards and Snooker, in this season and edit</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="players_qual_finals.php?season=<?php echo $season ?>">Qualified players for finals <?php echo $season ?></a></td>
        <td>View all players qualified for finals, Billiards and Snooker, in this season and edit</td>
      </tr>
      <tr>
        <td  class="greenbg_menu"><a href="scores_index_rounds_adjust.php?season=<?php echo $season; ?>">Adjust rounds <?php echo $season; ?></a></td>
        <td>View, Edit , insert ladder adjustments with explanations, (for use when a team forfeits or there is an adjustment required by the bylaws</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><span class="red_bold">Recalculate the scores and the rankings tables, must be done on completion of inputting scores</span></td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="../Admin_update_tables/Update_Scores_Rank.php">Calculate  tables</a></td>
        <td>Runs a complete calculation on scrs, teams and rankings, members tables where associated with the scoring system.</td>
      </tr>
      <tr>
        <td colspan="2" align="left" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="left" class="red_bold"><?php echo $colname_teamSnook ?> Teams &amp; Fixturing</td>
      </tr>
      <tr>
        <td class="greenbg_menu" width="300" align="left"><a href="../Admin_rankings/team_assessment.php">Pennant Snooker Team & Player Assessment</a></td>
        <td>Current snooker team assessment for all players.</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="../Admin_Fixtures/generate_fixtures.php?DayPlayed=Mon&season=<?php echo $season; ?>">Generate Fixtures for <?php echo $season; ?> (Mon) </a></td>
        <td class="greenbg_menu">Generate Fixtures for all grades playing Monday for <?php echo $season; ?>. Fixtures alogrithm for 4, 6, 8, 10, 12 and 14 teams available with 4 or 6 team finals.</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="../Admin_Fixtures/generate_fixtures.php?DayPlayed=Wed&season=<?php echo $season; ?>">Generate Fixtures for <?php echo $season; ?> (Wed) </a></td>
        <td class="greenbg_menu">Generate Fixtures for all grades playing Wednseday for <?php echo $season; ?>. Fixtures alogrithm for 4, 6, 8, 10, 12 and 14 teams available with 4 or 6 team finals.</td>
      </tr>
      <tr>
        <td colspan="2" align="left" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="../Admin_Fixtures/create_fixture_upload.php?season=<?php echo $season ?>">Upload Fixtures Spreadsheet for Season <?php echo $season ?></a></td>
        <td class="greenbg_menu">Upload/Create Fixtures for all grades for Season <?php echo $season ?></td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="../Admin_Fixtures/edit_fixtures.php?season=<?php echo $season; ?>">Edit Fixtures for <?php echo $season ?></a></td>
        <td class="greenbg_menu">Edit Fixtures for all grades for <?php echo $season ?></td>
      </tr>
      <tr>
        <td colspan="2" align="left" class="red_bold">&nbsp;</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="team_entries.php?season=<?php echo $season; ?>">View all teams - <?php echo $season; ?> Season only</a></td>
        <td>View, insert and update team entries (updates the public view page on the website as well)</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="team_grades.php?season=<?php echo $season; ?>&year=<?php echo date('Y'); ?>">Team Grades </a></td>
        <td class="greenbg_menu">View and edit Team Grade structure for season <?php echo $season; ?></td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="non_playable_dates.php?year=<?php echo date('Y'); ?>">Non Playable Dates </a></td>
        <td class="greenbg_menu">View and edit non playable dates for current_year <?php echo date('Y'); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="brks_last_50.php">Breaks - Last 50 recorded</a></td>
        <td>View / Edit / Delete breaks - Last 50 breaks recorded, Snooker &amp; Billiards</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="brks_byseason.php?season=<?php echo $season ?>&comptype=Snooker">Breaks - Snooker <?php echo $season ?></a></td>
        <td>View / Edit / Delete Snooker breaks that have occurred in Season <?php echo $season ?> in the Current year</td>
      </tr>
      <tr>
        <td class="greenbg_menu"><a href="brks_byseason.php?season=<?php echo $season ?>&comptype=Billiards">Breaks - Billiards <?php echo $season ?></a></td>
        <td>View / Edit / Delete Billiards breaks that have occurred in Season <?php echo $season ?> in the Current year</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td class="page">If there is a view that is not listed that would suit your purpose please let me know <a href="mailto:web@vbsa.org.au">web@vbsa.org.au</a></td>
      </tr>
    </table>
</body>
</html>
