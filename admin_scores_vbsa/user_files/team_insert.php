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
?><?php require_once('../../Connections/connvbsa.php'); ?>
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

if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_vbsaclubs = "SELECT ClubNumber, ClubTitle, ClubNameVBSA, ClubContact, ClubTables, VBSAteam FROM clubs WHERE VBSAteam=1 AND ClubNumber='$club_id' ORDER BY ClubTitle";
$vbsaclubs = mysql_query($query_vbsaclubs, $connvbsa) or die(mysql_error());
$row_vbsaclubs = mysql_fetch_assoc($vbsaclubs);
$totalRows_vbsaclubs = mysql_num_rows($vbsaclubs);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_S1 = "SELECT *  FROM Team_grade  WHERE current='Yes' AND season='S1'  ORDER BY type,grade_name";
$grades_S1 = mysql_query($query_grades_S1, $connvbsa) or die(mysql_error());
$row_grades_S1 = mysql_fetch_assoc($grades_S1);
$totalRows_grades_S1 = mysql_num_rows($grades_S1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_grades_S2 = "SELECT * FROM Team_grade WHERE current='Yes' AND season='S2' ORDER BY type,grade_name";
$grades_S2 = mysql_query($query_grades_S2, $connvbsa) or die(mysql_error());
$row_grades_S2 = mysql_fetch_assoc($grades_S2);
$totalRows_grades_S2 = mysql_num_rows($grades_S2);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Team_entries (team_id, team_club, team_club_id, team_name, team_grade, team_season, day_played, players, Final5, include_draw, audited, team_cal_year, comptype) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['team_id'], "int"),
                       GetSQLValueString($_POST['team_club'], "text"),
					   GetSQLValueString($_POST['team_club_id'], "int"),
                       GetSQLValueString($_POST['team_name'], "text"),
                       GetSQLValueString($_POST['team_grade'], "text"),
					   GetSQLValueString($_POST['team_season'], "text"),
					   GetSQLValueString($_POST['day_played'], "text"),
					   GetSQLValueString($_POST['players'], "text"),
					   GetSQLValueString($_POST['Final5'], "int"),
					   GetSQLValueString($_POST['include_draw'], "text"),
					   GetSQLValueString($_POST['audited'], "text"),
					   GetSQLValueString($_POST['team_cal_year'], "date"),
					   GetSQLValueString($_POST['comptype'], "text"));


 mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../team_entries.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

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

<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td align="center" nowrap="nowrap" class="red_bold">Insert a new team for <?php echo $row_vbsaclubs['ClubTitle']; ?> in season <?php echo $season; ?></td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" class="pagetitle">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club ID:</td>
      <td><?php echo $row_vbsaclubs['ClubNumber']; ?> (auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Club Title</td>
      <td><?php echo $row_vbsaclubs['ClubNameVBSA']; ?> (auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team Name:</td>
        <td>     
          <input name="team_name" type="text" id="team_name" value="" size="32" />      
        </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Team Grade:</td>
        <td> 
          
          <?php if($season=='S1') { ?> 
          <select name="team_grade">
            <?php
do {  
?>
            <option value="<?php echo $row_grades_S1['grade']?>"<?php if (!(strcmp($row_grades_S1['grade'], $row_grades_S1['grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_grades_S1['grade_name']?></option>
            <?php
} while ($row_grades_S1 = mysql_fetch_assoc($grades_S1));
  $rows = mysql_num_rows($grades_S1);
  if($rows > 0) {
      mysql_data_seek($grades_S1, 0);
	  $row_grades_S1 = mysql_fetch_assoc($grades_S1);
  }
?>
            
          </select>
		<?php } else { ?>
        
          <select name="team_grade">
            <?php
do {  
?>
            <option value="<?php echo $row_grades_S2['grade']?>"<?php if (!(strcmp($row_grades_S2['grade'], $row_grades_S2['grade']))) {echo "selected=\"selected\"";} ?>><?php echo $row_grades_S2['grade_name']?></option>
            <?php
} while ($row_grades_S2 = mysql_fetch_assoc($grades_S2));
  $rows = mysql_num_rows($grades_S2);
  if($rows > 0) {
      mysql_data_seek($grades_S2, 0);
	  $row_grades_S2 = mysql_fetch_assoc($grades_S2);
  }
?>
            
          </select>
		<?php }  ?>
          </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Day</td>
        <td>
          <select name="day_played">
            <option value="Mon">Mon</option>
            <option value="Wed">Wed</option>
          </select>
        </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type</td>
      <td><select name="comptype">
        <option value="Snooker" selected="selected">Snooker</option>
        <option value="Billiards">Billiards</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Players</td>
        <td>
          <select name="players">
            <option value="4">4</option>
            <option value="6">6</option>
          </select>
        </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Season:</td>
      <td><?php echo $season; ?> (auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Year entered:</td>
      <td><?php echo date("Y")?> (auto entered)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insert Team" /></td>
    </tr>
  </table>
  <input type="hidden" name="team_id" value="" />
  <input type="hidden" name="team_club_id" value="<?php echo $row_vbsaclubs['ClubNumber']; ?>" />
  <input type="hidden" name="team_club" value="<?php echo $row_vbsaclubs['ClubNameVBSA']; ?>" />
  <input type="hidden" name="team_season" value="<?php echo $season ?>" />
  <input type="hidden" name="Final5" value="4" />
  <input type="hidden" name="include_draw" value="Yes" />
  <input type="hidden" name="audited" value="No" />
  <input type="hidden" name="team_cal_year" value="<?php echo date("Y")?>" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>

</body>
</html>
<?php

?>

