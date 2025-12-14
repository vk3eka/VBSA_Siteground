<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}


$page = "inv_x_unsent.php";
$_SESSION['page'] = $page;

$inv_page = "../inv_x_unsent.php";
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
$query_all_invoices = "SELECT inv_id, club_id,  inv_busname, inv_to, inv_email, inv_phone, inv_date, inv_paid_amount, inv_total_all,  inv_status, inv_bad_debt, inv_type FROM inv_to  LEFT JOIN clubs ON club_id=ClubNumber WHERE inv_status='Not Sent' ORDER BY inv_busname";
$all_invoices = mysql_query($query_all_invoices, $connvbsa) or die(mysql_error());
$row_all_invoices = mysql_fetch_assoc($all_invoices);
$totalRows_all_invoices = mysql_num_rows($all_invoices);

mysql_select_db($database_connvbsa, $connvbsa);
$query_all = "SELECT SUM(inv_total_all) AS allinv FROM inv_to WHERE inv_status='Not Sent'";
$all = mysql_query($query_all, $connvbsa) or die(mysql_error());
$row_all = mysql_fetch_assoc($all);
$totalRows_all = mysql_num_rows($all);
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
    <td align="center" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_text"><span class="red_bold">Invoices that have not been &quot;Sent&quot; - Important: To complete any updated or inserted invoices you MUST recalculate</span></td>
  </tr>
  <tr>
    <td align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="middle">Total value  - $
      <?php if($row_all['allinv']==0) echo "0"; else echo $row_all['allinv']; ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="middle"><strong>Invoice Status:</strong> &nbsp;<img src="../Admin_Images/tick.JPG" height="20" />&nbsp;= Fully paid, &nbsp;&nbsp;<img src="../Admin_Images/cross.JPG" height="18" /> = unpaid or part paid,&nbsp;&nbsp; <img src="../Admin_Images/unsent.JPG" height="20"/>&nbsp;= unsent, &nbsp;&nbsp;<img src="../Admin_Images/bad_debt.JPG" width="20" /> = bad debt</td>
  </tr>
  <tr>
    <td colspan="4" align="right" class="red_bold">&nbsp;</td>
  </tr>
</table>

<?php if($totalRows_all_invoices==0) { ?>
<table align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td class="red_text">There are no Unsent invoices</td>
  </tr>
</table>
<?php } else { ?>

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
<?php } ?>
</body>
</html>
<?php
mysql_free_result($all_invoices);

mysql_free_result($all);
?>
