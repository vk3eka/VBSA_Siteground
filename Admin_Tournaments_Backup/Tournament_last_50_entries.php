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

mysql_select_db($database_connvbsa, $connvbsa);
$query_t_entry = "SELECT date_format( tourn_year, '%Y') AS Tyear, tournament_number, tournaments.tourn_name, members.FirstName, members.LastName, amount_entry, entered_by, how_paid, junior_cat, members.MemberID FROM tourn_entry, members, tournaments WHERE tourn_memb_id=members.MemberID AND tourn_entry.tournament_number=tournaments.tourn_id ORDER BY tourn_entry.ID DESC";

$maxRows_t_entry = 50;
$pageNum_t_entry = 0;
if (isset($_GET['pageNum_t_entry'])) {
  $pageNum_t_entry = $_GET['pageNum_t_entry'];
}
$startRow_t_entry = $pageNum_t_entry * $maxRows_t_entry;

if (isset($_GET['totalRows_t_entry'])) {
  $totalRows_t_entry = $_GET['totalRows_t_entry'];
} else {
  $all_t_entry = mysql_query($query_t_entry, $connvbsa);
  $totalRows_t_entry = mysql_num_rows($all_t_entry);
}
$totalPages_t_entry = ceil($totalRows_t_entry/$maxRows_t_entry)-1;$maxRows_t_entry = 50;
$pageNum_t_entry = 0;
if (isset($_GET['pageNum_t_entry'])) {
  $pageNum_t_entry = $_GET['pageNum_t_entry'];
}
$startRow_t_entry = $pageNum_t_entry * $maxRows_t_entry;

$query_limit_t_entry = sprintf("%s LIMIT %d, %d", $query_t_entry, $startRow_t_entry, $maxRows_t_entry);
$t_entry = mysql_query($query_limit_t_entry, $connvbsa) or die(mysql_error());
$row_t_entry = mysql_fetch_assoc($t_entry);

if (isset($_GET['totalRows_t_entry'])) {
  $totalRows_t_entry = $_GET['totalRows_t_entry'];
} else {
  $all_t_entry = mysql_query($query_t_entry, $connvbsa);
  $totalRows_t_entry = mysql_num_rows($all_t_entry);
}
$totalPages_t_entry = ceil($totalRows_t_entry/$maxRows_t_entry)-1;
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
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="left"><span class="red_bold">Confirmation - Last 50 Tournament Entries</span></td>
    <td class="greenbg"><a href="aa_tourn_index.php">Return to Tournaments</a></td>
  </tr>
</table>
<table align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center">Tournament Number</td>
    <td align="center">Year</td>
    <td align="left">Name</td>
    <td align="left">First Name</td>
    <td align="left">Last Name</td>
    <td align="center">Member ID</td>
    <td align="center">Paid</td>
    <td align="center">How Paid</td>
    <td align="left">Entered By</td>
    <td align="center">Junior Category</td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_t_entry['tournament_number']; ?></td>
      <td align="center"><?php echo $row_t_entry['Tyear']; ?></td>
      <td align="left"><?php echo $row_t_entry['tourn_name']; ?></td>
      <td align="left"><?php echo $row_t_entry['FirstName']; ?></td>
      <td align="left"><?php echo $row_t_entry['LastName']; ?></td>
      <td align="center"><?php echo $row_t_entry['MemberID']; ?></td>
      <td align="center"><?php echo $row_t_entry['amount_entry']; ?></td>
      <td align="center"><?php echo $row_t_entry['how_paid']; ?></td>
      <td align="left"><?php echo $row_t_entry['entered_by']; ?></td>
      <td align="center"><?php echo $row_t_entry['junior_cat']; ?></td>
    </tr>
    <?php } while ($row_t_entry = mysql_fetch_assoc($t_entry)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($t_entry);
?>
