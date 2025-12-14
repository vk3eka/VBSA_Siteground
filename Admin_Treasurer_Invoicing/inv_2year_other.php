<?php require_once('../Connections/connvbsa.php'); ?>
<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}

if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

$page = "inv_2year_other.php?inv_type=$inv_type";
$_SESSION['page'] = $page;

$inv_page = "../inv_2year_other.php?inv_type=$inv_type";
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

mysql_select_db($database_connvbsa, $connvbsa);
$query_all_invoices = "SELECT inv_id, club_id,  inv_busname, inv_to, inv_email, inv_phone, inv_date, inv_paid_amount, inv_total_all,  inv_status, inv_bad_debt, inv_type FROM inv_to LEFT JOIN clubs ON club_id=ClubNumber WHERE inv_type='$inv_type'  AND (YEAR(inv_date) = YEAR( CURDATE( ) ) OR YEAR(inv_date) = YEAR( CURDATE( ) )-1 OR inv_date IS NULL) ORDER BY inv_id DESC";
$all_invoices = mysql_query($query_all_invoices, $connvbsa) or die(mysql_error());
$row_all_invoices = mysql_fetch_assoc($all_invoices);
$totalRows_all_invoices = mysql_num_rows($all_invoices);

mysql_select_db($database_connvbsa, $connvbsa);
$query_all = "SELECT SUM(total_less_disc) AS allinv FROM inv_to WHERE inv_type='$inv_type'   AND (inv_date is null OR YEAR(inv_date) = YEAR(CURDATE( )) OR YEAR(inv_date) = YEAR(CURDATE( ))-1)";
$all = mysql_query($query_all, $connvbsa) or die(mysql_error());
$row_all = mysql_fetch_assoc($all);
$totalRows_all = mysql_num_rows($all);

mysql_select_db($database_connvbsa, $connvbsa);
$query_paid = "SELECT SUM(inv_paid_amount) AS paid FROM inv_to WHERE inv_type='$inv_type'  AND (inv_date is null OR YEAR(inv_date) = YEAR(CURDATE( )) OR YEAR(inv_date) = YEAR(CURDATE( ))-1)";
$paid = mysql_query($query_paid, $connvbsa) or die(mysql_error());
$row_paid = mysql_fetch_assoc($paid);
$totalRows_paid = mysql_num_rows($paid);

