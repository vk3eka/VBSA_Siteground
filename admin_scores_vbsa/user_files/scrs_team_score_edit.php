<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
<?php require_once('../../Connections/connvbsa.php'); ?><?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Team_entries SET total_score=%s, Result_pos=%s, Result_score=%s, Updated=%s, HB=%s, audited=%s WHERE team_id=%s",
                       GetSQLValueString($_POST['total_score'], "int"),
                       GetSQLValueString($_POST['Result_pos'], "int"),
                       GetSQLValueString($_POST['Result_score'], "int"),
                       GetSQLValueString($_POST['Updated'], "date"),
                       GetSQLValueString($_POST['HB'], "text"),
                       GetSQLValueString($_POST['audited'], "text"),
                       GetSQLValueString($_POST['team_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../scores_ladders.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}


if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}


if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Team_scr_edit = "SELECT SUM(IFNULL(scrs.r01s,0)) + SUM(IFNULL(scrs.r02s,0)) + SUM(IFNULL(scrs.r03s,0)) + SUM(IFNULL(scrs.r04s,0)) + SUM(IFNULL(scrs.r05s,0)) + SUM(IFNULL(scrs.r06s,0)) + SUM(IFNULL(scrs.r07s,0)) + SUM(IFNULL(scrs.r08s,0)) + SUM(IFNULL(scrs.r09s,0)) + SUM(IFNULL(scrs.r10s,0)) + SUM(IFNULL(scrs.r11s,0)) + SUM(IFNULL(scrs.r12s,0)) + SUM(IFNULL(scrs.r13s,0)) + SUM(IFNULL(scrs.r14s,0)) + SUM(IFNULL(scrs.r15s,0)) + SUM(IFNULL(scrs.r16s,0)) + SUM(IFNULL(scrs.r17s,0)) + SUM(IFNULL(scrs.r18s,0))AS team_total, scrs.scrsID, scrs.MemberID, scrs.team_grade, scrs.team_id, scrs.r01s, scrs.r02s, scrs.r03s, scrs.r04s, scrs.r05s, scrs.r06s, scrs.r07s, scrs.r08s, scrs.r09s, scrs.r10s, scrs.r11s, scrs.r12s, scrs.r13s, scrs.r14s, scrs.r15s, scrs.r16s, scrs.r17s, scrs.r18s,Team_entries.team_id, Team_entries.team_club, Team_entries.team_name, Team_entries.team_grade, Team_entries.day_played, Team_entries.players, Team_entries.total_score, Team_entries.Final5, Team_entries.Updated, Team_entries.Result_pos, Team_entries.Result_score, Team_entries.HB, Team_entries.audited FROM Team_entries,scrs WHERE Team_entries.team_id=scrs.team_id AND Team_entries.team_id = '$team_id'";
$Team_scr_edit = mysql_query($query_Team_scr_edit, $connvbsa) or die(mysql_error());
$row_Team_scr_edit = mysql_fetch_assoc($Team_scr_edit);
$totalRows_Team_scr_edit = mysql_num_rows($Team_scr_edit);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
  
  <table border="0" align="center">
    <tr>
      <td align="center" class="red_bold">Update a team score - Claculates the team score and sets where the team will show in the match report</td>
      <td align="center" class="red_bold">&nbsp;</td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  </table>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Team ID:</td>
        <td><?php echo $row_Team_scr_edit['team_id']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Club:</td>
        <td><?php echo $row_Team_scr_edit['team_club']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Team name:</td>
        <td><?php echo $row_Team_scr_edit['team_name']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Team grade:</td>
        <td><?php echo $row_Team_scr_edit['team_grade']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Day played:</td>
        <td><?php echo $row_Team_scr_edit['day_played']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Players:</td>
        <td><?php echo $row_Team_scr_edit['players']; ?>(To edit go to the team list)</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Round Position</td>
        <td><input type="text" name="Result_pos" value="<?php echo $row_Team_scr_edit['Result_pos']; ?>" size="32" /> 
          Sets the position this team will display in the match repport</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Round Score</td>
        <td><input type="text" name="Result_score" value="<?php echo $row_Team_scr_edit['Result_score']; ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">High Break</td>
        <td><input type="text" name="HB" value="<?php echo $row_Team_scr_edit['HB']; ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Audited</td>
        <td><select name="audited">
          <option value="No" <?php if (!(strcmp("No", htmlentities($row_Team_scr_edit['audited'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
          <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Team_scr_edit['audited'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          </select>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Check Team Score</td>
        <td><?php echo $row_Team_scr_edit['team_total']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Updated</td>
        <td><?php echo $row_Team_scr_edit['Updated']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update record" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="team_id" value="<?php echo $row_Team_scr_edit['team_id']; ?>" />
    <input type="hidden" name="total_score" value="<?php echo $row_Team_scr_edit['team_total']; ?>" />
    <input type="hidden" name="Updated" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?> " />
</form>
  <p>&nbsp;</p>
</center>
</body>
</html>
<?php

?>
