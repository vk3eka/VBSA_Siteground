<?php require_once('../../Connections/connvbsa.php'); ?>
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

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

$page = "team_entries_detail.php?club_id=$club_id";
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

$MM_restrictGoTo = "../../page_error.php";
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



mysql_select_db($database_connvbsa, $connvbsa);
$query_team_entries = "SELECT * FROM Team_entries WHERE team_club_id = '$club_id' AND team_cal_year=YEAR( CURDATE( ) ) AND team_season = '$season' ORDER BY day_played ASC";
$team_entries = mysql_query($query_team_entries, $connvbsa) or die(mysql_error());
$row_team_entries = mysql_fetch_assoc($team_entries);
$totalRows_team_entries = mysql_num_rows($team_entries);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 


</head>

<body>

<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>

<table align="center">
  <tr>
    <td width="184" class="red_bold">&nbsp;</td>
    <td width="128" class="red_bold">&nbsp;</td>
    <td width="129" class="red_bold">&nbsp;</td>
    <td width="237" class="greenbg">&nbsp;</td>
    <td width="100" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" nowrap="nowrap" class="red_bold">Team entries for: <?php echo $row_team_entries['team_club']; ?> in season <?php echo $season; ?> </td>
    <td class="greenbg" nowrap="nowrap"><a href="../team_entries.php?season=<?php echo $season ?>">Return to Team Entries</a></td>
    </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<table border="1" align="center" cellpadding="3" cellspacing="3">
  <tr class="page" >
    <td align="center">Team ID</td>
      <td align="left">Team Name</td>
      <td align="left">Grade</td>
      <td align="left">Day</td>
      <td align="center">Players</td>
      <td align="center">Include in Draw?</td>
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center" nowrap="nowrap"><?php echo $row_team_entries['team_id']; ?></td>
        <td align="left"><?php echo $row_team_entries['team_name']; ?></td>
        <td align="left"><?php echo $row_team_entries['team_grade']; ?></td>
        <td align="left"><?php echo $row_team_entries['day_played']; ?></td>
        <td align="center"><?php echo $row_team_entries['players']; ?></td>
        <td align="center" <?php if($row_team_entries['include_draw']=='No') echo 'class=red_bold'; ?>><?php echo $row_team_entries['include_draw']; ?></td>
        <td align="center"><a href="team_entries_edit.php?team_id=<?php echo $row_team_entries['team_id']; ?>&amp;season=<?php echo $season; ?>" title="Edit team details"><img src="../../Admin_Images/edit_butt.fw.png" width="25" /></a></td>
        <td align="center" nowrap="nowrap" class="greenbg"><a href="team_detail.php?team_id=<?php echo $row_team_entries['team_id']; ?>" >View Team</a></td>
    </tr>
    <?php } while ($row_team_entries = mysql_fetch_assoc($team_entries)); ?>
</table>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
</table>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
