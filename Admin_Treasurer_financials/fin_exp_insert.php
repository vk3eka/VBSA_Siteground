<?php require_once('../Connections/connvbsa.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form6")) {
  $insertSQL = sprintf("INSERT INTO financials (ID, item_type, exp_desc, item_amount, item_cat, entered_by, how_paid, paid_to, chq_no, entered_on, fin_year, cal_year, status) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['item_type'], "text"),
                       GetSQLValueString($_POST['exp_desc'], "text"),
                       GetSQLValueString($_POST['item_amount'], "double"),
                       GetSQLValueString($_POST['item_cat'], "text"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString($_POST['paid_to'], "text"),
                       GetSQLValueString($_POST['chq_no'], "text"),
                       GetSQLValueString($_POST['entered_on'], "date"),
                       GetSQLValueString($_POST['fin_year'], "date"),
                       GetSQLValueString($_POST['cal_year'], "date"),
                       GetSQLValueString($_POST['status'], "text"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "AA_treas_fin_index.php";
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
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form6" id="form6">
  <table align="center" cellpadding="5">
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap"><span class="red_bold">Insert Expenditure</span></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Item Category</td>
      <td valign="middle"><select name="item_cat">
          <option selected="selected" value="Administration" <?php if (!(strcmp("Administration", ""))) {echo "SELECTED";} ?>>Administration</option>
          <option value="Miscellaneous" <?php if (!(strcmp("Miscellaneous", ""))) {echo "SELECTED";} ?>>Miscellaneous</option>
      </select></td>
      <td valign="middle">Please select Administration or Miscellaneous</td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Expenditure description:</td>
      <td colspan="2" valign="middle"><input type="text" name="exp_desc" value="" size="50" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Item amount:</td>
      <td colspan="2" valign="middle"><input type="text" name="item_amount" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Payment method:</td>
      <td colspan="2" valign="middle"><select name="how_paid">
          <option selected="selected" value="Chq" <?php if (!(strcmp("Chq", ""))) {echo "SELECTED";} ?>>Chq</option>
          <option value="Cash" <?php if (!(strcmp("Cash", ""))) {echo "SELECTED";} ?>>Cash</option>
          <option value="BT" <?php if (!(strcmp("BT", ""))) {echo "SELECTED";} ?>>BT</option>
          <option value="Other" <?php if (!(strcmp("Other", ""))) {echo "SELECTED";} ?>>Other</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Paid To</td>
      <td colspan="2" valign="middle"><input type="text" name="paid_to" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Cheque Number:</td>
      <td colspan="2" valign="middle"><input type="text" name="chq_no" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="middle" nowrap="nowrap">Status:</td>
      <td colspan="2" valign="middle"><select name="status">
          <option selected="selected" value="Paid" <?php if (!(strcmp("Paid", ""))) {echo "SELECTED";} ?>>Paid</option>
          <option value="Unpaid" <?php if (!(strcmp("Unpaid", ""))) {echo "SELECTED";} ?>>Unpaid</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td colspan="2"><input type="submit" value="Insert Expenditure" /></td>
    </tr>
  </table>
  <input type="hidden" name="ID" value="" />
  <input type="hidden" name="item_type" value="Expenditure" />
  <input type="hidden" name="item_cat" value="Administration" />
  <input type="hidden" name="entered_by" value="Treasurer" />
  <input type="hidden" name="entered_on" value="" />
  <input type="hidden" name="fin_year" value="<?php if($row_fin1['mnth']<=6) { echo date("Y-1"); } elseif($row_fin1['mnth']>=7) { echo date("Y"); } ?> " />
  <input type="hidden" name="cal_year" value="<?php echo date("Y"); ?>" />
  <input type="hidden" name="MM_insert" value="form6" />
</form>
</body>
</html>