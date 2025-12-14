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

$scrsID = "-1";
if (isset($_GET['scrsID'])) {
  $scrsID = $_GET['scrsID'];
}

$season = "-1";
if (isset($_GET['season'])) {
  $season = $_GET['season'];
}

$grade = "-1";
if (isset($_GET['grade'])) {
  $grade = $_GET['grade'];
}

$team_id = "-1";
if (isset($_GET['team_id'])) {
  $team_id = $_GET['team_id'];
}

$comptype = "-1";
if (isset($_GET['comptype'])) {
  $comptype = $_GET['comptype'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_scrsdel = "SELECT scrs.scrsID, scrs.team_id, scrs.team_grade, FirstName, LastName FROM scrs LEFT JOIN members ON scrs.MemberID = members.memberID WHERE scrsID='$scrsID' ";
$scrsdel = mysql_query($query_scrsdel, $connvbsa) or die(mysql_error());
$row_scrsdel = mysql_fetch_assoc($scrsdel);
$totalRows_scrsdel = mysql_num_rows($scrsdel);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table align="center">
    <tr>
      <td align="right">Scores ID Number:</td>
      <td><?php echo $scrsID; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">Player Name: <?php echo $row_scrsdel['FirstName']; ?> <?php echo $row_scrsdel['LastName']; ?></td>
    </tr>

    <tr>
      <td colspan="2" align="center" nowrap="nowrap">This player is listed in team ID: <?php echo $team_id; ?> in grade: <?php echo $grade; ?> in season: <?php echo $season; ?> <?php echo $comptype; ?> </td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bold">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bold">IF YOU PROCEED THIS PLAYER WILL BE PERMANENTLY DELETED FROM THIS TEAM</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bold">YOU CANNOT UNDO THIS ACTION</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Do you wish to proceed?</td>
      <td class="greenbg"><a href="ajax/scrs_delete.php?scrsID=<?php echo $scrsID; ?> & grade=<?php echo $grade; ?> & team_id=<?php echo $team_id; ?> & season=<?php echo $season; ?> & comptype=<?php echo $comptype; ?>" rel="facebox">Yes</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($scrsdel);
?>
