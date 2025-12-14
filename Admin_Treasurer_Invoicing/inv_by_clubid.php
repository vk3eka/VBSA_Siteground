<?php require_once('../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}
 
if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

$inv_page = "../inv_by_clubid.php";
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
$query_inv_all = "SELECT * FROM inv_to WHERE club_id ='$club_id' ORDER BY inv_id DESC";
$inv_all = mysql_query($query_inv_all, $connvbsa) or die(mysql_error());
$row_inv_all = mysql_fetch_assoc($inv_all);
$totalRows_inv_all = mysql_num_rows($inv_all);
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
<table border="1" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>Search all invoices by business title (Invoice To):</td>
    <td><form id="form" name="form2" method="get" action="xx_search_res.php">
      <input name="INVsearch" type="text" id="INVsearch" size="24" />
      <input type="submit" name="checkinv" id="checkinv" value="Search Invoices" />
    </form></td>
    <td class="greenbg"><a href="../Admin_update_tables/UpdateInvoiceTables.php">Recalculate the Invoice tables</a> </td>
    <td class="greenbg"><a href="<?php echo $_SESSION['page']; ?>">Return to Previous page</a></td>
  </tr>
</table>
<table align="center">
  <tr>
    <td align="center" class="red_bold">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="red_bold">All Invoices that have been issued to : Club ID <?php echo $row_inv_all['club_id']; ?> </td>
  </tr>
  <tr>
    <td align="left">&nbsp;<?php echo $_SESSION['page']; ?></td>
  </tr>
  <tr>
    <td align="center" valign="middle"><strong>Invoice Status:</strong> &nbsp;<img src="../Admin_Images/tick.JPG" height="20" />&nbsp;= Fully paid, &nbsp;&nbsp;<img src="../Admin_Images/cross.JPG" height="18" /> = unpaid or part paid,&nbsp;&nbsp; <img src="../Admin_Images/unsent.JPG" height="20"/>&nbsp;= unsent, &nbsp;&nbsp;<img src="../Admin_Images/bad_debt.JPG" width="20" /> = bad debt</td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
</table>
<table border="1" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td align="center">Invoice Number</td>
    <td align="left">Club Title</td>
    <td align="left">Invoice To</td>
    <td align="left">Email</td>
    <td align="left">Phone</td>
    <td align="left">Issue Date</td>
    <td align="center">Amount Paid</td>
    <td align="center">Date Paid</td>
    <td align="center">Invoice Total</td>
    <td align="center">For</td>
    <td align="center">Status</td>
    <td align="center" nowrap="nowrap">Issue Date</td>
    <td colspan="5" align="center">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_inv_all['inv_id']; ?></td>
      <td align="left"><?php echo $row_inv_all['inv_busname']; ?></td>
      <td align="left"><?php echo $row_inv_all['inv_to']; ?></td>
      <td align="left"><?php echo $row_inv_all['inv_email']; ?></td>
      <td align="left"><?php echo $row_inv_all['inv_phone']; ?></td>
      <td align="left"><?php echo $row_inv_all['inv_date']; ?></td>
      <td align="center"><?php echo $row_inv_all['inv_paid_amount']; ?></td>
      <td align="center"><?php echo $row_inv_all['inv_paid_date']; ?></td>
      <td align="center"><?php echo $row_inv_all['inv_total_all']; ?></td>
      <td align="center"><?php echo $row_inv_all['inv_type']; ?></td>
      <td align="center"><?php
	  //Paid
	  if($row_inv_all['inv_paid_amount']==$row_inv_all['inv_total_all'] & $row_inv_all['inv_paid_amount']>0)
	  echo '<img src="../../Admin_Images/tick.JPG" height="15" />';
	  // Bad Debt
	  elseif($row_inv_all['inv_bad_debt']==1)
	  echo '<img src="../../Admin_Images/bad_debt.JPG" height="15" />';
	  // unpaid
	  elseif($row_inv_all['inv_status']!='Not Sent' & $row_inv_all['inv_paid_amount']!=$row_inv_all['inv_total_all'])
	  echo '<img src="../../Admin_Images/cross.JPG" height="15" />';
	  // not sent
	  elseif($row_inv_all['inv_status']=='Not Sent')
	  echo '<img src="../../Admin_Images/unsent.JPG" height="15" />';
	   
	  else
	  echo "";
	  ?></td>
      <td align="center">&nbsp;<?php 
	  $newDate = date("jS M Y", strtotime($row_inv_all['inv_date'])); 
	  $dateyear = date('Y', strtotime($row_inv_all['inv_date']));
	  if($dateyear<2000)
	  echo "Not set";
	  else
	  echo $newDate;
	   ?></td>
      <td align="center"><a href="user_files/inv_edit.php?inv_id=<?php echo $row_inv_all['inv_id']; ?>&amp;club_id=<?php echo $club_id; ?>"><img src="../Admin_Images/edit_butt.fw.png" alt="1" height="20" title="Edit Adress and status" /></a></td>
      <td align="center"><a href="user_files/inv_insert_from_club_id.php?inv_id=<?php echo $row_inv_all['inv_id']; ?>&club_id=<?php echo $row_inv_all['club_id']; ?>&inv_type=<?php echo $row_inv_all['inv_type']; ?>"><img src="../Admin_Images/new_doc.fw.png"  height="24" title="Create a new invoice for this customer" /></a></td>
      <td align="center"><a href="user_files/inv_print_detail.php?inv_id=<?php echo $row_inv_all['inv_id']; ?>&amp;inv_type=<?php echo $inv_all ?>"><img src="../Admin_Images/detail.fw.png" alt="3" width="20" height="20" title="View invoice, Add items, Update" /></a></td>
      <td>
       <?php if(isset($row_inv_all['inv_date'])) { ?> 
      <a href="xx_print.php?inv_id=<?php echo $row_inv_all['inv_id']; ?>"><img src="../Admin_Images/print.fw.png" height="25" alt="print" title="Print or save as pdf"/></a>
      <?php } else { ?>
      <a href="xx_pre_print.php?inv_id=<?php echo $row_inv_all['inv_id']; ?>"><img src="../Admin_Images/print.fw.png" height="25" alt="print" title="Print or save as pdf"/></a>
      <?php } ?>
      </td>
      
      <td align="center"><a href="user_files/inv_delete_confirm.php?inv_id=<?php echo $row_inv_all['inv_id']; ?>&club_id=<?php echo $club_id; ?>"><img src="../Admin_Images/Trash.fw.png" height="22" alt="trash" /></a></td>
    </tr>
    <?php } while ($row_inv_all = mysql_fetch_assoc($inv_all)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($inv_all);
?>
