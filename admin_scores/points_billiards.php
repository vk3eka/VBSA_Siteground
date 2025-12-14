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
<?php require_once('../Connections/connvbsa.php'); ?><?php
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
$query_Points_B = "SELECT scrsID, scrs.MemberID, LastName, FirstName, team_grade, team_id, pts_won, count_played, percent_won, scr_season FROM scrs  LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE current_year_scrs = YEAR( CURDATE( ) )  AND game_type = 'Billiards' AND pts_won >0 AND scr_season='$season' ORDER BY pts_won DESC";
echo($query_Points_B . "<br>");
$Points_B = mysql_query($query_Points_B, $connvbsa) or die(mysql_error());
$row_Points_B = mysql_fetch_assoc($Points_B);
$totalRows_Points_B = mysql_num_rows($Points_B);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
  
  <table width="800" border="0" align="center">
    <tr>
      <td align="left" class="red_bold">Points Won - All players Billiards <?php echo $season ?> (where points won &gt;0)</td>
      <td align="right" class="page"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  </table>
  <table width="797" border="1" align="center">
    <tr>
      <td align="center">Scrs ID</td>
      <td align="center">Member ID</td>
      <td align="center">Grade</td>
      <td align="center">Team ID</td>
      <td>First Name</td>
      <td>Surname</td>
      <td align="center">Total Points</td>
      <td align="center">Played</td>
      <td align="center">%</td>
    </tr>
    <?php do { ?>
      <tr>
        <td align="center"><?php echo $row_Points_B['scrsID']; ?></td>
        <td align="center"><?php echo $row_Points_B['MemberID']; ?></td>
        <td align="center"><?php echo $row_Points_B['team_grade']; ?></td>
        <td align="center"><?php echo $row_Points_B['team_id']; ?></td>
        <td><?php echo $row_Points_B['FirstName']; ?></td>
        <td><?php echo $row_Points_B['LastName']; ?></td>
        <td align="center"><?php echo $row_Points_B['pts_won']; ?></td>
        <td align="center"><?php echo $row_Points_B['count_played']; ?></td>
        <td align="center"><?php echo $row_Points_B['percent_won']; ?></td>
      </tr>
      <?php } while ($row_Points_B = mysql_fetch_assoc($Points_B)); ?>
</table>
</body>
</html>
<?php

?>