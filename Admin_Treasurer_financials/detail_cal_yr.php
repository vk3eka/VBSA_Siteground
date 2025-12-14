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

$colname_fin_cal_year = "-1";
if (isset($_GET['cal_year'])) {
  $colname_fin_cal_year = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_fin_cal_year = sprintf("SELECT financials.ID, financials.cal_year FROM financials WHERE financials.cal_year=%s GROUP BY financials.cal_year", GetSQLValueString($colname_fin_cal_year, "date"));
$fin_cal_year = mysql_query($query_fin_cal_year, $connvbsa) or die(mysql_error());
$row_fin_cal_year = mysql_fetch_assoc($fin_cal_year);
$totalRows_fin_cal_year = mysql_num_rows($fin_cal_year);

$colname_cal_inv = "-1";
if (isset($_GET['cal_year'])) {
  $colname_cal_inv = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_cal_inv = sprintf("SELECT SUM(inv_paid_amount) as invoices, inv_id,  inv_status, inv_cal_year, inv_fin_year FROM inv_to WHERE inv_cal_year = %s AND inv_status='Paid' AND club_id is null GROUP BY inv_cal_year", GetSQLValueString($colname_cal_inv, "date"));
$cal_inv = mysql_query($query_cal_inv, $connvbsa) or die(mysql_error());
$row_cal_inv = mysql_fetch_assoc($cal_inv);
$totalRows_cal_inv = mysql_num_rows($cal_inv);

$colname_cal_memb = "-1";
if (isset($_GET['cal_year'])) {
  $colname_cal_memb = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_cal_memb = sprintf("SELECT SUM(Paid) as mships, Fin_ID, DatePaid, HowMembPaid, memb_cal_year, memb_fin_year FROM members_fin WHERE memb_cal_year = %s GROUP BY memb_cal_year", GetSQLValueString($colname_cal_memb, "date"));
$cal_memb = mysql_query($query_cal_memb, $connvbsa) or die(mysql_error());
$row_cal_memb = mysql_fetch_assoc($cal_memb);
$totalRows_cal_memb = mysql_num_rows($cal_memb);

$colname_tourn_entry = "-1";
if (isset($_GET['cal_year'])) {
  $colname_tourn_entry = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_tourn_entry = sprintf("SELECT SUM(amount_entry) AS entries, entry_cal_year, entry_fin_year, tourn_date_ent FROM tourn_entry WHERE entry_cal_year = %s", GetSQLValueString($colname_tourn_entry, "date"));
$tourn_entry = mysql_query($query_tourn_entry, $connvbsa) or die(mysql_error());
$row_tourn_entry = mysql_fetch_assoc($tourn_entry);
$totalRows_tourn_entry = mysql_num_rows($tourn_entry);

$colname_pp_cost = "-1";
if (isset($_GET['cal_year'])) {
  $colname_pp_cost = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_pp_cost = sprintf("SELECT SUM(amount_entry*.024) + COUNT(amount_entry)*.3 AS pp_cost FROM tourn_entry WHERE how_paid='PP' AND entry_cal_year = %s", GetSQLValueString($colname_pp_cost, "date"));
$pp_cost = mysql_query($query_pp_cost, $connvbsa) or die(mysql_error());
$row_pp_cost = mysql_fetch_assoc($pp_cost);
$totalRows_pp_cost = mysql_num_rows($pp_cost);

$colname_cal_inv_clubs = "-1";
if (isset($_GET['cal_year'])) {
  $colname_cal_inv_clubs = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_cal_inv_clubs = sprintf("SELECT SUM(inv_paid_amount) as invoices, inv_id, club_id, inv_status, inv_cal_year, inv_fin_year FROM inv_to WHERE inv_cal_year = %s AND inv_status='Paid' AND club_id is not null GROUP BY inv_cal_year", GetSQLValueString($colname_cal_inv_clubs, "date"));
$cal_inv_clubs = mysql_query($query_cal_inv_clubs, $connvbsa) or die(mysql_error());
$row_cal_inv_clubs = mysql_fetch_assoc($cal_inv_clubs);
$totalRows_cal_inv_clubs = mysql_num_rows($cal_inv_clubs);

$colname_cal_inv_unpaid = "-1";
if (isset($_GET['cal_year'])) {
  $colname_cal_inv_unpaid = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_cal_inv_unpaid = sprintf("SELECT SUM( IFNULL( inv_total_all, 0 ) ) - SUM( IFNULL( inv_paid_amount, 0 ) ) AS invoices FROM inv_to WHERE inv_cal_year = %s AND inv_status<>'Paid' AND club_id is null", GetSQLValueString($colname_cal_inv_unpaid, "date"));
$cal_inv_unpaid = mysql_query($query_cal_inv_unpaid, $connvbsa) or die(mysql_error());
$row_cal_inv_unpaid = mysql_fetch_assoc($cal_inv_unpaid);
$totalRows_cal_inv_unpaid = mysql_num_rows($cal_inv_unpaid);

$colname_cal_inv_unpaid_clubs = "-1";
if (isset($_GET['cal_year'])) {
  $colname_cal_inv_unpaid_clubs = $_GET['cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_cal_inv_unpaid_clubs = sprintf("SELECT SUM( IFNULL( inv_total_all, 0 ) ) - SUM( IFNULL( inv_paid_amount, 0 ) ) AS invoices FROM inv_to WHERE inv_cal_year = %s AND inv_status<>'Paid' AND club_id is not null", GetSQLValueString($colname_cal_inv_unpaid_clubs, "date"));
$cal_inv_unpaid_clubs = mysql_query($query_cal_inv_unpaid_clubs, $connvbsa) or die(mysql_error());
$row_cal_inv_unpaid_clubs = mysql_fetch_assoc($cal_inv_unpaid_clubs);
$totalRows_cal_inv_unpaid_clubs = mysql_num_rows($cal_inv_unpaid_clubs);
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

<link href="../Admin_xx_CSS/Financials.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/vbsa_DB.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/VBSA_db_links.css" rel="stylesheet" type="text/css" />
<link href="../Admin_xx_CSS/Datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include '../admin_xx_includes/db_nav.php';?>
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center" class="red_bold"><?php echo $row_fin_cal_year['cal_year']; ?> CALENDAR YEAR REPORT</td>
    <td width="262" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>

<table width="800" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td>&nbsp;</td>
    <td><span class="expenditure"><span class="red_bold">Income</span></span></td>
    <td>&nbsp;</td>
    <td><span class="income"><span class="red_bold">Expenditure</span></span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>     
  <tr>
    <td><span class="title">Paid Invoices (not clubs)</span></td>
    <td><span class="income"><?php echo "$ ".number_format ($row_cal_inv['invoices'], 2); ?></span></td>
    <td><a href="detail_cal_yr_inv.php?inv_cal_year=<?php echo $row_fin_cal_year['cal_year']; ?>"><img src="../Admin_Images/detail.fw.png" width="20" height="20" title="view all paid invoices (not clubs)" /></a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    
  <tr>
    <td>Paid invoices to clubs</td>
    <td><span class="income"><?php echo "$ ".number_format ($row_cal_inv_clubs['invoices'], 2); ?></span></td>
    <td><a href="detail_cal_yr_clubs.php?inv_cal_year=<?php echo $row_fin_cal_year['cal_year']; ?>"><img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="view all paid invoices to clubs" /></a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="title">Memberships</span></td>
    <td><?php echo "$ ".number_format ($row_cal_memb['mships'], 2); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td><span class="title">Tournament Entry</span></td>
    <td><?php echo "$ ".number_format ($row_tourn_entry['entries'], 2); ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td>Tournament Entry Paypal costs</td>
    <td>&nbsp;</td>
    <td class="red_text">&nbsp;</td>
    <td class="red_text"><?php echo "-$ ".number_format ($row_pp_cost['pp_cost'], 2); ?></td>
    <td>Auto calculated</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="red_bold">Total - not inc outstanding</span></td>
    <td><?php 
	$sum = $row_cal_inv['invoices'] + $row_cal_memb['mships'] + $row_tourn_entry['entries'] + $row_cal_inv_clubs['invoices']; 
	echo "$ ".number_format($sum, 2);
	?></td>
    <td>&nbsp;</td>
    <td><span class="red_bold">
      <?php
$result1 = mysql_query('SELECT SUM(amount_entry*.024) + COUNT(amount_entry)*.3 AS pp_cost FROM tourn_entry WHERE how_paid="PP"'); 
$row1 = mysql_fetch_assoc($result1); 
$sum = $row1['pp_cost'];
echo "$ ".number_format($sum, 2);
?>
    </span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Unpaid Invoices (not clubs)</td>
    <td><span class="income"><?php echo "$ ".number_format ($row_cal_inv_unpaid['invoices'], 2); ?></span></td>
    <td><a href="detail_cal_yr_unpaid.php?inv_cal_year=<?php echo $row_fin_cal_year['cal_year']; ?>"><img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="view all outstanding invoices" /></a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Unpaid Invoices to clubs</td>
    <td><span class="income"><?php echo "$ ".number_format ($row_cal_inv_unpaid_clubs['invoices'], 2); ?></span></td>
    <td><a href="detail_cal_yr_unpaid_clubs.php?inv_cal_year=<?php echo $row_fin_cal_year['cal_year']; ?>"><img src="../Admin_Images/detail.fw.png" alt="" width="20" height="20" title="view outstanding invoices to clubs" /></a></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><span class="red_bold">Total - inc outstanding</span></td>
    <td><?php 
	$sum = $row_cal_inv['invoices'] + $row_cal_memb['mships'] + $row_tourn_entry['entries'] + $row_cal_inv_clubs['invoices'] + $row_cal_inv_unpaid['invoices'] + $row_cal_inv_unpaid_clubs['invoices']; 
	echo "$ ".number_format($sum, 2);
	?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($cal_inv);

mysql_free_result($fin_cal_year);

mysql_free_result($cal_memb);

mysql_free_result($tourn_entry);

mysql_free_result($pp_cost);

mysql_free_result($cal_inv_clubs);

mysql_free_result($cal_inv_unpaid);

mysql_free_result($cal_inv_unpaid_clubs);
?>
