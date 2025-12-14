<?php require_once('../Connections/connvbsa.php'); ?>
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
<?php require_once('../Connections/connvbsa.php'); ?>
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

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}


mysql_select_db($database_connvbsa, $connvbsa);
//$query_Adjust_notes = "SELECT team_id, team_name, team_grade, team_club, scr_adj_rd, scr_adjust, adj_comment, current_year_team, season FROM Team_entries LEFT JOIN Team_grade ON team_grade=grade WHERE YEAR( current_year_team ) = YEAR( CURDATE( ) ) AND season='$season' ORDER BY Team_entries.team_grade, Team_entries.team_club ";
$query_Adjust_notes = "Select team_id, team_name, team_grade, team_club, scr_adj_rd, scr_adjust, adj_comment, team_cal_year, team_season FROM Team_entries WHERE team_cal_year = YEAR( CURDATE( ) ) AND team_season='$season' ORDER BY Team_entries.team_grade, Team_entries.team_club";
//echo("Adjust Select " . $query_Adjust_notes . "<br>");

$Adjust_notes = mysql_query($query_Adjust_notes, $connvbsa) or die(mysql_error());
$row_Adjust_notes = mysql_fetch_assoc($Adjust_notes);
$totalRows_Adjust_notes = mysql_num_rows($Adjust_notes);
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

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

  <table align="center">
    <tr>
      <td align="left" class="page">&nbsp;</td>
      <td align="center" class="page">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" class="red_bold">Manual adjustments to ladders or round score - <?php echo $season; ?>, <?php echo date("Y"); ?></td>
      <td width="20" align="center" class="page">&nbsp;</td>
      <td class="greenbg"><a href="AA_scores_index_grades.php?season=<?php echo $season ?>">Return to <?php echo $season ?> ladders</a></td>
    </tr>
    <tr>
      <td align="left" class="page">&nbsp;</td>
      <td align="center" class="page">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
  </table>
  <table width="1100" border="1" align="center" class="page">
  <tr>
    <td width="60">Team ID</td>
      <td width="162">Team Name</td>
      <td width="50">Grade</td>
      <td width="162">Club</td>
      <td width="50">Round</td>
      <td width="50">Pts</td>
      <td>Explanation</td>
      <td>&nbsp;</td>
    </tr>
  <?php do { 
    //if($row_Adjust_notes['scr_adj_rd'] != '')
    //{
  ?>
    <tr>
      <td width="60"><?php echo $row_Adjust_notes['team_id']; ?></td>
        <td width="162"><?php echo $row_Adjust_notes['team_name']; ?></td>
        <td width="50"><?php echo $row_Adjust_notes['team_grade']; ?></td>
        <td width="162"><?php echo $row_Adjust_notes['team_club']; ?></td>
        <td width="50"><?php echo $row_Adjust_notes['scr_adj_rd']; ?></td>
        <td width="50"><?php echo $row_Adjust_notes['scr_adjust']; ?></td>
        <td><?php echo $row_Adjust_notes['adj_comment']; ?></td>
        <td><a href="user_files/scrs_ladders_adjust.php?team_id=<?php echo $row_Adjust_notes['team_id']; ?>&season=<?php echo $season ?>" ><img src="../Admin_Images/edit_butt.fw.png" width="20" height="20" /></a></td>
    </tr>
  <?php 
    //}
  } while ($row_Adjust_notes = mysql_fetch_assoc($Adjust_notes)); ?>
</table>
</body>
</html>
<?php

?>