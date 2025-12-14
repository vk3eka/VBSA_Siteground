<?php require_once('../../Connections/connvbsa.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Webmaster,Treasurer";
$MM_donotCheckaccess = "false";

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
    if (($strUsers == "") && false) { 
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

if (isset($_GET['event_id'])) {
  $event_id = $_GET['event_id'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tournaments (tourn_id, tourn_name, site_visible, tourn_type, ranking_type, tourn_class, how_seed, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tourn_id'], "int"),
                       GetSQLValueString($_POST['tourn_name'], "text"),
                       GetSQLValueString($_POST['site_visible'], "text"),
                       GetSQLValueString($_POST['tourn_type'], "text"),
                       GetSQLValueString($_POST['ranking_type'], "text"),
                       GetSQLValueString($_POST['tourn_class'], "text"),
                       GetSQLValueString($_POST['how_seed'], "text"),
                       GetSQLValueString($_POST['status'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../../Admin_Tournaments/aa_tourn_index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_eventID = "SELECT * FROM calendar WHERE event_id ='$event_id'";
$eventID = mysql_query($query_eventID, $connvbsa) or die(mysql_error());
$row_eventID = mysql_fetch_assoc($eventID);
$totalRows_eventID = mysql_num_rows($eventID);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="1000" border="0" align="center" cellpadding="2" cellspacing="2" class="greenbg">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" alt="" width="1000" height="140" /></td>
  </tr>
</table>
<table width="800" align="center">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">Create an entry list for Event ID: <?php echo $event_id ?></td>
    <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" cellpadding="5" cellspacing="5">
        <tr valign="baseline">
          <td align="right" nowrap="nowrap" class="red_text">Tournament ID</td>
          <td>Will be set as Event ID: <span class="red_bold"><?php echo $event_id ?></span></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Name:</td>
          <td><?php echo $row_eventID['event']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Please Select a Class for this tournament:</td>
          <td><select name="tourn_class">
            <option value="Aust Rank" <?php if (!(strcmp("Aust Rank", ""))) {echo "SELECTED";} ?>>Aust Rank</option>
            <option value="Victorian" <?php if (!(strcmp("Victorian", ""))) {echo "SELECTED";} ?>>Victorian</option>
            <option value="Junior" <?php if (!(strcmp("Junior", ""))) {echo "SELECTED";} ?>>Junior</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Does this tournament attract ranking points?</td>
          <td><select name="ranking_type">
            <option value="No Entry" selected="selected" <?php if (!(strcmp("", ""))) {echo "SELECTED";} ?>>No Entry</option>
            <option value="Vic Rank" <?php if (!(strcmp("Vic Rank", ""))) {echo "SELECTED";} ?>>Vic Rank</option>
            <option value="Womens Rank" <?php if (!(strcmp("Womens Rank", ""))) {echo "SELECTED";} ?>>Womens Rank</option>
            <option value="Junior Rank" <?php if (!(strcmp("Junior Rank", ""))) {echo "SELECTED";} ?>>Junior Rank</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="center" class="red_text">"No Entry" for tournaments that do not attract Victorian Ranking points. <br />
            "Vic Rank" for tournaments that DO attract Victorian Ranking points. <br />
            "Junior Rank" for tournaments that attract Victorian JUNIOR Ranking points. <br />
            "Womens Rank" for tournaments that DO attract Victorian WOMENS Ranking points. <br />
           </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">How is this tournament seeded?</td>
          <td><select name="how_seed">
          <option value="NA" <?php if (!(strcmp("NA", ""))) {echo "SELECTED";} ?>>Not Applicable</option>
            <option value="Aust Rankings" <?php if (!(strcmp("Aust Rankings", ""))) {echo "SELECTED";} ?>>Aust Rankings</option>
            <option value="Vic Rankings" <?php if (!(strcmp("Vic Rankings", ""))) {echo "SELECTED";} ?>>Victorian Rankings</option>
            <option value="Aust Womens Rankings" <?php if (!(strcmp("Aust Womens Rankings", ""))) {echo "SELECTED";} ?>>Aust Womens Rankings</option>
            <option value="Vic Womens Rankings" <?php if (!(strcmp("Vic WomensRankings", ""))) {echo "SELECTED";} ?>>Victorian Womens Rankings</option>
            <option value="Junior Rankings" <?php if (!(strcmp("Junior Rankings", ""))) {echo "SELECTED";} ?>>Junior Rankings</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Do you want this tournament to be visible on the website: </td>
          <td><select name="site_visible">
            <option value="Yes" selected="selected" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
            <option value="No" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>No</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Type: </td>
          <td><select name="tourn_type">
            <option value="Snooker" selected="selected" <?php if (!(strcmp("Snooker", ""))) {echo "SELECTED";} ?>>Snooker</option>
            <option value="Billiards" <?php if (!(strcmp("Billiards", ""))) {echo "SELECTED";} ?>>Billiards</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">The Year for this tournament is auto inserted for current year:</td>
          <td><?php echo date("Y"); ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert Tournament" /></td>
        </tr>
      </table>
      <input type="hidden" name="tourn_id" value="<?php echo $event_id; ?>" />
      <input type="hidden" name="tourn_name" value="<?php echo $row_eventID['event']; ?>" />
      <input type="hidden" name="status" value="Open" />
      <input type="hidden" name="MM_insert" value="form1" />
</form>
    <p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php

?>
