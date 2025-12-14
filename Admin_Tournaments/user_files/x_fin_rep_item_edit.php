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

$MM_restrictGoTo = "../../Admin_Treasurer/Treas_sorry.php";
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
<?php require_once('../../Connections/connvbsa.php'); ?><?php
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
  $updateSQL = sprintf("UPDATE tourn_fin SET tourn_fin_id=%s, item_type=%s, item_desc=%s, item_amount=%s, item_cat=%s, entered_by=%s, how_paid=%s, paid_to=%s, chq_no=%s, inv_no=%s, prizefund_rd=%s WHERE ID=%s",
                       GetSQLValueString($_POST['tourn_fin_id'], "int"),
                       GetSQLValueString($_POST['item_type'], "text"),
                       GetSQLValueString($_POST['item_desc'], "text"),
                       GetSQLValueString($_POST['item_amount'], "double"),
                       GetSQLValueString($_POST['item_cat'], "text"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString($_POST['paid_to'], "text"),
                       GetSQLValueString($_POST['chq_no'], "text"),
                       GetSQLValueString($_POST['inv_no'], "int"),
                       GetSQLValueString($_POST['prizefund_rd'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "../x_fin_rep.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_TournFin = "SELECT * FROM tourn_fin WHERE ID = '$item_id'";
$TournFin = mysql_query($query_TournFin, $connvbsa) or die(mysql_error());
$row_TournFin = mysql_fetch_assoc($TournFin);
$totalRows_TournFin = mysql_num_rows($TournFin);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center"><span class="red_bold">Edit a Financial Item</span></td>
    <td><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
  </table>

  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" cellpadding="5" cellspacing="5">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Item Type</td>
        <td><select name="item_type">
          <option value="Expenditure" <?php if (!(strcmp("Expenditure", htmlentities($row_TournFin['item_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Expenditure</option>
          <option value="Income" <?php if (!(strcmp("Income", htmlentities($row_TournFin['item_type'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Income</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Item Description:</td>
        <td><input type="text" name="item_desc" value="<?php echo htmlentities($row_TournFin['item_desc'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Item amount:</td>
        <td><input type="text" name="item_amount" value="<?php echo htmlentities($row_TournFin['item_amount'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Item Category</td>
        <td><select name="item_cat">
          <option value="Miscellaneous" <?php if (!(strcmp("Miscellaneous", htmlentities($row_TournFin['item_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Miscellaneous</option>
          <option value="Prizefund" <?php if (!(strcmp("Prizefund", htmlentities($row_TournFin['item_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Prizefund</option>
          <option value="Sponsor" <?php if (!(strcmp("Sponsor", htmlentities($row_TournFin['item_cat'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Sponsor</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">How paid:</td>
        <td><select name="how_paid">
        <option value="No Entry" <?php if (!(strcmp("", htmlentities($row_TournFin['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
        <option value="Cash" <?php if (!(strcmp("Cash", htmlentities($row_TournFin['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Cash</option>
        <option value="Chq" <?php if (!(strcmp("Chq", htmlentities($row_TournFin['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Chq</option>
        <option value="BT" <?php if (!(strcmp("BT", htmlentities($row_TournFin['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>BT</option>
        <option value="Other" <?php if (!(strcmp("Other", htmlentities($row_TournFin['how_paid'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Other</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Paid to:</td>
        <td><input type="text" name="paid_to" value="<?php echo htmlentities($row_TournFin['paid_to'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Chq no:</td>
        <td><input type="text" name="chq_no" value="<?php echo htmlentities($row_TournFin['chq_no'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Inv no:</td>
        <td><input type="text" name="inv_no" value="<?php echo htmlentities($row_TournFin['inv_no'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Entered By:</td>
        <td><input type="text" name="entered_by" value="<?php echo htmlentities($row_TournFin['entered_by'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Prizefund round::</td>
        <td><select name="prizefund_rd">
          <option value="16" <?php if (!(strcmp("16", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>16</option>
          <option value="08" <?php if (!(strcmp("08", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>8</option>
          <option value="04" <?php if (!(strcmp("04", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>4</option>
          <option value="02" <?php if (!(strcmp("02", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>2</option>
          <option value="01" <?php if (!(strcmp("01", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>1</option>
          <option value="HB" <?php if (!(strcmp("HB", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>HB</option>
          <option value="No Entry" <?php if (!(strcmp("", htmlentities($row_TournFin['prizefund_rd'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No Entry</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Update record" /></td>
      </tr>
    </table>
    <input type="hidden" name="ID" value="<?php echo $row_TournFin['ID']; ?>" />
    <input type="hidden" name="tourn_fin_id" value="<?php echo htmlentities($row_TournFin['tourn_fin_id'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="ID" value="<?php echo $row_TournFin['ID']; ?>" />
</form>
  <p>&nbsp;</p>
<p>&nbsp;</p>
</center>
</body>
</html>
<?php


?>