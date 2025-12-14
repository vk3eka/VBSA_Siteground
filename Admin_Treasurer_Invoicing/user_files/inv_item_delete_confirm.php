<?php require_once('../../Connections/connvbsa.php'); ?>
<?php

if (!isset($_SESSION)) {
  session_start();
}


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


$inv_id = "-1";
if (isset($_GET['inv_id'])) {
  $inv_id = $_GET['inv_id'];
}

$inv_type = "-1";
if (isset($_GET['inv_type'])) {
  $inv_type = $_GET['inv_type'];
}

$item_id = "-1";
if (isset($_GET['item_id'])) {
  $item_id = $_GET['item_id'];
}

$club_id = "-1";
if (isset($_GET['club_id'])) {
  $club_id = $_GET['club_id'];
}

mysql_select_db($database_connvbsa, $connvbsa);
$query_itemdel = "SELECT * FROM inv_items WHERE inv_item_id = '$item_id'";
$itemdel = mysql_query($query_itemdel, $connvbsa) or die(mysql_error());
$row_itemdel = mysql_fetch_assoc($itemdel);
$totalRows_itemdel = mysql_num_rows($itemdel);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="googlebot" content="noarchive,noindex,nofollow,nosnippet" />
<meta name="robots" content="noarchive,noindex,nofollow" />
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

</head>

<body>

<table width="1000" border="0" align="center">
  <tr>
    <td><img src="../../Admin_Images/Admin_Header.jpg" width="1000" height="140" /></td>
  </tr>
</table>

<form id="form1" name="form1" method="post" action="">
  <table align="center">
    <tr>
      <td align="right">&nbsp;</td>
      <td align="right"><span class="red_bold">
        <input type="button" value="Return to previous page" onclick="history.go(-1)"/>
      </span></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td align="left">&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Invoice Number:</td>
      <td align="left"><?php echo $inv_id; ?></td>
    </tr>
    <tr>
      <td align="right">Item ID:</td>
      <td align="left"><?php echo $item_id; ?></td>
    </tr>

    <tr>
      <td align="right" nowrap="nowrap">Invoice Type:</td>
      <td align="left" nowrap="nowrap"><?php echo $inv_type; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center" nowrap="nowrap">IF YOU PROCEED THIS ITEM WILL BE PERMANENTLY DELETED FROM THE DATABASE</td>
    </tr>
    <tr>
      <td colspan="2" align="center" class="red_bold">YOU CANNOT UNDO THIS ACTION</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">Do you wish to proceed?</td>
      <td class="greenbg"><a href="inv_item_delete.php?item_id=<?php echo $item_id; ?>&inv_id=<?php echo $inv_id; ?>&club_id=<?php echo $club_id ?>&inv_type=<?php echo $inv_type; ?>">Yes</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($itemdel);
?>
