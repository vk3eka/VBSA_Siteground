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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE inv_to SET inv_date=%s, inv_status=%s WHERE inv_id=%s",
  					   GetSQLValueString($_POST['inv_date'], "date"),
                       GetSQLValueString($_POST['inv_status'], "text"),
                       GetSQLValueString($_POST['inv_id'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($updateSQL, $connvbsa) or die(mysql_error());

  $updateGoTo = "xx_print.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_print_prep = "SELECT inv_id, inv_busname, date_format( inv_date, '%b %e, %Y' ) AS date,  inv_status, inv_date FROM inv_to WHERE inv_id = '$inv_id'";
$print_prep = mysql_query($query_print_prep, $connvbsa) or die(mysql_error());
$row_print_prep = mysql_fetch_assoc($print_prep);
$totalRows_print_prep = mysql_num_rows($print_prep);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>
<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />


</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="800" align="center">
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap" class="red_bold">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap" class="red_text">Print or download pdf -  Invoice  number <?php echo $row_print_prep['inv_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="right" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap">Invoice To: <?php echo $row_print_prep['inv_busname']; ?></td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="left" nowrap="nowrap" class="red_bold">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap" class="red_bold">By printing this invoice the Status will be set to &quot;Sent&quot;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap">Inv issue date will be set to: <?php date_default_timezone_set('Australia/Melbourne'); echo date("d M Y"); ?></td>
    </tr>
    <tr valign="baseline">
      <td height="24" colspan="3" align="right" valign="top" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">
      	<input type="submit" value="Set date and go to print" /></td>
      <td align="center" valign="middle" nowrap="nowrap">OR</td>
      <td align="left" nowrap="nowrap"><input type="button" value="Return to Previous page" onclick="history.go(-1)"/></td>
    </tr>
    <tr valign="baseline">
      <td colspan="3" align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
  </table>
<input type="hidden" name="MM_update" value="form1" />
<input type="hidden" name="inv_id" value="<?php echo $row_print_prep['inv_id']; ?>" />
<input type="hidden" name="inv_date" value="<?php date_default_timezone_set('Australia/Melbourne'); echo date("Y-m-d"); ?> " />
<input type="hidden" name="inv_status" value="Sent" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($print_prep);
?>
