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
  $insertSQL = sprintf("INSERT INTO inv_items (inv_item_id, inv_no, item_name, item_discount, discount_total, item_amount, apply_GST, item_total) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['inv_item_id'], "int"),
                       GetSQLValueString($_POST['inv_no'], "int"),
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_discount'], "double"),
                       GetSQLValueString($_POST['discount_total'], "double"),
                       GetSQLValueString($_POST['item_amount'], "double"),
                       GetSQLValueString($_POST['apply_GST'], "text"),
                       GetSQLValueString($_POST['item_total'], "double"));

  mysql_select_db($database_connvbsa, $connvbsa);
  $Result1 = mysql_query($insertSQL, $connvbsa) or die(mysql_error());

  $insertGoTo = "inv_print_detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv = "SELECT * FROM inv_to WHERE inv_id = '$inv_id'";
$Inv = mysql_query($query_Inv, $connvbsa) or die(mysql_error());
$row_Inv = mysql_fetch_assoc($Inv);
$totalRows_Inv = mysql_num_rows($Inv);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<table width="1000" border="0" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('item_name','','R','item_amount','','R');return document.MM_returnValue">
      <table width="800" align="center">
        <tr>
          <td colspan="6" align="left" class="red_bold">Administration Treasurer - Insert an item</td>
          <td colspan="3" align="right"> <input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
          <th align="right">&nbsp;</th>
          <td colspan="5" class="page">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="right" nowrap="nowrap">Insert an item to this invoice number:</td>
          <td><?php echo $row_Inv['inv_id']; ?></td>
          <td align="right">to:</td>
          <td colspan="4" align="left"><?php echo $row_Inv['inv_busname']; ?></td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td colspan="7">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="95" align="right" nowrap="nowrap">Item Description</td>
          <td colspan="7"><input name="item_name" type="text" id="item_name" value="" size="60" /></td>
          <td width="113"><input type="hidden" name="MM_insert" value="form1" />
          <input type="hidden" name="item_total" value="" />
          <input type="hidden" name="discount_total" value="" />
          <input type="hidden" name="inv_no" value="<?php echo $row_Inv['inv_id']; ?>" />
          <input type="hidden" name="inv_item_id" value="" /></td>
        </tr>
        <tr>
          <td align="right">Discount:</td>
          <td width="60"><input type="text" name="item_discount" value="" size="10" /></td>
          <td width="34" align="right">Value:</td>
          <td width="64"><input name="item_amount" type="text" id="item_amount" value="" size="10" /></td>
          <td width="65" align="right">GST:</td>
          <td width="76" align="left"><select name="apply_GST">
            <option value="No" <?php if (!(strcmp("No", ""))) {echo "SELECTED";} ?>>No</option>
            <option value="Yes" <?php if (!(strcmp("Yes", ""))) {echo "SELECTED";} ?>>Yes</option>
          </select></td>
          <td width="135" align="right"><input type="submit" value="Insert item" /></td>
          <td width="113" align="right">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td colspan="4" align="right">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td colspan="8">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="9" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="9" align="left">&nbsp;</td>
        </tr>
      </table>
  </form>

</body>
</html>
<?php
mysql_free_result($Inv);
?>

