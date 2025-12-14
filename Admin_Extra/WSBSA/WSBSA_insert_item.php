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

$MM_restrictGoTo = "../../page_error_extra.php";
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
  $insertSQL = sprintf("INSERT INTO WSBSA (WSBSA_id, WSBSA_type, item_title, `current`, list_order, uploaded_on) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['WSBSA_id'], "int"),
                       GetSQLValueString($_POST['WSBSA_type'], "text"),
                       GetSQLValueString($_POST['item_title'], "text"),
                       GetSQLValueString($_POST['current'], "int"),
                       GetSQLValueString($_POST['list_order'], "text"),
                       GetSQLValueString($_POST['uploaded_on'], "date"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../WSBSA/WSBSA_index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$item = "-1";
if (isset($_GET['item'])) {
  $item = $_GET['item'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_zone = "SELECT * FROM WSBSA WHERE WSBSA_type = '$item'";
$zone = mysql_query($query_zone, $connvbsa) or die(mysql_error());
$row_zone = mysql_fetch_assoc($zone);
$totalRows_zone = mysql_num_rows($zone);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Extra Administation Area</title>
<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="1000" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>
<p>&nbsp;</p>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" cellpadding="5" cellspacing="5">
    <tr valign="baseline">
      <td align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
      <td align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="red_bold">You are about to insert an item into:</td>
      <td>
      <?php 
	  if ($colname_zone = $_GET['item'] =="a_info") 
      {echo "Information";}
	  if ($colname_zone = $_GET['item'] =="c_zone1") 
      {echo "Zone 1";} 
	  if ($colname_zone = $_GET['item'] =="d_zone2") 
      {echo "Zone 2";}
	  if ($colname_zone = $_GET['item'] =="e_zone3") 
      {echo "Zone 3";}
	  if ($colname_zone = $_GET['item'] =="f_zone4") 
      {echo "Zone 4";}
	  if ($colname_zone = $_GET['item'] =="g_zone5") 
      {echo "Zone 5";}
	  if ($colname_zone = $_GET['item'] =="f_history") 
      {echo "History";}
	  ?>
      </td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="left" nowrap="nowrap" class="red_bold"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="red_bold">Create a title for the item:</td>
      <td align="left" nowrap="nowrap" class="red_bold"><input type="text" name="item_title" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap" class="red_bold">Visible? </td>
      <td width="300" align="left" class="red_bold">Auto inserted as &quot;Yes&quot; edit to change</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td><input type="submit" value="Insert Item" /></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap" class="red_text">After inserting the item you may upoad attachments or update</td>
    </tr>
  </table>
  <input type="hidden" name="WSBSA_id" value="" />
  <input type="hidden" name="uploaded_on" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d H:i:s"); ?> " />
  <input type="hidden" name="current" value="1" />
  <input type="hidden" name="WSBSA_type" value="<?php echo $colname_zone = $_GET['item']; ?>" />
  <input type="hidden" name="list_order" value="no" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($zone);
?>
