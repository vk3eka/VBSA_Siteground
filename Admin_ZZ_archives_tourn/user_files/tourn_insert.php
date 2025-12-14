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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tourn_archives (tournament_ID, tourn_name, ranked, status) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['tournament_ID'], "int"),
                       GetSQLValueString($_POST['tourn_name'], "text"),
                       GetSQLValueString($_POST['ranked'], "text"),
                       GetSQLValueString($_POST['status'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../index.php";
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
<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/Archives.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_DB_VBSA/php_mail_merge/php_mail_merge.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch.php';?>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table width="800" align="center">
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap" class="red_bold">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td align="center" nowrap="nowrap" class="red_bold">Enter a new Tournament into the Archives</td>
          <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap" class="red_bold">&nbsp;</td>
        </tr>
        </table>
        <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tournament Title:</td>
          <td><input type="text" name="tourn_name" value="" size="60" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">About: </td>
          <td>Please edit to create an &quot;About&quot; this tournament</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Ranked?:</td>
          <td><select name="ranked">
            <option value="Australian ranking tournament" selected="selected" <?php if (!(strcmp("Australian ranking tournament", ""))) {echo "SELECTED";} ?>>Australian ranking tournament</option>
            <option value="Victorian ranking tournament" <?php if (!(strcmp("Victorian ranking tournament", ""))) {echo "SELECTED";} ?>>Victorian Ranking Tournament</option>
            <option value="Non ranking tournament" <?php if (!(strcmp("Non ranking tournament", ""))) {echo "SELECTED";} ?>>Non ranking tournament</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Status?:</td>
          <td><select name="status">
            <option value="Current" selected="selected" <?php if (!(strcmp("Current", ""))) {echo "SELECTED";} ?>>Current</option>
            <option value="No longer played" <?php if (!(strcmp("No longer played", ""))) {echo "SELECTED";} ?>>No longer played</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Ordered to Display ? </td>
          <td>Edit to select order to display</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insert Tournament" /></td>
        </tr>
      </table>
      <input type="hidden" name="tournament_ID" value="" />
      <input type="hidden" name="MM_insert" value="form1" />
</form>

</body>
</html>
