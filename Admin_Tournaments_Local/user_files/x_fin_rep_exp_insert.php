<?php require_once('../../Connections/connvbsa.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO tourn_fin (ID, tourn_fin_id, item_type, item_desc, item_amount, item_cat, entered_by, how_paid, chq_no, fin_year, cal_year) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ID'], "int"),
                       GetSQLValueString($_POST['tourn_fin_id'], "int"),
                       GetSQLValueString($_POST['item_type'], "text"),
                       GetSQLValueString($_POST['item_desc'], "text"),
                       GetSQLValueString($_POST['item_amount'], "double"),
                       GetSQLValueString($_POST['item_cat'], "text"),
                       GetSQLValueString($_POST['entered_by'], "text"),
                       GetSQLValueString($_POST['how_paid'], "text"),
                       GetSQLValueString($_POST['chq_no'], "text"),
                       GetSQLValueString($_POST['fin_year'], "date"),
                       GetSQLValueString($_POST['cal_year'], "date"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../x_fin_rep.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['tourn_id'])) {
  $tourn_id = $_GET['tourn_id'];
}


mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_name = "SELECT tourn_id, tourn_name, tourn_year, DATE_FORMAT(tourn_year,'%m')AS mnth FROM tournaments WHERE tourn_id = '$tourn_id'";
$tourn_name = mysql_query($query_tourn_name, $connvbsa) or die(mysql_error());
$row_tourn_name = mysql_fetch_assoc($tourn_name);
$totalRows_tourn_name = mysql_num_rows($tourn_name);

$colname_getusername = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getusername = $_SESSION['MM_Username'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_getusername = sprintf("SELECT id, name, usertype FROM vbsaorga_users WHERE username = %s", GetSQLValueString($colname_getusername, "text"));
$getusername = mysql_query($query_getusername, $connvbsa) or die(mysql_error());
$row_getusername = mysql_fetch_assoc($getusername);
$totalRows_getusername = mysql_num_rows($getusername);
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
<link href="../../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
<link href="../../Admin_xx_CSS/VBSA_Scores_Index.css" rel="stylesheet" type="text/css" /> 
</head>

<body>

<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center"><span class="red_bold">Insert an Expenditure Item</span> for the 
	<?php $date = $row_tourn_name['tourn_year']; echo date("Y", strtotime($date)); ?>
	<?php echo $row_tourn_name['tourn_name']; ?> (Tournament ID: <?php echo $row_tourn_name['tourn_id']; ?>)</td>
    <td><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center" cellpadding="5" cellspacing="5">
      <tr valign="baseline">
        <td colspan="2" align="center" nowrap="nowrap" class="red_bold">test<?php echo $colname_getusername ?></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Description (max 50 characters):</td>
        <td><input type="text" name="item_desc" value="" size="50" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Amount:( No $ sign)</td>
        <td><input type="text" name="item_amount" value="" size="32" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Category:</td>
        <td><select name="item_cat">
          <option value="Miscellaneous" <?php if (!(strcmp("Miscellaneous", ""))) {echo "SELECTED";} ?>>Miscellaneous</option>
          <option value="Prize Fund" <?php if (!(strcmp("Prize Fund", ""))) {echo "SELECTED";} ?>>Prize Fund</option>
          <option value="Sponsor" <?php if (!(strcmp("Sponsor", ""))) {echo "SELECTED";} ?>>Sponsor</option>
          </select></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">How paid:</td>
        <td><select name="how_paid">
          <option value="" >No Entry</option>
          <option value="Chq" <?php if (!(strcmp("Chq", ""))) {echo "SELECTED";} ?>>Chg</option>
          <option value="Cash" <?php if (!(strcmp("Cash", ""))) {echo "SELECTED";} ?>>Cash</option>
          <option value="BT" <?php if (!(strcmp("BT", ""))) {echo "SELECTED";} ?>>BT</option>
          <option value="Other" <?php if (!(strcmp("Other", ""))) {echo "SELECTED";} ?>>Other</option>
          </select></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Cheque no:</td>
        <td><input type="text" name="chq_no" value="" size="32" /></td>
        </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Entered By:</td>
        <td><?php echo $row_getusername['name']; ?></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Insert item" /></td>
        </tr>
      </table>
    <input type="hidden" name="ID" value="" />
    <input type="hidden" name="tourn_fin_id" value="<?php echo $tourn_id; ?>" />
    <input type="hidden" name="entered_by" value="<?php echo $colname_getusername; ?>" />
    <input type="hidden" name="fin_year" value="<?php if($row_tourn_name['mnth']<=6) { echo date("Y-1"); } elseif($row_tourn_name['mnth']>=7) { echo date("Y"); } ?>" />
    <input type="hidden" name="item_type" value="Expenditure" />
    <input type="hidden" name="cal_year" value="<?php echo date("Y"); ?>" />
    <input type="hidden" name="MM_insert" value="form1" />
</form>

</body>
</html>
<?php

?>
