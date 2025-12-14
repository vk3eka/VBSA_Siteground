<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

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
  $updateSQL = sprintf("UPDATE inv_items SET item_name=%s, item_discount=%s, discount_total=%s, item_amount=%s, apply_GST=%s, item_total=%s, GST=%s, item_total_all=%s WHERE inv_item_id=%s",
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_discount'], "double"),
                       GetSQLValueString($_POST['discount_total'], "double"),
                       GetSQLValueString($_POST['item_amount'], "double"),
                       GetSQLValueString($_POST['apply_GST'], "text"),
                       GetSQLValueString($_POST['item_total'], "double"),
                       GetSQLValueString($_POST['GST'], "double"),
                       GetSQLValueString($_POST['item_total_all'], "double"),
                       GetSQLValueString($_POST['inv_item_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "inv_print_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE inv_items SET item_name=%s, item_discount=%s, item_amount=%s, apply_GST=%s WHERE inv_item_id=%s",
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_discount'], "double"),
                       GetSQLValueString($_POST['item_amount'], "double"),
                       GetSQLValueString($_POST['apply_GST'], "text"),
                       GetSQLValueString($_POST['inv_item_id'], "int"));

  $updateGoTo = "inv_print_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Item = "SELECT inv_item_id, item_name, item_discount, item_amount, discount_total, apply_GST FROM inv_items WHERE inv_item_id ='$item_id'";
$Item = mysql_query($query_Item, $connvbsa) or die(mysql_error());
$row_Item = mysql_fetch_assoc($Item);
$totalRows_Item = mysql_num_rows($Item);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />


<script type="text/javascript" src="../../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../../Scripts/datepicker.js"></script>

<link href="../../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body>

<table width="1000" border="0" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>


<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table width="370" align="center">
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap" class="red_bold"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="center" nowrap="nowrap" class="red_bold">&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="left" nowrap="nowrap" class="red_bold">Edit item ID: <?php echo $item_id; ?> on Invoice number: <?php echo $inv_id; ?></td>
        </tr>
        <tr valign="baseline">
          <td align="right" valign="top" nowrap="nowrap">Item Description:</td>
          <td><textarea name="item_name" cols="60" rows="3"><?php echo htmlentities($row_Item['item_name'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Item Cost:</td>
          <td><input type="text" name="item_amount" value="<?php echo htmlentities($row_Item['item_amount'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>

        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Discount:</td>
          <td><input type="text" name="item_discount" value="<?php echo htmlentities($row_Item['item_discount'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apply GST:</td>
          <td><select name="apply_GST">
              <option value="No" <?php if (!(strcmp("No", htmlentities($row_Item['apply_GST'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>No</option>
              <option value="Yes" <?php if (!(strcmp("Yes", htmlentities($row_Item['apply_GST'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>Yes</option>
          </select> </td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Update Item" /></td>
        </tr>
      </table>
<input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="inv_item_id" value="<?php echo $row_Item['inv_item_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Item);
?>
