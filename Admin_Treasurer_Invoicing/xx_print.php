<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

$inv_page = "../xx_print.php";
$_SESSION['inv_page'] = $inv_page;

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

$colname_Inv = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv = sprintf("SELECT * FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv, "int"));
$Inv = mysql_query($query_Inv, $connvbsa) or die(mysql_error());
$row_Inv = mysql_fetch_assoc($Inv);
$totalRows_Inv = mysql_num_rows($Inv);

$colname_Item = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Item = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Item = sprintf("SELECT inv_item_id, inv_no, item_name, item_discount, item_amount, inv_to.inv_id, inv_items.discount_total, inv_items.apply_GST, inv_items.item_total, inv_items.GST, inv_items.item_total_all FROM inv_items, inv_to WHERE inv_no=inv_to.inv_id AND inv_no = %s ORDER BY inv_item_id", GetSQLValueString($colname_Item, "int"));
$Item = mysql_query($query_Item, $connvbsa) or die(mysql_error());
$row_Item = mysql_fetch_assoc($Item);
$totalRows_Item = mysql_num_rows($Item);

$colname_Inv_det = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv_det = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_det = sprintf("SELECT * FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv_det, "int"));
$Inv_det = mysql_query($query_Inv_det, $connvbsa) or die(mysql_error());
$row_Inv_det = mysql_fetch_assoc($Inv_det);
$totalRows_Inv_det = mysql_num_rows($Inv_det);

$colname_Inv_no = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv_no = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_no = sprintf("SELECT date_format( inv_date, '%%b %%e, %%Y' ) AS date, DATE_ADD( inv_date, INTERVAL 14 DAY ) AS duedate, inv_id, inv_date FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv_no, "int"));
$Inv_no = mysql_query($query_Inv_no, $connvbsa) or die(mysql_error());
$row_Inv_no = mysql_fetch_assoc($Inv_no);
$totalRows_Inv_no = mysql_num_rows($Inv_no);

$colname_invtot = "-1";
if (isset($_GET['inv_id'])) {
  $colname_invtot = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_invtot = sprintf("SELECT * FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_invtot, "int"));
$invtot = mysql_query($query_invtot, $connvbsa) or die(mysql_error());
$row_invtot = mysql_fetch_assoc($invtot);
$totalRows_invtot = mysql_num_rows($invtot);

$colname_Inv_comment = "-1";
if (isset($_GET['inv_id'])) {
  $colname_Inv_comment = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_Inv_comment = sprintf("SELECT inv_id, inv_comment FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_Inv_comment, "int"));
$Inv_comment = mysql_query($query_Inv_comment, $connvbsa) or die(mysql_error());
$row_Inv_comment = mysql_fetch_assoc($Inv_comment);
$totalRows_Inv_comment = mysql_num_rows($Inv_comment);

$colname_inv_pay = "-1";
if (isset($_GET['inv_id'])) {
  $colname_inv_pay = $_GET['inv_id'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_inv_pay = sprintf("SELECT DATE_ADD( inv_date, INTERVAL 14 DAY ) AS duedate, inv_id, inv_date, inv_GST_total, inv_total_all, inv_paid_amount, IFNULL( SUM( inv_total_all - inv_paid_amount ) , 0 ) AS outstanding FROM inv_to WHERE inv_id = %s", GetSQLValueString($colname_inv_pay, "int"));
$inv_pay = mysql_query($query_inv_pay, $connvbsa) or die(mysql_error());
$row_inv_pay = mysql_fetch_assoc($inv_pay);
$totalRows_inv_pay = mysql_num_rows($inv_pay);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VBSA Administration</title>

<script type="text/javascript" src="../Scripts/AC_RunActiveContent.js"></script>
<script type="text/javascript" src="../Scripts/datepicker.js"></script>

<link href="../Admin_xx_CSS/Invoice_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />

</head>

<Body>

<div id="inv_wrapper">

	<div id="inv_header"><img src="../Admin_Images/Inv_Header.jpg" width="820" height="145" /></div>


<div id="Invoice_number">
	
  		<?php do { ?>
		<div class="inv_no">INVOICE: <?php echo $row_Inv_no['inv_id']; ?></div>
		<div class="inv_date">Issue Date:</div>
        <div class="inv_date"><?php echo $row_Inv_no['date']; ?></div>
  		<?php } while ($row_Inv_no = mysql_fetch_assoc($Inv_no)); ?>
</div>

<div id="Cust_detail">
  <?php do { ?>

<div class="Bus_name"><div class="name"><strong>Invoice to:</strong></div>
  <?php echo $row_Inv_det['inv_busname']; ?></div>
  
  <div class="Address"><div class="name"><strong>Attention:</strong></div>
<?php echo $row_Inv_det['inv_to']; ?></div>

<div class="Address"><div class="name"><strong>Email to:</strong></div>
<?php echo $row_Inv_det['inv_email']; ?></div>

 <div class="Address"><div class="name"><strong>Address:</strong></div>
<?php echo $row_Inv_det['inv_street']; ?>, <?php echo $row_Inv_det['inv_suburb']; ?>, <?php echo $row_Inv_det['inv_city']; ?>, <?php echo $row_Inv_det['inv_postcode']; ?></div>





<div class="inv_edit"><a href="user_files/inv_edit.php?inv_id=<?php echo $row_Inv['inv_id']; ?>"><img src="../Admin_Images/edit_butt.png" title="Edit" /></a></div>
<?php } while ($row_Inv_det = mysql_fetch_assoc($Inv_det)); ?>


</div>
	
<table width="825" align="left">
  <tr>
    <td width="400"><strong>Item Description</strong></td>
    <td align="center"><strong>Cost</strong></td>
    <td align="center">
      <?php
		if($row_Item['discount_total']=="0")
		{
		echo '&nbsp;';
		}
	    elseif($row_Item['discount_total']>"0")
		{
		echo "<strong>Disc Rate</strong>";
		}
		?>
    </td>
    <td align="center">
      <?php
		if($row_Item['discount_total']=="0")
		{
		echo '&nbsp;';
		}
	    elseif($row_Item['discount_total']>"0")
		{
		echo "<strong>Discount</strong>";
		}
		?>
    </td>
    <td align="center"><strong>Total</strong></td>
    <td align="center"><strong>GST ?</strong></td>
    <td align="center"><strong>GST</strong></td>
    <td align="center"><strong>Inc GST</strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td width="400"><?php echo $row_Item['item_name']; ?></td>
    <td align="center" valign="top" nowrap="nowrap">$<?php echo $row_Item['item_amount']; ?></td>
    <td align="center" valign="top" >
      <?php
		if($row_Item['discount_total']=="0")
		{
		echo '&nbsp;';
		}
	    elseif($row_Item['discount_total']>"0")
		{
		echo $row_Item['item_discount'],"%";
		}
		?>
    </td>
    <td align="center" valign="top">
      <?php
		if($row_Item['discount_total']=="0")
		{
		echo '&nbsp;';
		}
	    elseif($row_Item['discount_total']>"0")
		{
		echo $row_Item['discount_total'];
		}
		?>
    </td>
    <td align="center" valign="top">$<?php echo $row_Item['item_total']; ?></td>
    <td align="center" valign="top"><?php echo $row_Item['apply_GST']; ?></td>
    <td align="center" valign="top">$<?php echo $row_Item['GST']; ?></td>
    <td align="center" valign="top">$<?php echo $row_Item['item_total_all']; ?></td>
  </tr>
  <?php } while ($row_Item = mysql_fetch_assoc($Item)); ?>
  <?php do { ?>
  <tr>
    <td width="400" align="left"><strong>Totals</strong></td>
    <td align="center"><strong>$<?php echo $row_invtot['inv_amount_total']; ?></strong></td>
    <td align="center">&nbsp;</td>
    <td align="center"><?php
		if($row_invtot['inv_discount_total']=="0")
		{
		echo '&nbsp;';
		}
	    elseif($row_invtot['inv_discount_total']>"0")
		{
		echo "<strong>";
		echo "$";
		echo $row_invtot['inv_discount_total'];
		echo "</strong>";
		}
		?></td>
    <td align="center">$<strong><?php echo $row_invtot['total_less_disc']; ?></strong></td>
    <td align="center">&nbsp;</td>
    <td align="center">$<strong><?php echo $row_invtot['inv_GST_total']; ?></strong></td>
    <td align="center">$<strong><?php echo $row_invtot['inv_total_all']; ?></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="9">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Comment:</td>
    <td colspan="8"><?php echo $row_invtot['inv_comment']; ?></td>
    <td><a href="user_files/inv_edit.php?inv_id=<?php echo $row_invtot['inv_id']; ?>"><img src="../Admin_Images/edit_butt.png" title="Edit" /></a></td>
    <td>&nbsp;</td>
  </tr>
  <?php } while ($row_invtot = mysql_fetch_assoc($invtot)); ?>
</table>



<div class="inv_summary">
<table width="825">
  <tr>
    <td>Due Date: <strong><?php $newDate = date("M d, Y", strtotime($row_inv_pay['duedate'])); echo $newDate; ?></strong></td>
    <td align="right" class="please_pay_amount">

<?php
    if($row_inv_pay['inv_paid_amount']==0)
		{
		echo "Please Pay $";
		echo $row_inv_pay['inv_total_all'];
		echo " Total Includes $";
		echo $row_inv_pay['inv_GST_total'];
		echo " In GST ";
		}
	elseif($row_inv_pay['inv_paid_amount']<>$row_inv_pay['inv_total_all'])
		{
		echo "This invoice is part paid: Received $";
		echo $row_inv_pay['inv_paid_amount'];
		echo "&nbsp &nbsp &nbsp";
		echo " Outstanding $";
		echo $row_inv_pay['outstanding'];
		}
	elseif($row_inv_pay['inv_paid_amount']==$row_inv_pay['inv_total_all'])
		{
		echo "Thankyou, Invoice paid in full";
		}
		?>

      </td>
  </tr>
</table>
</div>



<div class="payment_detail"><strong>Payment Details</strong>- By Cheque or Money order, please make payable to VBSA. Post to: VBSA Treasurer, Reventon Snooker Academy, 175D Stephen Street, Yarraville, 3013. <strong>To pay moneys directly to the VBSA by bank transfer</strong> - Our Account is with the ANZ bank - our BSB number is 013236 - Our Account Number is 297730994 Account Name VBSA

When you transfer money to our account we need to know who has sent it and what it is for so please email to treasurer@vbsa.org.au with the details of the transfer.</div>

<div class="payment_back">
  <table width="825" align="center" class='noprint'>
    <tr>
      <td align="center" class="greenbg"><a href="pdf/process.php?inv_id=<?php echo $row_Inv['inv_id']; ?>">Download as pdf</a></td>
      <td align="center" class="greenbg"><a href="AA_inv_index.php">Return to main menu</a></td>
    </tr>
  </table>
    
</div>

<!--Close wrapper --></div>
</body>
</html>
<?php
mysql_free_result($Inv);

mysql_free_result($Item);

mysql_free_result($Inv_det);

mysql_free_result($Inv_no);

mysql_free_result($invtot);

mysql_free_result($Inv_comment);

mysql_free_result($inv_pay);
?>
