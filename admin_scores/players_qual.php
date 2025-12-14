<?php require_once('../Connections/connvbsa.php'); 

error_reporting(0);

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$page = "../admin_scores/players_qual.php?season=$season";
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

mysql_select_db($database_connvbsa, $connvbsa);

$query_qual_play = "SELECT scrsID, scrs.MemberID, FirstName, LastName, MobilePhone, Email, team_grade, team_id, game_type, count_played, captain_scrs, scr_season, byes_to_date FROM scrs LEFT JOIN members ON members.MemberID = scrs.MemberID WHERE scr_season = '$season' AND current_year_scrs = YEAR(CURDATE( )) AND scrs.MemberID != 1 ORDER BY team_grade, team_id, FirstName";
$qual_play = mysql_query($query_qual_play, $connvbsa) or die(mysql_error());
$row_qual_play = mysql_fetch_assoc($qual_play);
$totalRows_qual_play = mysql_num_rows($qual_play);

// get last round played
$sql_round = "Select * FROM vbsa3364_vbsa2.tbl_fixtures where season = '" . $season . "' and year = YEAR(CURDATE( )) and date < curdate() Order by round DESC LIMIT 1";
$round = mysql_query($sql_round, $connvbsa) or die(mysql_error());
$row_round = mysql_fetch_assoc($round);
$current_round = $row_round['round'];

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

<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2" align="center" class="greenbg">&nbsp;</td>
  </tr>
  <tr>
    <td class="red_bold">All <?php echo $colname_qual_play ?> (Billiards &amp; Snooker) qualified players in the current year (players may appear multiple times if playing in more than one grade) </td>
    <td class="greenbg"><a href="AA_scores_index_grades.php?season=<?php echo $season ?>">Return to <?php echo $season ?> ladders</a></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">Total qualified players: <?php echo $totalRows_qual_play ?></td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5" class="page">
  <tr>
    <td align="center">scrs ID</td>
    <td align="center">Member ID</td>
    <td align="left">First Name</td>
    <td align="left">Last Name</td>
    <td align="left">MobilePhone</td>
    <td align="left">Email</td>
    <td align="left">Grade</td>
    <td align="center">Team ID</td>
    <td align="center">Captain?</td>
    <td align="center">Played</td>
    <td align="center">Comp Type</td>
    <td align="center">&nbsp;</td>
  </tr>
  <?php 
  do { 
    if(($row_qual_play['count_played']-$row_qual_play['byes_to_date']) < ($current_round*0.5))
    {
      ?>
    <tr>
      <td align="center"><?php echo $row_qual_play['scrsID']; ?></td>
      <td align="center"><?php echo $row_qual_play['MemberID']; ?></td>
      <td align="left"><?php echo $row_qual_play['FirstName']; ?></td>
      <td align="left"><?php echo $row_qual_play['LastName']; ?></td>
      <td align="left"><a href="tel:<?php echo $row_qual_play['MobilePhone']; ?>" ><?php echo $row_qual_play['MobilePhone']; ?></a></td>
      <td align="left"><a href="mailto:<?php echo $row_qual_play['Email']; ?>" target="_blank"><?php echo $row_qual_play['Email']; ?></a></td>
      <td align="left"><?php echo $row_qual_play['team_grade']; ?></td>
      <td align="center"><?php echo $row_qual_play['team_id']; ?></td>
      <td align="center"><?php echo $row_qual_play['captain_scrs']; ?></td>
      <td align="center"><?php echo $row_qual_play['count_played']; ?></td>
      <td align="center"><?php echo $row_qual_play['game_type']; ?></td>
      <td><a href="../A_common/vbsa_member_edit.php?memb_id=<?php echo $row_qual_play['MemberID']; ?>" ><img src="../Admin_Images/edit_butt.fw.png" width="20" /></a></td>
    </tr>
    <?php 
    }
  }
  while ($row_qual_play = mysql_fetch_assoc($qual_play)); ?>
</table>
</center>
</center>
</body>
</html>
<?php

?>