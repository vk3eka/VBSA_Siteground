<?php require_once('../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$page = "../team_entries.php";
$_SESSION['page'] = $page;


$MM_authorizedUsers = "";
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
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_Tot_Play_Positions = "SELECT SUM(players) AS TotPositions FROM Team_entries LEFT JOIN Team_grade ON Team_entries.team_grade=grade WHERE season='$season' AND team_name<>'Bye' AND include_draw='Yes' AND team_cal_year = YEAR( CURDATE( ) )";
$Tot_Play_Positions = mysql_query($query_Tot_Play_Positions, $connvbsa) or die(mysql_error());
$row_Tot_Play_Positions = mysql_fetch_assoc($Tot_Play_Positions);
$totalRows_Tot_Play_Positions = mysql_num_rows($Tot_Play_Positions);

mysql_select_db($database_connvbsa, $connvbsa);
$query_Tot_Inc = "SELECT SUM(players*9*15) AS TotInc FROM Team_entries LEFT JOIN Team_grade ON Team_entries.team_grade=grade WHERE season='$season' AND team_name<>'Bye' AND include_draw='Yes' AND team_cal_year = YEAR( CURDATE( ) ) AND include_draw='Yes'";
$Tot_Inc = mysql_query($query_Tot_Inc, $connvbsa) or die(mysql_error());
$row_Tot_Inc = mysql_fetch_assoc($Tot_Inc);
$totalRows_Tot_Inc = mysql_num_rows($Tot_Inc);

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsaclubs = "SELECT ClubNumber, ClubTitle, ClubNameVBSA, ClubContact, ClubTables, VBSAteam, COUNT(team_season) AS total_teams FROM clubs  LEFT JOIN Team_entries ON team_club_id = ClubNumber AND team_season='$season'  AND team_cal_year = YEAR( CURDATE( ) ) WHERE VBSAteam=1 GROUP BY ClubNumber ORDER BY ClubTitle";
$vbsaclubs = mysql_query($query_vbsaclubs, $connvbsa) or die(mysql_error());
$row_vbsaclubs = mysql_fetch_assoc($vbsaclubs);
$totalRows_vbsaclubs = mysql_num_rows($vbsaclubs);

mysql_select_db($database_connvbsa, $connvbsa);
$query_team_entries = "SELECT Team_entries.team_id, team_club, team_name, comptype, Team_entries.team_grade, day_played, players, include_draw, team_club_id,  COUNT(scrs.team_id) AS allplayers, season, SUM(scrs.captain_scrs) AS capt FROM Team_entries   LEFT JOIN scrs ON Team_entries.team_id = scrs.team_id   LEFT JOIN Team_grade ON Team_entries.team_grade=grade WHERE season='$season'  AND team_cal_year = YEAR( CURDATE( ) ) GROUP BY Team_entries.team_id ORDER BY team_grade, team_name";
$team_entries = mysql_query($query_team_entries, $connvbsa) or die(mysql_error());
$row_team_entries = mysql_fetch_assoc($team_entries);
$totalRows_team_entries = mysql_num_rows($team_entries);

mysql_select_db($database_connvbsa, $connvbsa);
$query_team_summary = "SELECT COUNT( Team_entries.team_grade ) AS teams, team_grade, day_played, current_year_team, grade_name, fix_upload, fix_cal_year, comptype, season FROM Team_entries LEFT JOIN Team_grade ON Team_entries.team_grade=grade WHERE season='$season' AND `include_draw` = 'Yes' AND team_cal_year = YEAR( CURDATE( ) ) GROUP BY team_grade ORDER BY day_played, comptype DESC,  team_grade ASC";
$team_summary = mysql_query($query_team_summary, $connvbsa) or die(mysql_error());
$row_team_summary = mysql_fetch_assoc($team_summary);
$totalRows_team_summary = mysql_num_rows($team_summary);

mysql_select_db($database_connvbsa, $connvbsa);
$query_team_count = "SELECT COUNT(team_id) AS totalteams FROM Team_entries  WHERE team_season='$season' AND team_cal_year = YEAR( CURDATE( ) ) AND include_draw ='Yes'";
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

<table align="center">
  <tr>
    <td class="red_bold">&nbsp;</td>
    <td class="red_bold">&nbsp;</td>
    <td class="red_bold">&nbsp;</td>
    <td class="greenbg">&nbsp;</td>
    <td class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold" nowrap="nowrap"><?php echo date("Y")?> Season <?php echo $season; ?>  team entries</td>
    <td class="red_bold">&nbsp;</td>
    <td class="red_bold"><span class="greenbg"><a href="team_summary.php?season=<?php echo $season; ?>">Insert a new team</a></span></td>
    <td class="greenbg">&nbsp;</td>
    <td class="greenbg" nowrap="nowrap"><a href="AA_scores_index_grades.php?season=<?php echo $season; ?>">Return to <?php echo $colname_team_summary; ?>  Menu</a></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">Total Playing Positions </td>
    <td><?php echo $row_Tot_Play_Positions['TotPositions']; ?></td>
    <td>Teams in total: <?php echo $row_team_count['totalteams']; ?></td>
    <td align="right">Total Income (Approx) </td>
    <td>$<?php echo $row_Tot_Inc['TotInc']; ?> (Based on $9 per player per round over 15 rounds)</td>
  </tr>
</table>

  <p>&nbsp;</p>
  <table width="550" border="1" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td colspan="5" class="red_text">List only counts teams that have include in draw as &quot;Yes&quot;</td>
    </tr>
    <tr>
      <td class="red_text">Grade Code</td>
      <td class="red_text">Day</td>
      <td align="center" class="red_text">Total Teams</td>
      <td align="center" class="red_text">Season</td>
      <td align="center" class="red_text">Type</td>
    </tr>
  <?php do { ?>  
    <tr>
      <td><?php echo $row_team_summary['team_grade']; ?></td>
      <td><?php echo $row_team_summary['day_played']; ?></td>
      <td align="center"><?php echo $row_team_summary['teams']; ?></td>
      <td align="center"><?php echo $row_team_summary['season']; ?></td>
      <td align="center"><?php echo $row_team_summary['comptype']; ?></td>
    </tr>
  <?php } while ($row_team_summary = mysql_fetch_assoc($team_summary)); ?>  
</table>
  
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="3" cellspacing="3">
  <tr class="page" >
    <td align="center">Team ID</td>
      <td>Club</td>
      <td align="center">Club ID</td>
      <td align="left">Team Name</td>
      <td align="left">Grade</td>
      <td align="left">Day</td>
      <td align="center">Players</td>
      <td align="center">Include in Draw?</td>
      <td align="center" nowrap="nowrap">Selected players</td>
      <td align="center">Captain?</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center" nowrap="nowrap"><?php echo $row_team_entries['team_id']; ?></td>
        <td><?php echo $row_team_entries['team_club']; ?></td>
        <td align="center"><?php echo $row_team_entries['team_club_id']; ?></td>
        <td align="left"><?php echo $row_team_entries['team_name']; ?></td>
        <td align="left"><?php echo $row_team_entries['team_grade']; ?></td>
        <td align="left"><?php echo $row_team_entries['day_played']; ?></td>
        <td align="center"><?php echo $row_team_entries['players']; ?></td>
        <td align="center" <?php if($row_team_entries['include_draw']=='No') echo 'class=red_bold'; ?>><?php echo $row_team_entries['include_draw']; ?></td>
        <td align="center"><?php echo $row_team_entries['allplayers']; ?></td>
        <td align="center"><?php echo $row_team_entries['capt']; ?></td>
        <td align="center"><a href="user_files/team_entries_edit.php?team_id=<?php echo $row_team_entries['team_id']; ?>&season=<?php echo $season ?>" title="Edit team details"><img src="../Admin_Images/edit_butt.fw.png" width="25" /></a></td>
        <td align="center" nowrap="nowrap" class="greenbg"><a href="team_entries_player_multiple_insert.php?team_id=<?php echo $row_team_entries['team_id']; ?>&club_id=<?php echo $row_team_entries['team_club_id']; ?>&season=<?php echo $season ?>&comptype=<?php echo $row_team_entries['comptype'] ?>&grade=<?php echo $row_team_entries['team_grade'] ?>">Insert multiple players / edit players / select captain</a></td>
    </tr>
    <?php } while ($row_team_entries = mysql_fetch_assoc($team_entries)); ?>
</table>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
</table>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="6" align="center" class="red_bold">All VBSA Clubs - where VBSAclub (Clubs table) is checked (=1)</td>
  </tr>
  <tr>
    <td align="center">Club ID</td>
    <td align="left">Club Title</td>
    <td align="left">Club Contact</td>
    <td align="center">Tables</td>
    <td align="center"><?php echo $season ?> Teams</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_vbsaclubs['ClubNumber']; ?></td>
      <td align="left"><?php echo $row_vbsaclubs['ClubTitle']; ?></td>
      <td align="left"><?php echo $row_vbsaclubs['ClubContact']; ?></td>
      <td align="center"><?php echo $row_vbsaclubs['ClubTables']; ?></td>
      <td align="center"><?php echo $row_vbsaclubs['total_teams']; ?></td>
      <td align="center"><a href="user_files/team_entries_detail.php?club_id=<?php echo $row_vbsaclubs['ClubNumber']; ?>&amp;season=<?php echo $season ?>"><img src="../Admin_Images/detail.fw.png" width="20" title="View teams entered"/></a></td>
    </tr>
    <?php } while ($row_vbsaclubs = mysql_fetch_assoc($vbsaclubs)); ?>
</table>
</body>
</html>
<?php

?>
