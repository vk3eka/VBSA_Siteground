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
  $updateSQL = sprintf("UPDATE DVSA_contact SET contact_name=%s, contact_phone=%s, contact_phone2=%s, contact_fax=%s, contact_position=%s, contact_email=%s, contact_current=%s, contact_order=%s WHERE contact_id=%s",
                       GetSQLValueString($_POST['contact_name'], "text"),
                       GetSQLValueString($_POST['contact_phone'], "text"),
                       GetSQLValueString($_POST['contact_phone2'], "text"),
                       GetSQLValueString($_POST['contact_fax'], "text"),
                       GetSQLValueString($_POST['contact_position'], "text"),
                       GetSQLValueString($_POST['contact_email'], "text"),
                       GetSQLValueString($_POST['contact_current'], "text"),
					   GetSQLValueString($_POST['contact_order'], "text"),
                       GetSQLValueString($_POST['contact_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "DVSA_index_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update = "-1";
if (isset($_GET['contactid'])) {
  $colname_update = $_GET['contactid'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_update = sprintf("SELECT * FROM DVSA_contact WHERE contact_id = %s", GetSQLValueString($colname_update, "int"));
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
    <td><span class="red_bold">Update the selected contact on the DVSA &quot;Contact Us&quot; page</span></td>
    <td><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td width="62" align="right" valign="middle" nowrap="nowrap">Contact_id:</td>
      <td colspan="2"><?php echo $row_update['contact_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Name</td>
      <td colspan="2"><input type="text" name="contact_name" value="<?php echo htmlentities($row_update['contact_name'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Mob Phone</td>
      <td colspan="2"><input type="text" name="contact_phone" value="<?php echo htmlentities($row_update['contact_phone'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Phone 2</td>
      <td colspan="2"><input type="text" name="contact_phone2" value="<?php echo htmlentities($row_update['contact_phone2'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Fax</td>
      <td colspan="2"><input type="text" name="contact_fax" value="<?php echo htmlentities($row_update['contact_fax'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Position</td>
      <td colspan="2"><input type="text" name="contact_position" value="<?php echo htmlentities($row_update['contact_position'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Email</td>
      <td colspan="2"><input type="text" name="contact_email" value="<?php echo htmlentities($row_update['contact_email'], ENT_COMPAT, 'utf-8'); ?>" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Order</td>
      <td width="113">
        <select name="contact_order">
          <option value="NotOrdered" <?php if (!(strcmp("NotOrdered", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>NotOrdered</option>
          <option value="1" <?php if (!(strcmp("1", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
          <option value="2" <?php if (!(strcmp("2", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
          <option value="3" <?php if (!(strcmp("3", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>3</option>
          <option value="4" <?php if (!(strcmp("4", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
          <option value="5" <?php if (!(strcmp("5", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>5</option>
          <option value="6" <?php if (!(strcmp("6", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>6</option>
          <option value="7" <?php if (!(strcmp("7", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>7</option>
          <option value="8" <?php if (!(strcmp("8", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
          <option value="9" <?php if (!(strcmp("9", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>9</option>
          <option value="10" <?php if (!(strcmp("10", htmlentities($row_update['contact_order'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>10</option>
        </select></td>
      <td width="311" valign="middle"> Board Members are sorted by this number</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Current</td>
      <td colspan="2"><select name="contact_current">
        <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_update['contact_current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
        <option value="No" <?php if (!(strcmp("No", htmlentities($row_update['contact_current'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
      </select>      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td colspan="2"><input type="submit" value="Update Board Member" /></td>
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
