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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE MSBA_contact SET contact_name=%s, contact_phone=%s, contact_position=%s, contact_email=%s, contact_order=%s, contact_current=%s WHERE contact_id=%s",
                       GetSQLValueString($_POST['contact_name'], "text"),
                       GetSQLValueString($_POST['contact_phone'], "text"),
                       GetSQLValueString($_POST['contact_position'], "text"),
                       GetSQLValueString($_POST['contact_email'], "text"),
					   GetSQLValueString($_POST['contact_order'], "text"),
					   GetSQLValueString(isset($_POST['contact_current']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['contact_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../MSBA/MSBA_index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update = "-1";
if (isset($_GET['MSBA'])) {
  $colname_update = $_GET['MSBA'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_update = sprintf("SELECT * FROM MSBA_contact WHERE contact_id = %s", GetSQLValueString($colname_update, "int"));
$update = mysql_query($query_update, $connvbsa) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);
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
<table width="800" align="center">
  <tr>
    <td><span class="red_bold">Update the selected contact on the MSBA &quot;Contact Us&quot; page</span></td>
    <td align="right" class="page"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Contact ID:</td>
      <td><?php echo $row_update['contact_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Name</td>
      <td><input type="text" name="contact_name" value="<?php echo htmlentities($row_update['contact_name'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Phone</td>
      <td><input type="text" name="contact_phone" value="<?php echo htmlentities($row_update['contact_phone'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Position</td>
      <td><input type="text" name="contact_position" value="<?php echo htmlentities($row_update['contact_position'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email</td>
      <td><input type="text" name="contact_email" value="<?php echo htmlentities($row_update['contact_email'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Appear on Website: </td>
      <td><input type="checkbox" name="contact_current" id="contact_current"  <?php if (!(strcmp(htmlentities($row_update['contact_current'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /> 
      (checked = &quot;Yes&quot;)</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Order</td>
      <td><select name="contact_order">
        <option value="no" <?php if (!(strcmp("no", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>no</option>
        <option value="01" <?php if (!(strcmp("01", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>01</option>
        <option value="02" <?php if (!(strcmp("02", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>02</option>
        <option value="03" <?php if (!(strcmp("03", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>03</option>
        <option value="04" <?php if (!(strcmp("04", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>04</option>
        <option value="05" <?php if (!(strcmp("05", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>05</option>
        <option value="06" <?php if (!(strcmp("06", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>06</option>
        <option value="07" <?php if (!(strcmp("07", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>07</option>
        <option value="08" <?php if (!(strcmp("08", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>08</option>
      </select>
      Entries are sorted by this number </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update Contact" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="contact_id" value="<?php echo $row_update['contact_id']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($update);
?>
