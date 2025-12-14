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
  $insertSQL = sprintf("INSERT INTO inv_to (inv_id, inv_busname, inv_to, inv_street, inv_suburb, inv_city, inv_postcode, inv_email, inv_phone, inv_fax, inv_type, inv_paid_amount, inv_paid_date, inv_comment, inv_status, inv_cal_year, inv_fin_year, inv_random) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inv_id'], "int"),
                       GetSQLValueString($_POST['inv_busname'], "text"),
                       GetSQLValueString($_POST['inv_to'], "text"),
                       GetSQLValueString($_POST['inv_street'], "text"),
                       GetSQLValueString($_POST['inv_suburb'], "text"),
                       GetSQLValueString($_POST['inv_city'], "text"),
                       GetSQLValueString($_POST['inv_postcode'], "int"),
                       GetSQLValueString($_POST['inv_email'], "text"),
                       GetSQLValueString($_POST['inv_phone'], "text"),
                       GetSQLValueString($_POST['inv_fax'], "text"),
                       GetSQLValueString($_POST['inv_type'], "text"),
                       GetSQLValueString($_POST['inv_paid_amount'], "double"),
                       GetSQLValueString($_POST['inv_paid_date'], "date"),
                       GetSQLValueString($_POST['inv_comment'], "text"),
                       GetSQLValueString($_POST['inv_status'], "text"),
                       GetSQLValueString($_POST['inv_cal_year'], "date"),
                       GetSQLValueString($_POST['inv_fin_year'], "date"),
                       GetSQLValueString($_POST['inv_random'], "int"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "../inv_2year_other.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_fin1 = "SELECT DATE_FORMAT( inv_date,'%m')AS mnth, inv_id, inv_date FROM inv_to";
$fin1 = mysql_query($query_fin1, $connvbsa) or die(mysql_error());
$row_fin1 = mysql_fetch_assoc($fin1);
$totalRows_fin1 = mysql_num_rows($fin1);

mysql_select_db($database_connvbsa, $connvbsa);
$query_inv_no = "SELECT MAX(inv_id) FROM inv_to";
$inv_no = mysql_query($query_inv_no, $connvbsa) or die(mysql_error());
$row_inv_no = mysql_fetch_assoc($inv_no);
$totalRows_inv_no = mysql_num_rows($inv_no);
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
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
</head>

<body>
<?php include '../../admin_xx_includes/db_nav.php';?>
<?php include '../../admin_xx_includes/db_srch_treas.php';?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('inv_busname','','R');return document.MM_returnValue">
  <table border="1" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td>Search all invoices by business title (Invoice To):</td>
      <td><input name="INVsearch" type="text" id="INVsearch" size="24" />
        <input type="submit" name="checkinv" id="checkinv" value="Search Invoices" /></td>
      <td><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <div class="calendar_table_border" onfocus="MM_validateForm('inv_busname','','R');return document.MM_returnValue">
  <table width="644" align="center">
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" class="red_text" nowrap="nowrap">Create a new invoice - to a customer that has not been invoiced before</td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice Number</td>
      <td>Auto Generated</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Business Name:</td>
      <td><input name="inv_busname" type="text" id="inv_busname" value="" size="75" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Attention</td>
      <td><input type="text" name="inv_to" value="" size="75" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Adresss</td>
      <td><input type="text" name="inv_street" value="" size="75" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Suburb</td>
      <td><input type="text" name="inv_suburb" value="" size="75" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">State:</td>
      <td><input type="text" name="inv_city" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Postcode:</td>
      <td><input type="text" name="inv_postcode" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Inv email to:</td>
      <td><input type="text" name="inv_email" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Inv phone:</td>
      <td><input type="text" name="inv_phone" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Inv fax:</td>
      <td><input type="text" name="inv_fax" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice Status: </td>
      <td>Auto inserted as &quot;Not Sent&quot;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Invoice Date:</td>
      <td>Will be inserted when invoice is printed</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Paid:</td>
      <td>Auto inserted as &quot;No&quot; please edit to change</td>
    </tr>

    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Invoice Type:</td>
      <td>Auto inserted as &quot;Other&quot;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Inv_comment:</td>
      <td><textarea name="inv_comment" cols="75" rows="5"></textarea>          </td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Create Invoice" /></td>
    </tr>
  </table></div>
<input type="hidden" name="inv_id" value="<?php echo $row_inv_no['MAX(inv_id)']+1; ?>" />
  <input type="hidden" name="inv_type" value="Other" />
  <input type="hidden" name="inv_paid_amount" value="No" />
  <input type="hidden" name="inv_paid_date" value="" />
  <input type="hidden" name="inv_status" value="Not Sent" />
  <input type="hidden" name="inv_random" value="<?php echo(rand(100000000000,9999999999999)); ?> " />
  <input type="hidden" name="inv_cal_year" value="<?php echo date("Y"); ?> " />
  <input type="hidden" name="inv_fin_year" value="<?php if($row_fin1['mnth']>"6") { echo date("Y"); } elseif($row_fin1['mnth']<"7") { echo date("Y")-1; } ?> " />
  <input type="hidden" name="MM_insert" value="form1" />
  </form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($fin1);

mysql_free_result($inv_no);
?>