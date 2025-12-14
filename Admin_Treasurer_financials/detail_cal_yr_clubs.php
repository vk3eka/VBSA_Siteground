<?php 
function tfm_cleanOrderBy($theValue, $defaultSort) {
	if (preg_match("/^[\w,]{1,50}\s+(asc|desc)\s*$/i",$theValue, $matches)) {
		return $matches[0];
	}
	return $defaultSort;
}
?>
<?php require_once('../Connections/connvbsa.php'); ?>
<?php
$tfm_orderby =(!isset($_GET["tfm_orderby"]))?"inv_id":$_GET["tfm_orderby"];
$tfm_order =(!isset($_GET["tfm_order"]))?"ASC":$_GET["tfm_order"];
$sql_orderby = $tfm_orderby." ".$tfm_order;
$sql_orderby = tfm_cleanOrderBy($sql_orderby, "inv_id");
?>
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

//TOMLR Special List Recordset
$colname_inv_det = "-1";
if (isset($_GET['inv_cal_year'])) {
  $colname_inv_det = $_GET['inv_cal_year'];
}
// Defining List Recordset variable
$sqlorderby_inv_det = "inv_id";
if (isset($sql_orderby)) {
  $sqlorderby_inv_det = $sql_orderby;
}
mysql_select_db($database_connvbsa, $connvbsa);

$query_inv_det = sprintf("SELECT * FROM inv_to WHERE inv_cal_year = %s AND inv_to.inv_paid_amount=inv_amount_total AND club_id is not null ORDER BY {$sqlorderby_inv_det}", GetSQLValueString($colname_inv_det, "date"));
$inv_det = mysql_query($query_inv_det, $connvbsa) or die(mysql_error());
$row_inv_det = mysql_fetch_assoc($inv_det);
$totalRows_inv_det = mysql_num_rows($inv_det);
//End TOMLR Special List Recordset

$colname_fin_cal_year = "-1";
if (isset($_GET['inv_cal_year'])) {
  $colname_fin_cal_year = $_GET['inv_cal_year'];
}
mysql_select_db($database_connvbsa, $connvbsa);
$query_fin_cal_year = sprintf("SELECT financials.ID, financials.cal_year FROM financials WHERE financials.cal_year=%s GROUP BY financials.cal_year", GetSQLValueString($colname_fin_cal_year, "date"));
$fin_cal_year = mysql_query($query_fin_cal_year, $connvbsa) or die(mysql_error());
$row_fin_cal_year = mysql_fetch_assoc($fin_cal_year);
$totalRows_fin_cal_year = mysql_num_rows($fin_cal_year);
?>
<?php
//sort column headers for inv_det
$tfm_saveParams = explode(",","");
$tfm_keepParams = "";
if($tfm_order == "ASC") {
	$tfm_order = "DESC";
}else{
	$tfm_order = "ASC";
};
while (list($key,$val) = each($tfm_saveParams)) {
	if(isset($_GET[$val]))$tfm_keepParams .= ($val)."=".urlencode($_GET[$val])."&";	
	if(isset($_POST[$val]))$tfm_keepParams .= ($val)."=".urlencode($_POST[$val])."&";
}
$tfm_orderbyURL = $_SERVER["PHP_SELF"]."?".$tfm_keepParams."tfm_order=".$tfm_order."&tfm_orderby=";
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
<?php include '../admin_xx_includes/db_srch.php';?>
<table width="1000" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td align="center" class="red_bold"><?php echo $row_fin_cal_year['cal_year']; ?> CALENDAR YEAR INVOICES (Clubs only)</td>
    <td width="262" align="right"><input type="button" value="Return to previous page" onclick="history.go(-1)"/></td>
  </tr>
</table>
<table align="center" cellpadding="5" class="page">
  <tr>
    <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_id">Inv Number</a></td>
    <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_busname">Inv To</a></td>
    <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_to">Inv Contact</a></td>
    <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_paid_amount">Paid</a></td>
    <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_paid_date">Paid On</a></td>
    <td align="left"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_total_all">Inv Total</a></td>
    <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_status">Status</a></td>
    <td align="center"><a href="<?Php echo ($tfm_orderbyURL); ?>inv_cal_year">Calendar Year</a></td>
  </tr>
  <?php do { ?>
    <tr>
      <td align="center"><?php echo $row_inv_det['inv_id']; ?></td>
      <td align="left"><?php echo $row_inv_det['inv_busname']; ?></td>
      <td align="left"><?php echo $row_inv_det['inv_to']; ?></td>
      <td align="left"><?php echo $row_inv_det['inv_paid_amount']; ?></td>
      <td align="left"><?php echo $row_inv_det['inv_paid_date']; ?></td>
      <td align="left"><?php echo $row_inv_det['inv_total_all']; ?></td>
      <td align="center"><?php echo $row_inv_det['inv_status']; ?></td>
      <td align="center"><?php echo $row_inv_det['inv_cal_year']; ?></td>
    </tr>
    <?php } while ($row_inv_det = mysql_fetch_assoc($inv_det)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($inv_det);

mysql_free_result($fin_cal_year);
?>