mysql_select_db($database_connvbsa, $connvbsa);
$query_unsent = "SELECT SUM(total_less_disc) AS unsent FROM inv_to  WHERE inv_type='$inv_type'  AND inv_status = 'Not Sent'  AND (inv_date is null OR YEAR(inv_date) = YEAR(CURDATE( )) OR YEAR(inv_date) = YEAR(CURDATE( ))-1)";
$unsent = mysql_query($query_unsent, $connvbsa) or die(mysql_error());
$row_unsent = mysql_fetch_assoc($unsent);
$totalRows_unsent = mysql_num_rows($unsent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_sent = "SELECT SUM(total_less_disc) AS sent FROM inv_to WHERE inv_type='$inv_type'  AND inv_status = 'Sent'  AND (inv_date is null OR YEAR(inv_date) = YEAR(CURDATE( )) OR YEAR(inv_date) = YEAR(CURDATE( ))-1)";
$sent = mysql_query($query_sent, $connvbsa) or die(mysql_error());
$row_sent = mysql_fetch_assoc($sent);
$totalRows_sent = mysql_num_rows($sent);

mysql_select_db($database_connvbsa, $connvbsa);
$query_cust = "SELECT inv_id, inv_busname, inv_to FROM inv_to WHERE inv_type='Other' GROUP BY inv_busname";
$cust = mysql_query($query_cust, $connvbsa) or die(mysql_error());
$row_cust = mysql_fetch_assoc($cust);
$totalRows_cust = mysql_num_rows($cust);
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
<?php include '../admin_xx_includes/db_srch_treas.php';?>
<table width="1000" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>Search all invoices by business title (Invoice To):</td>
    <td><form id="form2" name="form2" method="get" action="xx_search_res.php">
        <input name="INVsearch" type="text" id="INVsearch" size="24" />
        <input type="submit" name="checkinv" id="checkinv" value="Search Invoices" />
      </form></td>
    <td class="greenbg"><a href="../Admin_update_tables/UpdateInvoiceTables.php">Recalculate the Invoice tables</a> </td>
    <td class="greenbg"><a href="AA_inv_index.php">Return to Invoice Menu</a></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td colspan="4" align="center">&nbsp;<?php echo $inv_type ?></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><span class="red_bold">All <?php echo date("Y")?> or <?php echo date("Y")-1 ?> &quot;Other&quot; Invoices - Invoices that are not VBSA, City Clubs or Association related</span></td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="red_bold"> Important: To complete any updated or inserted invoices you MUST recalculate</td>
  </tr>
  <tr>
    <td colspan="4" align="center"  class="red_text">List contains all Invoices made out in the last 2 years</td>
  </tr>
  <tr>
    <td colspan="4" align="center"  class="red_text">Scroll down for a full list of existing customers </td>
  </tr>
  <tr>
    <td colspan="4" align="center" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="greenbg"><a href="user_files/inv_insert_new.php?inv_type=<?php echo $inv_type; ?>">Create an invoice to a customer that does not already exist</a></td>
  </tr>
  <tr>
    <td colspan="4" align="center" >&nbsp;</td>
  </tr>
  <tr>
    <td align="center" >Total value  - $
      <?php if($row_all['allinv']==0) echo "0"; else echo $row_all['allinv']; ?></td>
    <td align="center" >Paid  : $
      <?php if($row_paid['paid']==0) echo "0"; else echo $row_paid['paid']; ?></td>
    <td align="center" >Unsent  : $
      <?php if($row_unsent['unsent']==0) echo "0"; else echo $row_unsent['unsent']; ?></td>
    <td align="center" >Sent  : $
      <?php if($row_sent['sent']==0) echo "0"; else echo $row_sent['sent']; ?></td>
    
  </tr>
  <tr>
    <td colspan="4" align="center" >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" ><strong>Invoice Status:</strong> &nbsp;<img src="../Admin_Images/tick.JPG" height="20" />&nbsp;= Fully paid, &nbsp;&nbsp;<img src="../Admin_Images/cross.JPG" height="18" /> = unpaid or part paid,&nbsp;&nbsp; <img src="../Admin_Images/unsent.JPG" height="20"/>&nbsp;= unsent, &nbsp;&nbsp;<img src="../Admin_Images/bad_debt.JPG" width="20" /> = bad debt</td>
  </tr>
  <tr>
    <td colspan="4" align="right" class="red_bold">&nbsp;</td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td align="center">Inv No</td>
    <td align="left">Business Title</td>
    <td align="left">Invoice To</td>
    <td align="left">Email</td>
    <td align="left">Phone</td>
    <td align="center">Total</td>
    <td align="center">Paid</td>
    <td align="center">Inv Type</td>
    <td align="center">Status</td>
    <td align="center">Issue Date</td>
    <td colspan="4" align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_all_invoices['inv_id']; ?></td>
      <td align="left"><?php echo $row_all_invoices['inv_busname']; ?></td>
      <td align="left"><?php echo $row_all_invoices['inv_to']; ?></td>
      <td align="left" class="page"><a href="mailto:<?php echo $row_all_invoices['inv_email']; ?>"><?php echo $row_all_invoices['inv_email']; ?></a></td>
      <td align="left" class="page"><a href="tel:<?php echo $row_all_invoices['inv_phone']; ?>"><?php echo $row_all_invoices['inv_phone']; ?></a></td>
      <td align="center"><?php echo $row_all_invoices['inv_total_all']; ?></td>
      <td align="center"><?php echo $row_all_invoices['inv_paid_amount']; ?></td>
      <td align="center"><?php echo $row_all_invoices['inv_type']; ?></td>
      <td align="center"><?php
	  //Paid
	  if($row_all_invoices['inv_paid_amount']==$row_all_invoices['inv_total_all'] & $row_all_invoices['inv_paid_amount']>0)
	  echo '<img src="../Admin_Images/tick.JPG" height="15" />';
	  // Bad Debt
	  elseif($row_all_invoices['inv_bad_debt']==1)
	  echo '<img src="../Admin_Images/bad_debt.JPG" height="15" />';
	  // unpaid
	  elseif($row_all_invoices['inv_status']!='Not Sent' & $row_all_invoices['inv_paid_amount']!=$row_all_invoices['inv_total_all'])
	  echo '<img src="../Admin_Images/cross.JPG" height="15" />';
	  // not sent
	  elseif($row_all_invoices['inv_status']=='Not Sent')
	  echo '<img src="../Admin_Images/unsent.JPG" height="15" />';
	   
	  else
	  echo "";
	  ?></td>
      <td align="center">
	  <?php 
	  $newDate = date("jS M Y", strtotime($row_all_invoices['inv_date'])); 
	  $dateyear = date('Y', strtotime($row_all_invoices['inv_date']));
	  if($dateyear<2000)
	  echo "Not set";
	  else
	  echo $newDate;
	   ?>
      </td>
      <td align="center"><a href="user_files/inv_edit.php?inv_id=<?php echo $row_all_invoices['inv_id']; ?>"><img src="../Admin_Images/edit_butt.fw.png" alt="1" height="20" title="Edit Adress or insert paid amount" /></a></td>
      <td><a href="user_files/inv_print_detail.php?inv_id=<?php echo $row_all_invoices['inv_id']; ?>&club_id=<?php echo $row_all_invoices['club_id']; ?> &inv_type=<?php echo $row_all_invoices['inv_type']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="View, update or add items" /></a></td>      
      <td>
       <?php if(isset($row_all_invoices['inv_date'])) { ?> 
      <a href="xx_print.php?inv_id=<?php echo $row_all_invoices['inv_id']; ?>"><img src="../Admin_Images/print.fw.png" height="25" alt="print" title="Print or save as pdf"/></a>
      <?php } else { ?>
      <a href="xx_pre_print.php?inv_id=<?php echo $row_all_invoices['inv_id']; ?>"><img src="../Admin_Images/print.fw.png" height="25" alt="print" title="Print or save as pdf"/></a>
      <?php } ?>
      </td>
      <td><a href="user_files/inv_delete_confirm.php?inv_id=<?php echo $row_all_invoices['inv_id']; ?>"><img src="../Admin_Images/Trash.fw.png" height="22" alt="trash" title="Delete permanently" /></a>
      </td>
    </tr>
    <?php } while ($row_all_invoices = mysql_fetch_assoc($all_invoices)); ?>
</table>
<p>&nbsp;</p>
<table border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="4" align="center" class="red_bold"><p>Existing Customers (listed alphabetically)</p>
      <p>Create an invoice </p>
      <p>for anything apart from VBSA S1 or S2, City Clubs or Association (invoice type =&quot;Other&quot;)</p></td>
  </tr>
  <tr>
    <td align="left"><strong>Business Name</strong></td>
    <td align="left"><strong>Made out to</strong></td>
    <td align="left">&nbsp;</td>
  </tr>
  <?php do { ?>
  <tr>
    <td align="left"><?php echo $row_cust['inv_busname']; ?></td>
    <td align="left"><?php echo $row_cust['inv_to']; ?></td>
    <td nowrap="nowrap"><a href="user_files/inv_insert_from_inv_other.php?inv_id=<?php echo $row_cust['inv_id']; ?>&inv_type=Other"><img src="../Admin_Images/new_doc.fw.png"  height="24" alt="new document" title="Create a new invoice for this customer" /></a></td>
  </tr>
  <?php } while ($row_cust = mysql_fetch_assoc($cust)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($all_invoices);

mysql_free_result($unsent);

mysql_free_result($sent);

mysql_free_result($cust);

mysql_free_result($paid);

mysql_free_result($all);
?>
